<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require 'my_controller.php';

class Party_vehicle_rents extends My_controller
{

    function __construct()
    {
        parent::__construct();

        $this->load->model('party_vehicle_rents_model', 'party_vehicle_rents');
        $this->load->model('parties_model', 'parties');
        $this->load->model('party_destinations_model', 'party_destinations');
        $this->load->model('party_vehicles_model', 'party_vehicles');
        $this->load->model('party_license_details_model', 'party_license_details');
        $this->load->model('destination_workcentres_model', 'destination_workcentres');

        // Determining is the logged in user allowed to go forward with the current action.
        $this->isAllowed();

        $this->table = 'party_vehicle_rents';
        $this->p_key = 'pvr_id';
    }

    function get_wlog()//Called by ajax
    {
        $model = $this->party_vehicle_rents;
        $wlogs = $this->init_wlog($this->p_key, $model);

        if ($wlogs)  // Format: 1 for deleted worklogs.
        {
            $latest_class = 'wlog_latest'; // Latest details about the worklog. Ie: the data from ref_tables, not from Tbl:backups.
            $wlog_fields = getWlogFields($this->table, 'keys');

            echo '<table>';

            // Creating headers.
            $headers = getWlogFields($this->table, 'all');
            echo '<tr>';
            foreach ($headers as $head)
                echo '<th>' . $head . '</th>';
            echo '</tr>';


            foreach ($wlogs as $key => $row)
            {
                if ($row) // Format: 2 for deleted worklogs.
                {
                    echo '<tr class="' . $latest_class . '">';
                    $latest_class = '';
                    foreach ($wlog_fields as $fld)
                    {
                        $val = $row[$fld];
                        $edited = false;
                        if (isset($wlogs[$key + 1]) && ($wlogs[$key + 1][$fld] !== $val))
                            $edited = true;

                        if ($fld == 'pvr_fk_workcentres')
                            $val = $this->workcentres->getNameById($row[$fld]);
                        else if ($fld == 'pvr_fk_party_destinations')
                            $val = $this->party_destinations->getNameById($row[$fld]);
                        else if ($fld == 'pvr_fk_party_vehicles')
                            $val = $this->party_vehicles->getNameById($row[$fld]);

                        if ($edited)
                            echo '<td><span class="wlog_changed">' . $val . '</span></td>';
                        else
                            echo '<td>' . $val . '</td>';
                    }
                    echo '</tr>';
                }
            }
            echo "</table>";
        }
        else
            echo "No Worklogs Found!!!";
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

        $pdst_id = $input['pvr_fk_party_destinations'];
        $pdst_name = $this->party_destinations->getNameById($pdst_id);
        $pty_id = $this->party_destinations->getPartyFromDestination($pdst_id);
        $pty_name = $this->parties->getNameById($pty_id);
        $pvhcl_no = $this->party_vehicles->getNameById($input['pvr_fk_party_vehicles']);
        $workcentres = array($input['pvr_fk_workcentres']);

        $pvr_id = $this->party_vehicle_rents->insert($input);

        // Message related to the worklog.
        $msg = 'Freight charge for vehicle';
        $msg .= ' <span class="wlg_name">' . $pvhcl_no . '</span> of party';
        $msg .= ' <span class="wlg_name">' . $pty_name . '</span>  from his destination';
        $msg .= ' <span class="wlg_name">' . $pdst_name . '</span> to the workcentre has been added.';


        // Inserting worklogs of Tbl: party_vehicle_rents.
        $this->send_wlog($this->table, $pvr_id, $msg, $this->add, $this->add, $workcentres);

        if ($pvr_id)
            echo 1;
        else
            echo '<div class="failureHead"><img src="images/failure.gif" width="15" height="15" /> Error!!!<div class="pop_failure">Couldn\'t add freight charges !</div></div>';
    }

