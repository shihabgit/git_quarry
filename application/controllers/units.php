<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require 'my_controller.php';

class Units extends My_controller
{

   function __construct()
   {
      parent::__construct();

      $this->load->model('units_model', 'units');
      $this->load->model('items_model', 'items');
      $this->load->model('item_units_n_rates_model', 'iur');

      // Determining is the logged in user allowed to go forward with the current action.
      $this->isAllowed();

      $this->table = 'units';
      $this->p_key = 'unt_id';
   }

   function getUnitsByItem()
   {
      $itm_id = $_GET['parent_id'];
      $itm_fk_units = $this->items->getFieldById($itm_id, 'itm_fk_units');
      $unt_batch = $this->units->getFieldById($itm_fk_units, 'unt_batch');
      $units = $this->units->getUnitsOfBatch($unt_batch);
      $options = $this->units->make_options_2($units);
      $this->json_options($options, '-- No Units --');
   }

   //Called by ajax.
   function edit()
   {
      // Checking is the current task is enabled for the user
      $this->isAllowedTask($this->clsfunc);

      // Recieving input 
      $input = $this->input->post();

      $item_details = $this->items->getById($input['itm_id']);

      // Validating units.
      $unit_row_count = count($input['unit_name']);
      for ($i = 0; $i < $unit_row_count; $i++)
      {
         $this->form_validation->set_rules("unit_name[$i]", 'Unit Name', 'required|max_length[20]');
         $this->form_validation->set_rules("unt_relation[$i]", 'Relation', "required|numeric");
      }

      $this->form_validation->set_error_delimiters('<div class="pop_failure">', '</div>');

      if (!$this->form_validation->run())
      {
         $message = validation_errors();
         if ($message)
         {
            echo $this->errorTitle . $message;
            return;
         }
      }


      // Index to understand default unit of the item.
      $def = $input['def'] ? : 0;
      $defaultUnit = '';

      $batchNo = $this->units->getNextBatchNo();

      //Taking previous parent id as unt_id.
      $unt_id = $this->getParentUnit2($input['itm_id']);


      foreach ($input['unit_name'] as $key => $val)
      {
         $tblData = array();
         $tblData['unt_batch'] = $batchNo;
         $tblData['unt_name'] = strtolower($input['unit_name'][$key]);
         $tblData['unt_parent'] = $unt_id;

         // We considered the first unit as parent, others are childs.
         $tblData['unt_is_parent'] = ($key == 0) ? 1 : 2; //1=>Parent, 2=>Not Parent.
         $tblData['unt_relation'] = $input['unt_relation'][$key];
         $unt_id = $this->units->insert($tblData);
         if ($key == $def)
            $defaultUnit = $unt_id;
      }

      $tblData = array();
      $tblData['itm_fk_units'] = $defaultUnit;

      // Editing default unit of the item.
      $this->items->save($tblData, $input['itm_id']);


      // Worklog should be displayed in all active firms.
      $firms = implode(',', $this->firms->getIds(array('firm_status' => 1)));

      // Setting details for worklogs for Tbl:items.
      $msg = 'The units of ';
      $msg .= ' <span class="wlg_name">' . $item_details['itm_name'] . '</span> has been changed.';
      $wlog_wc[0]['msg'] = $msg;      // $wlog_wc[0] Displayed as a general worklog. ie:- will not displayed under any workcentre.
      $wlog_wc[0]['action'] = $this->edit;    // According to the user, the action is editing.

      $wlog_ref_action = $this->add; // But really here we are adding a new batch and its unit, not editing.
      // Adding Tbl:items details to worklogs .
      $this->add_logs($this->table, $input['itm_id'], get_url($this->table), get_popup_id($this->table), $wlog_wc, $wlog_ref_action, $firms);

      // After changed units, all previous rates must be deleted. Because it is useless.
      $where['iur_fk_items'] = $input['itm_id'];
      $this->iur->delete_where($where);

      echo 1;
   }

   // Called by Ajax
   function getParentUnit()
   {
      $itm_id = $this->input->post('itm_id');
      $unt_id = $this->items->getUnitId($itm_id);
      $parent_details = $this->units->getBatchParentByUnit($unt_id);
      if (isset($parent_details['unt_name']))
         echo $parent_details['unt_name'];
      else
         echo "Logical Error!";
   }

   function getParentUnit2($itm_id)
   {
      $unt_id = $this->items->getUnitId($itm_id);
      $parent_details = $this->units->getBatchParentByUnit($unt_id);
      return $parent_details['unt_id'];
   }

}

?>