<?php
 
// �Z�b�V�����J�n
session_start();
 
// TwitterOAuth�̃R�A�t�@�C����Twitter�A�v���P�[�V�����̐ݒ�l��ǂݍ���
require_once 'common.php';
require_once 'twitteroauth/autoload.php';
     
// �uTwitter�̃R���V���}�[�L�[�v�ƁuTwitter�̃R���V���}�[�V�[�N���b�g�L�[�v���g���ăC���X�^���X��
$twitter = new Abraham\TwitterOAuth\TwitterOAuth(
            ConsumerKey, 
            ConsumerSecret
        );
 
//�R�[���o�b�NURL���Z�b�g���ĔF�؃g�[�N���̃��N�G�X�g�𔭍s
$request_token = $twitter->oauth('oauth/request_token', array('oauth_callback' => Callback));
 
// ��L�Ŏ󂯎�����uoauth_token�v�Ɓuoauth_token_secret�v���Z�b�V�����ɑ��
// �����ŃZ�b�V�����ɓ���闝�R��callback.php�ŔF�؂��s�����߂ł��B
$_SESSION['oauth_token'] = $request_token['oauth_token'];
$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
 
// Twitter�̔F�؉�ʂփ��_�C���N�g
$url = $twitter->url('oauth/authenticate', array('oauth_token' => $request_token['oauth_token']));
 
header('location: '. $url);
exit;