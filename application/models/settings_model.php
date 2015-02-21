<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Settings_model extends my_model
{

   function __construct()
   {
      parent::__construct();
      $this->loadTable(getTables('settings'));
      $this->p_key = 'set_id';
      $this->nameField = 'set_key';
      $this->statusField = '';
   }

   function getSettingsValues($KEY = '')
   {
      $values = array();
      // Format:-
      //  1. In the case of singel value. ie: it can have only one value at a time.
      //      $values[KEY][VALUE] = array(PROPERTIES OF VALUE);
      //  2. In the case of multiple values. ie: it can have multiple values at a time.
      //      $values['KEY']['multiple_values'] = array(1=>"Value 1",2=>"Value 2",3=>"Value 3");
      // Themes
      $values['THEME'][1] = array('text' => "Black", "color" => "blue");
      $values['THEME'][2] = array('text' => "White", "color" => "blue");

      // Redirect
      $values['REDIRECT'][1] = array('text' => "Enabled", "color" => "green");
      $values['REDIRECT'][2] = array('text' => "Disabled", "color" => "red");

      //Pay on zero balance
//        $values['PAY_ON_ZERO_BALANCE'][1] = array('text' => "Enabled", "color" => "green");
//        $values['PAY_ON_ZERO_BALANCE'][2] = array('text' => "Disabled", "color" => "red");
      //Sale on zero balance
//        $values['SALE_ON_ZERO_STOCK'][1] = array('text' => "Enabled", "color" => "green");
//        $values['SALE_ON_ZERO_STOCK'][2] = array('text' => "Disabled", "color" => "red");
      
      // The employee category, those ARE HAVING POWER TO VERIFY.
      $values['VERIFIERS']['multiple_values'] = array(2 => "Parteners", 3 => "Staffs", 4 => "Drivers", 5 => "Loaders");


      // Mark current user's worklog as "Verified/Nonverified" to him.
      $values['MY_WORKLOG'][1] = array('text' => "Verified", "color" => "");
      $values['MY_WORKLOG'][2] = array('text' => "Non verified", "color" => "");

      
      


      /*
        +-----------------------------------------------------------------------------------------------+
        | Financial Year Change Month.                                                                  |
        +-----------------------------------------------------------------------------------------------+
        | Determining at which month the financial year must be changed automatically.                  |
        +-----------------------------------------------------------------------------------------------+
       */
      for ($m = 1; $m <= 12; $m++)
      {
         $month = date('F', mktime(0, 0, 0, $m, 1, date('Y')));
         $values['FYEAR_CHANGE_MONTH'][$m] = array('text' => $month, "color" => "");
      }


      /*
        +---------------------------------------------------------------------------------------------------------+
        | Financial Year Changing Method.                                                                         |
        +---------------------------------------------------------------------------------------------------------+
        | Determining how is the financial year must be changed when reached the changing period (represented by  |
        | FYEAR_CHANGE_MONTH) occured. Either manually or automatically.                                          |
        +---------------------------------------------------------------------------------------------------------+
       */
      $values['FYEAR_CHANGE_MODE'][1] = array('text' => "Automatically", "color" => "");
      $values['FYEAR_CHANGE_MODE'][2] = array('text' => "Manually", "color" => "");






      // The financial year.
      for ($Y = 2015; $Y < 2050; $Y++)
      {
         $color = (date('Y') == $Y) ? 'green' : 'red';
         $values['FYEAR'][$Y] = array('text' => "$Y - " . ($Y + 1), "color" => $color);
      }
      
      
      //Loadin Charge payment mode
        $values['LOADING_PAY_MODE'][1] = array('text' => "Individually", "color" => "");
        $values['LOADING_PAY_MODE'][2] = array('text' => "Shared to all", "color" => "");
        

      if ($KEY)
         return $values[$KEY];
      return $values;
   }

   /**
    * Returns  array of empcat_ids having verify power.
    * @param type $firm_id
    * @return type
    */
   function getVerifiers($firm_id)
   {
      $this->db->from('settings,firm_settings');
      $this->db->select('frmset_value');
      $this->db->where('frmset_fk_firms', $firm_id);
      $this->db->where('set_key', 'VERIFIERS');
      $this->db->where('set_id = frmset_fk_settings');
      $result = $this->db->get();
      $result = $result->row_array();
      return explode(',', $result['frmset_value']);
   }

   function getFirmSettings($key, $firm_id)
   {
      $this->db->from('settings,firm_settings');
      $this->db->select('frmset_value');
      $this->db->where('frmset_fk_firms', $firm_id);
      $this->db->where('set_key', $key);
      $this->db->where('set_id = frmset_fk_settings');
      $result = $this->db->get();
      $result = $result->row_array();

      if (is_numeric($result['frmset_value']))
         return $result['frmset_value'];
      return explode(',', $result['frmset_value']);
   }

   function getDefaultValue($key)
   {
      $this->db->from('settings');
      $this->db->select('set_default_value');
      $this->db->where('set_key', $key);
      $result = $this->db->get();
      $result = $result->row_array();

      if (is_numeric($result['set_default_value']))
         return $result['set_default_value'];
      return explode(',', $result['set_default_value']);
   }

   /**
    * Function to check all settings are running currectley
    * @param type $firm_id = Current Firm
    */
   function checkSettings($firms)
   {
      // Variable to store error messages.
      $errors = array();

      // Getting all settings
      $settings = $this->getSettingsValues();

#------------------     SERCHING FOR KEYS IN Tbl:settings   --------------------------------#
      // Searching for all keys in Tbl: settings.
      foreach ($settings as $key => $val)
      {
         $this->db->where('set_key', $key);
         if (!$this->db->count_all_results('settings'))
         {
            //Shows, having mistakes. 
            $flag = FALSE;

            $errors[] = "The '$key' key is not found in Tbl:settings.";
         }
      }
#------------------    END OF SERCHING FOR KEYS IN Tbl:settings   --------------------------------#  
#
#  
#    
#        
#---------  Searching that, Have all settings applied to each firm (active & inactive) in Tbl:firm_settings. --------------#
      // Searching that, is all settings in Tbl: settings are applied to each firm (active/inactive).
      foreach ($settings as $key => $val)
      {
         foreach ($firms as $firm_id => $firm_name)
         {
            $this->db->from('settings,firm_settings');
            $this->db->where('set_key', $key);
            $this->db->where('frmset_fk_firms', $firm_id);
            $this->db->where('frmset_fk_settings = set_id');
            if (!$this->db->count_all_results())
            {
               //Storing error messages.
               $errors[] = "The '$key' Settings has not applied to the firm $firm_name in Tbl:firm_settings.";
            }
         }
      }

#------------------    END OF SERCHING FOR KEYS IN Tbl:settings   --------------------------------#    

      return $errors;
   }

   function index($firm_id)
   {

      $this->db->from('settings,firm_settings');


      $this->db->where('frmset_fk_firms', $firm_id);
      $this->db->where('set_id = frmset_fk_settings');
      $this->db->order_by('set_key', 'asc');



      $query = $this->db->get('');
      $result = $query->result_array();

//        echo "<br>".$this->db->last_query();echo "<br>";

      return $result;
//        return array();
   }

}

?>