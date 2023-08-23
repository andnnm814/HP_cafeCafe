<?php

date_default_timezone_set('Asia/Tokyo');
session_start();

require ('valid.php');

debug('「「「「「「「「「「「「「「「「「「「「「');
debug('「　editページ　');
debug('「「「「「「「「「
「「「「「「「「「「「「');

//================================
// ダイレクトアクセス禁止処理
//================================
$referer = $_SERVER['HTTP_REFERER']; 
$url = parse_url($referer); //URLの構成を要素とした連想配列が返される
$url = basename($referer); //ファイル名を返す
debug($url);

if( strpos($url, 'contact.php') !== false){
    debug('前ページはcontact.phpです。');
}elseif( strpos($url, 'edit.php') !== false){
    debug('前ページはedit.phpです。');
}else{
    debug('不正アクセスがあります。');
    header('Location:contact.php');
}


//================================
// 画面処理
//================================

// 前ページからDBのidを取得
$contact_id = (!empty($_GET['c_id'])) ? $_GET['c_id'] : '';
debug($contact_id);

// DBデータを取得
$dbFormData = getUserId($contact_id);
debug('取得したユーザー情報：'.print_r($dbFormData,true));

// POST送信ありの場合
if(!empty($_POST)){
    debug('POST送信あり');
    debug('POST情報：'.print_r($_POST,true));

    // POSTされた値を変数に格納
    $nameEdit = $_POST['name']; 
    $kanaEdit = $_POST['kana'];
    $telEdit = $_POST['tel'];
    $emailEdit = $_POST['email'];
    $bodyEdit = $_POST['body'];

    debug('POSTされた名前：'.$nameEdit);
    debug('POSTされたカナ：'.$kanaEdit);
    debug('POSTされたTEL：'.$telEdit);
    debug('POSTされたメアド：'.$emailEdit);
    debug('POSTされたbody：'.$bodyEdit);

    
    // バリデーションチェック
    errEmpty($nameEdit, 'name');
    errEmpty($kanaEdit, 'kana');
    errEmpty($emailEdit, 'email');
    errEmpty($bodyEdit, 'body');


    if( empty($err_msg) ){

        // DBの情報とPOSTされた値が異なる場合
        // 名前の文字数チェック
        if($dbFormData['name'] !== $nameEdit){
            errMaxlen($nameEdit,'name');
            if(!empty($err_msg['name'])){
                debug($err_msg['name']);
            }
        }

        // フリガナの文字数チェック
        if($dbFormData['kana'] !== $kanaEdit){
            errMaxlen($kanaEdit,'kana');
            if(!empty($err_msg['kana'])){
                debug($err_msg['kana']);
            }
        }

        // TEL形式チェック
        if($dbFormData['tel'] !== $telEdit){
            errTel($telEdit,'tel');
            if(!empty($err_msg['tel'])){
                debug($err_msg['tel']);
            }
        }

        // email形式チェック
        if($dbFormData['email'] !== $emailEdit){
            errEmail($emailEdit,'email');
            if(!empty($err_msg['email'])){
                debug($err_msg['email']);
            }
        }

        if(empty($err_msg)){
            debug('バリデーションOK');

            // サニタイズした値をセッションへ登録
            $_SESSION['name'] = sanitize($nameEdit);
            $_SESSION['kana'] = sanitize($kanaEdit);
            $_SESSION['tel'] = sanitize($telEdit);
            $_SESSION['email'] = sanitize($emailEdit);
            $_SESSION['body'] = sanitize($bodyEdit);

            debug('入力情報をセッションへ登録完了');
            header('Location:confirm.php?c_id='.$contact_id);

        }
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
                        <?php echo $err_msg['name'] ?? ''; ?>
                    </dd>
                    <dd><input type="text" name="name" id="name" placeholder="山田太郎"
                        value="<?php echo getFormData('name'); ?>">
                    </dd>
                    <!-- ================================= -->
                    <!-- ============ フリガナ ============ -->
                    <dt>
                        <label for="kana">フリガナ</label><span class="required">*</span>
                    </dt>
                    <dd class="error">
                        <!--PHPエラーメッセージ-->
                        <?php echo $err_msg['kana'] ?? ''; ?>
                    </dd>             
                    <dd>
                        <input type="text" name="kana" id="kana" placeholder="ヤマダタロウ"
                        value="<?php echo getFormData('kana'); ?>">
                    </dd>
                    <!-- ================================= -->
                    <!-- ============ 電話番号 ============ -->
                    <dt>
                        <label for="tel">電話番号</label>
                    </dt>
                    <dd class="error">
                        <!--PHPエラーメッセージ-->
                        <?php echo $err_msg['tel'] ?? ''; ?>
                    </dd>
                    <dd>
                        <input type="text" name="tel" id="tel" placeholder="09012345678"
                        value="<?php echo getFormData('tel'); ?>">
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
                        value="<?php echo getFormData('email'); ?>">
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
                        <textarea name="body" id="body"><?php echo getFormData('body'); ?></textarea>
                    </dd>
                    <dd>
                        <button type="submit" id="submitButton">更 新</button>
                    </dd>
                </dl>
                <!-- ================================= -->
			</form>
        </div>
    </section>



    <?php
        include('footer.php');
    ?>


    <script src="http://cdnjs.cloudflare.com/ajax/libs/vue/0.11.5/vue.min.js"></script>
    <script type="text/javascript" src="js/script.js"></script>
    <script type="text/javascript" src="js/valid.js"></script>

</body>
</html>



