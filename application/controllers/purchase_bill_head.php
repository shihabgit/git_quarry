<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require 'my_controller.php';

class Purchase_bill_head extends My_controller
{

   function __construct()
   {
      parent::__construct();
      $this->load->model('purchase_bill_head_model', 'purchase_bill_head');
      $this->load->model('employee_work_centre_model', 'employee_work_centre');
      $this->load->model('vehicles_model', 'vehicles');
//      $this->load->model('vehicle_workcentres_model', 'vehicle_workcentres');
//      $this->load->model('vehicles_employees_model', 'vehicles_employees');
//      $this->load->model('freight_charges_model', 'freight_charges');
//      $this->load->model('inter_freight_charges_model', 'inter_freight_charges');
//      $this->load->library('troubleshoot');

      // Determining is the logged in user allowed to go forward with the current action.
      $this->isAllowed();
      $this->per_page = 10;
      $this->table = 'purchase_bill_head';
      $this->p_key = '';
   }
 
/*   
Tbl: purchase_billnumber_notax
------------------------------   
pbntx_id  ,  pbntx_fk_workcentres  ,  pbntx_no  ,  pbntx_fyear
   
Tbl: purchase_billnumber_tax
-----------------------------   
pbtx_id  ,  pbtx_fk_workcentre_registration_details  ,  pbtx _no  ,  pbtx_fyear
   
   
   
Tbl:purchase_bill_head
 --------------------
pbh_id  ,  pbh_datetime  ,  pbh_fk_workcentres  ,  pbh_fk_party_destinations  ,  pbh_temp_party  ,  pbh_fk_purchase_billnumber_tax  ,  pbh_fk_purchase_billnumber_notax  ,  pbh_ref_no  ,  pbh_fk_ party_vehicles  ,  pbh_pty_veh_rent  ,  pbh_pty_veh_rent_declared  ,  pbh_pty_add_rent  ,  pbh_pty_add_rent_declared  ,  pbh_fk_vehicles  ,  pbh_temp_vehicle  ,  pbh_rent  ,  pbh_rent_declared  ,  pbh_fk_driver  ,  pbh_fk_driver_declared  ,  pbh_bata  ,  pbh_bata_declared  ,  pbh_loading  ,  pbh_loading _declared  ,  pbh_loading_mode  ,  pbh_loading_mode_declared  ,  pbh_round_off  ,  pbh_paid  ,  pbh_remarks  ,  pbh_status  ,  
      
Tbl: purchase_bill_body
--------------------------
pbb_id  ,  pbb_fk_purchase_bill_head  ,  pbb_fk_items  ,  pbb_qty  ,  pbb_fk_units  ,  pbb_rate  ,  pbb_rate_declared  ,  pbb_tax  ,  pbb_cess

Tbl: purchase_bill_loaders
--------------------------   
pbl_id  ,  pbl_fk_purchase_bill_head  ,  pbl_loader  ,  pbl_loading_charge
    
Tbl: purchase_bill_ additives
------------------------------
pba_id  ,  pba_fk_purchase_bill_head  ,  pba_name  ,  pba_amount

        
Tbl: purchase_bill_deductives
--------------------------------   
pbd_id  ,  pbd_fk_purchase_bill_head  ,  pbd_name  ,  pbd_amount

        */
        
        
        
        
   function add()
   {
      // Checking is the current task is enabled for the user
      $this->isAllowedTask($this->clsfunc);

      // Validating vehicles.
      $v_config = validationConfigs($this->table, '', $this->table);
      $this->form_validation->set_rules($v_config);
      
      // All active workcentres in the current firm where the user is registered.
      $data['workcentres'] = $this->workcentres->get_workcentres_options($this->user_id, $this->firm_id, 1);
 
      $data['date'] = date('d/m/Y');
      
      $data['time'] = date('h:i A'); //, time()
      
      

      if (!$this->form_validation->run())
      {
         $data['title'] = 'Add Bill';
         if ($_POST)
         {
            $data['message'] = 'Some Errors Occured !';
            $data['message_level'] = 2;
//            if (!isset($vhclwc['vwc_fk_workcentres']))
//               $data['availability_errors'] = '<div class="dialog-box-border">A vehicle must be register under any of the workcentres listed above.</div>';
         }
//            echo validation_errors();
         $this->_render_page($this->clsfunc, $data);
         return;
      }

      // Recieving input 
      $input = $this->input->post();
   }
   
