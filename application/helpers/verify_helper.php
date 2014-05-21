<?php

/**
 * 验证手机号
 * @param $str_mobile
 * @return bool
 * @author yanrui@tizi.com
 */
function is_mobile($str_mobile)
{
    $pattern = '/^(13[0-9]{1}[0-9]{8}|15[0-9]{1}[0-9]{8}|18[0-9]{1}[0-9]{8}|14[0-9]{9}|17[078][0-9]{8})$/';
    return (bool)preg_match($pattern, $str_mobile);
}

/**
 * 验证email
 * @param string $str_email
 * @return bool
 * @author yanrui@tizi.com
 */
function is_email($str_email)
{
    return preg_match("/^[a-z0-9]{1}[-_\.|a-z0-9]{0,19}@([a-z0-9]*[-_]?[a-z0-9]+)+[\.][a-z]{0,3}([\.][a-z]{1,3})?$/i", $email) ? true : false;
}