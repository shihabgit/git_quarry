<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require 'my_controller.php';

class Party_license_details extends My_controller
{

    function __construct()
    {
        parent::__construct();

        $this->load->model('parties_model', 'parties');
        $this->load->model('party_license_details_model', 'party_license_details');
      $this->load->model('party_destinations_model', 'party_destinations');

        // Determining is the logged in user allowed to go forward with the current action.
        $this->isAllowed();

        $this->table = 'party_license_details';
        $this->p_key = 'pld_id';
    }

    function get_wlog()//Called by ajax
    {
        $model = $this->party_license_details;
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

                    if ($fld == 'pld_status')
                        $val = $status[$row[$fld]];
                    else if ($fld == 'pld_address')
                        $val = nl2br($row[$fld]);

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
    
    function get_license_name()
    {
       $pdst_id = $this->input->post('pdst_id');
       
       if(!$pdst_id) return;
       
       $license_id = $this->party_destinations->getFieldById($pdst_id, 'pdst_fk_party_license_details');
       $license_name = $this->party_license_details->getNameByid($license_id);
       if($license_name)
          echo $license_name;
       else
          echo "No Legal Licenses";
    }

    // Called by Ajax
    function get_license_details()
    {
        $data = $this->party_license_details->get_option();
        
        if(!$data)
        {
            $json[] = array('value' => '', 'text' => 'No Active Licenses');
        }
        else
        {
            $json[] = array('value' => '', 'text' => 'Select');
            foreach ($data as $key => $val)
                $json[] = array('value' => $key, 'text' => $val);
        }
        
        echo json_encode($json);
    }

    // Called by Ajax
    function get_free_licenses()
    {
        // Getting licenses that are not used yet by anybody.
        $free_licenses = $this->party_license_details->getFreeLicenses();
        $data = $this->party_license_details->make_options($free_licenses, 'pld_id', 'pld_firm_name');
        
        
        if(!$data)
        {
            $json[] = array('value' => '', 'text' => 'No Active Licenses');
        }
        else
        {
            $json[] = array('value' => '', 'text' => 'Select');
            foreach ($data as $key => $val)
                $json[] = array('value' => $key, 'text' => $val);
        }
        
        echo json_encode($json);
    }

    function getAvailableLicenses()
    {
        $pty_id = $_GET['pty_id'];
        $available = $this->party_license_details->getPartysAvailableLicense($pty_id, 1);
        $data = $this->parties->make_options($available, 'pld_id', 'pld_firm_name');
        
        if(!$data)
        {
            $json[] = array('value' => '', 'text' => 'No Active Licenses');
        }
        else
        {
            $json[] = array('value' => '', 'text' => 'Select');
            foreach ($data as $key => $val)
                $json[] = array('value' => $key, 'text' => $val);
        }
        
        echo json_encode($json);
    }

    function add()
    {

        // Checking is the current task is enabled for the user
        $task = taskEnabled("parties/add");
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

        $input['pld_date'] = getSqlDate($input['pld_date']);
        $input['pld_firm_name'] = ucwords(strtolower($input['pld_firm_name']));
        $input['pld_status'] = 1; // Default status is active.
        // Inserting data to Tbl:owners
        $insert_id = $this->party_license_details->insert($input);

        // Adding worklogs
        $wc_id = 0; // The worklog in not under any workcentre, It is a General worklog.
        $wc_firms = implode(',', $this->firms->getIds(array('firm_status' => 1))); // Worklog should be displayed in all active firms.

        $wlog_wc[$wc_id]['msg'] = 'A new license details <span class="wlg_name">' . $input['pld_firm_name'] . '</span> added.';
        $wlog_wc[$wc_id]['action'] = $this->add;
        $this->add_logs($this->table, $insert_id, get_url('party_license_details'), get_popup_id('party_license_details'), $wlog_wc, $this->add, $wc_firms);

        if ($insert_id)
            echo 1;
        else
            echo '<div class="failureHead"><img src="images/failure.gif" width="15" height="15" /> Error!!!<div class="pop_failure">Data couldn\'t insert !</div></div>';
    }

    function beforeEdit()
    {
        // Checking is the current task is enabled for the user
        $this->isAllowedTask("parties/edit");
        $id = $_GET['pld_id'];
        $details = $this->party_license_details->getById($id);
        echo json_encode($details);
        return;
    }

    // Callback
    function checkUnique($val, $fld)
    {
        $id = $this->input->post('pld_id');
        $unique[$fld] = $val;
        if ($this->party_license_details->is_exists($unique, $id))
        {
            $this->form_validation->set_message('checkUnique', 'The %s already exists.');
            return FALSE;
        }
        return TRUE;
    }

    function edit()
    {   // Checking is the current task is enabled for the user
        $task = taskEnabled("parties/edit");
        if ($task != 1)
        {
            echo $task;
            return;
        }

        //	Validating 
        $this->form_validation->set_rules("pld_firm_name", 'Reg Name', 'required|max_length[50]|callback_checkUnique[pld_firm_name]');
        $this->form_validation->set_rules("pld_date", 'Date', 'required');
        $this->form_validation->set_rules("pld_address", 'Address', 'max_length[250]');
        $this->form_validation->set_rules("pld_phone", 'Phone', 'max_length[250]');
        $this->form_validation->set_rules("pld_email", 'Email', 'max_length[30]|valid_email');
        $this->form_validation->set_rules("pld_tin", 'TIN No', 'max_length[20]|callback_checkUnique[pld_tin]');
        $this->form_validation->set_rules("pld_licence", 'License No', 'max_length[20]|callback_checkUnique[pld_licence]');
        $this->form_validation->set_rules("pld_cst", 'CST', 'max_length[20]|callback_checkUnique[pld_cst]');

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

        $pld_id = $input['pld_id'];
        $pty_id = $this->party_license_details->getPartyByLicense($pld_id);

        // The license details before edit
        $prev_details = $this->party_license_details->getById($pld_id);

        // Party Details
        $party_details = $this->parties->getById($pty_id);

        $input['pld_firm_name'] = ucwords(strtolower($input['pld_firm_name']));
        $input['pld_date'] = getSqlDate($input['pld_date']);

        // Saving data to Tbl:owners
        $this->party_license_details->save($input, $pld_id);

        // The license details after edit
        $cur_details = $this->party_license_details->getById($pld_id);

        // Checking is anything edited.
        $edited = $this->isEdited($prev_details, $cur_details);

        if ($edited)
        {
            // Worklog should be displayed in all workcentres where party has been registered.
            $workcentres = $this->party_license_details->getLicenseWorkcentres($pld_id);

            // Message related to the worklog.
            $msg = 'The details of license';
            $msg .= ' <span class="wlg_name">' . $prev_details['pld_firm_name'] . '</span> of party';
            $msg .= ' <span class="wlg_name">' . $party_details['pty_name'] . '</span> has been changed.';

            // Inserting worklogs of Tbl: parties.
            $this->send_wlog($this->table, $pld_id, $msg, $this->edit, $this->edit, $workcentres, $prev_details);

            echo 1;
        }
        else
            echo '<div class="failureHead"><img src="images/failure.gif" width="15" height="15" /> Error!!!<div class="pop_failure">There is nothing changed!</div></div>';
    }

    function toggleStatus()
    {
        // Checking is the current task is enabled for the user
        $this->isAllowedTask('parties/edit');
        $pld_id = $this->input->post('pld_id');
        $pld_details = $this->party_license_details->getById($pld_id);
        $pld_name = $pld_details['pld_firm_name'];
        $pty_id = $this->party_license_details->getPartyByLicense($pld_id);
        $pty_details = $this->parties->getById($pty_id);
        $prev_status = $this->party_license_details->getTableStatus($pld_id);

        // Worklog should be displayed in all workcentres where the destination under the license has been registered.
        $pld_wcntrs = $this->party_license_details->getLicenseWorkcentres($pld_id);

        // Toggling status.
        $this->party_license_details->toggleTableStatus($pld_id);

        $curnt_status = $this->party_license_details->getTableStatus($pld_id);

        // If Status changed; 
        if ($prev_status != $curnt_status)
        {
            $status = ($curnt_status == 1) ? "activated" : "deactivated";

            // Message related to the worklog.
            $msg = 'The license <span class="wlg_name">' . $pld_name . '</span>';
            $msg .= ' of party <span class="wlg_name">' . $pty_details['pty_name'] . '</span>';
            $msg .= ' has been ' . $status . '.';

            // Inserting to worklog.
            $this->send_wlog($this->table, $pld_id, $msg, $this->edit, $this->edit, $pld_wcntrs, $pld_details);
            echo "The license " . $pld_name . " has been " . $status;
        }
        else
            echo "Couldn't change status.";
    }

}

?>
