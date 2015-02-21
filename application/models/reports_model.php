<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports_model extends my_model
{

    function __construct()
    {
        parent::__construct();
        //$this->loadTable(getTables(''));
        $this->p_key = '';
        $this->nameField = '';
        $this->statusField = '';
    }
    
    
}
?>