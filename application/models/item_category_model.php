<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Item_category_model extends my_model
{

    function __construct()
    {
        parent::__construct();
        $this->loadTable(getTables('item_category'));
        $this->p_key = 'itmcat_id';
        $this->nameField = 'itmcat_name';
        $this->statusField = 'itmcat_status';
    }

}
?>