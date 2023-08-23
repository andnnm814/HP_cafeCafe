<?php

require 'valid.php';
debug('「「「「「「「「「「「「「「「「');
debug('confirmページ');
debug('「「「「「「「「「「「「「「「「');

session_start();

$referer = $_SERVER['HTTP_REFERER']; //直前にアクセスしてきたURL
$url = parse_url($referer);
$url = basename($referer);
debug('アクセス元のURL：'.$url);

if( strpos($url, 'contact.php') !== false ){
    // アクセス元がcontact.phpの場合
    $_SESSION['from_url'] = 'contact.php';
    debug('前ページはcontact.phpです。');
}elseif( strpos($url, 'edit.php') !== false){
    // アクセス元がedit.phpの場合
    $_SESSION['from_url'] ='edit.php';
    debug('前ページはedit.phpです。');
}else{
    debug('不正アクセスがあります。');
    header("Location:contact.php");
}

debug('$_SESSION["from_url"]：'.$_SESSION['from_url']);

//================================
// 画面処理
//================================

// 前ページからDBのidを取得
$contact_id = (!empty($_GET['c_id'])) ? $_GET['c_id'] : '';
debug('$contact_id：'.$contact_id);

if(isset($_POST['submit'])){
    debug('POST送信あり');

    try{
        $dbh = dbConnect();
        $dbh->beginTransaction();


        if($_SESSION['from_url'] === 'contact.php' ){
            $sql = 'INSERT INTO contacts(`name`,`kana`,`tel`,`email`,`body`) VALUES(:username,:kana,:tel,:email,:body)';
        }
        elseif($_SESSION['from_url'] === 'edit.php' ){
            $sql = "UPDATE contacts SET `name`=:username, `kana`=:kana, `tel`=:tel, `email`=:email, `body`=:body WHERE `id`='".$contact_id."'";
        }

        $data = [
            ':username' => $_SESSION['name'],
            ':kana' => $_SESSION['kana'],
            ':tel' => $_SESSION['tel'],
            ':email' => $_SESSION['email'],
            ':body' => $_SESSION['body'],
        ];

        $stmt = queryPost($dbh,$sql,$data);

        if($stmt){
            debug('SQL実行');
            $dbh->commit();
        }

        session_destroy();
        debug('セッションデストロイ');
        header('Location:complete.php');

    }catch(PDOException $e){
        $dbh->rollBack();
        debug('エラー発生'.$e->getMessage());
    }

}


?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Lesson Sample Site</title>
<link rel="stylesheet" type="text/css" href="css/style.css">
<link rel="stylesheet" type="text/css" href="css/sp.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body id="app" v-on="click: closeMenu">
    <?php
        include('header.php');
    ?>
    <open-modal v-show="showContent" v-on="click: closeModal"></open-modal>

    <section>
        <div class="contact_box">
            <h2>お問い合わせ</h2>
			<form action="" method="post">
                <p>下記の内容をご確認の上送信ボタンを押してください</p>
                <p>内容を訂正する場合は戻るを押してください。</p>
                <dl class="confirm">
                    <!--    =====================================   -->
                    <dt>氏名</dt>
                    <dd>
                        <!--氏名を出力-->
                        <?php echo $_SESSION['name']; ?>
                    </dd>
                    <!--    =====================================   -->
                    <dt>フリガナ</dt>
                    <dd>
                        <!--フリガナを出力-->
                        <?php echo $_SESSION['kana']; ?>
                    </dd>
                    <!--    =====================================   -->
                    <dt>電話番号</dt>
                    <dd>
                        <!--電話番号を出力-->
                        <?php echo $_SESSION['tel']; ?>
                    </dd>            
                    <!--    =====================================   -->
                    <dt>メールアドレス</dt>
                    <dd>
                        <!--メールアドレスを出力-->
                        <?php echo $_SESSION['email']; ?>
                    </dd>
                    <!--    =====================================   -->
                    <dt>お問い合わせ内容</dt>
                    <dd>
                        <!--お問い合わせ内容を出力-->
                        <?php echo $_SESSION['body']; ?>
                    </dd>
                    <!--    =====================================   -->
                    <dd class="confirm_btn">
                        <?php if (strpos($url, 'edit.php') !== false) { ?>
                            <button type="submit" name="submit">更　新</button>
                        <?php } elseif (strpos($url, 'contact.php') !== false) { ?>
                        <button type="submit" name="submit">送　信</button>
                        <?php } ?>
                        <a href="javascript:history.back();">戻　る</button>
                    </dd>
                </dl>
			</form>
        </div>
    </section>

    <?php
        include('footer.php');
    ?>

    <script src="http://cdnjs.cloudflare.com/ajax/libs/vue/0.11.5/vue.min.js"></script>
    <script type="text/javascript" src="js/script.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</body>
</html>

