<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require 'my_controller.php';

class Destination_workcentres extends My_controller
{

   function __construct()
   {
      parent::__construct();

      $this->load->model('parties_model', 'parties');
      $this->load->model('party_license_details_model', 'party_license_details');
      $this->load->model('party_destinations_model', 'party_destinations');
      $this->load->model('destination_workcentres_model', 'destination_workcentres');

      // Determining is the logged in user allowed to go forward with the current action.
      $this->isAllowed();

      $this->table = 'destination_workcentres';
      $this->p_key = 'dwc_id';
   }

   function get_wlog()//Called by ajax
   {
      $model = $this->destination_workcentres;
      $wlogs = $this->init_wlog($this->p_key, $model);

      if ($wlogs[0])
      {
         $latest_class = 'wlog_latest'; // Latest details about the worklog. Ie: the data from ref_tables, not from Tbl:backups.
         $wlog_fields = getWlogFields($this->table, 'keys');
         $status = $model->get_status();
         $mode = $model->get_account_type(2);

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

               if ($fld == 'dwc_fk_workcentres')
                  $val = $this->workcentres->getNameById($row[$fld]);
               else if ($fld == 'dwc_fk_party_destinations')
                  $val = $this->party_destinations->getNameById($row[$fld]);
               else if ($fld == 'dwc_date')
                  $val = formatDate($row[$fld], FALSE, 1);
               else if ($fld == 'dwc_ob_mode')
                  $val = $row[$fld] ? $mode[$row[$fld]] : '';
               else if ($fld == 'dwc_status')
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

   function load_destinations()
   {
      $pty_id = $_GET['pty_id'];

      // Workcentres where user is available.
      $user_workcentres = $this->workcentres->get_workcentres_options($this->user_id, '', 1);
      $user_workcentres = $this->workcentres->getIdsFromOption($user_workcentres);

      $destinations = $this->party_destinations->getDestinationByParty_2($pty_id, false, 1, '', '', $user_workcentres);
      $data = $this->party_destinations->make_options($destinations, 'pdst_id', 'pdst_name');
      if (!$data)
      {
         $json[] = array('value' => '', 'text' => 'No Active Destinations');
      }
      else
      {
         $json[] = array('value' => '', 'text' => 'Select');
         foreach ($data as $key => $val)
            $json[] = array('value' => $key, 'text' => $val);
      }
      echo json_encode($json);
   }

   function load_availability_details()
   {
      $pdst_id = $this->input->post('pdst_id');

      if (!$pdst_id)
         return;

      $availability = $this->destination_workcentres->get_data('', array('dwc_fk_party_destinations' => $pdst_id));
      /* print_r($availability);
        Array ( [0] => Array ( [dwc_id] => 1 [dwc_fk_workcentres] => 4 [dwc_fk_party_destinations] => 1 [dwc_date] => 2014-12-18 00:00:00 [dwc_ob] => 1000.00 [dwc_ob_mode] => 2 [dwc_credit_lmt] => 0.00 [dwc_debt_lmt] => 0.00 [] => 2 )
        [1] => Array ( [dwc_id] => 2 [dwc_fk_workcentres] => 1 [dwc_fk_party_destinations] => 1 [dwc_date] => 2014-12-18 00:00:00 [dwc_ob] => 0.00 [dwc_ob_mode] => 0 [dwc_credit_lmt] => 0.00 [dwc_debt_lmt] => 0.00 [dwc_status] => 1 ) ) */


      $firms = $this->firms->get_firms_options($this->user_id, 1);
      $workcentres = $this->workcentres->get_workcentres($this->user_id, '', 1);
      if ($firms)
      {
         // echo '';
         echo '<table class="unt_tbl" cellspacing="0" style="width:100%">';
         echo '<thead>';
         echo '<tr>';
         echo '<th>Firm</th>';
         echo '<th>Workcentre</th>';
         echo '<th>O.B</th>';
         echo '<th>Cr.Limit</th>';
         echo '<th>Dr.Limit</th>';
         echo '</tr>';
         echo '</thead>';
         echo '<tbody>';


         foreach ($firms as $firm_id => $firm_name)
         {
            foreach ($workcentres as $wc)
            {
               if ($wc['wcntr_fk_firms'] == $firm_id)
               {
                  $wc_id = $wc['wcntr_id'];
                  $dwc_ob = $dwc_ob_mode = $dwc_credit_lmt = $dwc_debt_lmt = $checked = '';
                  $dwc_status = '';
                  foreach ($availability as $av)
                  {
                     if ($av['dwc_fk_workcentres'] == $wc_id)
                     {
                        $checked = ' checked=""  disabled=""';
                        extract($av);

                        $dwc_status = $av['dwc_status'];
                     }
                  }

                  if ($dwc_status != INACTIVE)
                  {
                     echo '<tr>';
                     echo '<td style="padding:5px">' . $firm_name . '</td>';


                     echo '<td style="padding:5px">';
                     echo '<input type="checkbox" class="wcntr_id" ' . $checked . ' name="dwc_fk_workcentres[' . $wc_id . ']" /> ';
                     echo $wc['wcntr_name'];

                     echo '</td>';


                     echo '<td>';
                     echo '<input type="text" name="dwc_ob[' . $wc_id . ']" class="intOnly dwc_ob" style="width:70px" value="' . $dwc_ob . '" />';

                     $checked = ($dwc_ob_mode == 1) ? ' checked="" ' : '';
                     echo '<input type="radio" name="dwc_ob_mode[' . $wc_id . ']" ' . $checked . ' class="dwc_ob_mode" value="1"  />';
                     echo '<span class="multy_options">Cr.</span>';

                     $checked = ($dwc_ob_mode == 2) ? ' checked="" ' : '';
                     echo '<input type="radio" name="dwc_ob_mode[' . $wc_id . ']" ' . $checked . ' class="dwc_ob_mode" value="2"  />';
                     echo '<span class="multy_options">Dr.</span>';
                     echo '</td>';

                     echo '<td>';
                     echo '<input type="text" name="dwc_credit_lmt[' . $wc_id . ']" value="' . $dwc_credit_lmt . '"  class="numberOnly dwc_credit_lmt" style="width:70px"/>';
                     echo '</td>';

                     echo '<td>';
                     echo '<input type="text" name="dwc_debt_lmt[' . $wc_id . ']" value="' . $dwc_debt_lmt . '" class="numberOnly dwc_debt_lmt" style="width:70px"/>';
                     echo '</td>';
                     echo '</tr>';
                  }
               }
            }
         }


         echo '</tbody>';
         echo '</table>';
      }
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

      // Recieving input 
      $input = $this->input->post();

      //	Validating 
      $this->form_validation->set_error_delimiters('<div class="pop_failure">', '</div>');
      $this->form_validation->set_rules("dwc_fk_party_destinations", 'Party', 'required');

      foreach ($input['dwc_ob'] as $wcntr_id => $val)
      {
         if ($val != 'undefined')
         {
            $this->form_validation->set_rules("dwc_ob[$wcntr_id]", 'O.B', 'numeric|callback_validateAvailability');
            if (intval($val))
               $this->form_validation->set_rules("dwc_ob_mode[$wcntr_id]", 'O.B Mode', 'callback_ifNotSet');
            $this->form_validation->set_rules("dwc_credit_lmt[$wcntr_id]", 'Cr Limit', 'numeric|callback_validateAvailability');
            $this->form_validation->set_rules("dwc_debt_lmt[$wcntr_id]", 'Dr Limit', 'numeric|callback_validateAvailability');
         }
      }

      if (!$this->form_validation->run())
      {
         $message = validation_errors();
         if ($message)
         {
            echo $this->errorTitle . $message;
            return;
         }
      }

      $dest_details = $this->party_destinations->getById($input['dwc_fk_party_destinations']);
      $pty_id = $this->party_destinations->getPartyFromDestination($dest_details['pdst_id']);
      $party_details = $this->parties->getById($pty_id);
      $pdst_name = $dest_details['pdst_name'];
      $pty_name = $party_details['pty_name'];

      foreach ($input['dwc_ob'] as $wcntr_id => $val)
      {
         $tbl_data = array();
         if ($val != 'undefined')
         {
            $tbl_data['dwc_fk_party_destinations'] = $input['dwc_fk_party_destinations'];
            $tbl_data['dwc_fk_workcentres'] = $wcntr_id;
            $tbl_data['dwc_date'] = getSqlDate();
            $tbl_data['dwc_ob'] = $input['dwc_ob'][$wcntr_id];

            if (isset($input['dwc_ob_mode'][$wcntr_id]) && intval($tbl_data['dwc_ob']))
               $tbl_data['dwc_ob_mode'] = $input['dwc_ob_mode'][$wcntr_id];
            else
               $tbl_data['dwc_ob_mode'] = '';

            $tbl_data['dwc_credit_lmt'] = $input['dwc_credit_lmt'][$wcntr_id];
            $tbl_data['dwc_debt_lmt'] = $input['dwc_debt_lmt'][$wcntr_id];
            $tbl_data['dwc_status'] = 1;

            // Checking is the availability details already added.
            $where['dwc_fk_party_destinations'] = $tbl_data['dwc_fk_party_destinations'];
            $where['dwc_fk_workcentres'] = $tbl_data['dwc_fk_workcentres'];

            // If already added, editing
            if ($dwc_id = $this->destination_workcentres->getId($where))
            {
               $prev_details = $this->destination_workcentres->getById($dwc_id);
               $this->destination_workcentres->save($tbl_data, $dwc_id);
               $current_details = $this->destination_workcentres->getById($dwc_id);

               if ($this->isEdited($prev_details, $current_details))
               {
                  $wlog_warning = $this->check_for_warnings($prev_details, $current_details);

                  // Message related to the worklog.
                  $msg = 'The details of destination';
                  $msg .= ' <span class="wlg_name">' . $pdst_name . '</span> of party';
                  $msg .= ' <span class="wlg_name">' . $pty_name . '</span> has been changed.';

                  // Inserting worklogs of Tbl: parties.
                  $this->send_wlog($this->table, $dwc_id, $msg, $this->edit, $this->edit, array($wcntr_id), $prev_details, '', $wlog_warning);
               }
            }

            // If not added yet, Inserting data to Tbl:destination_workcentres
            else
            {
               $dwc_id = $this->destination_workcentres->insert($tbl_data);

               // Message related to the worklog.
               $msg = 'The destination';
               $msg .= ' <span class="wlg_name">' . $pdst_name . '</span> of party';
               $msg .= ' <span class="wlg_name">' . $pty_name . '</span> has been registered in the workcentre.';

               // Inserting worklogs of Tbl: parties.
               $this->send_wlog($this->table, $dwc_id, $msg, $this->add, $this->add, array($wcntr_id));
            }
         }
      }


      if ($wcntr_id)
         echo 1;
      else
         echo '<div class="failureHead"><img src="images/failure.gif" width="15" height="15" /> Error!!!<div class="pop_failure">Data couldn\'t insert !</div></div>';
   }

   function check_for_warnings($prev_details, $current_details)
   {
      $wlog_warning = NORMAL; // There are no warnings for ADMIN.

      if (intval($prev_details['dwc_ob']) != intval($current_details['dwc_ob']))
         $wlog_warning = WARNING; // There is a warning for ADMIN.

      return $wlog_warning;
   }

   // Callback
   function validateAvailability($val)
   {
      if ($val == 'undefined')
      {
         $this->form_validation->set_message('validateAvailability', 'The %s is required.');
         return FALSE;
      }
      return TRUE;
   }

   // Callback
   function ifNotSet($val)
   {
      if (!$val || ($val == 'undefined'))
      {
         $this->form_validation->set_message('ifNotSet', 'The %s is required.');
         return FALSE;
      }

      return TRUE;
   }

   function toggleStatus()
   {
      // Checking is the current task is enabled for the user
      $this->isAllowedTask('parties/edit');
      $dwc_id = $this->input->post('dwc_id');
      $dwc_details = $this->destination_workcentres->getById($dwc_id);
      $dest_details = $this->party_destinations->getById($dwc_details['dwc_fk_party_destinations']);
      $pdst_id = $dwc_details['dwc_fk_party_destinations'];
      $pty_id = $this->party_destinations->getPartyFromDestination($dest_details['pdst_id']);
      $party_details = $this->parties->getById($pty_id);
      $pdst_name = $dest_details['pdst_name'];
      $pty_name = $party_details['pty_name'];
      $wnctr_name = $this->workcentres->getNameById($dwc_details['dwc_fk_workcentres']);

      // Getting all workcentres where the party has been registered.
      $pty_wcntrs = $this->destination_workcentres->getPartyWorkcentres($pty_id);

      // Getting all workcentres where the destination has been registered.
      $pdst_wcntrs = $this->destination_workcentres->getDestinationWorkcentres($pdst_id);

      // Getting all workcentres where the destination under the license has been registered.
      $pld_id = $dest_details['pdst_fk_party_license_details'];
      $pld_wcntrs = $this->party_license_details->getLicenseWorkcentres($pld_id);



      $prev_status = $this->destination_workcentres->getTableStatus($dwc_id);


      // Toggling status.
      $this->destination_workcentres->toggleTableStatus($dwc_id);

      $curnt_status = $this->destination_workcentres->getTableStatus($dwc_id);

      // If Status changed; 
      if ($prev_status != $curnt_status)
      {
         $status = ($curnt_status == ACTIVE) ? "activated" : "deactivated";

         // Worklog should be displayed in the related workcentre.
         $wcntrs = array($dwc_details['dwc_fk_workcentres']);

         // Message related to the worklog.
         $msg = 'The destination <span class="wlg_name">' . $pdst_name . '</span>';
         $msg .= ' of party <span class="wlg_name">' . $pty_name . '</span>';
         $msg .= ' has been ' . $status . ' in the workcentre.';

         // Inserting to worklog.
         $this->send_wlog($this->table, $dwc_id, $msg, $this->edit, $this->edit, $wcntrs, $dwc_details);

         //$this->load->helper('party');
         # If the destination is active in any of the workcentre, it must be activated in Tbl: party_destinations.
         # If the destination is inactive in all workcentres, it must be deactivated in Tbl: party_destinations.

         $is_active_in_active_workcentre = $this->destination_workcentres->is_active_in_any_workcentre($pdst_id);
         $pdst_status = $this->party_destinations->getTableStatus($pdst_id);

         if ($is_active_in_active_workcentre)
         {
            // If destination is inactive
            if ($pdst_status == INACTIVE)
            {
               //  Activating destination.
               $this->activate_destinations($pdst_id, $wnctr_name, $pty_wcntrs, $pdst_wcntrs, $pld_wcntrs);
            }
         }
         else
         {
            // Deactivating destinations in the inactive workcentres also.
            // Otherwise when activating the workcentre, it may cause for logical errors.
            $this->destination_workcentres->deactivate_in_all_workcentres($pdst_id);

            // If destination is active
            if ($pdst_status == ACTIVE)
            {
               // Deactivating destination.
               $this->deactivate_destinations($pdst_id, $wnctr_name, $pty_wcntrs, $pdst_wcntrs, $pld_wcntrs);
            }
         }

         echo "The destination " . $pdst_name . " has been " . $status . " in the workcentre.";
      }
      else
         echo "Couldn't change status.";
   }

   function activate_destinations($pdst_id, $wnctr_name, $pty_wcntrs, $pdst_wcntrs, $pld_wcntrs)
   {
      $pdst_details = $this->party_destinations->getById($pdst_id);
      $pdst_name = $pdst_details['pdst_name'];
      $pty_id = $this->party_destinations->getPartyFromDestination($pdst_id);
      $pty_name = $this->parties->getNameById($pty_id);


      // Activate iff it is inactive
      if ($this->party_destinations->is_inactive($pdst_id))
      {
         $this->party_destinations->activate($pdst_id);

         // Message related to the worklog.
         $msg = 'The destination <span class="wlg_name">' . $pdst_name . '</span>';
         $msg .= ' of party <span class="wlg_name">' . $pty_name . '</span>';
         $msg .= ' has been activated when it was activated in the workcentre <span class="wlg_name">' . $wnctr_name . '</span>.';

         // Inserting to worklog.
         $this->send_wlog('party_destinations', $pdst_id, $msg, $this->edit, $this->edit, $pdst_wcntrs, $pdst_details);
      }

      $party_status = $this->parties->getTableStatus($pty_id);

      $pld_id = $pdst_details['pdst_fk_party_license_details'];
      $license_status = $this->party_license_details->getTableStatus($pld_id);


      // If parent party related to the destination is not active, he must be activated.
      if ($party_status == INACTIVE)
         $this->actvateParty($pty_id, $pdst_name, $wnctr_name, $pty_wcntrs);

      // If license is inactive, it must be activated.
      if ($license_status == INACTIVE)
         $this->actvateLicense($pld_id, $pdst_name, $wnctr_name, $pld_wcntrs);
   }

   function actvateParty($pty_id, $pdst_name, $wnctr_name, $pty_wcntrs)
   {

      // Activate iff it is inactive
      if ($this->parties->is_inactive($pty_id))
      {
         $pty_details = $this->parties->getById($pty_id);
         $pty_name = $pty_details['pty_name'];
         $this->parties->activate($pty_id);

         // Message related to the worklog.
         $msg = 'The party <span class="wlg_name">' . $pty_name . '</span>';
         $msg .= ' has been activated ';
         $msg .= 'when activated his destination <span class="wlg_name">' . $pdst_name . '</span>';
         $msg .= ' in the workcentre <span class="wlg_name">' . $wnctr_name . '</span>.';

         // Inserting to worklog.
         $this->send_wlog('parties', $pty_id, $msg, $this->edit, $this->edit, $pty_wcntrs, $pty_details);
      }
   }

   function actvateLicense($pld_id, $pdst_name, $wnctr_name, $pld_wcntrs)
   {
      $license_details = $this->party_license_details->getById($pld_id);

      // If the destination don't have a license, return.
      if (!$license_details)
         return;

      // Activate iff it is inactive
      if ($this->party_license_details->is_inactive($pld_id))
      {
         $pld_name = $license_details['pld_firm_name'];
         $pty_id = $this->party_license_details->getPartyByLicense($pld_id);
         $pty_name = $this->parties->getNameById($pty_id);

         $this->party_license_details->activate($pld_id);

         // Message related to the worklog.
         $msg = 'The license details of <span class="wlg_name">' . $pld_name . '</span>';
         $msg .= ' of party <span class="wlg_name">' . $pty_name . '</span>';
         $msg .= ' has been activated';
         $msg .= ' when activated his destination <span class="wlg_name">' . $pdst_name . '</span>';
         $msg .= ' in the workcentre <span class="wlg_name">' . $wnctr_name . '</span>.';

         // Inserting to worklog.
         $this->send_wlog('party_license_details', $pld_id, $msg, $this->edit, $this->edit, $pld_wcntrs, $license_details);
      }
   }

   function deactivate_destinations($pdst_id, $wnctr_name, $pty_wcntrs, $pdst_wcntrs, $pld_wcntrs)
   {
      $pdst_details = $this->party_destinations->getById($pdst_id);
      $pdst_name = $pdst_details['pdst_name'];
      $pty_id = $this->party_destinations->getPartyFromDestination($pdst_id);
      $pty_name = $this->parties->getNameById($pty_id);

      // Dectivate iff it is active.
      if ($this->party_destinations->is_active($pdst_id))
      {
         $this->party_destinations->deactivate($pdst_id);

         // Message related to the worklog.
         $msg = 'The destination <span class="wlg_name">' . $pdst_name . '</span>';
         $msg .= ' of party <span class="wlg_name">' . $pty_name . '</span>';
         $msg .= ' has been deactivated when it was deactivated in the workcentre ';
         $msg .= '<span class="wlg_name">' . $wnctr_name . '</span>.';

         // Inserting to worklog.
         $this->send_wlog('party_destinations', $pdst_id, $msg, $this->edit, $this->edit, $pdst_wcntrs, $pdst_details);
      }

      $party_status = $this->parties->getTableStatus($pty_id);

      $pld_id = $pdst_details['pdst_fk_party_license_details'];
      $license_status = $this->party_license_details->getTableStatus($pld_id);

      // If parent party is active, it must be deactivated iff it has no active destinations.
      if ($party_status == ACTIVE)
         $this->deactvateParty($pty_id, $pdst_name, $wnctr_name, $pty_wcntrs);

      // If license is active, it must be deactivated iff there are no active destinations are using this license.
      if ($license_status == ACTIVE)
         $this->deactvateLicense($pld_id, $pdst_name, $wnctr_name, $pld_wcntrs);
   }

   function deactvateParty($pty_id, $pdst_name, $wnctr_name, $pty_wcntrs)
   {
      // If the party has no active destinations, he must be deactivated.
      // So checking is there any active destinations for party.
      $active_pdst = $this->party_destinations->getDestinationByParty($pty_id);

      // If there are no active destinations and iff the party is active itself, he must be deactivated.
      if (!$active_pdst && $this->parties->is_active($pty_id))
      {
         $pty_details = $this->parties->getById($pty_id);
         $pty_name = $pty_details['pty_name'];
         $this->parties->deactivate($pty_id);

         // Message related to the worklog.
         $msg = 'The party <span class="wlg_name">' . $pty_name . '</span>';
         $msg .= ' has been deactivated ';
         $msg .= ' when deactivated his destination <span class="wlg_name">' . $pdst_name . '</span>';
         $msg .= ' in the workcentre <span class="wlg_name">' . $wnctr_name . '</span>.';

         // Inserting to worklog.
         $this->send_wlog('parties', $pty_id, $msg, $this->edit, $this->edit, $pty_wcntrs, $pty_details);
      }
   }

   function deactvateLicense($pld_id, $pdst_name, $wnctr_name, $pld_wcntrs)
   {
      $license_details = $this->party_license_details->getById($pld_id);

      // If the destination don't have a license, return.
      if (!$license_details)
         return;

      // License will be deactivated iff there are no active destinations are using this license.
      // So checking is any active destination is using the license.
      $using = $this->party_destinations->destinationsUnderLicense($pld_id);

      // If the license is not use by any active destinations and iff it is active itself.
      if (!$using && $this->party_license_details->is_active($pld_id))
      {
         $pld_name = $license_details['pld_firm_name'];
         $pty_id = $this->party_license_details->getPartyByLicense($pld_id);
         $pty_name = $this->parties->getNameById($pty_id);

         $this->party_license_details->deactivate($pld_id);

         // Message related to the worklog.
         $msg = 'The license details of <span class="wlg_name">' . $pld_name . '</span>';
         $msg .= ' of party <span class="wlg_name">' . $pty_name . '</span>';
         $msg .= ' has been deactivated';
         $msg .= ' when deactivated his destination <span class="wlg_name">' . $pdst_name . '</span>';
         $msg .= ' in the workcentre <span class="wlg_name">' . $wnctr_name . '</span>.';

         // Inserting to worklog.
         $this->send_wlog('party_license_details', $pld_id, $msg, $this->edit, $this->edit, $pld_wcntrs, $license_details);
      }
   }

}

?>