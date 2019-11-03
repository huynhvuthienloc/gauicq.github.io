<?php
$curl->xemtrang('http://gatherproxy.com/');

if($curl->kiemtraketnoi()){
$curl->xemtrang('http://gatherproxy.com/');

$listsock = getStr($curl->xuatnoidung,'<div class="proxy-list">','</table>'); 	
//$listsock = strip_tags($listsock);

preg_match_all ('/"PROXY_IP":"([^>]*)","PROXY_LAST_UPDATE":"([^>]*)","PROXY_PORT":"([^>]*)"/U', $listsock, $pat_array);

//preg_match_all ('/<td id="link"><a href="([^"]+)">([^>]*)<\/a>/siU', $listsock, $pat_array);


//$listsock = str_replace(array("\n","IP address","Port","Country","Type","Checked (ago)","Check","//"), "", strip_tags($listsock));
//$listsock = strip_tags($pat_array[0]);
//echo $listsock;
//


foreach($pat_array[0] as $k => $url)
{
$IP =getStr($url, '"PROXY_IP":"','"'); 	
$PORT = getStr($url, '"PROXY_PORT":"','"'); 	
echo strip_tags($IP) . ":". strip_tags($PORT) . "\n" ;
}



	
	
	
}
else 
{
	echo "";
}

?>