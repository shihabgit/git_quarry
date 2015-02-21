<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Worklogs_model extends my_model
{

    function __construct()
    {
        parent::__construct();
        $this->loadTable(getTables('worklogs'));
        $this->p_key = 'wlog_id'; //ulog_id
        $this->nameField = '';
        $this->statusField = '';
    }

    function get_data()
    {
//SELECT * FROM `user_worklogs` WHERE FIND_IN_SET('12',wcntr_id);
    }

    function getStatus()
    {
        return array(1 => 'verified', 2 => 'Non-verified', 3 => 'Marked');
    }

    function checkVerifiedByUser($user_id, $ref)
    {
//        $this->db->from('worklogs,worklog_workcentres,verify');
//        $this->db->select();
    }

    
    /**
     *  Completely deleting worklogs.
     */
    function truncate_wlogs()
    {
        $this->db->truncate('backups');
        $this->db->truncate('verify');
        $this->db->truncate('worklog_workcentres');
        $this->db->truncate('worklogs');
    }
    
    function getUser($user_id, $ref)
    {
        $this->db->from('worklogs,employees');
        $this->db->select_max('wlog_id');

        $bs = " (CASE ";
        $bs .= " when emp_id = $user_id then 'Me' ";
        $bs .= " else emp_name ";
        $bs .= " END) ";

        $select = 'wlog_created, ';
        $select .= "$bs as 'user' ";

        $this->db->select($select);
        $this->db->where($ref);
        $this->db->where('wlog_status', 1);
        $this->db->where('wlog_fk_auth_users = emp_id');

        $result = $this->db->get();

//        echo "<br>" . $this->db->last_query() . "<br><br>";
        return $result->row_array();
    }

    function add_logs($wlog, $wlog_wc, $verifiers, $MY_WORKLOG, $loged_user, $datetime)
    {
        //Deactivating all worklogs with same 'wlog_ref_table' & 'wlog_ref_id' values.
        $this->update_where(array('wlog_status' => 2), array('wlog_ref_table' => $wlog['wlog_ref_table'], 'wlog_ref_id' => $wlog['wlog_ref_id']), 'worklogs');
//echo "<br>".$this->db->last_query();
        $this->db->insert('worklogs', $wlog);
        $wlog_id = $this->db->insert_id();
        $flag = FALSE;  // Variable to determine is the insertion fail/success.
        foreach ($wlog_wc as $wcntr_id => $data)
        {
            // Setting details for Tbl:worklog_workcentres
            $workcentre['wlog_wc_fk_worklogs'] = $wlog_id;
            $workcentre['wlog_wc_fk_workcentres'] = $wcntr_id;
            $workcentre['wlog_wc_message'] = $data['msg'];
            $workcentre['wlog_wc_action'] = $data['action']; // In user's view, what the action is made. (visit units/edit)

            // Adding data to Tbl:worklog_workcentres
            $this->db->insert('worklog_workcentres', $workcentre);
            $wlog_wc_id = $this->db->insert_id();
            foreach ($verifiers[$wcntr_id] as $emp_id)
            {
                // Setting details for Tbl:verify.
                $verify['verify_fk_worklog_workcentres'] = $wlog_wc_id;
                $verify['verify_fk_auth_users'] = $emp_id; //the id of the user who is verifying the data.
                $verify['verify_datime'] = $datetime;
                if ($emp_id == $loged_user)
                    $verify['verify_status'] = $MY_WORKLOG; // Related to Tbl:settings Key:MY_WORKLOG
                else
                    $verify['verify_status'] = 2; // Non-verified.
                    
                // Adding data to Tbl:verify
                $this->db->insert('verify', $verify);
                //echo "<br>".$this->db->last_query();
                $flag = $this->db->insert_id();
            }
        }

        //return $flag;
        return $wlog_id;
    }

    function index($input, $firm_id, $user_id, $is_admin, $num_rows = false)
    {
#----------------------------My Account Section ---------------------------------------------------#
        // Common for admin and non-admins.
        $common_where[] = "verify_fk_auth_users = '$user_id'";
        $common_where[] = "wlog_wc_id = verify_fk_worklog_workcentres";
        $common_where[] = "wlog_wc_fk_worklogs = wlog_id";

        $wlog_status = ifSetInput($input, 'wlog_status') ? $input['wlog_status'] : 1;
        $common_where[] = "wlog_status = '$wlog_status'";

        if (ifSetInput($input, 'my_verify_status'))
        {
            $str = $this->array_query(ifSetInput($input, 'my_verify_status'), 'verify_status');
            $common_where[] = $str;
        }
        else
            $common_where[] = "verify_status = '2'";


        if ($from = ifSetInput($input, 'wlog_from'))
        {
            $from = getSqlDate($from);
            $common_where[] = "wlog_created >= '$from'";
        }

        if ($to = ifSetInput($input, 'wlog_to'))
        {
            $to = getSqlDate($to);
            $common_where[] = "wlog_created <= '$to'";
        }


        if ($from = ifSetInput($input, 'my_verify_from'))
        {
            $from = getSqlDate($from);
            $common_where[] = "verify_datime >= '$from'";
        }

        if ($to = ifSetInput($input, 'my_verify_to'))
        {
            $to = getSqlDate($to);
            $common_where[] = "verify_datime <= '$to'";
        }

        // Conditions for only-admins. (The worklog is done not under a workcentre. Eg: Adding a firm/workcentre/owner)
        $general_where[] = "wlog_wc_fk_workcentres = '0'";
        $general_where[] = "FIND_IN_SET('$firm_id',wlog_firms)"; // Searching for $firm_id from a comma seperated values of Fld:wlog_firms
        // for admin and non-admin conditions. (The worklog is done under a workcentre.)
        $wcntr_where[] = "wcntr_id = wlog_wc_fk_workcentres";
        $wcntr_where[] = "wcntr_fk_firms = '$firm_id'";
        $wcntr_where[] = "wcntr_status = '1'";

        // Creating query to get worklogs not under a workcetre (General)
        if ($is_admin)
        {
            // creating where conditions
            $where = array_merge($common_where, $general_where);

            // Creating query string by connecting with 'AND' operator.
            $where = implode(' AND ', $where);

            $query_1 = " SELECT verify.*, worklog_workcentres.*, worklogs.*, '0' as wcntr_id,'General' as wcntr_name ";
            $query_1 .= " FROM (verify, worklog_workcentres, worklogs) ";
            $query_1 .= " WHERE $where ORDER BY wlog_created asc ";
        }

        // creating where conditions
        $where = array_merge($common_where, $wcntr_where);

        // Creating query string by connecting with 'AND' operator.
        $where = implode(' AND ', $where);

        // Creating query to get worklogs under a workcetre.
        $query_2 = " SELECT verify.*, worklog_workcentres.*, worklogs.*, wcntr_id, wcntr_name ";
        $query_2 .= " FROM (verify, worklog_workcentres, worklogs,workcentres) ";
        $query_2 .= " WHERE $where ORDER BY wlog_created asc ";
#------------------ End of My Account Section -------------------------------------------------------------#
#------------------ Other Verifier's Account Section -------------------------------------------------------------#
        $others_where = '';
        if ($verifier = ifSetInput($input, 'other_verifiers_id') && ifSetInput($input, 'other_verify_status'))
        {
            $str = $this->array_query(ifSetInput($input, 'other_verify_status'), 'verify_status');
            $others_where[] = $str;

            if ($from = ifSetInput($input, 'other_verify_from'))
            {
                $from = getSqlDate($from);
                $others_where[] = "verify_datime >= '$from'";
            }

            if ($to = ifSetInput($input, 'other_verify_to'))
            {
                $to = getSqlDate($to);
                $others_where[] = "verify_datime <= '$to'";
            }

            $others_where[] = "verify_fk_auth_users = '$verifier'";
            $others_where[] = "wlog_wc_id = verify_fk_worklog_workcentres";
            $others_where[] = "wlog_wc_fk_worklogs = wlog_id";
            $others_where = implode(' AND ', $others_where);

            $select = "verify_id as others_verify_id, ";
            $select .= "verify_fk_auth_users as verifier, ";
            $select .= "verify_datime as others_verify_datime, ";
            $select .= "verify_status as others_verify_status,";
            $select .= "wlog_id as others_wlog_id ";

            $query_others = " SELECT $select ";
            $query_others .= " FROM (verify, worklog_workcentres, worklogs) ";
            $query_others .= " WHERE $others_where ORDER BY wlog_created asc ";
            $query_others = "($query_others)";
        }
#------------------ End of other Verifier's Account Section -------------------------------------------------------------#     

        $str = $is_admin ? "(($query_1) UNION ALL ($query_2))" : "($query_2)";
        if ($others_where)
        {
            $str = "SELECT * FROM $str AS MY_WORKLOGS, $query_others AS OTHERS_WORKLOGS ";
            $str .= " WHERE MY_WORKLOGS.wlog_id = OTHERS_WORKLOGS.others_wlog_id ";
        }
        else
            $str = "SELECT * FROM $str AS MY_WORKLOGS ";

        if ($num_rows)
        {
            $query = $this->db->query($str);
            return count($query->result_array());
        }


        if ($input['PER_PAGE'])
            $query = $this->db->query($str . " LIMIT  $input[offset], $input[PER_PAGE] ");
        else
            $query = $this->db->query($str);

//        echo "<br>" . $this->db->last_query()."<br>";
        return $query->result_array();
    }

    /*
      function index($input, $limit, $offset, $firm_id, $user_id)
      {
      $this->db->from('verify,worklog_workcentres,worklogs,workcentres');

      //$this->db->select("DISTINCT(id),auth_users.*,employees.*", false);
      //$this->db->join('employee_work_centre', 'ewp_fk_auth_users = emp_id', 'left');

      $this->db->where('verify_fk_auth_users', $user_id);

      $this->db->where('wlog_wc_id = verify_fk_worklog_workcentres');

      $this->db->where('wlog_wc_fk_worklogs = wlog_id');

      $this->db->where('wcntr_id = wlog_wc_fk_workcentres');

      $this->db->where('wcntr_fk_firms', $firm_id);
      //$this->db->order_by('first_name', 'asc');



      if (!$limit)
      $query = $this->db->get();
      else
      $query = $this->db->get('', $limit, $offset);

      $result = $query->result_array();

      //        echo "<br>" . $this->db->last_query()."<br>";

      return $result;
      }
     */
}
