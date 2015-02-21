
<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase_bill_head_model extends my_model
{

   function __construct()
   {
      parent::__construct();
      $this->loadTable(getTables('purchase_bill_head'));
      $this->p_key = '';
      $this->nameField = ''; // Don't change its value as 'vhcl_name'.
      $this->statusField = '';
   }
}
?>