<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Freight_charges_model extends my_model
{

   function __construct()
   {
      parent::__construct();
      $this->loadTable(getTables('freight_charges'));
      $this->p_key = 'fc_id';
      $this->nameField = '';
      $this->statusField = '';
   }

   function index($vhcl_id)
   {
      $this->db->from("$this->table,parties,party_destinations,workcentres");
      $this->db->select("$this->table.*,pty_name,pdst_name,wcntr_name");
      $this->db->where('fc_fk_vehicles', $vhcl_id);
      $this->db->where('pdst_id = fc_fk_party_destinations');
      $this->db->where('pty_id = pdst_fk_parties');
      $this->db->where('wcntr_id = fc_fk_workcentres');
      $result = $this->db->get();
      return $result->result_array();
   }

   /**
    * 
    * @param type $wcntr         : A single workcentre id or array of workcentres like array(1,2,5,6);
    * @param type $pty_id
    * @param type $pty_status
    * @param type $pdst_status
    * @param type $dwc_status
    * @return type
    */
   function getFreeParties($vhcl_id, $wcntr_id, $pty_id = '', $distinct = true, $pty_status = 1, $pdst_status = 1, $dwc_status = 1)
   {
      
      if (!$wcntr_id)
         return array();

      $this->db->from("parties,party_destinations,destination_workcentres");

      if ($distinct)
         $this->db->select("DISTINCT(pty_id),parties.*,party_destinations.*,destination_workcentres.*");
      else
         $this->db->select("parties.*,party_destinations.*,destination_workcentres.*");

      $this->db->where('dwc_fk_workcentres', $wcntr_id);

      if ($pty_id)
         $this->db->where('pty_id', $pty_id);

      if ($pty_status)
         $this->db->where('pty_status', $pty_status);

      if ($pdst_status)
         $this->db->where('pdst_status', $pdst_status);

      if ($dwc_status)
         $this->db->where('dwc_status', $dwc_status);

      $this->db->where('pdst_id = dwc_fk_party_destinations');

      $this->db->where('pty_id = pdst_fk_parties');

      $subQuery = "(SELECT fc_fk_party_destinations FROM $this->table WHERE fc_fk_workcentres = $wcntr_id AND fc_fk_vehicles = $vhcl_id)";
      $this->db->where("pdst_id NOT IN $subQuery");

      $this->db->order_by('pty_name');

      $this->db->order_by('pdst_name');

      $result = $this->db->get();

      $result = $result->result_array();

//      echo "<br>".$this->db->last_query()."<br>";

      return $result;
   }

}

?>