<?php
require ('../xyly_mail.php');
require ('../xuly_curl.php');
include("../use.php");

$mail = new Mail();
$curl = new xuly_curl();
xoa_cookies();
$curl->trinhduyet(random_uagent());


function getStr($string,$start,$end){
	$str = explode($start,$string);
	$str = explode($end,$str[1]);
	return $str[0];
}
xoa_cookies();

include("all/ipro.php");
include("all/ksrv.php");
include("all/gatherproxy.php");
include("all/letushide.php");

?>