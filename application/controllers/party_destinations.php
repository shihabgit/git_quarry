<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require 'my_controller.php';

class Party_destinations extends My_controller
{

   function __construct()
   {
      parent::__construct();

      $this->load->model('parties_model', 'parties');
      $this->load->model('party_destinations_model', 'party_destinations');
      $this->load->model('party_license_details_model', 'party_license_details');
      $this->load->model('destination_workcentres_model', 'destination_workcentres');

      // Determining is the logged in user allowed to go forward with the current action.
      $this->isAllowed();

      $this->table = 'party_destinations';
      $this->p_key = 'pdst_id';
   }

   function get_wlog() //Called by ajax
   {
      $model = $this->party_destinations;
      $wlogs = $this->init_wlog($this->p_key, $model);

      if ($wlogs[0])
      {
         $latest_class = 'wlog_latest'; // Latest details about the worklog. Ie: the data from ref_tables, not from Tbl:backups.
         $wlog_fields = getWlogFields($this->table, 'keys');
         $status = $model->get_status();
         $license_details = $this->party_license_details->get_option();
         $parties = $this->parties->get_option();
         $category = $this->party_destinations->getCat();
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

               if ($fld == 'pdst_date')
                  $val = formatDate($row[$fld], FALSE, 1);
               else if ($fld == 'pdst_fk_party_license_details' && isset($license_details[$row[$fld]]))
                  $val = $license_details[$row[$fld]];
               else if ($fld == 'pdst_fk_parties')
                  $val = $parties[$row[$fld]];
               else if ($fld == 'pdst_category')
                  $val = $category[$row[$fld]];
               else if ($fld == 'pdst_status')
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

   function getDestinationsByParty()
   {
      $pty_id = $_GET['parent_id'];

      if (!$pty_id)
         return;

      $wcntr_ids = '';

      if (isset($_GET['wcntr_id']))
      {
         $wcntr_ids = $_GET['wcntr_id'];
      }

      if (!$wcntr_ids)
      {
         $user_wcntrs = $this->workcentres->get_workcentres_options($this->user_id, $this->firm_id, 1);
         $wcntr_ids = $this->workcentres->getIdsFromOption($user_wcntrs);
      }

      // Getting all active destinations of party under users workcentres.
      $destinations = $this->party_destinations->getDestinationsUnderWorkcentre($wcntr_ids, $pty_id);
      $data = $this->party_destinations->make_options($destinations, 'pdst_id', 'pdst_name');

      $this->json_options($data, "-- No New Destinations --");
   }

   function getDestinationsByParty2()
   {
      $pty_id = $_GET['parent_id'];
      $tax_type = $_GET['tax_type'];      

      if (!$pty_id || !$tax_type)
         return;

      $wcntr_ids = '';

      if (isset($_GET['wcntr_id']))
      {
         $wcntr_ids = $_GET['wcntr_id'];
      }

      if (!$wcntr_ids)
      {
         $user_wcntrs = $this->workcentres->get_workcentres_options($this->user_id, $this->firm_id, 1);
         $wcntr_ids = $this->workcentres->getIdsFromOption($user_wcntrs);
      }

      
      // Getting all active destinations of party under users workcentres.
      // If Taxable, getting the party only those are having a legal registration.
      if ($tax_type == 1)
      {
         $destinations = $this->party_license_details->getRegisteredDestinationsUnderWorkcentre($wcntr_ids, $pty_id);
      }
      else
      {
         $destinations = $this->party_destinations->getDestinationsUnderWorkcentre($wcntr_ids, $pty_id);
      }
      
      $data = $this->party_destinations->make_options($destinations, 'pdst_id', 'pdst_name');

      $this->json_options($data, "-- No New Destinations --");
   }
   
   function getDestinationsByParties()
   {
      $pty_ids = $_GET['pty_ids'];
      $wcntr_ids = $_GET['wcntr_ids'];

      if (!$pty_ids || !$wcntr_ids)
         return;

      // Getting all active destinations of party under users workcentres.
      $destinations = $this->party_destinations->getDestinationsUnderWorkcentre($wcntr_ids, $pty_ids);
      $options = array();

      if (!$destinations)
      {
         $options['-- No Destinations Found --'][] = array('id' => '', 'name' => '--No Options--');
      }
      else
      {
         foreach ($pty_ids as $pty_id)
         {
            foreach ($destinations as $row)
            {
               if ($row['pty_id'] == $pty_id)
                  $options[$row['pty_name']][] = array("id" => $row['pdst_id'], "name" => $row['pdst_name']);
            }
         }
      }

      echo json_encode($options);
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
      $this->form_validation->set_rules("pdst_fk_parties", 'Party', 'required');
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
      $input = $this->input->post();

      $input['pdst_date'] = getSqlDate($input['pdst_date']);
      $input['pdst_status'] = 1;
      $input['pdst_name'] = ucwords(strtolower($input['pdst_name']));

      // Inserting vehicle
      $insert_id = $this->party_destinations->insert($input);

      $pty_id = $input['pdst_fk_parties'];
      $party_details = $this->parties->getById($pty_id);


      // Worklog should be displayed in all workcentres where party has been registered.
      $workcentres = $this->destination_workcentres->getPartyWorkcentres($pty_id);

      // Message related to the worklog.
      $msg = 'A new destination';
      $msg .= ' <span class="wlg_name">' . $input['pdst_name'] . '</span> for party';
      $msg .= ' <span class="wlg_name">' . $party_details['pty_name'] . '</span> has been added.';

      // Inserting worklogs of Tbl: parties.
      $this->send_wlog($this->table, $insert_id, $msg, $this->add, $this->add, $workcentres);

      if ($insert_id)
      {
         // Registering the destination in the given workcentres.
         $this->register_party_destinations_in_the_workcentre($insert_id);

         echo 1;
      }
      else
         echo '<div class="failureHead"><img src="images/failure.gif" width="15" height="15" /> Error!!!<div class="pop_failure">Couldn\'t add vehicle !</div></div>';
   }

   function register_party_destinations_in_the_workcentre($pdst_id)
   {

      $pty_id = $this->party_destinations->getPartyFromDestination($pdst_id);
      $pdst_name = $this->party_destinations->getNameById($pdst_id);
      $pty_name = $this->parties->getNameById($pty_id);
      $wcntrs = $this->input->post('wcntrs');

      $input['dwc_fk_party_destinations'] = $pdst_id;
      $input['dwc_date'] = getSqlDate();
      $input['dwc_ob'] = '0.00';
      $input['dwc_ob_mode'] = '';
      $input['dwc_credit_lmt'] = '0.00';
      $input['dwc_debt_lmt'] = '0.00';
      $input['dwc_status'] = ACTIVE;

      foreach ($wcntrs as $wcntr_id)
      {
         $input['dwc_fk_workcentres'] = $wcntr_id;

         // Adding the destination to the workcentre.
         $dwc_id = $this->destination_workcentres->insert($input);


         // Message related to the worklog.
         $msg = 'The destination';
         $msg .= ' <span class="wlg_name">' . $pdst_name . '</span> of party';
         $msg .= ' <span class="wlg_name">' . $pty_name . '</span> has been registered in the workcentre on its creation time.';

         // Inserting worklogs of Tbl: parties.
         $this->send_wlog('destination_workcentres', $dwc_id, $msg, $this->add, $this->add, array($wcntr_id));
      }

      return true;
   }

   function toggleStatus()
   {
      // Checking is the current task is enabled for the user
      $this->isAllowedTask('parties/edit');
      $pdst_id = $this->input->post('pdst_id');
      $pdst_details = $this->party_destinations->getById($pdst_id);
      $pdst_name = $pdst_details['pdst_name'];
      $pty_id = $this->party_destinations->getPartyFromDestination($pdst_id);
      $pty_details = $this->parties->getById($pty_id);
      $prev_status = $this->party_destinations->getTableStatus($pdst_id);

      // Worklog should be displayed in all workcentres where the destination has been registered.
      $dest_wcntrs = $this->destination_workcentres->getDestinationWorkcentres($pdst_id);

      // Worklog should be displayed in all workcentres where the party has been registered.
      $partys_wcntrs = $this->destination_workcentres->getPartyWorkcentres($pty_id);

      // Toggling status.
      $this->party_destinations->toggleTableStatus($pdst_id);

      $curnt_status = $this->party_destinations->getTableStatus($pdst_id);

      // If Status changed; 
      if ($prev_status != $curnt_status)
      {
         $status = ($curnt_status == 1) ? "activated" : "deactivated";

         // Message related to the worklog.
         $msg = 'The destination <span class="wlg_name">' . $pdst_name . '</span>';
         $msg .= ' of party <span class="wlg_name">' . $pty_details['pty_name'] . '</span>';
         $msg .= ' has been ' . $status . '.';

         // Inserting to worklog.
         $this->send_wlog($this->table, $pdst_id, $msg, $this->edit, $this->edit, $dest_wcntrs, $pdst_details);

         $party_status = $this->parties->getTableStatus($pty_id);

         $pld_id = $pdst_details['pdst_fk_party_license_details'];
         $license_status = $this->party_license_details->getTableStatus($pld_id);

         // If destination is active
         if ($curnt_status == 1)
         {
            // If parent party is not active, it must be activated.
            if ($party_status == 2)
               $this->actvateParty($pty_id, $pty_details, $pdst_name, $partys_wcntrs);

            // If license is inactive, it must be activated.
            if ($license_status == 2)
               $this->actvateLicense($pld_id, $pdst_name, $dest_wcntrs);

            $this->actvateAvailability($pdst_id, $pty_details, $pdst_name);
         }

         // If destination is inactive
         else if ($curnt_status == 2)
         {
            // If parent party is active, it must be deactivated iff it has no active destinations.
            if ($party_status == 1)
               $this->deactvateParty($pty_id, $pty_details, $pdst_name, $partys_wcntrs);

            // If license is active, it must be deactivated iff there are no active destinations are using this license.
            if ($license_status == 1)
               $this->deactvateLicense($pld_id, $pdst_name, $dest_wcntrs);

            $this->deactivateAvailability($pdst_id, $pty_details, $pdst_name);
         }
         else
         {
            echo "Logical errors occured!";
            return;
         }

         echo "The destination " . $pdst_name . " has been " . $status;
      }
      else
         echo "Couldn't change status.";
   }

   function actvateLicense($pld_id, $pdst_name, $workcentres)
   {
      $license_details = $this->party_license_details->getById($pld_id);

      // Activate iff it is inactive
      if ($this->party_license_details->is_inactive($pld_id) && $license_details)
      {
         $this->party_license_details->activate($pld_id);

         // Message related to the worklog.
         $msg = 'The license details of <span class="wlg_name">' . $license_details['pld_firm_name'] . '</span>';
         $msg .= ' has been activated when activated the destination ';
         $msg .= '<span class="wlg_name">' . $pdst_name . '</span>';

         // Inserting to worklog.
         $this->send_wlog('party_license_details', $pld_id, $msg, $this->edit, $this->edit, $workcentres, $license_details);
      }
   }

   function deactvateLicense($pld_id, $pdst_name, $workcentres)
   {
      $license_details = $this->party_license_details->getById($pld_id);

      // If the destination don't have a license, return.
      if (!$license_details)
         return;

      // License will be deactivated iff there are no active destinations are using this license.
      // So checking is any active destination is using the license.
      $is_using = $this->party_destinations->destinationsUnderLicense($pld_id);

      // If not used by any active destinations and iff Active.
      if (!$is_using && $this->party_license_details->is_active($pld_id))
      {
         $this->party_license_details->deactivate($pld_id);

         // Message related to the worklog.
         $msg = 'The license of <span class="wlg_name">' . $license_details['pld_firm_name'] . '</span>';
         $msg .= ' has been deactivated when deactivated the destination ';
         $msg .= '<span class="wlg_name">' . $pdst_name . '</span>';

         // Inserting to worklog.
         $this->send_wlog('party_license_details', $pld_id, $msg, $this->edit, $this->edit, $workcentres, $license_details);
      }
   }

   function actvateParty($pty_id, $pty_details, $pdst_name, $workcentres)
   {

      // Activate iff it is inactive
      if ($this->parties->is_inactive($pty_id))
      {
         $this->parties->activate($pty_id);

         // Message related to the worklog.
         $msg = 'The party <span class="wlg_name">' . $pty_details['pty_name'] . '</span>';
         $msg .= ' has been activated when activated his destination ';
         $msg .= '<span class="wlg_name">' . $pdst_name . '</span>';

         // Inserting to worklog.
         $this->send_wlog('parties', $pty_id, $msg, $this->edit, $this->edit, $workcentres, $pty_details);
      }
   }

   function deactvateParty($pty_id, $pty_details, $pdst_name, $workcentres)
   {
      // If the party has no active destinations, he must be deactivated.
      // So checking is there any active destinations for party.
      $active_dests = $this->party_destinations->getDestinationByParty($pty_id);

      // Iff there are no active destinations and also it is active, the party must be deactivated.
      if (!$active_dests && $this->parties->is_active($pty_id))
      {
         $this->parties->deactivate($pty_id);

         // Message related to the worklog.
         $msg = 'The party <span class="wlg_name">' . $pty_details['pty_name'] . '</span>';
         $msg .= ' has been deactivated when deactivated his destination ';
         $msg .= '<span class="wlg_name">' . $pdst_name . '</span>';

         // Inserting to worklog.
         $this->send_wlog('parties', $pty_id, $msg, $this->edit, $this->edit, $workcentres, $pty_details);
      }
   }

   function actvateAvailability($pdst_id, $pty_details, $pdst_name)
   {
      $availability = $this->destination_workcentres->getByDestination