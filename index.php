<?php

define('MBEE_DIR', dirname(__FILE__));
define('MBEE_SCRIPTS_DIR', MBEE_DIR.'/scripts/');
//debug模式：js不会压缩，以合并后的源码方式输出
//true: 开启压缩
define('MBEE_DEBUG', true);

//加载Mbee
require_once "mbee.php";

$req_uri = $_SERVER['REQUEST_URI'];

$pathinfo = pathinfo($req_uri);

$dirname = str_replace('/','', $pathinfo['dirname']);
$filename = $pathinfo['filename'];

if(!empty($filename)){
	Mbee::make($dirname.'/'.$filename);
}