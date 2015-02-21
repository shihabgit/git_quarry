<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require 'my_controller.php';

class Workcentre_rates extends My_controller
{

   function __construct()
   {
      parent::__construct();

      $this->load->model('workcentre_rates_model', 'workcentre_rates');
      $this->load->model('items_model', 'items');
      $this->load->model('item_heads_model', 'item_heads');
      $this->load->model('item_category_model', 'item_category');
      $this->load->model('units_model', 'units');

      // Determining is the logged in user allowed to go forward with the current action.
      $this->isAllowed();

      $this->per_page = 10;
      $this->table = 'workcentre_rates';
      $this->p_key = 'wrt_id';
   }

   function get_wlog()//Called by ajax
   {
      $model = $this->workcentre_rates;
      $wlogs = $this->init_wlog($this->p_key, $model);

      if ($wlogs)   // Format: 1 for deleted worklogs.
      {
         $latest_class = 'wlog_latest'; // Latest details about the worklog. Ie: the data from ref_tables, not from Tbl:backups.
         $wlog_fields = getWlogFields($this->table, 'keys');

         echo "<table>";

         // Creating headers.
         $headers = getWlogFields($this->table, 'all');
         echo '<tr>';
         foreach ($headers as $head)
            echo '<th>' . $head . '</th>';
         echo '</tr>';

         foreach ($wlogs as $key => $row)
         {
            if ($row)   // Format: 2 for deleted worklogs.
            {
               $latest_class = ''; // Format: 3 for deleted worklogs.

               echo '<tr class="' . $latest_class . '">';
               $latest_class = '';
               foreach ($wlog_fields as $fld)
               {
                  $val = $row[$fld];
                  $edited = false;
                  if (isset($wlogs[$key + 1]) && ($wlogs[$key + 1][$fld] !== $val))
                     $edited = true;


                  if ($fld == 'wrt_fk_workcentres_to')
                     $val = $this->workcentres->getNameById($row[$fld]);
                  else if ($fld == 'wrt_fk_items')
                     $val = $this->items->getNameById($row[$fld]);
                  else if ($fld == 'wrt_fk_units')
                     $val = $this->units->getNameById($row[$fld]);


                  if ($edited)
                     echo '<td><span class="wlog_changed">' . $val . '</span></td>';
                  else
                     echo '<td>' . $val . '</td>';
               }
               echo '</tr>';
            }
         }
         echo "</table>";
      }
      else
         echo "No Worklogs Found!!!";
   }

