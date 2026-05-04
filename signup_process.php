<?php

session_start();
include('config.php');

// 🔥 SHOW ERRORS (REMOVE IN PRODUCTION)
error_reporting(E_ALL);
ini_set('display_errors', 1);

if(isset($_POST['signup_btn']))
{
    $user_namee = $_POST['username'];
    $email_address = $_POST['email'];
    $password_input = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // ✅ Check password match
    if($password_input !== $confirm_password)
    {
        header('location: create-account.php?error_message=Passwords do not match');
        exit;
    }

    // Generate values
    $full_name = full_name($email_address);
    $user_name = $user_namee;

    $user_type = "1";
    $facebook = "www.facebook.com";
    $whatsapp = "www.webwhatsapp.com";
    $bio = "tell us more about you";

    $fallowers = 0;
    $fallowing = 0;
    $post_count = 0;
    $image = "default.png";

    // ✅ Secure password
    $encrypted_password = password_hash($password_input, PASSWORD_DEFAULT);

    // ✅ TEMP: disable domain restriction (you can re-enable later)
    // if(domain_validator($email_address) == 1)
    if(true)
    {
        // ✅ Check if email exists
        $stmt = $conn->prepare("SELECT User_ID FROM USERS WHERE EMAIL = ?");
        $stmt->bind_param("s", $email_address);
        $stmt->execute();
        $stmt->store_result();

        if($stmt->num_rows > 0)
        {    
            $stmt->close();
            header('location: create-account.php?error_message=Email already registered');
            exit;
        }

        $stmt->close(); // ✅ VERY IMPORTANT

        // ✅ Insert user
        $stmt = $conn->prepare("INSERT INTO USERS 
        (FULL_NAME, USER_NAME, USER_TYPE, PASSWORD_S, EMAIL, IMAGE, FACEBOOK, WHATSAPP, BIO, FALLOWERS, FALLOWING, POSTS) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param("ssssssssiiii",
            $full_name,
            $user_name,
            $user_type,
            $encrypted_password,
            $email_address,
            $image,
            $facebook,
            $whatsapp,
            $bio,
            $fallowers,
            $fallowing,
            $post_count
        );

        if($stmt->execute())
        {
            $user_id = $conn->insert_id;

            // AUTO LOGIN
            $_SESSION['id'] = $user_id;
            $_SESSION['username'] = $user_name;
            $_SESSION['fullname'] = $full_name;
            $_SESSION['img_path'] = $image;
            $_SESSION['usertype'] = $user_type;

            // redirect to tag selection
            header("location: select-tags.php");
            exit;
        }
        else
        {
            // 🔥 SHOW REAL ERROR
            die("Insert failed: " . $stmt->error);
        }
    }
    else
    {
        header("location: create-account.php?error_message=Invalid email domain");
        exit;
    }
}


// FUNCTIONS

function userName()
{
    return "user_" . rand(1000,9999);
}

function full_name($email)
{   
    return strstr($email, '@', true);
}

function domain_validator($email)
{    
    $acceptedDomains = array('gmail.com');
    $domain = substr(strrchr($email, "@"), 1);

    return in_array($domain, $acceptedDomains);
}

?>