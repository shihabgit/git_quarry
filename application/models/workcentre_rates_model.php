<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Workcentre_rates_model extends my_model
{

   function __construct()
   {
      parent::__construct();
      $this->loadTable(getTables('workcentre_rates'));
      $this->p_key = 'wrt_id';
      $this->nameField = '';
      $this->statusField = '';
   }

   function index($input, $wcntr_ids, $num_rows = false)
   {
      $this->db->from("$this->table,item_category,item_heads,items,units");

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
         $str = $this->array_query($input['wcntr_id'], 'wrt_fk_workcentres_from');
         $this->db->where($str);
      }
      else
      {
         $str = $this->array_query($wcntr_ids, 'wrt_fk_workcentres_from');
         $this->db->where($str);
      }
      

      $this->db->where('unt_id = itm_fk_units');
      $this->db->where('itm_id = wrt_fk_items');
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

   
   function get_WorkcentreTo_Rates($itm_id, $wc_from_id, $wc_to_id, $unt_batch)
   {
      if (!$wc_from_id)
         return array();

      
      $this->db->from("$this->table,items,units");
      $this->db->select("$this->table.*,itm_fk_units");
      
      

      $this->db->where('wrt_fk_items', $itm_id);
      $this->db->where('itm_id = wrt_fk_items');

      $this->db->where('wrt_fk_workcentres_from', $wc_from_id);
      
      $this->db->where('wrt_fk_workcentres_to',$wc_to_id);


      # ------------        IMPORTANT      --------------------------------------------------------------------------#
      # When a user edited the unit of an item, Actually it is not the editing of the existing units.                #
      # But it is realy the creation of a new batch of units.                                                        #
      # So if the units of an item edited, The unit rates of the item set in Tbl:workcentre_rates become invalid.    #
      # so it must be avoid when listing.    
                     $this->db->where('unt_id = itm_fk_units');                                                
                     $this->db->where('unt_batch', $unt_batch);
      # -------------------------------------------------------------------------------------------------------------#



      $result = $this->db->get();
      
      return $result->result_array();
   }
   
   
   
   function get_WorkcentresTo_InWorkcentreRates($itm_id, $wc_from_id, $unt_batch)
   {
      if (!$wc_from_id)
         return array();

      $this->db->from("$this->table,items,units,workcentres");
      $this->db->select("DISTINCT(wrt_fk_workcentres_to),wcntr_id,wcntr_name");

      $this->db->where('wrt_fk_items', $itm_id);
      $this->db->where('itm_id = wrt_fk_items');

      $this->db->where('wrt_fk_workcentres_from', $wc_from_id);
      
      $this->db->where('wcntr_id = wrt_fk_workcentres_to');
      $this->db->where('wcntr_status', ACTIVE);


      # ------------        IMPORTANT      --------------------------------------------------------------------------#
      # When a user edited the unit of an item, Actually it is not the editing of the existing units.                #
      # But it is realy the creation of a new batch of units.                                                        #
      # So if the units of an item edited, The unit rates of the item set in Tbl:workcentre_rates become invalid.    #
      # so it must be avoid when listing.    
                     $this->db->where('unt_id = itm_fk_units');                                                
                     $this->db->where('unt_batch', $unt_batch);
      # -------------------------------------------------------------------------------------------------------------#


      $this->db->order_by('wcntr_name','asc');

      $result = $this->db->get();
      
      return $result->result_array();
   }

}

?>