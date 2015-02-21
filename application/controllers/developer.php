<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require 'my_controller.php';

class Developer extends My_controller
{
    function __construct()
    {
        parent::__construct();

        $this->load->model('tasks_model', 'tasks');
        $this->load->model('User_tasks_model', 'user_tasks');

        // Determining is the logged in user allowed to go forward with the current action.
        $this->isAllowed();
    }

    function index()
    {   // Checking is the current task is enabled for the user
        $this->isAllowedTask($this->cls);

        $data['check'][] = "Is all settings are done for all firms?";
        $data['check'][] = "Is admin have been put in all workcentres?";
        $data['check'][] = "Is admin has set all tasks?";
        $data['check'][] = "Is partner has set all tasks?";
        
        $data['after_set'] = $this->my_model->get_form_inputs($this->cls.'/add_all'); // my_model is the parent model loaded by config/autoload.php.
        $data['title'] = 'Developer';
        $data['heading'] = 'Developer Tools';
        $data['menu'] = $this->tasks->get_menu();
        $this->_render_page($this->clsfunc, $data);
    }

    function add_all()
    {
        // Adding all default settings to all firms if it has not added. 
        $firm_settings_flag = $this->add_firm_settings();

        // Adding all admins in all workcentres.
        $add_to_workcentre_flag = $this->add_admins_to_workcentres();
        
        // Adding all tasks to all admins
        $admin_task_flag = $this->add_admin_tasks();
        
        // Adding all tasks to all Partners
        $partner_task_flag = $this->add_partner_tasks();
        
        $data['firm_settings'] = $firm_settings_flag ? "Firm settings added successfully." : "Firm settings couldn\'t add successfully.";
        $data['emp_wrkcntr'] = $add_to_workcentre_flag ? "All Admins are added to all workcentres successfully." : "All Admins are not added to all workcentres successfully.";
        $data['admin_tasks'] = $admin_task_flag ? "All Tasks are added to all Admins successfully." : "All Tasks are not added to all Admins successfully.";
        $data['partner_tasks'] = $partner_task_flag ? "All Tasks are added to all Partners successfully." : "All Tasks are not added to all Partners successfully.";
        
         $this->my_model->set_form_inputs($this->clsfunc, $data);
        
        //redirect 
        redirect('developer', 'refresh');
    }

    /**
     * Function to add all tasks to all Admin
     * @return boolean
     */
    function add_admin_tasks()
    {
        // Getting all Admins (active && inactive).
        $admins = $this->employees->getIds(array('emp_category' => 1));
        $this->load->model('tasks_model', 'tasks');
        $this->load->model('user_tasks_model', 'user_tasks');
        
        // Getting All Tasks
        $tasks = $this->tasks->getIds();

        $admin_task_flag = TRUE;
        
        // Adding each tasks for admin.
        foreach ($admins as $emp_id)
        {
            foreach ($tasks as $tsk_id)
            {
                $data = array('utsk_fk_auth_users' => $emp_id, 'utsk_fk_tasks' => $tsk_id);
                if (!$this->user_tasks->is_exists($data))
                    if (!$this->user_tasks->insert($data))
                        $admin_task_flag = FALSE;
            }
        }
        
        return $admin_task_flag;
    }

    /**
     * Function to add all tasks to all Partners
     * @return boolean
     */
    function add_partner_tasks()
    {
        // Getting all Partners (active && inactive).
        $partners = $this->employees->getIds(array('emp_category' => 2));
        $this->load->model('tasks_model', 'tasks');
        $this->load->model('user_tasks_model', 'user_tasks');
        
        // Getting All Tasks
        $tasks = $this->tasks->getIds();

        $partner_task_flag = TRUE;
        
        // Adding each tasks for each partner.
        foreach ($partners as $emp_id)
        {
            foreach ($tasks as $tsk_id)
            {
                $data = array('utsk_fk_auth_users' => $emp_id, 'utsk_fk_tasks' => $tsk_id);
                if (!$this->user_tasks->is_exists($data))
                    if (!$this->user_tasks->insert($data))
                        $partner_task_flag = FALSE;
            }
        }
        
        return $partner_task_flag;
    }

