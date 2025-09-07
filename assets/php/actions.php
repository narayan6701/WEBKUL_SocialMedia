<?php
require_once('functions.php');
require_once('send_code.php');

//for managing signup
if(isset($_GET['signup'])){
    $response = validateSignupForm($_POST);
    if($response['status']){
       if(createUser($_POST)){
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

//for managing login
if(isset($_GET['login'])){

    $response = validateLoginForm($_POST);
    
    $response = validateLoginForm($_POST);
    if($response['status']){
        $_SESSION['Auth'] = true;
        $_SESSION['userdata'] = $response['user'];
        if($response['user']['ac_status'] == '0'){
            $_SESSION['code'] = $code = rand(100000, 999999);
            sendCode($response['user']['email'], 'Verify Your Email', $code);
        }
        header("location: ../../");
    }
    else{
        $_SESSION['error'] = $response;
        $_SESSION['formdata'] = $_POST;
        header("location: ../../?login");
    }
}

if(isset($_GET['resent_code'])){
    $_SESSION['code'] = $code = rand(100000, 999999);
    sendCode($_SESSION['userdata']['email'], 'Verify Your Email', $code);
    header("location: ../../?resent");
}

if(isset($_GET['verify_email'])){
    $user_code = $_POST['code'];
    if($user_code == $_SESSION['code']){
       if(verifyEmail($_SESSION['userdata']['email'])) {
           header("location: ../../");
       }else{
              echo "Something is wrong";
       }
    }
    else{
        $response['msg']= "Invalid Verification Code";
        if($_POST['code'] == ''){
            $response['msg']= "Please Enter Verification Code";
        }
        $response['field'] = 'email_verify';
        $_SESSION['error'] = $response;
        header("location: ../../");

    }
}