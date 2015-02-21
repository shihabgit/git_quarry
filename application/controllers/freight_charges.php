<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require 'my_controller.php';

class Freight_charges extends My_controller
{

   function __construct()
   {
      parent::__construct();

      $this->load->model('vehicles_model', 'vehicles');
      $this->load->model('vehicle_workcentres_model', 'vehicle_workcentres');
      $this->load->model('freight_charges_model', 'freight_charges');
      $this->load->model('parties_model', 'parties');
      $this->load->model('party_destinations_model', 'party_destinations');

      // Determining is the logged in user allowed to go forward with the current action.
      $this->isAllowed();

      $this->table = 'freight_charges';
      $this->p_key = 'fc_id';
   }

   function get_wlog()//Called by ajax
   {
      $model = $this->freight_charges;
      $wlogs = $this->init_wlog($this->p_key, $model);

      if ($wlogs)   // Format: 1 for deleted worklogs.
      {
         $latest_class = 'wlog_latest'; // Latest details about the worklog. Ie: the data from ref_tables, not from Tbl:backups.
         $wlog_fields = getWlogFields($this->table, 'keys');
         $add_rent = array(1 => "Rent will be added to the bill amount", 2 => "Rent will not be added to the bill amount");
         $add_bata = array(1 => "Bata will be added to the bill amount", 2 => "Bata will not be added to the bill amount");
         $add_loading = array(1 => "Loading charge will be added to the bill amount", 2 => "Loading charge will not be added to the bill amount");

         echo "<table>";

         // Creating headers.
         $headers = getWlogFields($this->table, 'all');
         echo '<tr>';
         foreach ($headers as $head)
            echo '<th>' . $head . '</th>';
         echo '</tr>';

         foreach ($wlogs as $key => $row)
         {
            if ($row)   // Format: 2 for deleted worklogs.
            {
               $latest_class = ''; // Format: 3 for deleted worklogs.
               
               echo '<tr class="' . $latest_class . '">';
               $latest_class = '';
               foreach ($wlog_fields as $fld)
               {
                  $val = $row[$fld];
                  $edited = false;
                  if (isset($wlogs[$key + 1]) && ($wlogs[$key + 1][$fld] !== $val))
                     $edited = true;

                  if ($fld == 'fc_fk_workcentres')
                     $val = $this->workcentres->getNameById($row[$fld]);
                  else if ($fld == 'fc_fk_party_destinations')
                  {
                     $pdst_id = $row[$fld];
                     $pdst_name = $this->party_destinations->getNameById($pdst_id);
                     $pty_id = $this->party_destinations->getFieldById($pdst_id, 'pdst_fk_parties');
                     $pty_name = $this->parties->getNameById($pty_id);
                     $val = "$pty_name - $pdst_name";
                  }
                  else if ($fld == 'fc_fk_vehicles')
                     $val = $this->vehicles->getNameById($row[$fld]);
                  else if ($fld == 'fc_add_rent')
                     $val = $add_rent[$row[$fld]];
                  else if ($fld == 'fc_add_bata')
                     $val = $add_bata[$row[$fld]];
                  else if ($fld == 'fc_add_loading')
                     $val = $add_loading[$row[$fld]];

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
      $task = taskEnabled("vehicles/add");
      if ($task != 1)
      {
         echo $task;
         return;
      }


      //	Validating 
      $v_config = validationConfigs($this->table);
//      $this->form_validation->set_rules("pty_id", 'Party', 'required');
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
      $vhcl_id = $input['fc_fk_vehicles'];
      $vhcl_no = $this->vehicles->getNameById($vhcl_id);
      $wcntr_name = $this->workcentres->getNameById($input['fc_fk_workcentres']);
      $pdst_id = $input['fc_fk_party_destinations'];
      $pdst_name = $this->party_destinations->getNameById($pdst_id);
      $pty_id = $this->party_destinations->getFieldById($pdst_id, 'pdst_fk_parties');
      $pty_name = $this->parties->getNameById($pty_id);

      // Inserting data to Tbl:freight_charges
      $fc_id = $this->freight_charges->insert($input);

      if ($fc_id)
      {
         // Worklog should be displayed in all workcentres where the vehicle has been registered.
         $workcentres = $this->vehicle_workcentres->getVehicleWorkcentres($vhcl_id);

         // Message related to the worklog.
         $msg = "The freight charge for the vehicle <span class='wlg_name'>$vhcl_no</span>";
         $msg .= " from <span class='wlg_name'>Workcentre: $wcntr_name</span>";
         $msg .= " to <span class='wlg_name'>party: $pty_name - $pdst_name</span> has been added.";


         // Inserting worklogs of Tbl: freight_charges.
         // A warning message to Admin must be sent. Because the recently inserted data contains 'amount' related fields.
         $this->send_wlog($this->table, $fc_id, $msg, $this->add, $this->add, $workcentres,'','',WARNING);

         echo 1;
      }
      else
         echo $this->formatePopupError('Data couldn\'t insert !');
   }

   function beforeEdit()
   {
      // Checking is the current task is enabled for the user
      $this->isAllowedTask("vehicles/edit");

      $id = $_GET['fc_id'];
      $details = $this->freight_charges->getById($id);
      echo json_encode($details);
      return;
   }

   function edit()
   {
      // Checking is the current task is enabled for the user
      $task = taskEnabled("vehicles/edit");
      if ($task != 1)
      {
         echo $task;
         return;
      }

      //	Validating 
      $v_config = validationConfigs($this->table, 'edit');
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
      $fc_id = $input['fc_id'];

      // Freight charge details before edit
      $prev_details = $this->freight_charges->getById($fc_id);


      $vhcl_id = $prev_details['fc_fk_vehicles'];
      $vhcl_no = $this->vehicles->getNameById($vhcl_id);
      $wcntr_name = $this->workcentres->getNameById($prev_details['fc_fk_workcentres']);
      $pdst_id = $prev_details['fc_fk_party_destinations'];
      $pdst_name = $this->party_destinations->getNameById($pdst_id);
      $pty_id = $this->party_destinations->getFieldById($pdst_id, 'pdst_fk_parties');
      $pty_name = $this->parties->getNameById($pty_id);



      // Saving data to Tbl:freight_charges
      $this->freight_charges->save($input, $fc_id);

      // Freight charge details after edit
      $cur_details = $this->freight_charges->getById($fc_id);

      // Checking is anything edited.
      $edited = $this->isEdited($prev_details, $cur_details);

      // If edited, creating worklog.
      if ($edited)
      {
         // Worklog should be displayed in all workcentres where vehicle has been registered.
         $workcentres = $this->vehicle_workcentres->getVehicleWorkcentres($vhcl_id);

         // Checking is there anything to warn Admin.
         $warning_fields = array('fc_rent', 'fc_add_rent', 'fc_bata', 'fc_add_bata', 'fc_loading', 'fc_add_loading');
         $warning = $this->check_warnings($prev_details, $cur_details, $warning_fields);

         // Message related to the worklog.
         $msg = "The freight charge for the vehicle <span class='wlg_name'>$vhcl_no</span>";
         $msg .= " from <span class='wlg_name'>Workcentre: $wcntr_name</span>";
         $msg .= " to <span class='wlg_name'>party: $pty_name - $pdst_name</span> has been edited.";

         // Inserting worklogs of Tbl: freight_charges.
         $this->send_wlog($this->table, $fc_id, $msg, $this->edit, $this->edit, $workcentres, $prev_details, '', $warning);
         echo 1;
      }
      else
         echo $this->formatePopupError('There is nothing changed!');
   }

   function delete()
   {
      $fc_id = $this->input->post('fc_id');
      $prev_details = $this->freight_charges->getById($fc_id);


      $vhcl_id = $prev_details['fc_fk_vehicles'];
      $vhcl_no = $this->vehicles->getNameById($vhcl_id);
      $wcntr_name = $this->workcentres->getNameById($prev_details['fc_fk_workcentres']);
      $pdst_id = $prev_details['fc_fk_party_destinations'];
      $pdst_name = $this->party_destinations->getNameById($pdst_id);
      $pty_id = $this->party_destinations->getFieldById($pdst_id, 'pdst_fk_parties');
      $pty_name = $this->parties->getNameById($pty_id);


      $this->freight_charges->remove($fc_id);

      // If deleted;
      if (!$this->freight_charges->getById($fc_id))
      {
         // Worklog should be displayed in all workcentres where vehicle has been registered.
         $workcentres = $this->vehicle_workcentres->getVehicleWorkcentres($vhcl_id);

         // Message related to the worklog.
         $msg = 'The freight charge for the vehicle <span class="wlg_name">' . $vhcl_no . '</span>';
         $msg .= ' from <span class="wlg_name">Workcentre: ' . $wcntr_name . '</span>';
         $msg .= " to <span class='wlg_name'>Party: $pty_name - $pdst_name</span> has been deleted.";

         // Inserting worklogs related to Tbl: freight_charges.
         $this->send_wlog($this->table, $fc_id, $msg, $this->delete, $this->delete, $workcentres, $prev_details, '', WARNING);
         echo "Freight charge deleted successfully.";
      }
   }

   function getFreeParties()
   {
      $vhcl_id = $_GET['vhcl_id'];
      $wcntr_id = $_GET['wcntr_id'];
      $free_parties = array();

      // Workentres where both the user and vehicles are available.
      // $vhcl_wc = $this->vehicle_workcentres->getVehicleWorkcentresByUser($this->user_id, $vhcl_id);
      // Getting party destinations under $vhcl_wc
      $free_parties = $this->freight_charges->getFreeParties($vhcl_id, $wcntr_id);
      $free_parties = $this->parties->make_options($free_parties, 'pty_id', 'pty_name');

      $this->json_options($free_parties, "-- No New Parties --");
   }

   function getFreeDestinations()
   {
      $vhcl_id = $_GET['vhcl_id'];
      $wcntr_id = $_GET['wcntr_id'];
      $pty_id = $_GET['pty_id'];
      $free_pdst = array();

      // Workentres where both the user and vehicles are available.
//      $vhcl_wc = $this->vehicle_workcentres->getVehicleWorkcentresByUser($this->user_id, $vhcl_id);
      // Getting party destinations under $vhcl_wc
      $free_pdst = $this->freight_charges->getFreeParties($vhcl_id, $wcntr_id, $pty_id, FALSE);

      $free_pdst = $this->party_destinations->make_options($free_pdst, 'pdst_id', 'pdst_name');

      $this->json_options($free_pdst, "-- No New Parties --");
   }
   
   function get_freights()
   {
      if(!$_GET['vhcl_id'] || !$_GET['wcntr_id'] || !$_GET['pdst_id'])
         return;
      
      $where['fc_fk_workcentres'] = $_GET['wcntr_id'];
      $where['fc_fk_party_destinations'] = $_GET['pdst_id'];
      $where['fc_fk_vehicles'] = $_GET['vhcl_id'];
      
      $json = $this->freight_charges->get_row($where);
      
      echo json_encode($json);
   }
   

}

?>