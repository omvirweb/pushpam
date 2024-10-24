<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    // public function autocomplete(Request $request)
    // {
    //     $search = $request->get('query');
    //     $results = Customer::where('name', 'LIKE', "%{$search}%")->get();

    //     return response()->json($results);
    // }

    public function createCustomer(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:customers',
            // Add more validation rules as needed
        ]);

        $customer = Customer::create($validatedData);

        return response()->json($customer);
    }
    public function index(Request $request)
    {
        $search = $request->input('search');
        $customers = Customer::where('name', 'like', "%{$search}%")
                             ->get(['id', 'name as text']);

        return response()->json($customers);
    }

    public function store(Request $request)
    {
        $name = $request->input('name');
        $customer = Customer::firstOrCreate(['name' => $name]);

        return response()->json(['id' => $customer->id, 'text' => $customer->name]);
    }
    
    // public function submitForm(Request $request)
    // {
    //     $customerId = $request->input('customer_id');

    //     // Perform actions with the customer ID, e.g., save to database
    //     // Example: Saving to a specific table
    //     $customer = new Customer();
    //     $customer->customer_id = $customerId;
    //     $customer->save();

    //     return response()->json(['message' => 'Form submitted successfully!']);
    // }
    public function submitForm(Request $request)
    {
        // Log received data for debugging
        Log::info('Received data:', $request->all());

        $customerId = $request->input('customer_id');
        $customerName = $request->input('customer_name');

        try {
            // Perform actions with the customer ID and name
            $customer = new Customer();
            $customer->id = $customerId;
            $customer->name = $customerName;
            $customer->save();

            return response()->json(['message' => 'Form submitted successfully!']);
        } catch (\Exception $e) {
            Log::error('Error submitting form:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Error submitting form.'], 500);
        }
    }

}
