<?php
require ('./xyly_mail.php');
require ('./xuly_curl.php');
include './card_class.php';
include './info.php';
include("./use.php");
include './Crypt/RSA.php';
include './Crypt/AES.php';


function check_exp($cc) {
    $today = getdate();
    if ($cc['year'] + 0 < $today['year'] + 0)
        return false;
    if ($cc['year'] + 0 == $today['year'] + 0) {
        if ($cc['mon'] + 0 < $today['mon'] + 0)
            return false;
    }
    return true;
}

function isEmail($email) {
    if (!preg_match("/^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$/i", $email))
        return false;
    return true;
}

function get_char($str, $sl) {
    $str = strip_tags($str);
    $mang = explode(" ", $str);
    $chuoi = "";
    for ($i = 0; $i < $sl; $i++) {
        $chuoi .= $mang[$i] . " ";
    }
    return trim($chuoi);
}

function get_img($str) {
    $mang = explode("<img", $str);
    $i = rand(1, count($mang) - 1);
    $mang = explode("src=", $mang[$i]);
    $mang = str_replace("'", "", $mang[1]);
    $mang = str_replace('"', "", $mang);
    $mang = explode(" ", $mang);
    if ($mang[0])
        return $mang[0];
    return "images/no-img.png";
}

function phantrang($now, $max, $sl) {
    if ($max < $sl) {
        $mang[0] = 1;
        $mang[1] = $max;
    } else {
        if ($now >= $sl) {
            $mang[0] = $now - 2;
            $mang[1] = $now + 2;
        } else {
            $mang[0] = 1;
            $mang[1] = $sl;
        }
    }
    return $mang;
}

function getsock($socks) {
    preg_match("/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}[:|-\s\/]\d{1,7}/", $socks, $s);
    return $s[0];
}

function value($s, $from, $to) {
    $s = explode($from, $s);
    $s = explode($to, $s[1]);
    return $s[0];
}



function get_string_between($string, $start, $end) {
    $string = " " . $string;
    $ini = strpos($string, $start);
    if ($ini == 0)
        return "";
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}

function right($value, $count) {
    return substr($value, ($count * -1));
}

function left($string, $count) {
    return substr($string, 0, $count);
}

function test($vao) {
    if (is_numeric($vao))
        return 1;
    return 0;
}

function getStr($string, $start, $end) {
    $str = explode($start, $string);
    $str = explode($end, $str[1]);
    return $str[0];
}


function get_string($string, $start, $end) {
    $string = " " . $string;
    $ini = strpos($string, $start);
    if ($ini == 0)
        return "";
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}

function roundup($a) {
    $b = explode(".", $a);
    if ($b[1] + 0 > 0)
        return $b[0] + 1;
    return $b[0] + 0;
}

//	****** WORKS CONFIG ******
	function inStr($s, $as)
	{
		$s = strtoupper($s);
		if(!is_array($as)) $as = array($as);
		for($i=0;$i<count($as);$i++) if(strpos(($s),strtoupper($as[$i])) !== false) return true;
		return false;
	}

//	****** RANDOM STRING ******
	function RequestRandomStrings($x)
	{
		$Strings = "abcdefghiklmnopqrstuvwxyz0123456789";
		$Str = "";
		while(strlen($Str) < $x)
		{
			$Rand = Rand(0,strlen($Strings));
			$Str .= substr($Strings,$Rand,1);
		}
		return $Str;
	}
	function RequestNumberRandomStrings($x)
	{
		$Strings = "0123456789";
		$Str = "";
		while(strlen($Str) < $x)
		{
			$Rand = Rand(0,strlen($Strings));
			$Str .= substr($Strings,$Rand,1);
		}
		return $Str;
	}
	function RequestTextRandomStrings($x)
	{
		$Strings = "abcdefghiklmnopqrstuvwxyz";
		$Str = "";
		while(strlen($Str) < $x)
		{
			$Rand = Rand(0,strlen($Strings));
			$Str .= substr($Strings,$Rand,1);
		}
		return $Str;
	}