   // Called by ajax
   function get_bill_date()
   {
      
   }
   
   // Called by ajax
   function get_bill_time()
   {
      
   }
   
   function index()
   {
      // Checking is the current task is enabled for the user
      $this->isAllowedTask($this->cls);
      
      
      
      // Receiving input
      $input = $this->get_pagination_inputs($this->vehicles);

      //Set the flash data message if there is one has set before redirected to this page.
      $data['message'] = $this->session->flashdata('message');
      $data['message_level'] = $this->session->flashdata('message_level');
      $data['offset'] = $input['offset'];
      $data['title'] = "Vehicles";
      $data['heading'] = "Search Vehicles";
      $data['status'] = $this->vehicles->get_vehicle_status();
      $data['ownership'] = $this->vehicles->get_ownership_values();
      $data['workcentres'] = $this->workcentres->get_workcentres_options($this->user_id, $this->firm_id, 1);

      $data['drivers'] = $this->employee_work_centre->getUsersEmployees($this->user_id, 4, 'options', '', $this->firm_id);
      $data['loaders'] = $this->employee_work_centre->getUsersEmployees($this->user_id, 5, 'options', '', $this->firm_id);
      

      $this->per_page = $_POST ? $input['PER_PAGE'] : $this->per_page;


      // Setting default search options.
      if (!$_POST)
      {
//         $input['vhcl_status'] = 1; //Default status is Active  
//         $input['vwc_status'] = 1; //Default status is Active  
         $input['vhcl_ownership'] = 0; //Default ownership is 'ours'.  
      }

      // If reffered from Worklogs;
      $wlog_ref_id = ($this->uri->segment(3) == 'wlogs') ? $this->uri->segment(4) : '';

      $data['table'] = array();//$this->vehicles->index($input, $data['workcentres'], $wlog_ref_id);
      $data['num_rows'] = '';//$this->vehicles->index($input, $data['workcentres'], $wlog_ref_id, true);

      $user_wcntrs = $this->workcentres->get_workcentres_options($this->user_id, '', 1);
      $user_wcntrs = $this->workcentres->getIdsFromOption($user_wcntrs);
   
      // Adding other details
      if ($data['table'])
      {
         // Getting user, who last changed the worklog.
         $data['wlog'] = $this->getWlogUser($data['table']);

         foreach ($data['table'] as $row)
         {
            $vhcl_id = $row[$this->p_key];

            // Getting vehicle availability
            $data['availability'][$vhcl_id] = $this->vehicle_workcentres->index($vhcl_id,$input['vwc_status'],$user_wcntrs);

            // Labours in the vehicle
            $data['labours'][$vhcl_id] = $this->vehicles_employees->index($vhcl_id);

            // Freight charges of the vehicle from workcentre to vehicle destinations.
            $data['freight'][$vhcl_id] = $this->freight_charges->index($vhcl_id);

            // Freight charges of the vehicle between workcentres.
            $data['inter_freight'][$vhcl_id] = $this->inter_freight_charges->index($vhcl_id);
         }
      }

      // Initializing pagination
      $data = array_merge($data, $this->initPagination($data['table'], $data['num_rows'], $input['offset']));

      // After validations
      $data = array_merge($data, $this->validateIndex());

      $this->_render_page($this->clsfunc, $data);
   }
   
   

   function validateIndex()
   {
      $config[] = array('f_pbh_datetime', 'From Date', 'callback_compare_dates[' . $this->input->post('t_pbh_datetime') . ']');
      $data = $this->checkValidations($config);
      return $data;
   }
}
?>