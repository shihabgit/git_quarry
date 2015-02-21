<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require 'my_controller.php';

class User_tasks extends My_controller
{

    function __construct()
    {
        parent::__construct();

        $this->load->model('user_tasks_model', 'user_tasks');
        $this->load->model('tasks_model', 'tasks');
        $this->load->model('employee_work_centre_model', 'employee_work_centre');

// Determining is the logged in user allowed to go forward with the current action.
        $this->isAllowed();
        $this->table = 'user_tasks';
        $this->p_key = 'utsk_id';
    }

    // Called by Ajax
    function getEmployeeTaskAjax()
    {
        $emp_id = $this->input->post('emp_id');
        if ($tasks = $this->getEmployeeTask($emp_id))
            echo $tasks;
    }

    function getEmployeeTask($emp_id)
    {
        $tasks = $this->user_tasks->get_menu($emp_id);
//        $collapse = 'site_url + "images/listUp.png"';
//        $expand = 'site_url + "images/listDown.png"';
        // Opening scrip.
        $script = '<script type="text/javascript">';

        // Setting default status of menu as collapsed.
        $script .= "$('ul.task li ul').slideUp(1);";

        // On clicking img, collapse/expand the menu.
        $script .= "$('.li_img').click(function(){";
        $script .= "$(this).closest('li').children('.li_img').toggle();";
        $script .= "$(this).closest('li').children('ul').slideToggle(500);";
        $script .= '});';

        // Clossing script.
        $script .= '</script>';

        if ($tasks)
            return $tasks . $script;
        return '';
    }

    function add()
    {   // Checking is the current task is enabled for the user
        $this->isAllowedTask($this->clsfunc);

//	Validating 
        $v_config = validationConfigs('user_tasks');
        $this->form_validation->set_rules($v_config);
        $this->form_validation->set_error_delimiters('<br>', '');
        if (!$this->form_validation->run())
        {
            $data['title'] = 'Add Tasks';
            $data['heading'] = 'ADD TASKS';

            // All employee categories except Admin. Admin must have all tasks. It should not be changed.
            $data['emp_cats'] = $this->employees->get_employee_category($this->user_cat, '', FALSE);

            //workcentres where employee is available.
            $search['workcentres'] = $this->employee_work_centre->getEmployeesWorkcentres($this->user_id,$this->firm_id);
            $search['emp_category'] = ifSet('emp_category');
            $data['employees'] = $search['emp_category']?$this->employee_work_centre->get_workcentres_employees_option($search):array();

            $data['menu'] = $this->getEmployeeTask(ifSet('utsk_fk_auth_users'));
            if ($_POST)
            {
                $data['message'] = 'Some Errors Occured !' . validation_errors();
                $data['message_level'] = 2;
            }

            $this->_render_page($this->clsfunc, $data);
            return;
        }

        // Recieving input 
        $input = $this->get_inputs();

        // Getting previous tasks of the employee
        $previous_tasks = $this->user_tasks->getEmployeeTasks($input['utsk_fk_auth_users']);
        $new_tasks = array_diff($input['utsk_fk_tasks'], $previous_tasks);
        $deleted_tasks = array_diff($previous_tasks, $input['utsk_fk_tasks']); 
        
        // workcentres where employee is available.
        $employee_workcentres = $this->employee_work_centre->getEmployeesWorkcentres($input['utsk_fk_auth_users']);
        
        // Details of employee
        $employee_dtls = $this->employees->getById($input['utsk_fk_auth_users']);
        $emp_cats = $this->employees->get_employee_category();
        $emp_category = $employee_dtls['emp_category'];    
        $tbl_data['utsk_fk_auth_users'] = $input['utsk_fk_auth_users'];
        
        
        // Deleting all deleted tasks from previous tasks.
        foreach ($deleted_tasks as $task_id)
        {
            $tbl_data['utsk_fk_tasks'] = $task_id;
            $this->user_tasks->deleteUsersTasks($tbl_data);
            #---------      Setting details for worklogs. -------------#
            $wlog_wc = array();
            foreach ($employee_workcentres as $wcntre_id)
            {
                // Details of Task
                $task_dtls = $this->tasks->getById($task_id);
                $task = '<span class="wlg_name">' . $task_dtls['tsk_description'] . '</span>';
                $employee = '<span class="wlg_name">' . $employee_dtls['emp_name'] . '</span>';
                $msg = "The task $task had been resigned to $emp_cats[$emp_category] $employee.";
                $wlog_wc[$wcntre_id]['msg'] = $msg;
                $wlog_wc[$wcntre_id]['action'] = $this->delete;
            }

            // Adding to worklogs.
            $this->add_logs($this->table, $task_id, get_url($this->table), get_popup_id($this->table), $wlog_wc, $this->delete);
        }

        // Adding new tasks.   
        foreach ($new_tasks as $task_id)
        {
            $tbl_data['utsk_fk_tasks'] = $task_id;
            $insert_id = $this->user_tasks->insert($tbl_data);
            #---------      Setting details for worklogs. -------------#
            $wlog_wc = array();
            foreach ($employee_workcentres as $wcntre_id)
            {
                // Details of Task
                $task_dtls = $this->tasks->getById($task_id);
                $task = '<span class="wlg_name">' . $task_dtls['tsk_description'] . '</span>';
                $employee = '<span class="wlg_name">' . $employee_dtls['emp_name'] . '</span>';
                $msg = "A new task $task has been assigned to $emp_cats[$emp_category] $employee";
                $wlog_wc[$wcntre_id]['msg'] = $msg;
                $wlog_wc[$wcntre_id]['action'] = $this->add;
            }

            // Adding to worklogs.
            $this->add_logs($this->table, $insert_id, get_url($this->table), get_popup_id($this->table), $wlog_wc, $this->add);
        }

        //redirecting
        $this->session->set_flashdata('message', 'Tasks added successfully');
        $this->session->set_flashdata('message_level', 1); // Success        
        redirect("user_tasks/add", 'refresh');
    }

    function hasTask($str)
    {
        // Returns if no employees are selected.
        if (!$this->input->post('utsk_fk_auth_users'))
            return TRUE;

        // Success
        if ($this->input->post('utsk_fk_tasks'))
            return TRUE;
        // Failure
        else
        {
            $this->form_validation->set_message('hasTask', 'Select atleast one task.');
            return FALSE;
        }
    }

}
