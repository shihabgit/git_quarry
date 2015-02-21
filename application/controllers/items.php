<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require 'my_controller.php';

class Items extends My_controller
{

   function __construct()
   {
      parent::__construct();

      $this->load->model('items_model', 'items');
      $this->load->model('item_heads_model', 'item_heads');
      $this->load->model('item_category_model', 'item_category');
      $this->load->model('units_model', 'units');
      $this->load->model('item_units_n_rates_model', 'iur');
      $this->load->model('opening_stock_model', 'os');

      // Determining is the logged in user allowed to go forward with the current action.
      $this->isAllowed();

      $this->per_page = 10;
      $this->table = 'items';
      $this->p_key = 'itm_id';
   }

   function get_wlog() //Called by ajax
   {
      $model = $this->items;
      $wlogs = $this->init_wlog($this->p_key, $model);

      if ($wlogs[0])
      {
         $latest_class = 'wlog_latest'; // Latest details about the worklog. Ie: the data from ref_tables, not from Tbl:backups.
         $wlog_fields = getWlogFields($this->table, 'keys');

         $status = $model->get_status();
         $itm_heads = $this->item_heads->get_option();
         $units = $this->units->get_option();

         echo "<table>";

         // Creating headers.
         $headers = getWlogFields($this->table, 'all');
         echo '<tr>';
         foreach ($headers as $head)
            echo '<th>' . $head . '</th>';
         echo '</tr>';


         foreach ($wlogs as $key => $row)
         {
            echo '<tr class="' . $latest_class . '">';
            $latest_class = '';
            foreach ($wlog_fields as $fld)
            {
               $val = $row[$fld];
               $edited = false;
               if (isset($wlogs[$key + 1]) && ($wlogs[$key + 1][$fld] !== $val))
                  $edited = true;

               if ($fld == 'itm_fk_item_head')
                  $val = $itm_heads[$row[$fld]];

               if ($fld == 'itm_fk_units')
                  $val = $units[$row[$fld]];

               if ($fld == 'itm_status')
                  $val = $status[$row[$fld]];

               if ($edited)
                  echo '<td><span class="wlog_changed">' . $val . '</span></td>';
               else
                  echo '<td>' . $val . '</td>';
            }
            echo '</tr>';
         }
         echo "</table>";
      }
      else
         echo "No Worklogs Found!!!";
   }

   
    function getItems()
    {
        $itmhd_id = $_GET['parent_id'];

        if (!$itmhd_id)
            return;

        $data = $this->items->get_items_by_itemHead($itmhd_id);
        $this->json_options($data, '--No Active Items--');
    }
   
   function add()
   {

      // Checking is the current task is enabled for the user
      $this->isAllowedTask($this->clsfunc);

      // Validating items.
      $v_config = validationConfigs($this->table, 'add', $this->table);
      $this->form_validation->set_rules($v_config);

      $this->form_validation->set_rules("itmcat_id", 'Item Category', 'required');
      // Validating units.
      $data['unit_row_count'] = $_POST ? count($_POST['unit']['name']) : 1;
      for ($i = 0; $i < $data['unit_row_count']; $i++)
      {
         $this->form_validation->set_rules("unit[name][$i]", 'Name', 'required|max_length[20]');

         // First unit will be considered as parent unit. so it will is not needed a 'relation with parent' field.
         if ($i != 0)
            $this->form_validation->set_rules("unit[rel][$i]", 'Relation', "required|numeric");
      }

      if (!$this->form_validation->run())
      {
         $data['title'] = 'Add Items';
         $data['heading'] = 'ADD NEW ITEM';
         $data['itmcats'] = $this->item_category->get_active_option();
         $data['itm_heads'] = array();

         if ($_POST)
         {
            $data['message'] = 'Some Errors Occured !';
            $data['message_level'] = 2;
            $data['itm_heads'] = $this->item_heads->get_active_option(array('itmhd_fk_item_category' => $this->input->post('itmcat_id')));
         }

         //echo validation_errors();
         $this->_render_page($this->clsfunc, $data);
         return;
      }

      // Recieving input 
      $input = $this->input->post();

      // Index to understand default unit of the item.
      $def = $input['unit']['def'] ? : 0;
      $defaultUnit = '';

      $batchNo = $this->units->getNextBatchNo();
      $unt_id = NULL;

      foreach ($input['unit']['name'] as $key => $val)
      {
         $tblData = array();
         $tblData['unt_batch'] = $batchNo;
         $tblData['unt_name'] = strtolower($input['unit']['name'][$key]);
         $tblData['unt_parent'] = $unt_id;

         // We considered the first unit as parent, others are childs.
         $tblData['unt_is_parent'] = ($key == 0) ? 1 : 2; //1=>Parent, 2=>Not Parent.
         $tblData['unt_relation'] = ($key == 0) ? '' : $input['unit']['rel'][$key]; // There shouldn't be relation for parent unit.
         $unt_id = $this->units->insert($tblData);
         if ($key == $def)
            $defaultUnit = $unt_id;
      }

      $input['items']['itm_fk_units'] = $defaultUnit;
      $input['items']['itm_name'] = ucwords(strtolower($input['items']['itm_name']));
      $itm_id = $this->items->insert($input['items']);

      // Worklog should be displayed in all active firms.
      $firms = implode(',', $this->firms->getIds(array('firm_status' => 1)));

      // Setting details for worklogs for Tbl:items.
      $msg = 'A new item';
      $msg .= ' <span class="wlg_name">' . $input['items']['itm_name'] . '</span> has been added.';
      $wlog_wc[0]['msg'] = $msg;      // $wlog_wc[0] Displayed as a general worklog. ie:- will not displayed under any workcentre.
      $wlog_wc[0]['action'] = $this->add;


      // Adding Tbl:items details to worklogs .
      $this->add_logs($this->table, $itm_id, get_url($this->table), get_popup_id($this->table), $wlog_wc, $this->add, $firms);

      //redirecting
      $this->session->set_flashdata('message', $msg);
      $this->session->set_flashdata('message_level', 1); // Success
      redirect($this->cls, 'refresh');
   }

