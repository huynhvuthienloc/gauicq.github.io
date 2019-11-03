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


$curl->xemtrang('http://ipro.club/account');

if($curl->kiemtraketnoi()){
	
$fullink="http://ipro.club/account/login";

$var = "email=dragon_tigergate72@yahoo.com&password=longdaica";

$curl->guipost($var);


$curl->xemtrang($fullink);
		if($curl->kiemtraketnoi()){
			
		if(stripos($curl->xuatnoidung, "Members") !== false){
			
		$curl->xemtrang('http://ipro.club/aliexpress/get_sock/index.php');
		
		//file_put_contents('sock.html', $curl->xuatnoidung);	
			
		$listsock = getStr($curl->xuatnoidung,'{"socks":"','"}'); 	
//	preg_match_all ('/"PROXY_IP":"([^>]*)","PROXY_LAST_UPDATE":"([^>]*)","PROXY_PORT":"([^>]*)"/U', $listsock, $pat_array);
 $listsock = explode(',', $listsock);

foreach($listsock as $k => $url)
{

echo strip_tags($url) . "\n" ;
}


		}


		}
			
			
			
			
			
			else{
	
	
		echo '';
	
		}	
	
	
	
}
else 
{
	echo "";
}



?>