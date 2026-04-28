<?php
// Run this once to create the lobby_messages table
// Or add to your Database.sql file

session_start();
include("../config.php");

$sql = "CREATE TABLE IF NOT EXISTS lobby_messages (
    Message_ID INT PRIMARY KEY AUTO_INCREMENT,
    Lobby_ID INT NOT NULL,
    User_ID INT NOT NULL,
    Message_Text LONGTEXT NOT NULL,
    Created_At TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (Lobby_ID) REFERENCES lobbies(Lobby_ID) ON DELETE CASCADE,
    FOREIGN KEY (User_ID) REFERENCES users(User_ID) ON DELETE CASCADE,
    INDEX (Lobby_ID),
    INDEX (Created_At)
)";

if ($conn->query($sql) === TRUE) {
    echo "Table lobby_messages created successfully!";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>
