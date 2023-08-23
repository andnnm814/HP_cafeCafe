<?php

date_default_timezone_set('Asia/Tokyo');

// ==================
// ログ
// ==================
ini_set('log_errors', 'on');
ini_set('error_log', 'php_error.log');

// ==================
// デバック
// ==================
$debug_flg = true;

function debug($str){
    global $debug_flg;
    if(!empty($debug_flg)){
        error_log('デバッグ：'.$str);
    }
}



// ==================
// データベース
// ==================

// DB接続関数
function dbConnect(){
    // DBへの接続準備
    $dsn = 'mysql:dbname=cafe;host=localhost;charset=utf8';
    $user = 'root';
    $password = 'root';
    $options = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
    );

    // PDOオブジェクトの生成
    $dbh = new PDO($dsn, $user, $password, $options);
    return $dbh;
}

// SQL実行関数
function queryPost($dbh, $sql, $data){
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    return $stmt;
}

// DBデータ取得関数
function getUserId($contact_id){
    debug('DBデータを取得します。');
    //例外処理
    try{
        $dbh = dbConnect();
        $sql = 'SELECT * FROM cafe.contacts WHERE id = :contacts_id';
        $data = array(':contacts_id' => $contact_id);
        $stmt = queryPost($dbh, $sql, $data);

        if($stmt){
            debug('クエリ成功');
        }else{
            debug('クエリに失敗しました');
        }

    }catch (Exception $e){
        error_log('エラー発生：'.$e->getMessage());
    }
    return $stmt->fetch(PDO::FETCH_ASSOC);
}



// ==================
// バリデーション
// ==================

$err_msg = [];

// 未入力チェック関数
function errEmpty($post,$key){
    if( empty($post) ){
        global $err_msg;
        $err_msg[$key] = '入力必須です';
    }
}

// 文字数チェック関数
function errMaxlen($post,$key){
    if( mb_strlen($post) > 10 ){
        global $err_msg;
        $err_msg[$key] = '10文字以内でご入力ください。';
}
}

// 電話番号チェック関数
function errTel($post,$key){
    if(!empty($post)){
        if( !preg_match('/^[0-9]+$/',$post) ){
            global $err_msg;
            $err_msg[$key] = '0-9の数字のみでご入力ください。';
        }
    }
}

// メアドチェック関数
function errEmail($post,$key){
    if( !preg_match('/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/', $post )){
        global $err_msg;
        $err_msg[$key] = 'メアドは正しい形式でご入力ください。';
    }
}

// サニタイズ関数
function sanitize($post){
    return htmlspecialchars($post,ENT_QUOTES);
}

// ==================
// フォーム入力保持 
// ==================

function getFormData($str){
    global $dbFormData;

    if(!empty($dbFormData)){
        if(!empty($err_msg[$str])){
            if(isset($_POST[$str])){
                // DBあり+エラーあり+POSTあり
                return $_POST[$str];
            }else{
                // DBあり+エラーあり+POSTなし
                return $dbFormData[$str];
            }
        } else {
            // DBあり＋エラーなし＋POSTあり＆DB≠POST
            if(isset($_POST[$str]) && $_POST[$str] !== $dbFormData[$str]){
                return $_POST[$str];
            }else{
                // DBあり＋エラーなし＋POSTなし||DB=POST
                return $dbFormData[$str];
            }
        }
    }else{
        if(isset($_POST[$str])){
            // DBなし＋POSTあり
            return $_POST[$str];
        }
    }
}





?>

