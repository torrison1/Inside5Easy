<?php

namespace Tools\CommonCore;

Class Routing {

    static function route(){

        $res = Array();

        //i--- Set Base URL ; inside_4_core_structure ; torrison ; 01.05.2020 ; 3 ---/
        $GLOBALS['app']['base_url'] = sprintf(
            "%s://%s",
            isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
            $_SERVER['SERVER_NAME']
        );

        //i--- Get Request URI ; inside_4_core_structure ; torrison ; 01.05.2020 ; 1 ---/
        $res['server_URI'] = $_SERVER['REQUEST_URI'];
        // if (!isset($_GET['url_line'])) $_GET['url_line'] = '';
        // $res['server_URI'] = $_GET['url_line'];

        //i--- Check /xx/ Localization Prefix ; inside_4_core_structure ; torrison ; 01.05.2020 ; 2 ---/
        $GLOBALS['app']['translate']['uri_prefix_value'] = '';
        $GLOBALS['app']['translate']['uri_prefix'] = '';

        if (substr($res['server_URI'], 3, 1) == '/') {

            $GLOBALS['app']['translate']['uri_prefix_value'] = substr($res['server_URI'], 1, 2);
            $GLOBALS['app']['translate']['uri_prefix'] = '/'.$GLOBALS['app']['translate']['uri_prefix_value'];
            $res['server_URI'] = '/'.substr($res['server_URI'], 4);

            if ($GLOBALS['config']['Translate']['default_lang'] == $GLOBALS['app']['translate']['uri_prefix_value'])
            {
                $redirect_301_url = $GLOBALS['config']['Website']['base_url'].$res['server_URI'];
                header("HTTP/1.1 301 Moved Permanently");
                header("Location: ".$redirect_301_url);
                exit();
            }
        }

        //i--- Cut GET string ; inside_4_core_structure ; torrison ; 01.05.2020 ; 3 ---/
        $result = explode('?',  $res['server_URI']);
        if (isset($result[1])) {
            $res['get_string'] = $result[1];
            $res['clear_uri'] = $result[0];
            $res['uri_parts'] = explode('/',  $res['clear_uri']);
        } else {
            $res['get_string'] = '';
            $res['clear_uri'] = $res['server_URI'];
            $res['uri_parts'] = explode('/',  $res['clear_uri']);
        }

        //i--- Default Service And Method ; torrison ; 01.05.2020 ; 4 ---/

        if (!isset($res['uri_parts'][1]) OR $res['uri_parts'][1] == '') $res['uri_parts'][1] = 'website';
        if (!isset($res['uri_parts'][2]) OR $res['uri_parts'][2] == '') $res['uri_parts'][2] = 'main';
        if (!isset($res['uri_parts'][3]) OR $res['uri_parts'][3] == '') $res['uri_parts'][3] = 'index';

        $res['service'] = $res['uri_parts'][1];
        $res['service'] = preg_replace('/[^a-zA-Z0-9]_/', '', ucfirst($res['service']));
        $res['class'] = $res['uri_parts'][2];
        $res['class'] = preg_replace('/[^a-zA-Z0-9]_/', '', ucfirst($res['class']));
        $res['method'] = $res['uri_parts'][3];
        $res['method'] = preg_replace('/[^a-zA-Z0-9]_/', '', $res['method']);

        // print_r($res); exit();

        //i--- RUN Service And Method ; torrison ; 01.05.2020 ; 6 ---/
        $route_class = "\\Services\\".$res['service']."\\".$res['class'];
        $route_method = $res['method'];

        $GLOBALS['app']['routing'] = $res;

        $run_service = new $route_class();
        if (isset($res['uri_parts'][5])) $run_service->$route_method($res['uri_parts'][4], $res['uri_parts'][5]);
        else if (isset($res['uri_parts'][4])) $run_service->$route_method($res['uri_parts'][4]);
        else $run_service->$route_method();


    }

}