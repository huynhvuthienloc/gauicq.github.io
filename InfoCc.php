<?php
namespace App\Classes;
class infocc{
    // New info cc
    public function checkMon($date, $type)
    {
        $len = strlen($date);
        if ($type == 2) {
            if ($len == 2) {
                return $date;
            } elseif ($len == 1) {
                switch ($date) {
                    case '1':
                        $date = '01';
                        break;
                    case '2':
                        $date = '02';
                        break;
                    case '3':
                        $date = '03';
                        break;
                    case '4':
                        $date = '04';
                        break;
                    case '5':
                        $date = '05';
                        break;
                    case '6':
                        $date = '06';
                        break;
                    case '7':
                        $date = '07';
                        break;
                    case '8':
                        $date = '08';
                        break;
                    case '9':
                        $date = '09';
                        break;
                }
            }
            return $date;
        } elseif ($type == 1) {
            if ($len == 2) {
                switch ($date) {
                    case '01':
                        $date = '1';
                        break;
                    case '02':
                        $date = '2';
                        break;
                    case '03':
                        $date = '3';
                        break;
                    case '04':
                        $date = '4';
                        break;
                    case '05':
                        $date = '5';
                        break;
                    case '06':
                        $date = '6';
                        break;
                    case '07':
                        $date = '7';
                        break;
                    case '08':
                        $date = '8';
                        break;
                    case '09':
                        $date = '9';
                        break;
                    case '10':
                        $date = '10';
                        break;
                    case '11':
                        $date = '11';
                        break;
                    case '12':
                        $date = '12';
                        break;
                }
                return $date;
            } elseif ($len == 1)
                return $date;
        } else
            return false;
    }


public function checkYear($date, $type)
    {
        $len = strlen($date);
        if ($type == 4) {
            if ($len == 4)
                return $date;
            elseif ($len == 2)
                return "20" . $date;
        } elseif ($type == 2) {
            if ($len == 2)
                return $date;
            elseif ($len == 4)
                return substr($date, -2);
        } else
            return false;
    }

public function multi_explode($pattern, $string, $standardDelimiter = ':')
    {
        $string = preg_replace(array($pattern, "/{$standardDelimiter}+/s"), $standardDelimiter,
            $string);
        return explode($standardDelimiter, $string);
    }

public function info($ccline, $type)
    {
        $ccline = str_replace(" ", "", $ccline);
        $pattern = '/[:\|\\\\\/\s]/';
        $line = $this->multi_explode($pattern, $ccline);
        // print_r($line);
        $typemy = explode(" ", $type);
        $typem = strlen($typemy[0]);
        $typey = strlen($typemy[1]);

        $amex = "american_express";
        $visa = "visa";
        $mast = "mastercard";
        $disc = "discover";

        foreach ($line as $col) {
            if (is_numeric($col)) {
                switch (strlen($col)) {
                    case 15:
                        if (substr($col, 0, 1) == 3) {
                            $ccnum['num'] = $col;
                            $ccnum['type'] = $amex;
                        }
                        break;
                    case 16:
                        switch (substr($col, 0, 1)) {
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
                        if (($col >= 1) and ($col <= 12) and (!isset($ccnum['mon'])))
                            $ccnum['mon'] = $this->checkMon($col, $typem);
                        break;
                    case 2:
                        if (($col >= 1) and ($col <= 12) and (!isset($ccnum['mon']))) {
                            $ccnum['mon'] = $this->checkMon($col, $typem);
                        } elseif (($col >= 9) and ($col <= 25) and (!isset($ccnum['year'])))
                            $ccnum['year'] = $this->checkYear($col, $typey);
                        break;
                    case 4:
                        if (($col >= 2009) and ($col <= 2025) and (!isset($ccnum['year'])))
                            $ccnum['year'] = $this->checkYear($col, $typey);
                        elseif ((substr($col, 0, 2) >= 1) and (substr($col, 0, 2) <= 12) and (substr($col,
                                    2, 2) >= 9) and (substr($col, 2, 2) <= 19) and (!isset($ccnum['mon'])) and (!
                            isset($ccnum['year']))) {
                            $ccnum['mon'] = $this->checkMon(substr($col, 0, 2), $typem);
                            $ccnum['year'] = $this->checkYear(substr($col, -2), $typey);
                        } else
                            $ccv['cv4'] = $col;
                        break;
                    case 6:
                        if ((substr($col, 0, 2) >= 1) and (substr($col, 0, 2) <= 12) and (substr($col, 2,
                                    4) >= 2009) and (substr($col, 2, 4) <= 2019)) {
                            $ccnum['mon'] = $this->checkMon(substr($col, 0, 2), $typem);
                            $ccnum['year'] = $this->checkYear(substr($col, -2), $typey);
                        }
                        break;
                    case 3:
                        $ccv['cv3'] = $col;
                        break;
                    case 5:
                        if (intval($col) >= 1000 && strlen($col) == 5) {
                            $ccnum['zipcode'] = $col; //US only
                        }
                        break;
                }
            } else
                if (strlen($col) == 10 && strpos($col, '-') !== false && !isset($ccv['zipcode'])) {
                    list($z1, $z2) = explode('-', $col);
                    if (is_numeric($z1) && is_numeric($z2))
                        $ccnum['zipcode'] = $col;
                    break;
                } else
                    if (isset($_POST['ccinter'])) {
                        if (strlen($col) == 6) {
                            $ccnum['zipcode'] = $col;
                        }
                    }
        }
        if (isset($ccnum["num"])) {
            if ($ccnum['type'] == $amex)
                if(isset($ccv['cv4']))
                {
                    $ccnum['cvv'] = $ccv['cv4'];
                }else{$ccnum['cvv']="0000";}
            elseif(isset($ccv['cv3']))
                $ccnum['cvv'] = $ccv['cv3'];
            else{$ccnum['cvv']='000';}
            return $ccnum;
        } else {
            $ccnum["num"] = false;
            return $ccnum;
        }
    }
public function inStr($s,$as){
        $s=strtoupper($s);
        if(!is_array($as)) $as=array($as);
        for($i=0;$i<count($as);$i++) if(strpos(($s),strtoupper($as[$i]))!==false) return true;
        return false;
    }

}
?>