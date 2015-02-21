<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Inter_freight_charges_model extends my_model
{

   function __construct()
   {
      parent::__construct();
      $this->loadTable(getTables('inter_freight_charges'));
      $this->p_key = 'ifc_id';
      $this->nameField = '';
      $this->statusField = '';
   }
   
   function index($vhcl_id)
   {
      $this->db->from("$this->table");
      $this->db->where('ifc_fkey_vehicles',$vhcl_id);
      
      $query = $this->db->get();
      $result = $this->substituteWorkcentres($query->result_array());
      return $result;
   }
   
   function substituteWorkcentres($result)
   {
      foreach($result as &$row)
      {
         $row['wcntr_from'] = $this->workcentres->getNameById($row['ifc_fk_workcentres_from']);
         $row['wcntr_to'] = $this->workcentres->getNameById($row['ifc_fk_workcentres_to']);
      }
      return $result;
   }
   
  
   
}
?>