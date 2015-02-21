<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require 'my_controller.php';

class Vehicles_employees extends My_controller
{

   function __construct()
   {
      parent::__construct();

      $this->load->model('vehicles_model', 'vehicles');
      $this->load->model('vehicles_employees_model', 'vehicles_employees');
      $this->load->model('vehicle_workcentres_model', 'vehicle_workcentres');

      // Determining is the logged in user allowed to go forward with the current action.
      $this->isAllowed();

      $this->table = 'vehicles_employees';
      $this->p_key = 'vemp_id';
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
      $vhcl_id = $input['vemp_fk_vehicles'];
      $emp_id = $input['vemp_fk_employees'];
      $vhcl_no = $this->vehicles->getNameById($vhcl_id);
      $emp_name = $this->employees->getNameById($emp_id);
      $emp_deatails = $this->employees->getById($emp_id);
      $emp_category = $emp_deatails['emp_category'];
      $emp_cat_txt = ($emp_category == 4) ? 'Driver' : (($emp_category == 5) ? 'Loader' : 'Logical Error');
      $def_cat_text = ($emp_category == 4) ? 'Default Driver' : (($emp_category == 5) ? 'Default Loader' : 'Logical Error');

      #-------------------------------------------------------------------------------------------#
      #    A vehicle can have only one 'default' driver and one 'default' loader.                 #
      #    So when setting a new default labour, you should remove the existing default labour.   #
      #-------------------------------------------------------------------------------------------#
      // if the labour is the default driver/loader (ie:- $input['vemp_is_default'] = 1), before inserting data,
      // it must be unset previous default driver/labour(ie:- Make previous labours 'vemp_is_default' = 2).    
      if ($input['vemp_is_default'] == 1)
      {
         // Checking is there any previous default driver/loader.
         if ($prev_default_labour = $this->vehicles_employees->getDefaultLabours($vhcl_id, $emp_deatails['emp_category']))
            $this->unsetDefaultLabour($vhcl_id, $prev_default_labour);
      }

      // Inserting data to Tbl:vehicles_employees
      $vemp_id = $this->vehicles_employees->insert($input);


      // Worklog should be displayed in all workcentres where the vehicle has been registered.
      $workcentres = $this->vehicle_workcentres->getVehicleWorkcentres($vhcl_id);

      // Message related to the worklog.
      $msg = 'A new <span class="wlg_name">' . $emp_cat_txt . ': ' . $emp_name . '</span> has been joined in the vehicle';
      $msg .= ' <span class="wlg_name">' . $vhcl_no . '</span>';
      if ($input['vemp_is_default'] == 1) // If the default labour
         $msg .= ' and set him as the <span class="wlg_name">' . $def_cat_text . '</span> of the vehicle';
      $msg .= '.';

      // Inserting worklogs of Tbl: vehicles_employees.
      $this->send_wlog($this->table, $vemp_id, $msg, $this->add, $this->add, $workcentres);


      if ($vemp_id)
         echo 1;
      else
         echo $this->formatePopupError('Data couldn\'t insert !');
   }

   function unsetDefaultLabour($vhcl_id, $emp_id)
   {
      $emp_details = $this->employees->getById($emp_id);
      $emp_category = $emp_details['emp_category'];
      $emp_name = $emp_details['emp_name'];
      $cat_text = $emp_category == 4 ? 'Driver' : (($emp_category == 5) ? 'Loader' : 'Logical Error');
      $def_cat_text = $emp_category == 4 ? 'Default Driver' : (($emp_category == 5) ? 'Default Loader' : 'Logical Error');
      $vhcl_no = $this->vehicles->getNameById($vhcl_id);

      // Worklog should be displayed in all workcentres where the vehicle has been registered.
      $workcentres = $this->vehicle_workcentres->getVehicleWorkcentres($vhcl_id);
      $vemp_id = $this->vehicles_employees->unsetDefaultLabour($vhcl_id, $emp_id);
      $msg = "The <span class='wlg_name'>$cat_text: $emp_name</span>";
      $msg .= " has been unset as <span class='wlg_name'>$def_cat_text</span>";
      $msg .= " from vehicle <span class='wlg_name'>$vhcl_no</span>.";

      // Inserting worklogs of Tbl:vehicles_employees .
      $this->send_wlog($this->table, $vemp_id, $msg, $this->edit, $this->edit, $workcentres);
   }

