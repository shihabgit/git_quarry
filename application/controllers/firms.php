<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require 'my_controller.php';

class Firms extends My_controller
{

    function __construct()
    {
        parent::__construct();

        //$this->load->model('firms_model', 'firms', true);

        $this->load->model('employee_work_centre_model', 'employee_work_centre');
    }

    function get_users_active_firms()
    {
        $data = $this->firms->get_firms_options($this->user_id, 1);
        $json[] = array('value' => '', 'text' => 'Select');
        foreach ($data as $key => $val)
            $json[] = array('value' => $key, 'text' => $val);
        echo json_encode($json);
    }

    function saveName()
    {   // Checking is the current task is enabled for the user
        $this->isAllowedTask($this->cls . '/edit');
        $firm_id = $this->input->post('firm_id');
        $details = $this->firms->getById($firm_id);
        $input['firm_name'] = $this->input->post('firm_name');

        // Capitalizing each first leter in the firm name
        $input['firm_name'] = ucwords(strtolower($input['firm_name']));
        if (!$this->firms->is_exists(array('firm_name' => $input['firm_name']), $firm_id))
        {
            if ($this->firms->save($input, $firm_id))
            {
                echo 1;
                // Adding firm reports to worklogs.
                $wlog_workcentre = 0; // The worklog is not under any workcentre, It is a General worklog.
                $wlog_firms = implode(',', $this->firms->getIds(array('firm_status' => 1))); // Worklog should be displayed in all active firms.
                $wlog_wc[$wlog_workcentre]['msg'] = 'The name of the firm <span class="wlg_name">' . $details['firm_name'] . '</span> has been changed to <span class="wlg_name">' . $input['firm_name'] . '</span>.';
                $wlog_wc[$wlog_workcentre]['action'] = $this->edit;
                $this->add_logs('firms', $firm_id, get_url('firms'), get_popup_id('firms'), $wlog_wc, $this->edit, $wlog_firms);
            }
        }
        else
            echo "Firm name already exists.";
    }

    function toggleStatus()
    {
        // Checking is the current task is enabled for the user
        $this->isAllowedTask($this->cls . '/edit');

        $firm_id = $this->input->post('firm_id');
        $details = $this->firms->getById($firm_id);
        $this->firms->toggleTableStatus($firm_id);
        $current_status = $this->firms->getTableStatus($firm_id);
        echo $current_status;

        // Making the staus of all workcentres as same as the status of its firm.
        $this->workcentres->update_where(array('wcntr_status' => $current_status), "wcntr_fk_firms = $firm_id");

        // Adding firm reports to worklogs.
        $msg = ($current_status == 1) ? "Activated" : "Deactivated";
        $wlog_workcentre = 0; // The worklog is not under any workcentre, It is a General worklog.
        $wlog_firms = implode(',', $this->firms->getIds(array('firm_status' => 1))); // Worklog should be displayed in all active firms.
        $wlog_wc[$wlog_workcentre]['msg'] = 'The firm <span class="wlg_name">' . $details['firm_name'] . '</span> and all of its workcentres have been ' . $msg . '.';
        $wlog_wc[$wlog_workcentre]['action'] = $this->edit;
        $this->add_logs('firms', $firm_id, get_url('firms'), get_popup_id('firms'), $wlog_wc, $this->edit, $wlog_firms);
    }

