<?php 
  include 'dbcon.php';
  $p_id=$_REQUEST['profile_id'];
  $sql="DELETE  from profile where profile_id=$p_id ";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  header('Location: index.php');