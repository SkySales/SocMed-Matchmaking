<?php

session_start();
include('config.php');

// 🔥 SHOW ERRORS (for debugging)
error_reporting(E_ALL);
ini_set('display_errors', 1);

if(isset($_POST['button']))
{
    $email = $_POST['email'];
    $password = $_POST['password'];

    // ✅ Prepare query (SAFE)
    $stmt = $conn->prepare("SELECT 
        User_ID, FULL_NAME, USER_NAME, USER_TYPE, EMAIL, PASSWORD_S, IMAGE, FACEBOOK, WHATSAPP, BIO, FALLOWERS, FALLOWING, POSTS 
        FROM USERS 
        WHERE USER_NAME = ? OR EMAIL = ?");

    $stmt->bind_param("ss", $email, $email);
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows > 0)
    {
        $stmt->bind_result(
            $user_id, 
            $full_name, 
            $user_name, 
            $user_type, 
            $email_address, 
            $hashed_password,
            $image, 
            $facebook, 
            $whatsapp, 
            $bio, 
            $fallowers, 
            $fallowing, 
            $post_count
        );

        $stmt->fetch();

        // ✅ VERIFY PASSWORD (THIS IS THE FIX)
        if(password_verify($password, $hashed_password))
        {
            $_SESSION['id'] = $user_id;
            $_SESSION['username'] = $user_name;
            $_SESSION['fullname'] = $full_name;
            $_SESSION['email'] = $email_address;
            $_SESSION['usertype'] = $user_type;
            $_SESSION['facebook'] = $facebook;
            $_SESSION['whatsapp'] = $whatsapp;
            $_SESSION['bio'] = $bio;
            $_SESSION['fallowers'] = $fallowers;
            $_SESSION['fallowing'] = $fallowing;
            $_SESSION['postcount'] = $post_count;
            $_SESSION['img_path'] = $image;

            header("location: home.php");
            exit;
        }
        else
        {
            header("location: login.php?error_message=Wrong Password");
            exit;
        }
    }
    else
    {
        header("location: login.php?error_message=User not found");
        exit;
    }
}
else
{
    header("location: login.php?error_message=Invalid Request");
    exit;
}
?>