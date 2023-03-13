<?php
namespace Tools\InsideAutoTables\Inputs;

class Resend_request
{
    public function input_form($input_array, $cell_id)
    {
        $db =& $GLOBALS['Commons']['db'];
        $sql = "SELECT * FROM cb_chats_log WHERE id = ".intval($cell_id)." LIMIT 1";
        $res = $db->sql_get_data($sql);
        if (isset($res[0]['chat_type_id'])) {
            if ($res[0]['chat_type_id'] == 2) {
                ?>
                <a href="<?=$GLOBALS['config']['Chatbots']['viber_webhook_url']?>?debug_from_log_id=<?= $cell_id ?>"
                   class="btn btn-success" target="_blank">RE-Send &gt;&gt;</a>
                <?php
            } elseif ($res[0]['chat_type_id'] == 3 OR $res[0]['chat_type_id'] == 4) {
                ?>
                <a href="<?=$GLOBALS['config']['Chatbots']['fb_webhook_url']?>?debug_from_log_id=<?= $cell_id ?>"
                   class="btn btn-success" target="_blank">RE-Send &gt;&gt;</a>
                <?php
            } else {
                ?>
                <a href="<?=$GLOBALS['config']['Chatbots']['telegram_webhook_url']?>?debug_from_log_id=<?= $cell_id ?>"
                   class="btn btn-success" target="_blank">RE-Send &gt;&gt;</a>
                <?php
            }
        }
        $data = ob_get_clean();
        return $data;
    }

    public function db_save($input_array, $cell_id)
    {

    }

}
