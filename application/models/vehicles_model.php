<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Vehicles_model extends my_model
{

   function __construct()
   {
      parent::__construct();
      $this->loadTable(getTables('vehicles'));
      $this->p_key = 'vhcl_id';
      $this->nameField = 'vhcl_no'; // Don't change its value as 'vhcl_name'.
      $this->statusField = 'vhcl_status';
   }

   function get_vehicle_status()
   {
      return $this->get_status();
   }

   function get_ownership_values()
   {
      return array(1 => 'Ours', 2 => 'Others');
   }

   function index($input, $workcentres, $wlog_ref_id, $num_rows = false)
   {
      $this->db->from($this->table . ' , vehicle_workcentres');

      $this->db->select("DISTINCT(vhcl_id),vehicles.*", false);

      #WHERE

      if ($wlog_ref_id)
         $this->db->where($this->p_key, $wlog_ref_id);

      if (ifSetInput($input, 'vhcl_no'))
      {
         $this->db->where('vhcl_no LIKE', ifSetInput($input, 'vhcl_no') . '%');
      }

      if (ifSetInput($input, 'vhcl_name'))
      {
         $this->db->where('vhcl_name LIKE', ifSetInput($input, 'vhcl_name') . '%');
      }

      if (ifSetInput($input, 'vhcl_status'))
      {
         $this->db->where('vhcl_status', ifSetInput($input, 'vhcl_status'));
      }

      if (ifSetInput($input, 'vhcl_ownership'))
      {
         $this->db->where('vhcl_ownership', ifSetInput($input, 'vhcl_ownership'));
      }

      if (ifSetInput($input, 'f_vhcl_date'))
      {
         $this->db->where('vhcl_date >= ', getSqlDate(ifSetInput($input, 'f_vhcl_date')));
      }

      if (ifSetInput($input, 't_vhcl_date'))
      {
         $this->db->where('vhcl_date <= ', getSqlDate(ifSetInput($input, 't_vhcl_date')));
      }


      // User selected atleast one workcentre.
      if (ifSetInput($input, 'vwc_fk_workcentres'))
      {
         $str = $this->array_query(ifSetInput($input, 'vwc_fk_workcentres'), 'vwc_fk_workcentres');
         $this->db->where($str);
      }
      else if (is_array($workcentres) && $workcentres)
      {
         $workcentres = array_flip($workcentres);
         $str = $this->array_query($workcentres, 'vwc_fk_workcentres');
         $this->db->where($str);
      }
      
      
      if (ifSetInput($input, 'vwc_status'))
      {
         $this->db->where('vwc_status', ifSetInput($input, 'vwc_status'));
      }
      
      $this->db->where('vwc_fk_vehicles = vhcl_id');


      if (ifSetInput($input, 'driver'))
         $this->db->where('vemp_fk_employees', $input['driver']);

      if (ifSetInput($input, 'loader'))
         $this->db->where('vemp_fk_employees', $input['loader']);

      $this->db->join('vehicles_employees', 'vemp_fk_vehicles = vhcl_id', 'left');
      

      $this->db->order_by('vhcl_no', 'asc');
      $this->db->order_by('vhcl_name', 'asc');

      if ($num_rows)
      {
         $query = $this->db->get();
         return count($query->result_array());
      }


      if ($input['PER_PAGE'])
         $query = $this->db->get('', $input['PER_PAGE'], $input['offset']);
      else
         $query = $this->db->get();
      
      
      $result = $query->result_array();

//        echo "<br>".$this->db->last_query();echo "<br>";

      return $result;
   }
   
   

   

}

?>