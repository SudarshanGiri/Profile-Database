<?php
    include 'head.php';
    session_start();

    if(isset($_POST['add'])){

          

        
        include 'dbcon.php';
        $first_name=$_POST['first_name'];
        $last_name=$_POST['last_name'];
        $email=$_POST['email'];
        $headline=$_POST['headline'];
        $summary=$_POST['summary'];
        function validatePos() {
            for($i=1; $i<=9; $i++) {
                if ( ! isset($_POST['year'.$i]) ) continue;
                if ( ! isset($_POST['desc'.$i]) ) continue;
                $year = $_POST['year'.$i];
                $desc = $_POST['desc'.$i];
                if ( strlen($year) == 0 || strlen($desc) == 0 ) {
                    echo "<h2><p class=\"alert-danger text-center\" ><strong>ERROR!!!</strong>All values are required</p></h2>";
                    return false;
                }
        
                if ( ! is_numeric($year) ) {
                    echo "<h2><p class=\"alert-danger text-center\" ><strong>ERROR!!!</strong> Position YEAR Must be numeric </p></h2>";
                    return false;
                }
            }
            return true;
        }
        if (validatePos()==false){
            echo "Please Try again";
        }

        elseif (!preg_match("/@/",$email)) {
            echo "<h2><p class=\"alert-danger text-center\" ><strong>ERROR!!!</strong>Email must contain @</p></h2>";
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


        $profile_id = $pdo->lastInsertId();
        $rank=1;
        for($i=1; $i<=9; $i++) {
            if(!isset($_POST['year'.$i])) continue;
            if(!isset($_POST['desc'.$i])) continue;

            $year = $_POST['year'.$i];
            $desc = $_POST['desc'.$i];
            if($year!==null){
                $stmt = $pdo->prepare('INSERT INTO Position
                (profile_id, rank, year, description) 
                VALUES ( :pid, :rank, :year, :desc)');
                $stmt->execute(array(
                    ':pid' => $profile_id,
                    ':rank' => $rank,
                    ':year' => $year,
                    ':desc' => $desc)
                );
                $rank++;
            }             
        }

        $rank=1;
        for($i=1; $i<=9; $i++) {
            if(!isset($_POST['edu_year'.$i])) continue;
            if(!isset($_POST['edu_school'.$i])) continue;

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

                $stmt = $pdo->prepare('INSERT INTO education
                (profile_id, rank, year, institution_id) 
                VALUES ( :pid, :rank, :year, :iid)');
                $stmt->execute(array(
                    ':pid' => $profile_id,
                    ':rank' => $rank,
                    ':year' => $year,
                    ':iid' => $institution_id)
                );
                $rank++;
            }             
    



        $_SESSION['message'] = "Profile added";

        header('Location: index.php');
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
    <div class="container">
        <h1>Adding Profile for <?php echo $_SESSION['name']; ?></h1>
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
        Education: <input type="submit" id="addEdu" value="+">
        <div id="edu_fields">
        </div>
        </p>

        <p>
        Position: <input type="submit" id="addPos" value="+">
        <div id="position_fields">
        </div>
        </p>



        <p>
        <input type="submit"  value="Add" name="add" onclick="validate();">
        <input type="submit" name="cancel" value="Cancel">
        </p>

        </form>
    </div>









    

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
                alert("All values are required");
                return false;
            }
            else if(!/@/.test(email)){
                alert ("Email must contain @ ");
                return false;

            }
            return true;
        } catch(e) {
            return false;
        }
        return false;
}


//validate position------------------------------------
countEdu=0;
countPos=0;
$(document).ready(function(){
    window.console && console.log('Document ready called');
    $('#addPos').click(function(event){
        // http://api.jquery.com/event.preventdefault/
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

        $('#edu_fields').append(
            '<div id="edu'+countEdu+'"> \
            <p>Year: <input type="text" name="edu_year'+countEdu+'" value="" /> \
            <input type="button" value="-" onclick="$(\'#edu'+countEdu+'\').remove();return false;"><br>\
            <p>School: <input type="text" size="80" name="edu_school'+countEdu+'" class="school" value="" />\
            </p></div>'
        );

        $('.school').autocomplete({
            source: "school.php"
        });

    });


});


</script>
   
</body>
</html>