   function add()
   {

      // Checking is the current task is enabled for the user
      $this->isAllowedTask($this->clsfunc);

      // Validating items.
      $v_config = validationConfigs($this->table);
      $this->form_validation->set_rules($v_config);

      $this->form_validation->set_rules("itmcat_id", 'Item Category', 'required');
      $this->form_validation->set_rules("itmhd_id", 'Item Head', 'required');

      if (!$this->form_validation->run())
      {
         $data['title'] = 'Add Workcentre Rates';
         $data['heading'] = 'ADD RATES BETWEEN WORKCENTRES';
         $data['itmcats'] = $this->item_category->get_active_option();
         $data['itm_heads'] = array();
         $data['items'] = array();
         $data['units'] = array();

         $data['workcentres'] = $this->workcentres->get_workcentres_options($this->user_id, $this->firm_id, 1);


         if ($_POST)
         {
            $data['message'] = 'Some Errors Occured !';
            $data['message_level'] = 2;
            $data['itm_heads'] = $this->item_heads->get_active_option(array('itmhd_fk_item_category' => $this->input->post('itmcat_id')));
            $data['items'] = $this->items->get_items_by_itemHead($this->input->post('itmhd_id'));

            if ($this->input->post('wrt_fk_items'))
            {
               $itm_id = $this->input->post('wrt_fk_items');
               $itm_fk_units = $this->items->getFieldById($itm_id, 'itm_fk_units');
               $unt_batch = $this->units->getFieldById($itm_fk_units, 'unt_batch');
               $units = $this->units->getUnitsOfBatch($unt_batch);
               $data['units'] = $this->units->make_options_2($units);
            }
         }

         //echo validation_errors();
         $this->_render_page($this->clsfunc, $data);
         return;
      }

      // Recieving input 
      $input = $this->get_inputs();

      $itm_name = $this->items->getNameById($input['wrt_fk_items']);
      $f_wcs = $input['wrt_fk_workcentres_from'];
      $t_wcs = $input['wrt_fk_workcentres_to'];

      $wrt_id = '';
      
      $error_msg = '';

      foreach ($f_wcs as $f_wc)
      {
         $tbl_data = array();
         $tbl_data['wrt_fk_workcentres_from'] = $f_wc;
         $tbl_data['wrt_fk_items'] = $input['wrt_fk_items'];
         $tbl_data['wrt_fk_units'] = $input['wrt_fk_units'];
         $tbl_data['wrt_s_rate'] = $input['wrt_s_rate'];
         foreach ($t_wcs as $t_wc)
         {
            // If both 'Workcentre From' and 'Workcentre To' are same, the rate should not be inserted.
            if ($f_wc == $t_wc)
            {  
               $error_msg  = 'Workcentre-From and Workcentre-To are equel';
               continue;
            }

            $tbl_data['wrt_fk_workcentres_to'] = $t_wc;


            // If the rate already added, it will be replaced by new rate.
            if ($wrt_id = $this->is_rate_exist($tbl_data))
            {
               $this->replaceRate($wrt_id, $tbl_data, ' when added new rates.');
               continue;
            }

            // inserting new rates.
            $wrt_id = $this->workcentre_rates->insert($tbl_data);
            $t_wc_name = $this->workcentres->getNameById($t_wc);

            $msg = "Selling rate of item <span class='wlg_name'>$itm_name</span>";
            $msg .= " for the workcentre <span class='wlg_name'>$t_wc_name</span> has been added";

            // Adding Tbl:workcentre_rates details to worklogs .
            $this->send_wlog('workcentre_rates', $wrt_id, $msg, $this->add, $this->add, $f_wc);
         }
      }

      //redirecting
      if ($wrt_id)
      {
         $this->session->set_flashdata('message', "Rate of item <span class='wlg_name'>$itm_name</span> added successfully");
         $this->session->set_flashdata('message_level', 1); // Success
      }
      else
      {
         $this->session->set_flashdata('message', "Insertion failed; $error_msg");
         $this->session->set_flashdata('message_level', 2); // Failure
      }

      redirect($this->cls, 'refresh');
   }

   function is_rate_exist($input)
   {
      $unique['wrt_fk_workcentres_from'] = $input['wrt_fk_workcentres_from'];
      $unique['wrt_fk_workcentres_to'] = $input['wrt_fk_workcentres_to'];
      $unique['wrt_fk_items'] = $input['wrt_fk_items'];
      $unique['wrt_fk_units'] = $input['wrt_fk_units'];
      $wrt_id = $this->workcentre_rates->is_exists_2($unique);
      return $wrt_id;
   }

   function replaceRate($wrt_id, $data, $post_msg = '.')
   {

      
      // Taking previous data before editing.
      $prev = $this->workcentre_rates->getById($wrt_id);

      // Editing
      $this->workcentre_rates->save($data, $wrt_id);

      // Details after edit
      $cur = $this->workcentre_rates->getById($wrt_id);

      // Checking is anything edited.
      $edited = $this->isEdited($prev, $cur);

      // If edited, creating worklog.
      if ($edited)
      {
         $wcntr_id = $prev['wrt_fk_workcentres_from'];
         $t_wc_id = $prev['wrt_fk_workcentres_to'];
         $t_wc_name = $this->workcentres->getNameById($t_wc_id);
         $itm_id = $prev['wrt_fk_items'];
         $itm_name = $this->items->getNameById($itm_id);

         // Setting message for worklogs.
         $msg = "Selling rate of item <span class='wlg_name'>$itm_name</span>";
         $msg .= " for Workcentre <span class='wlg_name'>$t_wc_name</span> has been edited$post_msg";

         // Adding Tbl:workcentre_rates details to worklogs .
         $this->send_wlog($this->table, $wrt_id, $msg, $this->edit, $this->edit, $wcntr_id, $prev, '', WARNING);
      }
   }

