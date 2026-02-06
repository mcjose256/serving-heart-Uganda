<?php
/**
 * Volunteer Application Processor
 * Handling submissions from get-involved.php
 */

// 1. Error reporting and session start
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Include database and helper functions
require_once 'includes/db.php';

// 3. Check if form was submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 4. Sanitize and Collect Input Data
    // We match the 'name' attributes from the form on get-involved.php
    $full_name  = sanitize($_POST['full_name'] ?? '');
    $email      = sanitize($_POST['email'] ?? '');
    $phone      = sanitize($_POST['phone'] ?? '');
    $skills     = sanitize($_POST['skills'] ?? '');
    $motivation = sanitize($_POST['motivation'] ?? '');
    
    // Additional fields if present in your form
    $gender       = sanitize($_POST['gender'] ?? 'other');
    $availability = sanitize($_POST['availability'] ?? 'flexible');

    // 5. Basic Validation
    if (empty($full_name) || empty($email) || empty($motivation)) {
        setFlashMessage('danger', 'Please fill in all required fields (Name, Email, and Motivation).');
        redirect('get-involved.php#volunteer');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        setFlashMessage('danger', 'Please provide a valid email address.');
        redirect('get-involved.php#volunteer');
    }

    // 6. Database Insertion
    try {
        $sql = "INSERT INTO volunteers (
                    full_name, 
                    email, 
                    phone, 
                    gender,
                    skills, 
                    availability,
                    motivation, 
                    application_status, 
                    created_at
                ) VALUES (
                    :full_name, 
                    :email, 
                    :phone, 
                    :gender,
                    :skills, 
                    :availability,
                    :motivation, 
                    'pending', 
                    NOW()
                )";

        $params = [
            'full_name'    => $full_name,
            'email'        => $email,
            'phone'        => $phone,
            'gender'       => $gender,
            'skills'       => $skills,
            'availability' => $availability,
            'motivation'   => $motivation
        ];

        $result = executeQuery($pdo, $sql, $params);

        if ($result) {
            // Success Message
            setFlashMessage('success', 'Thank you, ' . $full_name . '! Your application has been submitted successfully. Our team will review it and contact you soon.');
            redirect('get-involved.php#volunteer');
        } else {
            throw new Exception("Execution failed.");
        }

    } catch (Exception $e) {
        // Log error and notify user
        error_log("Volunteer Submission Error: " . $e->getMessage());
        setFlashMessage('danger', 'Sorry, something went wrong. Please try again later or contact us directly via email.');
        redirect('get-involved.php#volunteer');
    }

} else {
    // If someone tries to access this file directly without POST
    redirect('get-involved.php');
}