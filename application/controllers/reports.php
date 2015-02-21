<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require 'my_controller.php';

class Reports extends My_controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('reports_model', 'reports');
        $this->load->model('employee_work_centre_model', 'employee_work_centre');
        $this->load->model('rental_details_model', 'rental_details');
        $this->load->model('vehicle_workcentres_model', 'vehicle_workcentres');
        $this->load->model('destination_workcentres_model', 'destination_workcentres');
        
        // Determining is the logged in user allowed to go forward with the current action.
        $this->isAllowed();
    }
    
    function balanceSheet()
    {
        // Checking is the current task is enabled for the user
        $this->isAllowedTask($this->clsfunc);
        
        $data['title'] = "Balance Sheet";
        $data['heading'] = "Balance Sheet";
        $data['workcentres'] = $this->workcentres->get_workcentres_options($this->user_id, $this->firm_id, 1);
        
        // Storing id of all workcentres represented by $data['workcentres'].
        $workcentres = '';
        foreach($data['workcentres'] as $id=>$wc)
            $workcentres[] = $id;
        
        $workcentres = ifSet('wcntr_id')?:$workcentres;
        $from = ifSet('f_date');
        $to = ifSet('t_date');
        
        $data['balanceSheet'] = $this->getBalanceSheet($workcentres,$from,$to);        
        $this->_render_page($this->clsfunc, $data);
        
    }
    
    function cashInHand()
    {
        // Checking is the current task is enabled for the user
        $this->isAllowedTask($this->clsfunc);
         
        $data['title'] = "Cash";
        $data['heading'] = "Cash in Hand";
        $data['workcentres'] = $this->workcentres->get_workcentres_options($this->user_id, $this->firm_id, 1);
        
        // Storing id of all workcentres represented by $data['workcentres'].
        $workcentres = '';
        foreach($data['workcentres'] as $id=>$wc)
            $workcentres[] = $id;
        
        $workcentres = ifSet('wcntr_id')?:$workcentres;
        $from = ifSet('f_date');
        $to = ifSet('t_date');
        
        $data['cashInHand'] = $this->getCashInHand($workcentres, $from, $to, 'detailed');
        $this->_render_page($this->clsfunc, $data);
        
    }

}

?>