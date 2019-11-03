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

$curl->xemtrang('http://letushide.com/filter/socks5,all,all/list_of_free_SOCKS5_proxy_servers');

if($curl->kiemtraketnoi()){
$curl->xemtrang('http://letushide.com/filter/socks5,all,all/list_of_free_SOCKS5_proxy_servers');

$listsock = getStr($curl->xuatnoidung,'<tbody>','<div class="note">'); 	
//$listsock = strip_tags($listsock);
preg_match_all ('/<td id="link"><a href="([^"]+)">([^>]*)<\/a><\/td><td>([^>]*)<\/td>/U', $listsock, $pat_array);

//preg_match_all ('/<td id="link"><a href="([^"]+)">([^>]*)<\/a>/siU', $listsock, $pat_array);


//$listsock = str_replace(array("\n","IP address","Port","Country","Type","Checked (ago)","Check","//"), "", strip_tags($listsock));
//$listsock = strip_tags($pat_array[0]);
//echo $listsock;
//

foreach($pat_array[0] as $k => $url)
{
list($IP,$PORT) = explode('</a>', $url);
$IP = strtolower($IP);
$PORT = strtolower($PORT);
echo strip_tags($IP) . ":". strip_tags($PORT) . "\n" ;
}



	
	
	
}
else 
{
	echo "";
}

?>