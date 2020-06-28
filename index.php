<?php
include 'head.php';
session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sudarshan Giri</title>
</head>
<body>
    <p><h2>RESUME REGISTRY OF Sudarshan Giri</h2></p>
    <?php
    if(isset($_SESSION['message'])){
        echo $_SESSION['message'];

    }
    unset($_SESSION['message']);
    if(isset($_SESSION['user_id'])){
        echo " <p><a href='logout.php'> Logout</a></p>";

    }
    else{
        echo " <p><a href='login.php'>Please log in</a></p>";


    }
    ?>
    <?php
            include 'dbcon.php';
            /*$sql="select * from users";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
           foreach ($result as $val){
               echo "Name". $val['name'];
           }
       */     
      $sql="select * from profile";
      $stmt = $pdo->prepare($sql);
      $stmt->execute();
      //$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
      ?><table border="1">
      <tr><th>Name</th><th>Headline</th>                    <?php  if(isset($_SESSION['user_id'])){ ?>
<th>Action</th><tr> <?php }?>
      <?php
           while($result = $stmt->fetch()){
               ?>
               <tr>
                    <td><a href="view.php?profile_id= <?php echo $result["profile_id"] ?>"><?php echo $result['first_name']; ?></a></td>
                    <td><?php echo $result['headline']; ?></td>
                    
                    <?php  if(isset($_SESSION['user_id'])){

                        ?><td><a href='edit.php?profile_id= <?php echo $result["profile_id"] ?>'> Edit</a>
                        <a href="delete.php?profile_id= <?php echo $result["profile_id"] ?>"> Delete</a>
                        </td>
                        <?php

                    }?>
                  
               </tr>
               <?php
       

           }  ?></table><?php

           if(isset($_SESSION['user_id'])){
            echo " <p><a href='add.php'>Add New Entry</a></p>";
    
        }

    
    ?>



</body>
</html>