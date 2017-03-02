<?php
$dsn = 'mysql:dbname=oneline_bbs;host=localhost';
$user = 'root';
$password = '';
$dbh = new PDO($dsn,$user,$password);
$dbh->query('SET NAMES utf8');

if (!empty($_POST)) {
    // setの方がわかりやすい ?には後でデータを入れる
    $sql = 'INSERT INTO `posts` SET `id`=NULL,
                                    `nickname`=?,
                                    `comment`=?,
                                    `created`=NOW()
                                    ';
    $data = array($_POST['nickname'], $_POST['comment']);

    $stmt = $dbh->prepare($sql);
    $stmt->execute($data); //実行文（?を使う場合は変数名(この場合は$data)をセット

    // ページのリロード処理
    header('Location: bbs_no_css.php');
    exit();
}

$sql = 'SELECT * FROM `posts` ORDER BY `created` DESC';
// ORDER BY句
// 指定したカラムの照準(ASC)もしくは降順(DESC)でデータの取得順を指定する(初期設定ではidのASC)

$stmt = $dbh->prepare($sql);
$stmt->execute();  //stmtにsurverから取得した全件データが入る

// 繰り返し文
while (true) {  //  条件として無限ループを起こす
    // $stmtのデータを全件fetchしきたっら繰り返し処理を終了させる
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($record === false) {
        break;
    }
    echo $record['nickname'] . ' -' . $record['created'];
    echo '<br>';
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>セブ掲示版</title>
</head>
<body>
    <form method="post" action="">
      <p><input type="text" name="nickname" placeholder="nickname"></p>
      <p><textarea type="text" name="comment" placeholder="comment"></textarea></p>
      <p><button type="submit" >つぶやく</button></p>
    </form>
    <!-- ここにニックネーム、つぶやいた内容、日付を表示する -->

</body>
</html>