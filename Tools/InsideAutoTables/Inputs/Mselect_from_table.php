<?php
namespace Tools\InsideAutoTables\Inputs;

// MultiSelect ( NEED UI_Multiselect Script !!! )
// <script type="text/javascript" src="/files/jquery_lib/mselect/jquery.multiselect.min.js"></script>
// <link rel="stylesheet" type="text/css" href="/files/jquery_lib/mselect/jquery.multiselect.css" />


class MSelect_from_Table {


    static function input_form($input_array)
    {
        $db =& $GLOBALS['Commons']['db'];
        if ( ! is_array($input_array['value'])) $input_array['value'] = Array();

        if ( ! isset($input_array['select_table_where'])) $input_array['select_table_where'] = '';
        $sql = "SELECT ".$input_array['select_index'].", ".$input_array['select_field']." FROM ".$input_array['select_table']." ".$input_array['select_table_where']." ORDER BY ".$input_array['select_field']." ASC";

        if (isset($input_array['select_sql'])) $sql = $input_array['select_sql'];

        $res = $db->sql_get_data($sql);
        $data = '';
        $i=0;
        foreach ($res as $row)
        {
            $variants[$i]['name'] = $row [$input_array['select_field']];
            $tmp_index = $input_array['select_index'];
            $variants[$i]['id'] = $row [$tmp_index];
            $i++;
        }
        $style = '';

        if (!isset($input_array['adv_classes'])) $input_array['adv_classes'] = '';

        if (isset($input_array['style'])) $style = ' style="'.$input_array['style'].'"';

        $data_action = '';
        if (isset($input_array['data_action'])) $data_action = ' data-action="'.$input_array['data_action'].'"';

        $data_type = '';
        if (isset($input_array['data_type'])) $data_type = ' data-type="'.$input_array['data_type'].'"';

        $item_id = '';
        if (isset($input_array['item_id'])) $item_id = ' item_id="'.$input_array['item_id'].'"';


        // "Nothing selected" phrase language
        $nothing_selected = $title = 'Ничего не выбрано';

        $action_box = '';
        $live_search = '';
        if( isset($input_array['action_box']) AND $input_array['action_box']) $action_box = "data-actions-box=\"true\"";
        if( isset($input_array['live_search']) AND $input_array['live_search']) $live_search = "data-live-search=\"true\"";


        $data .= "
  		<select ".$style." class=\"form-control selectpicker".$input_array['adv_classes']."\" {$data_action} {$data_type} {$item_id} title=\"$title\" multiple $action_box  $live_search name=\"".$input_array['name']."\" id=\"".$input_array['name']."\">
  		";

        if (!isset($input_array['no_default_value'])) $data .= "<option value=\"\">$nothing_selected</option>";
        $i=0;
        while (isset ($variants[$i]['id']))
        {
            if (in_array($variants[$i]['id'], $input_array['value'])) $selected = " SELECTED"; else $selected = "";
            $data .= "<option value=\"".$variants[$i]['id']."\"".$selected.">[".$variants[$i]['id']."] ".$variants[$i]['name']."</option>";
            $i++;
        }
        $data .= "
  		</select>\n\n
  		
  		";
        return $data;
    }

}