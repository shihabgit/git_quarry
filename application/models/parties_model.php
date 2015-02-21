<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Parties_model extends my_model
{

   function __construct()
   {
      parent::__construct();
      $this->loadTable(getTables('parties'));
      $this->p_key = 'pty_id';
      $this->nameField = 'pty_name';
      $this->statusField = 'pty_status';
   }

   /**
    * 
    * @param type $wcntr         : A single workcentre id or array of workcentres like array(1,2,5,6);
    * @param type $pty_status
    * @param type $pdst_status
    * @param type $dwc_status
    * @return type
    */
   function getPartiesUnderWorkcentre($wcntr,$pty_status=1,$pdst_status=1,$dwc_status=1)
   {
      
      if(!$wcntr) return;
      
      $this->db->from("$this->table,party_destinations,destination_workcentres");
      $this->db->select("DISTINCT(pty_id),parties.*,party_destinations.*,destination_workcentres.*");
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
      
      $this->db->where('pdst_id = dwc_fk_party_destinations');
      
      $this->db->where('pty_id = pdst_fk_parties');
      
      $this->db->order_by('pty_name');
      
      $result = $this->db->get();
      
      $result = $result->result_array();
//      echo "<br>".$this->db->last_query();
      return $result;
   }

   
   
   
   function index($input, $wlog_ref_id, $availability, $num_rows = false)
   {
      $this->db->from($this->table);

      $this->db->select("DISTINCT(pty_id),parties.*");

      if ($wlog_ref_id)
         $this->db->where($this->p_key, $wlog_ref_id);

      #WHERE
      if (ifSetInput($input, 'pty_name'))
      {
         $this->db->where('pty_name LIKE', $input['pty_name'] . '%');
      }

      if (ifSetInput($input, 'pvhcl_no'))
      {
         $this->db->where('pvhcl_no LIKE', $input['pvhcl_no'] . '%');
      }

      if (ifSetInput($input, 'pdst_name'))
      {
         $this->db->where('pdst_name LIKE', $input['pdst_name'] . '%');
      }


      if (ifSetInput($input, 'pdst_category'))
      {
         $this->db->where('pdst_category', $input['pdst_category']);
      }

      if (ifSetInput($input, 'pty_status'))
      {
         $this->db->where('pty_status', $input['pty_status']);
      }

      if (ifSetInput($input, 'pvhcl_status'))
      {
         $this->db->where('pvhcl_status', $input['pvhcl_status']);
      }

      if (ifSetInput($input, 'pdst_status'))
      {
         $this->db->where('pdst_status', $input['pdst_status']);
      }

      if (ifSetInput($input, 'dwc_status'))
      {
         $this->db->where('dwc_status', $input['dwc_status']);
      }


      if ($availability)
      {
         $str = $this->array_query($availability, 'dwc_fk_workcentres');
         $this->db->where($str);
      }


      $this->db->join('party_vehicles', 'pvhcl_fk_parties = pty_id', 'left');
      $this->db->join('party_destinations', 'pdst_fk_parties = pty_id', 'left');
      $this->db->join('destination_workcentres', 'dwc_fk_party_destinations = pdst_id', 'left');

      $this->db->order_by('pty_name', 'asc');

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

//
//    function getDestinations($pty_id, $flag = true, $pdst_status = 1)
//    {
//        $this->db->from('party_destinations,party_license_details');
//        $this->db->where('pdst_fk_parties', $pty_id);
//        if ($pdst_status)
//            $this->db->where('pdst_status', $pdst_status);
//        $this->db->where('pld_id = pdst_fk_party_license_details');
//        $this->db->order_by('pdst_name', "asc");
//        $result = $this->db->get();
//        $destinations = $result->result_array();
//        if ($flag && $destinations)
//        {
//            $pdst_ids = array();
//            foreach ($destinations as $pdst)
//                $pdst_ids[] = $pdst['pdst_id'];
//            return $pdst_ids;
//        }
//        return $destinations;
//    }
//    function getPartyWorkcentres($pty_id, $flag = true, $dwc_status=1,$wcntr_status=1)
//    {
//        $workcentres = array();
//        
//        $pdst_ids = $this->getDestinations($pty_id,$flag,$dwc_status);
//        if (!$pdst_ids)
//            return $workcentres;
//        
//        $this->db->from('destination_workcentres,workcentres');
//        $this->db->select("DISTINCT(wcntr_id),workcentres.*");
//        $str = $this->array_query($pdst_ids, 'dwc_fk_party_destinations');
//        $this->db->where($str);
//        
//        if($dwc_status)
//            $this->db->where('dwc_status',$dwc_status);
//        
//        if($wcntr_status)
//            $this->db->where('wcntr_status',$wcntr_status);
//        
//        $this->db->where('wcntr_id = dwc_fk_workcentres');
//        $this->db->order_by('wcntr_name', "asc");
//        $result = $this->db->get();
//        $workcentres = $result->result_array();
//        if ($flag && $workcentres)
//        {
//            $wcntr_ids = array();
//            foreach ($workcentres as $wc)
//                $wcntr_ids[] = $wc['wcntr_id'];
//            return $wcntr_ids;
//        }
//        return $workcentres;
//    }
}

?>