<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require 'my_controller.php';

class Individual_rates extends My_controller
{

   function __construct()
   {
      parent::__construct();

      $this->load->model('individual_rates_model', 'individual_rates');
      $this->load->model('items_model', 'items');
      $this->load->model('item_heads_model', 'item_heads');
      $this->load->model('item_category_model', 'item_category');
      $this->load->model('units_model', 'units');
      $this->load->model('parties_model', 'parties');
      $this->load->model('party_destinations_model', 'party_destinations');
      $this->load->model('destination_workcentres_model', 'destination_workcentres');

      // Determining is the logged in user allowed to go forward with the current action.
      $this->isAllowed();

      $this->per_page = 10;
      $this->table = 'individual_rates';
      $this->p_key = 'indv_id';
   }

   function get_wlog()//Called by ajax
   {
      $model = $this->individual_rates;
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


                  if ($fld == 'indv_fk_workcentres')
                     $val = $this->workcentres->getNameById($row[$fld]);
                  else if ($fld == 'indv_fk_party_destinations')
                  {
                     $pdst_id = $row[$fld];
                     $pdst_name = $this->party_destinations->getNameById($pdst_id);
                     $pty_id = $this->party_destinations->getFieldById($pdst_id, 'pdst_fk_parties');
                     $pty_name = $this->parties->getNameById($pty_id);
                     $val = "$pty_name - $pdst_name";
                  }
                  else if ($fld == 'indv_fk_items')
                     $val = $this->items->getNameById($row[$fld]);
                  else if ($fld == 'indv_fk_units')
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
      $this->form_validation->set_rules("pty_id", 'Party', 'callback_checkNull');
      $this->form_validation->set_rules("indv_p_rate", 'Rate', 'numeric|callback_rateCheck');
      $this->form_validation->set_rules("indv_s_rate", 'Rate', 'numeric');


      if (!$this->form_validation->run())
      {
         $data['title'] = 'Add Party Rates';
         $data['heading'] = 'ADD RATES FOR PARTIES';
         $data['itmcats'] = $this->item_category->get_active_option();
         $data['itm_heads'] = array();
         $data['items'] = array();
         $data['units'] = array();
         $data['opt_group'] = array();
         $data['parties'] = array();
         $data['destinations'] = array();
         $data['workcentres'] = $this->workcentres->get_workcentres_options($this->user_id, $this->firm_id, 1);


         if ($_POST)
         {
            $data['message'] = 'Some Errors Occured !';
            $data['message_level'] = 2;
            $data['itm_heads'] = $this->item_heads->get_active_option(array('itmhd_fk_item_category' => $this->input->post('itmcat_id')));
            $data['items'] = $this->items->get_items_by_itemHead($this->input->post('itmhd_id'));

            if ($this->input->post('indv_fk_items'))
            {
               $itm_id = $this->input->post('indv_fk_items');
               $itm_fk_units = $this->items->getFieldById($itm_id, 'itm_fk_units');
               $unt_batch = $this->units->getFieldById($itm_fk_units, 'unt_batch');
               $units = $this->units->getUnitsOfBatch($unt_batch);
               $data['units'] = $this->units->make_options_2($units);
            }

            if ($selected_workcentres = $this->input->post('indv_fk_workcentres'))
            {
               // Getting all active parties under users workcentres.
               $wcntr_ids = $this->input->post('indv_fk_workcentres');
               $parties = $this->parties->getPartiesUnderWorkcentre($wcntr_ids);
               $data['parties'] = $this->parties->make_options_2($parties);
            }

            if ($selected_parties = $this->input->post('pty_id'))
            {
               $data['opt_group'] = $this->parties->getOptionFromIds($selected_parties);
               $data['destinations'] = $this->party_destinations->getDestinationsUnderWorkcentre($wcntr_ids, $selected_parties);
            }
         }

         //echo validation_errors();
         $this->_render_page($this->clsfunc, $data);
         return;
      }

      // Recieving input 
      $input = $this->get_inputs();

      $itm_name = $this->items->getNameById($input['indv_fk_items']);
      $workcentres = $input['indv_fk_workcentres'];
      $indv_id = '';

      foreach ($workcentres as $wc_id)
      {
         $tbl_data = array();
         $tbl_data['indv_fk_workcentres'] = $wc_id;
         $tbl_data['indv_fk_items'] = $input['indv_fk_items'];
         $tbl_data['indv_fk_units'] = $input['indv_fk_units'];
         $tbl_data['indv_p_rate'] = $input['indv_p_rate'];
         $tbl_data['indv_s_rate'] = $input['indv_s_rate'];
         foreach ($input['indv_fk_party_destinations'] as $pdst_id)
         {
            $tbl_data['indv_fk_party_destinations'] = $pdst_id;

            # The rate of an Item in a Workcentre for a Party Destinations must be neglected
            # if the Destination is not registered under the Workcentre.

            $where['dwc_fk_workcentres'] = $wc_id;
            $where['dwc_fk_party_destinations'] = $pdst_id;
            $where['dwc_status'] = ACTIVE;

            // If the destination is not registered under the workcenter.
            if (!$this->destination_workcentres->is_exists($where))
               continue;

            // If the rate already added, it will be replaced by new rate.
            if ($indv_id = $this->is_rate_exist($tbl_data))
            {
               $this->replaceRate($indv_id, $tbl_data, 'add', ' when added new values.');
               continue;
            }

            // inserting new rates.
            $indv_id = $this->individual_rates->insert($tbl_data);

            // Setting message for worklogs.
            $pdst_name = $this->party_destinations->getNameById($pdst_id);
            $pty_id = $this->party_destinations->getFieldById($pdst_id, 'pdst_fk_parties');
            $pty_name = $this->parties->getNameById($pty_id);

            $msg = "Rate of item <span class='wlg_name'>$itm_name</span>";
            $msg .= " for party <span class='wlg_name'>$pty_name: $pdst_name</span> has been added";

            // Adding Tbl:individual_rates details to worklogs .
            $this->send_wlog('individual_rates', $indv_id, $msg, $this->add, $this->add, $wc_id);
         }
      }

      //redirecting
      if ($indv_id)
      {
         $this->session->set_flashdata('message', "Rate of item <span class='wlg_name'>$itm_name</span> added successfully");
         $this->session->set_flashdata('message_level', 1); // Success
      }
      else
      {
         $this->session->set_flashdata('message', "Rate of item <span class='wlg_name'>$itm_name</span> couldn't added because the destination is not registered under any selected workcentres.");
         $this->session->set_flashdata('message_level', 2); // Failure
      }

      redirect($this->cls, 'refresh');
   }

