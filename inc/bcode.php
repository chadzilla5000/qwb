<?php

$len = strlen($_GET['c']);
if(trim($_GET['c'], '0123456789')!='' OR ($len!=12 AND $len!=13)) {
    echo 'Incorrect length ('.$len.')';
    die();
}

$c = str_split(substr($_GET['c'], 0, 12));
$now = 1;
foreach($c as $val) {
    if($now==1) {
        $sum += $val;
        $now = 3;
    }
    else
    {
        $sum += $val*3;
        $now = 1;
    }
}
$sum = 10-($sum%10);
if($sum==10) $sum = 0;

if($len==12) {
    $_GET['c'] .= $sum;
}
elseif(substr($_GET['c'], -1)!=$sum) {
    echo 'Incorrect controll sum '.$sum;
    die();
}

unset($len, $c, $now, $sum);

$kol = array(
    '0' => array('A', 'A', 'A', 'A', 'A', 'A'),
    '1' => array('A', 'A', 'B', 'A', 'B', 'B'),
    '2' => array('A', 'A', 'B', 'B', 'A', 'B'),
    '3' => array('A', 'A', 'B', 'B', 'B', 'A'),
    '4' => array('A', 'B', 'A', 'A', 'B', 'B'),
    '5' => array('A', 'B', 'B', 'A', 'A', 'B'),
    '6' => array('A', 'B', 'B', 'B', 'A', 'A'),
    '7' => array('A', 'B', 'A', 'B', 'A', 'B'),
    '8' => array('A', 'B', 'A', 'B', 'B', 'A'),
    '9' => array('A', 'B', 'B', 'A', 'B', 'A')
);

$code = array(
    'start' => '101',
    'lewa' => array(
        'A' => array(
            '0' => '0001101',
            '1' => '0011001',
            '2' => '0010011',
            '3' => '0111101',
            '4' => '0100011',
            '5' => '0110001',
            '6' => '0101111',
            '7' => '0111011',
            '8' => '0110111',
            '9' => '0001011'
        ),
        'B' => array(
            '0' => '0100111',
            '1' => '0110011',
            '2' => '0011011',
            '3' => '0100001',
            '4' => '0011101',
            '5' => '0111001',
            '6' => '0000101',
            '7' => '0010001',
            '8' => '0001001',
            '9' => '0010111'
        )
    ),
    'srodek' => '01010',
    'prawa' => array(
        '0' => '1110010',
        '1' => '1100110',
        '2' => '1101100',
        '3' => '1000010',
        '4' => '1011100',
        '5' => '1001110',
        '6' => '1010000',
        '7' => '1000100',
        '8' => '1001000',
        '9' => '1110100'
    ),
    'stop' => '101'
);


function gen_binary($c, $strona, $sys) {
    global $code, $kol;
    
    $c = str_split($c);
    $ret = '';
    if($strona==0) {
        foreach($c as $key => $val) {
            $ret .= $code['lewa'][$kol[$sys][$key]][$val];
        }
    }
    else
    {
        foreach($c as $val) {
            $ret .= $code['prawa'][$val];
        }
    }
    return $ret;
}
function print_code($c, $img) {
    global $b;
    
    $now = 0;
    $c = str_split($c);
    foreach($c as $val) {
        if($val==1) {
            imageline($img, $now, 0, $now, 40, $b);
            $now++;
        }
        elseif($val==0) {
            $now++;
        }
    }
}

$sys = substr($_GET['c'], 0, 1);
$lewa = substr($_GET['c'], 1, 6);
$prawa = substr($_GET['c'], 7);

$i = imagecreate(95, 40);
$w = imagecolorallocate($i, 255, 255, 255);
$b = imagecolorallocate($i, 0, 0, 0);

print_code($code['start'].gen_binary($lewa, 0, $sys).$code['srodek'].gen_binary($prawa, 1, $sys).$code['stop'], $i);

header('Content-type: image/bmp');
imagepng($i);
?>