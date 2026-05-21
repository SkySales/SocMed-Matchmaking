<?php
session_start();
include("config.php");

if(!isset($_SESSION['id'])){
    header("location: login.php");
    exit;
}

if(isset($_POST['submit'])){

    $user_id = $_SESSION['id'];

    // FORM VALUES
    $full_name = trim($_POST['full_name']);
    $user_name = trim($_POST['user_name']);
    $email = trim($_POST['email']);
    $bio = trim($_POST['bio']);
    $facebook = trim($_POST['fbook']);
    $whatsapp = trim($_POST['wapp']);
    $gender = trim($_POST['gender']);
    $birthdate = trim($_POST['birthdate'] ?? '');
    $age = !empty($_POST['age']) ? (int)$_POST['age'] : NULL;

    // CURRENT IMAGE
    $image_name = $_SESSION['img_path'];

    // IMAGE UPLOAD
    if(isset($_FILES['profile_img']) && $_FILES['profile_img']['error'] == 0){

        $allowed = ['jpg','jpeg','png','webp'];

        $file_name = $_FILES['profile_img']['name'];
        $file_tmp  = $_FILES['profile_img']['tmp_name'];

        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if(in_array($ext, $allowed)){

            $new_name = time() . "_" . rand(1000,9999) . "." . $ext;

            $upload_path = "assets/images/profiles/" . $new_name;

            move_uploaded_file($file_tmp, $upload_path);

            $image_name = $new_name;
        }
    }

    // UPDATE DATABASE
    $stmt = $conn->prepare("
        UPDATE users SET
            FULL_NAME=?,
            USER_NAME=?,
            EMAIL=?,
            BIO=?,
            FACEBOOK=?,
            WHATSAPP=?,
            Gender=?,
            AGE=?,
            Birthdate=?,
            IMAGE=?
        WHERE User_ID=?
    ");

    $stmt->bind_param(
        "sssssssissi",
        $full_name,
        $user_name,
        $email,
        $bio,
        $facebook,
        $whatsapp,
        $gender,
        $age,
        $birthdate,
        $image_name,
        $user_id
    );

    if($stmt->execute()){

        // UPDATE SESSION
        $_SESSION['fullname'] = $full_name;
        $_SESSION['username'] = $user_name;
        $_SESSION['email'] = $email;
        $_SESSION['bio'] = $bio;
        $_SESSION['facebook'] = $facebook;
        $_SESSION['whatsapp'] = $whatsapp;
        $_SESSION['gender'] = $gender;
        $_SESSION['age'] = $age;
        $_SESSION['birthdate'] = $birthdate;
        $_SESSION['img_path'] = $image_name;

        header("location: my_Profile.php");
        exit;

    } else {

        echo "Failed to update profile.";

    }

}
?>