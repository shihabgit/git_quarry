<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Individual_rates_model extends my_model
{

   function __construct()
   {
      parent::__construct();
      $this->loadTable(getTables('individual_rates'));
      $this->p_key = 'indv_id';
      $this->nameField = '';
      $this->statusField = '';
   }

   function index($input, $wcntr_ids, $num_rows = false)
   {
      $this->db->from("$this->table,party_destinations,item_category,item_heads,items,units");

      $this->db->select("DISTINCT(itm_id),items.*,item_heads.*,item_category.*,units.*");

      if (ifSetInput($input, 'itmcat_id'))
      {
         $this->db->where('itmcat_id', $input['itmcat_id']);
      }

      if (ifSetInput($input, 'itm_fk_item_head'))
      {
         $this->db->where('itm_fk_item_head', $input['itm_fk_item_head']);
      }

      if (ifSetInput($input, 'itm_name'))
      {
         $this->db->where('itm_name LIKE', $input['itm_name'] . '%');
      }

      if (ifSetInput($input, 'itm_status'))
      {
         $this->db->where('itm_status', $input['itm_status']);
      }

      if (ifSetInput($input, 'wcntr_id'))
      {
         $str = $this->array_query($input['wcntr_id'], 'indv_fk_workcentres');
         $this->db->where($str);
      }
      else
      {
         $str = $this->array_query($wcntr_ids, 'indv_fk_workcentres');
         $this->db->where($str);
      }

      if (ifSetInput($input, 'pdst_id'))
      {
         $this->db->where('indv_fk_party_destinations', $input['pdst_id']);
      }
      else if (ifSetInput($input, 'pty_id'))
      {
         $this->db->where('pdst_fk_parties', $input['pty_id']);
      }

      $this->db->where('unt_id = itm_fk_units');
      $this->db->where('itm_id = indv_fk_items');
      $this->db->where('pdst_id = indv_fk_party_destinations');
      $this->db->where('itmhd_id = itm_fk_item_head');
      $this->db->where('itmcat_id = itmhd_fk_item_category');

      $this->db->order_by('itm_name', 'asc');
      $this->db->order_by('itmcat_name', 'asc');
      $this->db->order_by('itmhd_name', 'asc');



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

   function getWorkcentreRates($itm_id, $wc_id, $pty_id = '', $pdst_id = '', $unt_batch, $pty_status = 1, $pdst_status = 1, $dwc_status = 1)
   {
      if (!$wc_id)
         return array();

      $this->db->from("$this->table,items,units,parties,party_destinations,destination_workcentres");
      $this->db->select("$this->table.*,pdst_id,pdst_name,pty_name,itm_fk_units");

      $this->db->where('indv_fk_items', $itm_id);
      $this->db->where('itm_id = indv_fk_items');

      $this->db->where('indv_fk_workcentres', $wc_id);
      
      if ($pdst_id)
      {
         $this->db->where('indv_fk_party_destinations', $pdst_id);
      }
      else if ($pty_id)
      {
         $this->db->where('pdst_fk_parties', $pty_id);
      }

      if ($pty_status)
         $this->db->where('pty_status', $pty_status);
      if ($pdst_status)
         $this->db->where('pdst_status', $pdst_status);
      if ($dwc_status)
         $this->db->where('dwc_status', $dwc_status);


      # ------------        IMPORTANT      --------------------------------------------------------------------------#
      # When a user edited the unit of an item, Actually it is not the editing of the existing units.                #
      # But it is realy the creation of a new batch of units.                                                        #
      # So if the units of an item edited, The unit rates of the item set in Tbl:individual_rates become invalid.    #
      # so it must be avoid when listing.    
                     $this->db->where('unt_id = itm_fk_units');                                                
                     $this->db->where('unt_batch', $unt_batch);
      # -------------------------------------------------------------------------------------------------------------#

      $this->db->where('pdst_id = indv_fk_party_destinations');
      $this->db->where('pdst_id = indv_fk_party_destinations');
      $this->db->where('pty_id = pdst_fk_parties');
      $this->db->where('dwc_fk_workcentres = indv_fk_workcentres');
      $this->db->where('dwc_fk_party_destinations = indv_fk_party_destinations');

      $this->db->order_by('pty_name','asc');
      $this->db->order_by('pdst_name','asc');
      $this->db->order_by('pty_name','asc');

      $result = $this->db->get();
      
      //echo "<br>HI : ".$this->db->last_query()."<br>";
      
      return $result->result_array();
   }
   
   function getDestinationsInWorkcentreRates($itm_id, $wc_id, $pty_id = '', $pdst_id = '', $unt_batch, $pty_status = 1, $pdst_status = 1, $dwc_status = 1)
   {
      if (!$wc_id)
         return array();

      $this->db->from("$this->table,items,units,parties,party_destinations,destination_workcentres");
      $this->db->select("pty_name,pdst_id,pdst_name");

      $this->db->where('indv_fk_items', $itm_id);
      $this->db->where('itm_id = indv_fk_items');

      $this->db->where('indv_fk_workcentres', $wc_id);
      
      if ($pdst_id)
      {
         $this->db->where('indv_fk_party_destinations', $pdst_id);
      }
      else if ($pty_id)
      {
         $this->db->where('pdst_fk_parties', $pty_id);
      }

      if ($pty_status)
         $this->db->where('pty_status', $pty_status);
      if ($pdst_status)
         $this->db->where('pdst_status', $pdst_status);
      if ($dwc_status)
         $this->db->where('dwc_status', $dwc_status);


      # ------------        IMPORTANT      --------------------------------------------------------------------------#
      # When a user edited the unit of an item, Actually it is not the editing of the existing units.                #
      # But it is realy the creation of a new batch of units.                                                        #
      # So if the units of an item edited, The unit rates of the item set in Tbl:individual_rates become invalid.    #
      # so it must be avoid when listing.    
                     $this->db->where('unt_id = itm_fk_units');                                                
                     $this->db->where('unt_batch', $unt_batch);
      # -------------------------------------------------------------------------------------------------------------#

      $this->db->where('pdst_id = indv_fk_party_destinations');
      $this->db->where('pdst_id = indv_fk_party_destinations');
      $this->db->where('pty_id = pdst_fk_parties');
      $this->db->where('dwc_fk_workcentres = indv_fk_workcentres');
      $this->db->where('dwc_fk_party_destinations = indv_fk_party_destinations');

      $this->db->order_by('pty_name','asc');
      $this->db->order_by('pdst_name','asc');
      $this->db->order_by('pty_name','asc');

      $this->db->get();
      
      
      $sub_query = $this->db->last_query();
      
      $select = "DISTINCT(SUB_TABLE.pdst_id) AS DEST_ID";
      $select .= ",pty_name";
      $select .= ",pdst_name";
      $query = "SELECT $select FROM ($sub_query) AS SUB_TABLE";
      $result = $this->db->query($query);
      
      $result = $result->result_array();
      return $result;
   }

}

?>