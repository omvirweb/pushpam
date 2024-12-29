<?php

namespace App\Console\Commands;

use App\Models\Company;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\FleetFile;
use App\Models\Type;

class UploadJsonFiles extends Command
{
    protected $signature = 'json:upload';
    protected $description = 'Upload JSON files from the specified directory and process them.';

    public function handle()
    {
        $sourceDir = '/home/ftpuserpushpam/ftp';
        $processedDirBase = $sourceDir . DIRECTORY_SEPARATOR . 'uploaded';
        $batchDirName = date('Y-m-d_H-i-s');
        $processedDir = $processedDirBase . DIRECTORY_SEPARATOR . $batchDirName;

        $this->info("Scanning directory: $sourceDir");

        // Check if the source directory exists
        if (!is_dir($sourceDir)) {
            $this->error("Source directory does not exist: $sourceDir");
            return 1; // Return non-zero exit code for failure
        }

        $files = glob("$sourceDir/*.json");
        if (empty($files)) {
            $this->warn("No JSON files found in directory: $sourceDir");
            return 0; // No error, but no files found
        }

        foreach ($files as $file) {
            $this->info("Processing file: $file");
            try {
                $jsonData = json_decode(file_get_contents($file), true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $this->error("Invalid JSON format in file: $file");
                    continue;
                }
                $companyId = 0;
                $keys = array_keys($jsonData);

                if (isset($jsonData['Company Details']) && is_array($jsonData['Company Details'])) {
                    foreach ($jsonData['Company Details'] as $companyDetail) {
                        $companyCode = $companyDetail['Company Code'] ?? null;
                        $companyName = $companyDetail['Company Name'] ?? null;
                        $companyAddress = $companyDetail['Address'] ?? null;
                        if ($companyCode) {
                            $this->info("Processing company: $companyCode");
                            $company = Company::where('code', $companyCode)->first();
                            if ($company) {
                                $company->update([
                                    'name' => $companyName,
                                    'address' => $companyAddress,
                                ]);
                                $this->info("Company updated: $companyCode");
                            } else {
                                if ($companyName) {
                                    $company = Company::create([
                                        'code' => $companyCode,
                                        'name' => $companyName,
                                        'address' => $companyAddress,
                                    ]);
                                    $this->info("Company added: $companyCode");
                                }
                            }
                            $companyId = $company->id;
                        } else {
                            $this->error("Company Code missing in 'Company Details' for file: $file");
                        }
                    }
                }

                $type = isset($keys[1]) ? $keys[1] : null;
                $fileName = time() . '_' . basename($file);
                if ($type) {
                    $typeData = Type::where('name', $type)->first();
                    if ($typeData) {
                        ini_set('max_execution_time', 300);
                        ini_set('memory_limit', '512M');
                        $filePath = Storage::disk('public')->putFileAs('uploads', $file, $fileName);
                        FleetFile::create([
                            'file_name' => $fileName,
                            'type' => $typeData->id,
                            'company_id' => $companyId,
                        ]);
                    }
                } else {
                    $this->error("Type key not found after 'Company Details' in file: $file");
                }

                $this->info("File uploaded and database updated: $fileName");
                if (!is_dir($processedDir)) {
                    mkdir($processedDir, 0755, true);
                }
                rename($file, "$processedDir/" . basename($file));
                $this->info("Moved file to: $processedDir");
            } catch (\Exception $e) {
                $this->error("Error processing file $file: " . $e->getMessage());
            }
        }
        return 0;
    }
}
