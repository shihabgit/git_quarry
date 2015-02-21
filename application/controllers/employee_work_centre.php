<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require 'my_controller.php';

class Employee_work_centre extends My_controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('employee_work_centre_model', 'employee_work_centre', true);

        // Determining is the logged in user allowed to go forward with the current action.
        $this->isAllowed();

        $this->table = 'employee_work_centre';
        $this->p_key = 'ewp_id';
    }
    
    function add()
    {
        redirect("employees", 'refresh');
    }

    
    
    function get_wlog()//Called by ajax
    {   $model = $this->employee_work_centre;
        $wlogs = $this->init_wlog($this->p_key,$model);

        if ($wlogs[0])
        {
            $latest_class = 'wlog_latest'; // Latest details about the worklog. Ie: the data from ref_tables, not from Tbl:backups.
            $wlog_fields = getWlogFields($this->table, 'keys');
            
            $ob_mode = array(1=>'Cr',2=>'Dr'); 
            $status = $model->get_status();
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
            
                    if ($fld == 'ewp_status')
                        $val = $status[$row[$fld]];
                    else if ($fld == 'ewp_ob_mode')
                        $val = $ob_mode[$row[$fld]];
                    
                    
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
    
    function index()
    {
        // Checking is the current task is enabled for the user
        $this->isAllowedTask($this->cls);

        // Receiving input
        $input = $this->get_pagination_inputs($this->employee_work_centre);

        //Set the flash data message if there is one has set before redirected to this page.
        $data['message'] = $this->session->flashdata('message');
        $data['message_level'] = $this->session->flashdata('message_level');
        $data['offset'] = $input['offset'];
        $data['title'] = "Availability";
        $data['heading'] = "Employees in Workcentres";
        $data['status'] = $this->employee_work_centre->get_employee_status();
        $data['emp_cats'] = $this->employees->get_employee_category($this->user_cat);
        $this->per_page = $_POST ? $input['PER_PAGE'] : $this->per_page;
        $data['workcentres'] = $this->workcentres->get_workcentres_options($this->user_id, $this->firm_id, 1);

        // Setting default search options.
        if (!$_POST)
        {
            $input['ewp_status'] = 1; //Default status is Active   
        }

        //If reffered from Worklogs;
        $wlog_ref_id = ($this->uri->segment(3) == 'wlogs') ? $this->uri->segment(4) : '';

        $data['table'] = $this->employee_work_centre->index($input, $this->user_cat, $data['workcentres'], $wlog_ref_id);
        $data['num_rows'] = $this->employee_work_centre->index($input, $this->user_cat, $data['workcentres'], $wlog_ref_id, true);

        
        // Initializing pagination
        $data = array_merge($data, $this->initPagination($data['table'], $data['num_rows'],$input['offset']));


        // After validations
        $data = array_merge($data, $this->validateIndex());

        $this->_render_page($this->clsfunc, $data);
    }

    function validateIndex()
    {
        $config[] = array('f_ewp_date', 'From Date', 'callback_compare_dates[' . $this->input->post('t_ewp_date') . ']');
        $data = $this->checkValidations($config);
        return $data;
    }

}

?>