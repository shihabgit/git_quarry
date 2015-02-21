<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require 'my_controller.php';

class Party_vehicles extends My_controller
{

    function __construct()
    {
        parent::__construct();

        $this->load->model('parties_model', 'parties');
        $this->load->model('party_vehicles_model', 'party_vehicles');
        $this->load->model('destination_workcentres_model', 'destination_workcentres');

        // Determining is the logged in user allowed to go forward with the current action.
        $this->isAllowed();

        $this->table = 'party_vehicles';
        $this->p_key = 'pvhcl_id';
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
        $this->form_validation->set_rules("pvhcl_fk_parties", 'Party', 'required');
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
        $input['pvhcl_status'] = 1;
        $input['pvhcl_name'] = ucwords(strtolower($input['pvhcl_name']));
        $input['pvhcl_no'] = strtoupper($input['pvhcl_no']);
        
        // Inserting vehicle
        $insert_id = $this->party_vehicles->insert($input);
        
        $pty_id = $input['pvhcl_fk_parties'];
        $party_details = $this->parties->getById($pty_id);


        // Worklog should be displayed in all workcentres where party has been registered.
        $workcentres = $this->destination_workcentres->getPartyWorkcentres($pty_id);

        // Message related to the worklog.
        $msg = 'A new vehicle';
        $msg .= ' <span class="wlg_name">' . $input['pvhcl_no'] . '</span> for party';
        $msg .= ' <span class="wlg_name">' . $party_details['pty_name'] . '</span> has been added.';

        // Inserting worklogs of Tbl: parties.
        $this->send_wlog($this->table, $insert_id, $msg, $this->add, $this->add, $workcentres);
        if($insert_id)
            echo 1;
        else
            echo '<div class="failureHead"><img src="images/failure.gif" width="15" height="15" /> Error!!!<div class="pop_failure">Couldn\'t add vehicle !</div></div>';
    }

    function get_wlog() //Called by ajax
    {
        $model = $this->party_vehicles;
        $wlogs = $this->init_wlog($this->p_key, $model);

        if ($wlogs[0])
        {
            $latest_class = 'wlog_latest'; // Latest details about the worklog. Ie: the data from ref_tables, not from Tbl:backups.
            $wlog_fields = getWlogFields($this->table, 'keys');
            $status = $model->get_status();
            $parties = $this->parties->get_option();
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

                    if ($fld == 'pvhcl_fk_parties')
                        $val = $parties[$row[$fld]];
                    else if ($fld == 'pvhcl_status')
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

    function beforeEdit()
    {
        // Checking is the current task is enabled for the user
        $this->isAllowedTask("parties/edit");
        $id = $_GET['pvhcl_id'];
        $details = $this->party_vehicles->getById($id);
        echo json_encode($details);
        return;
    }

    function toggleStatus()
    {
        // Checking is the current task is enabled for the user
        $this->isAllowedTask('parties/edit');
        $pvhcl_id = $this->input->post('pvhcl_id');
        $pvhcl_details = $this->party_vehicles->getById($pvhcl_id);
        $pty_id = $this->party_vehicles->getPartyFromVehicle($pvhcl_id);
        $pty_details = $this->parties->getById($pty_id);
        $prev_status = $this->party_vehicles->getTableStatus($pvhcl_id);


        // Toggling status.
        $this->party_vehicles->toggleTableStatus($pvhcl_id);

        $curnt_status = $this->party_vehicles->getTableStatus($pvhcl_id);

        // If Status changed; 
        if ($prev_status != $curnt_status)
        {
            $status = ($curnt_status == 1) ? "activated" : "deactivated";

            // Id of workcentres related to the party.
            $partys_wcntrs = $this->destination_workcentres->getPartyWorkcentres($pty_id);

            // Message related to the worklog.
            $msg = 'The vehicle <span class="wlg_name">' . $pvhcl_details['pvhcl_no'] . '</span>';
            $msg .= ' of party <span class="wlg_name">' . $pty_details['pty_name'] . '</span>';
            $msg .= ' has been ' . $status . '.';

            // Inserting worklogs of Tbl: party_vehicles.
            $this->send_wlog($this->table, $pvhcl_id, $msg, $this->edit, $this->edit, $partys_wcntrs, $pvhcl_details);
            echo "The vehicle " . $pvhcl_details['pvhcl_no'] . " has been " . $status;
        }
        else
            echo "Couldn't change status.";
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
        $v_config = validationConfigs($this->table);
        $this->form_validation->set_rules($v_config);
        $this->form_validation->set_rules("pvhcl_fk_parties", 'Party', 'required');
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

        $pvhcl_id = $input['pvhcl_id'];
        $pty_id = $input['pvhcl_fk_parties'];

        // The vehicle details before edit
        $prev_details = $this->party_vehicles->getById($pvhcl_id);

        // Party Details
        $party_details = $this->parties->getById($pty_id);

        $input['pvhcl_name'] = ucwords(strtolower($input['pvhcl_name']));
        $input['pvhcl_no'] = strtoupper($input['pvhcl_no']);

        // Saving data to Tbl:owners
        $this->party_vehicles->save($input, $pvhcl_id);

        // The vehicle details after edit
        $cur_details = $this->party_vehicles->getById($pvhcl_id);

        // Checking is anything edited.
        $edited = $this->isEdited($prev_details, $cur_details);

        if ($edited)
        {
            // Worklog should be displayed in all workcentres where party has been registered.
            $workcentres = $this->destination_workcentres->getPartyWorkcentres($pty_id);

            // Message related to the worklog.
            $msg = 'The details of vehicle';
            $msg .= ' <span class="wlg_name">' . $prev_details['pvhcl_no'] . '</span> of party';
            $msg .= ' <span class="wlg_name">' . $party_details['pty_name'] . '</span> has been changed.';

            // Inserting worklogs of Tbl: parties.
            $this->send_wlog($this->table, $pvhcl_id, $msg, $this->edit, $this->edit, $workcentres, $prev_details);

            echo 1;
        }
        else
            echo '<div class="failureHead"><img src="images/failure.gif" width="15" height="15" /> Error!!!<div class="pop_failure">There is nothing changed!</div></div>';
    }

    function load_vehicles()
    {
        $pty_id = $_GET['pty_id'];
        $vhcles = $this->party_vehicles->getVehicleByParty($pty_id, 1);
        $data = $this->party_vehicles->make_options($vhcles, 'pvhcl_id', 'pvhcl_no');
        
        if(!$data)
        {
            $json[] = array('value' => '', 'text' => 'No Active Vehicles');
        }
        else
        {
            $json[] = array('value' => '', 'text' => 'Select');
            foreach ($data as $key => $val)
                $json[] = array('value' => $key, 'text' => $val);
        }
        
        echo json_encode($json);
    }
}

?>