    function add()
    {
        // When adding a firm you must logged out if you have logged in to any firm previously. Other wise Worklog won't work expectedly.
        // Logout from firm if already logged in.
        $this->session->set_userdata('firm_id', NULL);

        // Checking is the current task is enabled for the user
        $task = taskEnabled($this->clsfunc);
        if ($task != 1)
        {
            echo $task;
            return;
        }

        // Redirecting to login page (after logout) if the new workcentre creation is not allowed.
        if (!$this->allow_multiple())
        {
            echo "Multiple firms are not allowed!";
            return;
        }

        //	Validating 
        $v_config = validationConfigs('firms');
        $this->form_validation->set_rules($v_config);
        $this->form_validation->set_error_delimiters('<div class="pop_failure">', '</div>');
        if (!$this->form_validation->run())
        {
            $message = validation_errors();
            if ($message)
            {
                echo '<div class="failureHead"><img src="images/failure.gif" width="15" height="15" /> Error!!!' . $message . '</div>';
                return;
            }
        }

        // Recieving input 
        $input = $this->get_inputs();

        // Setting default vales to other fields.
        $input['firm_date'] = getSqlDate(); // Setting default date as current date.
        $input['firm_status'] = 1;  // Setting default status as active.
        // Capitalizing each first leter in the firm name
        $input['firm_name'] = ucwords(strtolower($input['firm_name']));

        // Assuming the insertion is failed by default.
        $success = FALSE;

        // Inserting data to Tbl:firms
        if ($firm_id = $this->firms->insert($input))
        {
            // Settings has been inserted successfully.
            $success = TRUE;

            // if inserted successfully, applying the default settings to the firm.
            $this->load->model('settings_model', 'settings');
            $this->load->model('firm_settings_model', 'firm_settings');
            $settings = $this->settings->get_data();

            foreach ($settings as $row)
            {
                $firm_settings['frmset_fk_settings'] = $row['set_id'];
                $firm_settings['frmset_fk_firms'] = $firm_id;
                $firm_settings['frmset_value'] = $row['set_default_value'];
                if (!$this->firm_settings->insert($firm_settings))
                    $success = FALSE; // insertion failed.
            }

            // Adding firm reports to worklogs.
            $wlog_workcentre = 0; // The worklog in not under any workcentre, It is a General worklog.
            $wlog_firms = implode(',', $this->firms->getIds(array('firm_status' => 1))); // Worklog should be displayed in all active firms.
            $wlog_wc[$wlog_workcentre]['msg'] = 'A new firm <span class="wlg_name">' . $input['firm_name'] . '</span> and it\'s default settings were added.';
            $wlog_wc[$wlog_workcentre]['action'] = $this->add;
            $this->add_logs('firms', $firm_id, get_url('firms'), get_popup_id('firms'), $wlog_wc, $this->add, $wlog_firms);

            // Adding a default workcentre under the firm.
            $wcntr['wcntr_fk_firms'] = $firm_id;
            $wcntr['wcntr_date'] = $input['firm_date'];
            $wcntr['wcntr_ownership'] = 1;
            $wcntr['wcntr_name'] = 'Default Workcentre';
            $wcntr['wcntr_bill_name'] = 'Default Workcentre';
            $wcntr['wcntr_status'] = 1;

            // If workcentre created succesffully.
            if ($wcntr_id = $this->workcentres->insert($wcntr))
            {
                // Adding workcentre reports to worklogs.
                $wlog_workcentre = 0; // The worklog in not under any workcentre, It is a General worklog.
                $wlog_firms = $firm_id; // Worklog should be displayed only under the firm representred by $firm_id.
                $wlog_wc = array();
                $wlog_wc[$wlog_workcentre]['msg'] = 'A new workcentre <span class="wlg_name">' . $wcntr['wcntr_name'] . '</span> has been added under the firm: ' . $input['firm_name'] . '.';
                $wlog_wc[$wlog_workcentre]['action'] = $this->add;
                $this->add_logs('workcentres', $wcntr_id, get_url('workcentres'), get_popup_id('workcentres'), $wlog_wc, $this->add, $wlog_firms);

                #--------- Putting all admins to the workcentre. ---------------#
                $emp_wc['ewp_fk_workcentres'] = $wcntr_id;
                $emp_wc['ewp_date'] = $wcntr['wcntr_date'];
                $emp_wc['ewp_ob'] = '0.00';
                $emp_wc['ewp_day_wage'] = '0.00';
                $emp_wc['ewp_day_hourly_wage'] = '0.00';
                $emp_wc['ewp_day_ot_wage'] = '0.00';
                $emp_wc['ewp_night_wage'] = '0.00';
                $emp_wc['ewp_night_hourly_wage'] = '0.00';
                $emp_wc['ewp_night_ot_wage'] = '0.00';
                $emp_wc['ewp_salary_wage'] = '0.00';
                $emp_wc['ewp_status'] = 1;    // Active.
                // Getting all Admins (active && inactive).
                $admins = $this->employees->getAllEmployees(TRUE, array('emp_category' => 1));

                // Adding each admin to
                foreach ($admins as $id => $name)
                {
                    $emp_wc['ewp_fk_auth_users'] = $id;
                    $ewp_id = $this->employee_work_centre->insert($emp_wc);

                    // Adding 'employees in workcentres' reports to worklogs.
                    $wlog_wc = array();
                    $wlog_wc[$wcntr_id]['msg'] = 'An existing Admin <span class="wlg_name">' . $name . '</span> has been added to the workcentre when created the firm: ' . $input['firm_name'] . '.';
                    $wlog_wc[$wcntr_id]['action'] = $this->add;
                    //$this->add_logs('employee_work_centre', $ewp_id, get_url('employee_work_centre'), get_popup_id('employee_work_centre'), $wlog_wc, $this->add, $firm_id);
                }
            }
        }



        if ($success)
            echo 1;
        else
            echo '<div class="failureHead"><img src="images/failure.gif" width="15" height="15" /> Error!!!<div class="pop_failure">Data couldn\'t be insert !</div></div>';
    }

