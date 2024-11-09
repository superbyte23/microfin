<?php

require "../bootstrap.php";

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('clients', function ($table) {
  $table->increments('id');                   // Primary key
  $table->string('first_name');               // First name
  $table->string('last_name');                // Last name
  $table->string('email')->unique();          // Email address
  $table->string('phone_number');             // Contact number
  $table->string('address');                  // Home address
  $table->date('date_of_birth');              // Date of birth
  $table->string('gender')->nullable();       // Gender (optional)
  $table->string('id_number')->unique();      // Government-issued ID (e.g., SSN, National ID)
  $table->string('employment_status');        // Employment status (e.g., Employed, Unemployed, Self-Employed)
  $table->string('employer')->nullable();     // Employer name (optional)
  $table->decimal('monthly_income', 15, 2);   // Monthly income
  $table->string('marital_status')->nullable(); // Marital status (Single, Married, etc.)
  $table->integer('dependents')->default(0);  // Number of dependents
  $table->string('customer_status')->default('active'); // Status (active, inactive, etc.)
  $table->string('profile_image')->nullable();  // Path to profile image (optional)
  $table->text('notes')->nullable();           // Optional notes about the customer
  $table->timestamps();                       // created_at, updated_at
});
