// ＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝
// バリデーション
// ＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝
const submitButton = document.getElementById('submitButton');
submitButton.addEventListener("click",function(){


// フォームの入力値を取得
const submitName = document.getElementById("name");
const submitKana = document.getElementById("kana");
const submitTel = document.getElementById("tel");
const tel = submitTel.value;
console.log(tel); 
const submitEmail = document.getElementById("email");
const email = submitEmail.value;
const submitBody = document.getElementById("body");

// エラーメッセージ
const err_empty = '入力必須です。';
const err_max = '10文字以内でご入力ください。';
const err_tel = '電話番号は0-9の数字のみでご入力ください。';
const err_email = 'メールアドレスは正しい形式でご入力ください。';

// エラーメッセージアラート
let err_msg = [];

// 氏名バリデーション
if(submitName.value === ""){
    err_msg.push('氏名は'+err_empty);
}else if(submitName.value.length > 10){
    err_msg.push('氏名は'+err_max);
}

// かなバリエーション
if(submitKana.value === ""){
    err_msg.push('フリガナは'+err_empty);
}else if(submitKana.value.length > 10){
    err_msg.push('フリガナは'+err_max);
}

// 電話番号バリデーション
if( tel.length !== 0 ){
    if(!tel.match(/^[0-9]+$/)){
        err_msg.push(err_tel);
    }
}


// メアドチェック
if(email === ''){
    err_msg.push('メールアドレスは'+err_empty);
}else if(!email.match(/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/)){
    err_msg.push(err_email);
}

// テキストエリアチェック
if(submitBody.value === ''){
    err_msg.push('お問合せ内容は'+err_empty);
}


if(err_msg.length !== 0){
    alert(err_msg.join('\n'));
    location.href = "contact.php";
}

});




