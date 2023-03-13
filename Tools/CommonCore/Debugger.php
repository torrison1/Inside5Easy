<?php

namespace Tools\CommonCore;

Class Debugger {

    public function init() {

        //i--- Set time for count speed (Optional) ; inside_4_core_structure ; torrison ; 01.05.2020 ; 1 ---/
        $GLOBALS['app']['Timer']['time_start'] = microtime(true);

    }

    public function debug_global($exit = false) {

        $GLOBALS['app']['Timer']['time_finish'] = microtime(true);
        $GLOBALS['app']['Timer']['time_diff'] = $GLOBALS['app']['Timer']['time_finish'] - $GLOBALS['app']['Timer']['time_start'];
        // print_r($GLOBALS);
        echo "<pre>".json_encode($GLOBALS['app'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)."</pre>";
        if ($exit) exit();

    }

    public function debug_var($data, $exit = false) {

        echo "<pre>".json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)."</pre>";
        if ($exit) exit();
    }

}
