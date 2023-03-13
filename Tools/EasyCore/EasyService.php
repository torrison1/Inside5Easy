<?php

namespace Tools\EasyCore;


use Tools\CommonCore\Sessions as Sessions;

Class EasyService {

    var $auth;
    var $data;
    var $view;
    var $security;
    var $db;
    var $response;
    var $input;


    public function __construct(){

        $this->db = new \Tools\CommonCore\Database();
        $this->db->init();

        $this->security = new \Tools\Security\Security();
        $this->security->init();
        $this->security->db =& $this->db;

        $this->sessions = new Sessions();
        $this->sessions->db = &$this->db;
        $this->sessions->security = &$this->security;
        $this->sessions->init();

        $this->input = new \Tools\CommonCore\Input();
        $this->input->db = &$this->db;
        $this->input->security = &$this->security;
        $this->input->init();

        $this->view = new \Tools\CommonCore\RenderView();

        $this->response = new \Tools\CommonCore\Response();

        $GLOBALS['Commons']['db'] = &$this->db;
        $GLOBALS['Commons']['sessions'] = &$this->sessions;
        $GLOBALS['Commons']['security'] = &$this->security;
        $GLOBALS['Commons']['input'] = &$this->input;
        $GLOBALS['Commons']['view'] = &$this->view;
        $GLOBALS['Commons']['response'] = &$this->response;

        $this->auth = new AuthSystemEasy();
        $this->auth->init();
        $GLOBALS['Commons']['auth'] = &$this->auth;

        $GLOBALS['Commons']['user'] = &$this->auth->user;
        $this->data['user'] = &$this->auth->user;
        $this->data['inside4_security'] = &$this->security;

    }

    public function render_admin_page($view_name, $interface_name){

        $admin_system = new \Tools\InsideAdminSystem\InsideAdminSystem;
        $admin_system->init();
        $this->data['menu_arr'] = $admin_system->get_top_menu_arr();
        $this->data['top_menu'] = $this->view->render_to_var($this->data, 'Parts/inside_menu.php', $template_folder = 'inside_admin_template');
        $this->data['admin_interface_name'] = $interface_name;
        // Other HTML Template
        $this->view->render($this->data, $view_name, 'inside_admin_template');
    }

    public function add_to_log ($info, $table = '', $sql = '', $log_item_id = 0, $log_obj_type = ''){
        $this->db->insert('erp_log', [
                'log_datetime' => time(),
                'log_info' => $info,
                'log_user_id' => @$GLOBALS['Commons']['user']['id'],
                'log_table' => $table,
                'log_sql' => $sql,
                'log_item_id' => $log_item_id,
                'log_obj_type' => $log_obj_type
            ]
        );
    }
}