   function createHTML($data)
   {
      $html = '<table class="unt_tbl">';
      $html .= '<tbody>';
      $html .= '<tr>';
      $html .= '<th>Unit</th>';
      $html .= '<th>Selling Rate</th>';
      $html .= '</tr>';
      foreach ($data as $row)
      {
         $html .= '<tr>';
         $html .= '<td>' . $row['unit'] . '<input type="hidden" class="wrt_id" value="' . $row['id'] . '"></td>';
         $html .= '<td> <input type="text" class="wrt_s_rate numberOnly" value="' . $row['s_rate'] . '"></td>';
         $html .= '</tr>';
      }

      $html .= '</tbody>';
      $html .= '</table>';

      return $html;
   }
   
   function beforeEdit()
   {
      // Checking is the current task is enabled for the user
      $this->isAllowedTask("workcentre_rates/edit");

      $wrt_ids = $_POST['wrt_ids'];
      $rows = $this->workcentre_rates->getById($wrt_ids);
      $details = array();
      if ($rows)
      {
         foreach ($rows as $key => $row)
         {
            $details[$key]['id'] = $row['wrt_id'];
            $details[$key]['unit'] = $this->units->getNameById($row['wrt_fk_units']);
            $details[$key]['s_rate'] = $row['wrt_s_rate'];
         }

         $html = $this->createHTML($details);
         echo $html;
      }
      return;
   }

   function isNumeric($val)
   {
      if ($val)
      {
         if (is_numeric($val))
            return TRUE;
         else
            return FALSE;
      }

      return TRUE;
   }

   function edit()
   {
      // Checking is the current task is enabled for the user
      $task = taskEnabled($this->clsfunc);
      if ($task != 1)
      {
         echo $task;
         return;
      }

      // Recieving input 
      $input = $this->input->post(); // $this->get_inputs();
      
      // Validating 
      foreach ($input['ids'] as $key => $wrt_id)
      {
         if (!$this->isNumeric($input['s_rate'][$key]))
         {
            echo $this->errorTitle . '<div class="pop_failure">Rate must contain only numeric value.</div>';
            return;
         }
      }

      // After successfull validation.
      foreach ($input['ids'] as $key => $wrt_id)
      {
         $tbl_data = array();
         $tbl_data['wrt_s_rate'] = $input['s_rate'][$key];
         $this->replaceRate($wrt_id, $tbl_data);
      }

      echo 1; // Successfully edited.
   }
   
   function delete()
   {
      $wrt_ids = $this->input->post('wrt_ids');
      foreach ($wrt_ids as $wrt_id)
      {
         // Taking backup before delete.
         $prev = $this->workcentre_rates->getById($wrt_id);

         // Deleting
         $this->workcentre_rates->remove($wrt_id);

         // If Deleted
         if (!$this->workcentre_rates->getById($wrt_id))
         {
            $wc_f_id = $prev['wrt_fk_workcentres_from'];
            $wc_t_id = $prev['wrt_fk_workcentres_to'];
            $wc_t_name = $this->workcentres->getNameById($wc_t_id);
            $itm_id = $prev['wrt_fk_items'];
            $itm_name = $this->items->getNameById($itm_id);

            // Setting message for worklogs.
            $msg = "Rate of item <span class='wlg_name'>$itm_name</span>";
            $msg .= " for workcentre <span class='wlg_name'>$wc_t_name</span> has been deleted";

            // Adding Tbl:workcentre_rates details to worklogs .
            $this->send_wlog($this->table, $wrt_id, $msg, $this->delete, $this->delete, $wc_f_id, $prev, '', WARNING);
         }
      }

      echo "Rates deleted successfully.";
   }

