<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require 'my_controller.php';

class Workcentres extends My_controller
{

   function __construct()
   {
      parent::__construct();
      // Workcentres_model has been loaded in "My_controller".

      $this->load->model('owners_model', 'owners');
      $this->load->model('rental_details_model', 'rental_details');
      $this->load->model('employee_work_centre_model', 'employee_work_centre');
      $this->load->model('workcentre_registration_details_model', 'workcentre_registration_details');

      // Determining is the logged in user allowed to go forward with the current action.
      $this->isAllowed();

      $this->table = 'workcentres';
      $this->p_key = 'wcntr_id';
   }

   function load_users_workcentres()
   {
      $tax_type = $this->input->post('tax_type'); // 1=> Taxable, 2=> Compounted (Non-taxable)
      
      // Collecting all active workcentres in the current firm where the user is registered.
      // If Taxable, getting the workcentres only those having a legal registration.
      if ($tax_type == 1)
         $data = $this->workcentre_registration_details->get_registered_workcentres($this->user_id, $this->firm_id);
      else
         $data = $this->workcentres->get_workcentres_options($this->user_id, $this->firm_id, 1);

      if ($data)
      {
         echo get_options2($data, '', TRUE, 'Select Workcentre');
      }
      else
      {
         echo '<option value="">No Workcentres</option>';
      }
   }

