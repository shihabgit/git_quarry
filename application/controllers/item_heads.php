<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require 'my_controller.php';

class Item_heads extends My_controller
{

    function __construct()
    {
        parent::__construct();

        $this->load->model('item_heads_model', 'item_heads');
        $this->load->model('item_category_model', 'item_category');

        // Determining is the logged in user allowed to go forward with the current action.
        $this->isAllowed();

        $this->table = 'item_heads';
        $this->p_key = 'itmhd_id';
    }

    function get_wlog()//Called by ajax
    {
        $model = $this->item_heads;
        $wlogs = $this->init_wlog($this->p_key, $model);

        if ($wlogs[0])
        {
            // The CSS class to represent latest details about the worklog. Ie: the data from ref_tables, not from Tbl:backups.
            $latest_class = 'wlog_latest';

            $wlog_fields = getWlogFields($this->table, 'keys');
            $itmcats = $this->item_category->get_option();
            $status = $model->get_status();
            echo "<table>";

            // Creating headers.
            $headers = getWlogFields($this->table, 'all');
            echo '<tr>';
            foreach ($headers as $head)
                echo '<th>' . $head . '</th>';
            echo '</tr>';

            foreach ($wlogs as $key => $row)
            {
                echo '<tr class="' . $latest_class . '">';
                $latest_class = '';
                foreach ($wlog_fields as $fld)
                {
                    $val = $row[$fld];
                    $edited = false;
                    if (isset($wlogs[$key + 1]) && ($wlogs[$key + 1][$fld] !== $val))
                        $edited = true;

                    if ($fld == 'itmhd_status')
                        $val = $status[$row[$fld]];
                    else if ($fld == 'itmhd_fk_item_category')
                        $val = $itmcats[$row[$fld]];

                    if ($edited)
                        echo '<td><span class="wlog_changed">' . $val . '</span></td>';
                    else
                        echo '<td>' . $val . '</td>';
                }
                echo '</tr>';
            }
            echo "</table>";
        }
        else
            echo "No Worklogs Found!!!";
    }

    function getItemHead()
    {
        $input['itmhd_fk_item_category'] = $_GET['parent_id'];

        if (!$input['itmhd_fk_item_category'])
            return;

        $data = $this->item_heads->get_active_option($input);
        $json[] = array('value' => '', 'text' => 'Select');
        foreach ($data as $key => $val)
            $json[] = array('value' => $key, 'text' => $val);
        echo json_encode($json);
    }

    function add()
    {

        // Checking is the current task is enabled for the user
        $task = taskEnabled($this->clsfunc);
        if ($task != 1)
        {
            echo $task;
            return;
        }

        //	Validating 
        $v_config = validationConfigs($this->table);
        $this->form_validation->set_rules($v_config);
        $this->form_validation->set_error_delimiters('<div class="pop_failure">', '</div>');
        if (!$this->form_validation->run())
        {
            $message = validation_errors();
            if ($message)
            {
                echo $this->errorTitle . $message;
                return;
            }
        }

        // Recieving input 
        $input = $this->get_inputs();

        $input['itmhd_name'] = ucwords(strtolower($input['itmhd_name']));

        // Inserting data to Tbl:owners
        $insert_id = $this->item_heads->insert($input);

        // Adding worklogs
        $wc_id = 0; // The worklog in not under any workcentre, It is a General worklog.
        $wc_firms = implode(',', $this->firms->getIds(array('firm_status' => 1))); // Worklog should be displayed in all active firms.

        $wlog_wc[$wc_id]['msg'] = 'A new Item Head <span class="wlg_name">' . $input['itmhd_name'] . '</span> added.';
        $wlog_wc[$wc_id]['action'] = $this->add;
        $this->add_logs($this->table, $insert_id, get_url($this->table), get_popup_id($this->table), $wlog_wc, $this->add, $wc_firms);

        if ($insert_id)
            echo 1;
        else
            echo '<div class="failureHead"><img src="images/failure.gif" width="15" height="15" /> Error!!!<div class="pop_failure">Data couldn\'t insert !</div></div>';
    }

    function index()
    {
        // Checking is the current task is enabled for the user
        $this->isAllowedTask($this->cls);

        //Set the flash data message if there is one has set before redirected to this page.
        $data['message'] = $this->session->flashdata('message');
        $data['message_level'] = $this->session->flashdata('message_level');
        $data['title'] = "Item Heads";
        $data['heading'] = "Item Heads";
        $data['status'] = $this->item_heads->get_status();
        $data['itmcats'] = $this->item_category->get_option();

        $data['table'] = $this->item_heads->index();
        $this->_render_page($this->clsfunc, $data);
    }

    function itemheads($str)
    {

        $input = $this->get_inputs();
        $unique['itmhd_fk_item_category'] = $input['itmhd_fk_item_category'];
        $unique['itmhd_name'] = ucwords(strtolower($input['itmhd_name']));

        if ($this->item_heads->is_exists($unique))
        {
            $this->form_validation->set_message('itemheads', "The item name $str already exists.");
            return FALSE;
        }
        return true;
    }

}

?>