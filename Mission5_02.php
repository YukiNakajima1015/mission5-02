<!DOCTYPE html>
<html lang = "ja">
<head>
<meta charset="UTF-8">
<title>mission_5-04</title>
</head>
<body>

<form action="" method="post">
<input type="text" name="name" value='' placeholder="名前"><br>
<input type="text" name="comment" value='' placeholder="コメント"><br>
<input type="submit" value="送信"><br>
<input type="text" name="delete_num" value='' placeholder="削除番号"><br><!--削除する番号-->
<input type="submit" value="削除"><br><!--削除するボタン-->
<input type="text" name="compile_num" value='' placeholder="編集番号"><br><!--編集する番号-->
<input type="text" name="compile_name" value='' placeholder="編集する名前"><br><!--編集する名前-->
<input type="text" name="compile_comment" value='' placeholder="編集するコメント"><br><!--編集するコメント-->
<input type="submit" value="編集"><br><!--編集するボタン-->

</form>
<?php

$date=date("Y年m月d日 H:i:s");
$num=$_POST["name"];
$com=$_POST["comment"];
#$pass=$_POST["pass"];
$delete_num=$_POST["delete_num"];
$delete_pass=$_POST["delete_pass"];
$compile_num=$_POST["compile_num"];
$compile_pass=$_POST["compile_pass"];

/*データベースへの接続*/
$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password, #どのデータベースでもPDOならいい
array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));#PDOのデータベースでのエラーを返す

/*CREATE文：データベース内にテーブルを作成*/
$sql = "CREATE TABLE IF NOT EXISTS tbtest"
." ("
. "id INT AUTO_INCREMENT PRIMARY KEY,"
. "name char(32),"#32文字
. "comment TEXT,"
. "pass TEXT"
.");";
$stmt = $pdo->query($sql);#statementのこと、queryにsqlを渡す

/*SHOW TABLES：データベースのテーブル一覧を表示*/
$sql ='SHOW TABLES';
$result = $pdo -> query($sql);#queryにsqlを渡す
foreach ($result as $row)#queryの結果は配列で帰ってくる
{
	echo $row[0];
	echo '<br>';
}
echo "<hr>";#仕切り線

/*INSERT文：データを入力（データレコードの挿入）*/
if (!empty($num) and !empty($com))
{
    $sql = $pdo -> prepare("INSERT INTO tbtest (name, comment) VALUES (:name, :comment)");
    $sql -> bindParam(':name', $name, PDO::PARAM_STR);
    $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
    $name = $num;
    $comment = $com; 
    $sql -> execute();
}
/*削除番号のところを消す*/
if (!empty($delete_num))
{
    $id = $delete_num;
    $sql = 'delete from tbtest where id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();  
}
/*編集番号のところを編集*/
if (!empty($compile_num))
{
    $id = $compile_num; //変更する投稿番号
    $name = $_POST["compile_name"];
    $comment = $_POST["compile_comment"]; 
    $sql = 'UPDATE tbtest SET name=:name,comment=:comment WHERE id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
}
/*SELECT文：入力したデータレコードを抽出し、表示する*/
$sql = 'SELECT * FROM tbtest';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();
foreach ($results as $row)
{
//$rowの中にはテーブルのカラム名が入る
    echo $row['id'].',';
    echo $row['name'].',';
    echo $row['comment'].'<br>';
    echo "<hr>";#仕切り線
}
?>
</body>
</html>