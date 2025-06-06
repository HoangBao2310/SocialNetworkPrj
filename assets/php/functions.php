<?php

require_once 'config.php';
$db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME) or die("database is not connected");

//show page
function showPage($page, $data = "")
{
    include("assets/pages/$page.php");
}

//show error
function showError($field)
{
    if (isset($_SESSION['error'])) {
        $error = $_SESSION['error'];
        if (isset($error['field']) && $field == $error['field']) {
?>
            <div class="alert alert-danger" role="alert">
                <?= $error['msg'] ?>
            </div>
<?php
        }
    }
}

// show prev formdata
function showFormData($field)
{
    if (isset($_SESSION['formdata'])) {
        $formdata = $_SESSION['formdata'];
        return $formdata[$field];
    }
}

// checking duplicate email
function isEmailRegistered($email)
{
    global $db;
    $query = "SELECT count(*) as row FROM users WHERE email='$email'";
    $run = mysqli_query($db, $query);
    $return_data = mysqli_fetch_assoc($run);
    return $return_data['row'];
}
// checking duplicate email
function isUsernameRegistered($username)
{
    global $db;
    $query = "SELECT count(*) as row FROM users WHERE username='$username'";
    $run = mysqli_query($db, $query);
    $return_data = mysqli_fetch_assoc($run);
    return $return_data['row'];
}

// validate signup form
function validateSignupForm($form_data)
{
    $response = array();
    $response['status'] = true;
    if (!$form_data['email']) {
        $response['msg'] = "Email is not given";
        $response['status'] = false;
        $response['field'] = 'email';
    }
    if (!$form_data['username']) {
        $response['msg'] = "Username name is not given";
        $response['status'] = false;
        $response['field'] = 'username';
    }
    if (!$form_data['password']) {
        $response['msg'] = "Password is not given";
        $response['status'] = false;
        $response['field'] = 'password';
    }
    if (!$form_data['last_name']) {
        $response['msg'] = "Last name is not given";
        $response['status'] = false;
        $response['field'] = 'last_name';
    }
    if (!$form_data['first_name']) {
        $response['msg'] = "First name is not given";
        $response['status'] = false;
        $response['field'] = 'first_name';
    }
     if (isUsernameRegistered($form_data['username'])) {
        $response['msg'] = "Username is already registered";
        $response['status'] = false;
        $response['field'] = 'username';
    }

    if (isEmailRegistered($form_data['email'])) {
        $response['msg'] = "Email is already registered";
        $response['status'] = false;
        $response['field'] = 'email';
    }
   

    return $response;
}

//creating new user
function createUser($data)
{
    global $db;
    $first_name = mysqli_real_escape_string($db, $data['first_name']);
    $last_name = mysqli_real_escape_string($db, $data['last_name']);
    $gender = $data['gender'];
    $email = mysqli_real_escape_string($db, $data['email']);
    $username = mysqli_real_escape_string($db, $data['username']);
    $password = mysqli_real_escape_string($db, $data['password']);
    $password = md5($password);

    $query = "INSERT INTO users(first_name,last_name,gender,email,username,password) 
              VALUES ('$first_name','$last_name','$gender','$email','$username','$password')";
    return mysqli_query($db, $query);
}
