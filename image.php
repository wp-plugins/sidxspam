<?
@session_start();
$sxscode = $_SESSION['_sxs_'.$_GET['sid']];
if(!$sxscode){
	$sxscode = 'ERROR';
}
$im = imagecreate(50,20);
$white = ImageColorAllocate($im, 255,255,255);
$black = ImageColorAllocate($im, 0,0,0);
imagestring($im, 5, 6, 3, $sxscode, $black);
header("Content-type: image/png");
header("Cache-Control: no-cache");
ImagePNG($im);
ImageDestroy($im);
?>