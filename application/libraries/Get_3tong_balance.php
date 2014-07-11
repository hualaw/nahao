<?php
/**
 * Created by PhpStorm.
 * User: hua
 * Date: 14-7-11
 * Time: 上午10:07
 */
Class Get_3tong_balance
{
    private $account = 'dh20994';
    private $pwd = 'tizi2014';
    private $api_uri = 'http://3tong.net/http/sms/Balance';

    public function send()
    {
        $send_message = '<?xml version="1.0" encoding="UTF-8"?><message>';
        $send_message .= '<account>' . $this->account . '</account>'; //账号
        $send_message .= '<password>' . md5($this->pwd) . '</password>'; //密码
        $send_message .= '</message>';
        //echo $send_message;
        $ret = $this->curl_link($send_message);
        return $ret;
        
    }

    //send_3 调用
    private function curl_link($send_message){

        //echo "enter into ".__FUNCTION__."\n";
        $send_message = urlencode($send_message);
        $url = $this->api_uri . '?message=' . $send_message;
        //echo $url;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
       // echo $output;
        $output = $this->filter_output($output);
        curl_close($ch);
        return $output;
    }

    //大汉三通的结果处理
    private function filter_output($output){
        $output = simplexml_load_string($output);
        //var_dump($output);
        $result = (int)($output->result);
        $res['xml'] = $output;
        if($result === 0)
        {
        	$res = json_decode(json_encode($output),TRUE);
        	return $res;
//         	$result = simplexml_load_string($output->sms->amount);
//             return (int)($output->sms->amount);
        }
        else{
        	$res['result'] = $result;
        	$res['desc'] = $this->get_send3_error_code($result);
            return $res;
        }
    }


    private function get_send3_error_code($code){
        switch($code){
            case 0: $desc = "succ";break;
            case 1: $desc = "invalid account";break;
            case 2: $desc = "wrong pwd";break;
            case 3: $desc = "msgid is duplicated";break;
            case 4: $desc = "with invalid phone number in it";break;
            case 5: $desc = "over maximun count(phone)";break;
            case 6: $desc = "over maximun content";break;
            case 7: $desc = "invalid extended sub number";break;
            case 8: $desc = "time format is invalid";break;
            case 9: $desc = "api address error";break;
            case 10:$desc = "with illegal content against local law";break;
            case 11:$desc = "not enough money in your account";break;
            case 12:$desc = "订购关系无效";break;
            case 13:$desc = "invalid signature";break;
            case 14:$desc = "无效的手机子码";break;
            case 15:$desc = "product not exist";break;
            case 16:$desc = "please input at lease one phone num";break;
            case 97:$desc = "接入方式错误";break;
            case 98:$desc = "sys busy";break;
            case 99:$desc = "message content format error";break;
            default: $desc = 'unkown error';break;
        }
        return $desc;
    }
}