   function get_wlog()//Called by ajax
   {
      $model = $this->workcentres;
      $wlogs = $this->init_wlog($this->p_key, $model);

      if ($wlogs[0])
      {
         $latest_class = 'wlog_latest'; // Latest details about the worklog. Ie: the data from ref_tables, not from Tbl:backups.
         $wlog_fields = getWlogFields($this->table, 'keys');
         $ownership = $model->get_ownership_values();
         $status = $model->get_workcentre_status();

         // Getting all (active/inactive) registration names under the firm.
         $reg_names = $this->workcentre_registration_details->get_option(array('wrd_fk_firms' => $this->firm_id));

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

               if ($fld == 'wcntr_ownership')
                  $val = $ownership[$row[$fld]];
               else if ($fld == 'wcntr_status')
                  $val = $status[$row[$fld]];
               else if ($fld == 'wcntr_fk_workcentre_registration_details')
                  $val = isset($reg_names[$row[$fld]]) ? $reg_names[$row[$fld]] : '';

               // If no values, leaving dashes.
               $val = $val ? : '---------';

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

   function add()
   {

      // Checking is the current task is enabled for the user
      $this->isAllowedTask($this->clsfunc);

      // Redirecting to login page (after logout) if the new workcentre creation is not allowed.
      if (!$this->allow_multiple())
         $this->my_logout("Multiple workcentres are not allowed!", 2); // value 2 indicates "Failure";


         
// Validating workcentres.
      $val_wc = validationConfigs($this->table, 'add', $this->table);

      // Validating rental_details.
      $ownership = ifSetArray('workcentres', 'wcntr_ownership');
      $val_rd = ($ownership == 2) ? validationConfigs('rental_details', 'add', 'rental_details') : array();

      // adding validation configurations to form_validation.
      $v_config = array_merge($val_wc, $val_rd);
      $this->form_validation->set_rules($v_config);

      if (!$this->form_validation->run())
      {
         $data['title'] = 'Add Work Centre';
         $data['heading'] = 'ADD NEW WORK CENTRE';
         $data['ownership'] = $this->workcentres->get_ownership_values();
         $data['owners'] = $this->owners->get_active_option();

         // Getting active registrations under the firm.
         $data['registrations'] = $this->workcentre_registration_details->get_active_option(array('wrd_fk_firms' => $this->firm_id));

         if ($_POST)
         {
            $data['message'] = 'Some Errors Occured !';
            $data['message_level'] = 2;
         }
         //echo validation_errors();
         $this->_render_page($this->clsfunc, $data);
         return;
      }

      // Recieving input 
      $input = $this->get_inputs();

      // Formating workcentre details
      $input['workcentres']['wcntr_fk_firms'] = $this->firm_id;
      $input['workcentres']['wcntr_date'] = getSqlDate();
      $input['workcentres']['wcntr_name'] = ucwords(strtolower($input['workcentres']['wcntr_name']));

      // Inserting workcentre.
      $wcntr_id = $this->workcentres->insert($input['workcentres']);

      // Adding workcentre reports to worklogs.
      if ($wcntr_id)
      {
         $wlog_wc = array();
         $wlog_wc[0]['msg'] = 'A new workcentre <span class="wlg_name">' . $input['workcentres']['wcntr_name'] . '</span> under firm: ' . $this->firm_name . ' has been added.';
         $wlog_wc[0]['action'] = $this->add;
         $this->add_logs($this->table, $wcntr_id, get_url($this->table), get_popup_id($this->table), $wlog_wc, $this->add);
      }

      // If the workcentre is for rental (ie:- Not owned)
      if ($wcntr_id && ($input['workcentres']['wcntr_ownership'] == 2))
      {
         // Formating Rental Details
         $input['rental_details']['rntdt_fk_workcentre'] = $wcntr_id;
         $input['rental_details']['rntdt_date'] = $input['workcentres']['wcntr_date'];
         $input['rental_details']['rntdt_start_from'] = getSqlDate($input['rental_details']['rntdt_start_from']);
         if (isset($input['rental_details']['rntdt_auto_add']))
            $input['rental_details']['rntdt_auto_add'] = 1;
         else
            $input['rental_details']['rntdt_auto_add'] = 2;

         // Inserting rental details
         $rntdt_id = $this->rental_details->insert($input['rental_details']);

         // Adding Rental details reports to worklogs.
         if ($rntdt_id)
         {
            $wlog_wc = array(); //The worklog is done under which workcentres. 
            $wlog_wc[$wcntr_id]['msg'] = 'Rental details added for the workcentre <span class="wlg_name">' . $input['workcentres']['wcntr_name'] . '</span> ';
            $wlog_wc[$wcntr_id]['action'] = $this->add;
            $this->add_logs('rental_details', $rntdt_id, get_url('rental_details'), get_popup_id('rental_details'), $wlog_wc, $this->add);
         }
      }

      // Putting all admins to the workcentre.
      if ($wcntr_id)
      {
         // Getting all Admins (active && inactive).
         $admins = $this->employees->getAllEmployees(TRUE, array('emp_category' => 1));
         $emp_wc['ewp_fk_workcentres'] = $wcntr_id;
         $emp_wc['ewp_date'] = $input['workcentres']['wcntr_date'];
         $emp_wc['ewp_ob'] = '0.00';
         $emp_wc['ewp_day_wage'] = '0.00';
         $emp_wc['ewp_day_hourly_wage'] = '0.00';
         $emp_wc['ewp_day_ot_wage'] = '0.00';
         $emp_wc['ewp_night_wage'] = '0.00';
         $emp_wc['ewp_night_hourly_wage'] = '0.00';
         $emp_wc['ewp_night_ot_wage'] = '0.00';
         $emp_wc['ewp_salary_wage'] = '0.00';
         $emp_wc['ewp_status'] = 1;    // Active.
         // Adding each admin to
         foreach ($admins as $id => $name)
         {
            $emp_wc['ewp_fk_auth_users'] = $id;
            $ewp_id = $this->employee_work_centre->insert($emp_wc);

            // Adding 'employees in workcentres' reports to worklogs.
            $wlog_wc = array();
            $wlog_wc[$wcntr_id]['msg'] = 'An existing Admin <span class="wlg_name">' . $name . '</span>';
            $wlog_wc[$wcntr_id]['msg'] .= ' has added to the workcentre on its creation.';
            $wlog_wc[$wcntr_id]['action'] = $this->add;
            //$this->add_logs('employee_work_centre', $ewp_id, get_url('employee_work_centre'), get_popup_id('employee_work_centre'), $wlog_wc, $this->add);
         }
      }

      // Assuming the insertion is failed by default.
      $success = FALSE;

      //If workcentre inserted successfully
      if ($wcntr_id)
      {
         // Insertion of workcentre is successfull.
         $success = TRUE;

         // If the workcentre is for rental (ie:- Not owned)
         if ($input['workcentres']['wcntr_ownership'] == 2)
         {
            //If failed Retnt details insertion.
            if (!$rntdt_id)
            // Insertion of Retnt details is failure.
               $success = FALSE;
         }
      }

      // Redirecting if insertion was successfull.
      if ($success)
      {
         $this->session->set_flashdata('message', "Workcentre added successfully!");
         $this->session->set_flashdata('message_level', 1); // Success
         redirect($this->cls, 'refresh');
      }
      else
      {
         $this->session->set_flashdata('message', "Workcentre couldn't be added!");
         $this->session->set_flashdata('message_level', 2); // Failure
         redirect($this->clsfunc, 'refresh');
      }
   }

   function before_edit()
   {

      // Checking is the current task is enabled for the user
      $this->isAllowedTask($this->cls . "/edit");
      $workcentre_id = $this->uri->segment(3);

      // Preventing url attack by sending workcentre id manually.
      if (!$this->employee_work_centre->is_user_registered_in_workcentre($this->user_id, $workcentre_id, $this->firm_id, '', 1))
      {
         $msg = "Permission Error :- You are not a Member";
         $level = 2; // Having errors.
         $this->my_logout($msg, $level);
         return false;
      }

      $data['title'] = 'Edit Work Centre';
      $data['heading'] = 'EDIT WORK CENTRE';
      //$data['ownership'] = $this->workcentres->get_ownership_values();
      $data['owners'] = $this->owners->get_active_option();


      // By default, setting all field values empty/NULL.
      $data['workcentres'] = getFieldsWithEmptyValues($this->table);
      $data['rental_details'] = getFieldsWithEmptyValues('rental_details');

      // Getting current details of workcetnre.
      $data['workcentres'] = $this->workcentres->getById($workcentre_id);

      // Getting current reg.name (If active or inactive) and all active registrations under the firm.
      $where = array('wrd_fk_firms' => $this->firm_id);
      $or_where = '';
      if (isset($data['workcentres']['wcntr_fk_workcentre_registration_details']))
         $or_where = array('wrd_id' => $data['workcentres']['wcntr_fk_workcentre_registration_details']);

      $data['registrations'] = $this->workcentre_registration_details->get_active_option($where, $or_where);

      // If workcentre is for rental, collecting its rental details.
      if ($data['workcentres']['wcntr_ownership'] == 2)
      {
         $data['rental_details'] = $this->rental_details->getById($workcentre_id, 'rntdt_fk_workcentre');
         $data['rental_details']['rntdt_start_from'] = formatDate($data['rental_details']['rntdt_start_from'], false, 1);
      }

      $this->_render_page($this->cls . "/edit", $data);
      return;
   }

   function edit()
   {

      // Checking is the current task is enabled for the user
      $this->isAllowedTask($this->clsfunc);

      // Recieving input 
      $input = $this->get_inputs('nofilter');
      $wcntr_id = $input['workcentres']['wcntr_id'];

      // Validating workcentres.
      $val_wc = validationConfigs($this->table, 'edit', $this->table);

      // Validating rental_details, if the workcentre is for rental.
      $ownership = ifSetArray($this->table, 'wcntr_ownership');
      $val_rd = ($ownership == 2) ? validationConfigs('rental_details', 'add', 'rental_details') : array();

      // adding validation configurations to form_validation.
      $v_config = array_merge($val_wc, $val_rd);
      $this->form_validation->set_rules($v_config);

      if (!$this->form_validation->run())
      {
         $data['title'] = 'Add Work Centre';
         $data['heading'] = 'ADD NEW WORK CENTRE';
         $data['owners'] = $this->owners->get_active_option();

         // Getting current reg.name (If active or inactive) and all active registrations under the firm.
         $where = array('wrd_fk_firms' => $this->firm_id);
         $workcentre_details = $this->workcentres->getById($wcntr_id);
         $or_where = '';
         if (isset($workcentre_details['wcntr_fk_workcentre_registration_details']))
            $or_where = array('wrd_id' => $workcentre_details['wcntr_fk_workcentre_registration_details']);

         $data['registrations'] = $this->workcentre_registration_details->get_active_option($where, $or_where);

         if ($_POST)
         {
            $data['message'] = 'Some Errors Occured !';
            $data['message_level'] = 2;
            $data = array_merge($data, $this->get_inputs('nofilter'));
         }

         //echo validation_errors();
         $this->_render_page($this->clsfunc, $data);
         return;
      }

      # Formating workcentre details
      //$input['workcentres']['wcntr_fk_firms'] = $this->firm_id; //Firm Id must not be edited. it will affect the whole account.

      $input['workcentres']['wcntr_name'] = ucwords(strtolower($input['workcentres']['wcntr_name']));

      //Before saving new data about the workcentre, getting previous data.
      $prev['workcentres'] = $this->workcentres->getById($wcntr_id);

      // checking has any details about the workcentre been edited.
      $wcntr_edited = $this->isEdited($prev['workcentres'], $input['workcentres']);

      if ($wcntr_edited)
      {
         // Saving workcentre.
         $this->workcentres->save($input['workcentres'], $wcntr_id);

         // Adding workcentre reports to worklogs.
         $wlog_wc = array();
         $wlog_wc[0]['msg'] = 'The workcentre <span class="wlg_name">' . $prev['workcentres']['wcntr_name'] . '</span> has been edited.';
         $wlog_wc[0]['action'] = $this->edit;

         // Providing a warning message for Admin, if the staff changed the 'wcntr_capital'.
         $wlog_warnings = (intval($prev['workcentres']['wcntr_capital']) != intval($input['workcentres']['wcntr_capital'])) ? WARNING : NORMAL;

         $wlog_id = $this->add_logs($this->table, $wcntr_id, get_url($this->table), get_popup_id($this->table), $wlog_wc, $this->edit, '', $wlog_warnings);

         // Backing up previous details for data recovery needs.
         if (to_be_backed_up($this->table)) //If need to be backed up.
            $this->backups->backUpData($wlog_id, $prev['workcentres'], $this->table, $wcntr_id);
      }

      // If previously, the workcentre was for rental, collecting its rental details.
      if ($prev['workcentres']['wcntr_ownership'] == 2)
      {
         $prev['rental_details'] = $this->rental_details->getById($wcntr_id, 'rntdt_fk_workcentre');
         //$prev['rental_details']['rntdt_start_from'] = formatDate($prev['rental_details']['rntdt_start_from'], false, 1);
      }

      // If previously the workcentre was for rental but not now. ie: now it is owned. So the previous rental details must be deleted.
      if (($prev['workcentres']['wcntr_ownership'] == 2) && ($input['workcentres']['wcntr_ownership'] == 1))
      {
         $this->rental_details->delete_where(array('rntdt_fk_workcentre' => $wcntr_id));
         $wlog_wc = array(); //The worklog is done under which workcentres. 
         $wlog_wc[$wcntr_id]['msg'] = 'Rental details cancelled for the workcentre <span class="wlg_name">' . $prev['workcentres']['wcntr_name'] . '</span> ';
         $wlog_wc[$wcntr_id]['action'] = $this->delete;
         $wlog_id = $this->add_logs('rental_details', $prev['rental_details']['rntdt_id'], get_url('rental_details'), get_popup_id('rental_details'), $wlog_wc, $this->delete);

         // Backing up previous details for data recovery needs.
         if (to_be_backed_up('rental_details')) //If need to be backed up.
            $this->backups->backUpData($wlog_id, $prev['rental_details'], 'rental_details', $prev['rental_details']['rntdt_id']);
      }

      // If now the workcentre is for rental (ie:- Not owned)
      else if ($input['workcentres']['wcntr_ownership'] == 2)
      {

         // Formating Rental Details
         $input['rental_details']['rntdt_fk_workcentre'] = $wcntr_id;
         $input['rental_details']['rntdt_start_from'] = getSqlDate($input['rental_details']['rntdt_start_from']);

         if (isset($input['rental_details']['rntdt_auto_add']))
            $input['rental_details']['rntdt_auto_add'] = 1;
         else
            $input['rental_details']['rntdt_auto_add'] = 2;

         // If previously the workcentre was for rental, checking is it edited now.
         $rent_edited = ($prev['workcentres']['wcntr_ownership'] == 2) ? $this->isEdited($prev['rental_details'], $input['rental_details']) : FALSE;

         // if previously the workcentre was owned, but now it is changed to rental. just add new rental details.
         if ($prev['workcentres']['wcntr_ownership'] == 1)
         {
            $input['rental_details']['rntdt_date'] = getSqlDate();

            // Inserting rental details
            $rntdt_id = $this->rental_details->insert($input['rental_details']);

            // Adding Rental details reports to worklogs.
            if ($rntdt_id)
            {
               $wlog_wc = array(); //The worklog is done under which workcentres. 
               $wlog_wc[$wcntr_id]['msg'] = 'Rental details added for the workcentre <span class="wlg_name">' . $input['workcentres']['wcntr_name'] . '</span> ';
               $wlog_wc[$wcntr_id]['action'] = $this->add;
               $this->add_logs('rental_details', $rntdt_id, get_url('rental_details'), get_popup_id('rental_details'), $wlog_wc, $this->add);
            }
         }

         // else if previously and also currently the workcentre is for rental, and edited the previous details
         else if (($prev['workcentres']['wcntr_ownership'] == 2) && $rent_edited)
         {
            // editing rental details
            $this->rental_details->update_where($input['rental_details'], array('rntdt_fk_workcentre' => $wcntr_id));
            $wlog_wc = array(); //The worklog is done under which workcentres. 
            $wlog_wc[$wcntr_id]['msg'] = 'Rental details edited for the workcentre <span class="wlg_name">' . $input['workcentres']['wcntr_name'] . '</span> ';
            $wlog_wc[$wcntr_id]['action'] = $this->edit;
            $wlog_id = $this->add_logs('rental_details', $prev['rental_details']['rntdt_id'], get_url('rental_details'), get_popup_id('rental_details'), $wlog_wc, $this->edit);

            // Backing up previous details for data recovery needs.
            if (to_be_backed_up($this->table)) //If need to be backed up.
               $this->backups->backUpData($wlog_id, $prev['rental_details'], 'rental_details', $prev['rental_details']['rntdt_id']);
         }
      }

      // Putting all admins to the workcentre if they are currently not in.
      if ($wcntr_id)
      {
         // Getting all Admins (active && inactive).
         $admins = $this->employees->getAllEmployees(TRUE, array('emp_category' => 1));
         $emp_wc['ewp_fk_workcentres'] = $wcntr_id;
         $emp_wc['ewp_date'] = $prev['workcentres']['wcntr_date'];
         $emp_wc['ewp_ob'] = '0.00';
         $emp_wc['ewp_day_wage'] = '0.00';
         $emp_wc['ewp_day_hourly_wage'] = '0.00';
         $emp_wc['ewp_day_ot_wage'] = '0.00';
         $emp_wc['ewp_night_wage'] = '0.00';
         $emp_wc['ewp_night_hourly_wage'] = '0.00';
         $emp_wc['ewp_night_ot_wage'] = '0.00';
         $emp_wc['ewp_salary_wage'] = '0.00';
         $emp_wc['ewp_status'] = 1;    // Active.
         // Adding each admin to
         foreach ($admins as $id => $name)
         {
            $emp_wc['ewp_fk_auth_users'] = $id;
            if (!$this->employee_work_centre->is_exists(array('ewp_fk_workcentres' => $wcntr_id, 'ewp_fk_auth_users' => $id)))
            {
               $ewp_id = $this->employee_work_centre->insert($emp_wc);

               // Adding 'employees in workcentres' reports to worklogs.
               $wlog_wc = array();
               $wlog_wc[$wcntr_id]['msg'] = 'An existing Admin <span class="wlg_name">' . $name . '</span>';
               $wlog_wc[$wcntr_id]['msg'] .= ' has added to the workcentre.';
               $wlog_wc[$wcntr_id]['action'] = $this->add;
            }
         }
      }

      // Redirecting if insertion was successfull.
      $this->session->set_flashdata('message', "Workcentre edited successfully!");
      $this->session->set_flashdata('message_level', 1); // Success
      redirect("workcentres/index/action", 'refresh');
   }

   function index()
   {
      // Checking is the current task is enabled for the user
      $this->isAllowedTask($this->cls);

      // Receiving input
      $input = $this->get_pagination_inputs($this->workcentres);

      //Set the flash data message if there is one has set before redirected to this page.
      $data['message'] = $this->session->flashdata('message');
      $data['message_level'] = $this->session->flashdata('message_level');
      $data['offset'] = $input['offset'];
      $data['title'] = "Workcentres";
      $data['heading'] = "Search Workcentres";
      //$data['firms'] = $this->firms->get_firms_options($this->user_id, 1, '');
      // Getting active registrations under the firm.
      $data['reg_names'] = $this->workcentre_registration_details->get_active_option(array('wrd_fk_firms' => $this->firm_id));

      $data['status'] = $this->workcentres->get_workcentre_status();
      $data['insallments'] = $this->rental_details->getInstallmentPeriods();

      $this->per_page = $_POST ? $input['PER_PAGE'] : $this->per_page;

      // Setting default search options.
      if (!$_POST)
      {
         $input['wcntr_status'] = 1; //Default status is Active.     
      }

      //If reffered from Worklogs;
      $wlog_ref_id = ($this->uri->segment(3) == 'wlogs') ? $this->uri->segment(4) : '';

      // A user can be accessible only the workcentres in which he registered.
      $input['wcntr_fk_firms'] = $this->firm_id;
      $data['table'] = $this->workcentres->index($input, $this->user_id, $wlog_ref_id);
      $data['num_rows'] = $this->workcentres->index($input, $this->user_id, $wlog_ref_id, true);

      // Adding worklog details
      if ($data['table'])
      {
         foreach ($data['table'] as $row)
         {
            $ref['wlog_ref_table'] = $this->table;
            $ref['wlog_ref_id'] = $row[$this->p_key];
            $data['wlog'][$row[$this->p_key]] = $this->worklogs->getUser($this->user_id, $ref);
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
      $config[] = array('wcntr_date_f', 'From Date', 'callback_compare_dates[' . $this->input->post('wcntr_date_t') . ']');
      $data = $this->checkValidations($config);
      return $data;
   }

   function toggleStatus()
   {
      // Checking is the current task is enabled for the user
      $this->isAllowedTask($this->cls . '/edit');
      $wcntr_id = $this->uri->segment(3);
      $details = $this->workcentres->getById($wcntr_id);
      $this->workcentres->toggleTableStatus($wcntr_id);
      $current_status = $this->workcentres->getTableStatus($wcntr_id);

      //Adding to worklogs
      $msg = ($current_status == 1) ? "Activated" : "Deactivated";
      $wlog_workcentre = 0; // The worklog is not under any workcentre, It is a General worklog.

      $wlog_wc[$wlog_workcentre]['msg'] = 'The workcentre <span class="wlg_name">' . $details['wcntr_name'] . '</span> has been ' . $msg . '.';
      $wlog_wc[$wlog_workcentre]['action'] = $this->edit;
      $this->add_logs($this->table, $wcntr_id, get_url($this->table), get_popup_id($this->table), $wlog_wc, $this->edit);


      //If a workcentre is active, its firm must be active.
      if ($current_status == 1)
      {
         $this->firms->update_where(array('firm_status' => 1), "firm_id = $details[wcntr_fk_firms]");

         # When activating a worcentres, all Admin must be its members.
         // Getting all Admins (active && inactive).
         $admins = $this->employees->getAllEmployees(TRUE, array('emp_category' => 1));
         $emp_wc['ewp_fk_workcentres'] = $wcntr_id;
         $emp_wc['ewp_date'] = getSqlDate();
         $emp_wc['ewp_ob'] = '0.00';
         $emp_wc['ewp_day_wage'] = '0.00';
         $emp_wc['ewp_day_hourly_wage'] = '0.00';
         $emp_wc['ewp_day_ot_wage'] = '0.00';
         $emp_wc['ewp_night_wage'] = '0.00';
         $emp_wc['ewp_night_hourly_wage'] = '0.00';
         $emp_wc['ewp_night_ot_wage'] = '0.00';
         $emp_wc['ewp_salary_wage'] = '0.00';
         $emp_wc['ewp_status'] = 1;    // Active.
         // Adding each admin to
         foreach ($admins as $id => $name)
         {
            $emp_wc['ewp_fk_auth_users'] = $id;
            if (!$this->employee_work_centre->is_exists(array('ewp_fk_workcentres' => $wcntr_id, 'ewp_fk_auth_users' => $id)))
            {
               $ewp_id = $this->employee_work_centre->insert($emp_wc);

               // Adding 'employees in workcentres' reports to worklogs.
               $wlog_wc = array();
               $wlog_wc[$wcntr_id]['msg'] = 'An existing Admin <span class="wlg_name">' . $name . '</span>';
               $wlog_wc[$wcntr_id]['msg'] .= ' has added to the workcentre on its activation.';
               $wlog_wc[$wcntr_id]['action'] = $this->add;
               $this->add_logs('employee_work_centre', $ewp_id, get_url('employee_work_centre'), get_popup_id('employee_work_centre'), $wlog_wc, $this->add);
            }
         }
      }
      else
      {
         //Looking is there at least one active workcentre in the firm.
         $activeWorkcentres = $this->workcentres->get_workcentres('', $details['wcntr_fk_firms'], 1);

         // If there are no active workcentres, the parent firm must be deactivated. Because an active firm must have at least one active workcntre.
         if (!$activeWorkcentres)
         {
            $this->firms->update_where(array('firm_status' => 2), "firm_id = $details[wcntr_fk_firms]");

            // Adding firm reports to worklogs.
            $firm_details = $this->firms->getById($details['wcntr_fk_firms']);
            $wlog_workcentre = 0; // The worklog is not under any workcentre, It is a General worklog.
            $wlog_firms = implode(',', $this->firms->getIds(array('firm_status' => 1))); // Worklog should be displayed in all active firms.
            $wlog_wc[$wlog_workcentre]['msg'] = 'The firm <span class="wlg_name">' . $firm_details['firm_name'] . '</span> has been deactivated due to the lack of active workcentres.';
            $wlog_wc[$wlog_workcentre]['action'] = $this->edit;
            $this->add_logs($this->table, $wcntr_id, get_url($this->table), get_popup_id($this->table), $wlog_wc, $this->edit, $wlog_firms);
            //If the decativated firm is current firm, it must be logge out.
            if ($details['wcntr_fk_firms'] == $this->firm_id)
            {
               $msg = "Firm has been deactivated";
               $level = 2; // Having errors.
               $this->my_logout($msg, $level);
               return false;
            }
         }
      }

      // Redirecting after changing status.
      $this->session->set_flashdata('message', "Workcentre $msg successfully!");
      $this->session->set_flashdata('message_level', 1); // Success
      redirect("workcentres/index/paging", 'refresh');
   }

}

?>