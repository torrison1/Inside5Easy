<?php

namespace Tools\Website;

Class Website {

    var $config;

    //i--- Define and init global Website Variables ; inside_template ; torrison ; 01.05.2020 ; 1 ---/
    public function init(){

        $this->config['main']['sitename'] = $GLOBALS['config']['Website']['sitename'];
        $this->config['main']['base_url'] = $GLOBALS['config']['Website']['base_url'];

        $this->config['facebook']['app_id'] = $GLOBALS['config']['Website']['fb_app_id'];
        $this->config['facebook']['app_secret'] = $GLOBALS['config']['Website']['fb_app_secret'];
        $this->config['facebook']['app_login_redirect'] = $GLOBALS['config']['Website']['fb_app_login_redirect'];

        $this->config['facebook']['fb_img_url'] = $GLOBALS['config']['Website']['fb_img_url'];

        $this->config['google']['client_id'] = $GLOBALS['config']['Website']['g_client_id'];
        $this->config['google']['client_secret'] = $GLOBALS['config']['Website']['g_client_secret'];
        $this->config['google']['redirect_uri'] = $GLOBALS['config']['Website']['g_redirect_uri'];
        $this->config['google']['grant_type'] = 'authorization_code';

        $this->config['mailer']['footer'] = "<a href='".$this->config['main']['base_url']."/'>".$this->config['main']['sitename']."</a>";
        $this->config['mailer']['admin_email'] = $GLOBALS['config']['Website']['admin_email'];


    }

    //i--- Redirect Methods ; inside_template ; torrison ; 01.05.2020 ; 3 ---/
    public function redirect_301($redirect_301_url){

        header("HTTP/1.1 301 Moved Permanently");
        header("Location: ".$redirect_301_url);
        exit();
    }

    public function redirect_refresh($url){

        header("refresh: 1; url=$url");
        echo "Redirecting, please wait...";
        exit();
    }

    public function redirect_refresh_message($url, $message){

        echo $message."<br>";
        header("refresh: 1; url=$url");
        echo "Redirecting, please wait...";
        exit();
    }

}
