<?php

namespace Tools\InsideAutoTables\Tables;

Class Inside_log
{
    var $table_config;
    var $table_columns;
    var $db_table_name = 'inside_log';

    public function init()
    {


        $i = 0;
        $table_columns[$i]['name'] = 'log_id';
        $table_columns[$i]['text'] = 'ID';
        $table_columns[$i]['column_width'] = '100';
        $table_columns[$i]['in_crud'] = true;


        $i++;
        $table_columns[$i]['name'] = 'log_datetime';
        $table_columns[$i]['text'] = 'Datetime';
        $table_columns[$i]['tab'] = 'main';
        $table_columns[$i]['input_type'] = 'unix_time';
        $table_columns[$i]['in_crud'] = true;

        $i++;
        $table_columns[$i]['name'] = 'log_table';
        $table_columns[$i]['text'] = 'Table';
        $table_columns[$i]['tab'] = 'main';
        $table_columns[$i]['input_type'] = 'text';
        $table_columns[$i]['in_crud'] = true;

        $i++;
        $table_columns[$i]['name'] = 'log_user_id';
        $table_columns[$i]['text'] = 'User ID';
        $table_columns[$i]['tab'] = 'main';
        $table_columns[$i]['input_type'] = 'text';
        $table_columns[$i]['in_crud'] = true;

        $i++;
        $table_columns[$i]['name'] = 'log_sql';
        $table_columns[$i]['text'] = 'SQL';
        $table_columns[$i]['tab'] = 'main';
        $table_columns[$i]['column_width'] = '4600';
        $table_columns[$i]['input_type'] = 'textarea';
        $table_columns[$i]['in_crud'] = true;

        $i++;
        $table_columns[$i]['name'] = 'data';
        $table_columns[$i]['text'] = 'SQL';
        $table_columns[$i]['tab'] = 'main';
        $table_columns[$i]['column_width'] = '4600';
        $table_columns[$i]['input_type'] = 'textarea';
        $table_columns[$i]['in_crud'] = true;




        $table_config['key'] = 'log_id';


        $table_config['cell_tabs_arr'] = Array (
            'main' => 'Main',
        );

        $this->table_config = $table_config;
        $this->table_columns = $table_columns;
    }
}