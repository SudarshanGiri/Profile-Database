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
      $sql="select * from profile where first_name!='' ";
      $stmt = $pdo->prepare($sql);
      $stmt->execute();
      //$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
      ?><table border="1">
      <tr><th>Name</th><th>Headline</th><th>Action</th><tr>
      <?php
           while($result = $stmt->fetch()){
               ?>
               <tr>
                    <td><a href="view.php"><?php echo $result['first_name']; ?></a></td>
                    <td><?php echo $result['headline']; ?></td>
                    <td><a href="edit.php"> Edit</a>
                        <a href="delete.php"> Delete</a>
                    </td>
               </tr>
               <?php
       

           }  ?></table>