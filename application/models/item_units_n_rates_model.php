<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Item_units_n_rates_model extends my_model
{

    function __construct()
    {
        parent::__construct();
        $this->loadTable(getTables('item_units_n_rates'));
        $this->p_key = 'iur_id';
        $this->nameField = '';
        $this->statusField = '';
    }
    
    function getPurchaseRate($wcntr_id,$unt_id,$itm_id)
    {
        $data = $this->get_row(array('iur_fk_workcentres'=>$wcntr_id,'iur_fk_units'=>$unt_id,'iur_fk_items'=>$itm_id));
        if(isset($data['iur_p_rate']))
            return $data['iur_p_rate'];
        return '';
    }
    
    function getSalesRate($wcntr_id,$unt_id,$itm_id)
    {
        $data = $this->get_row(array('iur_fk_workcentres'=>$wcntr_id,'iur_fk_units'=>$unt_id,'iur_fk_items'=>$itm_id));
        if(isset($data['iur_s_rate']))
            return $data['iur_s_rate'];
        return '';
    }
}
?>
    