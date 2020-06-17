<?php
        include 'dbcon.php';
        $p_id=$_REQUEST['profile_id'];
      $sql="select * from profile where profile_id=$p_id";
      $stmt = $pdo->prepare($sql);
      $stmt->execute();
      //$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
           while($result = $stmt->fetch()){
               ?>
               <h2>Profile Information</h2>
               <p><h3>Firstname : <?php echo $result['first_name'] ?></h3></p>
               <p><h3>Lastname : <?php echo $result['last_name'] ?></h3></p>
               <p><h3>Email : <?php echo $result['email'] ?></h3></p>
               <p><h3>Headline : <?php echo $result['headline'] ?></p>
               <p><h3>Summary : <?php echo $result['summary'] ?></h3></p>

               <?php
             
       

           }  ?>