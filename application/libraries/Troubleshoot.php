<?php

if (!defined('BASEPATH'))
   exit('No direct script access allowed');

class Troubleshoot
{

   /**
    *
    * @var type :A message prepended with all worlog messages related to Troubleshoot.
    */
   var $tshoot_wlog_message = '';

   /**
    * 
    * Usage: $this->load->library('troubleshoot', $params);
    * 
    * @param type $params : If any thing to initialize.
    *                        Where $params is array like array('tshoot_wlog_message' => 'some message');
    */
   public function __construct($params = '')
   {
      $this->tshoot_wlog_message = isset($params['tshoot_wlog_message']) ? $params['tshoot_wlog_message'] : '<span class="tshoot_wlog">Troubleshoot: </span>';
   }

   /**
    * To find logical errors related to Tbl: vehicles_employees.
    * 
    *
    * @param type $pre_msg    : If any message to preppend with worklog message described in the function.
    * @param type $msg        : If any message to replace with worklog message described in the function.
    * @param type $post_msg   : If any message to append with worklog message described in the function.
    */
   function delete_illegal_labours($pre_msg = '', $msg = '', $post_msg = '')
   {

      // How to call this function: visit controller vehicles/index.

      /*

        +----------------------------------------------------------------------------------------------+
        |                                      ILLEGAL LABOURS:                                        |
        +----------------------------------------------------------------------------------------------+
        | 1. |  Labours are not available in any of the workcentres where vehicle is available.        |
        +----------------------------------------------------------------------------------------------+
        | 2. |  Labours in which they are not a driver/loader.                                         |
        +----------------------------------------------------------------------------------------------+

       */


      // Delete all labours they are not available in any of the workcentres where vehicle is available.
      $this->delete_all_illegal_labours_from_vehicle_1($pre_msg, $msg, $post_msg);

      // Delete all labours they are not a driver/loader.
      $this->delete_all_illegal_labours_from_vehicle_2($pre_msg, $msg, $post_msg);
   }

   /**
    * All the labours (Drivers and Loaders) in a vehicle must be available in any of the workcentres where the vehicle is available.
    * Otherwise it is a logical error. So this function finding such labours (Illegal Labours) 
    * and delete them from the labours list of the vehicle.
    * 
    * @return type
    */
   function delete_all_illegal_labours_from_vehicle_1($msg_1 = '', $msg_2 = '', $msg_3 = '')
   {

      $obj = & get_instance();
      $obj->load->model('vehicles_model', 'vehicles');
      $obj->load->model('vehicles_employees_model', 'vehicles_employees');
      $obj->load->model('vehicle_workcentres_model', 'vehicle_workcentres');
      $obj->load->model('employee_work_centre_model', 'employee_work_centre');

      // Getting all vehicles.
      $vehicles = $obj->vehicles->getIds();

      if (!$vehicles)
         return;

      foreach ($vehicles as $vhcl_id)
      {
         // All active and inactive status will be taken. Because inactive may be activated later.
         $vhcl_workcentres = $obj->vehicle_workcentres->getVehicleWorkcentres($vhcl_id, '', 'wcntr_id', 'wcntr_id', '', '');

         $labours = $obj->vehicles_employees->getCurrentLabours($vhcl_id);
         foreach ($labours as $emp_id)
         {
            // Only active status will be taken.
            $emp_workcentres = $obj->employee_work_centre->getEmployeesWorkcentres($emp_id);

            // Checking is the employee is available in any of the workcentres where vehicle is available.
            if (!array_intersect($vhcl_workcentres, $emp_workcentres))
            {
               // The employee must be deleted from the Tbl: vehicles_employees.
               $where['vemp_fk_employees'] = $emp_id;
               $where['vemp_fk_vehicles'] = $vhcl_id;
               $vemp_id = $obj->vehicles_employees->delete_where2($where);

               // Worklog should be displayed in all workcentres where vehicle has been registered.
               $workcentres = $obj->vehicle_workcentres->getVehicleWorkcentres($vhcl_id);

               $emp_name = $obj->employees->getNameById($emp_id);
               $emp_category = $obj->employees->getFieldById($emp_id, 'emp_category');
               $vhcl_no = $obj->vehicles->getNameById($vhcl_id);
               $cat_text = $emp_category == 4 ? 'Driver' : (($emp_category == 5) ? 'Loader' : 'Logical Error');

               // Message related to the worklog.
               $msg = '';

               $msg .= $msg_1 ? $msg_1 : '';

               if (!$msg_2)
               {
                  $msg = "The <span class='wlg_name'>$cat_text: $emp_name</span>";
                  $msg .= " has been deleted from the labours list of the vehicle ";
                  $msg .= " <span class='wlg_name'>$vhcl_no</span>, because he is not available in any workcentres where the ";
                  $msg .= 'vehicle is available';
               }
               else
                  $msg .= $msg_2;

               $msg .= $msg_3 ? $msg_3 : '; By the system troubleshooter.';

               $msg = "$this->tshoot_wlog_message $msg";

               // Inserting worklogs of Tbl: vehicles_employees.
               $obj->send_wlog('vehicles_employees', $vemp_id, $msg, $obj->delete, $obj->delete, $workcentres);
            }
         }
      }
   }

   /**
    * All the labours in a vehicle must be either a driver or a loader.
    * Otherwise it is a logical error. So this function finding such labours (Illegal Labours) and delete them from the vehicle.
    * 
    * @return type
    */
   function delete_all_illegal_labours_from_vehicle_2($msg_1 = '', $msg_2 = '', $msg_3 = '')
   {

      $obj = & get_instance();
      $obj->load->model('vehicles_model', 'vehicles');
      $obj->load->model('vehicles_employees_model', 'vehicles_employees');
      $obj->load->model('vehicle_workcentres_model', 'vehicle_workcentres');

      // Getting all vehicles employees.
      $vehicles_employees = $obj->vehicles_employees->getVehiclesEmployees();

      foreach ($vehicles_employees as $row)
      {
        
         
         // If the labour is neither a driver nor a labour OR Inactive.
         if ((($row['emp_category'] != 4) && ($row['emp_category'] != 5))  || $row['emp_status'] == INACTIVE)
         {         
            $vemp_id = $row['vemp_id'];

            // Deleting employee from vehicle.
            $obj->vehicles_employees->remove($vemp_id);

            // Worklog should be displayed in all active workcentres where the vehicle has been registered.
            $workcentres = $obj->vehicle_workcentres->getVehicleWorkcentres($row['vhcl_id']);

            $emp_name = $row['emp_name'];
            $emp_category = $row['emp_category'];
            $vhcl_no = $row['vhcl_no'];
            $cat_text = ($emp_category == 4) ? 'Driver' : (($emp_category == 5) ? 'Loader' : 'Logical Error');

            // Message related to the worklog.
            $msg = '';

            $msg .= $msg_1 ? $msg_1 : '';

            if (!$msg_2)
            {
               $msg .= "The <span class='wlg_name'>$cat_text: $emp_name</span>";
               $msg .= " has been deleted from the labours list of the vehicle";
               $msg .= " <span class='wlg_name'>$vhcl_no</span>";
               $msg .= ", because he is neither a driver nor a loader or an 'Inactive' labour";
            }
            else
               $msg .= $msg_2;

            $msg .= $msg_3 ? $msg_3 : '; By the system troubleshooter.';

            $msg = "$this->tshoot_wlog_message $msg";

            // Inserting worklogs of Tbl: vehicles_employees.
            $obj->send_wlog('vehicles_employees', $vemp_id, $msg, $obj->delete, $obj->delete, $workcentres);
         }
      }
   }

}

?>