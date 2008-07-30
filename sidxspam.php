<?php
/* 
Plugin Name: SidXSpam
Version: 3.0
Plugin URI: http://photozero.net/sidxspam/
Author: Neekey
Author URI: http://photozero.net
Description: Anti-spam by verify code that displayed in the image under the comment form.
*/ 


@session_start();
add_action('comment_form','show_sxs');
add_filter('preprocess_comment','check_sxs');



if (!defined('WP_CONTENT_DIR')) {
	define( 'WP_CONTENT_DIR', ABSPATH.'wp-content');
}
if (!defined('WP_CONTENT_URL')) {
	define('WP_CONTENT_URL', get_option('siteurl').'/wp-content');
}
if (!defined('WP_PLUGIN_DIR')) {
	define('WP_PLUGIN_DIR', WP_CONTENT_DIR.'/plugins');
}
if (!defined('WP_PLUGIN_URL')) {
	define('WP_PLUGIN_URL', WP_CONTENT_URL.'/plugins');
}



function make_seed() {
	list($usec, $sec) = explode(' ', microtime());
	return (float)$sec + ((float)$usec * 100000);
}


function sxs_create_sid(){
	mt_srand(make_seed());
	$sxscode = '';$sxs_sid_code = '';
	$sxsarray = array('a','b','c','d','e','f','g','h','i','j','k','m','n','p','q','r','s','t','u','v','w','x','y','z');
	$sxs_sid_array = array('1','2','3','4','5','6','7','8','9','0');
	shuffle($sxsarray);
	shuffle($sxs_sid_array);
	for( ;strlen($sxscode)<=11; ){
		$sxscode .= $sxsarray[mt_rand(0, count($sxsarray))];
	}
	for( ;strlen($sxs_sid_code)<=3; ){
		$sxs_sid_code .= $sxs_sid_array[mt_rand(0, count($sxs_sid_array))];
	}
	$_SESSION['_sxs_'.$sxscode] = $sxs_sid_code;
	return array($sxscode,$sxs_sid_code);
}

function show_sxs(){
	$sxscode = sxs_create_sid();
	$sxs_url = WP_PLUGIN_URL.'/sidxspam';
	
	echo '<br />
<abbr title="Just check you are a person not a robot :)">Verify Code</abbr>&nbsp;&nbsp;
<img alt="If you cannot see the CheckCode image,please refresh the page again!" src="'.$sxs_url.'/image.php?sid='.$sxscode[0].'" />
<input type="hidden" name="sxssid" value="_sxs_'.$sxscode[0].'" />
<input type="text" name="sxscode" maxlength="4" style="width:60px" title="Type here what you see in the left image" />';
}

function check_sxs($commentdata){

	if ($_SESSION[$_POST['sxssid']] !== $_POST['sxscode']) {
		//
		die(__('
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /><title>Error</title></head>
<body>
<h1 style="color:red">Error:</h1>Sorry!Your Verify Code is wrong.Please go back and try again!<br /><br />
<form name="form">
Your comment:<br />
<textarea rows="5" cols="40" name="copy">'.$commentdata['comment_content'].'</textarea><br /><br />
<a href="javascript:history.back(-1);">Back</a>
</form>
</body></html>'
));
	}
	//unset($_SESSION[$_POST['sxssid']]);
    return $commentdata;

}
?>