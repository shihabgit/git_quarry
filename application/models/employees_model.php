<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Employees_model extends my_model
{

    function __construct()
    {
        parent::__construct();
        $this->loadTable(getTables('employees'));
        $this->p_key = 'emp_id';
        $this->nameField = 'emp_name';
        $this->statusField = 'emp_status';
    }

    // If you want to make any changes to this function,
    // you must apply the corresponding chage to the function employee_work_centre_model::get_employee_category()
    function get_employee_category($cat = 1, $index = '', $includAdmin = true)
    {
        if (($cat == 1) && $includAdmin) // Admin
            $empcat[1] = "Admin";
        if ($cat == 1 || $cat == 2) // Admin or Partner
            $empcat[2] = "Partner";
        $empcat[3] = "Staff";
        $empcat[4] = "Driver";
        $empcat[5] = "Loader";
        if ($index)
            return $empcat[$index];
        return $empcat;
    }

    function get_employee_status()
    {
        return $this->get_status();
    }

    function is_active($user_id)
    {
        $where['emp_id'] = $user_id;
        $where['emp_status'] = 1;
        if ($this->get_row($where))
            return TRUE;
        return FALSE;
    }

    function is_partner($user_id)
    {
        $where['emp_id'] = $user_id;
        $where['emp_category'] = 2;
        if ($this->get_row($where))
            return TRUE;
        return FALSE;
    }

    function is_staff($user_id)
    {
        $where['emp_id'] = $user_id;
        $where['emp_category'] = 3;
        if ($this->get_row($where))
            return TRUE;
        return FALSE;
    }

    function employee_details($user_id)
    {
        $this->db->from('auth_users,employees');
        $this->db->where('emp_id', $user_id);
        $this->db->where('id = emp_id');
        $result = $this->db->get();
        return $result->row_array();
    }

    function getAllEmployees($option = true, $where = '')
    {
        $this->db->from('employees');
        if ($where)
            $this->db->where($where);
        $this->db->order_by('emp_name', 'asc');
        $query = $this->db->get();
        $result = $query->result_array();

        if ($option && $result)
        {
            $options = array();
            foreach ($result as $row)
                $options[$row['emp_id']] = $row['emp_name'];
            return $options;
        }
        return $result;
    }

    function index($input, $empcat, $workcentres, $wlog_ref_id,$num_rows=false)
    {   
        $this->db->from('auth_users,employees');

        $this->db->select("DISTINCT(id),auth_users.*,employees.*", false);


        if ($wlog_ref_id)
            $this->db->where($this->p_key, $wlog_ref_id);

        #WHERE
        if (ifSetInput($input, 'id'))
        {
            $this->db->where('id', ifSetInput($input, 'id'));
        }

        if (ifSetInput($input, 'first_name'))
        {
            $this->db->where('first_name LIKE', ifSetInput($input, 'first_name') . '%');
        }

        if (ifSetInput($input, 'emp_status'))
        {
            $this->db->where('emp_status', ifSetInput($input, 'emp_status'));
        }


        if (ifSetInput($input, 'f_emp_date'))
        {
            $this->db->where('emp_date >= ', getSqlDate(ifSetInput($input, 'f_emp_date')));
        }

        if (ifSetInput($input, 't_emp_date'))
        {
            $this->db->where('emp_date <= ', getSqlDate(ifSetInput($input, 't_emp_date')));
        }

        if (ifSetInput($input, 'username'))
        {
            $this->db->where('username LIKE', ifSetInput($input, 'username') . '%');
        }
        
        if (ifSetInput($input, 'emp_address'))
        {
            $this->db->where('emp_address LIKE', '%' . ifSetInput($input, 'emp_address') . '%');
        }

        if (ifSetInput($input, 'emp_category'))
        {
            $str = $this->array_query(ifSetInput($input, 'emp_category'), 'emp_category');
            $this->db->where($str);
        }


        // Non-admins are not accessible of Admin details
        if ($empcat != 1)    // Not admin
            $this->db->where('emp_category != 1');

        // Partners details are accessible only by Admins and partners. Others are not.
        if (($empcat != 1) && ($empcat != 2))// Not Admin && Not Partner
            $this->db->where('(emp_category != 1 AND emp_category != 2)');

        // User selected atleast one workcentre.
        if (ifSetInput($input, 'ewp_fk_workcentres'))
        {
            $str = $this->array_query(ifSetInput($input, 'ewp_fk_workcentres'), 'ewp_fk_workcentres');
            $this->db->where($str);
        }
        else if (is_array($workcentres) && $workcentres)
        {
            $workcentres = array_flip($workcentres);
            $str = $this->array_query($workcentres, 'ewp_fk_workcentres'); 
            $this->db->where($str);
        }

        $this->db->where('id = emp_id');
        //$this->db->where('ewp_fk_auth_users = emp_id');
        $this->db->join('employee_work_centre', 'ewp_fk_auth_users = emp_id', 'left');
        
        $this->db->order_by('emp_category', 'asc');
        $this->db->order_by('first_name', 'asc');

        if($num_rows)
        {
            $query = $this->db->get();
            return count($query->result_array());
        }
        
        
        if($input['PER_PAGE'])
            $query = $this->db->get('', $input['PER_PAGE'], $input['offset']);
        else
            $query = $this->db->get();
        $result = $query->result_array();

//        echo "<br>".$this->db->last_query();echo "<br>";

        return $result;
    }

}

?>