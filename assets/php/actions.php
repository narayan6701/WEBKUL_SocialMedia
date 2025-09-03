<?php
require_once('functions.php');
if(isset($_GET['signup'])){
    $response = validateSignupForm($_POST);
    if($response['status']){
       if(createUser($_POST)){
        echo "<script>alert('User registered successfully');</script>";
        unset($_SESSION['formdata']);
        header("location: ../../?login&newuser");
       }
       else{
        echo "<script>alert('User registration failed');</script>";
       }
    }
    else{
        $_SESSION['error'] = $response;
        $_SESSION['formdata'] = $_POST;
        header("location: ../../?signup");
    }
}