<?php

    session_start();
    include("config.php");

    if (isset($_POST['fallow'])) {

        $user_id = $_SESSION['id'];
        $following_person = $_POST['fallow_person'];

        // ❌ prevent self-follow
        if ($user_id == $following_person) {
            header("Location: home.php");
            exit;
        }

        // 🔍 CHECK IF ALREADY FOLLOWING
        $check = $conn->prepare("SELECT * FROM fallowing WHERE User_ID = ? AND Other_user_id = ?");
        $check->bind_param("ii", $user_id, $following_person);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {

            // ❌ UNFOLLOW
            $delete = $conn->prepare("DELETE FROM fallowing WHERE User_ID = ? AND Other_user_id = ?");
            $delete->bind_param("ii", $user_id, $following_person);
            $delete->execute();

            // 🔻 update counts
            remove_Fallowers($following_person);
            remove_Fallowing($user_id);

            $_SESSION['fallowing'] = max(0, $_SESSION['fallowing'] - 1);

        } else {

            // ✅ FOLLOW
            $stmt = $conn->prepare("INSERT INTO fallowing(User_ID, Other_user_id) VALUES (?, ?)");
            $stmt->bind_param("ii", $user_id, $following_person);

            if ($stmt->execute()) {

                update_Fallowers($following_person);
                update_Fallowing($user_id);

                $_SESSION['fallowing'] = $_SESSION['fallowing'] + 1;

            } else {
                echo "Insert Error: " . $stmt->error;
                exit;
            }
        }

        header("Location: home.php");
        exit;

    } else {
        header("Location: home.php");
        exit;
    }


/* ================= UPDATE FUNCTIONS ================= */

    function update_Fallowing($user_id)
    {
        include("config.php");
        $stmt = $conn->prepare("UPDATE users SET FALLOWING = FALLOWING + 1 WHERE User_ID = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
    }

    function update_Fallowers($other_Person)
    {
        include("config.php");
        $stmt = $conn->prepare("UPDATE users SET FALLOWERS = FALLOWERS + 1 WHERE User_ID = ?");
        $stmt->bind_param("i", $other_Person);
        $stmt->execute();
    }

    /* 🔻 NEW FUNCTIONS */

    function remove_Fallowing($user_id)
    {
        include("config.php");
        $stmt = $conn->prepare("UPDATE users SET FALLOWING = GREATEST(FALLOWING - 1, 0) WHERE User_ID = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
    }

    function remove_Fallowers($other_Person)
    {
        include("config.php");
        $stmt = $conn->prepare("UPDATE users SET FALLOWERS = GREATEST(FALLOWERS - 1, 0) WHERE User_ID = ?");
        $stmt->bind_param("i", $other_Person);
        $stmt->execute();
    }
?>