<?php

namespace App\Console\Commands;

use App\Models\Company;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\FleetFile;
use App\Models\Type;

class UploadJsonFiles extends Command
{
    protected $signature = 'json:upload';
    protected $description = 'Upload JSON files from the specified directory and process them.';

    public function handle() {
        $sourceDir = '/home/ftpuserpushpam/ftp';
        $processedDirBase = '/home/ftpuserpushpam/uploaded';

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

        $existingFiles = Storage::files('public/uploads');
        $jsonFiles = array_filter($existingFiles, function ($file) {
            return Str::endsWith($file, '.json');
        });

        if (!empty($jsonFiles)) {
            Storage::delete($jsonFiles);
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

                        if ($companyName) {
                            $this->info("Processing company: $companyName");
                            $company = Company::where('name', $companyName)->first();

                            if ($company) {
                                if ($companyCode != null || $companyAddress != null) {
                                    if ($companyCode != $company->code || $companyAddress != $company->address)  {
                                        if ($companyCode != $company->code) {
                                            $company->code = $companyCode;
                                        }

                                        if ($companyAddress != $company->address)  {
                                            $company->address = $companyAddress;
                                        }

                                        $company->save();
                                    }
                                }

                                $this->info("Company updated: $companyCode");
                            } else {
                                $company = Company::create([
                                    'code' => $companyCode,
                                    'name' => $companyName,
                                    'address' => $companyAddress,
                                ]);

                                $this->info("Company added: $companyCode");
                            }

                            $companyId = $company->id;
                        } else {
                            $this->error("Company Name missing in 'Company Details' for file: $file");
                        }
                    }
                }

                $type = isset($keys[1]) ? $keys[1] : null;
                $fileName = date('d_m_y_h_i_A') . '_' . basename($file);

                if ($type) {
                    $typeData = Type::where('name', $type)->first();

                    if (!$typeData) {
                        $typeData = Type::create([
                            'name' => $type,
                        ]);
                    }

                    ini_set('max_execution_time', 300);
                    ini_set('memory_limit', '512M');
                    $filePath = Storage::disk('public')->putFileAs('uploads', $file, $fileName);

                    FleetFile::create([
                        'file_name' => $fileName,
                        'type' => $typeData->id,
                        'company_id' => $companyId,
                    ]);
                } else {
                    $this->error("Type key not found after 'Company Details' in file: $file");
                }

                // $this->info("File uploaded and database updated: $fileName");
                // $directory = '/home/ftpuserpushpam/ftp/uploaded/' . date('Y-m-d_H-i-s');
                // $executingUser = trim(shell_exec('whoami'));
                // $this->info("Executing user: $executingUser");
                // $this->info("Directory permissions: " . substr(sprintf('%o', fileperms('/home/ftpuserpushpam/ftp/uploaded')), -4));

                // if (!is_dir($processedDir)) {
                //     $this->info("Attempting to create directory: $processedDir");
                //     umask(002);
                //     $created = mkdir($processedDir, 0755, true);
                //     if (!$created) {
                //         $this->error("Failed to create directory: $processedDir");
                //         $this->error("Current permissions: " . substr(sprintf('%o', fileperms($processedDirBase)), -4));
                //     }
                // }

                // try {
                //     if (!is_writable(dirname($processedDir))) {
                //         throw new \Exception("Parent directory is not writable: " . dirname($processedDir));
                //     }
                //     mkdir($processedDir, 0755, true);
                //     $this->info("Successfully created directory: $processedDir");
                // } catch (\Exception $e) {
                //     $this->error("Failed to create directory: {$e->getMessage()}");
                // }

                if (!is_dir($processedDir)) {
                    mkdir($processedDir, 0755, true);
                }

                // if (!File::exists($processedDir)) {
                //     File::makeDirectory($processedDir, 0755, true);
                // }

                rename($file, "$processedDir/" . basename($file));
                $this->info("Moved file to: $processedDir");
            } catch (\Exception $e) {
                $this->error("Error processing file $file: " . $e->getMessage());
            }
        }

        return 0;
    }
}
