<?php

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