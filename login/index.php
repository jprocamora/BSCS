<?php
include("db.php");

$errors = [];

if($_SERVER['REQUEST_METHOD'] == 'POST'){ // check which method is used when submitting the form
    $username = $_POST['username']; // stores the user input to variable $username
    $password = $_POST['password']; // stores the user input to variable $password
    $confirmPassword = $_POST['confirm_password']; // // stores the user input to variable $confirmPassword

    if(strlen($username) <= 8){ // ensuring that the username must be 8 or more characters long
        $errors[] = "Username must be equal or greater than 8 characters"; // saves the error to $errors array
    } else if(empty($username)){ // check if the user inputs an empty field on username input
        $errors[] = "Username is required"; // saves the error to $errors array
    }

    $sql = "SELECT `username` FROM `users` WHERE `username` = '$username'"; // constructing the SQL query for checking if username exists on the database
    $result = $conn->query($sql); // executing the query command

    if($result->num_rows > 0){ // ensuring that there is no similar username on the database
        $errors[] = "username already exist"; // saves the error to $errors array
    }

    $pattern = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*[\W_]).+$/'; // pattern to check that password must contain lower, uppercase, special characters

    if($password != $confirmPassword){ // checking the password and confirm password if they match
        $errors[] = "Password did not match"; // saves the error to $errors array
    }
    
    if(!preg_match($pattern, $password)){ // checking if the password meets the set pattern
        $errors[] = "Password must contain at least lowercase, uppercase, special chars"; // saves the error to $errors array
    }

    $passwordHashed = password_hash($password, PASSWORD_DEFAULT); // encrypting the password

    if(empty($errors)){ // checks if the errors array is empty
        $sql = "INSERT INTO `users` (`username`, `password`) VALUES ('$username', '$passwordHashed')"; // constructing the SQL query for inserting values to the database
        $conn->query($sql); // executing the query
        echo "Registered Successfully!"; // print the message
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
            <input type="text" name="username" required value="<?php echo (isset($_POST['username'])) ? $_POST['username'] : "" ;?>">
        </label>
        <br><br>
        <label for="Password">Enter your password
            <input type="password" name="password">
        </label>
         <br><br>
        <label for="Confirm Password">Confirm your password
            <input type="password" name="confirm_password">
        </label>
        <br><br>
        <button type="submit">Register</button>
    </form>
    <p style="color:red">
    <?php
        foreach($errors as $error){
            echo $error."<br>";        
        }

    ?>

    </p>

<?php

    // /^ the beginning;
    // $/ the end;
    // ?= check ahead or check the word
    // .* if there is any
    // [A-Z] if the word or phrase contains letters uppercase A-Z
    // \W all special chars but this does not include the underscore
    // .+ check ahead (all)


?>
</body>
</html>

