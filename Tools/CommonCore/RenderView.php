<?php

namespace Tools\CommonCore;

Class RenderView {

    //i--- Easy render php view by php tags and include function ; inside_core ; torrison ; 01.05.2020 ; 1 ---/
    public function render($data, $page_center, $template_folder = 'app_default_template') {

        //i--- Turn Off Cache for Browsers (Optional) can be in php.ini ; inside_4_core_structure ; torrison ; 01.05.2020 ; 2 ---/
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        // Convert array data to local variables
        foreach($data as $key => $value) ${$key} = $value;
        include "Views/".$template_folder."/main_template.php";
    }

    //i--- Render View file to the variable ; inside_core ; torrison ; 01.05.2020 ; 2 ---/
    public function render_to_var($data, $file_path, $template_folder = 'app_default_template') {

        // Convert array data to local variables
        foreach($data as $key => $value) ${$key} = $value;
        ob_start();
        include "Views/".$template_folder."/".$file_path;
        return ob_get_clean();
    }
}