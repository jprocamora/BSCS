<?php
session_start();


include("db.php");

$errors = [];

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $username = $_POST['username'];
    $password = $_POST['password'];

    if(empty($username)){
        $errors[] = "Username is required";
    }
    
    $sql = "SELECT * FROM `users` WHERE `username` = '$username'";
    $result = $conn->query($sql);

     if($result->num_rows == 1){
        $user = mysqli_fetch_assoc($result);
        
        if(password_verify($password, $user['password'])){
            $_SESSION['username'] = $username;
            header("Location: dashboard.php");
            exit();
        } else {
            $errors[] = "Incorrect credentials";
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