//XONG




	function Scan_Old($String, $Begin, $End)
	{
		$Ram = explode($Begin, $String);
		for($i = 1; $i < count($Ram); $i++)
		{
			if(inStr($Ram[$i], $End))
			{
				list($Arrayi) = explode($End, $Ram[$i]);
				$Proladay[] = $Arrayi;
			}
		}
		return $Proladay;
	}
	function Scan_New($String, $Begin, $End)
	{
		$Ram = explode($Begin, $String);
		for($i = 2; $i <= count($Ram); $i++)
		{
			$Rami = explode($Begin, $String, $i);
			list($Arrayi) = explode($End, $Rami[count($Rami) - 1]);
			$Proladay[] = $Arrayi;
		}
		return $Proladay;
	}
	function ScanInformationCreditsCard($ccline)
	{
		$xy = array("|", "\\", "/", "-", ";", " ");
		$sepe = $xy[0]; 
		foreach($xy as $v) if(substr_count($ccline, $sepe) < substr_count($ccline, $v)) $sepe = $v; 
		$x = explode($sepe, $ccline); 
		foreach ($xy as $y) $x = str_replace($y,"",str_replace(" ","",$x)); 
		foreach ($x as $xx)
		{
			$xx = trim($xx); 
			if(is_numeric($xx))
			{
				$yy = strlen($xx); 
				switch($yy)
				{ 
					case 15: 
					if(substr($xx,0,1)==3)
					{ 
						$ccnum['num'] = $xx; 
						$ccnum['type'] = "A"; 
					} 
					break; 
					case 16: 
					switch(substr($xx,0,1))
					{
						case '4': 
						$ccnum['num'] = $xx; 
						$ccnum['type'] = "V"; 
						break; 
						case '5': 
						$ccnum['num'] = $xx; 
						$ccnum['type'] = "M"; 
						break; 
						case '6': 
						$ccnum['num']=$xx; 
						$ccnum['type'] = "D"; 
						break; 
					}
					break; 
					case 1: 
					if(($xx >= 1) and ($xx <=12) and (!isset($ccnum['mon']))) $ccnum['mon'] = "0".$xx; 
					case 2: 
					if(($xx >= 1) and ($xx <=12) and (!isset($ccnum['mon'])))    $ccnum['mon'] = $xx; 
					elseif(($xx >= 11) and ($xx <= 21) and (isset($ccnum['mon'])) and (!isset($ccnum['year']))) $ccnum['year'] = "20".$xx; 
					break;
					case 3:
					$ccv['cv3'] = $xx;
					break;
					case 4: 
					if(($xx >= 2011) and ($xx <= 2021) and (isset($ccnum['mon']))) $ccnum['year'] = $xx; 
					elseif((substr($xx,0,2) >= 1) and (substr($xx,0,2) <=12) and (substr($xx,2,2)>= 11) and (substr($xx,2,2) <= 21) and (!isset($ccnum['mon'])) and (!isset($ccnum['year'])))
					{
						$ccnum['mon'] = substr($xx,0,2); 
						$ccnum['year'] = "20".substr($xx,2,2); 
					}
					else $ccv['cv4'] = $xx;
					break;
					case 5:
					$ccnum['z'] = $xx;
					break;
					case 6:
					if((substr($xx,0,2) >= 1) and (substr($xx,0,2) <=12) and (substr($xx,2,4)>= 2011) and (substr($xx,2,4) <= 2021))
					{
						$ccnum['mon'] = substr($xx,0,2);
						$ccnum['year'] = substr($xx,2,4);
					}
					break;
				}
			}
		}
		if($ccnum['type'] == "A") $ccnum['cvv'] = $ccv['cv4'];
		else $ccnum['cvv'] = $ccv['cv3'];
		if($ccnum['z']) $ccnum['zip'] = $ccnum['z'];
		else $ccnum['zip'] = "75248";
		if(isset($ccnum['num']) && isset($ccnum['type']) && CheckExpirationCreditsCard($ccnum['mon'], $ccnum['year'])) return TrimArray($ccnum);
		else return false;
	}
	function CheckExpirationCreditsCard($mon, $year)
	{
		if($year < date('Y')) return false;
		elseif($year > date('Y')) return true;
		elseif($mon < date('m')) return false;
		else return true;
	}
	function RemoveDupebyCreditsCardNumber($cclist)
	{
		foreach($cclist as $ccline)
		{
			$cc = ScanInformationCreditsCard($ccline);
			$CcNum = str_replace(array(" ", "-"),"", $cc['num']);
			if(strlen($CcNum)=="16" || strlen($CcNum)=="15")
			{
				if($CheckMP[$CcNum] != true)
				{
					$CheckMP[$CcNum] = true;
					$New[] = $ccline;
				}
			}
		}
		return TrimArray($New);
	}
	function SoftListbyDateExpiration($cclist)
	{
		foreach ($cclist as $cc)
		{
			$ccnum = ScanInformationCreditsCard($cc); 
			if($ccnum)
			{ 
				$_d = $ccnum['year'].$ccnum['mon']; 
				$order[$_d][] = $cc; 
			}
			else $order['e'][] = $cc;
		}
		ksort($order); 
		if(!is_null($order)) foreach ($order as $_d) foreach ($_d as $cc) $ok[] = $cc; 
		if(!is_null($order['e'])) foreach ($order['e'] as $cc) $ok[]=$cc; 
		return $ok;
	}
	
	
	
	
