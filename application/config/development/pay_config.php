<?php

//充值方式
$config['pay_channel'] = array
(
    'alipay'=>array(
            'account' => 'nahao@tizi.com',
            'merchant_id' => '2088411963723035',
            'merchant_key' => '5swgtp6kt5kmbze4mw35rqqq3hv1kfdd',
            'request_url' => 'https://mapi.alipay.com/gateway.do?',
    ),
    'netpay'=>array(
        'account' => '91waijiao',
        'merchant_id' => '808080201304148',
        'MerPrk_key' => APPPATH.'third_party/netpay/MerPrK_808080201304148_20140402230308.key', //私钥
        'PgPubk_key' => APPPATH.'third_party/netpay/PgPubk.key', //公钥
        'request_url' => 'https://payment.chinapay.com/pay/TransGet',
        'version' => '20070129',
        'pay_back_url' => 'http://www.91waijiao.com/pay/payback'
    ),
    'netpay-upop'=>array(
        'account' => '91waijiao',
        'merchant_id' => '808080201304149',
        'MerPrk_key' => APPPATH.'third_party/netpay/MerPrK_808080201304149_20140409195637.key', //私钥
        'PgPubk_key' => APPPATH.'third_party/netpay/PgPubk.key', //公钥
        'request_url' => 'https://payment.ChinaPay.com/pay/TransGet',
        'version' => '20070129',
        'pay_back_url' => 'http://www.91waijiao.com/pay/payback',
        'refund_url' => 'https://bak.chinapay.com/refund/SingleRefund.jsp',
        'refund_back_url' => 'http://www.91waijiao.com/pay/refundback'
    ),
    'alipay_wap'=>array(
        'account' => 'xiaoxiangyanzi@gmail.com',
        'merchant_id' => '2088901014223251',
        'merchant_key' => 'o8cm5994xdpcvck4t76e08saz55046nl',
        'sign_type' => 'MD5',
        'transport' => 'http',
        'input_charset' => 'utf-8',
        'request_url' => 'http://wappaygw.alipay.com/service/rest.htm?',
        'pay_back_url'=>'http://wap.91waijiao.com/pay/payback/call_back',
        'notify_url' => 'http://wap.91waijiao.com/pay/payback/notify'
    )
);

$config['bank_code'] = array(
    'banks' => array(
        'guangda' => array('name' => '光大银行', 'type' => 'bankPay', 'code' => 'CEBBANK'),
        'zhaoshang' => array('name' => '招商银行', 'type' => 'bankPay', 'code' => 'CMB'),
        'gongshang' => array('name' => '工商银行', 'type' => 'bankPay', 'code' => 'ICBCB2C'),
        'zhongguo' => array('name' => '中国银行', 'type' => 'bankPay', 'code' => 'BOCB2C'),
        'jiaotong' => array('name' => '交通银行', 'type' => 'bankPay', 'code' => 'COMM'),
        'jianshe' => array('name' => '建设银行', 'type' => 'bankPay', 'code' => 'CCB'),
        'nongye' => array('name' => '农业银行', 'type' => 'bankPay', 'code' => 'ABC'),
        'zhongxin' => array('name' => '中信银行', 'type' => 'bankPay', 'code' => 'CITIC'),
        'pufa' => array('name' => '浦发银行', 'type' => 'bankPay', 'code' => 'SPDB'),
        'xingye' => array('name' => '兴业银行', 'type' => 'bankPay', 'code' => 'CIB'),
        'shenfazhan' => array('name' => '深圳发展银行', 'type' => 'bankPay', 'code' => 'SDB'),
        'minsheng' => array('name' => '民生银行', 'type' => 'bankPay', 'code' => 'CMBC'),
    ),
    'credit' => array(
        'guangda' => array('name' => '光大银行', 'type' => 'CCIP', 'code' => 'CEB-CCIP'),
        'zhaoshang' => array('name' => '招商银行', 'type' => 'motoPay', 'code' => 'CMB-EXPRESS-CREDIT'),
        'gongshang' => array('name' => '工商银行', 'type' => 'motoPay', 'code' => 'ICBC-MOTO-CREDIT'),
        'zhongguo' => array('name' => '中国银行', 'type' => 'CCIP', 'code' => 'BOC-CCIP'),
        'guangfa' => array('name' => '广发银行', 'type' => 'motoPay', 'code' => 'GDB-EXPRESS-CREDIT'),
        'jianshe' => array('name' => '建设银行', 'type' => 'motoPay', 'code' => 'CCB-MOTO-CREDIT'),
        'nongye' => array('name' => '农业银行', 'type' => 'CCIP', 'code' => 'ABC-CCIP'),
        'zhongxin' => array('name' => '中信银行', 'type' => 'motoPay', 'code' => 'CITIC-EXPRESS-CREDIT'),
        'pingan' => array('name' => '平安银行', 'type' => 'CCIP', 'code' => 'SPABANK-CCIP'),
        'xingye' => array('name' => '兴业银行', 'type' => 'motoPay', 'code' => 'CIB-EXPRESS-CREDIT'),
        'huaxia' => array('name' => '华夏银行', 'type' => 'motoPay', 'code' => 'HXBANK-EXPRESS-CREDIT'),
        'minsheng' => array('name' => '民生银行', 'type' => 'motoPay', 'code' => 'CMBC-EXPRESS-CREDIT'),
    ),
);
$config['pay_back_url'] = 'http://www.nahaodev.com/pay/payback';