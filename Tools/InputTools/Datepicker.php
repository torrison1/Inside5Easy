<?php
namespace Tools\InputTools;

Class Datepicker
{
    static function js_init() {
?>
            // datepicker init <script>
            $(".datepicker").datepicker({
                weekStart: 1,
                todayHighlight: true
            });
<?php

    }
    static function payments_date_change($input_name, $api_url) {
        ?>
            $("input[name=<?=$input_name?>]").on('change', function(e) {
                let old_value = $(this).val();
                let item_id = $(this).attr('item_id');
                change_doc_time_fast(e.target.value, old_value, item_id);
            });
            function change_doc_time_fast(value, old_value, item_id) {
                $.post('<?=$api_url?>', {value : value, id : item_id}, function(data){
                    if (data.status == 'error') {
                        $.notify(data.message, "error");
                    } else {
                        $.notify("Data Saved!", "success");
                        $("input[name=doc_time]").attr('old_value', old_value);

                    }
                });
            }
    <?php

}
}