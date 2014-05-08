<?php

/**
 * @param $mobilephone
 * @return bool
 */
function is_mobilephone($mobilephone)
{
    //add by zhangsj add 170|177|178号码段
    $pattern = '/^(13[0-9]{1}[0-9]{8}|15[0-9]{1}[0-9]{8}|18[0-9]{1}[0-9]{8}|14[0-9]{9}|17[078][0-9]{8})$/';
    if(preg_match($pattern, $mobilephone))
        return true;
    return false;
}

/**
 * 验证email
 * @param string $email
 * @return bool
 * @author yanrui@91waijiao.com
 */
function is_email($email){
    return preg_match("/^[a-z0-9]{1}[-_\.|a-z0-9]{0,19}@([a-z0-9]*[-_]?[a-z0-9]+)+[\.][a-z]{0,3}([\.][a-z]{1,3})?$/i",$email) ? true : false;
}