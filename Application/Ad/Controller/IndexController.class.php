<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-4-28
 * Time: 上午11:30
 * @author 郑钟良<zzl@ourstu.com>
 */

namespace Ad\Controller;


use Think\Controller;

class IndexController extends Controller{


    public function add()
    {
        $defaultCountry = 45;
        $defaultCurrency = 'CNY';
        if (isset($_GET['id'])){
            $isNew = 0;
            $id = I('get.id');
            $uid = get_uid();
            $ad = M('tradead')->where('id='.$id.' and uid='.$uid)->find();
            if ($ad){
                $this->assign('id',$id);
                $this->assign('ad',$ad);
            }else{
                $this->redirect('/ad/advertise');
            }
        }else{
            $isNew = 1;
        }
        $country = D('Country')->field('id,name,code')->select();
        $currency = D('Currency')->select();
        $payType = D('Pay')->select();
        $market = D('market')->field('market')->select();
        $time = array(
            array('id'=>0,'time' => '00:00'), array('id'=>1,'time' => '01:00'), array('id'=>2,'time' => '02:00'),
            array('id'=>3,'time' => '03:00'), array('id'=>4,'time' => '04:00'), array('id'=>4,'time' => '05:00'),
            array('id'=>6,'time' => '06:00'), array('id'=>7,'time' => '07:00'), array('id'=>8,'time' => '08:00'),
            array('id'=>9,'time' => '09:00'), array('id'=>10,'time' => '10:00'), array('id'=>11,'time' => '11:00'),
            array('id'=>12,'time' => '12:00'), array('id'=>13,'time' => '13:00'), array('id'=>14,'time' => '14:00'),
            array('id'=>15,'time' => '15:00'), array('id'=>16,'time' => '16:00'), array('id'=>17,'time' => '17:00'),
            array('id'=>18,'time' => '18:00'), array('id'=>19,'time' => '19:00'), array('id'=>20,'time' => '20:00'),
            array('id'=>21,'time' => '21:00'), array('id'=>22,'time' => '22:00'), array('id'=>23,'time' => '23:00'),
            array('id'=>24,'time' => '24:00'),
        );
        $ratePrice = 24000;
        $this->assign('defaultCurrency', $defaultCurrency);
        $this->assign('defaultCountry', $defaultCountry);
        $this->assign('market', $market);
        $this->assign('country', $country);
        $this->assign('currency', $currency);
        $this->assign('payType', $payType);
        $this->assign('time', $time);
        $this->assign('ratePrice', $ratePrice);
        $this->assign('isNew', $isNew);
        $this->display();
    }

    public function doPost($id=0,$coin_type = 0,$type = 0,$country=0,$currency='',$pre_price=0,$price=0,$pay_time=0,$pay_addr='',$low_price=0,$min_price=0,$max_price=0,$pay_type=0,$pay_text='',$pay_remark='',$auto_message='',$is_safe=0,$is_trust=0,$start0=0,$end0=0,$start1=0,$end1=0,$start2=0,$end2=0,$start3=0,$end3=0,$start4=0,$end4=0,$start5=0,$end5=0,$start6=0,$end6=0)
    {
        if (!is_login()) {
            $this->error(L('_ERROR_LOGIN_'),U('/ucenter/member/login'));
        }
        if ($coin_type == 0){
            $this->error(L('_ERROR_COIN_TYPE_'));
        }
        if ($type == 0){
            $this->error(L('_ERROR_TYPE_'));
        }
        if ($country == 0){
            $this->error(L('_ERROR_COUNTRY_'));
        }
        if ($currency == ''){
            $this->error(L('_ERROR_CURRENCY_'));
        }
        if ($pre_price == 0){
            $this->error(L('_ERROR_PRE_'));
        }
        if ($min_price == 0){
            $this->error(L('_ERROR_MIN_'));
        }
        if ($max_price == 0){
            $this->error(L('_ERROR_MAX_'));
        }
        if ($pay_type == 0){
            $this->error(L('_ERROR_PAY_'));
        }
        if (($type == 3 || $type == 4) && $pay_addr == ''){
            $this->error(L('_ERROR_ADDR_'));
        }
        $payType = '';
        foreach ($pay_type as $item){
            $payType .= $item.',';
        }
        $payType = substr($payType,0,strlen($payType)-1);
        $content = D('Tradead')->create();
        $content['type'] = $type;
        $content['coin_type'] = $coin_type;
        $content['country'] = $country;
        $content['currency'] = $currency;
        $content['price'] = $price;
        $content['pre_price'] = $pre_price;
        $content['low_price'] = $low_price;
        $content['max_price'] = $max_price;
        $content['min_price'] = $min_price;
        $content['pay_type'] = $payType;
        $content['pay_time'] = $pay_time;
        $content['pay_addr'] = $pay_addr;
        $content['pay_remark'] = str_replace("\n","<br>",$pay_remark);
        $content['pay_text'] = str_replace("\n","<br>",$pay_text);
        $content['auto_message'] = $auto_message;
        $content['is_safe'] = $is_safe;
        $content['is_trust'] = $is_trust;
        $content['start0'] = $start0;
        $content['end0'] = $end0;
        $content['start1'] = $start1;
        $content['end1'] = $end1;
        $content['start2'] = $start2;
        $content['end2'] = $end2;
        $content['start3'] = $start3;
        $content['end3'] = $end3;
        $content['start4'] = $start4;
        $content['end4'] = $end4;
        $content['start5'] = $start5;
        $content['end5'] = $end5;
        $content['start6'] = $start6;
        $content['end6'] = $end6;
        $content['status'] = 2;     //发布广告默认为下架状态
        if ($id > 0){
            D('Tradead')->where('id='.$id)->save($content);
        }else{
            $content['uid'] = is_login();
            $content['create_time'] = time();
            D('Tradead')->add($content);
        }
        $this->success(L('_SUCCESS_POST_'), U('ucenter/index/myad'));
    }


