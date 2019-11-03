<?php
class card {

    public $card;

    public function __construct($cc) {
        $ccn = str_replace(' ', '', $cc);
        $ccn = str_replace(',', '|', $ccn);
        $ccn = str_replace('/', '|', $ccn);
        $ccn = str_replace("%92", '|', $ccn);
        $ccn = str_replace('||', '|', $ccn);
        $ccnum['num'] = $this->timnum($ccn);
        $date = $this->timexp($ccn);
        $time = explode('|', $date);
        $ccnum['mon'] = $time[0];
        $ccnum['year'] = $time[1];
        $ccnum['cvv'] = $this->timcvv($ccn, $date, $ccnum['num']);
        $ccnum['zip'] = $this->timzip($ccn);
        $ccnum['full'] = $ccnum['num'] . "|" . $ccnum['mon'] . "|" . $ccnum['year'] . "|" . $ccnum['cvv'];
        $this->card = $ccnum;
    }

    function type($chuoi) {
        $mang = explode(",", $chuoi);
        $n = left($this->card['num'], 1);
        if ($n == 3)
            return $mang[0];
        if ($n == 4)
            return $mang[1];
        if ($n == 5)
            return $mang[2];
        if ($n == 6)
            return $mang[3];
        return '';
    }

    function timzip($cc) {
        $cc = str_replace('-', '|', $cc);
        $mang = explode("|", $cc);
        for ($i = 0; $i < count($mang); $i++) {
            $item = trim($mang[$i]);
            if (strlen($item) == 5 && is_numeric($item))
                return $mang[$i];
        }
        return "";
    }

    function timcvv($dat, $vexp, $num) {
        $mang = explode("|", $dat);
        $exp1 = explode("|", $vexp);
        $tcvv = '';
        for ($j = 0; $j < count($mang); $j++) {
            $item = trim($mang[$j]);
            if (left($num, 1) == '3') {
                if (test($item) == 1 && strlen($item) == 4 && $item != $exp1[1]) {
                    $tcvv = $item;
                }
            } else {
                if (test($item) == 1 && strlen($item) == 3) {
                    $tcvv = $item;
                }
            }
        }
        if ($tcvv == '') {
            if (left($num, 1) != '3')
                $tcvv = '000';
            else
                $tcvv = '0000';
        }
        return $tcvv;
    }

    function timnum($str) {
        $ccNum = '';
        $str = str_replace(" ", "", $str);
        for ($i = 0; $i <= strlen($str); $i++) {
            if (is_numeric($str[$i])) {
                $ccNum.=$str[$i];
                if (strlen($ccNum) == 15 && substr($ccNum, 0, 1) == '3') {
                    return $ccNum;
                    break;
                } elseif (strlen($ccNum) == 16 && substr($ccNum, 0, 1) != '3') {
                    return $ccNum;
                    break;
                } else {
                    
                }
            }
            else
                $ccNum = '';
        }
    }