function checkMon($date,$type){
    $len = strlen($date);
    if ($type == 2){
        if($len == 2){
            return $date;
        }
        elseif($len == 1){
            switch($date){
                case '1':  $date='01'; break;
                case '2':  $date='02'; break;
                case '3':  $date='03'; break;
                case '4':  $date='04'; break;
                case '5':  $date='05'; break;
                case '6':  $date='06'; break;
                case '7':  $date='07'; break;
                case '8':  $date='08'; break;
                case '9':  $date='09'; break;
            }
        }
        return $date;
    }
    elseif ($type == 1){
        if($len == 2){
            switch ($date){
                case '01':  $date='1'; break;
                case '02':  $date='2'; break;
                case '03':  $date='3'; break;
                case '04':  $date='4'; break;
                case '05':  $date='5'; break;
                case '06':  $date='6'; break;
                case '07':  $date='7'; break;
                case '08':  $date='8'; break;
                case '09':  $date='9'; break;
                case '10': $date='10'; break;
                case '11': $date='11'; break;
                case '12': $date='12'; break;
            }
            return $date;
        }
        elseif($len == 1) return $date;
    }
    else return false;
}


function checkYear($date,$type){
	$len = strlen($date);
	if($type == 4){
		if($len == 4) return $date;
		elseif($len == 2) return "20".$date;
	}
	elseif($type == 2){
		if($len == 2) return $date;
		elseif($len == 4) return substr($date,-2);
	}
	else return false;
}

function multi_explode($pattern, $string, $standardDelimiter = ':'){
    $string = preg_replace(array($pattern, "/{$standardDelimiter}+/s"), $standardDelimiter, $string);
    return explode($standardDelimiter, $string);
}

