<?php
 
// セッション開始
session_start();
 
// TwitterOAuthのコアファイルとTwitterアプリケーションの設定値を読み込み
require_once 'common.php';
require_once 'twitteroauth/autoload.php';
     
// 「Twitterのコンシュマーキー」と「Twitterのコンシュマーシークレットキー」を使ってインスタンス化
$twitter = new Abraham\TwitterOAuth\TwitterOAuth(
            ConsumerKey, 
            ConsumerSecret
        );
 
//コールバックURLをセットして認証トークンのリクエストを発行
$request_token = $twitter->oauth('oauth/request_token', array('oauth_callback' => Callback));
 
// 上記で受け取った「oauth_token」と「oauth_token_secret」をセッションに代入
// ここでセッションに入れる理由はcallback.phpで認証を行うためです。
$_SESSION['oauth_token'] = $request_token['oauth_token'];
$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
 
// Twitterの認証画面へリダイレクト
$url = $twitter->url('oauth/authenticate', array('oauth_token' => $request_token['oauth_token']));
 
header('location: '. $url);
exit;