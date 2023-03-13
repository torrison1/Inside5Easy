<?php
namespace Services\Crm;

Class Api extends \Tools\CommonCore\SmartWebsiteService
{

    public function add_contacts_request()
    {

        $data['requests_user_contacts'] = $this->security->xss_cleaner($_POST['name_contacts']);
        $data['requests_message'] = $this->security->xss_cleaner($_POST['message']);
        $data['requests_type'] = 1;
        $data['requests_result'] = 1;
        $data['requests_url'] = $this->security->xss_cleaner($_POST['url']);
        $data['requests_datetime'] = time();
        if (isset($this->auth->user['id'])) $user_id = $this->auth->user['id'];
        else $user_id = 0;
        $data['requests_user_id'] = $user_id;

        // Usage of CRM Model
        $crm = new \Tools\CRM\CRM();
        $crm->init_from_controller($this);

        $crm->website_request($data);

        $this->sessions->track_activity('contacts request from user #'.$user_id);

        $result['status'] = "success";
        $this->response->echo_json($result);
    }
}