   function beforeDelete()
   {
      // Checking is the current task is enabled for the user
      $this->isAllowedTask("vehicles/edit");

      $vhcl_id = $_GET['vhcl_id'];
      $drivers = $this->vehicles_employees->getDrivers($vhcl_id);
      $loaders = $this->vehicles_employees->getLoaders($vhcl_id);

      $details['drivers'] = $this->getPopupDesign('DRIVERS', $drivers, 'delete');
      $details['loaders'] = $this->getPopupDesign('LOADERS', $loaders, 'delete');

      echo json_encode($details);
      return;
   }

   function delete()
   {
      // Checking is the current task is enabled for the user
      $task = taskEnabled('vehicles/edit');
      if ($task != 1)
      {
         echo $task;
         return;
      }

      // Recieving input 
      $input = $this->get_inputs(); // Array ( [vemp_ids] => Array ( [0] => 9 [1] => 11 ) )

      foreach ($input['vemp_ids'] as $vemp_id)
      {
         $emp_id = $this->vehicles_employees->getFieldById($vemp_id, 'vemp_fk_employees');
         $vhcl_id = $this->vehicles_employees->getFieldById($vemp_id, 'vemp_fk_vehicles');
         $emp_name = $this->employees->getNameById($emp_id);
         $emp_category = $this->employees->getFieldById($emp_id, 'emp_category');
         $vhcl_no = $this->vehicles->getNameById($vhcl_id);
         $cat_text = $emp_category == 4 ? 'Driver' : (($emp_category == 5) ? 'Loader' : 'Logical Error');

         // Deleting Labour.
         $this->vehicles_employees->remove($vemp_id);

         // Worklog should be displayed in all workcentres where the vehicle has been registered.
         $workcentres = $this->vehicle_workcentres->getVehicleWorkcentres($vhcl_id);

         $msg = "The <span class='wlg_name'>$cat_text: $emp_name</span>";
         $msg .= " has been deleted from the";
         $msg .= " vehicle <span class='wlg_name'>$vhcl_no</span>.";

         // Inserting worklogs of Tbl:vehicles_employees .
         $this->send_wlog($this->table, $vemp_id, $msg, $this->delete, $this->delete, $workcentres);
      }

      echo 1;
      return;
   }

   function beforeEdit()
   {
      // Checking is the current task is enabled for the user
      $this->isAllowedTask("vehicles/edit");

      $vhcl_id = $_GET['vhcl_id'];
      $drivers = $this->vehicles_employees->getDrivers($vhcl_id);
      $loaders = $this->vehicles_employees->getLoaders($vhcl_id);

      $details['drivers'] = $this->getPopupDesign('DRIVERS', $drivers, 'edit');
      $details['loaders'] = $this->getPopupDesign('LOADERS', $loaders, 'edit');

      echo json_encode($details);
      return;
   }

   function getPopupDesign($labour_cat_text, $labours, $action)
   {
      $design = '<table class="unt_tbl">';
      $design .= '<tbody>';
      $design .= '<tr>';
      $design .= '<th class="pop_labour" colspan="2" style="text-align:center;">' . $labour_cat_text . '</th>';
      $design .= '</tr>';

      if ($labours)
      {
         foreach ($labours as $lbr)
         {

            $design .= '<tr>';
            $design .= "<td>";
            if ($action == 'edit')
            {
               $checked = ($lbr['vemp_is_default'] == 1) ? 'checked="checked"' : '';
               $design .= "<input type='hidden' name='vemp_id' class='vemp_id' value='$lbr[vemp_id]' >";
               $design .= "<input type='radio' name='$labour_cat_text' class='$labour_cat_text' value='$lbr[vemp_is_default]' $checked >";
            }
            else if ($action == 'delete')
               $design .= "<input type='checkbox' class='vemp_id' value='$lbr[vemp_id]' >";

            $design .= "</td>";
            $design .= '<td>' . $lbr['emp_name'] . '</td>';
            $design .= '</tr>';
         }
      }
      else
         $design .= '<tr><td colspan="2" style="text-align:center;">NO ' . $labour_cat_text . ' FOUND</td></tr>';

      $design .= '</tbody>';
      $design .= '</table>';

      return $design;
   }

   function edit()
   {
      // Checking is the current task is enabled for the user
      $task = taskEnabled('vehicles/edit');
      if ($task != 1)
      {
         echo $task;
         return;
      }

      // Recieving input 
      $input = $this->get_inputs();

      if ($input['default_driver'])
      {
         $this->setDefaultLabour($input['default_driver'], 4); // emp_category = 4 is 'Drivers'.
      }

      if ($input['default_loader'])
      {
         $this->setDefaultLabour($input['default_loader'], 5); // emp_category = 5 is 'Loaders'.
      }

      echo 1;
   }