   function is_rate_exist($input)
   {
      $unique['indv_fk_workcentres'] = $input['indv_fk_workcentres'];
      $unique['indv_fk_party_destinations'] = $input['indv_fk_party_destinations'];
      $unique['indv_fk_items'] = $input['indv_fk_items'];
      $unique['indv_fk_units'] = $input['indv_fk_units'];
      $indv_id = $this->individual_rates->is_exists_2($unique);
      return $indv_id;
   }

   function replaceRate($indv_id, $input, $mode, $post_msg = '.')
   {
      // If $mode == 'add', data will be inserted only if it specified.
      if ($mode == 'add')
      {
         if ($input['indv_p_rate'])
            $data['indv_p_rate'] = $input['indv_p_rate'];
         if ($input['indv_s_rate'])
            $data['indv_s_rate'] = $input['indv_s_rate'];
      }

      // If $mode == 'edit', data will be inserted even if it is not specified.
      else if ($mode == 'edit')
      {
         $data['indv_p_rate'] = $input['indv_p_rate'];
         $data['indv_s_rate'] = $input['indv_s_rate'];
      }

      if (isset($data))
      {
         // Taking previous data before editing.
         $prev = $this->individual_rates->getById($indv_id);

         // Editing
         $this->individual_rates->save($data, $indv_id);

         // Details after edit
         $cur = $this->individual_rates->getById($indv_id);

         // Checking is anything edited.
         $edited = $this->isEdited($prev, $cur);

         // If edited, creating worklog.
         if ($edited)
         {
            $wcntr_id = $prev['indv_fk_workcentres'];
            $pdst_id = $prev['indv_fk_party_destinations'];
            $pdst_name = $this->party_destinations->getNameById($pdst_id);
            $pty_id = $this->party_destinations->getFieldById($pdst_id, 'pdst_fk_parties');
            $pty_name = $this->parties->getNameById($pty_id);
            $itm_id = $prev['indv_fk_items'];
            $itm_name = $this->items->getNameById($itm_id);

            // Setting message for worklogs.
            $msg = "Rate of item <span class='wlg_name'>$itm_name</span>";
            $msg .= " for party <span class='wlg_name'>$pty_name: $pdst_name</span> has been edited$post_msg";

            // Adding Tbl:individual_rates details to worklogs .
            $this->send_wlog($this->table, $indv_id, $msg, $this->edit, $this->edit, $wcntr_id, $prev, '', WARNING);
         }
      }
   }

