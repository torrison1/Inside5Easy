<?php

namespace Tools\CommonCore;

Class Response
{

    var $sessions;

    //i--- Output method for JSON Response ; inside_core ; torrison ; 01.05.2020 ; 1 ---/
    public function echo_json($response)
    {
        $response['new_token'] = $this->sessions->token;
        header('Content-Type: application/json');
        echo json_encode($response, JSON_PRETTY_PRINT);
    }

    public function echo_json_debug($response)
    {
        $response['new_token'] = $this->sessions->token;
        header('Content-Type: application/json');
        echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public function echo_json_free($response)
    {
        header('Content-Type: application/json');
        echo json_encode($response, JSON_PRETTY_PRINT);
    }

    public function echo_json_token($response, $token)
    {
        $response['token'] = $token;
        header('Content-Type: application/json');
        echo json_encode($response, JSON_PRETTY_PRINT);
    }

}