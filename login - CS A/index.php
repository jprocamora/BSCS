<?php


include("db.php");

$errors = [];

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $cofirmPassword = $_POST['confirm_password'];

    if(empty($username)){
        $errors[] = "Username is required";
    } else if (strlen($username) < 8){
        $errors[] = "Username must be 8 or more characters long";
    }

    $sql = "SELECT `username` FROM `users` WHERE `username` = '$username'";
    $userCheck = $conn->query($sql);

    if($userCheck->num_rows > 0){
        $errors[] = "Username already exist";
    }

    $passworPattern = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*[\W_]).{8,}$/';

    if($password != $cofirmPassword){
        $errors[] = "Password did not match";
    } 

    if(!preg_match($passworPattern, $password)){
        $errors[] = "Password must contain lower and upper case with number(s)";
    } 

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    if(empty($errors)){
        $sql = "INSERT INTO `users` (`username`, `password`) VALUES ('$username', '$hashedPassword')";

        $result = $conn->query($sql);

        if($result){
            header("Location: login.php");
            exit();
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
                echo $error . "<br>";
            }
  
        ?>
    </p>


</body>
</html>