   function index()
   {
      // Checking is the current task is enabled for the user
      $this->isAllowedTask($this->cls);
      
      // Receiving input.
      $input = $this->input_backup($this->items);
      $itmcat_id = isset($input['itmcat_id']) ? $input['itmcat_id'] : '';

      //Set the flash data message if there is one has set before redirected to this page.
      $data['message'] = $this->session->flashdata('message');
      $data['message_level'] = $this->session->flashdata('message_level');
      $data['title'] = "Items";
      $data['heading'] = "Items";
      $data['status'] = $this->item_heads->get_status();
      $data['itmcats'] = $this->item_category->get_active_option();
      $data['itm_heads'] = $this->item_heads->get_active_option(array('itmhd_fk_item_category' => $itmcat_id));
      $data['firms'] = $this->firms->get_firms_options($this->user_id, 1);
      $data['workcentres'] = $this->workcentres->get_workcentres($this->user_id, '', 1);

      // Setting default search options.
      if (!$_POST)
      {
         $input['itm_status'] = 1; //Default status is Active 
      }
      
      
      //If reffered from Worklogs;
      $wlog_ref_id = ($this->uri->segment(3) == 'wlogs') ? $this->uri->segment(4) : '';
      $data['table'] = $this->items->index($input, $wlog_ref_id);
      $data['units'] = array();

      if ($data['table'])
      {

         foreach ($data['table'] as $row)
         {
            // Getting units.
            $data['units'][$row['itm_id']] = $this->units->getUnitsOfBatch($row['unt_batch']);

            // Getting rates of item in different workcentres.
            $data['rates'][$row['itm_id']] = $this->get_rates($row['itm_id']);
         }
      }
      $this->_render_page($this->clsfunc, $data);
   }

   function get_rates($itm_id)
   {
      $data['units'] = $this->units->getUnitsOfItem($itm_id);
      $data['p_rates'] = array(); //Purchase rate.
      $data['s_rates'] = array(); // Sale rate.
      $workcentres = $this->workcentres->get_workcentres($this->user_id, '', 1);
//        $data['item_details'] = $this->items->getById($itm_id);
      foreach ($workcentres as $wc)
         foreach ($data['units'] as $unt)
         {
            $data['p_rates'][$wc['wcntr_id']][$unt['unt_id']] = $this->iur->getPurchaseRate($wc['wcntr_id'], $unt['unt_id'], $itm_id);
            $data['s_rates'][$wc['wcntr_id']][$unt['unt_id']] = $this->iur->getSalesRate($wc['wcntr_id'], $unt['unt_id'], $itm_id);
         }



      // The values must be derived from the variable $data['units'], because it has been sorted in parent->child order.
//        $data['units_option'] = array();
//        foreach ($data['units'] as $unit)
//            $data['units_option'][$unit['unt_id']] = $unit['unt_name'];
      return $data;
   }

   function before_edit()
   {

      // Checking is the current task is enabled for the user
      $this->isAllowedTask($this->cls . "/edit");

      if ($this->uri->segment(3) == 'edit')
         $emp_id = $this->uri->segment(4);
      else if (($this->uri->segment(3) != 'edit') || (!$this->uri->segment(4)))
      {
         $msg = "Permission Error :- Action is not supported.";
         $level = 2; // Having errors.
         $this->my_logout($msg, $level);
         return false;
      }

      $id = $this->uri->segment(4);
      $data['title'] = 'Edit Item';
      $data['heading'] = 'EDIT ITEM';
      $data['items'] = $this->items->getById($id);
      $data['itmcat_id'] = $this->item_heads->getCategory($data['items']['itm_fk_item_head']);
      $data['itmcats'] = $this->item_category->get_active_option();
      $data['itm_heads'] = $this->item_heads->get_active_option(array('itmhd_fk_item_category' => $data['itmcat_id']));
      $data['units'] = $this->units->getSiblings($data['items']['itm_fk_units']);

      $this->_render_page($this->cls . "/edit", $data);
      return;
   }

