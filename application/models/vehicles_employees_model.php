<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Vehicles_employees_model extends my_model
{

   function __construct()
   {
      parent::__construct();
      $this->loadTable(getTables('vehicles_employees'));
      $this->p_key = 'vemp_id';
      $this->nameField = '';
      $this->statusField = '';
   }

   function index($vhcl_id)
   {
      $this->db->from("$this->table,employees");
      $this->db->where('vemp_fk_vehicles', $vhcl_id);
      $this->db->where('emp_id = vemp_fk_employees');

      $this->db->order_by('emp_category', 'asc');
      $this->db->order_by('emp_name', 'asc');

      $result = $this->db->get();
      return $result->result_array();
   }

   function getVehiclesEmployees($emp_id = '', $vhcl_id = '')
   {
      $this->db->from("$this->table,vehicles,employees");

      if ($emp_id)
         $this->db->where('vemp_fk_employees', $emp_id);

      if ($vhcl_id)
         $this->db->where('vemp_fk_vehicles', $vhcl_id);

      $this->db->where('vhcl_id = vemp_fk_vehicles');
      $this->db->where('emp_id = vemp_fk_employees');

      $this->db->order_by('vhcl_no');

      $result = $this->db->get();
      return $result->result_array();
   }

   /**
    * Function return array of p_key of employees, those are related to the vehicle (Drivers/Loaders).
    * 
    * @param type $vhcl_id
    * @return type
    */
   function getCurrentLabours($vhcl_id)
   {
      $this->db->from($this->table);
      $this->db->select('vemp_fk_employees');
      $this->db->where('vemp_fk_vehicles', $vhcl_id);
      $result = $this->db->get();
      $result = $result->result_array();
      $emps = array();
      foreach ($result as $row)
         $emps[] = $row['vemp_fk_employees'];
      return $emps;
   }

   function getLaboursInVehicleUnderWorkcentre($wcntr_id, $vhcl_id, $labour_type = '', $option = TRUE, $emp_status = ACTIVE)
   {
      $this->db->from("$this->table,employees,employee_work_centre");

      $this->db->where('vemp_fk_vehicles', $vhcl_id);
      $this->db->where('emp_id = vemp_fk_employees');

      if ($labour_type) // Driver/Loader
         $this->db->where('emp_category', $labour_type);

      if ($emp_status)
         $this->db->where('emp_status', $emp_status);

      if (is_array($wcntr_id))
      {
         $str = $this->array_query($wcntr_id, 'ewp_fk_workcentres');
         $this->db->where($str);
      }
      else
      {
         $this->db->where('ewp_fk_workcentres', $wcntr_id);
      }


      $this->db->where('ewp_fk_auth_users = emp_id');

      $this->db->order_by('emp_name', 'asc');
      $query = $this->db->get();
      $result = $query->result_array();

      if ($option)
         $result = $this->make_options($result, 'emp_id', 'emp_name');

      return $result;
   }

   function getDrivers($vhcl_id, $options = false, $emp_status = ACTIVE)
   {
      $this->db->from("$this->table,employees");
      $this->db->where('vemp_fk_vehicles', $vhcl_id);
      $this->db->where('emp_id = vemp_fk_employees');
      $this->db->where('emp_category', 4); //Drivers.
      if ($emp_status)
         $this->db->where('emp_status', $emp_status);
      $this->db->order_by('emp_name', 'asc');

      $result = $this->db->get();
      $result = $result->result_array();

      if ($options)
         $result = $this->make_options($result, 'emp_id', 'emp_name');

      return $result;
   }

   function getLoaders($vhcl_id, $options = false, $emp_status = ACTIVE)
   {
      $this->db->from("$this->table,employees");
      $this->db->where('vemp_fk_vehicles', $vhcl_id);
      $this->db->where('emp_id = vemp_fk_employees');
      $this->db->where('emp_category', 5); //Loaders.
      if ($emp_status)
         $this->db->where('emp_status', $emp_status);
      $this->db->order_by('emp_name', 'asc');

      $result = $this->db->get();
      $result = $result->result_array();

      if ($options)
         $result = $this->make_options($result, 'emp_id', 'emp_name');

      return $result;
   }

   function getNewLabours($emp_category, $labours_workcentres, $vhcl_id, $options = TRUE, $emp_status = ACTIVE)
   {
      if (!$labours_workcentres)
         return array();

      $this->db->from("employees,employee_work_centre");

      $str = $this->array_query($labours_workcentres, 'ewp_fk_workcentres');
      $this->db->where($str);


      $this->db->where("ewp_fk_auth_users = emp_id");

      $this->db->where('emp_category', $emp_category);

      if ($emp_status)
         $this->db->where('emp_status', $emp_status);


      // The employees those are added already to the vehicle should not be included in new list.
      $subQuery = "(SELECT vemp_fk_employees FROM $this->table WHERE vemp_fk_vehicles = $vhcl_id)";
      $this->db->where("emp_id NOT IN " . $subQuery);

      $this->db->order_by('emp_name', "asc");

      $result = $this->db->get();
      $result = $result->result_array();

      if ($options)
         $result = $this->make_options($result, 'emp_id', 'emp_name');

      return $result;
   }

   function getDefaultLabours($vhcl_id, $labours_category = '')
   {
      $this->db->from("$this->table,employees");
      $this->db->where('vemp_fk_vehicles', $vhcl_id);
      $this->db->where('vemp_is_default', 1);
      $this->db->where('emp_id = vemp_fk_employees');

      $result = $this->db->get();
      $default_labours = $result->result_array();
      if ($default_labours)
      {
         if (!$labours_category)
            return $default_labours;

         if ($default_labours)
            foreach ($default_labours as $default_labour)
               if (($default_labour['emp_category'] == $labours_category))
                  return $default_labour['emp_id']; // There will be only one default driver/loader, so returns when found one.
               
      }
      return '';
   }

   function setDefaultLabour($vhcl_id, $emp_id)
   {
      $where['vemp_fk_employees'] = $emp_id;
      $where['vemp_fk_vehicles'] = $vhcl_id;
      $data['vemp_is_default'] = 1; // Default labour.
      $id = $this->vehicles_employees->update_where($data, $where);
      return $id;
   }

   function unsetDefaultLabour($vhcl_id, $emp_id)
   {
      $where['vemp_fk_employees'] = $emp_id;
      $where['vemp_fk_vehicles'] = $vhcl_id;
      $data['vemp_is_default'] = 2; // Not a default labour.
      $id = $this->vehicles_employees->update_where($data, $where);
      return $id;
   }

}

?>