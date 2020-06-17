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
           if(isset($_POST['save'])){
               
include 'dbcon.php';
$first_name=$_POST['first_name'];
$last_name=$_POST['last_name'];
$email=$_POST['email'];
$headline=$_POST['headline'];
$summary=$_POST['summary'];
if (!preg_match("/@/",$email)) {
    echo "<h2><p class=\"alert-danger text-center\" ><strong>ERROR!</strong>Email must contain @</p></h2>";
  }
  else{
    $stmt = $pdo->prepare('UPDATE INTO Profile
    (first_name, last_name, email, headline, summary)
    VALUES ( :first_name, :last_name, :email, :headline, :summary)');
$stmt->execute(array(
    ':uid' => $_SESSION['user_id'],
    ':fn' => $_POST['first_name'],
    ':ln' => $_POST['last_name'],
    ':em' => $_POST['email'],
    ':he' => $_POST['headline'],
    ':su' => $_POST['summary'])
);
header('Location: index.php');
}

      
  }
               
           

           
        

    
    ?>