<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Firms_model extends my_model
{

    function __construct()
    {
        parent::__construct();
        $this->loadTable(getTables('firms'));
        $this->p_key = 'firm_id';
        $this->nameField = 'firm_name';
        $this->statusField = 'firm_status';
    }

    function search($input, $flag = false, $limit = 0, $offset = 0)
    {
        if ($flag)
            return $this->db->count_all_results();
        else
            $query = $this->db->get('', $limit, $offset);

        $result = $query->result_array();
        return $result;
    }

    /**
     * 
     * @param type $user_id
     * @param type $status
     * @param type $is_admin
     * @return type
     */
    function get_firms($user_id = '', $status = '', $order_by = '')
    {
        if (!$user_id)
            $this->db->from('firms');
        else
        {
            $this->db->select('DISTINCT(firm_id), firms.*');
            $this->db->from('employee_work_centre,workcentres,firms');
            $this->db->where('employee_work_centre.ewp_fk_auth_users', $user_id);
            $this->db->where('workcentres.wcntr_id = employee_work_centre.ewp_fk_workcentres');
            $this->db->where('firms.firm_id = workcentres.wcntr_fk_firms');
        }

        if ($status)
            $this->db->where('firms.firm_status', $status);
        if ($order_by)
            $this->db->order_by($order_by, 'asc');
        else
            $this->db->order_by($this->nameField, 'asc');

        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    /**
     * 
     * @param type $user_id
     * @param type $status
     * @param type $is_admin
     * @return type
     * 
     * Usage:- $admin_firms = $this->firms->get_users_firms_options($this->user_id,1,$this->is_admin);
     */
    function get_firms_options($user_id = '', $status = '', $order_by = '')
    {
        $options = $this->get_firms($user_id, $status, $order_by);
        $option = array();

        foreach ($options as $row)
        {
            $option[$row['firm_id']] = $row['firm_name'];
        }
        return $option;
    }

    function is_user_registered_in_firm($user_id, $firm_id)
    {

        $this->db->select('DISTINCT(firm_id)');
        $this->db->from('employee_work_centre,workcentres,firms');
        $this->db->where('employee_work_centre.ewp_fk_auth_users', $user_id);
        $this->db->where('workcentres.wcntr_id = employee_work_centre.ewp_fk_workcentres');
        $this->db->where('firms.firm_id = workcentres.wcntr_fk_firms');
        $this->db->where('firms.firm_id', $firm_id);

        return $this->db->count_all_results();
    }

    function get_status()
    {
        return array(1 => 'Active', 2 => 'Delete');
    }

}

?>