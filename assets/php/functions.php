<?php
require_once('config.php');
$db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME) or die('Database connection error');

//function for showing pages
function showPage($page, $data="") {
    include("assets/pages/$page.php");
}

//function for showing error message
function showError($field){
    if(isset($_SESSION['error'])){
        $error = $_SESSION['error'];
        if(isset($error['field']) && $field==$error['field']){
            ?>
                <div class="alert alert-danger my-2" role="alert">
                <?=$error['msg']?>
                </div>
            <?php
        }
    }
}

//function for showing previous form data
function showFormData($field){
    if(isset($_SESSION['formdata'])){
        $formdata = $_SESSION['formdata'];
       return $formdata[$field];
    }
    return '';
}

//for checking duplicate email
function isEmailRegistered($email){
    global $db;
    $query = "SELECT count(*) as row FROM users WHERE email='$email'";
    $run = mysqli_query($db, $query);
    $return_data = mysqli_fetch_assoc($run);
    return $return_data['row'];
}

//for checking duplicate username
function isUsernameRegistered($username){
    global $db;
    $query = "SELECT count(*) as row FROM users WHERE username='$username'";
    $run = mysqli_query($db, $query);
    $return_data = mysqli_fetch_assoc($run);
    return $return_data['row'];
}

//for checking duplicate username by other users
function isUsernameRegisteredByOther($username){
    global $db;
    $user_id = $_SESSION['userdata']['id'];
    $query = "SELECT count(*) as row FROM users WHERE username='$username' && id!=$user_id";
    $run = mysqli_query($db, $query);
    $return_data = mysqli_fetch_assoc($run);
    return $return_data['row'];
}

//for validating the signup form
function validateSignupForm($form_data){
    $response = array();
    $response['status'] = true;

    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
        $file_type = $_FILES['profile_pic']['type'];
        $file_size = $_FILES['profile_pic']['size'];
        if (!in_array($file_type, $allowed_types)) {
            $response['msg'] = 'Profile picture must be an image (jpg, png, webp)';
            $response['status'] = false;
            $response['field'] = 'profile_pic';
        } elseif ($file_size > 10 * 1024 * 1024) {
            $response['msg'] = 'Profile picture must be less than 10 MB';
            $response['status'] = false;
            $response['field'] = 'profile_pic';
        }
    }

    if(!$form_data['password']){
        $response['msg'] = 'Password is required';
        $response['status'] = false; 
        $response['field'] = 'password';
    }
    if(!$form_data['username']){
        $response['msg'] = 'Username is required';
        $response['status'] = false;
        $response['field'] = 'username';
    }
      if(!$form_data['email']){
        $response['msg'] = 'Email is required';
        $response['status'] = false;
        $response['field'] = 'email';
    }
      if(!$form_data['last_name']){
        $response['msg'] = 'Last name is required';
        $response['status'] = false;
        $response['field'] = 'last_name';
    }
    if(!$form_data['first_name']){
        $response['msg'] = 'First name is required';
        $response['status'] = false;
        $response['field'] = 'first_name';
    }
    if(empty($form_data['dob'])){
        $response['msg'] = 'Date of birth is required';
        $response['status'] = false;
        $response['field'] = 'dob';
    }
     if(isEmailRegistered($form_data['email'])){
        $response['msg'] = 'Email id is already registered';
        $response['status'] = false;
        $response['field'] = 'email';
    }
     if(isUsernameRegistered($form_data['username'])){
        $response['msg'] = 'Username is already taken';
        $response['status'] = false;
        $response['field'] = 'username';
    }
    return $response;
}

//for creating new user
function createUser($data, $image_data){
    global $db;

    $first_name = mysqli_real_escape_string($db, $data['first_name']);
    $last_name = mysqli_real_escape_string($db, $data['last_name']);
    $gender =  $data['gender'];
    $dob = mysqli_real_escape_string($db, $data['dob']);
    $email = mysqli_real_escape_string($db, $data['email']);
    $username = mysqli_real_escape_string($db, $data['username']);
    $password = mysqli_real_escape_string($db, $data['password']);
    $password = password_hash($password, PASSWORD_BCRYPT); // password encryption

    // Initialize query components
    $columns = "first_name, last_name, gender, dob, email, username, password";
    $values = "'$first_name', '$last_name', '$gender', '$dob', '$email', '$username', '$password'";

    // Handle image if provided
    if (!empty($image_data['name'])) {
        $image = time() . basename($image_data['name']);
        move_uploaded_file($image_data['tmp_name'], "../images/profile/" . $image);
        $columns .= ", profile_pic";
        $values .= ", '$image'";
    }

    // Final query
    $query = "INSERT INTO users ($columns) VALUES ($values)";
    return mysqli_query($db, $query);
}


