<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require 'my_controller.php';

class Owners extends My_controller
{

    function __construct()
    {
        parent::__construct();

        $this->load->model('owners_model', 'owners');

        // Determines if the logged in user is allowed to go forward with the current action.
        $this->isAllowed();
        
        
        $this->table = 'owners';
        $this->p_key = 'ownr_id';
    }

    
    
    function get_wlog()//Called by ajax
    {   $model = $this->owners;
        $wlogs = $this->init_wlog($this->p_key,$model);

        if ($wlogs[0])
        {
            $latest_class = 'wlog_latest'; // Latest details about the worklog. Ie: the data from ref_tables, not from Tbl:backups.
            $wlog_fields = getWlogFields($this->table, 'keys');
            
            $status = $model->get_owners_status();
            
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

                    if ($fld == 'ownr_status')
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

    
    
    function get_active_owners()
    {
        $data = $this->owners->get_active_option();
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
        $v_config = validationConfigs('owners');
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

        $input['ownr_date'] = getSqlDate($input['ownr_date']);

        // Inserting data to Tbl:owners
        $insert_id = $this->owners->insert($input);

        // Adding worklogs
        $wc_id = 0; // The worklog in not under any workcentre, It is a General worklog.
        $wc_firms = implode(',', $this->firms->getIds(array('firm_status' => 1))); // Worklog should be displayed in all active firms.

        $wlog_wc[$wc_id]['msg'] = 'A new owner <span class="wlg_name">' . $input['ownr_name'] . '</span> added.';
        $wlog_wc[$wc_id]['action'] = $this->add;
        $this->add_logs('owners', $insert_id, get_url('owners'), get_popup_id('owners'), $wlog_wc, $this->add, $wc_firms);

        if ($insert_id)
            echo 1;
        else
            echo '<div class="failureHead"><img src="images/failure.gif" width="15" height="15" /> Error!!!<div class="pop_failure">Data couldn\'t insert !</div></div>';
    }

}

?>