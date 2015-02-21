
<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Party_vehicles_model extends my_model
{

    function __construct()
    {
        parent::__construct();
        $this->loadTable(getTables('party_vehicles'));
        $this->p_key = 'pvhcl_id';
        $this->nameField = 'pvhcl_no';
        $this->statusField = 'pvhcl_status';
    }

    function getVehicleByParty($pty_id, $pvhcl_status, $pvhcl_no = '')
    {
        if ($pvhcl_status)
            $this->db->where('pvhcl_status', $pvhcl_status);

        $this->db->where('pvhcl_fk_parties', $pty_id);

        if ($pvhcl_no)
            $this->db->where('pvhcl_no LIKE', $pvhcl_no . '%');
        
        $this->db->order_by($this->nameField, "asc");
        $result = $this->db->get($this->table);
        return $result->result_array();
    }

    function getPartyFromVehicle($pvhcl_id)
    {
        $data = $this->getById($pvhcl_id);
        return $data['pvhcl_fk_parties'];
    }

}

?>