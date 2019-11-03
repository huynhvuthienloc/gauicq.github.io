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

$curl->xemtrang('http://k-srv.net/get.php?getsocks');

if($curl->kiemtraketnoi()){
$curl->xemtrang('http://k-srv.net/get.php?getsocks');

$listsock = $curl->xuatnoidung; 	
//$listsock = strip_tags($listsock);

//preg_match_all ('/"PROXY_IP":"([^>]*)","PROXY_LAST_UPDATE":"([^>]*)","PROXY_PORT":"([^>]*)"/U', $listsock, $pat_array);

//preg_match_all ('/<td id="link"><a href="([^"]+)">([^>]*)<\/a>/siU', $listsock, $pat_array);


$listsock = str_replace("@", "", strip_tags($listsock));
//$listsock = strip_tags($pat_array[0]);
echo $listsock;
//




	
	
	
}
else 
{
	echo "";
}

?>