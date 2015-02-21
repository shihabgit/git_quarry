<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Owners_model extends my_model
{

    function __construct()
    {
        parent::__construct();
        $this->loadTable(getTables('owners'));
        $this->p_key = 'ownr_id';
        $this->nameField = 'ownr_name';
        $this->statusField = 'ownr_status';
    }
    
    

    function get_owners_status()
    {
        return $this->get_status();
    }
}    


?>