function info($ccline,$type){
	$iscvv = 1;
	$pattern = '/[:\|\\\\\/\s]/';
	$line = multi_explode($pattern,$ccline);
	
	$typemy = explode(" ",$type);
	$typem = strlen($typemy[0]);
	$typey = strlen($typemy[1]);
	
	$amex = "3";
	$VISA = "4";
	$MC = "5";
	$disc = "6";

	foreach($line as $col){
		if(is_numeric($col)){
			switch(strlen($col)){
				case 15:
					if(substr($col,0,1)==3){
						$ccnum['num'] = $col;
						$ccnum['type'] = $amex;
					}
					break;
				case 16:
					switch(substr($col,0,1)){
						case '4':
							$ccnum['num'] = $col;
							$ccnum['type'] = $VISA;
							break;
						case '5':
							$ccnum['num'] = $col;
							$ccnum['type'] = $MC;
							break;
						case '6':
							$ccnum['num'] = $col;
							$ccnum['type'] = $disc;
							break;
					}
					break;
				case 1:
					if (($col >= 1) and ($col <=12) and (!isset($ccnum['mon']))) $ccnum['mon'] = checkMon($col,$typem); break;
				case 2:
					if (($col >= 1) and ($col <=12) and (!isset($ccnum['mon']))){
						$ccnum['mon'] = checkMon($col,$typem);
					}
					elseif (($col >= 9) and ($col <= 19) and (isset($ccnum['mon'])) and (!isset($ccnum['year'])))    $ccnum['year'] = checkYear($col,$typey);
					break;
				case 4:
					if (($col >= 2009) and ($col <= 2019) and (isset($ccnum['mon'])))    $ccnum['year'] = checkYear($col,$typey);
					elseif ((substr($col,0,2) >= 1) and (substr($col,0,2) <=12) and (substr($col,2,2)>= 9) and (substr($col,2,2) <= 19) and (!isset($ccnum['mon'])) and (!isset($ccnum['year']))){
						$ccnum['mon'] = checkMon(substr($col,0,2),$typem);
						$ccnum['year'] = checkYear(substr($col,-2),$typey);
					}
					else $ccv['cv4'] = $col;
					break;
				case 6:
					if ((substr($col,0,2) >= 1) and (substr($col,0,2) <=12) and (substr($col,2,4)>= 2009) and (substr($col,2,4) <= 2019)){
                        $ccnum['mon'] = checkMon(substr($col,0,2),$typem);
						$ccnum['year'] = checkYear(substr($col,-2),$typey);
                    }
                    break;
				case 3:
					$ccv['cv3'] = $col;
                    break;
			}
		}
	}
	if($iscvv == 1){
		if ($ccnum['type'] == $amex) $ccnum['cvv'] = $ccv['cv4'];
		else $ccnum['cvv'] = $ccv['cv3'];
		return $ccnum;
	}
    else return $ccnum;
}
	
	
	
	
	
	
	
	
	function checkexp($m, $y, $t = 'cctype'){
    $cm = @date('m');
    $ty = strlen($y) == 2 ? 'y': 'Y';
    $cy = @date($ty);
    if($y < $cy || ($y == $cy && $m < $cm)){
        $return = array(
            'ccline' => '',
            'sock' => '',
            'stt' => 3,
        );
        echo json_encode($return);
        exit();
    }
    else if($t == '-1'){
        $return['stt'] = 0;
        $return['mess'] = 'CantCheck TYPE';
        echo json_encode($return);
        exit();
    }
}
	
	function ccType($type, $rs = array(3,4,5,6)){
    switch($type){
        case 3: $cctype = $rs[0]; break;
        case 4: $cctype = $rs[1]; break;
        case 5: $cctype = $rs[2]; break;
        case 6: $cctype = $rs[3]; break;
        default: $cctype = 0; break;
    }
    return $cctype;
}
	
	function info2($ccline, $type = 'mm yyyy'){
    $iscvv = 1;
    $pattern = '/[:\|\\\|\\/\s]/';
    $line = multi_explode($pattern,'|'.$ccline);

    $typemy = explode(" ",$type);
    $typem = strlen($typemy[0]);
    $typey = strlen($typemy[1]);

    $amex = "3";
    $visa = "4";
    $mast = "5";
    $disc = "6";
    $ccnum = array();
    $ccnum['zip'] = '10001';
    global $havezip;
    $havezip = 'fal';
    foreach($line as $col){
        if(is_numeric($col)){
            switch(strlen($col)){
                case 15:
                    if(substr($col,0,1)==3){
                        $ccnum['num'] = $col;
                        $ccnum['type'] = $amex;
                    }
                    break;
                case 16:
                case 17:
                    switch(substr($col,0,1)){
                        case '4':
                            $ccnum['num'] = $col;
                            $ccnum['type'] = $visa;
                            break;
                        case '5':
                            $ccnum['num'] = $col;
                            $ccnum['type'] = $mast;
                            break;
                        case '6':
                            $ccnum['num'] = $col;
                            $ccnum['type'] = $disc;
                            break;
                    }
                    break;
                case 1:
                    if (($col >= 1) and ($col <=12) and (!isset($ccnum['mon']))) $ccnum['mon'] = checkMon($col,$typem); break;
                case 2:
                    if (($col >= 1) and ($col <=12) and (!isset($ccnum['mon']))){
                        $ccnum['mon'] = checkMon($col,$typem);
                    }
                    elseif (($col >= 9) and ($col <= 19) and (isset($ccnum['mon'])) and (!isset($ccnum['year'])))    $ccnum['year'] = checkYear($col,$typey);
                    break;
                case 4:
                    if (($col >= 2009) and ($col <= 2019) and (isset($ccnum['mon'])))    $ccnum['year'] = checkYear($col,$typey);
                    elseif ((substr($col,0,2) >= 1) and (substr($col,0,2) <=12) and (substr($col,2,2)>= 9) and (substr($col,2,2) <= 19) and (!isset($ccnum['mon'])) and (!isset($ccnum['year']))){
                        $ccnum['mon'] = checkMon(substr($col,0,2),$typem);
                        $ccnum['year'] = checkYear(substr($col,-2),$typey);
                    }
                    else $ccv['cv4'] = $col;
                    break;
                case 6:
                    if ((substr($col,0,2) >= 1) and (substr($col,0,2) <=12) and (substr($col,2,4)>= 2009) and (substr($col,2,4) <= 2019)){
                        $ccnum['mon'] = checkMon(substr($col,0,2),$typem);
                        $ccnum['year'] = checkYear(substr($col,-2),$typey);
                    }
                    break;
                case 3:
                    $ccv['cv3'] = $col;
                    break;
                case 5:
                    if (($col >= 0) and ($col <=99999)){
                        $ccnum['zip'] = $col;
                        $havezip = 'tru';
                    }

            }
        }
    }
	
	
	
	
	
	
	
	
	
	if (is_numeric($line[3]))
	{
		if (is_numeric($line[4]))
		{
			
				if (is_numeric($line[5]))
				{
					
				$ccnum['hovaten'] = "KHONG+CO+TEN";
					
				}
				
				else {
					if(is_numeric($line[6])){ $ccnum['hovaten'] = $line[5];}
					else { $ccnum['hovaten'] = $line[5]." ".$line[6];	 }
				
				}
		
			
		}
		
		else {
			if(is_numeric($line[5])){ $ccnum['hovaten'] = $line[4];}
			else { $ccnum['hovaten'] = $line[4]." ".$line[5]; }
		
		}
		
	}
	
	
	
	else {
		
		if(is_numeric($line[4])){ $ccnum['hovaten'] = $line[3];}
		else { $ccnum['hovaten'] = $line[3]." ".$line[4]; }
				
		}
		
		
		
		
		
    @$ccnum['fname'] = trim($line[0]);
    @$ccnum['lname'] = trim($line[1]);
    if(trim($line[0] == '')){
        @$ccnum['fname'] = trim($line[1]);
        @$ccnum['lname'] = trim($line[2]);
    }
    if(isset($line[4])){
        @$ccnum['add'] = trim($line[2]);
        @$ccnum['city'] = trim($line[3]);
        @$ccnum['state'] = trim($line[4]);
    }

    if($iscvv == 1){
        if(isset($ccv['cv3']) || isset($ccv['cv4'])){
            if ($ccnum['type'] == $amex) $ccnum['cvv'] = isset($ccv['cv4']) ? $ccv['cv4'] : '0000';
            else $ccnum['cvv'] = isset($ccv['cv3']) ? $ccv['cv3'] : '000';
            return $ccnum;
        }
        else{

            if ($ccnum['type'] == $amex) $ccnum['cvv'] = '0000';
            else $ccnum['cvv'] = '000';
            return $ccnum;
        }
    }

}
	
	
	
	function getRand($en = true, $length = 7){
    $characters = 'abcdefghijklmnopqrstuvwxyz';
    $randomString = 'x';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }

    if($en)
        return rawurlencode($randomString);
    return $randomString;
}
function getEmail($en = true, $length = 10){
    $characters = 'abcdefghijklmnopqrstuvwxyz';
    $randomString = '';
    $dm = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    $dotmail = array(
        'gmail.com',
       'live.com',
        'hotmail.com',
       'yahoo.com',
      // 'thuantn14.us',
      'ymail.com',
       'yahoo.co.uk',
      'att.net',
    );
    $rand = rand(0,6);
   // $dm = $dotmail[$rand];
    $dm = $dotmail[$rand];
    for ($i = 0; $i < 8; $i++) {
        //$dm .= $characters[rand(0, strlen($characters) - 1)];
    }
   $uname = $randomString.'@'.$dm;
	//$uname = $randomString.'@'.'checkerpro.net';
    if($en)
       // return rawurlencode($uname);
	    return $uname;
   // return $uname;
}


if ($_POST['hamxuly'] == 'checkwebsite')
{
	
	if ($_POST['webcheck'] == 'CC01') { include ('./webcheck/CC01.php'); }

	
    
	
}

?>