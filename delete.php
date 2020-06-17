<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sudarshan Giri</title>
</head>
<body>
<div>
<h1>Deleteing Profile</h1>

<?php 
  session_start();
  include 'dbcon.php';
  $p_id=$_REQUEST['profile_id'];
  $sql="select * from profile where profile_id=$p_id";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
 //$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
      while($result = $stmt->fetch()){
        ?>
        <p>First Name:<?php echo $result['first_name']?></p>
        <p>Last Name:<?php echo $result['last_name']?></p>
        <?php
      }

    
?>
<form action="" method="post">
<input type="submit" name="delete" value="Delete">
  <input type="submit" name="cancel" value="Cancel">
</form>

<?php 
if (isset($_POST['delete'])){
    include 'dbcon.php';
  $p_id=$_REQUEST['profile_id'];
  $sql="DELETE  from profile where profile_id=$p_id ";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $_SESSION['message']="Deleted Successfully";
  header('Location: index.php');


  }
  if (isset($_POST['cancel'])){
    header('Location: index.php');


  }

  
  ?>
  
</body>
</html>