//for validating the login
function validateLoginForm($form_data){
    $response = array();
    $response['status'] = true;
    $blank=false;

    if(!$form_data['password']){
        $response['msg'] = 'Password is required';
        $response['status'] = false; 
        $response['field'] = 'password';
        $blank=true;
    }
    if(!$form_data['username_email']){
        $response['msg'] = 'Username/email is required';
        $response['status'] = false;
        $response['field'] = 'username_email';
        $blank=true;
    }
    if(!$blank && !checkUser($form_data)['status']){
        $response['msg'] = 'something is incorrect, we can\'t find you';
        $response['status'] = false;
        $response['field'] = 'checkuser';
    }
    else{
        $response['user'] = checkUser($form_data)['user'];
    }
    return $response;
}

//for checking the user
function checkUser($login_data){
    global $db;
    $username_email = $login_data['username_email'];
    $entered_password = $login_data['password'];

    // Use prepared statement to prevent SQL injection
    $query = "SELECT * FROM users WHERE username=? OR email=?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ss", $username_email, $username_email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    $data['user'] = $user ?? [];

    // Verify password using bcrypt
    if ($user && password_verify($entered_password, $user['password'])) {
        $data['status'] = true;
    } else {
        $data['status'] = false;
        $data['user'] = []; // Clear user data if password fails
    }

    return $data;
}

// for getting user data by id
function getUser($user_id){
    global $db;
    
    $query = "SELECT * FROM users WHERE id=$user_id";
    $run = mysqli_query($db, $query);
    return mysqli_fetch_assoc($run);  
}

// function for verifying email
function verifyEmail($email){
   global $db;
    $query = "UPDATE users SET ac_status=1 WHERE email='$email'";
    return mysqli_query($db, $query);
}

 // Age calculation function
    function calculateAge($dob) {
        if (empty($dob)) return '';
        $dobObj = new DateTime($dob);
        $today = new DateTime();
        return $today->diff($dobObj)->y;
    }

//for validating update form
function validateUpdateForm($form_data, $image_data){
    $response = array();
    $response['status'] = true;

    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
        $file_type = $_FILES['profile_pic']['type'];
        $file_size = $_FILES['profile_pic']['size'];
        if (!in_array($file_type, $allowed_types)) {
            $response['msg'] = 'Profile picture must be an image (jpg, png, webp)';
            $response['status'] = false;
            $response['field'] = 'profile_pic';
        } elseif ($file_size > 10 * 1024 * 1024) {
            $response['msg'] = 'Profile picture must be less than 10 MB';
            $response['status'] = false;
            $response['field'] = 'profile_pic';
        }
    }

    if(!$form_data['username']){
        $response['msg'] = 'Username is required';
        $response['status'] = false;
        $response['field'] = 'username';
    }
      if(!$form_data['last_name']){
        $response['msg'] = 'Last name is required';
        $response['status'] = false;
        $response['field'] = 'last_name';
    }
    if(!$form_data['first_name']){
        $response['msg'] = 'First name is required';
        $response['status'] = false;
        $response['field'] = 'first_name';
    }
    if(empty($form_data['dob'])){
        $response['msg'] = 'Date of birth is required';
        $response['status'] = false;
        $response['field'] = 'dob';
    }
     if(isUsernameRegisteredByOther($form_data['username'])){
        $response['msg'] = $form_data['username']." is already taken";
        $response['status'] = false;
        $response['field'] = 'username';
    }


    return $response;
}

//function for updating profile
function updateProfile($data, $image_data){
    global $db;

    $first_name = mysqli_real_escape_string($db, $data['first_name']);
    $last_name = mysqli_real_escape_string($db, $data['last_name']);
    $gender_input = strtolower(trim($data['gender']));
switch ($gender_input) {
    case 'option1':
        $gender = 1;
        break;
    case 'option2':
        $gender = 2;
        break;
    case 'option0':
        $gender = 0;
        break;
    default:
        $gender = 1;
}

    $dob = mysqli_real_escape_string($db, $data['dob']);
    $username = mysqli_real_escape_string($db, $data['username']);
    $raw_password = mysqli_real_escape_string($db, $data['password']);

    // Handle password update
    if (!empty($raw_password)) {
        $password = password_hash($raw_password, PASSWORD_BCRYPT);
        $_SESSION['userdata']['password'] = $password;
    } else {
        $password = $_SESSION['userdata']['password'];
    }

    // Handle profile picture upload
    $set_clause = "first_name='$first_name', last_name='$last_name', gender='$gender', dob='$dob', username='$username', password='$password'";

    if (!empty($image_data['name'])) {
        $image = time() . basename($image_data['name']);
        move_uploaded_file($image_data['tmp_name'], "../images/profile/" . $image);
        $set_clause .= ", profile_pic='$image'";
    }

    $query = "UPDATE users SET $set_clause WHERE id=" . $_SESSION['userdata']['id'];

    return mysqli_query($db, $query);
}



?>