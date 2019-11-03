<?php
$thongtinnguoiviet = "Gate 1";
$mail = new Mail();
$curl = new xuly_curl();


//BỘ LỌC CC
$card = new card($_POST['listcc']);
$cc = $card->card;
$type = $card->type("AM,VC,MC,DC,");
// $type = $card->type("1004,1000,1002,Discover,");
$zipcc = $cc['zip'];
$ccn = $cc['num'];
$ccmon = $cc['mon'];
//$ccyear = $cc['year'];
$cvv = $cc['cvv'];

$ccyear1 = trim($cc['year']);
$ccyear = substr($ccyear1, -2);

if (!$cvv)
{
    if ($type == 'American Express')
    {
        $cvv = "0000";
    }
    else
    {
        $cvv = "000";
    }
}

$randomusername = 'dm' . rand(1, 999999);
$emailok = 'johanna' . time() . '@gmail.com';
$pwdok = '036859558' . rand(1, 1000);
$pin = rand(1000, 1000000);

function selectccv($Cct)
{
    if ($Cct == 3) return '0000';
    else return '000';
}
function Balance($Balance)
{
    switch ($Balance)
    {
        case 5:
            $Value = "5";
        break;
        case 10:
            $Value = "10";
        break;
        case 15:
            $Value = "15";
        break;
        case 20:
            $Value = "20";
        break;
        case 25:
            $Value = "25";
        break;
    }
    return $Value;
}

if (!$type)
{
    $result['thongbaoloi'] = - 1;
    $result['msg'] = urldecode($_POST['listcc']);
    echo json_encode($result);
    exit;
}
//END: BỘ LOC CC

//THONG TIN RAMDOM NEU CAN
$infoacc = new Info;
$first = getRand(true, 4);
$last = getRand(true, 5);
$domain = getRand(true, 12);
$mail = getEmail();
$name1 = $infoacc->getFirstName();
$name2 = $infoacc->getLastName();
$name3 = $infoacc->getFirstName2();
$name4 = $infoacc->getLastName2();
$fullname = $infoacc->getFullName();
$fullnamewithoutspace = str_replace(' ', '', $fullname);
$emailgenerator = $fullnamewithoutspace . time() . '@gmail.com';
$diachine = $infoacc->getAddress();
$cityne = $infoacc->getCity();
$statene = $infoacc->getSt();
if ($zipcc == "") $zipcc = $infoacc->getZipcode();
$phonene = $infoacc->getPhone1();
$phonene2 = $infoacc->getPhone2();
$statene1 = $infoacc->getState();
//THONG TIN RAMDOM NEU CAN



        $result['thongbaoloi'] = 2;
        $result['ccnum'] = $ccn;
        $result['ccyear'] = $ccyear;
        $result['ccmonth'] = $ccmon;
        $result['cvv'] = $cvv;
        $result['name'] = $fullname;
        $result['email'] = $emailgenerator;

echo json_encode($result);
exit;

?>
