<?php
session_start(); // initialize the page with session

include("db.php"); // includes all the db variables here

$errors = []; // initializes errorrs as empty

if($_SERVER['REQUEST_METHOD'] == 'POST'){ // checks which method is used when submitting the form
    $username = $_POST['username']; // stored the user input to variable $username
    $password = $_POST['password']; // stored the user input to variable $password


    if(empty($username)){
        $errors[] = "Username is required"; // saves the error to $errors array
    }

    $sql = "SELECT * FROM `users` WHERE `username` = '$username'"; // constructing the SQL query for checking if username exists on the database
    $result = $conn->query($sql); // executing the query command
 
    if($result->num_rows == 1){ // checks if there exist a username
        $user = mysqli_fetch_assoc($result); // made a user variable to store the fetch user info (array) from the database
    
        if(password_verify($password, $user['password'])){ // password verify from the user input and cross check from the password coming from the database
            $_SESSION['username'] = $username; // created a session to use in dashboard
            header("Location: dashboard.php"); // redirect location to dashboard
            exit(); // terminate from this point
        } else {
            $errors[] = "Incorrect Credentials"; // store an error
        }
    }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <h3>Login System</h3>
    <form method="POST">
        <label for="Username">Enter your username
            <input type="text" name="username" required>
        </label>
        <br><br>
        <label for="Password">Enter your password
            <input type="password" name="password" required>
        </label>
         <br><br>
        <button type="submit">Submit</button>
    </form>
    <p style="color:red">
        <?php 

            foreach($errors as $error){
                echo $error . "<br>";
            }

        ?>
    </p>

  
</body>
</html>