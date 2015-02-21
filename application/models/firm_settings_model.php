<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Firm_settings_model extends my_model
{

    function __construct()
    {
        parent::__construct();
        $this->loadTable(getTables('firm_settings'));
        $this->p_key = 'frmset_id';
        $this->nameField = '';
        $this->statusField = '';
    }
    
    function get_Key($frmset_id)
    {
        $this->db->from('settings,firm_settings');
        $this->db->select('set_key');
        $this->db->where('frmset_id',$frmset_id);
        $this->db->where('frmset_fk_settings = set_id');
        $result = $this->db->get();
        $result = $result->row_array();
        return $result['set_key'];        
    }
    
    function updateFirm($id='',$data,$where='')
    {
        if($id)
            $this->db->where($this->p_key, $id);
        if($where)
            $this->db->where($where);
        
        $this->db->update($this->table, $data); 
    }
    
    function getSettingsId($id)
    {
         $row = $this->getById($id);
         return $row['frmset_fk_settings'];
    }
}    