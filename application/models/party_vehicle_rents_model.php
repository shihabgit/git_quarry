<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Party_vehicle_rents_model extends my_model
{

    function __construct()
    {
        parent::__construct();
        $this->loadTable(getTables('party_vehicle_rents'));
        $this->p_key = 'pvr_id';
        $this->nameField = '';
        $this->statusField = '';
    }

    function index($pdst_id, $wcntr_id, $pvhcl_status = 1)
    {
        $this->db->from("party_vehicles,$this->table");
        $this->db->where('pvr_fk_party_destinations', $pdst_id);
        $this->db->where('pvr_fk_workcentres', $wcntr_id);
        $this->db->where('pvhcl_id = pvr_fk_party_vehicles');

        if ($pvhcl_status)
            $this->db->where('pvhcl_status', $pvhcl_status);

        $this->db->order_by('pvhcl_no', "asc");

        $result = $this->db->get();
        return $result->result_array();
    }

    

    function getFreeVehicles($pty_id, $pvhcl_status, $pdst_id, $wcntr_id)
    {
        $subQuery = "(SELECT pvr_fk_party_vehicles FROM $this->table ";
        $subQuery .= " WHERE pvr_fk_party_destinations = $pdst_id AND pvr_fk_workcentres = $wcntr_id )";
        
        $this->db->from("party_vehicles");
        
        if ($pvhcl_status)
            $this->db->where('pvhcl_status', $pvhcl_status);
        
        $this->db->where('pvhcl_fk_parties', $pty_id);
        
        $this->db->where(" pvhcl_id NOT IN " . $subQuery,NULL,FALSE); // Removing backtick protection from WHERE.
        
        $this->db->order_by('pvhcl_no', "asc");

        $result = $this->db->get();
        return $result->result_array();
    }

}

?>