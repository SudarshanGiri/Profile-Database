<?php include 'head.php'; include 'dbcon.php';    session_start(); ?>
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
     $p_id=$_REQUEST['profile_id'];

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

            //Remove old position and reinsert again
            $stmt2 = $pdo->prepare('DELETE FROM position WHERE profile_id=:pid');
            $stmt2->execute(array( ':pid' => $_REQUEST['profile_id']));

            $rank = 1;
            for($i=1; $i<=9; $i++) {
                if ( ! isset($_POST['year'.$i]) ) continue;
                if ( ! isset($_POST['desc'.$i]) ) continue;

                $year = $_POST['year'.$i];
                $desc = $_POST['desc'.$i];
                $stmt2 = $pdo->prepare('INSERT INTO position
                    (profile_id, rank, year, description)
                    VALUES ( :pid, :rank, :year, :desc)');

                $stmt2->execute(array(
                ':pid' => $_REQUEST['profile_id'],
                ':rank' => $rank,
                ':year' => $year,
                ':desc' => $desc)
                );

                $rank++;

            }

            //Remove old education and reinsert again
            $stmt2 = $pdo->prepare('DELETE  FROM education  WHERE profile_id=:pid');
            $stmt2->execute(array( ':pid' => $_REQUEST['profile_id']));


            $rank = 1;
            for($i=1; $i<=9; $i++) {
                
                if ( ! isset($_POST['edu_year'.$i]) ) continue;
                if ( ! isset($_POST['edu_school'.$i]) ) continue;

                $year = $_POST['edu_year'.$i];
                $school = $_POST['edu_school'.$i];


                //Lookup the school if it is there
                $institution_id=false;
                $stmt=$pdo->prepare('SELECT institution_id FROM Institution WHERE name=:name');
                $stmt->execute(array(':name'=>$school));
                $row=$stmt->fetch(PDO::FETCH_ASSOC);
                if ($row!=false) $institution_id=$row['institution_id'];

                //if there was no institution, insert it
                if($institution_id==false){
                    $stmt=$pdo->prepare('INSERT INTO Institution (name) VALUES (:name)');
                    $stmt->execute(array(':name' => $school));
                    $institution_id=$pdo->lastInsertId();
                }
                $stmt2 = $pdo->prepare('INSERT INTO education
                (profile_id, rank, year, institution_id) 
                VALUES ( :pid, :rank, :year, :iid)');

                $stmt2->execute(array(
                    ':pid' => $p_id,
                    ':rank' => $rank,
                    ':year' => $year,
                    ':iid' => $institution_id)
                );

                $rank++;

            }
            

            $_SESSION['message'] = "Profile Updated";

            header('Location: index.php');

        }

        
    }
?>
   
<?php
    if(isset($_SESSION['user_id'])){

        include 'dbcon.php';
        $p_id=$_REQUEST['profile_id'];
        $sql="select * from profile where profile_id=$p_id ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        //$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        while($result = $stmt->fetch()){
            ?><div class="container">
            <h2>EDITING Profile for <?php echo $_SESSION['name']?></h2>
            <form action="" method="post">
            <p>Firstname : <input id="fname" name="first_name" type="text" value="<?php echo $result['first_name'];?>"></p>
            <p>Lastname : <input id="lname" name="last_name" type="text" value="<?php echo $result['last_name'];?>"></p>
            <p>Email : <input id="email"name="email" type="text" value="<?php echo $result['email'];?>"></p>
            <p>Headline : <input id="headline" name="headline" type="text" value="<?php echo $result['headline'];?>"></p>
            <p>Summary : <input id="summary" name="summary" type="text" value="<?php echo $result['summary'];?>"></p>


            <?php
        } 

       



        // EDIT EDUCATION
        $sql2="select year,name FROM education JOIN institution ON education.institution_id=institution.institution_id WHERE profile_id=$p_id";
        $stmt2 = $pdo->prepare($sql2);
        $stmt2->execute();         
        
        ?> <p>Education: <input type="submit" id="addEdu" value="+">
        <div id="edu_fields">
        </div></p>
        <?php
        
        
        while($educations = $stmt2->fetch()){
            ?>
 
                <div id="edu1">
                <p>Year: <input type="text" name="edu_year1" value="<?php echo $educations['year'];?>" />
                <input type="button" value="-" onclick="$('#edu1').remove();return false;">
                </p>
                <p>School: <input type="text" size="80" name="edu_school1" class="school" value="<?php echo $educations['name'];?>" />
                </div></p>                   
           <?php 
        }



         //EDIT POSITIONS
         $sql2="select * from position where profile_id=$p_id and year!='' ";
         $stmt2 = $pdo->prepare($sql2);
         $stmt2->execute();         
         
         ?> <p>Position: <input type="submit" id="addPos" value="+">
                 <div id="position_fields"></div>
             </p>
         <?php
         
         
         while($result2 = $stmt2->fetch()){
             ?>
  
                 <div id="position1">
                 <p>Year: <input type="text" name="year1" value="<?php echo $result2['year'];?>" />
                 <input type="button" value="-" onclick="$('#position1').remove();return false;">
                 </p>
                 <textarea style="text-align:left;"name="desc1" rows="8" cols="80"><?php echo $result2['description'];?></textarea>
                 </div>                   
            <?php 
         }?>



        
        <p>
        <button type="submit" name='save' value="Save">Save</button>
        <input type="submit" name="cancel" value="Cancel">
        </p>
        </form>

        <?php             
          
          /*?><p><button type="submit" onclick="validate();" name="save" value="Save">Save</button><?php*/                           
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

<script>
countPos = 1;
countEdu = 1;


$(document).ready(function(){
    window.console && console.log('Document ready called');
    $('#addPos').click(function(event){
        event.preventDefault();
        if ( countPos >= 9 ) {
            alert("Maximum of nine position entries exceeded");
            return;
        }
        countPos++;
        window.console && console.log("Adding position "+countPos);
        $('#position_fields').append(
            '<div id="position'+countPos+'"> \
            <p>Year: <input type="text" name="year'+countPos+'" value="" /> \
            <input type="button" value="-" \
            onclick="$(\'#position'+countPos+'\').remove();return false;"></p> \
            <textarea name="desc'+countPos+'" rows="8" cols="80"></textarea>\
            </div>');
    });


    $('#addEdu').click(function(event){
        event.preventDefault();
        if ( countEdu >= 9 ) {
            alert("Maximum of nine education entries exceeded");
            return;
        }
        countEdu++;
        window.console && console.log("Adding education "+countEdu);

        // Grab some HTML with hot spots and insert into the DOM
        var source  = $("#edu-template").html();
        $('#edu_fields').append(source.replace(/@COUNT@/g,countEdu));

        // Add the even handler to the new ones
        $('.school').autocomplete({
            source: "school.php"
        });

    });

    $('.school').autocomplete({
        source: "school.php"
    });


});
</script>

</script>
<!-- HTML with Substitution hot spots -->
<script id="edu-template" type="text">
  <div id="edu@COUNT@">
    <p>Year: <input type="text" name="edu_year@COUNT@" value="" />
    <input type="button" value="-" onclick="$('#edu@COUNT@').remove();return false;"><br>
    <p>School: <input type="text" size="80" name="edu_school@COUNT@" class="school" value="" />
    </p>
  </div>
</script>
    
</body>
</html>
