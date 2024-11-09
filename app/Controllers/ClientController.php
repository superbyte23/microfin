<?php

namespace App\Controllers;

use App\Models\Client;
use App\View;
use Illuminate\Http\Request;

class ClientController extends AuthController
{
    // Display a listing of the Clients
    public function index()
    {
      // Get all clients
      $clients = Client::all(); 

      return View::render('clients/index', ['clients' => $clients]);
    }

    // Show the form for creating a new Client
    public function create()
    { 

        return View::render('clients/add'); 
    }

    // Store a newly created Client in storage
    public function store()
    {
         
        // Create a new Client instance and assign the validated data
        $clientData = [
          'first_name' => $_POST['first_name'],
          'last_name' => $_POST['last_name'],
          'email' => $_POST['email'],
          'phone_number' => $_POST['phone_number'],
          'address' => $_POST['address'],
          'date_of_birth' => $_POST['date_of_birth'],
          'gender' => $_POST['gender'],
          'id_number' => $_POST['id_number'],
          'employment_status' => $_POST['employment_status'],
          'employer' => $_POST['employer'],
          'monthly_income' => $_POST['monthly_income'],
          'marital_status' => $_POST['marital_status'],
          'dependents' => $_POST['dependents'],
          'client_status' => $_POST['client_status'],
          'profile_image' => $_POST['profile_image'], // Handle file upload
          'notes' => $_POST['notes'],
        ];

        // Create the client
        Client::create($clientData);

        // Redirect or respond to the client
        // For example, redirect to a success page or back to the form
        header('Location: /'.ENTRY_POINT.'clients'); // Adjust the path as needed
        exit;
    }

    // Display the specified Client
    public function show($id)
    {

        // Find the client by ID
        $client = Client::find($id); 

        // Return a view or JSON response with the client details
        // return response()->json($client);
    }

    // Show the form for editing the specified Client
    public function edit($id)
    {

        // Find the client by ID
        $client = Client::find($id); 

        if (!$client) {
            return View::render('errors/404');
        }

        // Return a view with a form to edit the client
        return View::render('clients/edit', ['client' => $client]);
    }

    // Update the specified Client in storage
    public function update($id)
    { 
        
        $client = Client::find($id); 

        // dd($client);

        $client->first_name = $_POST['first_name'];
        $client->last_name = $_POST['last_name'];
        $client->email = $_POST['email'];
        $client->phone_number = $_POST['phone_number'];
        $client->address = $_POST['address'];
        $client->date_of_birth = $_POST['date_of_birth'];
        $client->gender = $_POST['gender'];
        $client->id_number = $_POST['id_number'];
        $client->employment_status = $_POST['employment_status'];
        $client->employer = $_POST['employer'];
        $client->monthly_income = $_POST['monthly_income'];
        $client->marital_status = $_POST['marital_status'];
        $client->dependents = $_POST['dependents'];
        $client->client_status = $_POST['client_status'];
        $client->notes = $_POST['notes'];

        // Update the client
        $client->save();
        header('Location: /'.ENTRY_POINT.'clients'); // Adjust the path as needed
        exit;
    }

    // Remove the specified Client from storage
    public function destroy($id)
    {
        // Find the client by ID
        $client = Client::find($id);

        // Delete the client
        $client->delete();

        // Return a response
        header('Location: /'.ENTRY_POINT.'clients'); // Adjust the path as needed
        return response()->json(['message' => 'Client deleted successfully']);
    }
}
