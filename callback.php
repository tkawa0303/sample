<?php
 
// セッション開始
session_start();
 
// TwitterOAuthのコアファイルとTwitterアプリケーションの設定値を読み込み
require_once 'common.php';
require_once 'twitteroauth/autoload.php';
 
// セッション情報を変数に代入
$request_token['oauth_token'] = $_SESSION['oauth_token'];
$request_token['oauth_token_secret'] = $_SESSION['oauth_token_secret'];
 
// 本人確認
if (isset($_REQUEST['oauth_token']) && $request_token['oauth_token'] !== $_REQUEST['oauth_token']) {
    die( 'えらーです。' );
    exit;
}
 
//OAuth トークンも用いて TwitterOAuth をインスタンス化
$twitter = new Abraham\TwitterOAuth\TwitterOAuth(
                    ConsumerKey, 
                    ConsumerSecret, 
                    $request_token['oauth_token'], 
                    $request_token['oauth_token_secret']
            );
 
// tokenを取得
$result = $twitter->oauth("oauth/access_token", array("oauth_verifier" => $_REQUEST['oauth_verifier']));
 
$getUser = new Abraham\TwitterOAuth\TwitterOAuth(
                    ConsumerKey, 
                    ConsumerSecret, 
                    $result['oauth_token'], 
                    $result['oauth_token_secret']
            );
 
// ユーザ情報取得
$user = $getUser->get("account/verify_credentials");
 
// ユーザ情報の展開
var_dump($user);