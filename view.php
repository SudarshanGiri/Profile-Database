<?php
        include 'head.php';
        include 'dbcon.php';
        $p_id=$_REQUEST['profile_id'];
      $sql="select * from profile where profile_id=$p_id";
      $stmt = $pdo->prepare($sql);
      $stmt->execute();
      //$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
           while($result = $stmt->fetch()){
               ?>
               <div class="container">
               <h2>Profile Information</h2>
               <p><h4>Firstname : <?php echo $result['first_name'] ?></h4></p>
               <p><h4>Lastname : <?php echo $result['last_name'] ?></h4></p>
               <p><h4>Email : <?php echo $result['email'] ?></h4></p>
               <p><h4>Headline : <?php echo $result['headline'] ?></p>
               <p><h4>Summary : <?php echo $result['summary'] ?></h4></p>

                </div>

               <?php
             

           } 
           //checking if position exists
           $sql1="select * from position where profile_id=$p_id and year!='' ";
           $stmt1 = $pdo->prepare($sql1);
           $stmt1->execute();
           $result2=$stmt1->fetch();
           ?><div class="container"><?php
           if($result2!=null){
               echo "<h4>Position</h4>";

           }

           //gettin positions from the database and displayng them
          $sql="select * from position where profile_id=$p_id and year!='' ";
          $stmt = $pdo->prepare($sql);
          $stmt->execute();
          ?><div class="container"><?php
        
          while($result = $stmt->fetch()){
                  ?>
                      
                      <ul>
                      <li><?php echo $result['year'] ?>: <?php echo $result['description'] ?></li>
                      </ul>
                    
                     
                  <?php             
          }


         ?> <p><a href="index.php">Done</a></p></div><?php
          
?>