   function setDefaultLabour($vemp_id, $emp_category)
   {
      $vhcl_id = $this->vehicles_employees->getFieldById($vemp_id, 'vemp_fk_vehicles');

      // Checking is there any previous default driver/loader in the vehicle.
      if ($prev_default_labour = $this->vehicles_employees->getDefaultLabours($vhcl_id, $emp_category))
         $this->unsetDefaultLabour($vhcl_id, $prev_default_labour);

      // Setting new default labour.
      $this->vehicles_employees->save(array('vemp_is_default' => 1), $vemp_id);

      $emp_id = $this->vehicles_employees->getFieldById($vemp_id, 'vemp_fk_employees');
      $emp_name = $this->employees->getNameById($emp_id);
      $vhcl_no = $this->vehicles->getNameById($vhcl_id);
      $cat_text = $emp_category == 4 ? 'Driver' : (($emp_category == 5) ? 'Loader' : 'Logical Error');
      $def_cat_text = $emp_category == 4 ? 'Default Driver' : (($emp_category == 5) ? 'Default Loader' : 'Logical Error');

      // Worklog should be displayed in all workcentres where the vehicle has been registered.
      $workcentres = $this->vehicle_workcentres->getVehicleWorkcentres($vhcl_id);

      $msg = "The <span class='wlg_name'>$cat_text: $emp_name</span>";
      $msg .= " has been set as <span class='wlg_name'>$def_cat_text</span>";
      $msg .= " of the vehicle <span class='wlg_name'>$vhcl_no</span>.";

      // Inserting worklogs of Tbl:vehicles_employees .
      $this->send_wlog($this->table, $vemp_id, $msg, $this->edit, $this->edit, $workcentres);
   }

   function getNewLabours()
   {
      $vhcl_id = $_GET['vhcl_id'];
      $emp_category = $_GET['emp_category'];
      $cat_text = ($emp_category == 4) ? 'Drivers' : 'Loaders';

      // Workcentres where vehicle is available.
      $vhcl_workcentres = $this->vehicle_workcentres->getVehicleWorkcentres($vhcl_id);

      // Workcentres where user is available.
      $user_workcentres = $this->workcentres->get_workcentres_options($this->user_id, '', 1);
      $user_workcentres = $this->workcentres->getIdsFromOption($user_workcentres);

      // User can add only labours whome are available in the workcentres where both user & the vehicle is available.
      $labours_workcentres = array_intersect($vhcl_workcentres, $user_workcentres);

      // Getting active registrations under the firm.
      $data = $this->vehicles_employees->getNewLabours($emp_category, $labours_workcentres, $vhcl_id);
      $this->json_options($data, "-- No $cat_text --");
   }

   function get_labours_option()
   {
      $wcntr_id = $this->input->post('wcntr_id');
      $vhcl_id = $this->input->post('vhcl_id');
      $all_labours = $this->input->post('all_labours') ? : 2; // 1 => Show All drivers/Loaders, 2=>Show only drivers/Loaders in the selected vehicle.
      $emp_categroy = $this->input->post('emp_categroy'); // Emp_category

      if (!$wcntr_id || !$vhcl_id | !$all_labours)
         return;

      if ($all_labours == 1) //Show All drivers/Loaders in the workcentre
      {
         $this->load->model('employee_work_centre_model', 'employee_work_centre');
         $data = $this->employee_work_centre->getEmployeesInWorkcentres($wcntr_id, TRUE, $emp_categroy);
      }
      else if ($all_labours == 2) // Show only drivers/Loaders in the selected vehicle and in selected workcentres.
      {
         $data = $this->vehicles_employees->getLaboursInVehicleUnderWorkcentre($wcntr_id, $vhcl_id, $emp_categroy);
      }

      $default_labour = $this->vehicles_employees->getDefaultLabours($vhcl_id, $emp_categroy);

      if ($data)
      {
         if ($emp_categroy == DRIVER)
            echo get_options2($data, $default_labour, TRUE, 'Select Driver');
         else if ($emp_categroy == LOADER)
            echo get_options2($data, $default_labour, TRUE, 'Select Loader');
      }
      else
      {
         if ($emp_categroy == DRIVER)
            echo '<option value="">No Drivers</option>';
         else if ($emp_categroy == LOADER)
            echo '<option value="">No Loaders</option>';
      }
   }

}

?>