    /**
     * Function to add all admins in all workcentres.
     * @return boolean
     */
    function add_admins_to_workcentres()
    {

        $this->load->model('employee_work_centre_model', 'employee_work_centre');

        // Getting all workcentres (active & inactive).
        $workcentres = $this->workcentres->getIds();

        // Getting all Admins (active && inactive).
        $admins = $this->employees->getIds(array('emp_category' => 1));

        $add_to_workcentre_flag = true;
        foreach ($admins as $id)
        {
            foreach ($workcentres as $wcntre_id)
            {
                $table_data = array();
                $table_data['ewp_fk_auth_users'] = $id;
                $table_data['ewp_fk_workcentres'] = $wcntre_id;
                if (!$this->employee_work_centre->is_exists($table_data))
                // Adding employee to workcentres
                    if (!$this->employee_work_centre->insert($table_data))
                        $add_to_workcentre_flag = false;
            }
        }

        return $add_to_workcentre_flag;
    }

    /**
     * Function to add all default settings to all firms if it has not added. 
     * @return boolean
     */
    function add_firm_settings()
    {
        // Getting all firms
        $firms = $this->firms->get_firms_options();

        $this->load->model('settings_model', 'settings');
        $this->load->model('firm_settings_model', 'firm_settings');

        $firm_settings_flag = TRUE;

        // Getting all default settings.
        $settings = $this->settings->get_data();

        if ($firms && $settings)
        {
            foreach ($firms as $id => $frm)
            {
                foreach ($settings as $row)
                {
                    $unique_firm_settings = array();
                    $unique_firm_settings['frmset_fk_settings'] = $row['set_id'];
                    $unique_firm_settings['frmset_fk_firms'] = $id;
                    if (!$this->firm_settings->is_exists($unique_firm_settings))
                    {
                        $firm_settings = $unique_firm_settings;
                        $firm_settings['frmset_value'] = $row['set_default_value'];
                        if (!$this->firm_settings->insert($firm_settings))
                            $firm_settings_flag = false;
                        $firm_settings = array();
                    }
                }
            }
        }

        return $firm_settings_flag;
    }

    function createTable()
    {
        $data['title'] = 'Developer';
        $data['heading'] = 'Developer Tools';
        $data['menu'] = $this->tasks->get_menu();
        $this->_render_page($this->clsfunc, $data);
        return;
    }

    function create()
    {
        createTables();
    }

    // Called by Ajax
    function add()
    {

        //Inputs : tsk_parent tsk_name tsk_url tsk_pos common show
        // Validating
        $v_config = validationConfigs('tasks');
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

        // Url of parent task must be '#'.
        if ($input['tsk_parent'])
            $this->tasks->save(array('tsk_url' => '#'), $input['tsk_parent']);
        else
            unset($input['tsk_parent']);

        $input['tsk_display'] = ($input['show'] == 'true') ? 1 : 2;
        $input['tsk_status'] = ($input['common'] == 'true') ? 1 : 2;
        $input['tsk_description'] = ucwords(strtolower($input['tsk_description']));

        if ($tsk_id = $this->tasks->insert($input))
        {    // Getting all Admins and partners (active && inactive).
            $admins = $this->employees->getIds('emp_category = 1 OR emp_category = 2');

            // Adding tasks for each admin/partner to Tbl:user_tasks. 
            foreach ($admins as $id)
            {
                $data = array('utsk_fk_auth_users' => $id, 'utsk_fk_tasks' => $tsk_id);
                if (!$this->user_tasks->is_exists($data))
                    $this->user_tasks->insert($data);
                else
                {
                    $msg = "Error: User task already added.";
                    $level = 2; // Having errors.
                    $this->my_logout($msg, $level);
                }
            }
            echo 1;
        }
        else
            echo '<div class="failureHead"><img src="images/failure.gif" width="15" height="15" /> Error!!!<div class="pop_failure">Data couldn\'t be insert !</div></div>';
    }

    //Call back
    function checkpos($pos)
    {   //tsk_parent tsk_name tsk_url tsk_pos common show
        $parent = $this->input->post('tsk_parent');
        if ($parent)
        {
            if ($this->tasks->is_exists(array('tsk_parent' => $parent, 'tsk_pos' => $pos)))
            {
                $this->form_validation->set_message('checkpos', 'Position already reserved by another task.');
                return FALSE;
            }
        }
        else
        {
            if ($this->tasks->is_exists("tsk_parent IS NULL AND  tsk_pos = $pos"))
            {
                $this->form_validation->set_message('checkpos', 'Position already reserved by another task.');
                return FALSE;
            }
        }
        return TRUE;
    }

    // Callback
    function checkParent($parent)
    {
        if ($parent && !$this->tasks->is_exists(array('tsk_id' => $parent)))
        {
            $this->form_validation->set_message('checkParent', 'Parent Id not found.');
            return FALSE;
        }
        return TRUE;
    }

}
