
<?php
if(isset($_POST['add'])){


include 'dbcon.php';
session_start();
$first_name=$_POST['first_name'];
$last_name=$_POST['last_name'];
$email=$_POST['email'];
$headline=$_POST['headline'];
$summary=$_POST['summary'];
if (!preg_match("/^\w+@+$/",$email)) {
    echo "<h2><p class=\"alert-danger text-center\" ><strong>ERROR!</strong>Email must contain @</p></h2>";
  }
  else{
    $stmt = $pdo->prepare('INSERT INTO Profile
    (user_id, first_name, last_name, email, headline, summary)
    VALUES ( :uid, :fn, :ln, :em, :he, :su)');
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<h1>Adding Profile for </h1>
<form method="post">
<p>First Name:
<input type="text" id="fname" name="first_name" size="60"/></p>
<p>Last Name:
<input type="text" id="lname" name="last_name" size="60"/></p>
<p>Email:
<input type="text" id="email" name="email" size="30"/></p>
<p>Headline:<br/>
<input type="text" id="headline" name="headline" size="80"/></p>
<p>Summary:<br/>
<textarea id="summary" name="summary" rows="8" cols="80"></textarea>
<p>
<input type="submit"  value="Add" name="add">
<input type="submit" name="cancel" value="Cancel">
</p>
</form>
    
</body>
</html>



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
            else if(!/^\w+@+$/.test(email)){
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
