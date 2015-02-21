<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require 'my_controller.php';

class Employees extends My_controller
{

   function __construct()
   {
      parent::__construct();

      #  'employees_model' has been loaded at parent class 'my_controller'.
      $this->load->model('employee_work_centre_model', 'employee_work_centre');
      $this->load->model('vehicles_employees_model', 'vehicles_employees');
      $this->load->model('vehicle_workcentres_model', 'vehicle_workcentres');
      $this->load->library('troubleshoot');

      // Determining is the logged in user allowed to go forward with the current action.
      $this->isAllowed();

      $this->table = 'employees';
      $this->p_key = 'emp_id';
   }

   function get_wlog()//Called by ajax
   {
      //$ref_id = 9;
      $wcntr_id = $_POST['wcntr_id'];
      $ref_id = $_POST['ref_id'];
      $from = 'employees,auth_users';
      $select = getWlogFields($this->table, 'keys');
      $where = "emp_id = $ref_id AND id = emp_id";
      $wlogs[] = $this->employees->get_row2($from, $select, $where);

      //Staffs must not be accessible (Admin/partner)'s Details.
      //Staffs and Partner must not be accessible Admin's Details
      $wloger_cat = $wlogs[0]['emp_category'];
      if (($this->user_cat == 3) && (($wloger_cat == 1) || ($wloger_cat == 2)))
      {
         echo "Your not accessible this worklog!!!";
         return;
      }
      else if (($this->user_cat == 2) && ($wloger_cat == 1))
      {
         echo "Your not accessible this worklog!!!";
         return;
      }

      $backups = $this->backups->getUsersBackupsByRef($ref_id, $this->table, $this->user_id, $wcntr_id);

      //print_r($backups);

      if ($backups)
      {
         $wlogs = array_merge($wlogs, $backups);
      }

      if ($wlogs[0])
      {
         $latest_class = 'wlog_latest'; // Latest details about the worklog. Ie: the data from ref_tables, not from Tbl:backups.
         $wlog_fields = getWlogFields($this->table, 'keys');
         $emp_cats = $this->employees->get_employee_category();
         $emp_status = $this->employees->get_employee_status();
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

               if ($fld == 'password')
                  $val = '<b>.....</b>';
               else if ($fld == 'emp_category')
                  $val = $emp_cats[$row[$fld]];
               else if ($fld == 'emp_status')
                  $val = $emp_status[$row[$fld]];
               else if ($fld == 'emp_address')
                  $val = nl2br($row[$fld]);


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

   function getEmployees()
   {
      $input['emp_category'] = $_GET['parent_id'];

      if (!$input['emp_category'])
         return;

      //workcentres where employee is available.
      $input['workcentres'] = $this->employee_work_centre->getEmployeesWorkcentres($this->user_id, $this->firm_id);
      $data = $this->employee_work_centre->get_workcentres_employees_option($input);

      $json[] = array('value' => '', 'text' => 'Select');
      foreach ($data as $key => $val)
         $json[] = array('value' => $key, 'text' => $val);
      echo json_encode($json);
   }

   function getAvailabilityFields($flag = FALSE)
   {
      $fld[] = 'ewp_fk_workcentres';
      $fld[] = 'ewp_ob';
      $fld[] = 'ewp_ob_mode';
      $fld[] = 'ewp_day_wage';
      $fld[] = 'ewp_day_hourly_wage';
      $fld[] = 'ewp_day_ot_wage';
      $fld[] = 'ewp_night_wage';
      $fld[] = 'ewp_night_hourly_wage';
      $fld[] = 'ewp_night_ot_wage';
      $fld[] = 'ewp_salary_wage';
      if ($flag)
         $fld[] = 'ewp_status';
      return $fld;
   }

   function add()
   {
      // Checking is the current task is enabled for the user
      $this->isAllowedTask($this->clsfunc);

//	Validating 
      $v_config = validationConfigs('employees', '', 'employees');
      $this->form_validation->set_rules($v_config);

      // Adding ion-auth validations
      $tables = $this->config->item('tables', 'ion_auth');
      $this->form_validation->set_rules('auth[first_name]', "Name", 'required|xss_clean');
      $this->form_validation->set_rules('auth[email]', "Email", 'valid_email|is_unique[' . $tables['users'] . '.email]');
      $this->form_validation->set_rules('auth[phone]', 'Phone No', 'xss_clean');

      // Login details are usefull only for Admin, Partner or Staff
      $cat = ifSetArray('employees', 'emp_category');
      if ($cat == 1 || $cat == 2 || $cat == 3) // Admin/Parteners/Staffs
      {
         $this->form_validation->set_rules('auth[username]', "Username", 'required|max_length[25]|is_unique[' . $tables['users'] . '.username]');
         $this->form_validation->set_rules('auth[password]', "Password", 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
         $this->form_validation->set_rules('password_confirm', "Password confirmation", 'required');
      }

      //$data['curent_workcentres'] = $this->workcentres->get_workcentres_options($this->user_id, $this->firm_id, 1);
      $data['workcentres'] = $this->workcentres->get_workcentres($this->user_id, '', 1);
      $data['emp_cats'] = $this->employees->get_employee_category($this->user_cat);

      $availabilityFields = $this->getAvailabilityFields();
      $empwc = $this->input->post('empwc_flds');

      foreach ($availabilityFields as $fld_name)
      {
         foreach ($data['workcentres'] as $wc)
         {
            if (isset($empwc[$fld_name][$wc['wcntr_id']]))
            {
               $data['empwc_flds'][$fld_name][$wc['wcntr_id']] = $empwc[$fld_name][$wc['wcntr_id']];
            }
            else
            {
               $data['empwc_flds'][$fld_name][$wc['wcntr_id']] = '';
            }
         }
      }

      if ((!$this->form_validation->run()) || (!isset($empwc['ewp_fk_workcentres'])))
      {
         $data['title'] = 'Add Employee';
         $data['heading'] = 'ADD NEW EMPLOYEE';
         $data['firms'] = $this->firms->get_firms_options($this->user_id, 1);
         $data['availability_errors'] = '';
         if ($_POST)
         {
            $data['message'] = 'Some Errors Occured !';
            $data['message_level'] = 2;
            if (!isset($empwc['ewp_fk_workcentres']))
               $data['availability_errors'] = '<div class="dialog-box-border">An employee must be a member of any of the workcentres listed above.</div>';
         }
         //echo validation_errors();
         $this->_render_page($this->clsfunc, $data);
         return;
      }

      // Recieving input 
      $input = $this->input->post();

      // Setting ion-auth input details
      $username = strtolower(ifSetArray('auth', 'username'));
      $email = strtolower(ifSetArray('auth', 'email'));
      $password = ifSetArray('auth', 'password');
      $auth_group = ($input['employees']['emp_category'] == 1) ? array('1') : array('2'); // array('1') --> Admin , array('2') --> Non-Admin

      $additional_data = array(
          'first_name' => ucfirst(ifSetArray('auth', 'first_name')),
          'last_name' => '',
          'company' => '',
          'phone' => ifSetArray('auth', 'phone'),
      );

      // If ion-auth input details inserted successfully
      if ($insert_id = $this->ion_auth->register($username, $password, $email, $additional_data, $auth_group))
      {
         //Setting Tbl:employees input details            
         $input['employees']['emp_date'] = getSqlDate();
         $input['employees']['emp_id'] = $insert_id;
         $input['employees']['emp_name'] = $additional_data['first_name'];

         // Inserting data to Tbl:employees 
         $this->employees->insert($input['employees']);

         // If not Admin, grabbing registered workcentres.
         if ($input['employees']['emp_category'] != 1)
            $employee_work_centres = array_keys($input['empwc_flds']['ewp_fk_workcentres']);

         // If Admin, he should be registered in all workcentres including inactive workcentres.
         else if ($input['employees']['emp_category'] == 1)
            $employee_work_centres = $this->workcentres->getIds(); // Getting all workcentres (active & inactive).
















            
         ###------ START WORK LOG -----####
         // Related firms
         $firms = $this->workcentres->getFirmsOfWorkcentres($employee_work_centres);

         // Formating $firms to add to worklogs.
         $firms = implode(',', $firms);

         // Setting details for worklogs for Tbl:employees.
         $msg = 'A new ' . $data['emp_cats'][$input['employees']['emp_category']];
         $msg .= ' <span class="wlg_name">' . $additional_data['first_name'] . '</span> and his personal details have added.';
         $wlog_wc[0]['msg'] = $msg;
         $wlog_wc[0]['action'] = $this->add;

         // Adding Tbl:employees details to worklogs .
         $this->add_logs($this->table, $insert_id, get_url($this->table), get_popup_id($this->table), $wlog_wc, $this->add, $firms);

         ###------ END WORK LOG -----####

         foreach ($employee_work_centres as $wcntre_id)
         {
            $tblData = array();
            foreach ($input['empwc_flds'] as $fldName => $vals)
               $tblData[$fldName] = isset($input['empwc_flds'][$fldName][$wcntre_id]) ? $input['empwc_flds'][$fldName][$wcntre_id] : '';
            $tblData['ewp_fk_auth_users'] = $insert_id;
            $tblData['ewp_fk_workcentres'] = $wcntre_id;
            $tblData['ewp_date'] = getSqlDate();
            $tblData['ewp_status'] = 1; // Employee is active in workcentre. (Default)
            // Adding employee to workcentres
            $ewp_id = $this->employee_work_centre->insert($tblData);


            ####------ START WORK LOG -----####
            // Setting details for worklogs for Tbl:employee_work_centre.
            $wlog_wc = array();
            $msg = $data['emp_cats'][$input['employees']['emp_category']];
            $msg .= ' <span class="wlg_name">' . $additional_data['first_name'] . '</span> has became as a member of the workcentre.';
            $wlog_wc[$wcntre_id]['msg'] = $msg;
            $wlog_wc[$wcntre_id]['action'] = $this->add;

            // Adding Tbl:employee_work_centre details to worklogs .
            $this->add_logs('employee_work_centre', $ewp_id, get_url('employee_work_centre'), get_popup_id('employee_work_centre'), $wlog_wc, $this->add);
            ###------ END WORK LOG -----####
         }



         // Adding all tasks if the employee is admin/partner.
         if (($input['employees']['emp_category'] == 1) || ($input['employees']['emp_category'] == 2))
         {
            $this->load->model('tasks_model', 'tasks');
            $this->load->model('user_tasks_model', 'user_tasks');
            $tasks = $this->tasks->getIds();

            // Adding each tasks for employee to Tbl:user_tasks. 
            foreach ($tasks as $tsk_id)
            {
               $data = array('utsk_fk_auth_users' => $insert_id, 'utsk_fk_tasks' => $tsk_id);
               if (!$this->user_tasks->is_exists($data))
                  $this->user_tasks->insert($data);
               else
               {
                  $msg = "Error: User task already added.";
                  $level = 2; // Having errors.
                  $this->my_logout($msg, $level);
               }
            }
         }

         //redirecting
         $this->session->set_flashdata('message', $this->ion_auth->messages());
         $this->session->set_flashdata('message_level', 1); // Success        
         redirect($this->cls, 'refresh');
      }
      else
      {
         $this->session->set_flashdata('message', $this->ion_auth->messages());
         $this->session->set_flashdata('message_level', 2); // Failed.
         redirect($this->cls, 'refresh');
      }
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

      // Preventing url attack by sending employee id manually. An user can edit employee only those are under him.
      if (!$this->employee_work_centre->is_users_employee($this->user_id, $emp_id))
      {
         $msg = "Permission Error :- The employee is out of your controll.";
         $level = 2; // Having errors.
         $this->my_logout($msg, $level);
         return false;
      }

      $data['title'] = 'Edit Employee';
      $data['heading'] = 'EDIT EMPLOYEE';
      $data['firms'] = $this->firms->get_firms_options($this->user_id, 1);
      $data['workcentres'] = $this->workcentres->get_workcentres($this->user_id, '', 1);
      $cat = $this->is_admin ? 1 : 3;
      $data['emp_cats'] = $this->employees->get_employee_category($cat);
      $data['availability_errors'] = '';


      // Getting current details of workcetnre.
      $data['employees'] = $this->employees->getById($emp_id);

      $this->load->model('ion_auth_model', 'ion_auth_model');
      $data['auth'] = $this->ion_auth_model->getById($emp_id);

      $availabilityFields = $this->getAvailabilityFields(TRUE);
      $empwcntrs = $this->employee_work_centre->getEmployeesWorkcentres($emp_id, '', 'All', 'wcntr_id', '', '');

      foreach ($data['workcentres'] as $wc)
      {
         //echo "<br>&ensp;Workcentre: " . $wc['wcntr_name'] . '(' . $wc['wcntr_id'] . ')';
         foreach ($empwcntrs as $empwc)
         {
            //echo "<br>&ensp;&ensp;ewp_fk_workcentres: " . $empwc['ewp_fk_workcentres'];

            if ($empwc['ewp_fk_workcentres'] == $wc['wcntr_id'])
            {
               foreach ($availabilityFields as $fld_name)
               {
                  $data['empwc_flds'][$fld_name][$wc['wcntr_id']] = $empwc[$fld_name];
               }
            }
            else
            {
               if (!isset($data['empwc_flds']['ewp_fk_workcentres'][$wc['wcntr_id']]))
               {
                  foreach ($availabilityFields as $fld_name)
                  {
                     $data['empwc_flds'][$fld_name][$wc['wcntr_id']] = '';
                  }
               }
            }
         }
      }
      $this->_render_page($this->cls . "/edit", $data);
      return;
   }

   function edit()
   {
      // Checking is the current task is enabled for the user
      $this->isAllowedTask($this->clsfunc);

      if (!($emp_id = $_POST['employees']['emp_id']))
      {
         $msg = "Permission Error :- Wrong access.";
         $level = 2; // Having errors.
         $this->my_logout($msg, $level);
         return false;
      }



//	Validating 
      $v_config = validationConfigs('employees', '', 'employees');
      $this->form_validation->set_rules($v_config);

      // Adding ion-auth validations
      $tables = $this->config->item('tables', 'ion_auth');
      $this->form_validation->set_rules('auth[first_name]', "Name", 'required|xss_clean');
      $this->form_validation->set_rules('auth[email]', "Email", 'valid_email|callback_isEmailExist');
      $this->form_validation->set_rules('auth[phone]', 'Phone No', 'xss_clean');

      // Login details are usefull only for Admin, Partner or Staff
      $cat = ifSetArray('employees', 'emp_category');
      if (($cat == 1 || $cat == 2 || $cat == 3) && ($this->is_admin || $this->is_partner)) // Admin/Parteners/Staffs
      {
         $this->form_validation->set_rules('auth[username]', "Username", 'required|max_length[25]|callback_isUsernameExist');

         // If previously there has not set any password for the employee.
         if (!$this->ion_auth->isPasswordExist($emp_id) || $_POST['auth']['password'])
            $this->form_validation->set_rules('auth[password]', "Password", 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
         if ($_POST['auth']['password'])
            $this->form_validation->set_rules('password_confirm', "Password confirmation", 'required');
      }

//        if ($_POST['auth']['password'] && $this->ion_auth->isPasswordExist($emp_id))
//        {
//            $this->form_validation->set_rules('old_password', "Old Password", 'required|callback_oldPassword');
//        }

      $data['workcentres'] = $this->workcentres->get_workcentres($this->user_id, '', 1);

      $cat = $this->is_admin ? 1 : 3;
      $data['emp_cats'] = $this->employees->get_employee_category($cat);

      $availabilityFields = $this->getAvailabilityFields(TRUE);
      $empwc = $this->input->post('empwc_flds');

      foreach ($availabilityFields as $fld_name)
      {
         foreach ($data['workcentres'] as $wc)
         {
            if (isset($empwc[$fld_name][$wc['wcntr_id']]))
            {
               $empwc_flds[$fld_name][$wc['wcntr_id']] = $empwc[$fld_name][$wc['wcntr_id']];
            }
            else
            {
               $empwc_flds[$fld_name][$wc['wcntr_id']] = '';
            }
         }
      }

      if ((!$this->form_validation->run()) || (!isset($empwc['ewp_fk_workcentres'])))
      {
         $data['title'] = 'Edit Employee';
         $data['heading'] = 'EDIT EMPLOYEE';
         $data['firms'] = $this->firms->get_firms_options($this->user_id, 1);
         $data['availability_errors'] = '';
         if ($_POST)
         {
            $data['message'] = 'Some Errors Occured !';
            $data['message_level'] = 2;
            $data = array_merge($data, $this->get_inputs('nofilter')); //DNC (Don't change the coding position)
            $data['empwc_flds'] = $empwc_flds; //DNC (Don't change the coding position)
            if (!isset($empwc['ewp_fk_workcentres']))
               $data['availability_errors'] = '<div class="dialog-box-border">An employee must be a member of any of the workcentres listed above.</div>';
         }
//            echo validation_errors();
         $this->_render_page($this->clsfunc, $data);
         return;
      }

      // Storing previous details from Tbl:employees & Tbl:auth_users. It will be compared with latest details after edit Tbl:employee_work_centre details.
      $prev_employees_details = $this->employees->getById($emp_id);
      $prev_auth_details = $this->ion_auth->getPersonalDetailsById($emp_id);


      // Recieving input 
      $input = $this->input->post();

      //Editing ion_auth password
      if (($this->is_admin || $this->is_partner))
      {
         if ($input['auth']['password'])
         {
            $change = $this->ion_auth->change_password2($emp_id, '', $input['auth']['password']);

            if (!$change)
            {
               echo "<br>Change Login Errors :- " . $this->ion_auth->errors() . '.<br>';
               return false;
            }
         }
      }

      // Setting ion-auth input details
      $auth_data = array();
      if ($this->is_admin || $this->is_partner)
         $auth_data['username'] = strtolower(ifSetArray('auth', 'username'));
      $auth_data['email'] = strtolower(ifSetArray('auth', 'email'));
      $auth_data['phone'] = strtolower(ifSetArray('auth', 'phone'));
      $auth_data['first_name'] = ucfirst(ifSetArray('auth', 'first_name'));

      //Editing ion_auth details.
      if ($auth_data)
         $this->ion_auth->update_where($auth_data, array('id' => $emp_id));

      // Editing ion_auth group
      $auth_group = ($input['employees']['emp_category'] == 1) ? '1' : '2'; // '1' --> Admin , '2' --> Non-Admin
      $this->ion_auth->editGroup($auth_group, $emp_id);

      //Setting Tbl:employees input details            
      $input['employees']['emp_name'] = ucfirst(ifSetArray('auth', 'first_name'));
      $emp_name = $input['employees']['emp_name'];

      // Inserting data to Tbl:employees 
      $this->employees->save($input['employees'], $emp_id);

      // If not Admin, grabbing registered workcentres.
      if ($input['employees']['emp_category'] != 1)
         $current_workcentres = array_keys($input['empwc_flds']['ewp_fk_workcentres']);

      // If Admin, he should be registered in all workcentres including inactive workcentres.
      else if ($input['employees']['emp_category'] == 1)
         $current_workcentres = $this->workcentres->getIds(); // Getting all workcentres (active & inactive).









         
//Before saving new data about the employee, getting previous data.
      $previous_workcentres = $this->employee_work_centre->getEmployeesWorkcentres($emp_id, '', 'wcntr_id', 'wcntr_id', '', 1);

      $new_workcentres = array_diff($current_workcentres, $previous_workcentres);
      $deleted_workcentres = array_diff($previous_workcentres, $current_workcentres);
      $edited_workcentres = array_intersect($previous_workcentres, $current_workcentres);

      # Deleting employee from workcentre.
      if ($deleted_workcentres)
      {
         foreach ($deleted_workcentres as $wcntr_id)
         {
            // Taking details before it is being deleting.
            $prev = $this->employee_work_centre->get_row(array('ewp_fk_auth_users' => $emp_id, 'ewp_fk_workcentres' => $wcntr_id));

            // Deleting
            $this->employee_work_centre->delete_where(array('ewp_fk_auth_users' => $emp_id, 'ewp_fk_workcentres' => $wcntr_id));

            //Adding to worklog 

            $msg = $data['emp_cats'][$input['employees']['emp_category']];
            $msg .= ' <span class="wlg_name">' . $emp_name . '</span> has been deleted from the workcentre.';
            $wlog_wc = array(); //The worklog is done under which workcentres. 
            $wlog_wc[$wcntr_id]['msg'] = $msg;
            $wlog_wc[$wcntr_id]['action'] = $this->delete;

            $wlog_id = $this->add_logs('employee_work_centre', $prev['ewp_id'], get_url('employee_work_centre'), get_popup_id('employee_work_centre'), $wlog_wc, $this->delete);


            // Backing up previous details for data recovery needs.
            if (to_be_backed_up('employee_work_centre')) //If need to be backed up.
               $this->backups->backUpData($wlog_id, $prev, 'employee_work_centre', $prev['ewp_id']);

            // If the employee is not available in any workcentres where vehicle is available,
            // he must be removed from labours list of that vehicle.
            $this->troubleshoot_1($emp_id, $wcntr_id, 1);
         }
      }

      # Adding employee to workcentres.
      if ($new_workcentres)
      {
         $ewp_id = '';
         $wlog_wc = array(); //The worklog is done under which workcentres. 
         foreach ($new_workcentres as $wcntr_id)
         {
            // Adding employee to workcentres if he is not in there.
            if (!$this->employee_work_centre->is_exists(array('ewp_fk_auth_users' => $emp_id, 'ewp_fk_workcentres' => $wcntr_id)))
            {
               $tblData = array();
               foreach ($input['empwc_flds'] as $fldName => $vals)
                  $tblData[$fldName] = isset($input['empwc_flds'][$fldName][$wcntr_id]) ? $input['empwc_flds'][$fldName][$wcntr_id] : '';
               $tblData['ewp_fk_auth_users'] = $emp_id;
               $tblData['ewp_fk_workcentres'] = $wcntr_id;
               $tblData['ewp_date'] = getSqlDate();
               $ewp_id = $this->employee_work_centre->insert($tblData);

               // Setting details for worklogs.
               $wlog_wc = array(); //The worklog is done under which workcentres. 
               $msg = $data['emp_cats'][$input['employees']['emp_category']];
               $msg .= ' <span class="wlg_name">' . $emp_name . '</span> has became as a member of the workcentre.';
               $wlog_wc[$wcntr_id]['msg'] = $msg;
               $wlog_wc[$wcntr_id]['action'] = $this->add;

               // Adding to worklogs.
               if ($ewp_id)
                  $this->add_logs('employee_work_centre', $ewp_id, get_url('employee_work_centre'), get_popup_id('employee_work_centre'), $wlog_wc, $this->add);
            }
         }
      }

      # Editing employee to workcentres.
      if ($edited_workcentres)
      {
         foreach ($edited_workcentres as $wcntr_id)
         {
            // Taking details before it is being deleting.
            $prev = $this->employee_work_centre->get_row(array('ewp_fk_auth_users' => $emp_id, 'ewp_fk_workcentres' => $wcntr_id));
            $ewp_id = $prev['ewp_id'];
            $tblData = array();
            foreach ($input['empwc_flds'] as $fldName => $vals)
               $tblData[$fldName] = $input['empwc_flds'][$fldName][$wcntr_id];
            $tblData['ewp_fk_auth_users'] = $emp_id;
            $tblData['ewp_fk_workcentres'] = $wcntr_id;

            // Editing employee details in workcentres
            $this->employee_work_centre->update_where($tblData, array('ewp_id' => $ewp_id));

            // Taking back edited data (as numbers in database format).
            $after_edit = $this->employee_work_centre->getById($ewp_id);

            // Asuming there was no editing made yet.
            $edited = false;

            //Checking is there any changes made.
            foreach ($after_edit as $key => $val)
            {
               if ($prev[$key] != $val)
               {
                  // Confirms that the data has been edited
                  $edited = true;
                  break;
               }
            }

            // If any changes made, adding to worklogs.
            if ($edited)
            {
               // Setting details for worklogs.
               // Checking is there anything to warn Admin.
               $warning_fields = array('ewp_ob', 'ewp_ob_mode', 'ewp_day_wage', 'ewp_day_hourly_wage', 'ewp_day_ot_wage', 'ewp_night_wage', 'ewp_night_hourly_wage', 'ewp_night_ot_wage', 'ewp_salary_wage');
               $wlog_warnings = $this->check_warnings($prev, $after_edit, $warning_fields);

               $msg = 'Membership Details of ' . $data['emp_cats'][$input['employees']['emp_category']];
               $msg .= ' <span class="wlg_name">' . $emp_name . '</span> has been edited.';
               $wlog_wc = array(); //The worklog is done under which workcentres. 
               $wlog_wc[$wcntr_id]['msg'] = $msg;
               $wlog_wc[$wcntr_id]['action'] = $this->edit;

               // Adding to worklogs.
               $wlog_id = $this->add_logs('employee_work_centre', $ewp_id, get_url('employee_work_centre'), get_popup_id('employee_work_centre'), $wlog_wc, $this->edit, '', $wlog_warnings);

               // Backing up previous details for data recovery needs.
               if (to_be_backed_up('employee_work_centre')) //If need to be backed up.
                  $this->backups->backUpData($wlog_id, $prev, 'employee_work_centre', $ewp_id);


               // If the employee is deactivated in the workcentre where the vehicle is available,
               // he must be removed from labours list of that vehicle.
               $pre_cat = $prev_employees_details['emp_category'];
               $new_cat = $input['employees']['emp_category'];
               $is_driver = (($pre_cat == 4) && ($new_cat == 4)) ? TRUE : FALSE;
               $is_loader = (($pre_cat == 5) && ($new_cat == 5)) ? TRUE : FALSE;
               if ($is_driver || $is_loader)
                  if (($after_edit['ewp_status'] == INACTIVE) && ($prev['ewp_status'] == ACTIVE))
                     $this->troubleshoot_1($emp_id, $wcntr_id, 2);
            }
         }
      }

      // Adding all tasks if the employee is admin/partner.
      if (($input['employees']['emp_category'] == 1) || ($input['employees']['emp_category'] == 2))
      {
         $this->load->model('tasks_model', 'tasks');
         $this->load->model('user_tasks_model', 'user_tasks');
         $tasks = $this->tasks->getIds();

         // Adding each tasks for employee to Tbl:user_tasks. 
         $tbl_data = '';
         foreach ($tasks as $tsk_id)
         {
            $tbl_data = array('utsk_fk_auth_users' => $emp_id, 'utsk_fk_tasks' => $tsk_id);
            if (!$this->user_tasks->is_exists($tbl_data))
               $this->user_tasks->insert($tbl_data);
         }
      }

      // if employee is inactvie in all workcentres, he must be deactivated from Tbl:employees.
      if (!$this->employee_work_centre->is_exists(array('ewp_fk_auth_users' => $emp_id, 'ewp_status' => 1)))
         $this->employees->save(array('emp_status' => 2), $emp_id);

      // if employee is actvie in any of the workcentres, he must be active in Tbl:employees.
      else if ($this->employee_work_centre->is_exists(array('ewp_fk_auth_users' => $emp_id, 'ewp_status' => 1)))
         $this->employees->save(array('emp_status' => 1), $emp_id);

      // Checking is employees details in Tbl: employees or Tbl:auth_users changed. if changed, putting to worklogs
      // Getting latest details from Tbl:employees & Tbl:auth_users. 
      $latest_employees_details = $this->employees->getById($emp_id);
      $latest_auth_details = $this->ion_auth->getPersonalDetailsById($emp_id);

      $auth_edited = FALSE;

      // Comparing old details with latest details of Tbl:auth_users.
      foreach ($prev_auth_details as $key => $val)
      {
         if ($latest_auth_details[$key] != $val)
         {
            $auth_edited = TRUE;
            break;
         }
      }

      $employee_edited = false;

      // Comparing old details with latest details of Tbl:employees.
      foreach ($prev_employees_details as $key => $val)
      {

         if ($latest_employees_details[$key] != $val)
         {
            $employee_edited = TRUE;
            break;
         }
      }


      // If anything edited, adding to worklog.
      if ($auth_edited || $employee_edited)
      {
         $availability = $this->employee_work_centre->getEmployeesWorkcentres($emp_id, '', 'wcntr_id', 'wcntr_id', 1, 1);
         $wlog_wc = array(); //The worklog is done under which workcentres.

         $msg = 'Personal Details of ' . $data['emp_cats'][$input['employees']['emp_category']];
         $msg .= ' <span class="wlg_name">' . $emp_name . '</span> has been edited.';

         foreach ($availability as $wcntr_id)
         {
            $wlog_wc[$wcntr_id]['msg'] = $msg;
            $wlog_wc[$wcntr_id]['action'] = $this->edit;
         }

         if (!$availability)
         {
            $wlog_wc[0]['msg'] = $msg;
            $wlog_wc[0]['action'] = $this->edit;
         }

         // Adding to worklogs.
         $wlog_id = $this->add_logs($this->table, $emp_id, get_url($this->table), get_popup_id($this->table), $wlog_wc, $this->edit);

         // Backing up previous details for data recovery needs.
         if (to_be_backed_up($this->table)) //If need to be backed up.
         {
            $backup_data = array_merge($prev_employees_details, $prev_auth_details);
            $this->backups->backUpData($wlog_id, $backup_data, $this->table, $emp_id);
         }
      }

      // Deleteing illegal employees from vehicle.
      $this->deleteVehicleEmployee($prev_employees_details, $latest_employees_details);
 //return;     
      //redirecting
      $msg = $data['emp_cats'][$prev_employees_details['emp_category']];
      $msg .= ' <span class="wlg_name">' . $prev_employees_details['emp_name'] . '</span> edited successfully.';
      $this->session->set_flashdata('message', $msg);
      $this->session->set_flashdata('message_level', 1); // Success 
      redirect("employees/index/action", 'refresh');
   }

   function deleteVehicleEmployee($prev_employees_details, $latest_employees_details)
   {
      // If the emp_category of employee (driver/loader) edited, and he is a labour (Driver/Loader) of a vehicle,
      // he must be removed from labours list of that vehicle.
      
      // If previously the employee is a driver or a loader.
      if (($prev_employees_details['emp_category'] == 4) || ($prev_employees_details['emp_category'] == 5))
      {
         // Checking the category is edited or not.
         if ($prev_employees_details['emp_category'] != $latest_employees_details['emp_category'])
         {
            $emp_id = $prev_employees_details['emp_id'];
            $pre_cat = $prev_employees_details['emp_category'];
            $new_cat = $latest_employees_details['emp_category'];
            $emp_name = $prev_employees_details['emp_name'];
            $pre_cat_text = ($pre_cat == 4) ? 'Driver' : (($pre_cat == 5) ? 'Loader' : 'Logical Error');
            $new_cat_text = $this->employees->get_employee_category(1,$new_cat);
            
            
            $vehicles_employees = $this->vehicles_employees->getVehiclesEmployees($emp_id);
            foreach ($vehicles_employees as $row)
            {
               $vemp_id = $row['vemp_id'];
               $vhcl_no = $row['vhcl_no'];

               // Deleting employee from vehicle.
               $this->vehicles_employees->remove($vemp_id);

               // Worklog should be displayed in all active workcentres where the vehicle has been registered.
               $workcentres = $this->vehicle_workcentres->getVehicleWorkcentres($row['vhcl_id']);

               $msg = "When changed the employee";
               $msg .= " <span class='wlg_name'>$pre_cat_text: $emp_name</span>";
               $msg .= " as <span class='wlg_name'>$new_cat_text</span>";
               $msg .= ", he is deleted from the labours list of the vehicle<span class='wlg_name'>$vhcl_no</span>";
               
               // Inserting worklogs of Tbl: vehicles_employees.
               $this->send_wlog('vehicles_employees', $vemp_id, $msg, $this->delete, $this->delete, $workcentres);
            }
         }
      }
   }

   function troubleshoot_1($emp_id, $wcntr_id, $flag)
   {
      // If the employee is not available in any workcentres where vehicle is available,
      // he must be removed from labours list of that vehicle.
      $emp_details = $this->employees->getById($emp_id);
      $wcntr_name = $this->workcentres->getNameById($wcntr_id);
      $emp_name = $emp_details['emp_name'];
      $emp_cat = $emp_details['emp_category'];
      $user = $this->employees->get_employee_category(1, $this->user_cat) . ': ' . $this->user_name;
      if (($emp_cat == 4) || ($emp_cat == 5))
      {
         $cat_text = ($emp_cat == 4) ? 'Driver' : (($emp_cat == 5) ? 'Loader' : 'Logical Error');

         // When delete employee from workcentre
         if ($flag == 1)
         {
            $post_msg = "; By the system troubleshooter when <span class='wlg_name'>$user</span> removed the ";
            $post_msg .= " employee <span class='wlg_name'>$cat_text: $emp_name</span>";
            $post_msg .= " from the workcentre <span class='wlg_name'>$wcntr_name</span>";
         }
         
         // When deactivated the employee in the workcentre
         if ($flag == 2)
         { 
            $post_msg = "; By the system troubleshooter when <span class='wlg_name'>$user</span> deactivated the ";
            $post_msg .= " employee <span class='wlg_name'>$cat_text: $emp_name</span>";
            $post_msg .= " in the workcentre <span class='wlg_name'>$wcntr_name</span>";
         }

         $this->troubleshoot->delete_all_illegal_labours_from_vehicle_1('', '', $post_msg);
      }
   }

   function index()
   {
      // Checking is the current task is enabled for the user
      $this->isAllowedTask($this->cls);

      // Receiving input
      $input = $this->get_pagination_inputs($this->employees);

      //Set the flash data message if there is one has set before redirected to this page.
      $data['message'] = $this->session->flashdata('message');
      $data['message_level'] = $this->session->flashdata('message_level');
      $data['offset'] = $input['offset'];
      $data['title'] = "Employees";
      $data['heading'] = "Search Employees";
      $data['status'] = $this->employees->get_employee_status();
      $data['emp_cats'] = $this->employees->get_employee_category($this->user_cat);

      $this->per_page = $_POST ? $input['PER_PAGE'] : $this->per_page;

      $data['workcentres'] = $this->workcentres->get_workcentres_options($this->user_id, $this->firm_id, 1);

      // Setting default search options.
      if (!$_POST)
      {
         $input['emp_status'] = 1; //Default status is Active   
      }

      //If reffered from Worklogs;
      $wlog_ref_id = ($this->uri->segment(3) == 'wlogs') ? $this->uri->segment(4) : '';

      $data['table'] = $this->employees->index($input, $this->user_cat, $data['workcentres'], $wlog_ref_id);
      $data['num_rows'] = $this->employees->index($input, $this->user_cat, $data['workcentres'], $wlog_ref_id, true);

      // Adding other details
      if ($data['table'])
      {
         // Getting user, who last changed the worklog.
         $data['wlog'] = $this->getWlogUser($data['table']);

         foreach ($data['table'] as $row)
         {
            // Getting employee availability
            $availability = $this->employee_work_centre->getEmployeesWorkcentres($row[$this->p_key], '', 'wcntr_name', 'wcntr_name');
            $data['availability'][$row[$this->p_key]] = implode(', ', $availability);
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
      $config[] = array('f_emp_date', 'From Date', 'callback_compare_dates[' . $this->input->post('t_emp_date') . ']');
      $data = $this->checkValidations($config);
      return $data;
   }

   function is_workcentre_selected()
   {
      if ($this->input->post('wcntre_id'))
         return TRUE;
      else
      {
         $this->form_validation->set_message('is_workcentre_selected', 'Select a workcentre where employee is available.');
         return FALSE;
      }
   }

   /* function oldPassword($value)
     {
     $emp_id = $_POST['employees']['emp_id'];
     $old_password = $this->ion_auth->isPasswordExist($emp_id);
     if ($old_password && $_POST['auth']['password'])
     {
     if ($old_password == $value)
     return TRUE;
     else
     {
     $this->form_validation->set_message('oldPassword', 'Old password is not currect.');
     return FALSE;
     }
     }
     return TRUE;
     } */

   function isEmailExist($value)
   {
      $emp_id = $_POST['employees']['emp_id'];
      if (!$value)
         return TRUE;
      if ($this->ion_auth->is_exists(array('email' => $value), $emp_id))
      {
         $this->form_validation->set_message('isEmailExist', 'Email already exists.');
         return FALSE;
      }
      else
         return TRUE;
   }

   function isUsernameExist($value)
   {
      $emp_id = $_POST['employees']['emp_id'];
      if (!$value)
         return TRUE;
      if ($this->ion_auth->is_exists(array('username' => $value), $emp_id))
      {
         $this->form_validation->set_message('isUsernameExist', 'Username already exists.');
         return FALSE;
      }
      else
         return TRUE;
   }

   function oldPassword($old_password)
   {
      $emp_id = $_POST['employees']['emp_id'];
      if ($this->ion_auth->oldPasswordMatch($emp_id, $old_password) === TRUE)
      {
         return TRUE;
      }
      else
      {
         $this->form_validation->set_message('oldPassword', 'Old password doesn\'t match.');
         return FALSE;
      }
   }

}

?>