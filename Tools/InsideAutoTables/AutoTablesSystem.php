<?php
namespace Tools\InsideAutoTables;

use Tools\Security\Security as Security;

Class AutoTablesSystem
{
    public function init() {

    }

    public function generate_top_filters(&$table_obj) {
            $filters = '';
            // Make Filters
            $filters = Array();
            foreach ($table_obj->table_columns as $config_row) {
                if (isset ($config_row['filter'])) {

                    if (isset($config_row['default_filter_value']))
                        $config_row['value'] = $config_row['default_filter_value'];
                    else $config_row['value'] = '';

                    // Filter by current user id
                    if (isset($config_row['filter_current_user_id']))
                        $config_row['value'] = $this->user->id;

                    if (isset($_GET[$config_row['name']]))
                        $config_row['value'] = Security::xss_cleaner($_GET[$config_row['name']]);

                    if (isset($config_row['input_type'])) {
                        if (!isset($config_row['filters_tab'])) $config_row['filters_tab'] = 'Main';
                        if (!isset($config_row['filters_column'])) $config_row['filters_column'] = 1;
                        if (isset($config_row['filter_method']))
                            $filter_input = $this->make_input($config_row['filter_method'], $config_row);
                        else $filter_input = $this->make_input("input_filter", $config_row);
                        $filters[] = Array(
                            'text' => $config_row['text'],
                            'input' => $filter_input,//$this->inside_lib->make_input("input_filter", $config_row), // add new filter method
                            'filters_tab' => $config_row['filters_tab'],
                            'filters_column' => $config_row['filters_column'],
                        );
                    }

                }
            }
            return $filters;
    }

    public function make_input($part, $input_array)
    {
        if (isset($input_array['input_type'])) {
            if (!isset($input_array['width'])) $input_array['width'] = 400;
            $type = $input_array['input_type'];
            $type = str_replace("-", "_", $type); // Fix Minus to C++ style

            $input_class = "\\Tools\\InsideAutoTables\\Inputs\\".ucfirst($type);

            if (class_exists($input_class)) {

                $input_obj = new $input_class();

                if (method_exists($input_obj, $part)) {
                    return $input_obj->$part($input_array);
                } else if ($part == "input_filter") {
                    $input_array['width'] = 100;
                    return $input_obj->input_form($input_array) . "<br />\n"; // Default input for form
                } else if ($part == "db_save") {
                    return $input_array['value']; // Default value without changes
                } else if ($part == "crud_view") {
                    return $input_array['value']; // Default value without changes
                }

            } else return "File not found: " . APPPATH . 'models/inside/inputs/' . $type . '.php';
        }
    }
    public function make_rel_input($part, $input_array, $cell_id)
    {
        if (isset($input_array['input_type'])) {
            if (!isset($input_array['width'])) $input_array['width'] = 500;
            $type = $input_array['input_type'];
            $type = str_replace("-", "_", $type); // Fix Minus to C++ style

            $input_class = "\\Tools\\InsideAutoTables\\Inputs\\".ucfirst($type);

            if (class_exists($input_class)) {

                $input_obj = new $input_class();

                if (method_exists($input_obj, $part)) {
                    return $input_obj->$part($input_array, $cell_id);
                }
            }
            else return "Class {$input_class} not found!";
        }
    }

    public function get_table_arr($table_name, $filter)
    {

        $db = &$GLOBALS['Commons']['db'];
        $input = &$GLOBALS['Commons']['input'];
        $auth = &$GLOBALS['Commons']['auth'];

        $table_class = "\\Tools\\InsideAutoTables\\Tables\\".$table_name;
        if (!class_exists($table_class)) exit('No Table '.$table_name.' class!');
        $table_obj = new $table_class();
        $table_obj->init();

        $table_config = $table_obj->table_config;
        $table_columns = $table_obj->table_columns;
        $table_name = $table_obj->db_table_name;

        // Connect other DB
        if (isset($table_obj->other_db_name)) {
            $adv_db = new \Tools\CommonCore\Database();
            $adv_db->init($table_obj->other_db_name);
            $db = &$adv_db;
        }

        // Add Special vars
        $order = " ORDER BY `".$table_config['key']."` DESC";
        if (isset($table_config['order_by'])) $order = $table_config['order_by'];
        $where = '';
        $where_filter = '';
        $limit = ' ';
        $asc = 'ASC';
        $columns = '';
        $join_columns = '';
        $join = '';


        // Prepare Where Filter: Form Filter + Multi Search
        foreach ($table_columns as $config_row) {

            if (isset ($config_row['in_crud'])) $columns .= $table_name . '.' . $config_row['name'] . ", "; //was ambiguous - add table_name
            //if (isset ($config_row['in_crud'])) {  //filter without in crud option

            // =============== Filters generation =============== =============== ===============
            if (isset ($config_row['filter'])) {
                $tmp_name = $config_row['name'];

                if (isset($config_row['filter_method'])) {

                    if ($config_row['filter_method'] == 'multiselect_filter') {
                        if (isset($_POST[$tmp_name]) AND count($_POST[$tmp_name]) > 0) {

                            foreach ($_POST[$tmp_name] as &$variant) {
                                $variant = (int) $input->defend_filter(4, $variant);
                            }
                            unset($variant);

                            $variants = implode(', ', $_POST[$tmp_name]);
                            $where_filter .= " AND " . $table_name . '.' . $config_row['name'] . " IN (" . $variants . ")";
                        }
                    } elseif ($config_row['filter_method'] == 'like_filter') {
                        if (isset($_POST[$tmp_name]) AND strlen($_POST[$tmp_name]) > 1)
                            $where_filter .= " AND " . $table_name . '.' . $config_row['name'] . " like '%" . $_POST[$tmp_name] . "%'";
                    } elseif ($config_row['filter_method'] == 'comparison_filter') {
                        if (isset($_POST['comparison_' . $tmp_name]) AND $_POST['comparison_' . $tmp_name] != '') {
                            if($_POST['to_' . $tmp_name] == 0) $_POST['to_' . $tmp_name] = 9999999999;
                            $where_filter .= " AND " . $table_name . '.' . $config_row['name'] . " BETWEEN " . intval($_POST['from_' . $tmp_name]) . " AND " . intval($_POST['to_' . $tmp_name]);
                        }
                    }
                } else {

                    $_POST[$tmp_name] = $input->defend_filter(4, @$_POST[$tmp_name]);
                    if (strlen($_POST[$tmp_name]) > 0)
                        $where_filter .= " AND " . $table_name . '.' . $config_row['name'] . " = '" . $_POST[$tmp_name] . "'"; //here also was ambiguous

                }
            }

            if ((isset($filter['fsearch'])) && (strlen($filter['fsearch']) > 1))
                $where .= " CONVERT(" . $table_name . '.' . $config_row['name'] . ", CHAR) like '%" . $filter['fsearch'] . "%' OR"; // here also was ambiguous + CONVERT MODIFY

            if ((isset($filter['fkey'])) && (intval($filter['fkey']) > 0))
                $where .= " " . $table_name . '.' . $table_config['key'] . " = '" . $filter['fkey'] . "' OR"; //here also was ambiguous

            //}
        }
        $columns = substr($columns, 0, -2);
        $where = substr($where, 0, -3);

        // Prepare Order parametrs
        if ( (isset($filter['asc'])) && (strlen($filter['asc']) > 1) ) $asc = $filter['asc'];
        if ( (isset($filter['order'])) && (strlen($filter['order']) > 1) )  $order = " ORDER BY `".$filter['order']."` ".$asc;

        // Prepare Limit and Page parametrs
        if ( (isset($filter['page'])) && ($filter['page'] > 0) ) $filter['page']--;
        if ( (isset($filter['limit'])) && ($filter['limit'] > 0) ) $limit = " LIMIT ".intval($filter['page'])*intval($filter['limit']).",".intval($filter['limit']);

        // Add Where to request
        if (strlen($where) > 2) $where = ' WHERE 1 '.$where_filter.' AND ('.$where.') ';
        else $where = ' WHERE 1 '.$where_filter.' ';

        // Make Join Columns
        if (isset ($table_join))
        {
            foreach ($table_join as $join_arr) {

                $join_columns_this_table = '';
                foreach ($table_columns as $config_row) {
                    if ( (isset ($config_row['join'])) && ($config_row['join'] == $join_arr['table']))
                    {

                        // adv_columns
                        $join_columns .= ", ".$join_arr['table'].".".$config_row['join_column']." ".$config_row['join_as'];
                        //$join_columns_this_table .= $config_row['join_column'].", ";
                    }
                }
                $join .= " LEFT JOIN ".$join_arr['table']." ON ".$table_name.".".$join_arr['table_key']." = ".$join_arr['table'].".".$join_arr['join_key']." ";

            }
        }


        if (isset($table_config['join_sql'])) {
            $join .= $table_config['join_sql'];
        }

        if (isset($table_config['adv_columns'])) {
            $join_columns .= ', ' . $table_config['adv_columns'];
        }



        // Make Request
        $query_sql = 'SELECT '.$columns.$join_columns.' FROM '.$table_name.$join.$where.$order.$limit;
        $return['res'] = $db->sql_get_data($query_sql);
        $return['sql'] = $query_sql;

        return $return;

    }

    public function get_table_cell_arr($table_obj, $cell_id, $db = false)
    {

        if (!$db) $db = &$GLOBALS['Commons']['db'];
        else $db = &$db;

        // Make Request
        $array = $db->sql_get_data("SELECT * FROM ".$table_obj->db_table_name." WHERE ".$table_obj->table_config['key']." = ".intval($cell_id)." LIMIT 1");
        // Return One Row!
        return $array[0];
    }

    public function check_access($module_name, $type)
    {

        $db =& $GLOBALS['Commons']['db'];
        $user =& $GLOBALS['Commons']['user'];
        $auth =& $GLOBALS['Commons']['auth'];

        if (!isset($user['id'])) exit('No User!<script>location.href = \'/auth/page/login\'</script>');

        $user_id =$user['id'];

        $type_clear = '';
        if ($type == 'init') $type_clear = 'init';
        if ($type == 'view') $type_clear = 'view';
        if ($type == 'edit') $type_clear = 'edit';
        $sql = "
            SELECT
            	top_menu_url as url

				FROM inside_top_menu

				LEFT JOIN inside_groups_access ON inside_groups_access.module_id = top_menu_id
				LEFT JOIN auth_users_groups ON auth_users_groups.group_id = inside_groups_access.group_id

				WHERE

				auth_users_groups.user_id = " . intval($user_id) . "

				AND module_" . $type_clear . " = 1

				AND top_menu_module_name = " . $db->quote($module_name) . "

				GROUP BY top_menu_id

				ORDER BY inside_top_menu.top_menu_name

        ";
        $res = $db->sql_get_data($sql);

        // print_r($module_name);

        if (isset($res[0]) OR $auth->is_admin()) return true;
        else {
            echo "Access Denied!";
            die();
        }
    }

    public function update_table_cell($table_name, $tab, $cell_id)
    {

        $db =& $GLOBALS['Commons']['db'];
        $input =& $GLOBALS['Commons']['input'];
        $user =& $GLOBALS['Commons']['user'];

        // Load Table Config
        $table_class = "\\Tools\\InsideAutoTables\\Tables\\".$table_name;
        if (!class_exists($table_class)) exit('No Table '.$table_name.' class!');
        $table_obj = new $table_class();
        $table_obj->init();

        // Make Update Array by Input Fields
        $update_array = Array();

        if(!isset($_POST['not_update_table_columns'])) {
            foreach ($table_obj->table_columns as $config_row) {
                if ((isset($config_row['tab'])) && ($config_row['tab'] == $tab)) {
                    $tmp_name = $config_row['name'];

                    if (!isset ($config_row['defend_filter'])) $config_row['defend_filter'] = 1;
                    $config_row['value'] = $input->defend_filter(intval($config_row['defend_filter']), @$_POST[@$tmp_name]);

                    $config_row['cell_id'] = $cell_id;
                    $config_row['tab'] = $tab;
                    $config_row['table'] = $table_obj->db_table_name;
                    $config_row['post_array'] = $_POST;
                    $config_row['save_type'] = 'update';

                    $save_value = $this->make_input('db_save', $config_row);

                    if (!(is_bool($save_value) && !$save_value)) {
                        if (is_array($save_value)) {
                            foreach ($save_value as $key => $value) $update_array[$key] = $value;
                        } else $update_array[$tmp_name] = $save_value;
                    }
                }
            }
        }


        // Check Connect other DB
        $main_db = &$db;
        if (isset($table_obj->other_db_name)) {
            $adv_db = new \Tools\CommonCore\Database();
            $adv_db->init($table_obj->other_db_name);
            $db = &$adv_db;
        }

        if (count ($update_array) > 0) $db->update($table_obj->db_table_name, $update_array, ' WHERE '.$table_obj->table_config['key']." = ".intval($cell_id));

        $main_db->insert(
            'inside_log',
            Array(
                'log_table' => $table_obj->db_table_name,
                'log_datetime' => time(),
                'log_sql' => 'UPDATE '.$table_obj->db_table_name.print_r($update_array, true).' WHERE '.$table_obj->table_config['key']." = ".intval($cell_id),
                'log_user_id' => $user['id'])
        );

        if (isset($table_obj->adv_rel_inputs))
        {
            foreach ($table_obj->adv_rel_inputs as $rel_input_arr) {
                if ($rel_input_arr['tab'] == $tab) {
                    $rel_input_arr['base_table'] = $table_obj->db_table_name;
                    $this->make_rel_input("db_save", $rel_input_arr, $cell_id);
                }
            }
        }
        return "Ok!";
    }


    public function insert_table_cell($table_name)
    {
        $db =& $GLOBALS['Commons']['db'];
        $input =& $GLOBALS['Commons']['input'];
        $user =& $GLOBALS['Commons']['user'];

        // Load Table Config
        $table_class = "\\Tools\\InsideAutoTables\\Tables\\".$table_name;
        if (!class_exists($table_class)) exit('No Table '.$table_name.' class!');
        $table_obj = new $table_class();
        $table_obj->init();

        // Make Update Array by Input Fields
        $insert_array = Array();
        foreach ($table_obj->table_columns as $config_row) {
            $tmp_name = $config_row['name'];

            if (!isset ($config_row['defend_filter'])) $config_row['defend_filter'] = 1;
            $config_row['value'] = $input->defend_filter(intval($config_row['defend_filter']), @$_POST[$tmp_name]);

            $config_row['table'] = $table_obj->db_table_name;
            $config_row['post_array'] = $_POST;
            $config_row['save_type'] = 'insert';

            $save_value = $this->make_input('db_save', $config_row);
            if (! (is_bool($save_value) && !$save_value))
            {
                if (is_array($save_value))
                {
                    foreach ($save_value as $key => $value) $insert_array[$key] = $value;
                }
                else $insert_array[$tmp_name] = $save_value;
            }
        }

        // Check Connect other DB
        $main_db = &$db;
        if (isset($table_obj->other_db_name)) {
            $adv_db = new \Tools\CommonCore\Database();
            $adv_db->init($table_obj->other_db_name);
            $db = &$adv_db;
        }

        // print_r($insert_array);

        if (count ($insert_array) > 0) $db->insert($table_obj->db_table_name, $insert_array);

        $cell_id = $db->last_id();

        $main_db->insert('inside_log', Array('log_table' => $table_obj->db_table_name, 'log_datetime' => time(), 'log_sql' => 'INSERT '.$table_obj->db_table_name.print_r($insert_array, true), 'log_user_id' => $user['id']));

        //$cell_id = $this->db->insert_id();

        if (isset($table_obj->adv_rel_inputs))
        {
            foreach ($table_obj->adv_rel_inputs as $rel_input_arr) {
                $rel_input_arr['base_table'] = $table_obj->db_table_name;
                $this->make_rel_input("db_add", $rel_input_arr, $cell_id);
            }
        }
        return "Ok!";
    }

    public function del_table_cell($table_name)
    {
        if (isset($_POST['del_ids']))
        {
            // Load Config
            $db =& $GLOBALS['Commons']['db'];
            $user =& $GLOBALS['Commons']['user'];

            // Load Table Config
            $table_class = "\\Tools\\InsideAutoTables\\Tables\\".$table_name;
            if (!class_exists($table_class)) exit('No Table '.$table_name.' class!');
            $table_obj = new $table_class();
            $table_obj->init();

            foreach ($_POST['del_ids'] as $del_id)
            {
                echo intval ($del_id);

                // Check Connect other DB
                $main_db = &$db;
                if (isset($table_obj->other_db_name)) {
                    $adv_db = new \Tools\CommonCore\Database();
                    $adv_db->init($table_obj->other_db_name);
                    $db = &$adv_db;
                }

                $db->run_sql("DELETE FROM ".$table_obj->db_table_name." WHERE ".$table_obj->table_config['key']." = ".intval($del_id));

                $main_db->insert('inside_log', Array('log_table' => $table_obj->db_table_name, 'log_datetime' => time(), 'log_sql' => 'DELETE id = '.$del_id.' FROM '.$table_obj->db_table_name, 'log_user_id' => $user['id']));
            }
        }
        return "Ok!";
    }

}