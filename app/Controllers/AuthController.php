<?php

namespace App\Controllers;

use App\Models\User;
use App\View;

class AuthController
{

    public function __construct() {

        if (!isset($_SESSION['user_id'])) {
            // return View::render('auth/login');
        }

    }

    public function index()
    {
        $users = User::all();
        return View::render('home', ['users' => $users]);
    }

    public function logout()
    {
        session_destroy();
        header('location: login');
    }

    public function login()
    {
        return View::render('Auth/login');
    }

    public function register()
    {
        return View::render('Auth/register');
    }

    public function authenticate()
    {     
        header('Content-Type: application/json');
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        if($data === null){
          echo json_encode(['status' => 'error', 'message' => 'Invalid  JSON']);
          exit;
        } 

        $email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
        $password = $data['password'];  
        
        // Debugging: Log received data
        error_log('Received data: ' . print_r($data, true));
          
        $user = User::where('email', $email)->first();

        // if (!$user) {
        //     echo json_encode(['login' => 'failed' , 'message' => 'User Account not found!']);
        //      exit;
        // }

        if ($user && password_verify($password, $user->password)) {
            // Password is correct, log in the user
            $_SESSION['user_id'] = $user->id;
            $_SESSION['isLoggedIn'] = true; 
            echo json_encode(['login' => 'success']);
            exit;
        } else { 
            echo json_encode(['login' => 'failed', 'message' => 'Invalid Username and Password']);
        } 

    }
}
