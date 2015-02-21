<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Workcentre_registration_details_model extends my_model
{

    function __construct()
    {
        parent::__construct();
        $this->loadTable(getTables('workcentre_registration_details'));
        $this->p_key = 'wrd_id';
        $this->nameField = 'wrd_name';
        $this->statusField = 'wrd_status';
    }
    
    function get_registered_workcentres($user_id='',$firm_id='',$option=true,$wrd_id='',$wcntr_status=ACTIVE,$wrd_status=ACTIVE)
    {
       //
        if (!$user_id)
        {
            $this->db->from('workcentres,firms,workcentre_registration_details');
        }
        else
        {
            $this->db->select('workcentres.*,firms.*,workcentre_registration_details.*');
            $this->db->from('employee_work_centre,workcentres,firms,workcentre_registration_details');
            $this->db->where('employee_work_centre.ewp_fk_auth_users', $user_id);
            $this->db->where('workcentres.wcntr_id = employee_work_centre.ewp_fk_workcentres');
        }

        $this->db->where('firms.firm_id = workcentres.wcntr_fk_firms');
        

        if ($firm_id)
            $this->db->where('workcentres.wcntr_fk_firms', $firm_id);
        
        if($wrd_id)
           $this->db->where('wrd_id', $wrd_id);
        $this->db->where('wrd_id = wcntr_fk_workcentre_registration_details');

        if ($wcntr_status)
        {
            $this->db->where('workcentres.wcntr_status', $wcntr_status);

            // if active
            if ($wcntr_status == 1)
            {
                // A workcentre is considered as active only when both the workcentre itself and its parent "firm" are active.
                $this->db->where('firms.firm_status', 1);
            }
        }
        
        if($wrd_status)
           $this->db->where('wrd_status', $wrd_status);

        $this->db->order_by('wcntr_name', 'asc');
        $this->db->order_by('wrd_name', 'asc');
        $query = $this->db->get();
        $result = $query->result_array();
        
        if($option)
          $result = $this->make_options($result,'wcntr_id','wcntr_name');
        
        return $result;
    }
    
    /**
     * Function returns registrations that are not used yet by any workcentres. 
     * @param type $wrd_status
     * @return type
     */
//    function getFreeRegistrations($wrd_status = 1)
//    {
//        $this->db->from($this->table);
//
//        if ($wrd_status)
//            $this->db->where($this->statusField, $wrd_status);
//
//        $subQuery = "(SELECT wcntr_fk_workcentre_registration_details FROM workcentres)";
//        $this->db->where($this->p_key . " NOT IN " . $subQuery);
//
//        $this->db->order_by($this->nameField, "asc");
//
//        $result = $this->db->get();
//        return $result->result_array();
//    }

    


}

?>