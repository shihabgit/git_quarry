<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require 'my_controller.php';

class Parties extends My_controller
{

   function __construct()
   {
      parent::__construct();

      $this->load->model('parties_model', 'parties');
      $this->load->model('party_license_details_model', 'party_license_details');
      $this->load->model('party_destinations_model', 'party_destinations');
      $this->load->model('party_vehicles_model', 'party_vehicles');
      $this->load->model('destination_workcentres_model', 'destination_workcentres');
      $this->load->model('party_vehicle_rents_model', 'party_vehicle_rents');

      // Determining is the logged in user allowed to go forward with the current action.
      $this->isAllowed();

      $this->per_page = 10;
      $this->table = 'parties';
      $this->p_key = 'pty_id';
   }

   function get_wlog() //Called by ajax
   {
      $model = $this->parties;
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

               if ($fld == 'pty_status')
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
      $this->isAllowedTask($this->clsfunc);

      // Setting validation rules for Tbl:parties.
      $v_config = validationConfigs($this->table, 'add', $this->table);
      $this->form_validation->set_rules($v_config);
      $this->form_validation->set_rules("dwc_fk_workcentres", 'Availability', 'required');


      // Recieving input 
      $input = $this->input->post();

      $data['pvhcl_count'] = 1;
      $data['pdst_count'] = 1;

      if ($_POST)
      {
         $data['pvhcl_count'] = count($input['pvhcl']['pvhcl_no']);
         $data['pdst_count'] = count($input['pdst']['pdst_name']);

         // Setting validation rules for Tbl:party_vehicles.
         foreach ($input['pvhcl']['pvhcl_no'] as $indx => $val)
         {
            foreach ($input['pvhcl'] as $input_field => $rows)
               if ($input_field != 'pvhcl_no')
                  if (!$val && $input['pvhcl'][$input_field][$indx])
                     $this->form_validation->set_rules("pvhcl[pvhcl_no][$indx]", 'Vehicle No', 'required');
         }

         // Setting validation rules for Tbl:party_destinations
         foreach ($input['pdst']['pdst_name'] as $indx => $val)
         {
            $this->form_validation->set_rules("pdst[pdst_name][$indx]", 'Name', 'required');
         }
      }


      if (!$this->form_validation->run())
      {
         $data['title'] = 'Add Party';
         $data['heading'] = 'ADD NEW PARTY';
         $data['pdst_category'] = $this->party_destinations->getCat();
         $data['workcentres'] = $this->workcentres->get_workcentres_options($this->user_id, '', 1);

         // Getting licenses that are not used yet by anybody.
         $free_licenses = $this->party_license_details->getFreeLicenses();
         $data['license'] = $this->parties->make_options($free_licenses, 'pld_id', 'pld_firm_name');

         if ($_POST)
         {
            $data['message'] = 'Some Errors Occured !';
            $data['message_level'] = 2;
         }

         //echo validation_errors();
         $this->_render_page($this->clsfunc, $data);
         return;
      }


      // Inserting party
      $pty_id = $this->add_party();

      // Inserting Party's vehicle details.
      $this->add_vehicle($pty_id);

      // Insert Party's Destinations details.
      $this->add_party_destinations($pty_id);

      //redirecting
      $party_details = $this->parties->getById($pty_id);
      $msg = 'Party <span class="wlg_name">' . $party_details['pty_name'] . '</span> added successfully!';
      $this->session->set_flashdata('message', $msg);
      $this->session->set_flashdata('message_level', 1); // Success
      redirect($this->cls, 'refresh');
   }

   function add_party_destinations($pty_id)
   {
      $pty_name = $this->parties->getNameById($pty_id);

      // Recieving input 
      $input = $this->input->post('pdst');
      $tbl_data['pdst_date'] = getSqlDate();
      $tbl_data['pdst_fk_parties'] = $pty_id;
      $tbl_data['pdst_status'] = 1;

      // The workcentres where the worklog should be displayed.
      $wcntrs = $this->input->post('dwc_fk_workcentres');

      foreach ($input['pdst_name'] as $key => $val)
      {
         if ($val)
         {
            $tbl_data['pdst_fk_party_license_details'] = $input['pdst_fk_party_license_details'][$key];
            $tbl_data['pdst_name'] = ucwords(strtolower($val));
            $tbl_data['pdst_phone'] = $input['pdst_phone'][$key];
            $tbl_data['pdst_email'] = $input['pdst_email'][$key];
            $tbl_data['pdst_category'] = $input['pdst_category'][$key];

            $pdst_id = $this->party_destinations->insert($tbl_data);

            // Setting message for worklogs.
            $msg = 'A new destination ';
            $msg .= ' <span class="wlg_name">' . $tbl_data['pdst_name'] . '</span> has been added';
            $msg .= ' for the party <span class="wlg_name">' . $pty_name . '</span>.';

            // Adding Tbl:party_destinations details to worklogs .
            $this->send_wlog('party_destinations', $pdst_id, $msg, $this->add, $this->add, $wcntrs);

            // Registering the destination in the given workcentres.
            $this->register_party_destinations_in_the_workcentre($pdst_id);
         }
      }
   }

