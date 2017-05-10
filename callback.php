<?php
 
// �Z�b�V�����J�n
session_start();
 
// TwitterOAuth�̃R�A�t�@�C����Twitter�A�v���P�[�V�����̐ݒ�l��ǂݍ���
require_once 'common.php';
require_once 'twitteroauth/autoload.php';
 
// �Z�b�V��������ϐ��ɑ��
$request_token['oauth_token'] = $_SESSION['oauth_token'];
$request_token['oauth_token_secret'] = $_SESSION['oauth_token_secret'];
 
// �{�l�m�F
if (isset($_REQUEST['oauth_token']) && $request_token['oauth_token'] !== $_REQUEST['oauth_token']) {
    die( '����[�ł��B' );
    exit;
}
 
//OAuth �g�[�N�����p���� TwitterOAuth ���C���X�^���X��
$twitter = new Abraham\TwitterOAuth\TwitterOAuth(
                    ConsumerKey, 
                    ConsumerSecret, 
                    $request_token['oauth_token'], 
                    $request_token['oauth_token_secret']
            );
 
// token���擾
$result = $twitter->oauth("oauth/access_token", array("oauth_verifier" => $_REQUEST['oauth_verifier']));
 
$getUser = new Abraham\TwitterOAuth\TwitterOAuth(
                    ConsumerKey, 
                    ConsumerSecret, 
                    $result['oauth_token'], 
                    $result['oauth_token_secret']
            );
 
// ���[�U���擾
$user = $getUser->get("account/verify_credentials");
 
// ���[�U���̓W�J
var_dump($user);