    function edit()
    {
        // Checking is the current task is enabled for the user
        $task = taskEnabled("parties/add");
        if ($task != 1)
        {
            echo $task;
            return;
        }

        //	Validating 
        $this->form_validation->set_rules("pvr_rent", 'Freight Charge', 'required|numeric');
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
        $pvr_id = $input['pvr_id'];
        $prev_details = $this->party_vehicle_rents->getById($pvr_id);
        $pdst_id = $prev_details['pvr_fk_party_destinations'];
        $pdst_name = $this->party_destinations->getNameById($pdst_id);
        $pty_id = $this->party_destinations->getPartyFromDestination($pdst_id);
        $pty_name = $this->parties->getNameById($pty_id);
        $pvhcl_no = $this->party_vehicles->getNameById($prev_details['pvr_fk_party_vehicles']);
        $workcentres = array($prev_details['pvr_fk_workcentres']);

        $this->party_vehicle_rents->save($input, $pvr_id);

        $cur_details = $this->party_vehicle_rents->getById($pvr_id);

        // Checking is anything edited.
        $edited = $this->isEdited($prev_details, $cur_details);

        // If edited, creating worklog.
        if ($edited)
        {
            // Message related to the worklog.
            $msg = 'Freight charge for vehicle';
            $msg .= ' <span class="wlg_name">' . $pvhcl_no . '</span> of party';
            $msg .= ' <span class="wlg_name">' . $pty_name . '</span>  from his destination';
            $msg .= ' <span class="wlg_name">' . $pdst_name . '</span> to the workcentre has been edited.';

            // Inserting worklogs of Tbl: party_vehicle_rents.
            $this->send_wlog($this->table, $pvr_id, $msg, $this->edit, $this->edit, $workcentres, $prev_details, '', WARNING);

            if ($pvr_id)
                echo 1;
            else
                echo '<div class="failureHead"><img src="images/failure.gif" width="15" height="15" /> Error!!!<div class="pop_failure">Couldn\'t add freight charges !</div></div>';
        }
        else
            echo '<div class="failureHead"><img src="images/failure.gif" width="15" height="15" /> Error!!!<div class="pop_failure">There is nothing changed to edit !</div></div>';
    }

    function delete()
    {
        $pvr_id = $this->input->post('pvr_id');
        $prev_details = $this->party_vehicle_rents->getById($pvr_id);
        $pdst_id = $prev_details['pvr_fk_party_destinations'];
        $pdst_name = $this->party_destinations->getNameById($pdst_id);
        $pty_id = $this->party_destinations->getPartyFromDestination($pdst_id);
        $pty_name = $this->parties->getNameById($pty_id);
        $pvhcl_no = $this->party_vehicles->getNameById($prev_details['pvr_fk_party_vehicles']);
        $workcentres = array($prev_details['pvr_fk_workcentres']);

        $deleted = $this->party_vehicle_rents->remove($pvr_id);

        // If deleted, creating worklog.
        if ($deleted)
        {
            // Message related to the worklog.
            $msg = 'Freight charge for vehicle';
            $msg .= ' <span class="wlg_name">' . $pvhcl_no . '</span> of party';
            $msg .= ' <span class="wlg_name">' . $pty_name . '</span>  from his destination';
            $msg .= ' <span class="wlg_name">' . $pdst_name . '</span> to the workcentre has been deleted.';

            // Inserting worklogs of Tbl: party_vehicle_rents.
            $this->send_wlog($this->table, $pvr_id, $msg, $this->edit, $this->edit, $workcentres, $prev_details, '', WARNING);

            echo 1;
        }
        else
            echo 'Couldn\'t delete!';
    }

    function load_vehicles()
    {
        $pty_id = $_GET['pty_id'];
        $pdst_id = $_GET['pdst_id'];
        $wcntr_id = $_GET['wcntr_id'];

        $vhcles = $this->party_vehicle_rents->getFreeVehicles($pty_id, 1, $pdst_id, $wcntr_id);
        $data = $this->party_vehicle_rents->make_options($vhcles, 'pvhcl_id', 'pvhcl_no');
        if ($data)
        {
            $json[] = array('value' => '', 'text' => 'Select');
            foreach ($data as $key => $val)
                $json[] = array('value' => $key, 'text' => $val);
        }
        else
            $json[] = array('value' => '', 'text' => '--- No Vehicles ---');
        echo json_encode($json);
    }

   function get_freights()
   {
      if(!$_GET['vhcl_id'] || !$_GET['wcntr_id'] || !$_GET['pdst_id'])
         return;      
      
      $where['pvr_fk_workcentres'] = $_GET['wcntr_id'];
      $where['pvr_fk_party_destinations'] = $_GET['pdst_id'];
      $where['pvr_fk_party_vehicles'] = $_GET['vhcl_id'];
      
      $json = $this->party_vehicle_rents->get_row($where);
      
      echo json_encode($json);
   }
}

?>