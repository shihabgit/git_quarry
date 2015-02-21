<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Item_heads_model extends my_model
{

    function __construct()
    {
        parent::__construct();
        $this->loadTable(getTables('item_heads'));
        $this->p_key = 'itmhd_id';
        $this->nameField = 'itmhd_name';
        $this->statusField = 'itmhd_status';
    }

    function index()
    {
        $this->db->from('item_category,item_heads');
        $this->db->where('itmcat_id = itmhd_fk_item_category');
        $this->db->order_by('itmcat_name', 'asc');
        $this->db->order_by('itmhd_name', 'asc');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }
    
    function getCategory($id)
    {
        $data = $this->getById($id);
        if(isset($data['itmhd_fk_item_category']))
            return $data['itmhd_fk_item_category'];
        return 0;
    }

}
?>