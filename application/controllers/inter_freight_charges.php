<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require 'my_controller.php';

class Inter_freight_charges extends My_controller
{

   function __construct()
   {
      parent::__construct();

      $this->load->model('vehicles_model', 'vehicles');
      $this->load->model('vehicle_workcentres_model', 'vehicle_workcentres');
      $this->load->model('inter_freight_charges_model', 'inter_freight_charges');

      // Determining is the logged in user allowed to go forward with the current action.
      $this->isAllowed();

      $this->table = 'inter_freight_charges';
      $this->p_key = 'ifc_id';
   }

   function get_wlog()//Called by ajax
   {
      $model = $this->inter_freight_charges;
      $wlogs = $this->init_wlog($this->p_key, $model);

      if ($wlogs)   // Format: 1 for deleted worklogs.
      {
         $latest_class = 'wlog_latest'; // Latest details about the worklog. Ie: the data from ref_tables, not from Tbl:backups.
         $wlog_fields = getWlogFields($this->table, 'keys');
         $add_rent = array(1 => "Rent will be added to the bill amount", 2 => "Rent will not be added to the bill amount");
         $add_bata = array(1 => "Bata will be added to the bill amount", 2 => "Bata will not be added to the bill amount");
         $add_loading = array(1 => "Loading charge will be added to the bill amount", 2 => "Loading charge will not be added to the bill amount");

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
               echo '<tr class="' . $latest_class . '">';
               $latest_class = '';
               foreach ($wlog_fields as $fld)
               {
                  $val = $row[$fld];
                  $edited = false;
                  if (isset($wlogs[$key + 1]) && ($wlogs[$key + 1][$fld] !== $val))
                     $edited = true;

                  if (($fld == 'ifc_fk_workcentres_from') || ($fld == 'ifc_fk_workcentres_to'))
                     $val = $this->workcentres->getNameById($row[$fld]);
                  else if ($fld == 'ifc_fkey_vehicles')
                     $val = $this->vehicles->getNameById($row[$fld]);
                  else if ($fld == 'ifc_add_rent')
                     $val = $add_rent[$row[$fld]];
                  else if ($fld == 'ifc_add_bata')
                     $val = $add_bata[$row[$fld]];
                  else if ($fld == 'ifc_add_loading')
                     $val = $add_loading[$row[$fld]];

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
      $task = taskEnabled("vehicles/add");
      if ($task != 1)
      {
         echo $task;
         return;
      }


      //	Validating 
      $v_config = validationConfigs($this->table);
      $this->form_validation->set_rules($v_config);
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

      // Recieving input
      $input = $this->get_inputs();
      $vhcl_id = $input['ifc_fkey_vehicles'];
      $vhcl_no = $this->vehicles->getNameById($vhcl_id);
      $from = $this->workcentres->getNameById($input['ifc_fk_workcentres_from']);
      $to = $this->workcentres->getNameById($input['ifc_fk_workcentres_to']);


      // Inserting data to Tbl:vehicles_employees
      $ifc_id = $this->inter_freight_charges->insert($input);


      if ($ifc_id)
      {
         // Worklog should be displayed in all workcentres where the vehicle has been registered.
         $workcentres = $this->vehicle_workcentres->getVehicleWorkcentres($vhcl_id);

         // Message related to the worklog.
         $msg = 'The freight charge for the vehicle <span class="wlg_name">' . $vhcl_no . '</span>';
         $msg .= ' between <span class="wlg_name">Workcentre: ' . $from . '</span>';
         $msg .= ' and <span class="wlg_name">Workcentre: ' . $to . '</span> has been added.';


         // Inserting worklogs of Tbl: inter_freight_charges.
         $this->send_wlog($this->table, $ifc_id, $msg, $this->add, $this->add, $workcentres);

         echo 1;
      }
      else
         echo $this->formatePopupError('Data couldn\'t insert !');
   }

   function beforeEdit()
   {
      // Checking is the current task is enabled for the user
      $this->isAllowedTask("vehicles/edit");

      $id = $_GET['ifc_id'];
      $details = $this->inter_freight_charges->getById($id);
      echo json_encode($details);
      return;
   }

   function edit()
   {
      // Checking is the current task is enabled for the user
      $task = taskEnabled("vehicles/edit");
      if ($task != 1)
      {
         echo $task;
         return;
      }

      //	Validating 
      $v_config = validationConfigs($this->table, 'edit');
      $this->form_validation->set_rules($v_config);
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

      // Recieving input 
      $input = $this->get_inputs();
      $ifc_id = $input['ifc_id'];

      // Freight charge details before edit
      $prev_details = $this->inter_freight_charges->getById($ifc_id);


      $vhcl_id = $prev_details['ifc_fkey_vehicles'];
      $vhcl_no = $this->vehicles->getNameById($vhcl_id);
      $from = $this->workcentres->getNameById($prev_details['ifc_fk_workcentres_from']);
      $to = $this->workcentres->getNameById($prev_details['ifc_fk_workcentres_to']);


      // Saving data to Tbl:vehicles
      $this->inter_freight_charges->save($input, $ifc_id);

      // Freight charge details after edit
      $cur_details = $this->inter_freight_charges->getById($ifc_id);

      // Checking is anything edited.
      $edited = $this->isEdited($prev_details, $cur_details);

      // If edited, creating worklog.
      if ($edited)
      {
         // Worklog should be displayed in all workcentres where vehicle has been registered.
         $workcentres = $this->vehicle_workcentres->getVehicleWorkcentres($vhcl_id);

         // Checking is there anything to warn Admin.
         $warning_fields = array('ifc_rent', 'ifc_add_rent', 'ifc_bata', 'ifc_add_bata', 'ifc_loading', 'ifc_add_loading');
         $warning = $this->check_warnings($prev_details, $cur_details, $warning_fields);

         // Message related to the worklog.
         $msg = 'The freight charge for the vehicle <span class="wlg_name">' . $vhcl_no . '</span>';
         $msg .= ' between <span class="wlg_name">Workcentre: ' . $from . '</span>';
         $msg .= ' and <span class="wlg_name">Workcentre: ' . $to . '</span> has been edited.';

         // Inserting worklogs of Tbl: inter_freight_charges.
         $this->send_wlog($this->table, $ifc_id, $msg, $this->edit, $this->edit, $workcentres, $prev_details, '', $warning);
         echo 1;
      }
      else
         echo $this->formatePopupError('There is nothing changed!');
   }

   function delete()
   {
      $ifc_id = $this->input->post('ifc_id');
      $prev_details = $this->inter_freight_charges->getById($ifc_id);


      $vhcl_id = $prev_details['ifc_fkey_vehicles'];
      $vhcl_no = $this->vehicles->getNameById($vhcl_id);
      $from = $this->workcentres->getNameById($prev_details['ifc_fk_workcentres_from']);
      $to = $this->workcentres->getNameById($prev_details['ifc_fk_workcentres_to']);


      $this->inter_freight_charges->remove($ifc_id);

      // If deleted;
      if (!$this->inter_freight_charges->getById($ifc_id))
      {
         // Worklog should be displayed in all workcentres where vehicle has been registered.
         $workcentres = $this->vehicle_workcentres->getVehicleWorkcentres($vhcl_id);

         // Message related to the worklog.
         $msg = 'The freight charge for the vehicle <span class="wlg_name">' . $vhcl_no . '</span>';
         $msg .= ' between <span class="wlg_name">Workcentre: ' . $from . '</span>';
         $msg .= ' and <span class="wlg_name">Workcentre: ' . $to . '</span> has been deleted.';

         // Inserting worklogs of Tbl: inter_freight_charges.
         $this->send_wlog($this->table, $ifc_id, $msg, $this->delete, $this->delete, $workcentres, $prev_details, '', WARNING);
         echo "Freight charge deleted successfully.";
      }
   }

   /**
    * 
    * Free workcentres means that the workcentres in which the freight charge from vehicle's workcentres to it is not defined.
    * If we defined freight charge from workcentre W1 to workcentre W2, there is no need to define freight charge from W2 to W1.
    * Because both are same. So when taking free vehicles this should be in remind.
    */
   function getFreeWorkcentres()
   {
      $vhcl_id = $_GET['vhcl_id'];
      $wc_from = $_GET['ifc_fk_workcentres_from'];
      $free_workcenres = array();

      // Taking all workcentres where the freight charges for the vehicle has defined.
      $ifc_details = $this->inter_freight_charges->index($vhcl_id);

      // Taking all acitve workentres of the user.
      $workcentres_to = $this->workcentres->get_workcentres($this->user_id, '', 1);

      foreach ($workcentres_to as $wcntrs)
      {
         $found = false;
         $wc_to = $wcntrs['wcntr_id'];
         $wc_to_name = $wcntrs['wcntr_name'];

         // Workcenre_from should not be equel to Workcentre_to.
         if ($wc_from != $wc_to)
         {
            foreach ($ifc_details as $ifc)
            {
               $from_to = (($ifc['ifc_fk_workcentres_from'] == $wc_from) && ($ifc['ifc_fk_workcentres_to'] == $wc_to));
               $to_from = (($ifc['ifc_fk_workcentres_from'] == $wc_to) && ($ifc['ifc_fk_workcentres_to'] == $wc_from));
               if ($from_to || $to_from)
               {
                  $found = true;
                  break;
               }
            }

            // If freight charges for the vehicle between $wc_from and $wc_to is not defined yet,
            // $wc_to will be considered as a free_workcentre.
            if (!$found)
               $free_workcenres[$wc_to] = $wc_to_name;
         }
      }

      $this->json_options($free_workcenres, "-- No Workcentres --");
   }

   /**
    * Callback function for validations.
    * @param type $val
    * @return boolean
    * 
    */
   function is_freight_exist($from)
   {

      $to = $this->input->post('ifc_fk_workcentres_to');

      if ($from == $to)
      {
         $this->form_validation->set_message('is_freight_exist', 'Both workcentres are same.');
         return FALSE;
      }

      $where_1['ifc_fk_workcentres_from'] = $from;
      $where_1['ifc_fk_workcentres_to'] = $to;
      $where_2['ifc_fk_workcentres_from'] = $to;
      $where_2['ifc_fk_workcentres_to'] = $from;

      if ($this->inter_freight_charges->is_exists($where_1) || $this->inter_freight_charges->is_exists($where_2))
      {
         $W1 = $this->workcentres->getNameById($from);
         $W2 = $this->workcentres->getNameById($to);
         $vhcl_no = $this->vehicles->getNameById($this->input->post('ifc_fkey_vehicles'));
         $msg = "The freight charge between <b>$W1</b> and <b>$W2</b> has been already added for the vehicle $vhcl_no.";
         $this->form_validation->set_message('is_freight_exist', $msg);
         return FALSE;
      }

      return TRUE;
   }

}