    /**
     * 根据CURRENCY MARKET 得出公式
     */
    public function getFormula()
    {
        if (IS_POST) {
            $currency = I('post.currency','','op_t');
            $market = I('post.market','','op_t');
            if ($currency == '' || $market == ''){
                return;
            }
            $len = strlen($market);
            $mktCurcy = substr($market,$len-3,$len);
            $Formula = '';
            if ($currency == $mktCurcy){
                $Formula = $market;
            }
            else if($currency == 'USD' && $mktCurcy != 'USD'){
                $Formula = $market . "/USD_in_".$mktCurcy;
            }
            else if ($mktCurcy == 'USD'){
                $Formula = $market . "*" ."USD_in_".$currency;
            }
            else{
                $Formula = $market . "/USD_in_".$mktCurcy."*USD_in_".$currency;
            }
            echo $Formula;
        }
    }

    /**
     * 根据公式获取市场价格
     */
    public function getMarketPrice()
    {
        if (IS_POST){
            $formula = I('post.formula','','op_t');
            if ($formula == ''){
                return;
            }
            $formulaArray = explode('*',$formula);
            if (count($formulaArray) == 2){
                if (strpos($formulaArray[0],'/')){  //OkCoinCNY/USD_in_CNY*1.02
                    $market = explode('/',$formulaArray[0]);
                    $prePrice = $formulaArray[1];
                    $avg = M('market')->field('avg')->where("market='".$market[0]."'")->find();
                    $currency = explode('_',$market[1]);
                    $rate = $this->queryRate($currency[2]);
                    if ($rate == 0){
                        echo $rate;
                        return;
                    }
                    echo round($avg['avg'] / $rate * $prePrice,2);
                }else{  //BitFinexUSD*1.02
                    $market = $formulaArray[0];
                    $prePrice = $formulaArray[1];
                    $avg = M('market')->field('avg')->where("market='".$market."'")->find();
                    echo round($avg['avg'] * $prePrice,2);
                }
            }else{
                if (strpos($formulaArray[0],'/')){//KraKenEUR/USD_in_EUR*USD_in_CNY*1.02
                    $market = explode('/',$formulaArray[0]);
                    $currency = $formulaArray[1];
                    $prePrice = $formulaArray[2];
                    $avg = M('market')->field('avg')->where("market='".$market[0]."'")->find();
                    $currency2 = explode('_',$market[1]);
                    $rate1 = $this->queryRate($currency2[2]);
                    if ($rate1 == 0){
                        echo $rate1;
                        return;
                    }
                    $currency = explode('_',$currency);
                    $rate2 = $this->queryRate($currency[2]);
                    if ($rate2 == 0){
                        echo $rate2;
                        return;
                    }
                    echo round($avg['avg'] / $rate1 * $rate2 * $prePrice,2);
                }else{  //BitFinexUSD*USD_in_CNY*1.02
                    $market = $formulaArray[0];
                    $currency = $formulaArray[1];
                    $prePrice = $formulaArray[2];
                    $avg = M('market')->field('avg')->where("market='".$market."'")->find();
                    $currency = explode('_',$currency);
                    $rate = $this->queryRate($currency[2]);
                    if ($rate == 0){
                        echo $rate;
                        return;
                    }
                    echo round($avg['avg'] * $rate * $prePrice,2);
                }
            }
        }
    }

    public function queryRate($to='')
    {
        $currency = M('currency')->field('rate')->where("code='".$to."'")->find();
        if ($currency){
            return $currency['rate'];
        }else{
            return 0;
        }
    }

    public function updateRate()
    {
        $currency = M('currency')->field('id,code')->select();
        foreach ($currency as $item){
            $url = "http://download.finance.yahoo.com/d/quotes.csv?e=.csv&f=sl1d1t1&s=USD".$item['code']."=x";
            $result = $this->request($url);
            $result = explode(',',$result);
            M('currency')->where('id='.$item['id'])->save(array('rate'=>$result[1]));
        }
    }

