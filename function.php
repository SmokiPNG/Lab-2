<?php

function setComment() {
    require("connection.php");
    if(isset($_POST['commentSubmit'])) {
            $date = $_POST['date'];
            $title = $_POST['message_title'];
            $message = $_POST['message_text'];
        if (!empty($title) && !empty ($message)) {
            $date = $_POST['date'];
            $title = $_POST['message_title'];
            $message = $_POST['message_text'];
            $query = "INSERT INTO messages (date, message_title, message_text) VALUES ('$date', '$title', '$message')";
            $result = mysqli_query($con, $query);
        }
        else {
            echo "<br><b><div class ='comment'>Please, fill out both boxes to submit your message.</div></b>";
        }
    }
}

function getComments($page_count) {
    require("connection.php");
    $query = "SELECT * FROM messages ORDER BY message_id DESC LIMIT 100";
    $result = mysqli_query($con, $query);
    while ($row = mysqli_fetch_assoc($result)) {   
        echo "<div class='comment'>
            <div> By <b>Anonymous</b> on <i>".$row['date']."</i><br><b>".$row['message_title']."</b></div>
            <div>".$row["message_text"]."</div>
            <div><br>  <form method='POST' action='".likeSubmit($row)."'>  <button type='submit' name='".$row['message_id']."' class='like_button'>Like</button>  Likes: ".$row["likes"]."</form></div>
        </div><br>";
    }
}

function likeSubmit($row) {
    require("connection.php");
    if(isset($_POST[$row['message_id']])) {
        $id = $row['message_id'];
        $likes = $row['likes']+1;
        $query = "UPDATE messages SET likes = '$likes' WHERE message_id = '$id'";
        $result = mysqli_query($con, $query);
        header('Location: index.php');
        exit;
    }
}
