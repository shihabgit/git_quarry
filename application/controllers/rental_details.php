<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require 'my_controller.php';

class Rental_details extends My_controller
{

    function __construct()
    {
        parent::__construct();

        $this->load->model('rental_details_model', 'rental_details',true);
        $this->load->model('owners_model', 'owners');
        
        // Determining is the logged in user allowed to go forward with the current action.
        $this->isAllowed();
        
        
        $this->table = 'rental_details';
        $this->p_key = 'rntdt_id';
    }

    
    
    
    function get_wlog()//Called by ajax
    {   $model = $this->rental_details;
        $wlogs = $this->init_wlog($this->p_key,$model);

        if ($wlogs[0])
        {
            $latest_class = 'wlog_latest'; // Latest details about the worklog. Ie: the data from ref_tables, not from Tbl:backups.
            $wlog_fields = getWlogFields($this->table, 'keys');
            
            $owners = $this->owners->get_option();
            $installment_periods = $model->getInstallmentPeriods();
            $auto_add = array(1=>'Yes',2=>'No');
            $ob_mode = array(1=>'Cr',2=>'Dr'); 
            
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

                    if ($fld == 'rntdt_fk_owners')
                        $val = $owners[$row[$fld]];
                    else if ($fld == 'rntdt_ob_mode')
                        $val = $ob_mode[$row[$fld]];
                    else if ($fld == 'rntdt_instalment_period')
                        $val = $installment_periods[$row[$fld]];
                    else if ($fld == 'rntdt_auto_add')
                        $val = $auto_add[$row[$fld]];
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

    
    
}