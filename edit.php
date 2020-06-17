<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sudarshan Giri</title>
</head>
<body>
 <!---- VALIDATE and UPDATE  using PHP------->
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
            $_SESSION['message'] = "Profile Updated";
            header('Location: index.php');

        }

        
    }
?>
   
<?php
    session_start();
    if(isset($_SESSION['user_id'])){

        include 'dbcon.php';
        $p_id=$_REQUEST['profile_id'];
        $sql="select * from profile where profile_id=$p_id ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        //$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        while($result = $stmt->fetch()){
            ?>
            <h2>EDITING Profile for <?php echo $_SESSION['name']?></h2>
            <form action="" method="post">
            <p>Firstname : <input id="fname" name="first_name" type="text" value="<?php echo $result['first_name'];?>"></p>
            <p>Lastname : <input id="lname" name="last_name" type="text" value="<?php echo $result['last_name'];?>"></p>
            <p>Email : <input id="email"name="email" type="text" value="<?php echo $result['email'];?>"></p>
            <p>Headline : <input id="headline" name="headline" type="text" value="<?php echo $result['headline'];?>"></p>
            <p>Summary : <input id="summary" name="summary" type="text" value="<?php echo $result['summary'];?>"></p>

            <p><input type="submit" onclick="validate();" name="save" value="SAVE"></p>
            </form>

            <?php
        } 
       
                    
    }
    else{
        echo"Access Denied";
    }  
?>


<!---JS VALIDATION---->
<script>
    function validate(){
        console.log('Validating...');
            try {
                fname = document.getElementById('fname').value;
                lname = document.getElementById('lname').value;
                email = document.getElementById('email').value;
                headline = document.getElementById('headline').value;
                summary = document.getElementById('summary').value;
                console.log("Validating");
                if (fname == null || fname == "" ||lname==null || lname=="" ||email==null||email==""||headline==""||headline==null||summary==null||summary=="") {
                    alert("All fields must be filled out");
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
