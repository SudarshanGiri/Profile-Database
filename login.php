<?php
 include "dbcon.php";
if(isset($_POST['login'])){
    $email=$_POST['email'];
    $pass=$_POST['pass'];
    $salt ='XyZzy12*_';
    $check = hash('md5', $salt.$_POST['pass']);
    $stmt = $pdo->prepare('SELECT user_id, name FROM users
        WHERE email = :email AND password = :pass');
    $stmt->execute(array( ':email' => $_POST['email'], ':pass' => $check));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ( $row !== false ) {
        session_start();
        $_SESSION['user_id'] = $row['user_id'];
        // Redirect the browser to index.php
        header("Location: index.php");
        return;
    }
    else{
        echo "LOGIN ERROR";

    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sudarshan Giri</title>
</head>
<body>
<form action="" method="post">
<h2>Please Log In</h2>
    <b>Email</b><input type="email" name="email"id="em" ><br>
    <b>Password</b><input type="password" name="pass" id="pass" autocomplete="on"><br>
    <input name="login" type="submit" onclick="return doValidate();" value="Log In">
</form>



<script>
    function doValidate() {
        try {
            console.log ("Validating....");
            var pw = document.getElementById('pass').value;
            var em = document.getElementById('em').value;
            if (pw == null || pw == "" || em==null||em=="") {
                alert("Both fields must be filled out");
                return false;
            }
            else if(!/^\w+@+$/.test(em)){
                alert ("Email must contain @ ");
                return false;

            }
            return true;
        } catch(e) {
            return false;
        }
        return false;
    }
</script>
 
</body>

</html>