   function edit()
   {
      // Checking is the current task is enabled for the user
      $this->isAllowedTask($this->clsfunc);

      // Recieving input 
      $input = $this->input->post();
      $data = $input;
      $itm_id = $input['items']['itm_id'];

      // Taking old data for backup before update it.
      $prev = $this->items->getById($itm_id);

      // Validating items.
      $v_config = validationConfigs($this->table, 'edit', $this->table);
      $this->form_validation->set_rules($v_config);

      $this->form_validation->set_rules("itmcat_id", 'Item Category', 'required');

      if (!$this->form_validation->run())
      {
         $data['title'] = 'Edit Item';
         $data['heading'] = 'EDIT ITEM';
         $data['itmcats'] = $this->item_category->get_active_option();
         $data['itm_heads'] = $this->item_heads->get_active_option(array('itmhd_fk_item_category' => $this->input->post('itmcat_id')));
         $data['units'] = $this->units->getSiblings($prev['itm_fk_units']);
         $data['message'] = 'Some Errors Occured !';
         $data['message_level'] = 2;
         //echo validation_errors();
         $this->_render_page($this->clsfunc, $data);
         return;
      }


      // Formating  data
      $input['items']['itm_name'] = ucwords(strtolower($input['items']['itm_name']));

      // Asuming there was no editing made yet.
      $edited = false;
      $msg = "You didn't changed anything.";

      //Checking is there any changes made.
      foreach ($input['items'] as $key => $val)
      {
         if ($prev[$key] != $val)
         {
            // Confirms that the data has been edited.
            $edited = true;
            break;
         }
      }

      // If the data has been edited.
      if ($edited)
      {

         $this->items->save($input['items'], $itm_id);

         // Worklog should be displayed in all active firms.
         $firms = implode(',', $this->firms->getIds(array('firm_status' => 1)));

         // Setting details for worklogs for Tbl:items.
         $msg = 'The item';
         $msg .= ' <span class="wlg_name">' . $input['items']['itm_name'] . '</span> has been edited.';
         $wlog_wc[0]['msg'] = $msg;      // $wlog_wc[0] Displayed as a general worklog. ie:- will not displayed under any workcentre.
         $wlog_wc[0]['action'] = $this->add;


         // Adding Tbl:items details to worklogs .
         $wlog_id = $this->add_logs($this->table, $itm_id, get_url($this->table), get_popup_id($this->table), $wlog_wc, $this->edit, $firms);

         // Backing up previous details for data recovery needs.
         if (to_be_backed_up($this->table)) //If need to be backed up.
            $this->backups->backUpData($wlog_id, $prev, $this->table, $itm_id);
      }

      //redirecting
      $this->session->set_flashdata('message', $msg);
      $this->session->set_flashdata('message_level', 1); // Success
      redirect('items/index/action', 'refresh');
   }

   function toggleStatus()
   {
      // Checking is the current task is enabled for the user
      $this->isAllowedTask($this->cls . '/edit');
      $itm_id = $this->input->post('itm_id');
      $itm_details = $this->items->getById($itm_id);
      $prev_status = $this->items->getTableStatus($itm_id);

      // Toggling status.
      $this->items->toggleTableStatus($itm_id);

      $curnt_status = $this->items->getTableStatus($itm_id);

      // If Status changed.
      if ($prev_status != $curnt_status)
      {
         //Adding to worklogs
         // Worklog should be displayed in all active firms.
         $firms = implode(',', $this->firms->getIds(array('firm_status' => 1)));

         $mst_status = ($curnt_status == 1) ? "activated" : "deactivated";
         $msg = 'The item <span class="wlg_name">' . $itm_details['itm_name'] . '</span> has been ' . $mst_status . '.';
         $wlog_workcentre = 0; // The worklog is not under any workcentre, It is a General worklog.

         $wlog_wc[$wlog_workcentre]['msg'] = $msg;
         $wlog_wc[$wlog_workcentre]['action'] = $this->edit;
         $wlog_id = $this->add_logs($this->table, $itm_id, get_url($this->table), get_popup_id($this->table), $wlog_wc, $this->edit, $firms);

         // Backing up previous details for data recovery needs.
         if (to_be_backed_up($this->table)) //If need to be backed up.
            $this->backups->backUpData($wlog_id, $itm_details, $this->table, $itm_id);

         echo $itm_details['itm_name'] . " has been " . $mst_status;
      }
      else
         echo "Couldn't change status.";
   }

}

?>