    //Selecting current firm.
    function login()
    {
        // Checking is the current task is enabled for the user
        $this->isAllowedTask($this->clsfunc);

        // The user must be logged in.
        if (!$this->ion_auth->logged_in())
        {
            //redirect them to the login page.
            $this->session->set_flashdata('message', "Permission Error :- Please Login!");
            $this->session->set_flashdata('message_level', 2); // Error
            redirect('index/login', 'refresh');
            return;
        }

        //Checking is user active.
        if (!$this->employees->is_active($this->user_id))
        {
            $msg = "Permission Error :- Inactive user";
            $level = 2; // Having errors.
            $this->my_logout($msg, $level);
            return false;
        }

        //validate form input
        $this->form_validation->set_rules('firm_id', 'Firm', 'required');

        if (!$this->form_validation->run())
        {
            // Logout from firm if already logged in.
            $this->session->set_userdata('firm_id', NULL);

            $this->data['title'] = "Select Firm";
            $this->load->model('firms_model', 'firms', true);

            // Firms related to the user.
            $this->data['firms'] = $this->firms->get_firms_options($this->user_id, 1);

            // Determining is the new firm creation is allowed.
            $this->data['is_allowed'] = $this->allow_multiple();

            //set the flash data error message if there is one
            $this->data['message'] = $this->session->flashdata('message');
            $this->data['message_level'] = $this->session->flashdata('message_level');
            $this->load->view($this->clsfunc, $this->data);
            return;
        }

        // Saving current firm in session.
        $this->session->set_userdata('firm_id', $this->input->post('firm_id'));

        // Redirecting to default page
        redirect('/', 'refresh');
    }

    // Logout from current firm and then loggin to another firm
    function changeLogin($firm_id, $cls, $func = '')
    {
        $this->isAllowed();

        // Deleting current firm from session.
        $this->session->set_userdata('firm_id', '');

        // Checking is the current task is enabled for the user.
        $this->isAllowedTask($this->cls . '/login');

        // Saving current firm in session.
        $this->session->set_userdata('firm_id', $firm_id);

        // Redirecting to default page.
        redirect("$cls/$func", 'refresh');
    }

    function list_firms()
    {

        $data['title'] = "Firms";
        $data['table'] = $this->firms->list_firms();
        $this->_render_page($this->clsfunc, $data);
    }

}

?>