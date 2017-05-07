<?php
/**************************************************

	GETメソッドのリクエスト [アクセストークン]

**************************************************/

// 設定
$api_key = "pB5befMOwoPcrl69BhA5HcMCW" ;	// APIキー
$api_secret = "WdKVv4NZCqDAzvl1Zb1vb5Fv7THqYinV9qI812opDzbcZBlQO8" ;	// APIシークレット
$access_token = "860048892020129792-6z629Ingx7EyC9EEeNaR5S1S77mqpz6" ;	// アクセストークン
$access_token_secret = "MMHwmoG2LNsJsmldu4KQZ6h6nkjftneCNFHOWbrHEnGCI" ;	// アクセストークンシークレット
$request_url = 'https://api.twitter.com/1.1/statuses/user_timeline.json' ;	// エンドポイント
$request_method = 'GET' ;

// パラメータA (リクエストのオプション)
$params_a = array(
	'screen_name' => '@arayutw' ,
	'count' => 10 ,
) ;

// キーを作成する (URLエンコードする)
$signature_key = rawurlencode( $api_secret ) . '&' . rawurlencode( $access_token_secret ) ;

// パラメータB (署名の材料用)
$params_b = array(
	'oauth_token' => $access_token ,
	'oauth_consumer_key' => $api_key ,
	'oauth_signature_method' => 'HMAC-SHA1' ,
	'oauth_timestamp' => time() ,
	'oauth_nonce' => microtime() ,
	'oauth_version' => '1.0' ,
) ;

// パラメータAとパラメータBを合成してパラメータCを作る
$params_c = array_merge( $params_a , $params_b ) ;

// 連想配列をアルファベット順に並び替える
ksort( $params_c ) ;

// パラメータの連想配列を[キー=値&キー=値...]の文字列に変換する
$request_params = http_build_query( $params_c , '' , '&' ) ;

// 一部の文字列をフォロー
$request_params = str_replace( array( '+' , '%7E' ) , array( '%20' , '~' ) , $request_params ) ;

// 変換した文字列をURLエンコードする
$request_params = rawurlencode( $request_params ) ;

// リクエストメソッドをURLエンコードする
// ここでは、URL末尾の[?]以下は付けないこと
$encoded_request_method = rawurlencode( $request_method ) ;
 
// リクエストURLをURLエンコードする
$encoded_request_url = rawurlencode( $request_url ) ;
 
// リクエストメソッド、リクエストURL、パラメータを[&]で繋ぐ
$signature_data = $encoded_request_method . '&' . $encoded_request_url . '&' . $request_params ;

// キー[$signature_key]とデータ[$signature_data]を利用して、HMAC-SHA1方式のハッシュ値に変換する
$hash = hash_hmac( 'sha1' , $signature_data , $signature_key , TRUE ) ;

// base64エンコードして、署名[$signature]が完成する
$signature = base64_encode( $hash ) ;

// パラメータの連想配列、[$params]に、作成した署名を加える
$params_c['oauth_signature'] = $signature ;

// パラメータの連想配列を[キー=値,キー=値,...]の文字列に変換する
$header_params = http_build_query( $params_c , '' , ',' ) ;

// リクエスト用のコンテキスト
$context = array(
	'http' => array(
		'method' => $request_method , // リクエストメソッド
		'header' => array(			  // ヘッダー
			'Authorization: OAuth ' . $header_params ,
		) ,
	) ,
) ;

// パラメータがある場合、URLの末尾に追加
if( $params_a ) {
	$request_url .= '?' . http_build_query( $params_a ) ;
}

// cURLを使ってリクエスト
$curl = curl_init() ;
curl_setopt( $curl, CURLOPT_URL , $request_url ) ;	// リクエストURL
curl_setopt( $curl, CURLOPT_HEADER, true ) ; 	// ヘッダーを取得
curl_setopt( $curl, CURLOPT_CUSTOMREQUEST , $context['http']['method'] ) ;	// メソッド
curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER , false ) ;	// 証明書の検証を行わない
curl_setopt( $curl, CURLOPT_RETURNTRANSFER , true ) ;	// curl_execの結果を文字列で返す
curl_setopt( $curl, CURLOPT_HTTPHEADER , $context['http']['header'] ) ;	// ヘッダー
curl_setopt( $curl, CURLOPT_TIMEOUT , 5 ) ;	// タイムアウトの秒数
$res1 = curl_exec( $curl ) ;
$res2 = curl_getinfo( $curl ) ;
curl_close( $curl ) ;

// 取得したデータ
$json = substr( $res1, $res2['header_size'] ) ;	// 取得したデータ(JSONなど)
$header = substr( $res1, 0, $res2['header_size'] ) ;	// レスポンスヘッダー (検証に利用したい場合にどうぞ)

// [cURL]ではなく、[file_get_contents()]を使うには下記の通りです…
// $json = @file_get_contents( $request_url , false , stream_context_create( $context ) ) ;

// JSONを変換
// $obj = json_decode( $json ) ;	// オブジェクトに変換
// $arr = json_decode( $json, true ) ;	// 配列に変換

// HTML用
$html = '' ;

// 検証用にレスポンスヘッダーを出力 [本番環境では不要]
$html .= '<h2>取得したデータ</h2>' ;
$html .= '<p>下記のデータを取得できました。</p>' ;
$html .= 	'<h3>ボディ(JSON)</h3>' ;
$html .= 	'<p><textarea rows="8">' . $json . '</textarea></p>' ;
$html .= 	'<h3>レスポンスヘッダー</h3>' ;
$html .= 	'<p><textarea rows="8">' . $header . '</textarea></p>' ;

// アプリケーション連携の解除
$html .= '<h2 style="color:red">アプリケーション連携の解除</h2>' ;
$html .= '<p>このアプリケーションとの連携を解除するには、下記ページより、行なって下さい。</p>' ;
$html .= '<p><a href="https://twitter.com/settings/applications" target="_blank">https://twitter.com/settings/applications</a></p>' ;

// HTMLを出力
echo $html ;
