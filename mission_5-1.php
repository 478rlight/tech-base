<!DOCTYPE html>
<?php
//データベースに接続
 $dsn='データベース名';
 $user='ユーザー名';
 $password='パスワード';
 $pdo=new PDO($dsn,$user,$password,
     array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
?>

<?php

//データベース内で新規テーブル作成 

 $sql = "CREATE TABLE IF NOT EXISTS tbdora"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
	. "dt datetime,"
	. "password char(255) NOT NULL"
	. ");";
	$stmt = $pdo->query($sql);

//ポスト受信
 if(isset($_POST["name"]))
   {$name=$_POST["name"];}
 if(isset($_POST["com"]))  
   {$com=$_POST["com"];}
 if(isset($_POST["delnum"]))
   {$delid=$_POST["delnum"];}
 if(isset($_POST["editnum"]))
   {$editid=$_POST["editnum"];}
 if(isset($_POST["editnum2"]))
   {$editnum2=$_POST["editnum2"];}
 if(isset($_POST["pass1"]))  
   {$pass1=$_POST["pass1"];
    $hash = password_hash($pass1, PASSWORD_BCRYPT);
   }
 if(isset($_POST["pass2"])) 
   {$pass2=$_POST["pass2"];}
 if(isset($_POST["pass3"])) 
   {$pass3=$_POST["pass3"];}
 $date=date('Y-m-d H:i:s');
 
//編集ボタンが押されたとき 
 if(isset($editid) && isset($_POST["submit3"]))
 {

  $sql = "SELECT password FROM tbdora WHERE id = $editid";
  $stmt = $pdo->query($sql);
  $results = $stmt->fetchAll();
  foreach($results as $row)
  {}
  if(password_verify($pass3,$row['password']))
     {
      
      $sql = "SELECT id,name,comment FROM tbdora WHERE id = $editid";
      $stmt = $pdo->query($sql);
      $results = $stmt->fetchAll();
      foreach($results as $row)
      {
        $num2=$row['id'];
        $name2=$row['name'];
        $com2=$row['comment'];
      }
     }
   else
     {echo "パスワードが違います";}
 }

//データの入力
 if(isset($_POST["submit1"]))
  {if(empty($editnum2))
    {if(isset($pass1))
     {
      $sql=$pdo -> prepare("INSERT INTO tbdora(name,comment,dt,password)
      VALUES(:name,:comment,:dt,:password)");
      $sql -> bindParam(':name',$name,PDO::PARAM_STR);
      $sql -> bindParam(':comment',$com,PDO::PARAM_STR);
      $sql -> bindParam(':dt',$date,PDO::PARAM_STR);
      $sql -> bindParam(':password',$hash,PDO::PARAM_STR);
      $sql -> execute();
     }
    } 
//データの編集
   elseif(!empty($editnum2)) 
    {
     $sql="UPDATE tbdora SET name=:name,comment=:comment,dt=:dt,password=:password WHERE id = $editnum2";
     $stmt=$pdo -> prepare($sql);
     $stmt -> bindParam(':name',$name,PDO::PARAM_STR);
     $stmt -> bindParam(':comment',$com,PDO::PARAM_STR);
     $stmt -> bindParam(':dt',$date,PDO::PARAM_STR);
     $stmt -> bindParam(':password',$hash,PDO::PARAM_STR);
     $stmt -> execute();
    } 
  }

//データの削除
if(isset($_POST["submit2"]))
 {
  $sql = "SELECT password FROM tbdora WHERE id = $delid";
  $stmt = $pdo->query($sql);
  $results = $stmt->fetchAll();
  foreach($results as $row)
  {}
  if(password_verify($pass2,$row['password']))
     {
      $sql='delete from tbdora where id=:id';
      $stmt=$pdo -> prepare($sql);
      $stmt -> bindParam(':id',$delid,PDO::PARAM_INT);
      $stmt -> execute();
     }
  else
     {echo "パスワードが違います";}
  }
?>
<html>
   <head>
       <meta charset="utf-8" /> 
   </head> 
   <body>
    <form method="POST" action="">
        
        お名前:<input type="name" name="name" value="<?php 
        if(isset($_POST["submit3"])&&isset($name2))
        {echo $name2;} ?>" required><br>
        
        コメント:<input type="comment" name="com" value="<?php
        if(isset($_POST["submit3"])&&isset($com2))
        {echo $com2;} ?>" required><br>
        
        <input type="hidden" name="editnum2" value="<?php
        if(isset($_POST["submit3"])&&isset($num2))
        {echo $num2;} ?>">
        
        パスワード:<input type="text" name="pass1" required>
        
        <input type="submit" name="submit1" value="送信"><br><br>
    </form>
    
    <form method="POST" action="">
        
        削除対象番号:<input type="number" name="delnum" required>
        
        登録したパスワード:<input type="text" name="pass2" required>
        
        <input type="submit" name="submit2" value="削除"><br><br>
     
    </form>
     
    <form method="POST" action="">
        編集対象番号:<input type="number" name="editnum" required>
        
        登録したパスワード:<input type="text" name="pass3" required>
        
        <input type="submit" name="submit3" value="編集">
    </form>  
   </body>
</html>     
<?php
//データの表示
 $sql='SELECT * FROM tbdora';
 $stmt=$pdo -> query($sql);
 $results=$stmt -> fetchAll();
  foreach($results as $row)
     {
      echo $row['id'].',';
      echo $row['name'].',';
      echo $row['comment'].',';
      echo $row['dt'].'<br>';
      echo "<hr>";
     }
     ?>