<?php
include 'dbcon.php';
$p_id=$_REQUEST['profile_id'];
$sql="select * from profile where profile_id=$p_id ";
$stmt = $pdo->prepare($sql);
$stmt->execute();
//$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

while($result = $stmt->fetch()){
    ?>
    <h2>EDIT</h2>
    <form action="" method="post">
    <p>Firstname : <input name="first_name" type="text" value="<?php echo $result['first_name'];?>"></p>
    <p>Lastname : <input name="last_name" type="text" value="<?php echo $result['last_name'];?>"></p>
    <p>Email : <input name="email" type="text" value="<?php echo $result['email'];?>"></p>
    <p>Headline : <input name="headline" type="text" value="<?php echo $result['headline'];?>"></p>
    <p>Summary : <input name="summary" type="text" value="<?php echo $result['summary'];?>"></p>

    <p><input type="submit" name="save" value="SAVE"></p>
    </form>

    <?php
} 
?>
<?php
if(isset($_POST['save'])){
$first_name=$_POST['first_name'];
$last_name=$_POST['last_name'];
$email=$_POST['email'];
$headline=$_POST['headline'];
$summary=$_POST['summary'];
if (!preg_match("/@/",$email)) {
    echo "<h2><p class=\"alert-danger text-center\" ><strong>ERROR!</strong>Email must contain @</p></h2>";
  }
  else{
    $data=[
        'first_name' => $first_name,
        'last_name' => $last_name,
        'email' => $email,
        'headline' => $headline,
        'summary' => $summary,
    ];
    $sql = "UPDATE profile SET first_name=:first_name, last_name=:last_name, email=:email, headline=:headline, summary=:summary WHERE profile_id=$p_id";
    $stmt= $pdo->prepare($sql);
    $stmt->execute($data);
    header('Location: index.php');
}

      
  }
               
  ?>