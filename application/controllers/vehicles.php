<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require 'my_controller.php';

class Vehicles extends My_controller
{

   function __construct()
   {
      parent::__construct();

      $this->load->model('vehicles_model', 'vehicles');
      $this->load->model('vehicle_workcentres_model', 'vehicle_workcentres');
      $this->load->model('vehicles_employees_model', 'vehicles_employees');
      $this->load->model('freight_charges_model', 'freight_charges');
      $this->load->model('inter_freight_charges_model', 'inter_freight_charges');
      $this->load->library('troubleshoot');

      // Determining is the logged in user allowed to go forward with the current action.
      $this->isAllowed();
      $this->per_page = 10;
      $this->table = 'vehicles';
      $this->p_key = 'vhcl_id';
   }

   function get_wlog()//Called by ajax
   {
      $model = $this->vehicles;
      $wlogs = $this->init_wlog($this->p_key, $model);

      if ($wlogs[0])
      {
         $latest_class = 'wlog_latest'; // Latest details about the worklog. Ie: the data from ref_tables, not from Tbl:backups.
         $wlog_fields = getWlogFields($this->table, 'keys');

         $ownership = $model->get_ownership_values();
         $status = $model->get_vehicle_status();
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

               if ($fld == 'vhcl_ownership')
                  $val = $ownership[$row[$fld]];
               else if ($fld == 'vhcl_status')
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

   function getAvailabilityFields($flag = FALSE)
   {
      $fld[] = 'vwc_fk_workcentres';
      $fld[] = 'vwc_cost';
      $fld[] = 'vwc_ob';
      $fld[] = 'vwc_ob_mode';
      $fld[] = 'vwc_hourly_rate';
      $fld[] = 'vwc_daily_rate';
      $fld[] = 'vwc_monthly_rate';

      if ($flag)
      {
         $fld[] = 'vwc_sold_price';
         $fld[] = 'vwc_status';
      }
      return $fld;
   }

   //vhcl_date,vhcl_no,vhcl_name,vhcl_length,vhcl_breadth,vhcl_height,vhcl_remarks,vhcl_ownership,vhcl_status
   function add()
   {

      // Checking is the current task is enabled for the user
      $this->isAllowedTask($this->clsfunc);

      // Validating vehicles.
      $v_config = validationConfigs($this->table, '', $this->table);
      $this->form_validation->set_rules($v_config);

      $data['workcentres'] = $this->workcentres->get_workcentres($this->user_id, '', 1);

      $availabilityFields = $this->getAvailabilityFields();
      $vhclwc = $this->input->post('vhclwc_flds');

      foreach ($availabilityFields as $fld_name)
      {
         foreach ($data['workcentres'] as $wc)
         {
            if (isset($vhclwc[$fld_name][$wc['wcntr_id']]))
            {
               $data['vhclwc_flds'][$fld_name][$wc['wcntr_id']] = $vhclwc[$fld_name][$wc['wcntr_id']];
            }
            else
            {
               $data['vhclwc_flds'][$fld_name][$wc['wcntr_id']] = '';
            }
         }
      }

      if ((!$this->form_validation->run()) || (!isset($vhclwc['vwc_fk_workcentres'])))
      {
         $data['title'] = 'Add Vehicle';
         $data['heading'] = 'ADD NEW VEHICLE';
         $data['ownership'] = $this->vehicles->get_ownership_values();
         $data['firms'] = $this->firms->get_firms_options($this->user_id, 1);

         $data['availability_errors'] = '';
         if ($_POST)
         {
            $data['message'] = 'Some Errors Occured !';
            $data['message_level'] = 2;
            if (!isset($vhclwc['vwc_fk_workcentres']))
               $data['availability_errors'] = '<div class="dialog-box-border">A vehicle must be register under any of the workcentres listed above.</div>';
         }
//            echo validation_errors();
         $this->_render_page($this->clsfunc, $data);
         return;
      }

      // Recieving input 
      $input = $this->input->post();

      // Formating input details
      $input['vehicles']['vhcl_date'] = getSqlDate();
      $input['vehicles']['vhcl_name'] = ucwords(strtolower($input['vehicles']['vhcl_name']));
      $input['vehicles']['vhcl_no'] = strtoupper($input['vehicles']['vhcl_no']);

      // Inserting Vehicle.
      if (!$vhcl_id = $this->vehicles->insert($input['vehicles']))
      {
         echo "Couldn't insert vehicle, please contact your web consultant !!!";
         return;
      }


      // Getting registered workcentres.
      $vehicle_workcentres = array_keys($input['vhclwc_flds']['vwc_fk_workcentres']);

      // Related firms
      $firms = $this->workcentres->getFirmsOfWorkcentres($vehicle_workcentres);

      // Formating $firms to add to worklogs.
      $firms = implode(',', $firms);

      // Setting details for worklogs for Tbl:vehicles.
      $msg = 'A new vehicle';
      $msg .= ' <span class="wlg_name">' . $input['vehicles']['vhcl_no'] . '</span> has been added.';
      $wlog_wc[0]['msg'] = $msg;      // $wlog_wc[0] Displayed as a general worklog. ie:- will not displayed under any workcentre.
      $wlog_wc[0]['action'] = $this->add;


      // Adding Tbl:vehicle details to worklogs .
      $this->add_logs($this->table, $vhcl_id, get_url($this->table), get_popup_id($this->table), $wlog_wc, $this->add, $firms);


      foreach ($vehicle_workcentres as $wcntre_id)
      {
         $tblData = array();
         foreach ($input['vhclwc_flds'] as $fldName => $vals)
            $tblData[$fldName] = isset($input['vhclwc_flds'][$fldName][$wcntre_id]) ? $input['vhclwc_flds'][$fldName][$wcntre_id] : '';
         $tblData['vwc_fk_vehicles'] = $vhcl_id;
         $tblData['vwc_fk_workcentres'] = $wcntre_id;
         $tblData['vwc_date'] = $input['vehicles']['vhcl_date'];
         $tblData['vwc_status'] = 1; // Employee is active in workcentre. (Default)
         // Registering vehicles in workcentres
         $vwc_id = $this->vehicle_workcentres->insert($tblData);

         // Setting details for worklogs for Tbl:vehicle_workcentres.
         $wlog_wc = array();
         $msg = 'Vehicle ';
         $msg .= ' <span class="wlg_name">' . $input['vehicles']['vhcl_no'] . '</span> has been registered in the workcentre.';
         $wlog_wc[$wcntre_id]['msg'] = $msg;
         $wlog_wc[$wcntre_id]['action'] = $this->add;

         // Adding Tbl:vehicle_workcentres details to worklogs .
         $this->add_logs('vehicle_workcentres', $vwc_id, get_url('vehicle_workcentres'), get_popup_id('vehicle_workcentres'), $wlog_wc, $this->add);
      }

      //redirecting
      $this->session->set_flashdata('message', $this->ion_auth->messages());
      $this->session->set_flashdata('message_level', 1); // Success        
      redirect($this->cls, 'refresh');
   }

   function index()
   {
      // Checking is the current task is enabled for the user
      $this->isAllowedTask($this->cls);


      // Troubleshoot to delete Illegal labours from vehicle.
      // Illegal labours are employees whom are not available in any of the workcentres where vehicle is available
      $usrcat = $this->employees->get_employee_category(1, $this->user_cat);
      $this->troubleshoot->delete_illegal_labours('', '', "; By the system troubleshooter when <span class='wlg_name'>$usrcat: $this->user_name</span> visited the vehicles page.");

      // Receiving input
      $input = $this->get_pagination_inputs($this->vehicles);

      //Set the flash data message if there is one has set before redirected to this page.
      $data['message'] = $this->session->flashdata('message');
      $data['message_level'] = $this->session->flashdata('message_level');
      $data['offset'] = $input['offset'];
      $data['title'] = "Vehicles";
      $data['heading'] = "Search Vehicles";
      $data['status'] = $this->vehicles->get_vehicle_status();
      $data['ownership'] = $this->vehicles->get_ownership_values();
      $data['workcentres'] = $this->workcentres->get_workcentres_options($this->user_id, $this->firm_id, 1);

      $this->load->model('employee_work_centre_model', 'employee_work_centre');
      $data['drivers'] = $this->employee_work_centre->getUsersEmployees($this->user_id, 4, 'options', '', $this->firm_id);
      $data['loaders'] = $this->employee_work_centre->getUsersEmployees($this->user_id, 5, 'options', '', $this->firm_id);


      $this->per_page = $_POST ? $input['PER_PAGE'] : $this->per_page;


      // Setting default search options.
      if (!$_POST)
      {
         $input['vhcl_status'] = 1; //Default status is Active  
         $input['vwc_status'] = 1; //Default status is Active  
         $input['vhcl_ownership'] = 0; //Default ownership is All.  
      }

      // If reffered from Worklogs;
      $wlog_ref_id = ($this->uri->segment(3) == 'wlogs') ? $this->uri->segment(4) : '';

      $data['table'] = $this->vehicles->index($input, $data['workcentres'], $wlog_ref_id);
      $data['num_rows'] = $this->vehicles->index($input, $data['workcentres'], $wlog_ref_id, true);

      $user_wcntrs = $this->workcentres->get_workcentres_options($this->user_id, '', 1);
      $user_wcntrs = $this->workcentres->getIdsFromOption($user_wcntrs);

      // Adding other details
      if ($data['table'])
      {
         // Getting user, who last changed the worklog.
         $data['wlog'] = $this->getWlogUser($data['table']);

         foreach ($data['table'] as $row)
         {
            $vhcl_id = $row[$this->p_key];

            // Getting vehicle availability
            $data['availability'][$vhcl_id] = $this->vehicle_workcentres->index($vhcl_id, $input['vwc_status'], $user_wcntrs);

            // Labours in the vehicle
            $data['labours'][$vhcl_id] = $this->vehicles_employees->index($vhcl_id);

            // Freight charges of the vehicle from workcentre to vehicle destinations.
            $data['freight'][$vhcl_id] = $this->freight_charges->index($vhcl_id);

            // Freight charges of the vehicle between workcentres.
            $data['inter_freight'][$vhcl_id] = $this->inter_freight_charges->index($vhcl_id);
         }
      }

      // Initializing pagination
      $data = array_merge($data, $this->initPagination($data['table'], $data['num_rows'], $input['offset']));

      // After validations
      $data = array_merge($data, $this->validateIndex());

      $this->_render_page($this->clsfunc, $data);
   }

   function validateIndex()
   {
      $config[] = array('f_vhcl_date', 'From Date', 'callback_compare_dates[' . $this->input->post('t_vhcl_date') . ']');
      $data = $this->checkValidations($config);
      return $data;
   }

   function beforeEdit()
   {
      // Checking is the current task is enabled for the user
      $this->isAllowedTask($this->cls . "/edit");
      //$input = $this->get_inputs();
      $id = $_GET['vhcl_id'];
      $details = $this->vehicles->getById($id);
      $details['vhcl_date'] = formatDate($details['vhcl_date'], false, 1);
      echo json_encode($details);
      return;
   }

   function edit()
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

      $vhcl_id = $input['vhcl_id'];


      // The vehicle details before edit
      $prev_details = $this->vehicles->getById($vhcl_id);

      $input['vhcl_name'] = ucwords(strtolower($input['vhcl_name']));
      $input['vhcl_no'] = strtoupper($input['vhcl_no']);

      // Saving data to Tbl:vehicles
      $this->vehicles->save($input, $vhcl_id);

      // The vehicle details after edit
      $cur_details = $this->vehicles->getById($vhcl_id);

      // Checking is anything edited.
      $edited = $this->isEdited($prev_details, $cur_details);

      // If edited, creating worklog.
      if ($edited)
      {
         // Worklog should be displayed in all workcentres where vehicle has been registered.
         $workcentres = $this->vehicle_workcentres->getVehicleWorkcentres($vhcl_id);

         // Message related to the worklog.
         $name = $prev_details['vhcl_name'] ? $prev_details['vhcl_name'] . ": " . $prev_details['vhcl_no'] : $prev_details['vhcl_no'];
         $msg = 'The details of vehicle ';
         $msg .= ' <span class="wlg_name">' . $name . '</span> has been changed.';

         // If vhcl_ownership is changed from our's to other's
         // All labours and freight charges associated with the vehicle must be deleted.
         // Some of the accountable fields in Tbl: vehicle_workcentres must be make zero.
         if (($prev_details['vhcl_ownership'] == 1) && ($cur_details['vhcl_ownership'] == 2))
         {
            $this->delete_associates($vhcl_id);
            $msg .= ' And also all of the labours & freight charges associated with the vehicle has been deleted because the vehicle is not our\'s now.';

            $this->delete_accountables($vhcl_id);
         }

         // Inserting worklogs of Tbl: vehicles.
         $this->send_wlog($this->table, $vhcl_id, $msg, $this->edit, $this->edit, $workcentres, $prev_details);

         echo 1;
      }
      else
         echo '<div class="failureHead"><img src="images/failure.gif" width="15" height="15" /> Error!!!<div class="pop_failure">There is nothing changed!</div></div>';
   }

   function delete_accountables($vhcl_id)
   {
      $vwc_details = $this->vehicle_workcentres->get_data('', array('vwc_fk_vehicles' => $vhcl_id));

      $input['vwc_cost'] = '';
      $input['vwc_hourly_rate'] = '';
      $input['vwc_daily_rate'] = '';
      $input['vwc_monthly_rate'] = '';
      $input['vwc_sold_price'] = '';


      foreach ($vwc_details as $row)
      {
         $prev_details = $row;
         $vwc_id = $row['vwc_id'];

         // Saving data to Tbl:vehicle_workcentres
         $this->vehicle_workcentres->save($input, $vwc_id);

         // The vehicle details after edit
         $cur_details = $this->vehicle_workcentres->getById($vwc_id);

         // Checking is anything edited.
         $edited = $this->isEdited($prev_details, $cur_details);

         if ($edited)
         {
            $vhcl_no = $this->vehicles->getNameById($row['vwc_fk_vehicles']);
            $wcntr_id = $row['vwc_fk_workcentres'];
            $msg = "Some unwanted datas have been deleted when ownership of the vehicle <span class='wlg_name'>$vhcl_no</span> changed.";

            // Inserting worklogs of Tbl: vehicle_workcentres.
            $this->send_wlog('vehicle_workcentres', $vwc_id, $msg, $this->edit, $this->edit, $wcntr_id, $prev_details);
         }
      }
   }

   function delete_associates($vhcl_id)
   {
      // Deleting all labours associated with the vehicle.
      $where = array();
      $where['vemp_fk_vehicles'] = $vhcl_id;
      $this->vehicles_employees->delete_where($where);

      // Deleting all inter freight charges associated with the vehicle.
      $where = array();
      $where['ifc_fkey_vehicles'] = $vhcl_id;
      $this->inter_freight_charges->delete_where($where);

      // Deleting all freight charges to the parties associated with the vehicle.
      $where = array();
      $where['fc_fk_vehicles'] = $vhcl_id;
      $this->freight_charges->delete_where($where);
   }

   function toggleStatus()
   {
      // Checking is the current task is enabled for the user
      $this->isAllowedTask($this->cls . '/edit');

      $vhcl_id = $this->input->post('vhcl_id');
      $vhcl_details = $this->vehicles->getById($vhcl_id);

      // Status before toggle.
      $prev_status = $this->vehicles->getTableStatus($vhcl_id);

      // Worklog should be displayed in all active workcentres where vehicle has been registered.
      $workcentres = $this->vehicle_workcentres->getVehicleWorkcentres($vhcl_id);

      // Toggling status.
      $this->vehicles->toggleTableStatus($vhcl_id);

      // Status after toggle.
      $curnt_status = $this->vehicles->getTableStatus($vhcl_id);

      // If Status changed; 
      if ($prev_status != $curnt_status)
      {
         $vhcl_status = $this->status_message($curnt_status);

         // Message related to the worklog.
         $name = $vhcl_details['vhcl_name'] ? $vhcl_details['vhcl_name'] . ": " . $vhcl_details['vhcl_no'] : $vhcl_details['vhcl_no'];
         $msg = 'The vehicle <span class="wlg_name">' . $name . '</span>';
         $msg .= ' has been ' . $vhcl_status . '.';

         // Inserting worklogs of Tbl: vehicles.
         $this->send_wlog($this->table, $vhcl_id, $msg, $this->edit, $this->edit, $workcentres, $vhcl_details);

         // If the status of vehicle changed, the change must be applied to Tbl:vehicle_workcentres.
         $this->toggle_availability($vhcl_id, $curnt_status, $name);

         echo $name . " has been " . $vhcl_status;
      }
      else
         echo "Couldn't change status.";
   }

   function toggle_availability($vhcl_id, $vhcl_status, $name)
   {
      // Worklog should be displayed in only active workcentres where the vehicle has been registered.
      // So taking active workcentres first. After worklog, we will consider inactive workcentres also.
      $workcentres = $this->vehicle_workcentres->getVehicleWorkcentres($vhcl_id, '', 'wcntr_id', 'wcntr_id', '');

      foreach ($workcentres as $wc_id)
      {
         $where['vwc_fk_workcentres'] = $wc_id;
         $where['vwc_fk_vehicles'] = $vhcl_id;

         // Getting details before toggle.
         $prev_details = $this->vehicle_workcentres->get_row($where);

         $vwc_id = $prev_details['vwc_id'];

         // Changing status
         $this->vehicle_workcentres->setTableStatus($vhcl_status, $vwc_id);

         // Getting details after toggle.
         $cur_details = $this->vehicle_workcentres->getById($vwc_id);

         // Checking is anything edited.
         $edited = $this->isEdited($prev_details, $cur_details);

         // If edited, creating worklog.
         if ($edited)
         {
            // Message related to the worklog.
            $msg = 'The vehicle <span class="wlg_name">' . $name . '</span>';
            $msg .= ' has been ' . $this->status_message($vhcl_status) . ' in the workcentre ';
            $msg .= 'when it was ' . $this->status_message($vhcl_status) . ' itself.';

            // Inserting worklogs of Tbl:vehicle_workcentres.
            $this->send_wlog('vehicle_workcentres', $vwc_id, $msg, $this->edit, $this->edit, $wc_id, $prev_details);
         }
      }

      # By the above code, we have changed the vwc_status in active workcentres only. So the vwc_status in the inactive workcentre are not changed yet. So if the action is 'Deactivating', the vwc_status of the inactive workcentres must also be deactivated. It is not required to generate a worklog due to the workcentres are inactive.
      if ($vhcl_status == INACTIVE)
      {
         $data = array();
         $where = array();
         $data['vwc_status'] = INACTIVE;
         $where['vwc_fk_vehicles'] = $vhcl_id;
         $this->vehicle_workcentres->update_where($data, $where);
      }

      return TRUE;
   }

}

?>