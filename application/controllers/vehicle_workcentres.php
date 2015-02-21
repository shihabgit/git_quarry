<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require 'my_controller.php';

class Vehicle_workcentres extends My_controller
{

   function __construct()
   {
      parent::__construct();

      $this->load->model('vehicles_model', 'vehicles');
      $this->load->model('vehicle_workcentres_model', 'vehicle_workcentres');

      // Determining is the logged in user allowed to go forward with the current action.
      $this->isAllowed();

      $this->table = 'vehicle_workcentres';
      $this->p_key = 'vwc_id';
   }

   function get_wlog()//Called by ajax
   {
      $model = $this->vehicle_workcentres;
      $wlogs = $this->init_wlog($this->p_key, $model);

      if ($wlogs[0])
      {
         $latest_class = 'wlog_latest'; // Latest details about the worklog. Ie: the data from ref_tables, not from Tbl:backups.
         $wlog_fields = getWlogFields($this->table, 'keys');

         $ob_mode = array(1 => 'Cr', 2 => 'Dr');
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

               if ($fld == 'vwc_status')
                  $val = $status[$row[$fld]];
               else if ($fld == 'vwc_ob_mode')
                  $val = $ob_mode[$row[$fld]];
               else if ($fld == 'vwc_date')
                  $val = formatDate($row[$fld], FALSE);

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

   function getVehiclesWorkcentres()
   {
      $vhcl_id = $_GET['vhcl_id'];

      // Workentres where both the user and vehicles are available.
      $workcentres = $this->vehicle_workcentres->getVehicleWorkcentresByUser($this->user_id, $vhcl_id, '', 'All');

      $data = $this->vehicle_workcentres->make_options($workcentres, 'wcntr_id', 'wcntr_name');
      $this->json_options($data, "-- No Workcentres --");
   }
   
   function get_vehicles_in_workcentre()
   {
        $wcntr_id = $_GET['wcntr_id'];
        
        if(!$wcntr_id) return;
        
        $vhcl_ownership = $_GET['vhcl_ownership']?:'';
        
        $vhcles = $this->vehicle_workcentres->get_vehicles_in_workcentre($wcntr_id, $vhcl_ownership);
        $data = $this->vehicles->make_options_2($vhcles);
        
        if(!$data)
        {
            $json[] = array('value' => '', 'text' => 'No Vehicles');
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
      $task = taskEnabled("vehicles/add");
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

      $input['vwc_date'] = getSqlDate();
      $input['vwc_status'] = ACTIVE;

      $vhcl_id = $input['vwc_fk_vehicles'];
      $vhcl_no = $this->vehicles->getNameById($vhcl_id);

      // Inserting data to Tbl:vehicles_employees
      $vwc_id = $this->vehicle_workcentres->insert($input);

      if ($vwc_id)
      {
         // Worklog should be displayed only in newly registered workcentre.
         $workcentre = $input['vwc_fk_workcentres'];

         // Message related to the worklog.
         $msg = 'The vehicle <span class="wlg_name">' . $vhcl_no . '</span>';
         $msg .= ' has been registered in the workcentre';


         // Inserting worklogs of Tbl: vehicle_workcentres.
         $this->send_wlog($this->table, $vwc_id, $msg, $this->add, $this->add, $workcentre);

         echo 1;
      }
      else
         echo $this->formatePopupError('Data couldn\'t insert !');
   }

   function beforeEdit()
   {
      // Checking is the current task is enabled for the user
      $this->isAllowedTask("vehicles/edit");
      //$input = $this->get_inputs();
      $id = $_GET['vwc_id'];
      $details = $this->vehicle_workcentres->getById($id);
      $details['vwc_date'] = formatDate($details['vwc_date'], false, 1);
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

      $vwc_id = $input['vwc_id'];

      // The vehicle details before edit
      $prev_details = $this->vehicle_workcentres->getById($vwc_id);

      $wcntr_id = $prev_details['vwc_fk_workcentres'];

      $vhcl_no = $this->vehicles->getNameById($prev_details['vwc_fk_vehicles']);

      // Saving data to Tbl:vehicle_workcentres
      $this->vehicle_workcentres->save($input, $vwc_id);

      // The vehicle details after edit
      $cur_details = $this->vehicle_workcentres->getById($vwc_id);

      // Checking is anything edited.
      $edited = $this->isEdited($prev_details, $cur_details);

      // If edited, creating worklog.
      if ($edited)
      {
         // If a warning to Admin.
         $warning_fields = array('vwc_cost', 'vwc_ob', 'vwc_ob_mode', 'vwc_hourly_rate', 'vwc_daily_rate', 'vwc_monthly_rate', 'vwc_sold_price');
         $warning = $this->check_warnings($prev_details, $cur_details, $warning_fields);

         // Message related to the worklog.
         $msg = 'The details of the vehicle <span class="wlg_name">' . $vhcl_no . '</span>';
         $msg .= ' in the workcentre has been edited';

         // Inserting worklogs of Tbl: vehicle_workcentres.
         $this->send_wlog($this->table, $vwc_id, $msg, $this->edit, $this->edit, $wcntr_id, $prev_details, '', $warning);

         echo 1;
      }
      else
         echo $this->formatePopupError('There is nothing changed');
   }

   function toggleStatus()
   {
      // Checking is the current task is enabled for the user
      $this->isAllowedTask('vehicles/edit');

      $vwc_id = $this->input->post('vwc_id');
      $vwc_details = $this->vehicle_workcentres->getById($vwc_id);
      $vhcl_id = $vwc_details['vwc_fk_vehicles'];
      $vhcl_no = $this->vehicles->getNameById($vhcl_id);

      // Workcentre to which the worklog should be displayed under.
      $wcntr_id = $vwc_details['vwc_fk_workcentres'];
      $wcntr_name = $this->workcentres->getNameById($wcntr_id);



      // Status before toggle.
      $prev_status = $this->vehicle_workcentres->getTableStatus($vwc_id);

      // Toggling status.
      $this->vehicle_workcentres->toggleTableStatus($vwc_id);

      // Status after toggle.
      $curnt_status = $this->vehicle_workcentres->getTableStatus($vwc_id);

      // If Status changed; 
      if ($prev_status != $curnt_status)
      {
         $vwc_status = $this->status_message($curnt_status);

         // Message related to the worklog.
         $msg_1 = 'The vehicle <span class="wlg_name">' . $vhcl_no . '</span> has been ';
         $msg = $msg_1 . $vwc_status . ' in the workcentre.';

         // Inserting worklogs of Tbl: vehicle_workcentres.
         $this->send_wlog($this->table, $vwc_id, $msg, $this->edit, $this->edit, $wcntr_id, $vwc_details);

         // If the status of vehicle changed, the change must be applied to Tbl:vehicles.
         $this->toggle_vehicle($vhcl_id, $curnt_status, $msg_1, $wcntr_name);

         echo "The vehicle has been " . $vwc_status . ' in the workcentre.';
      }
      else
         echo "Couldn't change status.";
   }

   function toggle_vehicle($vhcl_id, $vwc_status, $msg, $wcntr_name)
   {
      $msg = $msg . $this->status_message($vwc_status) . ' when it was ';
      $msg .= $this->status_message($vwc_status) . ' in the workcentre <span class="wlg_name">' . $wcntr_name . '</span>';

      // Details of the vehicle before toggling status.
      $prev_details = $this->vehicles->getById($vhcl_id);

      // If the vehicle is active in the workcenter.
      if ($vwc_status == ACTIVE)
      {
         // Getting vehicle's status.
         $vhcl_status = $this->vehicles->getTableStatus($vhcl_id);

         // If the vehicle is incative, it must be activated.
         if ($vhcl_status == INACTIVE)
         {
            $this->vehicles->activate($vhcl_id);

            // Worklog should be displayed in all workcentres where vehicle has been registered.
            $workcentres = $this->vehicle_workcentres->getVehicleWorkcentres($vhcl_id);

            // Inserting worklogs of Tbl:vehicle_workcentres.
            $this->send_wlog('vehicles', $vhcl_id, $msg, $this->edit, $this->edit, $workcentres, $prev_details);
         }
      }

      // Else if the vehilce is deactivated in the workcentre.
      else if ($vwc_status == INACTIVE)
      {
         # If the vehicle is inactive in all workcentres, it must be deactivated.
         // So checking for the workcentres where the vehicle is active.
         $workcentres = $this->vehicle_workcentres->getVehicleWorkcentres($vhcl_id);

         // If no such workcentres, the vehicle must be deactivated.
         if (!$workcentres)
         {
            $this->vehicles->deactivate($vhcl_id);

            // Worklog will be a general worklog.            
            // Inserting worklogs of Tbl:vehicle_workcentres.
            $this->send_wlog('vehicles', $vhcl_id, $msg, $this->edit, $this->edit, '', $prev_details);

            // if the vehicle is active in any inactive workcentres, it must be also deactivated. But no worklog will be generated.
            // because the workcentres are inactive.
            $where['vwc_fk_vehicles'] = $vhcl_id;
            $this->vehicle_workcentres->update_where(array('vwc_status' => INACTIVE), $where);
         }
      }

      return TRUE;
   }

   /**
    * The the fuction returns all active workcentres in which the vehicle is not yet registered in it and
    * the user is a member of that workcentres.
    * 
    */
   function eligible_workcentres_for_vehicle()
   {
      $vhcl_id = $_GET['vhcl_id'];

      // Workentres (active/inactive) where the vehicle is available.
      $vhcle_wc = $this->vehicle_workcentres->getVehicleWorkcentres($vhcl_id, '', 'wcntr_id', 'wcntr_id', '', '');

//       echo "<br><br>vhelc : ";print_r($vhcle_wc);
      // Users workcentres
      $user_wc = $this->workcentres->get_workcentres_options($this->user_id, '', 1);
      $user_wc = $this->workcentres->getIdsFromOption($user_wc);


//     echo "<br><br>User : ";print_r($user_wc);
      // Elegible workcentres
      $elg_wc = array_diff($user_wc, $vhcle_wc);

//       echo "<br><br>Eligble : ";print_r($elg_wc);


      $options = $this->workcentres->getOptionFromIds($elg_wc);
      $this->json_options($options, "-- No New Workcentres --");
   }

   /**
    * Callback function for validations.
    * @param type $val
    * @return boolean
    * 
    */
   function is_wc_exist($val)
   {

      $exist = false;
      $unique['vwc_fk_workcentres'] = $this->input->post('vwc_fk_workcentres');
      $unique['vwc_fk_vehicles'] = $this->input->post('vwc_fk_vehicles');

      // If the action is ADD
      if (!$this->input->post('vwc_id'))
      {
         if ($this->vehicle_workcentres->is_exists($unique))
            $exist = TRUE;
      }

      // else if the action is EDIT.
      else
      {
         $id = $this->input->post('vwc_id');
         if ($this->vehicle_workcentres->is_exists($unique, $id))
            $exist = TRUE;
      }

      if ($exist)
      {
         $this->form_validation->set_message('is_wc_exist', 'The vehicle has been already registered in the workcentre.');
         return FALSE;
      }
      else
         return TRUE;
   }

}
