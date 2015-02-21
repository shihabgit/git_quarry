<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Items_model extends my_model
{

   function __construct()
   {
      parent::__construct();
      $this->loadTable(getTables('items'));
      $this->p_key = 'itm_id';
      $this->nameField = 'itm_name';
      $this->statusField = 'itm_status';
   }

   function getUnitId($itm_id)
   {
      $data = $this->getById($itm_id);
      if (isset($data['itm_fk_units']))
         return $data['itm_fk_units'];
      return FALSE;
   }

   function index($input, $wlog_ref_id = '')
   {
      $this->db->from('item_category,item_heads,items,units');


      if ($wlog_ref_id)
         $this->db->where($this->p_key, $wlog_ref_id);

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

      $this->db->where('unt_id = itm_fk_units');
      $this->db->where('itmhd_id = itm_fk_item_head');
      $this->db->where('itmcat_id = itmhd_fk_item_category');
      $this->db->order_by('itm_name', 'asc');
      $this->db->order_by('itmcat_name', 'asc');
      $this->db->order_by('itmhd_name', 'asc');
      $query = $this->db->get();
      $result = $query->result_array();
      return $result;
   }

   //itm_name   itm_status
   function get_items_by_itemHead($itmhd_id, $itm_status = 1, $option = true)
   {
      if (!$itmhd_id)
         return array();
      
      $where['itm_fk_item_head'] = $itmhd_id;
      
      if ($itm_status)
         $where['itm_status'] = $itm_status;
      
      $items = $this->get_data('', $where);
      
      if(!$items)
         return array();
     
      if ($option)
      {
         $options = $this->make_options_2($items);
         return $options;
      }
      
      return $items;
   }

}

?>