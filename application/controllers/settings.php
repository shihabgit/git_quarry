<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require 'my_controller.php';

class Settings extends My_controller
{

    function __construct()
    {
        parent::__construct();

        $this->load->model('firm_settings_model', 'firm_settings');

        // Determining is the logged in user allowed to go forward with the current action.
        $this->isAllowed();
    }

    function add()
    {
        // Checking is the current task is enabled for the user
        $msg = taskEnabled($this->clsfunc);
        if ($msg != 1)
        {    
            echo $msg;
            return;
        }
        
        //  Validating settings .
        $val_settings = validationConfigs('settings', '', 'settings');

        // Validating firm_settings.
        $val_firm_settings = validationConfigs('firm_settings', '', 'firm_settings');

        // adding validation configurations to form_validation.
        $v_config = array_merge($val_settings, $val_firm_settings);
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

        // Recieving input for Tbl:settings
        $settings = $_POST['settings'];

        // Formating settings values.
        $settings['set_title'] = ucfirst(strtolower($settings['set_title'])); // Uppercase the first character.
        $settings['set_key'] = strtoupper($settings['set_key']);  // Capitalizing.

        // Assuming the insertion is failed by default.
        $success = FALSE;

        // Inserting data to Tbl:settings. 
        if ($set_id = $this->settings->insert($settings))
        {
            // Settings has been inserted successfully.
            $success = TRUE;


            #<<====== After successfull insertion of Tbl:settings,Applaying the 'settings' to all firms (both Active and Inactive firms).====>>#
            // Recieving input for Tbl:firm_settings
            $firm_settings = $_POST['firm_settings'];

            //Recieving recently inserted settings id for Tbl:firm_settings.
            $firm_settings['frmset_fk_settings'] = $set_id;

            // Getting all registered firms
            $firms = $this->firms->get_firms_options();
            
            // the $firm_settings['frmset_value'] will be changed by following for loop. So saving it.
            $frmset_value = $firm_settings['frmset_value'];
            
            foreach ($firms as $firm_id => $firm_name)
            {   // Selecting the firm in which the setings to be inserted.
                $firm_settings['frmset_fk_firms'] = $firm_id;

                // If you don't want the 'frmset_value' to be applied to all firms, seting the default value to it.
                if (($_POST['apply_to_all_firms']=='false') && ($firm_id != $this->firm_id))
                {
                    $firm_settings['frmset_value'] = $settings['set_default_value'];
                }
                else
                    $firm_settings['frmset_value'] = $frmset_value;

                //  Inserting data to Tbl:firm_settings
                if (!$this->firm_settings->insert($firm_settings))
                    $success = FALSE; // If insertion failed.
            }
        }

        if ($success) // If insertion is success.
            echo 1;
        else
            echo '<div class="failureHead"><img src="images/failure.gif" width="15" height="15" /> Error!!!<div class="pop_failure">Data couldn\'t be insert !</div></div>';
    }

    function index()
    {
        // Checking is the current task is enabled for the user
        $this->isAllowedTask($this->cls);
        
        $data['title'] = "Settings";
        $data['values'] = $this->settings->getSettingsValues();
        $data['table'] = $this->settings->index($this->firm_id);
        $this->_render_page($this->clsfunc, $data);
    }

    /**
     * callback function 
     * @param type $key
     * @return boolean : returns FALSE if the $key contains any thing other than alphanumeric(uppercase)/underscore  characters.
     */
    function key_format($key)
    {
        // passing each charcters to array.
        $char_array = str_split($key);

        // Asuming the key is containing only alphanumerics.
        $is_alphanumerics = true;

        // checking the existance of non-alphanumerics by using the ASCII value.
        foreach ($char_array as $chr)
        {
            $A_Z = (ord($chr) >= 65 && ord($chr) <= 90); // checking is the charcters are between A-Z.
            $zero_nine = (ord($chr) >= 48 && ord($chr) <= 57); // checking is the charcters are between 0-9.
            $underscore = (ord($chr) == 95); // checking is the charcters is an underscore.

            if ($A_Z || $zero_nine || $underscore)
                continue;
            else
            {
                $is_alphanumerics = false;
                break;
            }
        }

        if (!$is_alphanumerics)
        {
            $this->form_validation->set_message('key_format', 'The %s field contain only "Alphanumeric(uppercase)/Underscores".');
            return FALSE;
        }
        return TRUE;
    }
    
    /**
     * callback function 
     * @param type $value
     * @return boolean : returns FALSE if the $value contains any thing other than Numerics/comma  characters.
     */
    function value_format($value)
    {
        // passing each charcters to array.
        $char_array = str_split($value);

        // Asuming the key is containing only Numerics.
        $is_alphanumerics = true;

        // checking the existance of non-numerics by using the ASCII value.
        foreach ($char_array as $chr)
        {
            $zero_nine = (ord($chr) >= 48 && ord($chr) <= 57); // checking is the charcters are between 0-9.
            $comma = (ord($chr) == 44); // checking is the charcters is a comma.

            if ($zero_nine || $comma)
                continue;
            else
            {
                $is_alphanumerics = false;
                break;
            }
        }

        if (!$is_alphanumerics)
        {
            $this->form_validation->set_message('value_format', 'The %s field contain only "numbers" or "a comma seperated number set".');
            return FALSE;
        }
        return TRUE;
    }

}