   function createHTML($data)
   {
      $html = '<table class="unt_tbl">';
      $html .= '<tbody>';
      $html .= '<tr>';
      $html .= '<th>Unit</th>';
      $html .= '<th>Purchase Rate</th>';
      $html .= '<th>Selling Rate</th>';
      $html .= '</tr>';
      foreach ($data as $row)
      {
         $html .= '<tr>';
         $html .= '<td>' . $row['unit'] . '<input type="hidden" class="indv_id" value="' . $row['id'] . '"></td>';
         $html .= '<td> <input type="text" class="indv_p_rate numberOnly" value="' . $row['p_rate'] . '"></td>';
         $html .= '<td> <input type="text" class="indv_s_rate numberOnly" value="' . $row['s_rate'] . '"></td>';
         $html .= '</tr>';
      }

      $html .= '</tbody>';
      $html .= '</table>';

      return $html;
   }

   function beforeEdit()
   {
      // Checking is the current task is enabled for the user
      $this->isAllowedTask("individual_rates/edit");

      $indv_ids = $_POST['indv_ids'];
      $rows = $this->individual_rates->getById($indv_ids);
      $details = array();
      if ($rows)
      {
         foreach ($rows as $key => $row)
         {
            $details[$key]['id'] = $row['indv_id'];
            $details[$key]['unit'] = $this->units->getNameById($row['indv_fk_units']);
            $details[$key]['p_rate'] = $row['indv_p_rate'];
            $details[$key]['s_rate'] = $row['indv_s_rate'];
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
      foreach ($input['ids'] as $key => $indv_id)
      {
         if (!$this->isNumeric($input['p_rate'][$key]) || !$this->isNumeric($input['s_rate'][$key]))
         {
            echo $this->errorTitle . '<div class="pop_failure">Rate must contain only numeric value.</div>';
            return;
         }
      }

      // After successfull validation.
      foreach ($input['ids'] as $key => $indv_id)
      {
         $tbl_data = array();
         $tbl_data['indv_p_rate'] = $input['p_rate'][$key];
         $tbl_data['indv_s_rate'] = $input['s_rate'][$key];
         $this->replaceRate($indv_id, $tbl_data, 'edit');
      }

      echo 1; // Successfully edited.
   }

   function delete()
   {
      $indv_ids = $this->input->post('indv_ids');
      foreach ($indv_ids as $indv_id)
      {
         // Taking backup before delete.
         $prev = $this->individual_rates->getById($indv_id);

         // Deleting
         $this->individual_rates->remove($indv_id);

         // If Deleted
         if (!$this->individual_rates->getById($indv_id))
         {
            $wcntr_id = $prev['indv_fk_workcentres'];
            $pdst_id = $prev['indv_fk_party_destinations'];
            $pdst_name = $this->party_destinations->getNameById($pdst_id);
            $pty_id = $this->party_destinations->getFieldById($pdst_id, 'pdst_fk_parties');
            $pty_name = $this->parties->getNameById($pty_id);
            $itm_id = $prev['indv_fk_items'];
            $itm_name = $this->items->getNameById($itm_id);

            // Setting message for worklogs.
            $msg = "Rate of item <span class='wlg_name'>$itm_name</span>";
            $msg .= " for party <span class='wlg_name'>$pty_name: $pdst_name</span> has been deleted";

            // Adding Tbl:individual_rates details to worklogs .
            $this->send_wlog($this->table, $indv_id, $msg, $this->delete, $this->delete, $wcntr_id, $prev, '', WARNING);
         }
      }

      echo "Rates deleted successfully.";
   }

   function index()
   {
      // Checking is the current task is enabled for the user
      $this->isAllowedTask($this->cls);

      // Receiving input.
      $input = $this->get_pagination_inputs($this->individual_rates);

      //Set the flash data message if there is one has set before redirected to this page.
      $data['message'] = $this->session->flashdata('message');
      $data['message_level'] = $this->session->flashdata('message_level');

      $data['offset'] = $input['offset'];
      $this->per_page = $_POST ? $input['PER_PAGE'] : $this->per_page;

      $data['title'] = "Party Rates";
      $data['heading'] = "Search Rates For Parties";
      $data['itmcats'] = $this->item_category->get_active_option();
      $itmcat_id = isset($input['itmcat_id']) ? $input['itmcat_id'] : '';
      $data['itm_heads'] = $this->item_heads->get_active_option(array('itmhd_fk_item_category' => $itmcat_id));
      $data['workcentres'] = $this->workcentres->get_workcentres_options($this->user_id, $this->firm_id, 1);

      // Getting all active parties under users workcentres.
      $wcntr_ids = $this->workcentres->getIdsFromOption($data['workcentres']);
      $parties = $this->parties->getPartiesUnderWorkcentre($wcntr_ids);
      $data['parties'] = $this->parties->make_options($parties, 'pty_id', 'pty_name');

      $data['destinations'] = array();
      if ($this->input->post('pty_id'))
      {
         $destinations = $this->party_destinations->getDestinationsUnderWorkcentre($wcntr_ids, $this->input->post('pty_id'));
         $data['destinations'] = $this->party_destinations->make_options($destinations, 'pdst_id', 'pdst_name');
      }

      // Setting default search options.
      if (!$_POST)
      {
         $input['itm_status'] = 1; //Default status is Active 
      }


      //If reffered from Worklogs;
      $data['table'] = $this->individual_rates->index($input, $wcntr_ids);
      $data['num_rows'] = $this->individual_rates->index($input, $wcntr_ids, true);
      $data['units'] = array();
      $data['indvs'] = array();

      if ($data['table'])
      {
         $wcntrs = ifSetInput($input, 'wcntr_id')? : $wcntr_ids;
         $pty_id = isset($input['pty_id']) ? $input['pty_id'] : '';
         $pdst_id = isset($input['pdst_id']) ? $input['pdst_id'] : '';
         foreach ($data['table'] as $row)
         {
            $itm_id = $row['itm_id'];
            $itm_fk_units = $this->items->getFieldById($itm_id, 'itm_fk_units');
            $unt_batch = $this->units->getFieldById($itm_fk_units, 'unt_batch');

            // Getting units.
            $data['units'][$itm_id] = $this->units->getUnitsOfBatch($row['unt_batch']);
            $data['unit_details'][$itm_id] = $this->units->groupQueryResultById($data['units'][$itm_id]);

            // Getting Item rates for parties in each workcentres
            foreach ($wcntrs as $wc_id)
            {
               # ------------        IMPORTANT      --------------------------------------------------------------------------#
               # When a user edited the unit of an item, Actually it is not the editing of the existing units.                #
               # But it is realy the creation of a new batch of units.                                                        #
               # So if the units of an item edited, The unit rates of the item set in Tbl:individual_rates become invalid.    #
               # so it must be avoid when listing.                                                                            #
               # -------------------------------------------------------------------------------------------------------------#

               $data['dests'][$itm_id][$wc_id] = $this->individual_rates->getDestinationsInWorkcentreRates($itm_id, $wc_id, $pty_id, $pdst_id, $unt_batch);

               if ($data['dests'][$itm_id][$wc_id])
                  foreach ($data['dests'][$itm_id][$wc_id] as $pdst_rows)
                     $data['indvs'][$itm_id][$wc_id][$pdst_rows['DEST_ID']] = $this->individual_rates->getWorkcentreRates($itm_id, $wc_id, '', $pdst_rows['DEST_ID'], $unt_batch);
               
            }
         }
      }

      // Initializing pagination
      $data = array_merge($data, $this->initPagination($data['table'], $data['num_rows'], $input['offset']));

      $this->_render_page($this->clsfunc, $data);
   }

   function rateCheck($rate)
   {
      if (!$this->input->post('indv_p_rate') && !$this->input->post('indv_s_rate'))
      {
         $this->form_validation->set_message('rateCheck', 'Either Purchase or Selling rate is required.');
         return FALSE;
      }

      return TRUE;
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