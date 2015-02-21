<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require 'my_controller.php';

class Workcentre_registration_details extends My_controller
{

    function __construct()
    {
        parent::__construct();

        $this->load->model('workcentre_registration_details_model', 'workcentre_registration_details');

        // Determining is the logged in user allowed to go forward with the current action.
        $this->isAllowed();

        $this->table = 'workcentre_registration_details';
        $this->p_key = 'wrd_id';
    }

    function get_wlog()//Called by ajax
    {
        $model = $this->workcentre_registration_details;
        $wlogs = $this->init_wlog($this->p_key, $model);

        if ($wlogs[0])
        {
            $latest_class = 'wlog_latest'; // Latest details about the worklog. Ie: the data from ref_tables, not from Tbl:backups.
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

                    if ($fld == 'wrd_status')
                        $val = $status[$row[$fld]];
                    else if ($fld == 'wrd_date')
                        $val = formatDate($row[$fld], false);

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

    // Called by Ajax
    function get_registrations()
    {
        // Getting active registrations under the firm.
        $data = $this->workcentre_registration_details->get_active_option(array('wrd_fk_firms' => $this->firm_id));
        $this->json_options($data, 'No Active Registrations');
    }
    
    function get_license_name()
    {
       $wcntr_id = $this->input->post('wcntr_id');
       
       if(!$wcntr_id) return;
       
       $license_id = $this->workcentres->getFieldById($wcntr_id, 'wcntr_fk_workcentre_registration_details');
       $license_name = $this->workcentre_registration_details->getNameByid($license_id);
       if($license_name)
          echo $license_name;
       else
          echo "No Legal Licenses";
    }

    function add()
    {

        // Checking is the current task is enabled for the user
        $task = taskEnabled("workcentres/add");
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

        $input['wrd_fk_firms'] = $this->firm_id;
        $input['wrd_date'] = getSqlDate($input['wrd_date']);
        $input['wrd_name'] = ucwords(strtolower($input['wrd_name']));
        $input['wrd_status'] = 1; // Default status is active.
        // Inserting data to Tbl:workcentre_registration_details
        $insert_id = $this->workcentre_registration_details->insert($input);

        # Adding worklogs
        // Message related to the worklog.
        $msg = 'A new registration details <span class="wlg_name">' . $input['wrd_name'] . '</span> added.';

        // Inserting worklogs of Tbl: workcentre_registration_details.
        $this->send_wlog($this->table, $insert_id, $msg, $this->add, $this->add);

        if ($insert_id)
            echo 1;
        else
            echo '<div class="failureHead"><img src="images/failure.gif" width="15" height="15" /> Error!!!<div class="pop_failure">Data couldn\'t insert !</div></div>';
    }

    function beforeEdit()
    {
        // Checking is the current task is enabled for the user
        $this->isAllowedTask("workcentres/edit");
        $id = $_GET['wrd_id'];
        $details = $this->workcentre_registration_details->getById($id);
        $details['wrd_date'] = formatDate($details['wrd_date'], FALSE, 1);
        echo json_encode($details);
        return;
    }

    function edit()
    {
        // Checking is the current task is enabled for the user
        $task = taskEnabled("workcentres/edit");
        if ($task != 1)
        {
            echo $task;
            return;
        }

        // Validating 
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

        $wrd_id = $input['wrd_id'];

        // Getting previous details for worklog.
        $prev_details = $this->workcentre_registration_details->getById($wrd_id);

        $input['wrd_date'] = getSqlDate($input['wrd_date']);
        $input['wrd_name'] = ucwords(strtolower($input['wrd_name']));

        // Saving data to Tbl:workcentre_registration_details
        $this->workcentre_registration_details->save($input, $wrd_id);

        // Getting current details.
        $cur_details = $this->workcentre_registration_details->getById($wrd_id);


        # Adding to worklogs if anything edited.
        // Checking is anything edited.
        $edited = $this->isEdited($prev_details, $cur_details);

        // If edited, creating worklog.
        if ($edited)
        {
            // Workcentres those are using the license.
            $workcentres = $this->workcentres->get_workcentres_under_regname($wrd_id);

            // Message related to the worklog.
            $msg = 'The registration details of <span class="wlg_name">' . $input['wrd_name'] . '</span> has been edited.';

            // Inserting worklogs of Tbl: workcentre_registration_details.
            $this->send_wlog($this->table, $wrd_id, $msg, $this->edit, $this->edit, $workcentres, $prev_details);

            echo 1;
        }
        else
            echo '<div class="failureHead"><img src="images/failure.gif" width="15" height="15" /> Error!!!<div class="pop_failure">There is nothing to edit !</div></div>';
    }

    function check_unique($val, $field)
    {
        if (!$this->input->post($field))
            return TRUE;

        $wrd_id = $this->input->post('wrd_id');
        $where[$field] = $this->input->post($field);
        if ($this->workcentre_registration_details->is_exists($where, $wrd_id))
        {
            $this->form_validation->set_message('check_unique', 'The %s already exists.');
            return FALSE;
        }
        else
            return TRUE;
    }

    function toggleStatus()
    {
        // Checking is the current task is enabled for the user
        $this->isAllowedTask('workcentres/edit');
        
        $wrd_id = $this->input->post('wrd_id');
        $wrd_details = $this->workcentre_registration_details->getById($wrd_id);
        
        // Storing previous status.
        $prev_status = $this->workcentre_registration_details->getTableStatus($wrd_id);

        // Workcentres those are using the license.
        $workcentres = $this->workcentres->get_workcentres_under_regname($wrd_id);

        // Toggling status.
        $this->workcentre_registration_details->toggleTableStatus($wrd_id);

        // Storing current status.
        $curnt_status = $this->workcentre_registration_details->getTableStatus($wrd_id);

        // If Status changed; 
        if ($prev_status != $curnt_status)
        {
            $wrd_status = ($curnt_status == ACTIVE) ? "activated" : "deactivated";

            // Message related to the worklog.
            $msg = 'The Reg.Name <span class="wlg_name">' . $wrd_details['wrd_name'];
            $msg .= '</span>  has been ' . $wrd_status . '.';

            // Inserting worklogs of Tbl: workcentre_registration_details.
            $this->send_wlog($this->table, $wrd_id, $msg, $this->edit, $this->edit, $workcentres, $wrd_details);
            
            echo $wrd_details['wrd_name'] . " has been " . $wrd_status;
        }
        else
            echo "Couldn't change status.";
    }

}

?>
