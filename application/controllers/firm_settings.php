<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require 'my_controller.php';

class Firm_settings extends My_controller
{

    function __construct()
    {
        parent::__construct();

        $this->load->model('firm_settings_model', 'firm_settings', true);

        // Determining is the logged in user allowed to go forward with the current action.
        $this->isAllowed();
    }

    // Called by Ajax
    function edit()
    {   
        // Checking is the current task is enabled for the user
        $msg = taskEnabled($this->clsfunc);
        if ($msg != 1)
        {    
            echo $msg;
            return;
        }
        
        $this->form_validation->set_rules('frmset_id', "id", 'required');
        $this->form_validation->set_rules('frmset_value', "value", 'required');
        
        $this->form_validation->set_error_delimiters('<div class="pop_failure">', '</div>');
        if (!$this->form_validation->run())
        {
            $message = validation_errors();
            if ($message)
            {
                echo $this->errorTitle . $message ;
                return;
            }
        }

        // Recieving input 
        $input = $this->get_inputs();
        
        if(is_array($input['frmset_value']))
            $input['frmset_value'] = implode (',',$input['frmset_value']);
         
        $data = array('frmset_value'=>$input['frmset_value']);
        $id = $input['frmset_id'];
        
        // Udating data to Tbl:firm_settings
        if($input['apply_to_all_firms']=='true')
        {    
            $where['frmset_fk_settings'] = $this->firm_settings->getSettingsId($id);
            $this->firm_settings->updateFirm('',$data,$where);
        }
        else
            $this->firm_settings->updateFirm($id,$data);
       
        echo 1;
        
    }

    // Called by Ajax
    function getOptions()
    {
        $p_key = $_GET['frmset_id'];
        $settings_key = $this->firm_settings->get_Key($p_key);
        $settings = $this->settings->getSettingsValues($settings_key);
        $values = array();
        $type = 'Normal'; // To determine is the dropdown is multiple or normal.

        foreach ($settings as $key => $val)
        {
            if ($key == 'multiple_values')
            {
                $values = $val;
                $type = 'Multiple';
                break;
            }
            else
                $values[$key] = $val['text'];
        }
        // The first option is used to determine is the dropdown is multiple or normal. It will not be listed in dropdown.
        $json[] = array('value' => $type, 'text' => '');
        
        $json[] = array('value' => '', 'text' => 'Select');
        foreach ($values as $key => $val)
            $json[] = array('value' => $key, 'text' => $val);
        echo json_encode($json);
    }

}