   function index()
   {
      // Checking is the current task is enabled for the user
      $this->isAllowedTask($this->cls);

      // Receiving input.
      $input = $this->get_pagination_inputs($this->workcentre_rates);

      //Set the flash data message if there is one has set before redirected to this page.
      $data['message'] = $this->session->flashdata('message');
      $data['message_level'] = $this->session->flashdata('message_level');

      $data['offset'] = $input['offset'];
      $this->per_page = $_POST ? $input['PER_PAGE'] : $this->per_page;

      $data['title'] = "Workcentre Rates";
      $data['heading'] = "Search Rates For Workcentres";
      $data['itmcats'] = $this->item_category->get_active_option();
      $itmcat_id = isset($input['itmcat_id']) ? $input['itmcat_id'] : '';
      $data['itm_heads'] = $this->item_heads->get_active_option(array('itmhd_fk_item_category' => $itmcat_id));
      $data['workcentres'] = $this->workcentres->get_workcentres_options($this->user_id, $this->firm_id, 1);
      $wcntr_ids = $this->workcentres->getIdsFromOption($data['workcentres']);

      // Setting default search options.
      if (!$_POST)
      {
         $input['itm_status'] = 1; //Default status is Active 
      }

      $data['table'] = $this->workcentre_rates->index($input, $wcntr_ids);
      $data['num_rows'] = $this->workcentre_rates->index($input, $wcntr_ids, true);
      $data['units'] = array();
      $data['wrts'] = array();

      if ($data['table'])
      {
         $wcntrs_from = ifSetInput($input, 'wcntr_id')? : $wcntr_ids;

         foreach ($data['table'] as $row)
         {
            $itm_id = $row['itm_id'];
            $itm_fk_units = $this->items->getFieldById($itm_id, 'itm_fk_units');
            $unt_batch = $this->units->getFieldById($itm_fk_units, 'unt_batch');

            // Getting units.
            $data['units'][$itm_id] = $this->units->getUnitsOfBatch($row['unt_batch']);
            $data['unit_details'][$itm_id] = $this->units->groupQueryResultById($data['units'][$itm_id]);

            // Getting Item rates in workcentre (represented by Fld:wrt_fk_workcentres_from) for workcentres (represented by Fld:wrt_fk_workcentres_to)
            foreach ($wcntrs_from as $wc_from_id)
            {
               # --------------------------------        IMPORTANT      ------------------------------------------------------#
               # When a user edited the unit of an item, Actually it is not the editing of the existing units.                #
               # But it is realy the creation of a new batch of units.                                                        #
               # So if the units of an item edited, The unit rates of the item set in Tbl:workcentre_rates become invalid.    #
               # so it must be avoid when listing.                                                                            #
               # -------------------------------------------------------------------------------------------------------------#

               $data['wcntrs_to'][$itm_id][$wc_from_id] = $this->workcentre_rates->get_WorkcentresTo_InWorkcentreRates($itm_id, $wc_from_id, $unt_batch);

               if ($data['wcntrs_to'][$itm_id][$wc_from_id])
                  foreach ($data['wcntrs_to'][$itm_id][$wc_from_id] as $wc_to_rows)
                     $data['wrts'][$itm_id][$wc_from_id][$wc_to_rows['wcntr_id']] = $this->workcentre_rates->get_WorkcentreTo_Rates($itm_id, $wc_from_id, $wc_to_rows['wcntr_id'], $unt_batch);
            }
         }
      }

      // Initializing pagination
      $data = array_merge($data, $this->initPagination($data['table'], $data['num_rows'], $input['offset']));

      $this->_render_page($this->clsfunc, $data);
   }

   

   function checkNull($val)
   {
      if (!is_array($val))
      {
         $this->form_validation->set_message('checkNull', '%s field is required');
         return FALSE;
      }

      if (array_filter($val, 'trim'))
         return TRUE;
      else
      {
         $this->form_validation->set_message('checkNull', '%s field is required');
         return FALSE;
      }
   }

}

?>