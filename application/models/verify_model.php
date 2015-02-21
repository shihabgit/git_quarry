<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Verify_model extends my_model
{

    function __construct()
    {
        parent::__construct();
        $this->loadTable(getTables('verify'));
        $this->p_key = 'verify_id';
        $this->nameField = '';
        $this->statusField = 'verify_status';
    }

    /**
     * Function returns all users those are having verify power under workcentre represented by $wcntre_id.
     * @param type $wcntre_id
     * @param type $empcats_verify_power
     */
    function getVerifiersId($wcntre_id, $empcats_verify_power)
    {
        // By default all Admins are verifiers. So taking them first.
        $result_1 = $this->get_data(array('emp_id'), array('emp_status' => 1, 'emp_category' => 1), '', 'employees', 'emp_id');

        // Verifier must have set the task 'Worklogs'.
        
        $task_id = getTaskId('worklogs/index');
        $task_id = $task_id ? : getTaskId('worklogs');
        if (!$task_id)
        {
            echo "<br>Task not set in Tbl:tasks<br>";
            return FALSE;
        }

        // Finding other verifiers
        $this->db->from('employees,employee_work_centre,user_tasks');
        $this->db->select('emp_id');
        if (is_array($empcats_verify_power))
        {
            $str = $this->array_query($empcats_verify_power, 'emp_category');
            $this->db->where($str);
        }
        else
            $this->db->where('emp_category', $empcats_verify_power);
        $this->db->where('emp_status', 1);
        $this->db->where('ewp_fk_auth_users = emp_id');
        $this->db->where('ewp_fk_workcentres', $wcntre_id);
        $this->db->where('utsk_fk_auth_users = emp_id');
        $this->db->where('utsk_fk_tasks', $task_id);

        $query = $this->db->get();
        $result_2 = $query->result_array();

        $verifiers = array_merge($result_1, $result_2);
        $verifiers_id = array();
        foreach ($verifiers as $row)
            $verifiers_id[] = $row['emp_id'];

        return $verifiers_id;
    }

    function getAllAdminVerifiers()
    {
        // Verifier must have set the task 'Worklogs'.
        $task_id = getTaskId('worklogs/index');
        $task_id = $task_id ? : getTaskId('worklogs');
        if (!$task_id)
        {
            echo "<br>Task not set in Tbl:tasks<br>";
            return FALSE;
        }
        $this->db->from('employees,user_tasks');
        $this->db->select('emp_id');
        $this->db->where('emp_category', 1);
        $this->db->where('emp_status', 1);
        $this->db->where('utsk_fk_auth_users = emp_id');
        $this->db->where('utsk_fk_tasks', $task_id);

        $query = $this->db->get();
        $verifiers = $query->result_array();
        $verifiers_id = array();
        foreach ($verifiers as $row)
            $verifiers_id[] = $row['emp_id'];

        return $verifiers_id;
    }

    
    // Return users having verify power in a firm.
    function getVerifiersInFirm($firm_id, $empcats_verify_power, $except_me = '')
    {
        // Verifier must have set the task 'Worklogs'.
        $task_id = getTaskId('worklogs/index');
        $task_id = $task_id ? : getTaskId('worklogs');
        if (!$task_id)
        {
            echo "<br>Task not set in Tbl:tasks<br>";
            return FALSE;
        }

        // Finding other verifiers
        $this->db->from('auth_users,employees,employee_work_centre,workcentres,firms,user_tasks');
        $this->db->select('id,first_name');
        if (is_array($empcats_verify_power))
        {
            $str = $this->array_query($empcats_verify_power, 'emp_category');
            $this->db->where("($str OR emp_category = 1)"); // All admins are verifiers.
        }
        else
            $this->db->where("(emp_category = $empcats_verify_power OR emp_category = 1)");
        
        if ($except_me)
            $this->db->where('id != ', $except_me);

        $this->db->where('emp_status', 1);
        $this->db->where('id = emp_id');
        $this->db->where('ewp_fk_auth_users = emp_id');
        $this->db->where('wcntr_id = ewp_fk_workcentres');
        $this->db->where('firm_id = wcntr_fk_firms');
        $this->db->where('firm_id', $firm_id);
        $this->db->where('utsk_fk_auth_users = emp_id');
        $this->db->where('utsk_fk_tasks', $task_id);
        $this->db->order_by('first_name', 'asc');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    function getSameWlogs($user_id,$verify_id)
    {
        
        $wlog_id = $this->getWlogId($verify_id);
        
        $this->db->from('worklog_workcentres,verify');
        $this->db->select('verify_id');
        
        $this->db->where('wlog_wc_fk_worklogs',$wlog_id);
        $this->db->where('verify_fk_worklog_workcentres = wlog_wc_id');
        $this->db->where('verify_fk_auth_users',$user_id);
        
        $result = $this->db->get();
        $result = $result->result_array();
        $verify_ids = array();
        foreach($result as $row)
            $verify_ids[] = $row['verify_id'];
        return $verify_ids;
    }
    
    function getWlogId($verify_id)
    {
        $this->db->from('worklog_workcentres,verify');
        $this->db->select('wlog_wc_fk_worklogs');
        $this->db->where('verify_id',$verify_id);
        $this->db->where('wlog_wc_id = verify_fk_worklog_workcentres');
        $result = $this->db->get();
        
        //echo $this->db->last_query()."<br><br>";
        $result = $result->row_array(); 
        return $result['wlog_wc_fk_worklogs'];
    }

    function getVerifiersInFirmOption($firm_id, $empcats_verify_power, $except_me = '')
    {
        $options = $this->getVerifiersInFirm($firm_id, $empcats_verify_power, $except_me);
        return $this->make_options($options, 'id', 'first_name');
    }

}