    public function requestMarket()
    {
        $bitfinexUrl = 'https://api.bitfinex.com/v2/ticker/tBTCUSD';
        $bitstampUrl = 'https://www.bitstamp.net/api/ticker/';
        $coincheckUrl = 'https://coincheck.com/api/ticker';
        $btcboxUrl = 'https://www.btcbox.co.jp/api/v1/ticker/';
        $korbitUrl = 'https://api.korbit.co.kr/v1/ticker/detailed';
        $hitbtcUrl = 'https://api.hitbtc.com/api/1/public/BTCUSD/ticker';
        $krakenUrl = 'https://api.kraken.com/0/public/Ticker?pair=XBTEUR';
        $okcoinUrl = 'https://www.okex.com/api/v1/ticker.do?symbol=btc_usdt';
        //OkcoinUSD
        $output = $this->request($okcoinUrl);
        $json = json_decode($output);
        $data = array();
        $data['market'] = 'OkcoinUSD';
        $data['high'] = $json->ticker->high;
        $data['low'] = $json->ticker->low;
        $data['bid'] = $json->ticker->buy;
        $data['ask'] = $json->ticker->sell;
        $data['close'] = $json->ticker->last;
        $data['avg'] = ($json->ticker->high+$json->ticker->low)/2;
        $data['create_time'] = time();
        M('market')->where("market='OkcoinUSD'")->save($data);
        //KrakenEUR
        $output = $this->request($krakenUrl);
        $json = json_decode($output);
        unset($data);
        $data['high'] = $json->result->XXBTZEUR->h[1];
        $data['low'] = $json->result->XXBTZEUR->l[1];
        $data['bid'] = $json->result->XXBTZEUR->b[0];
        $data['ask'] = $json->result->XXBTZEUR->a[0];
        $data['close'] = $json->result->XXBTZEUR->c[0];
        $data['avg'] = $json->result->XXBTZEUR->p[1];
        $data['create_time'] = time();
        M('market')->where("market='KrakenEUR'")->save($data);
        //HitbtcUSD
        $output = $this->request($hitbtcUrl);
        $json = json_decode($output);
        unset($data);
        $data['high'] = $json->high;
        $data['low'] = $json->low;
        $data['bid'] = $json->bid;
        $data['ask'] = $json->ask;
        $data['close'] = $json->last;
        $data['avg'] = ($json->high+$json->low)/2;
        $data['create_time'] = time();
        M('market')->where("market='HitbtcUSD'")->save($data);
        //KorbitKRW
        $output = $this->request($korbitUrl);
        $json = json_decode($output);
        unset($data);
        $data['high'] = $json->high;
        $data['low'] = $json->low;
        $data['bid'] = $json->bid;
        $data['ask'] = $json->ask;
        $data['close'] = $json->last;
        $data['avg'] = ($json->high+$json->low)/2;
        $data['create_time'] = time();
        M('market')->where("market='KorbitKRW'")->save($data);
        //BtcboxJPY
        $output = $this->request($btcboxUrl);
        $json = json_decode($output);
        unset($data);
        $data['high'] = $json->high;
        $data['low'] = $json->low;
        $data['bid'] = $json->buy;
        $data['ask'] = $json->sell;
        $data['close'] = $json->last;
        $data['avg'] = ($json->high+$json->low)/2;
        $data['create_time'] = time();
        M('market')->where("market='BtcboxJPY'")->save($data);
        //CoincheckJPY
        $output = $this->request($coincheckUrl);
        $json = json_decode($output);
        unset($data);
        $data['high'] = $json->high;
        $data['low'] = $json->low;
        $data['bid'] = $json->bid;
        $data['ask'] = $json->ask;
        $data['close'] = $json->last;
        $data['avg'] = ($json->high+$json->low)/2;
        $data['create_time'] = time();
        M('market')->where("market='CoincheckJPY'")->save($data);
        //BitstampUSD
        $output = $this->request($bitstampUrl);
        $json = json_decode($output);
        unset($data);
        $data['high'] = $json->high;
        $data['low'] = $json->low;
        $data['bid'] = $json->bid;
        $data['ask'] = $json->ask;
        $data['close'] = $json->last;
        $data['avg'] = $json->vwap;
        $data['create_time'] = time();
        M('market')->where("market='BitstampUSD'")->save($data);
        //BitfinexUSD
        $output = $this->request($bitfinexUrl);
        $json = json_decode($output);
        unset($data);
        $data['high'] = $json[8];
        $data['low'] = $json[9];
        $data['bid'] = $json[0];
        $data['ask'] = $json[2];
        $data['close'] = $json[6];
        $data['avg'] = ($json[8]+$json[9])/2;
        $data['create_time'] = time();
        M('market')->where("market='BitfinexUSD'")->save($data);
    }

    private function request($url)
    {
        $ch = curl_init();
        $method = "GET";
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

} 