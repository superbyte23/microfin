<?php

namespace App\Controllers;

use App\Models\User;
use App\View;
use Illuminate\Http\Request;

class UserController extends AuthController
{
    // Display a listing of the users
    public function index()
    {
      // Get all users
      $users = User::all(); 

      return View::render('users/index', ['users' => $users]);
    }

    // Show the form for creating a new user
    public function create()
    { 

        return View::render('users/user_form'); 
    }

    // Store a newly created user in storage
    public function store()
    {
         
        // Create a new user instance and assign the validated data
        $userData = [];

        // Create the user
        User::create($userData);

        // Redirect or respond to the user
        // For example, redirect to a success page or back to the form
        header('Location: /'.ENTRY_POINT.'users'); // Adjust the path as needed
        exit;
    }

    // Display the specified user
    public function show($id)
    {

        // Find the user by ID
        $user = User::find($id); 

        // Return a view or JSON response with the user details
        // return response()->json($user);
    }

    // Show the form for editing the specified user
    public function edit($id)
    {

        // Find the user by ID
        $user = User::find($id); 
        // Return a view with a form to edit the user
        return View::render('users/edit', ['user' => $user]);
    }

    // Update the specified user in storage
    public function update($id)
    { 
        
        $user = User::find($id);  
        // Update the user
        $user->save();
        header('Location: /'.ENTRY_POINT.'users'); // Adjust the path as needed
        exit;
    }

    // Remove the specified user from storage
    public function destroy($id)
    {
        // Find the user by ID
        $user = User::find($id); 
        // Delete the user
        $user->delete(); 
        // Return a response
        header('Location: /'.ENTRY_POINT.'users'); // Adjust the path as needed
        return response()->json(['message' => 'user deleted successfully']);
    }
}
