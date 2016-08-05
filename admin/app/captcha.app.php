<?php

/**
 *    验证码
 *
 *    @author    Garbin
 *    @usage    none
 */
class CaptchaApp extends ECBaseApp
{
    function index()
    {
        $this->_captcha(70, 20);
    }
}

?>