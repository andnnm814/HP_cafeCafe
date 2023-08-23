<?php

date_default_timezone_set('Asia/Tokyo');
session_start();

require 'valid.php';

debug('「「「「「「「「「「「「「「「「「「「「「');
debug('「　Contactページ　');
debug('「「「「「「「「「
「「「「「「「「「「「「');


try {

    $dbh = dbConnect();
    $dbh->beginTransaction();

    // コンタクトテーブルの全てのデータを取得
    $sql = 'SELECT * FROM contacts';
    $data = [];
    $stmt = queryPost($dbh, $sql, $data);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if($result){
        $dbh->commit();
        debug('contactsテーブルのデータを全て取得');
    }

}
catch (PDOException $exception){
    $dbh->rollBack();
    debug('エラー発生');
}



// ①送信ボタンが押されたら入力内容を取得
if( !empty($_POST) ){
    $name = $_POST['name'];
    $kana = $_POST['kana'];
    $tel = $_POST['tel'];
    $email = $_POST['email'];
    $body = $_POST['body'];

// ②バリデーションを実行

    errEmpty($name, 'name');
    errEmpty($kana, 'kana');
    errEmpty($email,'email');
    errEmpty($body, 'body');

    if( empty($err_msg) ){
        errMaxlen($name, 'name');
        errMaxlen($kana, 'kana' );
        errEmail($email, 'email');
    }

    errTel($tel, 'tel');

// ④エラーなしの場合：取得した内容をサニタイズ→セッションに登録

    if( empty($err_msg) ){
        $_SESSION['name'] = sanitize($name);
        $_SESSION['kana'] = sanitize($kana);
        $_SESSION['tel'] = sanitize($tel);
        $_SESSION['email'] = sanitize($email);
        $_SESSION['body'] = sanitize($body);


        header('Location:confirm.php');
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
			<form action='' method="post">
                <h3>下記の項目をご記入の上送信ボタンを押してください</h3>
                <p>送信頂いた件につきましては、当社より折り返しご連絡を差し上げます。</p>
                <p>なお、ご連絡までに、お時間を頂く場合もございますので予めご了承ください。</p>
                <p><span class="required">*</span>は必須項目となります。</p>
                <dl>
                    <!-- ============ 氏名 ============ -->
                    <dt>
                        <label for="name">氏名</label><span class="required">*</span>
                    </dt>
                    <dd class="error">
                        <!--PHPエラーメッセージ-->
                        <?php echo $err_msg['name'] ?? '' ; ?>
                    </dd>
                    <dd><input type="text" name="name" id="name" placeholder="山田太郎"
                        value="<?php if(!empty($_POST['name'])) echo $_POST['name']; ?>">
                    </dd>
                    <!-- ================================= -->
                    <!-- ============ フリガナ ============ -->
                    <dt>
                        <label for="kana">フリガナ</label><span class="required">*</span>
                    </dt>
                    <dd class="error">
                        <!--PHPエラーメッセージ-->
                        <?php echo $err_msg['kana'] ?? '' ; ?>
                    </dd>             
                    <dd>
                        <input type="text" name="kana" id="kana" placeholder="ヤマダタロウ"
                        value="<?php if(!empty($_POST['kana'])) echo $_POST['kana']; ?>">
                    </dd>
                    <!-- ================================= -->
                    <!-- ============ 電話番号 ============ -->
                    <dt>
                        <label for="tel">電話番号</label>
                    </dt>
                    <dd class="error">
                        <!--PHPエラーメッセージ-->
                        <?php echo $err_msg['tel'] ?? '' ; ?>
                    </dd>
                    <dd>
                        <input type="text" name="tel" id="tel" placeholder="09012345678"
                        value="<?php if(!empty($_POST['tel'])) echo $_POST['tel']; ?>">
                    </dd>
                    <!-- ================================= -->
                    <!-- ============ メールアドレス ============ -->
                    <dt>
                        <label for="email">メールアドレス</label><span class="required">*</span>
                    </dt>
                    <dd class="error">
                        <!--PHPエラーメッセージ-->
                        <?php echo $err_msg['email'] ?? '' ; ?>
                    </dd>
                    <dd>
                        <input type="text" name="email" id="email" placeholder="test@test.co.jp"
                        value="<?php if(!empty($_POST['email'])) echo $_POST['email']; ?>">
                    </dd>
                </dl>
                <!-- ================================= -->
                <!-- ============ お問合せ ============ -->
                <h3><label for="body">お問い合わせ内容をご記入ください<span class="required">*</span></label></h3>
                <dl class="body">
                    <dd class="error">
                        <!--エラーメッセージ-->
                        <?php echo $err_msg['body'] ?? '' ; ?>
                    </dd>
                    <dd>
                        <textarea name="body" id="body"><?php if(!empty($_POST['body'])) echo $_POST['body']; ?></textarea>
                    </dd>
                    <dd>
                        <button type="submit" id="submitButton">送　信</button>
                    </dd>
                </dl>
                <!-- ================================= -->
			</form>
        </div>
    </section>

    <table class="db-table">
        <thead>
            <tr class=table-item>
                <th>氏名</th>
                <th>フリガナ</th>
                <th>電話番号</th>
                <th>メールアドレス</th>
                <th>お問合せ内容</th>
                <th class="table-button"></th>
                <th class="table-button"></th>
            </tr>
        </thead>

        <?php foreach($result as $key => $data): ?>

        <tbody>
            <tr class="table-body">
                <td><?php echo $data['name']; ?></td>
                <td><?php echo $data['kana']; ?></td>
                <td><?php echo $data['tel']; ?></td>
                <td><?php echo $data['email']; ?></td>
                <td><?php echo $data['body']; ?></td>
                <!-- a要素のhref属性に編集ページのファイル名を指定し、さらに「?」で区切った上でGETパラメータとして投稿ID「message_id」を付与。
                    これで、リンクをクリックしたときに投稿IDも同時に渡されるので、編集ページが開いた後にこのパラメータから投稿データを取得することができる。 -->
                <td class="table-button"><button><a href="edit.php?c_id=<?php echo $data['id']; ?>">編集</a></button></td>
                <td class="table-button"><button type="button" id= "deleteBtn">
                    <a href="delete.php?c_id=<?php echo $data['id']; ?>" onclick="return confirm('削除してよろしいですか？')">削除</a></button></td>
            </tr>
        </tbody>
        <?php endforeach; ?>
    </table>


    
    

    <?php
        include('footer.php');
    ?>

    <script src="http://cdnjs.cloudflare.com/ajax/libs/vue/0.11.5/vue.min.js"></script>
    <script type="text/javascript" src="js/script.js"></script>
    <script type="text/javascript" src="js/valid.js"></script>


</body>
</html>



