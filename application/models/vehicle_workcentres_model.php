<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Vehicle_workcentres_model extends my_model
{

   function __construct()
   {
      parent::__construct();
      $this->loadTable(getTables('vehicle_workcentres'));
      $this->p_key = 'vwc_id';
      $this->nameField = '';
      $this->statusField = 'vwc_status';
   }

   function get_vehicle_workcentres_status()
   {
      return $this->get_status();
   }

   function get_vehicles_in_workcentre($wcntr_id, $vhcl_ownership = '', $vhcl_status = 1, $vwc_status = 1)
   {

      if (!$wcntr_id)
         return;

      $this->db->from("$this->table,vehicles");
      $this->db->select("DISTINCT(vhcl_id),vehicles.*,$this->table.*");
      
      if (is_array($wcntr_id))
      {
         $str = $this->array_query($wcntr_id, 'vwc_fk_workcentres');
         $this->db->where($str);
      }
      else
      {
         $this->db->where('vwc_fk_workcentres', $wcntr_id);
      }
      
      if ($vhcl_ownership)
         $this->db->where('vhcl_ownership', $vhcl_ownership);

      if ($vhcl_status)
         $this->db->where('vhcl_status', $vhcl_status);

      if ($vwc_status)
         $this->db->where('vwc_status', $vwc_status);
      
      $this->db->where('vhcl_id = vwc_fk_vehicles');
      
      $this->db->order_by('vhcl_no');
      $result = $this->db->get();
      $result = $result->result_array();
      
      //echo "<br>".$this->db->last_query()."<br>";
      
      return $result;
   }

   /**
    * Function return array of workcentres where vehicle is available.
    * @param type $vhcl_id
    * @return type
    */
   function getVehicleWorkcentres($vhcl_id, $firm_id = '', $field = 'wcntr_id', $order_by = 'wcntr_id', $vwc_status = 1, $wcntr_status = 1)
   {
      $this->db->from('workcentres,' . $this->table);
      if ($field != 'All')
         $this->db->select($field);
      $this->db->where('vwc_fk_vehicles', $vhcl_id);
      if ($vwc_status)
         $this->db->where('vwc_status', $vwc_status); // Active in workcentre.
      if ($wcntr_status)
         $this->db->where('wcntr_status', $wcntr_status); // Active workcentres.
      $this->db->where('wcntr_id = vwc_fk_workcentres');
      if ($firm_id)
         $this->db->where('wcntr_fk_firms', $firm_id);
      $this->db->order_by($order_by);
      $result = $this->db->get();
      $data = $result->result_array();

      if ($field == 'All')
         return $data;
      else
      {
         $arr = array();
         foreach ($data as $val)
            $arr[] = $val[$field];
         return $arr;
      }
   }

   /**
    * Function returns the workentres where both the user and vehicles are available.
    * 
    * 
    * @param type $user_id
    * @param type $vhcl_id
    * @param type $firm_id
    * @param type $field
    * @param type $order_by
    * @param type $vwc_status
    * @param type $wcntr_status
    * @return type
    */
   function getVehicleWorkcentresByUser($user_id, $vhcl_id, $firm_id = '', $field = 'wcntr_id', $order_by = 'wcntr_id', $vwc_status = 1, $wcntr_status = 1)
   {
      $this->db->from("workcentres,$this->table,employee_work_centre");

      if ($field != 'All')
         $this->db->select($field);
      else
         $this->db->select("workcentres.*, $this->table.*");


      $this->db->where('ewp_fk_auth_users', $user_id);
      $this->db->where('ewp_fk_workcentres = vwc_fk_workcentres');
      $this->db->where('vwc_fk_vehicles', $vhcl_id);

      if ($vwc_status)
         $this->db->where('vwc_status', $vwc_status); // Active in workcentre.
      if ($wcntr_status)
         $this->db->where('wcntr_status', $wcntr_status); // Active workcentres.
      $this->db->where('wcntr_id = vwc_fk_workcentres');
      if ($firm_id)
         $this->db->where('wcntr_fk_firms', $firm_id);
      $this->db->order_by($order_by);
      $result = $this->db->get();
      $data = $result->result_array();

      if ($field == 'All')
         return $data;
      else
      {
         $arr = array();
         foreach ($data as $val)
            $arr[] = $val[$field];
         return $arr;
      }
   }

   function index($vhcl_id, $vwc_status = '', $user_wcntrs = '')
   {
      $this->db->from("$this->table,workcentres");
      $this->db->select("$this->table.*,wcntr_name");
      $this->db->where('vwc_fk_vehicles', $vhcl_id);
      $this->db->where('wcntr_id = vwc_fk_workcentres');
      $this->db->where('wcntr_status', ACTIVE);

      if ($vwc_status)
         $this->db->where('vwc_status', $vwc_status);

      if ($user_wcntrs)
      {
         $str = $this->array_query($user_wcntrs, 'vwc_fk_workcentres');
         $this->db->where($str);
      }

      $this->db->order_by('wcntr_name', 'asc');
      $result = $this->db->get();

      return $result->result_array();
   }

   function reports_Cost($wcntr_id, $from, $to)
   {
      $this->db->from("vehicles,$this->table,workcentres");

      $select = ' vwc_id as ID, ';
      $select .= " '$this->table' as TBL, ";
      $select .= " vwc_date as DATE, ";
      $select .= " 2 as ACC_TYPE, ";       //  ACC_TYPE = 1 is Income/Credit. ACC_TYPE = 2 is Expense/Debt.
      $select .= " CONCAT('Vehicle: ',CONCAT(vhcl_no,' Cost.')) as DESCRIPTION, ";

      // Category for Balance Sheet. It must match with any of the 'Sub_category' described in my_controller/get_BS_Categories()
      $select .= " 'Debtors' as BS, ";

      // Category for Profit & Loss.
      $select .= " '' as PL, ";

      $select .= 'wcntr_name as WORKCENTRE, ';
      $select .= 'vwc_cost as AMOUNT';

      $this->db->select($select, FALSE);

      if (is_array($wcntr_id))
      {
         $str = $this->array_query($wcntr_id, 'vwc_fk_workcentres');
         $this->db->where($str);
      }
      else
         $this->db->where("vwc_fk_workcentres", $wcntr_id);

      if ($from)
         $this->db->where('vwc_date >= ', getSqlDate($from));

      if ($to)
         $this->db->where('vwc_date <= ', getSqlDate($to));


      $this->db->where("vwc_cost > ", 0);
      $this->db->where("vhcl_id = vwc_fk_vehicles");
      $this->db->where("wcntr_id = vwc_fk_workcentres");

      $this->db->order_by('vwc_date', 'asc');
      $this->db->order_by('wcntr_name', 'asc');
      $query = $this->db->get();
      $result = $query->result_array();

      return $result;
   }

   function reports_OB($wcntr_id, $from, $to)
   {
      $this->db->from("vehicles,$this->table,workcentres");

      $bs = " (CASE ";
      $bs .= " when vwc_ob_mode = 1 then 'Creditors' ";
      $bs .= " when vwc_ob_mode = 2 then 'Debtors' ";
      $bs .= " else 'UNKNOWN' ";
      $bs .= " END) ";



      $select = ' vwc_id as ID, ';
      $select .= " '$this->table' as TBL, ";
      $select .= " vwc_date as DATE, ";
      $select .= " vwc_ob_mode as ACC_TYPE, ";       //  ACC_TYPE = 1 is Income/Credit. ACC_TYPE = 2 is Expense/Debt.
      $select .= " CONCAT('Vehicle: ',CONCAT(vhcl_no,' O.B.')) as DESCRIPTION, ";

      // Category for Balance Sheet. It must match with any of the 'Sub_category' described in my_controller/get_BS_Categories()
      $select .= " $bs as BS, ";

      // Category for Profit & Loss.
      $select .= " '' as PL, ";

      $select .= 'wcntr_name as WORKCENTRE, ';
      $select .= 'vwc_ob as AMOUNT';

      $this->db->select($select, FALSE);

      if (is_array($wcntr_id))
      {
         $str = $this->array_query($wcntr_id, 'vwc_fk_workcentres');
         $this->db->where($str);
      }
      else
         $this->db->where("vwc_fk_workcentres", $wcntr_id);

      if ($from)
         $this->db->where('vwc_date >= ', getSqlDate($from));

      if ($to)
         $this->db->where('vwc_date <= ', getSqlDate($to));


      $this->db->where("vwc_ob > ", 0);
      $this->db->where("vhcl_id = vwc_fk_vehicles");
      $this->db->where("wcntr_id = vwc_fk_workcentres");

      $this->db->order_by('vwc_date', 'asc');
      $this->db->order_by('wcntr_name', 'asc');
      $query = $this->db->get();
      $result = $query->result_array();

      return $result;
   }

   function reports($wcntr_id, $from, $to)
   {
      $data = $this->reports_OB($wcntr_id, $from, $to);
      $data = array_merge($data, $this->reports_Cost($wcntr_id, $from, $to));

      return $data;
   }

}

?>