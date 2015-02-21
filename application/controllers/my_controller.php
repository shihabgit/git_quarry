<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class My_controller extends CI_Controller
{

   var $per_page = 100;                 //  No of records per page in pagination.
   var $defaultHeader = 'header';      //  Default header file for each view page.
   var $defaultRedirect = '';          //  Default redirect page.
   var $cls = '';                      //  Variable contains current className.
   var $func = '';                     // Variable contains current MethodName.
   var $clsfunc = '';                  //  Variable contains current className/MethodName
   var $errorTitle = '<div class="failureHead"><img src="images/failure.gif" width="15" height="15" /> Error!!!</div>';
   var $allow_multiple = '';
   var $environment = '';
   var $version = '';
   var $firm_id = '';
   var $firm_dt = '';
   var $firm_name = '';
   var $all_firms = '';
   var $user_details = '';
   var $user_id = '';
   var $user_name = '';
   var $is_admin = '';
   var $is_partner = '';
   var $is_staff = '';
   var $all_employee_category = '';
   var $users_employee_category = '';
   var $user_cat = '';
   var $user_cat_name = '';
   var $add = '';    //Variable used to represent worklogs 'add' action.
   var $edit = '';   //Variable used to represent worklogs 'edit' action.
   var $delete = ''; //Variable used to represent worklogs 'delete' action.
   var $theme = ''; // Determines which theme used. (Tbl:settings)
   var $themes = ''; // Array of all possible themes;
   var $table; // Related Table name in db.
   var $p_key; // Primary key of table

   public function __construct()
   {
      parent::__construct();

      // If any table missed in database, creating it.
      if ($this->missedTables())
         exit;
      //Setting Default Redirect.
      $this->config->load('routes', TRUE);
      $this->defaultRedirect = $this->config->item('default_redirect', 'routes');
      $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
      $this->set_classfunc();
      $this->load->model('firms_model', 'firms', true);
      $this->load->model('workcentres_model', 'workcentres');
      $this->load->model('employees_model', 'employees');
      $this->load->model('settings_model', 'settings');
      $this->load->model('worklogs_model', 'worklogs');
      $this->load->model('verify_model', 'verify');
      $this->load->model('backups_model', 'backups');

      $this->add = 1;    //Variable used to represent worklogs 'add' action.
      $this->edit = 2;   //Variable used to represent worklogs 'edit' action.
      $this->delete = 3; //Variable used to represent worklogs 'delete' action.
      // Setting timezone
      date_default_timezone_set($this->config->item('timezone'));

      // Determining is the s/w allows multiple firm/workcentre fecility.
      $this->allow_multiple = $this->config->item('allow_multiple');

      //Determining the software environment. values will be either 'Development' or 'Production'
      $this->environment = $this->config->item('environment');

      // Specifying the software version
      $this->version = $this->config->item('version');

      // Logged in user's details from Tbl:auth_users.
      $this->user_details = $this->ion_auth->user()->row();

      if ($this->user_details)
      {   // Getting logged in user's id.
         $this->user_id = $this->user_details->id;

         // Logged in user's details contain both the details from Tbl:auth_users and Tbl:employees.
         $this->user_details = $this->employees->employee_details($this->user_id);

         // Setting user's name
         $this->user_name = $this->user_details['first_name'];


         // Category id of logged in user
         $this->user_cat = $this->user_details['emp_category'];

         // All Categories of Employees
         $this->all_employee_category = $this->employees->get_employee_category(1);

         // Employee categories of related to user
         $this->users_employee_category = $this->employees->get_employee_category($this->user_cat);

         // Category name of logged in user
         $this->user_cat_name = $this->all_employee_category[$this->user_cat];
      }

      // Current firm
      $this->firm_id = $this->session->userdata('firm_id');

      // Details of current firm
      $this->firm_dt = $this->firms->getById($this->firm_id);
      $this->firm_name = isset($this->firm_dt['firm_name']) ? $this->firm_dt['firm_name'] : 'No Firm Selected';

      // All active/inactive firms (To show in firm dropdown)
      $this->all_firms = $this->firms->get_firms($this->user_id);

      // Is logged in user is an Admin.
      $this->is_admin = $this->ion_auth->is_admin();

      // Is logged in user is a Partner.
      $this->is_partner = $this->employees->is_partner($this->user_id);

      // Is logged in user is a Staff.
      $this->is_staff = $this->employees->is_staff($this->user_id);

      $this->theme = $this->firm_id ? $this->settings->getFirmSettings('THEME', $this->firm_id) : $this->settings->getDefaultValue('THEME');
      $this->themes = $this->settings->getSettingsValues('THEME');
   }

   // returns array whichs represents the what the status action is made.
   function status_message($action, $format = 1)
   {
      if (!$action)
         return 'Logical Error';

      if ($format == 1)
         $status_message = array(1 => 'activated', 2 => 'deactivated');

      if ($format == 2)
         $status_message = array(1 => 'Activated', 2 => 'Deactivated');

      if ($format == 3)
         $status_message = array(1 => 'ACTIVATED', 2 => 'DEACTIVATED');

      return $status_message[$action];
   }

   /** 	Rendering page
    * 	@author : 	"Shihabu Rahman K" <shihab@levoirsolutions.com>
    * 	@params : 	$data -> Values for view page.
    * 	@return : 	true
    * 	@access public
    */
   function _render_page($view, $data = null, $render = false, $show_shortcut_menu = true)
   {

      $this->viewdata = (empty($data)) ? $this->data : $data;
      $this->viewdata['view'] = $view;
      $this->viewdata['per_pages'] = array(0 => 'All', 10 => 10, 50 => 50, 100 => 100, 250 => 250);

      // Determining show/hide the Shortcut Menu.
      $this->viewdata['show_shortcut_menu'] = $show_shortcut_menu;

      // Determining is the new firm/workcentre creation is allowed.
      $this->viewdata['is_allowed'] = $this->allow_multiple();

      $view_html = $this->load->view($this->defaultHeader, $this->viewdata, $render);

      if (!$render)
         return $view_html;
   }

   // If any table missed in database, creating it.
   function missedTables()
   {
      if ($missed = $this->my_model->checkForTables(getTables('All')))
      {
         // Setting messages.
         echo "<h2>Please insert basic values to the table.</h2>";

         // Creating missed tables in database.
         createTables($missed);

         return TRUE;
      }
      return FALSE;
   }

   function get_inputs($flag = 'filter')
   {
      // study about the inbuilt function filter_input()
      $input = array();
      if ($_GET)
         $fields = $_GET;
      else
         $fields = $this->input->post();
      if (is_array($fields))
      {
         foreach ($fields as $fld => $val)
         {
            if (is_array($val))
            {
               if ($flag == 'filter')
                  $input[$fld] = array_filter($val, 'trim');
               elseif ($flag == 'nofilter')
                  $input[$fld] = $val;
            }
            else
               $input[$fld] = trim($val);
         }
      }
      return $input;
   }

   function formatePopupError($msg)
   {
      $open = '<div class="failureHead"><img src="images/failure.gif" width="15" height="15" /> Error!!!<div class="pop_failure">';
      $close = '</div></div>';
      return "$open $msg $close";
   }

   function get_pagination_inputs($model)
   {
      # To work this, I have used my own pagination (application/libraries/Pagination.php).
      # it is a copy of system/libraries/Pagination.php. But I have made some changes. 
      # To see the changes made by me Ctrl+F: "Hided by shihab" / "Added by shihab".
      # The new library is created to support page offset after an action (Eg: edit).
      # Normaly pagination class takes offset from uri_segment.
      # But in the newly created library, we can set the offset through the config as $config['page_offset'] = $offset.
      # For example see My_controller::initPagination();

      if ($this->uri->segment(3) == 'paging')
      {
         //$offset = $this->uri->segment(4) ? : 0;
         $input = $model->get_form_inputs($this->clsfunc);
         $input['offset'] = $this->uri->segment(4) ? : 0;
         $_POST = $input;
      }
      else if ($this->uri->segment(3) == 'action')
      {
         //$offset = $this->uri->segment(4) ? : 0;
         $input = $model->get_form_inputs($this->clsfunc);
         $_POST = $input;
      }
      else
      {
         //$offset = 0;
         $input = $this->get_inputs();
         $input['offset'] = 0;
         if (!$_POST)
            $input['PER_PAGE'] = $this->per_page;
      }
      //$input['offset'] = $offset;

      $model->set_form_inputs($this->clsfunc, $input);
      //print_r($input);
      return $input;
   }

   function input_backup($model)
   {
      if ($this->uri->segment(3) == 'action')
      {
         $input = $model->get_form_inputs($this->clsfunc);
         $_POST = $input;
      }
      else
      {
         $input = $this->get_inputs();
      }
      $model->set_form_inputs($this->clsfunc, $input);
      return $input;
   }

   function initPagination($table, $num_rows, $offset)
   {
      # To work this, I have used my own pagination (application/libraries/Pagination.php).
      # it is a copy of system/libraries/Pagination.php. But I have made some changes. 
      # To see the changes made by me Ctrl+F: "Hided by shihab" / "Added by shihab".
      # The new library is created to support page offset after an action (Eg: edit).
      # Normaly pagination class takes offset from uri_segment.
      # But in the newly created library, we can set the offset through the config as $config['page_offset'] = $offset.
      # For example see bottom of this function;

      $data['page_count'] = $this->per_page ? ceil($num_rows / $this->per_page) : 1;
      $this->load->library('pagination');
      $config = get_pagination_configurations();
      $config['per_page'] = $this->per_page;
      $config['base_url'] = site_url($this->clsfunc . '/paging');
      $config['total_rows'] = $num_rows;
      $config['page_offset'] = $offset;
      $this->pagination->initialize($config);
      return $data;
   }

   function json_options($data, $no_data)
   {
      if (!$data)
      {
         $json[] = array('value' => '', 'text' => $no_data);
      }
      else
      {
         $json[] = array('value' => '', 'text' => 'Select');
         foreach ($data as $key => $val)
            $json[] = array('value' => $key, 'text' => $val);
      }

      echo json_encode($json);
   }

   function checkValidations($config)
   {
      $this->form_validation->set_error_delimiters('', '');

      foreach ($config as $val)
         $this->form_validation->set_rules($val[0], $val[1], $val[2]);

      // If validation fails
      $data = array();
      if (!$this->form_validation->run() && $_POST)
      {
         $data['message'] = validation_errors();
         $data['message_level'] = 2;
         $data['num_copy'] = 0;
         $data['table'] = array();
         $data['offset'] = 0;
         $data['page_count'] = 0;
      }
      return $data;
   }

   // Validation Callback
   function compare_dates($from, $to)
   {
      if ((strtotime($from) > strtotime($to)) && $to)
      {
         $this->form_validation->set_message('compare_dates', '"FROM" date must be less than "TO" date');
         return FALSE;
      }

      return TRUE;
   }

   function set_classfunc()
   {
      $Rtr = & load_class('Router');
      $this->cls = $Rtr->fetch_class();
      $this->func = $Rtr->fetch_method();
      $this->clsfunc = "$this->cls/$this->func";
   }

   /**
    *  Determining is the logged in user allowed to go forward with the current action.
    */
   function isAllowed()
   {
      $allowed = TRUE;
      $msg = "";

      // Checking is user loged in
      if (!$this->ion_auth->logged_in())
      {
         $msg = "Not logged in";
         $allowed = FALSE;
      }


      //Checking is any firm selected.
      else if (!$this->firm_id)
      {
         $msg = "No firms selected....";
         $allowed = FALSE;
      }

      //Checking is the user is registered under the current firm.
      else if (!$this->firms->is_user_registered_in_firm($this->user_id, $this->firm_id))
      {
         $msg = "Unknown firm";
         $allowed = FALSE;
      }

      //Checking is user active.
      else if (!$this->employees->is_active($this->user_id))
      {
         $msg = "Inactive user";
         $allowed = FALSE;
      }


      // Checking is atleast one workcentre has created.
      else if ($this->clsfunc != 'index/index' && $this->cls != 'firms' && $this->cls != 'workcentres')
      {
         //If no workcentres created yet;
         if (!$this->workcentres->get_workcentres_options($this->user_id, $this->firm_id, 1))
         {
            $msg = "No workcentres found";
            $allowed = FALSE;
         }
      }



      if (!$allowed)
      {
         $msg = "Permission Error :- " . $msg;
         $level = 2; // Having errors.
         $this->my_logout($msg, $level);
         return false;
      }

      return true;
   }

   /**
    * This function is used to do/check anything automatically by the system. 
    * This funciton will be called from any classes you need, commonly when user login this will be called.
    * 
    */
   function auto_run()
   {
      // Add rent amount to rent account if the rent payment date reached. It will be checked on each user login.
      // Suppose if the user has not logged in when rent period occured. then it must be checked on next time he login.
      // Is any reminder has set.
   }

   /**
    * Callback function for validations.
    * @param type $val
    * @return boolean
    * 
    */
   function is_numeric($val)
   {
      if (empty($val) || is_numeric($val))
         return TRUE;
      else
      {
         $this->form_validation->set_message('is_numeric', 'The %s field can have only numeric data.');
         return FALSE;
      }
   }

   /**
    * Callback function for validations. It also called on some other occations.
    * When creating a new firm/workcentre this function checks is the multimple firm/workcentre creation is allowed.
    * @param type $val
    * @return boolean
    */
   function allow_multiple($val = '')
   {
      if (!$this->allow_multiple)
      {
         if ($this->firms->get_firms('', 1))
         {
            $this->form_validation->set_message('allow_multiple', 'Only a single firm is allowed.');
            return FALSE;
         }
      }
      return TRUE;
   }

   // Showing login page.
   //log the user out
   function my_logout($msg = '', $level = '')
   {
      $this->data['title'] = "Logout";


      // log the user out
      $logout = $this->ion_auth->logout();

      // Logout from firm.
      $this->session->set_userdata('firm_id', NULL);

      //Setting flash message.
      if (!$msg && !$level)
      {
         $msg = $this->ion_auth->messages();
         $level = 1; // Success
      }

      //Setting messages.
      $this->session->set_flashdata('message', $msg);
      $this->session->set_flashdata('message_level', $level);

      //redirect them to the login page
      redirect('index/login', 'refresh');
   }

   function isEdited($prev, $new)
   {
      foreach ($prev as $k1 => $v1)
      {
         if (isset($new[$k1]) && ($prev[$k1] != $new[$k1]))
            return TRUE;    // Edited
      }
      return FALSE; // Not edited.
   }

   /**
    * When editing anything, to check is there any thing to warn Admin through worklogs.
    * 
    * @param type $prev             :  Data before edit.
    * @param type $new              :  Data after edit.
    * @param array $warning_fields  :  Warning related to which fields.
    *                                   It is an array of Table fields (Eg:- array('ifc_rent', 'ifc_bata', 'ifc_loading'))
    * @return string
    */
   function check_warnings($prev, $new, $warning_fields)
   {
      foreach ($prev as $k1 => $v1)
      {
         if (in_array($k1, $warning_fields))
            if (isset($new[$k1]) && ($prev[$k1] != $new[$k1]))
               return WARNING;    // Edited            
      }
      return NORMAL; // Not edited.
   }

   /**
    * 
    * @param type $val_1
    * @param type $val_2
    * @param type $discard_If_Empty :    If its value is TRUE, The conditions like the following are considered as not edited.
    *                                     +---------------------------------+
    *                                     | $val_1      |        $val_2     |
    *                                     +---------------------------------+
    *                                     | 0           |      '0'          |
    *                                     | 0           |      NULL         |
    *                                     | 0           |      ''           |
    *                                     | 0           |      array()      |
    *                                     +---------------------------------+
    * 
    *                                     Important:-
    *                                     If its value is FALSE,  The all the above conditions cannot be considered as edited.
    * 
    * 
    * @return boolean :If edited return TRUE, else FALSE.
    * 
    * A logical error has found that, if $val_1='0.00' and $val_2='' and $discard_If_Empty = TRUE, Then it is showing as EDITED.
    * My expected result is NOT EDITED.
    */
//    function isEdited_2($val_1, $val_2, $discard_If_Empty = TRUE)
//    {
//        if (is_array($val_1))
//        {
//            foreach ($val_1 as $k1 => $v1)
//            {
//                if (isset($val_2[$k1]))
//                    if (!$this->is_similar($val_1[$k1], $val_2[$k1],$discard_If_Empty))
//                        return TRUE; // Edited.
//                        
//            }
//            return FALSE; // Not edited.
//        }
//        else if (!$this->is_similar($val_1, $val_2,$discard_If_Empty))
//            return TRUE; // Edited
//
//        return FALSE; // Not edited.
//    }

   /**
    * 
    * @param type $val_1
    * @param type $val_2
    * @param type $discard_If_Empty:  If its value is TRUE, 0 == '0' == '' == NULL etc.
    * @return boolean :If $val_1 == $val_2 return TRUE, else FALSE.
    * A logical error has found that, if $val_1='0.00' and $val_2='' and $discard_If_Empty = TRUE, Then it is showing as NOT SIMILAR.
    * My expected result is SIMILAR.
    */
//    function is_similar($val_1, $val_2, $discard_If_Empty = TRUE)
//    {
//        if (($val_1 != $val_2))
//        {
//            if ($discard_If_Empty)
//            {
//                if (empty($val_1) == empty($val_2))
//                    return TRUE;
//                else
//                    return FALSE;
//            }
//            else
//               return FALSE;    // Edited
//        }
//         return TRUE; // Values are same.
//    }

   function isAllowedTask($url)
   {
      $msg = taskEnabled($url);
      if ($msg != 1)/* index() function in a controller may be represented only by its class name. */
      {
         $level = 2; // Having errors.
         $this->my_logout($msg, $level);
         return false;
      }
      return;
   }

   /**
    * Function returns the user who created the worklog. It will be called from index() function of controllers.
    * @param type $tableData : Data taken from database to view page.
    * @param type $refTbl: Worklog ref-table
    * @param type $ref_key: Worklog ref-id
    * @return type
    */
   function getWlogUser($tableData, $refTbl = '', $ref_key = '')
   {
      $refTbl = $refTbl ? : $this->table;
      $ref_key = $ref_key? : $this->p_key;
      $wlog_details = array();
      foreach ($tableData as $row)
      {   // Getting worklog data
         $ref['wlog_ref_table'] = $refTbl;
         $ref['wlog_ref_id'] = $row[$ref_key];
         $wlog_details[$row[$this->p_key]] = $this->worklogs->getUser($this->user_id, $ref);
      }
      return $wlog_details;
   }

   /**
    * 
    * @param type $table :       Name of the database table in which the worklog is related to.
    * @param type $key:          Value of primary key of table.
    * @param type $wc_msg:       Message related to the worklog.
    * @param type $ref_action:   Action caused to the worklog.
    * @param type $wc_action:    Action caused to the worklog in the point of user's view. (Visit units/edit)
    *                                  For example;
    *                                  when editing units actually we are adding new batch of units.But in the user's view, 
    *                                  he is edited the units now. So here the value of $ref_action is 1 (ADD) and the value of 
    *                                  $wc_action is 2 (EDIT).
    * @param type $workcentres:  The id of workcentres in which worklog will be displayed under it. 
    *                             its value can be array of workcentres ids (Eg: $workcentres = array(1,2,3)) 
    *                             or a single single workcentre id (Eg: $workcentres = 2);
    *                             If you want the worklog should set as a General worklog, assign 0 to this varialbe.   
    * 
    * @param type $prev_dt:      If want to backup the previous data when edit/delete action occures, that data.
    * 
    * @param type $firms:        If the worklog is a general worklog, the id of firms in which worklog will be displayed under it.
    *                            (Eg: $firms = array(1,2,3));
    *                            In this case the value of $workcentres should be set as 0; 
    * 
    * @param type $wlog_warnings:     If any altrations made by user, that must be informed the ADMIN. (1=>Warning, 2=>Normal).
    *                                  There are two constants defined in 'config/settings.php' with possible values of this parmeter.
    *                                  WARNING => A constant, having value 1, says there is a warning to the admin in the worklog.
    *                                  NORMAL => A constant, having value 2, says there is nothing to warn ADMIN in the worklog,
    *                                            ie:- a 'NORMAL' worklog.
    * 
    */
   function send_wlog($table, $key, $wc_msg, $ref_action, $wc_action, $workcentres = '', $prev_dt = '', $firms = '', $wlog_warnings = NORMAL)
   {
      $wlog_wc = array();

      // If the worklogs done under workcentres.
      if ($workcentres)
      {
         // If $workcentre, the worklog will be displayed under all given workcentres, even whatever the firms are.
         // So value of $firms is not considerable.
         $firms = '';

         if (is_array($workcentres))
         {
            foreach ($workcentres as $wc)
            {
               $wlog_wc[$wc]['msg'] = $wc_msg;
               $wlog_wc[$wc]['action'] = $wc_action;
            }
         }
         else
         {
            $wlog_wc[$workcentres]['msg'] = $wc_msg;
            $wlog_wc[$workcentres]['action'] = $wc_action;
         }
      }

      // If a General worklog.
      else
      {
         // The worklog is a general worklog. So it won't be displayed under any workcentre.
         // Rather it will be displayed under the general section of the firms represented by $firms.
         $workcentres = 0;

         // If the Firm is not specified, the worklog should be displayed under all active firms.
         $firms = $firms ? implode(',', $firms) : implode(',', $this->firms->getIds(array('firm_status' => 1)));

         $wlog_wc[$workcentres]['msg'] = $wc_msg;
         $wlog_wc[$workcentres]['action'] = $wc_action;
      }

      // Adding Tbl:parties details to worklogs .
      $wlog_id = $this->add_logs($table, $key, get_url($table), get_popup_id($table), $wlog_wc, $ref_action, $firms, $wlog_warnings);


      // Backing up previous details for data recovery needs.
      if (to_be_backed_up($table) && $prev_dt) //If need to be backed up.
         $this->backups->backUpData($wlog_id, $prev_dt, $table, $key);
   }

   /**
    * 
    * @param type $ref_table : Table related to the worklog. Used to help verifier when he needs to view the details about the worklogs. 
     If no need to show the details when verifying leave it NULL;
    * @param type $ref_id  :   value of the Primary key of the table represented by $ref_table related to the worklog.
                                If no need to show the details when verifying leave it NULL;
    * @param type $ref_url :   URL to the page which will display the details about the worklog if the verifier wants to see it.
                                If for a worklog, there is nothing to display any details in a special page leave this field blank.
    * @param type $popup_id :  The value of the id attribute of the popup box which will display the details about the worklog.
                                If for a worklog, there is nothing to display in popup boxes leave this field blank.
    * @param type $wlog_wc :   The information to the workcentres where the worklog is done. 
    *                           It is an array as wcntr_id as key and  the worklog message and the current action made 
    *                           (according to the view of user) as values. (Visit units/edit). Format is following;
    * 
    *                               $wlog_wc[wcntr_id]['msg']    => Message to the workcentre
    *                               $wlog_wc[wcntr_id]['action'] => The action made in the point of user's view. For example;
    *                                                               when editing units actually we are adding new batch of units. 
    *                                                               But in the user's view, he is edited the units now. 
    *                                                               So here the value of $action is 1 (ADD) and the value of 
    *                                                               $wlog_wc[wcntr_id]['action'] is 2 (EDIT).
    * 
    *                            If the worklog is a general worklog, ie:- It is not done under any workcentres 
    *                            but common to all firms. it is called a general worklog. To send a genaral worklog to all firms 
    *                            replace the wcntr_id with 0. For eg:-
    * 
    *                               $wlog_wc[0]['msg']
    *                               $wlog_wc[0]['action']
    * 
    * @param type $action  :   What the action done now. Add/Edit/Delete.
    * @param type $firm_id :  The worklog shouldbe displayed under which firm, when verifying it.
                               If more than one firm, insert it as a comma seperated string of firm_ids. Eg: implode(',', $firm_ids);
    * @param type $wlog_warnings  :  If any altrations made by user, that must be informed the ADMIN. (1=>Warning, 2=>Normal).
    *                                  There are two constants defined in 'config/settings.php' with possible values of this parmeter.
    *                                  WARNING => A constant, having value 1, says there is a warning to the admin in the worklog.
    *                                  NORMAL => A constant, having value 2, says there is nothing to warn ADMIN in the worklog,
    *                                            ie:- a 'NORMAL' worklog.
    * @return boolean
    */
   function add_logs($ref_table, $ref_id, $ref_url, $popup_id, $wlog_wc, $action, $firm_id = '', $wlog_warnings = NORMAL)
   {
      $wlog_time = getSqlDateTime();
      $wlog['wlog_fk_auth_users'] = $this->user_id;
      $wlog['wlog_firms'] = !$firm_id ? $this->firm_id : $firm_id;
      $wlog['wlog_created'] = $wlog_time;
      $wlog['wlog_warnings'] = $wlog_warnings;
      $wlog['wlog_ref_table'] = $ref_table;
      $wlog['wlog_ref_id'] = $ref_id;
      $wlog['wlog_ref_url'] = $ref_url;
      $wlog['wlog_popup_id'] = $popup_id;
      $wlog['wlog_clsfunc'] = $this->clsfunc;
      $wlog['wlog_ref_action'] = $action;
      $wlog['wlog_status'] = 1;   // Default status

      if (!$wlog_wc)
      {
         // Getting all active Admins as verifiers.
         $verifiers[0] = $this->verify->getAllAdminVerifiers();
         $wlog_wc[0]['msg'] = "Logical error due to null ".'$wlog_wc'." when <b>'$this->clsfunc'</b>";
         $wlog_wc[0]['action'] = NULL;
      }
      
      // Getting verifiers emp_id in each workcentres.
      foreach ($wlog_wc as $wc_id => $arr)
      {
         // If the action is under any workcentre And after logged in to a frim.
         if ($wc_id && $this->firm_id)
         {
            // Employee category those are having power to verify. 
            $empcats_verify_power = $this->settings->getVerifiers($this->firm_id);

            // Getting emp_id of verifiers.
            $verifiers[$wc_id] = $this->verify->getVerifiersId($wc_id, $empcats_verify_power);
         }

         // If the action is not under any workcentre, ie:- A general action.
         // So it is need to be show only in front of Admins.
         else
         {
            // Getting all active Admins as verifiers.
            $verifiers[$wc_id] = $this->verify->getAllAdminVerifiers();
         }
      }



      // is current user's worklog as "Verified/Nonverified" to him.
      // If action is done after logged in to a firm.
      if ($this->firm_id)
      {
         $MY_WORKLOG = $this->settings->getFirmSettings('MY_WORKLOG', $this->firm_id);
         //echo "<br>if firm Myworklog =    $MY_WORKLOG";
      }

      // If the action is done before logged in to a firm. Eg: adding a firm.
      else
      {
         $MY_WORKLOG = $this->settings->getDefaultValue('MY_WORKLOG');
         //echo "<br>if no firm Myworklog =    $MY_WORKLOG";
      }

      // Adding worklogs and related things to Database.
      $wlog_id = $this->worklogs->add_logs($wlog, $wlog_wc, $verifiers, $MY_WORKLOG, $this->user_id, $wlog_time);
      
      if (!$wlog_id)
      {
         $msg = "Worklog couldn't be added perfectly!";
         $level = 2; // Having errors.
         $this->my_logout($msg, $level);
         return false;
      }

      return $wlog_id;
   }

   function get_BS_Categories($cat_type)
   {
      // Format $cat['assets/liabilities']['Main Category'] = array('Sub category 1','Sub category 2','...','...','Sub category n');
      // You can add new 'Main Category/Sub category' at any time. It will not be affect our code.
      // You don't edit any 'Main Category/Sub category' later. Because it is being using in several controllers/models.
      // There should not be matching values in Sub_Categories of $cat['liabilities'] with Sub_Categories of $cat['assets']
      // Liabilities:
      $cat['liabilities']['Capital'] = array('Capital');
      $cat['liabilities']['Loan'] = array('Loan');
      $cat['liabilities']['Current Liability'] = array('Creditors', 'Rent Payable', 'Salary Payable');

      // Assets:
      $cat['assets']['Deposits'] = array('Deposits');
      $cat['assets']['Fixed Assets'] = array('Land Buildings', 'Furnitures', 'Equipments', 'Electricals');
      $cat['assets']['Current Assets'] = array('Stock in Hand', 'Debtors', 'Cash in Hand', 'Cash at Bank');

      return $cat[$cat_type];
   }

   function get_PL_Categories()
   {
      // Format $cat['debts/credits']['Main Category'] = array('Sub category 1','Sub category 2','...','...','Sub category n');
      // You can add new 'Main Category/Sub category' at any time. It will not be affect our code.
      // You don't edit any 'Main Category/Sub category' later. Because it is being using in several controllers/models.
      // There should not be matching values in Sub_Categories of $cat['debts'] with Sub_Categories of $cat['credits']
      // Debts
      $cat['debts']['Opening Stock'] = array('Opening Stock');



      // Credits
      $cat['credits']['Clossing Stock'] = array('Clossing Stock');

      return $cat;
   }

   function getAllReports($workcentres, $from, $to)
   {
      $reports = $this->workcentres->reports($workcentres, $from, $to);
      $reports = array_merge($reports, $this->employee_work_centre->reports($workcentres, $from, $to));
      $reports = array_merge($reports, $this->rental_details->reports($workcentres, $from, $to));
      $reports = array_merge($reports, $this->vehicle_workcentres->reports($workcentres, $from, $to));
      $reports = array_merge($reports, $this->destination_workcentres->reports($workcentres, $from, $to));

      //Sorting $reports by 'DATE' then 'WORKCENTRE' then 'DESCRIPTION':
      $date = array();
      $wcntr = array();
      $desc = array();
      foreach ($reports as $key => $row)
      {
         $date[$key] = $row['DATE'];
         $wcntr[$key] = $row['WORKCENTRE'];
         $desc[$key] = $row['DESCRIPTION'];
      }
      array_multisort($date, SORT_ASC, $wcntr, SORT_ASC, $desc, SORT_ASC, $reports);

      return $reports;
   }

   function getBalanceSheet($workcentres, $from, $to)
   {
      $reports = $this->getAllReports($workcentres, $from, $to);
      $liabilities = $this->get_BS_Categories('liabilities');
      $assets = $this->get_BS_Categories('assets');

      $balanceSheet['liabilities'] = array();
      $balanceSheet['assets'] = array();

      // Getting Liabilities
      if ($reports)
         foreach ($liabilities as $mainCat => $subCat)
            foreach ($subCat as $cat)
               foreach ($reports as $row)
                  if (isset($row['BS']) && ($row['BS'] == $cat))
                     $balanceSheet['liabilities'][$mainCat][$cat][] = $row;
      // getting Assets.                
      if ($reports)
         foreach ($assets as $mainCat => $subCat)
            foreach ($subCat as $cat)
               foreach ($reports as $row)
                  if (isset($row['BS']) && ($row['BS'] == $cat))
                     $balanceSheet['assets'][$mainCat][$cat][] = $row;


      // Including  'Cash in Hand' to Balance Sheet;
      $cashInHand = $this->getCashInHand($workcentres, $from, $to, 'total');
      if (!isset($balanceSheet['assets']['Current Assets']['Cash in Hand'][0]))
         $balanceSheet['assets']['Current Assets']['Cash in Hand'][0] = array('ACC_TYPE' => 2, 'AMOUNT' => $cashInHand);
      else
         echo "<font color='red'><b>Error !!!</b><br>Cash in Hand Already Exists.</font>";
      return $balanceSheet;
   }

   function getCashInHand($workcentres, $from, $to, $return = 'total')
   {
      $reports = $this->getAllReports($workcentres, $from, $to);

      //$categories = $this->get_BS_Categories();
//        $liabilities = $this->get_BS_Categories('liabilities');
//        $assets = $this->get_BS_Categories('assets');
//        $balanceSheet['liabilities'] = array();
//        $balanceSheet['assets'] = array();
      //$balanceSheet = array();

      if ($reports)
      {
         if ($return == 'total')
         {
            $credits = 0;
            $debts = 0;
            foreach ($reports as $val)
            {
               if ($val['ACC_TYPE'] == 1)
                  $credits = bcadd("$credits", "$val[AMOUNT]", 2);
               if ($val['ACC_TYPE'] == 2)
                  $debts = bcadd("$debts", "$val[AMOUNT]", 2);
            }
            $cashInHand = bcsub("$credits", "$debts", 2);
            return $cashInHand;
         }
         else if ($return == 'detailed')
         {
            $credits = array();
            $debts = array();
            foreach ($reports as $val)
            {
               if ($val['ACC_TYPE'] == 1)
                  $credits[] = $val;
               if ($val['ACC_TYPE'] == 2)
                  $debts[] = $val;
            }
            $cashInHand = array("credits" => $credits, "debts" => $debts);
            return $cashInHand;
         }
      }
   }

   /**
    * 
    * @param type $p_key: Name of the primary key field related to ref_id.
    * @param type $model: Corresponding model object related to the ref_table.
    * @param type $from: Data collected from which tables.
    *                      Eg: $from = 'item_category,item_heads,items';
    * @param type $where: conditions for where clause. 
    *                      Condition for primary key matching of reference table is not need to be passed. 
    *                      Because it has been coded inside the function.
    *                      Eg: $where[] = "itm_fk_item_head = itmhd_id";
    *                          $where[] = "itmcat_id = itmhd_fk_item_category";
    * @return type
    */
   function init_wlog($p_key, $model, $from = '', $where = array())
   {
      $wcntr_id = $_POST['wcntr_id'];
      $ref_id = $_POST['ref_id'];
      $ref_table = $_POST['ref_table'];

      $from = $from ? : $ref_table;
      $select = getWlogFields($ref_table, 'keys');
      // $where = "$p_key = $ref_id";
      // Matching primary key of reference table.
      $where[] = "$p_key = $ref_id";
      $where = implode(' AND ', $where);

      $wlogs[] = $model->get_row2($from, $select, $where);
      $backups = $this->backups->getUsersBackupsByRef($ref_id, $ref_table, $this->user_id, $wcntr_id);
      if ($backups)
      {
         $wlogs = array_merge($wlogs, $backups);
      }
      return $wlogs;
   }

}

?>