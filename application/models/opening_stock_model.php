<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Opening_stock_model extends my_model
{

    function __construct()
    {
        parent::__construct();
        $this->loadTable(getTables('opening_stock'));
        $this->p_key = 'ostk_id';
        $this->nameField = '';
        $this->statusField = '';
    }
}
?>