<?php
/**
 * Created by PhpStorm.
 * User: 王杰
 * Date: 2017/3/1
 * Time: 9:48
 */
function FromXml($xml)
{
    libxml_disable_entity_loader(true);
    return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
}

function ToXml($data)
{
    $xml = "<xml>";
    foreach ($data as $key=>$val)
    {
        if (is_numeric($val)){
            $xml.="<".$key.">".$val."</".$key.">";
        }else{
            $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
        }
    }
    $xml.="</xml>";
    return $xml;
}

header('Content-type: text/xml');

$returnResult = $GLOBALS['HTTP_RAW_POST_DATA'];

$res = FromXml($returnResult);

file_put_contents('callback.php',json_encode($res));

//支付成功
if ($res['result_code'] == 'SUCCESS') {
    define('ONETHINK_ADDON_PATH',1);
    define('__ROOT__',1);
    $conf = array();
    $conf = array_merge($conf,require './Conf/common.php');
    $mysql = new PDO('mysql:dbname='.$conf['DB_NAME'].';host='.$conf['DB_HOST'].';port=3306',$conf['DB_USER'],$conf['DB_PWD']);
    $sql = <<<EOF
UPDATE `ocenter_wx_pay_log` SET `status` =
'1' WHERE `trade_no` = '{$res['out_trade_no']}';
EOF;
    $result = $mysql->exec($sql);
    if ($result) {
        //todo 充值到用户账户
        $sql = <<<EOF
SELECT `uid` FROM `ocenter_wx_pay_log` WHERE `trade_no` = '{$res['out_trade_no']}';
EOF;
        $rs = $mysql->query($sql)->fetch();

        $sql1 = <<<AAA
SELECT *
FROM  `ocenter_member`
WHERE uid ='{$rs['uid']}';
AAA;
        $user = $mysql->query($sql1)->fetch();

        $money = $user['score4'] + $res['total_fee'] / 100;

        $sql2 = <<<BBB
UPDATE `ocenter_member` SET `score4`= '{$money}' WHERE uid = '{$rs['uid']}';
BBB;
        $aaa = $mysql->exec($sql2);

        $sucess = array('return_code' => 'SUCCESS', 'return_msg' => 'OK');
        exit(ToXml($sucess));
    }
}

//out_trade_no