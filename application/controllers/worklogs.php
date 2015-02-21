<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require 'my_controller.php';

class Worklogs extends My_controller
{

    function __construct()
    {
        parent::__construct();

        //$this->load->model('worklogs_model', 'worklogs'); // Loaded in my_controller
        //$this->load->model('verify_model','verify'); // Loaded in my_controller
        // Determining is the logged in user allowed to go forward with the current action.
        $this->isAllowed();
    }

    function truncate_wlogs()
    {
        $this->worklogs->truncate_wlogs();
    }

    function groupChange()
    {
        $verify_ids = $_POST['verify_ids'];
        $verify_status = $_POST['verify_status'];
        $do_all = $_POST['do_all'];
        foreach ($verify_ids as $verify_id)
        {
            // The current action will be applied to all same worklogs in different workcentres under the user.
            if ($do_all == 'true')
            {
                // Getting same worklogs in all workcentres under the user.
                $sameWlogs = $this->verify->getSameWlogs($this->user_id, $verify_id);
                foreach ($sameWlogs as $v_id)
                    $this->verify->save(array('verify_status' => $verify_status), $v_id);
            }

            // The action applying to only current worklog.
            else
                $this->verify->save(array('verify_status' => $verify_status), $verify_id);
        }

        //redirecting
        $msg = ($verify_status == 1) ? "Worklogs verified successfully !!!" : "Worklogs marked successfully !!!";
        $this->session->set_flashdata('message', $msg);
        $this->session->set_flashdata('message_level', 1); // Success 
    }

    function changeStatus()
    {
        $verify_id = $_POST['verify_id'];
        $verify_status = $_POST['verify_status'];
        $do_all = $_POST['do_all'];

        // The current action will be applied to all same worklogs in different workcentres under the user.
        if ($do_all == 'true')
        {
            // Getting same worklogs in all workcentres under the user.
            $sameWlogs = $this->verify->getSameWlogs($this->user_id, $verify_id);
            foreach ($sameWlogs as $v_id)
                $this->verify->save(array('verify_status' => $verify_status), $v_id);
        }

        // The action applying to only current worklog.
        else
            $this->verify->save(array('verify_status' => $verify_status), $verify_id);
    }

    function index()
    {
        // Checking is the current task is enabled for the user
        $this->isAllowedTask($this->cls);

        // Receiving input
        $input = $this->get_pagination_inputs($this->worklogs);

        //Set the flash data message if there is one has set before redirected to this page.
        $data['message'] = $this->session->flashdata('message');
        $data['message_level'] = $this->session->flashdata('message_level');
        $data['offset'] = $input['offset'];
        $data['title'] = "Worklogs";
        $data['heading'] = 'User\'s Worklogs';
        $data['status'] = $this->worklogs->getStatus();
        $data['employees'] = $this->employees->getAllEmployees();

        // Employee category those are having power to verify. 
        $empcats_verify_power = $this->settings->getVerifiers($this->firm_id);

        // Getting emp_id of verifiers.
        $data['verifiers'] = $this->verify->getVerifiersInFirmOption($this->firm_id, $empcats_verify_power, $this->user_id);
        $this->per_page = $_POST ? $input['PER_PAGE'] : $this->per_page;
        $data['table'] = $this->worklogs->index($input, $this->firm_id, $this->user_id, $this->is_admin);
        $data['num_rows'] = $this->worklogs->index($input, $this->firm_id, $this->user_id, $this->is_admin, true);

        // Finding workcentres in worklog
        $workcentres = array();
        if ($data['table'])
            foreach ($data['table'] as $arr)
                if (!array_key_exists($arr['wlog_wc_fk_workcentres'], $workcentres))
                    $workcentres[$arr['wcntr_id']] = $arr['wcntr_name'];
        $data['workcentres'] = $workcentres;

        // Initializing pagination
        $data = array_merge($data, $this->initPagination($data['table'], $data['num_rows'], $input['offset']));

        // After validations
        $data = array_merge($data, $this->validateIndex());

        $this->_render_page($this->clsfunc, $data);
    }

    function validateIndex()
    {
        $config[] = array("my_verify_status", 'My account: status', 'required');
        if ($this->input->post('other_verifiers_id'))
            $config[] = array("other_verify_status", 'Other verifier\'s account: status', 'required');
        $config[] = array("wlog_from", 'Worklog From Date', 'callback_compare_dates[' . $this->input->post('wlog_to') . ']');
        $config[] = array("my_verify_from", 'My account: Status From Date', 'callback_compare_dates[' . $this->input->post('my_verify_to') . ']');
        $config[] = array("other_verify_from", 'Other\'s account: Status From Date', 'callback_compare_dates[' . $this->input->post('other_verify_to') . ']');
        $data = $this->checkValidations($config);
        return $data;
    }

}
