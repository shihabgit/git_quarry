<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Workcentres_model extends my_model
{

    function __construct()
    {
        parent::__construct();
        $this->loadTable(getTables('workcentres'));
        $this->p_key = 'wcntr_id';
        $this->nameField = 'wcntr_name';
        $this->statusField = 'wcntr_status';
    }

    function get_ownership_values()
    {
        return array(1 => 'Owned', 2 => 'Rental');
    }
    
    
    
    /**
     * Function returns array of distinct firm ids of related workcentres.
     * @param type $wcntrs = Array of workcentre ids. Eg: array(1,5,4,8,7);
     */
    function getFirmsOfWorkcentres($wcntrs)
    {
        $this->db->select("DISTINCT(wcntr_fk_firms)", false);
        $str = $this->array_query($wcntrs, 'wcntr_id');
        $this->db->where($str);
        $query = $this->db->get($this->table);
        $result = $query->result_array();
//        echo "<br>".$this->db->last_query();echo "<br>";
        $firms = array();
        foreach($result as $row)
            $firms[] = $row['wcntr_fk_firms'];
        return $firms;
    }

    function index($input, $user_id, $wlog_ref_id,$num_rows=false)
    {
        $this->db->from('workcentres,firms,employee_work_centre');

        //A user is permited to see only the workcentres which he has registered in that.
        $this->db->where('ewp_fk_auth_users', $user_id);
        $this->db->where('wcntr_id = ewp_fk_workcentres');   
        
        
        if($wlog_ref_id)
            $this->db->where($this->p_key,$wlog_ref_id);

        if (ifSetInput($input, 'wcntr_fk_firms'))
        {
            $this->db->where('wcntr_fk_firms', $input['wcntr_fk_firms']);
        }
        
        if (ifSetInput($input, 'wcntr_fk_workcentre_registration_details'))
        {
            $this->db->where('wcntr_fk_workcentre_registration_details', $input['wcntr_fk_workcentre_registration_details']);
        }

        if (ifSetInput($input, 'wcntr_ownership'))
        {
            $this->db->where('wcntr_ownership', $input['wcntr_ownership']);
        }

        if (ifSetInput($input, 'wcntr_name'))
        {
            $this->db->where('wcntr_name LIKE', $input['wcntr_name'] . '%');
        }

        if (ifSetInput($input, 'wcntr_bill_name'))
        {
            $this->db->where('wcntr_bill_name LIKE', $input['wcntr_bill_name'] . '%');
        }

        if (ifSetInput($input, 'wcntr_status'))
        {
            $this->db->where('wcntr_status', $input['wcntr_status']);
        }

        if (ifSetInput($input, 'wcntr_date_f'))
        {
            $this->db->where('wcntr_date >= ', getSqlDate($input['wcntr_date_f']));
        }

        if (ifSetInput($input, 'wcntr_date_t'))
        {
            $this->db->where('wcntr_date <= ', getSqlDate($input['wcntr_date_t']));
        }


        $this->db->where('firm_id = wcntr_fk_firms');
        $this->db->join('workcentre_registration_details', 'wcntr_fk_workcentre_registration_details = wrd_id', 'left');
        $this->db->join('rental_details', 'rntdt_fk_workcentre = wcntr_id', 'left');
        $this->db->join('owners', 'ownr_id = rntdt_fk_owners', 'left');
        

        $this->db->order_by('wcntr_name', 'asc');
        
        
        
        
        
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
/*  
SELECT * FROM (`workcentres`, `firms`, `employee_work_centre`) LEFT JOIN `rental_details` ON `rntdt_fk_workcentre` = `wcntr_id` LEFT JOIN `owners` ON `ownr_id` = `rntdt_fk_owners` WHERE `ewp_fk_auth_users` = '1' AND `wcntr_id` = ewp_fk_workcentres AND `firm_id` = wcntr_fk_firms ORDER BY `wcntr_name` asc LIMIT 0 
        

SELECT * FROM (`workcentres`, `firms`, `employee_work_centre`) LEFT JOIN `rental_details` ON `rntdt_fk_workcentre` = `wcntr_id` LEFT JOIN `owners` ON `ownr_id` = `rntdt_fk_owners` WHERE `ewp_fk_auth_users` = '1' AND `wcntr_id` = ewp_fk_workcentres AND `wcntr_status` = 1 AND `firm_id` = wcntr_fk_firms ORDER BY `wcntr_name` asc LIMIT 100        
    
    
*/
    /**
     * 
     * @param type $user_id
     * @param type $firm_id
     * @param type $status
     * @return type
     * 
     * Usage:- $admin_workcentres = $this->workcentres->get_users_workcentres_options($this->user_id,$this->firm_id,1,$this->is_admin);
     */
    function get_workcentres_options($user_id = '', $firm_id = '', $status = '')
    {
        $options = $this->get_workcentres($user_id, $firm_id, $status);
        $option = array();

        foreach ($options as $row)
        {
            $option[$row['wcntr_id']] = $row['wcntr_name'];
        }
        return $option;
    }

    function get_workcentres($user_id = '', $firm_id = '', $status = '')
    {
        if (!$user_id)
        {
            $this->db->from('workcentres,firms');
        }
        else
        {
            $this->db->select('workcentres.*,firms.*');
            $this->db->from('employee_work_centre,workcentres,firms');
            $this->db->where('employee_work_centre.ewp_fk_auth_users', $user_id);
            $this->db->where('workcentres.wcntr_id = employee_work_centre.ewp_fk_workcentres');
        }

        $this->db->where('firms.firm_id = workcentres.wcntr_fk_firms');

        if ($firm_id)
            $this->db->where('workcentres.wcntr_fk_firms', $firm_id);

        if ($status)
        {
            $this->db->where('workcentres.wcntr_status', $status);

            // if active
            if ($status == 1)
            {
                // A workcentre is considered as active only when both the workcentre itself and its parent "firm" are active.
                $this->db->where('firms.firm_status', 1);
            }
        }

        $this->db->order_by('wcntr_name', 'asc');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }
    
    

    function reports_Capital($wcntr_id, $from, $to)
    {
        $this->db->from($this->table);

        $select = " wcntr_id as ID, ";
        $select .= "'$this->table' as TBL, ";
        $select .= " wcntr_date as DATE, ";
        $select .= " 1 as ACC_TYPE, ";       //  ACC_TYPE = 1 is Income/Credit. ACC_TYPE = 2 is Expense/Debt.
        //$select .= " CONCAT(CONCAT('Workcentre: ', wcntr_name),' Capital') as DESCRIPTION, "; 
        $select .= " 'Capital' as DESCRIPTION, ";

        // Category for Balance Sheet. It must match with any of the 'Sub_category' described in my_controller/get_BS_Categories()
        $select .= " 'Capital' as BS, ";

        // Category for Profit & Loss.
        $select .= " '' as PL, ";

        $select .= 'wcntr_name as WORKCENTRE, ';
        $select .= " wcntr_capital as AMOUNT";

        $this->db->select($select, FALSE);

        if (is_array($wcntr_id))
        {
            $str = $this->array_query($wcntr_id, 'wcntr_id');
            $this->db->where($str);
        }
        else
            $this->db->where("wcntr_id", $wcntr_id);

        if ($from)
            $this->db->where('wcntr_date >= ', getSqlDate($from));

        if ($to)
            $this->db->where('wcntr_date <= ', getSqlDate($to));

        $this->db->where("wcntr_capital > ",0);
        $this->db->order_by('wcntr_name', 'asc');

        $query = $this->db->get();

        $result = $query->result_array();

        //echo "<br>".$this->db->last_query()."<br>";
        //echo "<br>";print_r($result);
        return $result;
    }

    function reports($wcntr_id, $from, $to)
    {
        $data = $this->reports_Capital($wcntr_id, $from, $to);
        return $data;
    }

    function get_workcentre_status()
    {
        return $this->get_status();
    }

    

    /**
     * function  returns the workcentre id's those are using the license.
     * @param type $wrd_id : Primary key value of the license
     */
    function get_workcentres_under_regname($wrd_id,$flag=false,$wcntr_status=1)
    {
        $where['wcntr_fk_workcentre_registration_details'] = $wrd_id;
        
        if($wcntr_status)
            $where['wcntr_status'] = $wcntr_status;
        
        if($flag)
            return $this->get_data('', $where);
        else 
            return $this->getIds($where);
    }
    
}

?>