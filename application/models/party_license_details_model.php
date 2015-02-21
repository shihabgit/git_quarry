<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Party_license_details_model extends my_model
{

   function __construct()
   {
      parent::__construct();
      $this->loadTable(getTables('party_license_details'));
      $this->p_key = 'pld_id';
      $this->nameField = 'pld_firm_name';
      $this->statusField = 'pld_status';
   }
   
   function get_registered_partiesUnderWorkcentre($wcntr,$pty_status=1,$pdst_status=1,$dwc_status=1,$pld_status=1)
   {
      
      if(!$wcntr) return;
      
      $this->db->from("$this->table,parties,party_destinations,destination_workcentres");
      $this->db->select("DISTINCT(pty_id),$this->table.*,parties.*,party_destinations.*,destination_workcentres.*");
      
      if (is_array($wcntr))
      {
         $str = $this->array_query($wcntr, 'dwc_fk_workcentres');
         $this->db->where($str);
      }
      else
      {
         $this->db->where('dwc_fk_workcentres',$wcntr);
      }
      
      if($pty_status)
         $this->db->where('pty_status',$pty_status);
      
      if($pdst_status)
         $this->db->where('pdst_status',$pdst_status);
      
      if($dwc_status)
         $this->db->where('dwc_status',$dwc_status);
      
      if($pld_status)
         $this->db->where('pld_status',$pld_status);
      
      $this->db->where('pld_id = pdst_fk_party_license_details');
      
      $this->db->where('pdst_id = dwc_fk_party_destinations');
      
      $this->db->where('pty_id = pdst_fk_parties');
      
      $this->db->order_by('pty_name');
      
      $result = $this->db->get();
      
      $result = $result->result_array();
      return $result;
   }
   
   function getRegisteredDestinationsUnderWorkcentre($wcntr,$pty_id='',$pty_status=1,$pdst_status=1,$dwc_status=1,$pld_status=1)
   {

      if (!$wcntr)
         return;

      $this->db->from("parties,party_destinations,$this->table,destination_workcentres");
      
      
      # DISTINCT(dwc_fk_party_destinations) is not working expectedly. I don't know why it is? 
      # so I have use a sub-table at the end of the function;
      # Ref: http://dev.mysql.com/doc/refman/5.0/en/distinct-optimization.html
      
      //$this->db->select("DISTINCT(dwc_fk_party_destinations), parties.*,party_destinations.*,destination_workcentres.*",false);
      $this->db->select("$this->table.*,parties.*,party_destinations.*,destination_workcentres.*",false);      

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
      
      if($pld_status)
         $this->db->where('pld_status',$pld_status);
      
      $this->db->where('pld_id = pdst_fk_party_license_details');

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

   /**
    * Function returns licenses that are not used yet by any parties. 
    * @param type $pld_status
    * @return type
    */
   function getFreeLicenses($pld_status = 1)
   {
      $this->db->from($this->table);

      if ($pld_status)
         $this->db->where($this->statusField, $pld_status);

      $subQuery = "(SELECT pdst_fk_party_license_details FROM party_destinations)";
      $this->db->where($this->p_key . " NOT IN " . $subQuery);

      $this->db->order_by($this->nameField, "asc");

      $result = $this->db->get();
      return $result->result_array();
   }

   /**
    * Get the licenses of a party.
    * @param type $pty_id
    * @param type $pld_status
    * @return type
    */
   function getPartysLicenses($pty_id, $pld_status = 1)
   {
      $this->db->from($this->table . ', party_destinations');
      $this->db->select("DISTINCT($this->p_key),$this->table.*");
      $this->db->where('pdst_fk_parties', $pty_id);
      if ($pld_status)
         $this->db->where($this->statusField, $pld_status);
      $this->db->where('pld_id = pdst_fk_party_license_details');
      $this->db->order_by($this->nameField, "asc");
      $result = $this->db->get();
      return $result->result_array();
   }

   /**
    * Function returns licenses that are used by the party ($pty_id) and aslo the licenses are not used by anybody.
    * @param type $pty_id
    * @param type $pld_status
    * @return type
    */
   function getPartysAvailableLicense($pty_id, $pld_status = 1)
   {
      $freeLicenses = $this->getFreeLicenses();
      $myLicenses = $this->getPartysLicenses($pty_id, $pld_status);
      $availables = array_merge($freeLicenses, $myLicenses);

      //Sorting
      if ($availables)
      {
         foreach ($availables as $key => $row)
         {
            $name[$key] = $row['pld_firm_name'];
            $address[$key] = $row['pld_address'];
         }
         array_multisort($name, SORT_ASC, $address, SORT_ASC, $availables);
      }

      return $availables;
   }

   function getPartyByLicense($pld_id)
   {
      $this->db->from('party_destinations');
      $this->db->select("pdst_fk_parties");
      $this->db->where('pdst_fk_party_license_details', $pld_id);
      $result = $this->db->get();
      $result = $result->row_array();
      return $result['pdst_fk_parties'];
   }

   function getLicenseWorkcentres($pld_id, $flag = true, $dwc_status = 1, $wcntr_status = 1)
   {
      $workcentres = array();
      $this->db->from('party_destinations,destination_workcentres,workcentres');
      $this->db->select("workcentres.*");
      $this->db->where('pdst_fk_party_license_details', $pld_id);
      $this->db->where('dwc_fk_party_destinations = pdst_id');

      if ($dwc_status)
         $this->db->where('dwc_status', $dwc_status);

      if ($wcntr_status)
         $this->db->where('wcntr_status', $wcntr_status);

      $this->db->where('wcntr_id = dwc_fk_workcentres');
      $this->db->order_by('wcntr_name', "asc");
      $result = $this->db->get();
      $workcentres = $result->result_array();
      if ($flag && $workcentres)
      {
         $wcntr_ids = array();
         foreach ($workcentres as $wc)
            $wcntr_ids[] = $wc['wcntr_id'];
         return $wcntr_ids;
      }
      return $workcentres;
   }

}

?>