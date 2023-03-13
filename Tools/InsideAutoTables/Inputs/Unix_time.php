<?php
namespace Tools\InsideAutoTables\Inputs;

class Unix_time {


	public function input_form($input_array)
	{
	    if (isset($input_array['set_default_now']) AND $input_array['value'] == 0) $input_array['value'] = time();
    	return "<input type=\"text\" name=\"".$input_array['name']."\" id=\"".$input_array['name']."\" class=\"input form-control\" value=\"".date("Y-m-d H:i:s", intval($input_array['value']))."\" >";
	}
	public function db_save($input_array)
	{
		return strtotime($input_array['value']);
	}
	public function crud_view($input_array)
	{
		return date("Y-m-d H:i:s", $input_array['value']);
	}

}