    function timexp($dat) {
        $dat = str_replace(' ', '', $dat);
        $mang2 = explode("|", $dat);
        for ($j = 0; $j < count($mang2); $j++) {

            if (test($mang2[$j]) == 1 && strlen($mang2[$j]) == 4) {
                $mon = left($mang2[$j], 2);
                $year = right($mang2[$j], 2);
                if (test($mon) == 1 && test($year) == 1 && $mon + 0 > 0 && $mon + 0 <= 12 && $year + 0 > 0 && $year + 0 < 30) {
                    $cexp = $mon . '|20' . $year;
                    break;
                }
            }
            if (test($mang2[$j]) == 1 && strlen($mang2[$j]) == 6) {
                $mon = left($mang2[$j], 2);
                $year = right($mang2[$j], 4);
                if (test($mon) == 1 && test($year) == 1 && $mon + 0 > 0 && $mon + 0 <= 12 && $year + 0 > 2000 && $year + 0 < 2030) {
                    $cexp = $mon . '|' . $year;
                    break;
                }
            }
            if (test($mang2[$j]) == 1 && strlen($mang2[$j]) == 2 && strlen($mang2[$j + 1]) == 4) {
                if ($mang2[$j] + 0 > 0 && $mang2[$j] + 0 < 13 && $mang2[$j + 1] > 1000 && $mang2[$j + 1] < 2030) {
                    $cexp = $mang2[$j] . "|" . $mang2[$j + 1];
                    break;
                }
            }
            if (test($mang2[$j]) == 1 && strlen($mang2[$j + 1]) == 2 && strlen($mang2[$j]) == 4) {
                if ($mang2[$j + 1] + 0 > 0 && $mang2[$j + 1] + 0 < 13 && $mang2[$j] > 2000 && $mang2[$j] < 2030) {
                    $cexp = $mang2[$j + 1] . "|" . $mang2[$j];
                    break;
                }
            }
            if (test($mang2[$j]) == 1 && strlen($mang2[$j]) == 2 && strlen($mang2[$j + 1]) == 2 && test($mang2[$j + 1]) == 1) {
                if ($mang2[$j] + 0 > 0 && $mang2[$j] + 0 < 13 && $mang2[$j + 1] > 10 && $mang2[$j + 1] < 30) {
                    $cexp = $mang2[$j] . "|20" . $mang2[$j + 1];
                    break;
                }
                if ($mang2[$j + 1] + 0 > 0 && $mang2[$j + 1] + 0 < 13 && $mang2[$j] > 10 && $mang2[$j] < 30) {
                    $cexp = "20" . $mang2[$j] . "|" . $mang2[$j + 1];
                    break;
                }
            }
            if (test($mang2[$j + 1]) == 1 && strlen($mang2[$j]) == 1 && strlen($mang2[$j + 1]) == 2) {
                if ($mang2[$j] + 0 > 0 && $mang2[$j] + 0 < 13 && $mang2[$j + 1] > 10 && $mang2[$j + 1] < 30) {
                    $cexp = "0" . $mang2[$j] . "|20" . $mang2[$j + 1];
                    break;
                }
                if ($mang2[$j + 1] + 0 > 0 && $mang2[$j + 1] + 0 < 13 && $mang2[$j] > 10 && $mang2[$j] < 30) {
                    $cexp = "0" . $mang2[$j + 1] . "|20" . $mang2[$j];
                    break;
                }
            }
            if (test($mang2[$j]) == 1 && strlen($mang2[$j + 1]) == 1 && strlen($mang2[$j]) == 2) {
                if ($mang2[$j + 1] + 0 > 0 && $mang2[$j + 1] + 0 < 13 && $mang2[$j] > 10 && $mang2[$j] < 30) {
                    $cexp = "0" . $mang2[$j + 1] . "|20" . $mang2[$j];
                    break;
                }
                if ($mang2[$j] + 0 > 0 && $mang2[$j] + 0 < 13 && $mang2[$j + 1] > 10 && $mang2[$j + 1] < 30) {
                    $cexp = "0" . $mang2[$j] . "|20" . $mang2[$j + 1];
                    break;
                }
            }
            if (strlen($mang2[$j]) == 1 && strlen($mang2[$j + 1]) == 4) {
                if ($mang2[$j] + 0 > 0 && $mang2[$j] + 0 < 10 && $mang2[$j + 1] > 1000 && $mang2[$j + 1] < 2030) {
                    $cexp = "0" . $mang2[$j] . "|" . $mang2[$j + 1];
                    break;
                }
            }
            if (strlen($mang2[$j + 1]) == 1 && strlen($mang2[$j]) == 4) {
                if ($mang2[$j + 1] + 0 > 0 && $mang2[$j + 1] + 0 < 10 && $mang2[$j] > 1000 && $mang2[$j] < 2030) {
                    $cexp = "0" . $mang2[$j + 1] . "|" . $mang2[$j];
                    break;
                }
            }
        }
        return $cexp;
    }

}

?>
