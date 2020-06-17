<?php 
        $sql="select * from profile where first_name!='' ";
      $stmt = $pdo->prepare($sql);
      $stmt->execute();