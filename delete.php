<?php

require 'valid.php';
debug('「「「「「「「「「「「「「「「「');
debug('　deleteページ');
debug('「「「「「「「「「「「「「「「「');

session_start();

$referer = $_SERVER['HTTP_REFERER']; //直前にアクセスしてきたURL
$url = parse_url($referer);
$url = basename($referer);
debug('アクセス元のURL：'.$url);

if( strpos($url, 'contact.php') !== false ){
    // アクセス元がcontact.phpの場合
    debug('前ページはcontact.phpです。');
}else{
    debug('不正アクセスがあります。');
    header("Location:contact.php");
}

//================================
// 画面処理
//================================

$contact_id = (!empty($_GET['c_id'])) ? $_GET['c_id'] : '';

try{
    // DBへ接続
    $dbh = dbConnect();
    $sql = 'DELETE FROM contacts WHERE id = :contact_id';
    $data = [':contact_id' => $contact_id];
    $dbh->beginTransaction();

    $stmt = queryPost($dbh, $sql, $data);

    if($stmt){
        $dbh->commit();
        debug('削除しました。');
        header('Location:contact.php');
    }
}catch(Exception $e){
    $dbh->rollBack();
    error_log('エラー発生：'.$e->getMessage());
}



?>
