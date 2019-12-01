
<?php
$dataFile = 'bbs.dat';

function h($s)
{
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

if (isset($_POST['toukou'])){

    $lines = file('bbs.dat');
    $cnt = count($lines);
    $cnt += 1;

    $message = $_POST['message'];
    $user = $_POST['user'];
    $time = date("Y/m/d H:i:s");


    $newData = "{番号}" . "<" . $cnt . ">" . "\t" . "{名前}" . "<" . $user . ">" . "\t" . "{コメント}" . "<" . $message . ">" . "\t" . $time . "\n";

     write($dataFile, $newData, "a");
}

$posts = file($dataFile, FILE_IGNORE_NEW_LINES);
$posts = array_reverse($posts);

if (isset($_POST['delete']))
{
    for ($i = 0; $i < count($posts); $i++)
    {
        $items = explode("\t", $posts[$i]);
        if($items[0] == "{番号}<{$_POST['delno']}>")
        {
            $posts[$i] = "";
        }
    }

    $newData = array_reverse($posts);
    $newData = implode("\n", $newData) . "\n";

    write($dataFile, $newData, "w");
}

$all_posts = count($posts);
foreach($posts as $post)
{
    if(empty($post)){ $all_posts--; }
}

function write($dataFile, $newData, $mode)
{
    $fp = fopen($dataFile, $mode);
    fwrite($fp, $newData);
    fclose($fp);
}

?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>簡易掲示板</title>
    </head>
    <body>
        <h1>簡易掲示板</h1>
        <form action="" method="post">
            名前：<input type="text" name="user">
            コメント：<input type="text" name="message">
            <input type="submit" name="toukou" value="投稿">
        </form>
        <form method="post" action="">
            削除指定番号：<input type="text" name="delno"> <input type="submit" name="delete" value="削除">
        </form>
        <h2>投稿一覧 (<?php echo $all_posts; ?>件)</h2>
        <ul>
<?php if (count($posts)): ?>
    <?php foreach ($posts as $post): ?>
        <?php if (empty($post)) continue; ?>
        <?php list($cnt, $user, $message, $time) = explode("\t", $post); ?>
                    <li><?php echo h($cnt); ?> <?php echo h($user); ?> <?php echo h($message); ?></li>
                <?php endforeach ?>
            <?php else: ?>
                <li>まだ投稿はありません。</li>
            <?php endif; ?>
        </ul>
    </body>
</html>