   function register_party_destinations_in_the_workcentre($pdst_id)
   {

      $pty_id = $this->party_destinations->getPartyFromDestination($pdst_id);
      $pdst_name = $this->party_destinations->getNameById($pdst_id);
      $pty_name = $this->parties->getNameById($pty_id);
      $wcntrs = $this->input->post('dwc_fk_workcentres');

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

   function add_vehicle($pty_id)
   {
      $pty_name = $this->parties->getNameById($pty_id);

      // Recieving input 
      $input = $this->input->post('pvhcl');
      $tbl_data['pvhcl_fk_parties'] = $pty_id;
      $tbl_data['pvhcl_status'] = 1;

      // The workcentres where the worklog should be displayed.
      $wcntrs = $this->input->post('dwc_fk_workcentres');

      foreach ($input['pvhcl_no'] as $key => $val)
      {
         if ($val)
         {
            $tbl_data['pvhcl_name'] = ucwords(strtolower($input['pvhcl_name'][$key]));
            $tbl_data['pvhcl_no'] = strtoupper($val);
            $tbl_data['pvhcl_length'] = $input['pvhcl_length'][$key];
            $tbl_data['pvhcl_breadth'] = $input['pvhcl_breadth'][$key];
            $tbl_data['pvhcl_height'] = $input['pvhcl_height'][$key];
            $tbl_data['pvhcl_xheight'] = $input['pvhcl_xheight'][$key];

            $pvhcl_id = $this->party_vehicles->insert($tbl_data);

            // Setting details for worklogs for Tbl:parties.
            $msg = 'A new vehicle ';
            if ($tbl_data['pvhcl_name'])
            {
               $msg .= ' <span class="wlg_name">' . $tbl_data['pvhcl_name'] . '-' . $tbl_data['pvhcl_no'] . '</span>';
               $msg .= ' has been added for the party <span class="wlg_name">' . $pty_name . '</span>.';
            }
            else
            {
               $msg .= ' <span class="wlg_name">' . $tbl_data['pvhcl_no'] . '</span>';
               $msg .= ' has been added for the party ' . $pty_name . '.';
            }

            // Inserting worklogs of Tbl: party_vehicles.
            $this->send_wlog('party_vehicles', $pvhcl_id, $msg, $this->add, $this->add, $wcntrs);
         }
      }
   }

   function add_party()
   {
      // Recieving input 
      $input = $this->input->post('parties');
      $input['pty_name'] = ucwords(strtolower($input['pty_name']));
      $input['pty_date'] = getSqlDate();
      $pty_id = $this->parties->insert($input);

      // The workcentres where the worklog should be displayed.
      $wcntrs = $this->input->post('dwc_fk_workcentres');

      // Setting the message for the worklogs for Tbl:parties.
      $msg = 'A new party ';
      $msg .= ' <span class="wlg_name">' . $input['pty_name'] . '</span> has been added.';

      // Inserting worklogs of Tbl: parties.
      $this->send_wlog($this->table, $pty_id, $msg, $this->add, $this->add, $wcntrs);

      return $pty_id;
   }

   function index()
   {
      // Checking is the current task is enabled for the user
      $this->isAllowedTask($this->cls);

//        print_r($_POST);echo "<br><br>";
      // Receiving input
      $input = $this->get_pagination_inputs($this->parties);

      //Set the flash data message if there is one has set before redirected to this page.
      $data['message'] = $this->session->flashdata('message');
      $data['message_level'] = $this->session->flashdata('message_level');

      $data['offset'] = $input['offset'];
      $data['title'] = "Party";
      $data['heading'] = "Search For Party";
      $data['pty_status'] = $this->parties->get_status();
      $data['pdst_status'] = $this->party_destinations->get_status();
      $data['pvhcl_status'] = $this->party_vehicles->get_status();
      $data['pdst_category'] = $this->party_destinations->getCat();

      // All active workcentres under the current firm where the user is available.
      $data['workcentres'] = $this->workcentres->get_workcentres_options($this->user_id, $this->firm_id, 1);

      // All active workcentres under all firms where the user is available.
      $data['workcentres_2'] = $this->workcentres->get_workcentres_options($this->user_id, '', 1);

      $data['license_options'] = '';
      $this->per_page = $_POST ? $input['PER_PAGE'] : $this->per_page;

      // Setting default search options.
      if (!$_POST)
      {
         $input['pty_status'] = 1; //Default status is Active   
         $input['pvhcl_status'] = 0; // No default status.
         $input['pdst_status'] = 0; // No default status.
         $input['dwc_status'] = 0; // No default status.
         $input['pdst_category'] = '';
         $input['pdst_name'] = '';
         $input['pvhcl_no'] = '';
         $availability = $this->workcentres->getIdsFromOption($data['workcentres']);
      }
      else
      {
         if (isset($input['wcntr_id']) && $input['wcntr_id'])
            $availability = $input['wcntr_id'];
         else
            $availability = $this->workcentres->getIdsFromOption($data['workcentres']);
      }

      //If reffered from Worklogs;
      $wlog_ref_id = ($this->uri->segment(3) == 'wlogs') ? $this->uri->segment(4) : '';
      $data['table'] = $this->parties->index($input, $wlog_ref_id, $availability);
      $data['num_rows'] = $this->parties->index($input, $wlog_ref_id, $availability, true);

      // Getting all active Party's Option.
      $data['party_opt'] = $this->parties->get_active_option();


      // Adding other details
      if ($data['table'])
      {
         // Getting user, who last changed the worklog.
         $data['wlog'] = $this->getWlogUser($data['table']);

         foreach ($data['table'] as $row)
         {
            // Getting Party's Destinations.
            $data['destinations'][$row[$this->p_key]] = $this->party_destinations->getDestinationByParty_2($row[$this->p_key], false, $input['pdst_status'], $input['pdst_category'], $input['pdst_name'], $availability);



            // Getting Party's Vehicles.
            $data['vehicles'][$row[$this->p_key]] = $this->party_vehicles->getVehicleByParty($row[$this->p_key], $input['pvhcl_status'], $input['pvhcl_no']);
            if ($data['destinations'][$row[$this->p_key]])
            {
               foreach ($data['destinations'][$row[$this->p_key]] as $row)
                  $data['dst_wnctr'][$row['pdst_id']] = $this->destination_workcentres->index($row['pdst_id']);
            }
         }
      }

      // Geting Freight Charges.
      if (isset($data['dst_wnctr']) && $data['dst_wnctr'])
      {
         foreach ($data['dst_wnctr'] as $availability)
            foreach ($availability as $avbl)
               $data['freight'][$avbl['dwc_id']] = $this->party_vehicle_rents->index($avbl['dwc_fk_party_destinations'], $avbl['dwc_fk_workcentres']);
      }


      // Initializing pagination
      $data = array_merge($data, $this->initPagination($data['table'], $data['num_rows'], $input['offset']));

      // After validations
      $data = array_merge($data, $this->validateIndex());

      $this->_render_page($this->clsfunc, $data);
   }

   function validateIndex()
   {
      $config[] = array('f_emp_date', 'From Date', 'callback_compare_dates[' . $this->input->post('t_emp_date') . ']');
      $data = $this->checkValidations($config);
      return $data;
   }

   function toggleStatus()
   {
      // Checking is the current task is enabled for the user
      $this->isAllowedTask($this->cls . '/edit');
      $pty_id = $this->input->post('pty_id');
      $pty_details = $this->parties->getById($pty_id);
      $prev_status = $this->parties->getTableStatus($pty_id);

      // Id of workcentres related to the party.
      $partys_wcntrs = $this->destination_workcentres->getPartyWorkcentres($pty_id);

      // Toggling status.
      $this->parties->toggleTableStatus($pty_id);

      $curnt_status = $this->parties->getTableStatus($pty_id);

      // If Status changed; 
      if ($prev_status != $curnt_status)
      {
         $pty_status = ($curnt_status == ACTIVE) ? "activated" : "deactivated";

         // Message related to the worklog.
         $msg = 'The party <span class="wlg_name">' . $pty_details['pty_name'];
         $msg .= '</span>  has been ' . $pty_status . '.';

         // Inserting worklogs of Tbl: parties.
         $this->send_wlog($this->table, $pty_id, $msg, $this->edit, $this->edit, $partys_wcntrs, $pty_details);

         ### If the status of party changed, the change must be applied to Tbl:party_destinations and so Tbl:party_license_details. 
         //Destinations of party (Active/Inactive).
         $destinations = $this->party_destinations->getDestinationByParty($pty_id, FALSE, '');

         foreach ($destinations as $pdst)
         {
            // The worklog should be added under all workcentres in which the destination is related to it.
            $pdst_id = $pdst['pdst_id'];
            $dest_wcntrs = $this->destination_workcentres->getDestinationWorkcentres($pdst_id);

            // Changing status of destination.
            if ($curnt_status == ACTIVE && $this->party_destinations->is_inactive($pdst_id))
               $this->party_destinations->activate($pdst_id);
            else if ($curnt_status == INACTIVE && $this->party_destinations->is_active($pdst_id))
               $this->party_destinations->deactivate($pdst_id);

            // Message related to the worklog.
            $msg = 'The destination <span class="wlg_name">' . $pdst['pdst_name'] . '</span> has been ' . $pty_status;
            $msg .= ' when it\'s parent party <span class="wlg_name">' . $pty_details['pty_name'] . '</span> ' . $pty_status . '.';

            // Inserting worklogs of Tbl: party_destinations.
            $this->send_wlog('party_destinations', $pdst['pdst_id'], $msg, $this->edit, $this->edit, $dest_wcntrs, $pdst);

            ### Doing the same for Tbl:destination_workcenres.
            if ($curnt_status == ACTIVE)
               $this->actvateAvailability($pdst['pdst_id']);
            else
               $this->deactivateAvailability($pdst['pdst_id']);


            ### Doing the same for Tbl:party_license_details
            // Getting the previous details of the License.
            $pld_id = $pdst['pdst_fk_party_license_details'];
            $prev_licence = $this->party_license_details->getById($pld_id);
            if ($prev_licence)
            {
               // Changing status of License..
               if ($curnt_status == ACTIVE && $this->party_license_details->is_inactive($pld_id))
                  $this->party_license_details->activate($pdst['pdst_fk_party_license_details']);
               else if ($curnt_status == INACTIVE && $this->party_license_details->is_active($pld_id))
                  $this->party_license_details->deactivate($pdst['pdst_fk_party_license_details']);

               // Message related to the worklog.

               $msg = 'The Licence details of <span class="wlg_name">' . $prev_licence['pld_firm_name'] . '</span>';
               $msg .= ' of Destination <span class="wlg_name">' . $pdst['pdst_name'] . '</span>';
               $msg .= ' of party <span class="wlg_name">' . $pty_details['pty_name'] . '</span>';
               $msg .= ' has been ' . $pty_status . '.';

               // Inserting worklogs of Tbl: party_license_details.
               $this->send_wlog('party_license_details', $pdst['pdst_fk_party_license_details'], $msg, $this->edit, $this->edit, $dest_wcntrs, $prev_licence);
            }
         }


         echo $pty_details['pty_name'] . " has been " . $pty_status;
      }
      else
         echo "Couldn't change status.";
   }

   function actvateAvailability($pdst_id)
   {
      $availability = $this->destination_workcentres->getByDestination($pdst_id, false, '');

      if ($availability)
      {
         $pty_id = $this->party_destinations->getPartyFromDestination($pdst_id);
         $pty_name = $this->parties->getNameById($pty_id);
         $pdst_name = $this->party_destinations->getNameById($pdst_id);

         foreach ($availability as $row)
         {
            // Activate iff the workcentre is active and also iff it is inactive itself in Tbl: destination_workcentres.
            $is_wcntr_active = $this->workcentres->is_active($row['dwc_fk_workcentres']);
            $is_dwc_inactive = $this->destination_workcentres->is_inactive($row['dwc_id']);
            if ($is_wcntr_active && $is_dwc_inactive)
            {
               $prev_details = $this->destination_workcentres->getById($row['dwc_id']);
               $this->destination_workcentres->activate($row['dwc_id']);

               // Message related to the worklog.
               $msg = 'The destination <span class="wlg_name">' . $pdst_name . '</span> has been activated in the workcentre';
               $msg .= ' when its party <span class="wlg_name">' . $pty_name . '</span> activated.';

               $wc[0] = $row['dwc_fk_workcentres'];

               // Inserting to worklog.
               $this->send_wlog('destination_workcentres', $row['dwc_id'], $msg, $this->edit, $this->edit, $wc, $prev_details);
            }
         }
      }
   }

   function deactivateAvailability($pdst_id)
   {
      $availability = $this->destination_workcentres->getByDestination($pdst_id, false, '');
      if ($availability)
      {
         $pty_id = $this->party_destinations->getPartyFromDestination($pdst_id);
         $pty_name = $this->parties->getNameById($pty_id);
         $pdst_name = $this->party_destinations->getNameById($pdst_id);
         foreach ($availability as $row)
         {
            // Deactivate iff it is active itself in Tbl: destination_workcentres.
            $is_dwc_active = $this->destination_workcentres->is_active($row['dwc_id']);

            if ($is_dwc_active)
            {
               $prev_details = $this->destination_workcentres->getById($row['dwc_id']);
               $this->destination_workcentres->deactivate($row['dwc_id']);

               // Message related to the worklog.
               $msg = 'The destination <span class="wlg_name">' . $pdst_name . '</span> has been deactivated in the workcentre';
               $msg .= ' when its party <span class="wlg_name">' . $pty_name . '</span> deactivated.';

               $wc[0] = $row['dwc_fk_workcentres'];

               // Inserting to worklog.
               $this->send_wlog('destination_workcentres', $row['dwc_id'], $msg, $this->edit, $this->edit, $wc, $prev_details);
            }
         }
      }
   }

   function getPartiesByWorkcentres()
   {
      $wc_ids = $_GET['wcntr_ids'];

      if (!$wc_ids)
         return;

      // Getting all active parties under users workcentres.
      $parties = $this->parties->getPartiesUnderWorkcentre($wc_ids);
      $data = $this->parties->make_options_2($parties);
      $this->json_options($data, "-- No New Parties --");
   }

   function getPartiesByWorkcentres2()
   {
      $wc_ids = $_GET['wcntr_ids'];
      $tax_type = $_GET['tax_type'];

      if (!$wc_ids || !$tax_type)
         return;

      // Getting all active parties under users workcentres.
      // If Taxable, getting the party only those are having a legal registration.
      if ($tax_type == 1)
      {
         $parties = $this->party_license_details->get_registered_partiesUnderWorkcentre($wc_ids);
      }
      else
      {
         $parties = $this->parties->getPartiesUnderWorkcentre($wc_ids);
      }
      
      $data = $this->parties->make_options_2($parties);
      $this->json_options($data, "-- No New Parties --");
   }

   function beforeEdit()
   {
      // Checking is the current task is enabled for the user
      $this->isAllowedTask($this->cls . "/edit");
      //$input = $this->get_inputs();
      $id = $_GET['pty_id'];
      $details = $this->parties->getById($id);
      $details['pty_date'] = formatDate($details['pty_date'], false, 1);
      echo json_encode($details);
      return;
   }

   function edit()
   {   // Checking is the current task is enabled for the user
      $task = taskEnabled($this->clsfunc);
      if ($task != 1)
      {
         echo $task;
         return;
      }

      //	Validating 
      $v_config = validationConfigs($this->table);
      $this->form_validation->set_rules($v_config);
      $this->form_validation->set_rules("pty_date", 'Date', 'required');
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

      $pty_id = $input['pty_id'];

      // The party details before edit
      $prev_details = $this->parties->getById($pty_id);

      $input['pty_date'] = getSqlDate($input['pty_date']);
      $input['pty_name'] = ucwords(strtolower($input['pty_name']));

      // Saving data to Tbl:owners
      $this->parties->save($input, $pty_id);

      // The party details after edit
      $cur_details = $this->parties->getById($pty_id);

      // Checking is anything edited.
      $edited = $this->isEdited($prev_details, $cur_details);

      // If edited, creating worklog.
      if ($edited)
      {
         // Worklog should be displayed in all workcentres where party has been registered.
         $workcentres = $this->destination_workcentres->getPartyWorkcentres($pty_id);

         // Message related to the worklog.
         $msg = 'The personal details of party ';
         $msg .= ' <span class="wlg_name">' . $prev_details['pty_name'] . '</span> has been changed.';

         // Inserting worklogs of Tbl: parties.
         $this->send_wlog($this->table, $pty_id, $msg, $this->edit, $this->edit, $workcentres, $prev_details);

         echo 1;
      }
      else
         echo '<div class="failureHead"><img src="images/failure.gif" width="15" height="15" /> Error!!!<div class="pop_failure">There is nothing changed!</div></div>';
   }

}

?>