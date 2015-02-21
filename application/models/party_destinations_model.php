<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Party_destinations_model extends my_model
{

   function __construct()
   {
      parent::__construct();
      $this->loadTable(getTables('party_destinations'));
      $this->p_key = 'pdst_id';
      $this->nameField = 'pdst_name';
      $this->statusField = 'pdst_status';
   }

   function getCat($index = '')
   {
      $cat = array(1 => 'Supplier', 2 => 'Customer', 3 => 'Splr cum Cstmr'); // $cat[3] is for both Supplier and Customer.

      if ($index)
         return $cat[$index];
      return $cat;
   }

   /**
    * 
    * @param type $wcntr         : A single workcentre id or array of workcentres like array(1,2,5,6);
    * @param type $pty_id        : A single party id or array of parties like array(1,2,5,6);
    * @param type $pty_status    : Status of the party. 
    * @param type $pdst_status   : Status of the destination.
    * @param type $dwc_status    : Status of the destination in the workcentre.
    * @return type
    */
   function getDestinationsUnderWorkcentre($wcntr, $pty_id = '', $pty_status = 1, $pdst_status = 1, $dwc_status = 1)
   {

      if (!$wcntr)
         return;

      $this->db->from("parties,$this->table,destination_workcentres");
      
      
      # DISTINCT(dwc_fk_party_destinations) is not working expectedly. I don't know why it is? 
      # so I have use a sub-table at the end of the function;
      # Ref: http://dev.mysql.com/doc/refman/5.0/en/distinct-optimization.html
      
      //$this->db->select("DISTINCT(dwc_fk_party_destinations), parties.*,party_destinations.*,destination_workcentres.*",false);
      $this->db->select("parties.*,party_destinations.*,destination_workcentres.*",false);      

      if (is_array($wcntr))
      {
         $str = $this->array_query($wcntr, 'dwc_fk_workcentres');
         $this->db->where($str);
      }
      else
      {
         $this->db->where('dwc_fk_workcentres', $wcntr);
      }

      if ($pty_id)
      {
         if(is_array($pty_id)) // Like array(1,4,5);
         {
            $str = $this->array_query($pty_id, 'pty_id');
            $this->db->where($str);
         }
         else
            $this->db->where('pty_id', $pty_id);
      }

      if ($pty_status)
         $this->db->where('pty_status', $pty_status);

      if ($pdst_status)
         $this->db->where('pdst_status', $pdst_status);

      if ($dwc_status)
         $this->db->where('dwc_status', $dwc_status);

      $this->db->where('pdst_id = dwc_fk_party_destinations');

      $this->db->where('pty_id = pdst_fk_parties');

      $this->db->order_by('pty_name');
      $this->db->order_by('pdst_name');

      $this->db->get();
      
      $sub_query = $this->db->last_query();
      
      $select = "DISTINCT(SUB_TABLE.dwc_fk_party_destinations) AS DEST_ID";
      $select .= ",pty_id";
      $select .= ",pty_name";
      $select .= ",pdst_name";
      $select .= ",pdst_id";
      $query = "SELECT $select FROM ($sub_query) AS SUB_TABLE";
      $result = $this->db->query($query);
      
      $result = $result->result_array();
      return $result;
   }

   function getDestinationByParty($pty_id, $flag = true, $pdst_status = 1, $pdst_category = '', $pdst_name = '')
   {
      $this->db->from($this->table);
      $this->db->where('pdst_fk_parties', $pty_id);

      if ($pdst_status)
         $this->db->where('pdst_status', $pdst_status);

      if ($pdst_category)
         $this->db->where('pdst_category', $pdst_category);

      if ($pdst_name)
         $this->db->where('pdst_name LIKE ', $pdst_name . '%');



      $this->db->join('party_license_details', 'pld_id = pdst_fk_party_license_details', 'left');

      $this->db->order_by($this->nameField, "asc");
      $destinations = $this->db->get();
      $destinations = $destinations->result_array();

      // If want only pdst_id
      if ($flag && $destinations)
      {
         $pdst_ids = array();
         foreach ($destinations as $pdst)
            $pdst_ids[] = $pdst['pdst_id'];

         // Returs an array of pdst_id.
         return $pdst_ids;
      }

      // Return full destination details.
      return $destinations;
   }

   function getDestinationByParty_2($pty_id, $flag = true, $pdst_status = 1, $pdst_category = '', $pdst_name = '', $availability)
   {
      $this->db->from("$this->table, destination_workcentres");

      $this->db->select("DISTINCT(pdst_id),$this->table.*,party_license_details.*", false);


      $this->db->where('pdst_fk_parties', $pty_id);

      if ($pdst_status)
         $this->db->where('pdst_status', $pdst_status);

      if ($pdst_category)
         $this->db->where('pdst_category', $pdst_category);

      if ($pdst_name)
         $this->db->where('pdst_name LIKE ', $pdst_name . '%');



      if ($availability)
      {
         $str = $this->array_query($availability, 'dwc_fk_workcentres');
         $this->db->where($str);
      }

      $this->db->where('dwc_fk_party_destinations = pdst_id');


      $this->db->join('party_license_details', 'pld_id = pdst_fk_party_license_details', 'left');

      $this->db->order_by($this->nameField, "asc");
      $destinations = $this->db->get();
      $destinations = $destinations->result_array();

      // If want only pdst_id
      if ($flag && $destinations)
      {
         $pdst_ids = array();
         foreach ($destinations as $pdst)
            $pdst_ids[] = $pdst['pdst_id'];

         // Returs an array of pdst_id.
         return $pdst_ids;
      }

      // Return full destination details.
      return $destinations;
   }

   function getPartyFromDestination($pdst_id)
   {
      $data = $this->getById($pdst_id);
      return $data['pdst_fk_parties'];
   }

   function destinationsUnderLicense($pld_id, $pdst_status = 1)
   {
      $this->db->where('pdst_fk_party_license_details', $pld_id);
      if ($pdst_status)
         $this->db->where('pdst_status', $pdst_status);
      $this->db->order_by('pdst_name', "asc");
      $result = $this->db->get($this->table);
      return $result->result_array();
   }

}

?>