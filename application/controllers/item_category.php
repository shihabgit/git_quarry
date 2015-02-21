<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require 'my_controller.php';

class Item_category extends My_controller
{

    function __construct()
    {
        parent::__construct();

        $this->load->model('item_category_model', 'item_category');

        // Determining is the logged in user allowed to go forward with the current action.
        $this->isAllowed();

        $this->table = 'item_category';
        $this->p_key = 'itmcat_id';
    }
    
    function get_active_cats()
    {
        $data = $this->item_category->get_active_option();
        $json[] = array('value' => '', 'text' => 'Select');
        foreach ($data as $key => $val)
            $json[] = array('value' => $key, 'text' => $val);
        echo json_encode($json);
    }
    
    function get_wlog()//Called by ajax
    {   $model = $this->item_category;
        $wlogs = $this->init_wlog($this->p_key,$model);

        if ($wlogs[0])
        {
            // The CSS class to represent latest details about the worklog. Ie: the data from ref_tables, not from Tbl:backups.
            $latest_class = 'wlog_latest'; 
            
            $wlog_fields = getWlogFields($this->table, 'keys');
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

                    if ($fld == 'itmcat_status')
                        $val = $status[$row[$fld]];
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
        
        $input['itmcat_name'] = ucwords(strtolower($input['itmcat_name']));
                
        // Inserting data to Tbl:owners
        $insert_id = $this->item_category->insert($input);

        // Adding worklogs
        $wc_id = 0; // The worklog in not under any workcentre, It is a General worklog.
        $wc_firms = implode(',', $this->firms->getIds(array('firm_status' => 1))); // Worklog should be displayed in all active firms.

        $wlog_wc[$wc_id]['msg'] = 'A new Item Category <span class="wlg_name">' . $input['itmcat_name'] . '</span> added.';
        $wlog_wc[$wc_id]['action'] = $this->add;
        $this->add_logs($this->table, $insert_id, get_url($this->table), get_popup_id($this->table), $wlog_wc, $this->add, $wc_firms);

        if ($insert_id)
            echo 1;
        else
            echo '<div class="failureHead"><img src="images/failure.gif" width="15" height="15" /> Error!!!<div class="pop_failure">Data couldn\'t insert !</div></div>';
    }
}
?>