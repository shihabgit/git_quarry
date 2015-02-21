<?php

function getUser()
{
   $table = getTables('STAFF');
   $CI = & get_instance();
   $username = $CI->admin_entry->get_admin();
   $CI->db->select('STF_NAME'); // IF you'r not connected with Database or not load any 'model file' may cause error on this line !
   $CI->db->where('STF_UNM', $username);
   $resultSet = $CI->db->get($table);
   $result = $resultSet->row_array();
   if (isset($result['STF_NAME']))
      return $result['STF_NAME'];
   else
      return 'Unknown User';
}

function getCaseFunc()
{
   return 'strtolower'; //$caseFunc must be any of the inbuilt function strtolower or strtolower.
}

function createTables($tables = '')
{
   $caseFunc = getCaseFunc();
   $tables = $tables ? $tables : getTables('All');
   $INDEX = array();
   //print_r($tables);
   foreach ($tables as $ref => $tbl)
   {
      echo "Table : " . $tbl . "<br><br>";
      $flag = 1;
      $func_name = 'getFields_' . $ref;
      $fields = $func_name(true, 'DB Creation');
      //echo "<br>$tbl: <pre>";print_r($fields);
      foreach ($fields as $fld => $description)
      {
         if ($fld == 'INDEX')
         {
            $INDEX[$tbl] = $description;
            continue; //break;
         }
         if ($flag)
            $query = "CREATE TABLE IF NOT EXISTS " . $tbl . " (";
         else
            $query .= ', ';
         $query .= $fld . ' ' . $description[0] . ' ';
         if ($description[1])
            $query .= '(' . $description[1] . ') ';
         if ($description[2])
            $query .= $description[2];
         $flag = 0;
      }

      $query .= " )ENGINE InnoDB DEFAULT CHARSET=latin1 ;";
      $CI = & get_instance(); // IF you'r not connected with Database or not load any 'model file' may cause error on this line !
      $CI->db->query($query);
      //echo "<br><br>$query<br><br>";
   }
   //print_r($INDEX);
   //Setting Foreign Keys
   if ($INDEX)
   {
      foreach ($INDEX as $tbl => $rows)
         foreach ($rows as $row)
         {
            $query = "ALTER TABLE $tbl ADD CONSTRAINT FKey_$tbl" . "_$row[f_key] FOREIGN KEY ($row[f_key]) "
                    . "REFERENCES $row[ref_tbl] ($row[ref_fld]) ON DELETE $row[DELETE] ON UPDATE $row[UPDATE];\n ";
            //echo "<br><br><pre>" .$query."</pre>";
            $CI->db->query($query);
         }
   }
   // echo "<br><br><pre>" . $CI->db->last_query() . "</pre><br><br>";

   echo "Tables Created";
}

function backUp($skip = array())
{   //where $skip is table to be skipped from backup. eg: array('Table 1','Table 2');
   $caseFunc = getCaseFunc();
   $tables = getTables('All');
   $backUp = array('create' => '', 'alter' => '');
   $INDEX = array();
   foreach ($tables as $ref => $tbl)
   {
      if (in_array($tbl, $skip))
         continue;
      $query = '';
      $flag = 1;
      $func_name = 'getFields_' . $ref;
      $fields = $func_name(true, 'DB Creation');
      foreach ($fields as $fld => $description)
      {
         if ($fld == 'INDEX')
         {
            $INDEX[$tbl] = $description;
            continue; //break;
         }
         if ($flag)
            $query = "CREATE TABLE IF NOT EXISTS " . $tbl . " (\n";
         else
            $query .= ", \n";
         $query .= $fld . ' ' . $description[0] . ' ';
         if ($description[1])
            $query .= '(' . $description[1] . ') ';
         if ($description[2])
            $query .= $description[2];
         $flag = 0;
      }

      $query .= " \n)ENGINE InnoDB DEFAULT CHARSET=latin1 ;\n\n";
      $backUp['create'] .= $query . "\n";
   }
   //Setting Foreign Keys
   if ($INDEX)
   {
      foreach ($INDEX as $tbl => $rows)
         foreach ($rows as $row)
         {
            $query = "ALTER TABLE $tbl ADD CONSTRAINT FKey_$tbl" . "_$row[f_key] FOREIGN KEY ($row[f_key]) "
                    . "REFERENCES $row[ref_tbl] ($row[ref_fld]) ON DELETE $row[DELETE] ON UPDATE $row[UPDATE];\n ";
            $backUp['alter'] .= $query . "\n";
         }
   }
   // echo "<br><br><pre>" . $CI->db->last_query() . "</pre><br><br>";

   return $backUp;
}

function getTables($table = 'All', $key = 'tbl_name')
{
   //Ion_auth related Tables:
//    $tbl['auth_groups'] = 'auth_groups';
//    $tbl['auth_users'] = 'auth_users';
//    $tbl['auth_users_groups'] = 'auth_users_groups';
//    $tbl['auth_login_attempts'] = 'auth_login_attempts';



   $tbl['backups']['tbl_name'] = 'backups';
   $tbl['backups']['title'] = 'Data Back Up';


   $tbl['employees']['tbl_name'] = 'employees';
   $tbl['employees']['title'] = 'Employees';


   $tbl['employee_work_centre']['tbl_name'] = 'employee_work_centre';
   $tbl['employee_work_centre']['title'] = 'Employees in Workcentres';


   $tbl['firms']['tbl_name'] = 'firms';
   $tbl['firms']['title'] = 'Firms';


   $tbl['firm_settings']['tbl_name'] = 'firm_settings';
   $tbl['firm_settings']['title'] = 'Firm Settings';


   $tbl['form_inputs']['tbl_name'] = 'form_inputs';
   $tbl['form_inputs']['title'] = 'Form Inputs';

   $tbl['owners']['tbl_name'] = 'owners';
   $tbl['owners']['title'] = 'Owners';


   $tbl['rental_details']['tbl_name'] = 'rental_details';
   $tbl['rental_details']['title'] = 'Rental Details';


   $tbl['rent_payables']['tbl_name'] = 'rent_payables';
   $tbl['rent_payables']['title'] = 'Rent Payables';


   $tbl['settings']['tbl_name'] = 'settings';
   $tbl['settings']['title'] = 'Settings';


   $tbl['tasks']['tbl_name'] = 'tasks';
   $tbl['tasks']['title'] = 'Tasks';


   $tbl['user_tasks']['tbl_name'] = 'user_tasks';
   $tbl['user_tasks']['title'] = 'Users Tasks';


   $tbl['verify']['tbl_name'] = 'verify';
   $tbl['verify']['title'] = 'Verify';


   $tbl['workcentres']['tbl_name'] = 'workcentres';
   $tbl['workcentres']['title'] = 'Workcentres';


   $tbl['workcentre_registration_details']['tbl_name'] = 'workcentre_registration_details';
   $tbl['workcentre_registration_details']['title'] = 'Workcentre\'s Registration Details';


   $tbl['worklogs']['tbl_name'] = 'worklogs';
   $tbl['worklogs']['title'] = 'Users Work Logs';


   $tbl['worklog_workcentres']['tbl_name'] = 'worklog_workcentres';
   $tbl['worklog_workcentres']['title'] = 'Workcentres Related To Work Log';

   $tbl['vehicles']['tbl_name'] = 'vehicles';
   $tbl['vehicles']['title'] = 'Vehicles in Workcentres';

   $tbl['vehicle_workcentres']['tbl_name'] = 'vehicle_workcentres';
   $tbl['vehicle_workcentres']['title'] = 'Vehicles in Workcentres';

   $tbl['vehicles_employees']['tbl_name'] = 'vehicles_employees';
   $tbl['vehicles_employees']['title'] = 'Labours in vehicle';

   $tbl['freight_charges']['tbl_name'] = 'freight_charges';
   $tbl['freight_charges']['title'] = 'Freight charge';

   $tbl['inter_freight_charges']['tbl_name'] = 'inter_freight_charges';
   $tbl['inter_freight_charges']['title'] = 'Inter freight charges';

   $tbl['item_category']['tbl_name'] = 'item_category';
   $tbl['item_category']['title'] = 'Item Categories';

   $tbl['item_heads']['tbl_name'] = 'item_heads';
   $tbl['item_heads']['title'] = 'Item Heads';

   $tbl['units']['tbl_name'] = 'units';
   $tbl['units']['title'] = 'Units';

   $tbl['items']['tbl_name'] = 'items';
   $tbl['items']['title'] = 'Items';


   $tbl['item_units_n_rates']['tbl_name'] = 'item_units_n_rates';
   $tbl['item_units_n_rates']['title'] = 'Item unit and rates';


   $tbl['opening_stock']['tbl_name'] = 'opening_stock';
   $tbl['opening_stock']['title'] = 'Openign Stock';


   $tbl['individual_rates']['tbl_name'] = 'individual_rates';
   $tbl['individual_rates']['title'] = 'Individual Rates';


   $tbl['workcentre_rates']['tbl_name'] = 'workcentre_rates';
   $tbl['workcentre_rates']['title'] = 'Workcentre Rates';


   $tbl['parties']['tbl_name'] = 'parties';
   $tbl['parties']['title'] = 'Parties';


   $tbl['party_license_details']['tbl_name'] = 'party_license_details';
   $tbl['party_license_details']['title'] = 'Party\'s License Details';


   $tbl['party_destinations']['tbl_name'] = 'party_destinations';
   $tbl['party_destinations']['title'] = 'Destinations of Parties';


   $tbl['party_vehicles']['tbl_name'] = 'party_vehicles';
   $tbl['party_vehicles']['title'] = 'Vehicles of parties';


   $tbl['destination_workcentres']['tbl_name'] = 'destination_workcentres';
   $tbl['destination_workcentres']['title'] = 'Destinations under workcentre';


   $tbl['party_vehicle_rents']['tbl_name'] = 'party_vehicle_rents';
   $tbl['party_vehicle_rents']['title'] = 'Party vehicle rent';


   
// purchase_bill_head
// purchase_bill_body
// purchase_billnumber_notax
// purchase_billnumber_tax
// purchase_bill_loaders
// purchase_bill_additives
// purchase_bill_deductives
   
   $tbl['purchase_bill_head']['tbl_name'] = 'purchase_bill_head';
   $tbl['purchase_bill_head']['title'] = 'Purchase Bill';

 

   $tbl['purchase_bill_body']['tbl_name'] = 'purchase_bill_body';
   $tbl['purchase_bill_body']['title'] = 'Purchase Bill Body';


   $tbl['purchase_billnumber_notax']['tbl_name'] = 'purchase_billnumber_notax';
   $tbl['purchase_billnumber_notax']['title'] = 'Purchase Bill Number for non-taxable bills';


   $tbl['purchase_billnumber_tax']['tbl_name'] = 'purchase_billnumber_tax';
   $tbl['purchase_billnumber_tax']['title'] = 'Purchase Bill Number for taxable bills';


   $tbl['purchase_bill_loaders']['tbl_name'] = 'purchase_bill_loaders';
   $tbl['purchase_bill_loaders']['title'] = 'Loaders details in a purchase bill';


   $tbl['purchase_bill_additives']['tbl_name'] = 'purchase_bill_additives';
   $tbl['purchase_bill_additives']['title'] = 'Purchase bill additives';


   $tbl['purchase_bill_deductives']['tbl_name'] = 'purchase_bill_deductives';
   $tbl['purchase_bill_deductives']['title'] = 'Purchase bill deductives';


   $tbl['sale_billnumber_notax']['tbl_name'] = 'sale_billnumber_notax';
   $tbl['sale_billnumber_notax']['title'] = 'Sale Bill Number for non-taxable bills';


   $tbl['sale_billnumber_tax']['tbl_name'] = 'sale_billnumber_tax';
   $tbl['sale_billnumber_tax']['title'] = 'Sale Bill Number for taxable bills';

   if ($key)
   {
      $temp = array();
      foreach ($tbl as $k => $v)
         $temp[$k] = $v[$key];
      $tbl = $temp;
   }

   if ($table == 'All')
      return $tbl;
   return $tbl[$table];
}

function getFields($table, $id = true)
{
   $func_name = 'getFields_' . $table;
   $fields = array_keys($func_name($id));
   return $fields;
}

function getFieldsWithEmptyValues($table)
{
   $fields = getFields($table);
   $empty = array();
   foreach ($fields as $fld)
      $empty[$fld] = '';
   return $empty;
}

/* * Including validation field names inside the settings[] array. because the form elements are declared as arrays 
 * 
 * @param type $table :         Name of the table.
 * 
 * @param type $validate_on :   [optional] Current action(add/edit/delete). 
 *                              if we want to applay unique/different validation for different actions (add/edit/delete). 
 * 
 * @param type $container:      [optional] The name of the container array.
 *                              If the form elements are declared as arrays, this parameter holds the name of the container array.
 *                              Eg: if the name of the form element is as <input name="workcentre['wcntr_name']" > 
 *                                  you should use the string 'workcentre' as the $container.
 * @return type
 */

function validationConfigs($table, $validate_on = 'add', $container = '')
{
   $config = array();
   $func_name = 'getFields_' . $table;
   $fields = $func_name(true, '', $validate_on);
   foreach ($fields as $key => $val)
      $config[] = array('field' => $key, 'label' => $val[3], 'rules' => $val[4]);
   if ($container)
   {
      // Including validation field names inside the $container array. (In the case when the form elements are declared as arrays)
      foreach ($config as &$value)
         $value['field'] = $container . '[' . $value['field'] . ']';
   }

   return $config;
}

function getFields_auth_groups($id = true, $action = '', $validation = '')
{
   if ($id)
      $field['id'] = array('mediumint', '8', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', '', '');
   $field['name'] = array('varchar', '20', ' NOT NULL', 'Name', '');
   $field['description'] = array('varchar', '100', 'NOT NULL', 'Description', '');
   return $field;
}

function getFields_auth_users($id = true, $action = '', $validation = '')
{
   if ($id)
      $field['id'] = array('int', '11', ' unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', '', '');
   $field['ip_address'] = array('varchar', '15', 'NOT NULL', 'IP', '');
   $field['username'] = array('varchar', '100', 'NOT NULL', 'Username', '');
   $field['password'] = array('varchar', '255', 'NOT NULL', 'Password', '');
   $field['salt'] = array('varchar', '255', 'DEFAULT NULL', 'Salt', '');
   $field['email'] = array('varchar', '100', 'NOT NULL', 'Email', '');
   $field['activation_code'] = array('varchar', '40', 'DEFAULT NULL', 'Activation Code', '');
   $field['forgotten_password_code'] = array('varchar', '40', 'DEFAULT NULL', 'Password Forgot', '');
   $field['forgotten_password_time'] = array('int', '11', 'unsigned DEFAULT NULL', 'Password Forgoten Time', '');
   $field['remember_code'] = array('varchar', '40', 'DEFAULT NULL', 'Remember Code', '');
   $field['created_on'] = array('int', '11', 'unsigned NOT NULL', 'Created', '');
   $field['last_login'] = array('int', '11', 'unsigned DEFAULT NULL', 'Last login', '');
   $field['active'] = array('tinyint', '1', 'unsigned DEFAULT NULL', 'Active', '');
   $field['first_name'] = array('varchar', '50', 'DEFAULT NULL', 'First Name', '');
   $field['last_name'] = array('varchar', '50', 'DEFAULT NULL', 'Last Name', '');
   $field['company'] = array('varchar', '100', 'DEFAULT NULL', 'Company', '');
   $field['phone'] = array('varchar', '20', 'DEFAULT NULL', 'Phone', '');
   return $field;
}

function getFields_auth_users_groups($id = true, $action = '', $validation = '')
{
   if ($id)
      $field['id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', '', '');
   $field['user_id'] = array('int', '11', 'unsigned NOT NULL', 'User', '');
   $field['group_id'] = array('mediumint', '8', 'unsigned NOT NULL', '', '');
   if ($action == 'DB Creation') // On creating database and tables
   {
      $field['INDEX'][] = array('f_key' => 'user_id', 'ref_tbl' => 'auth_users', 'ref_fld' => 'id', 'DELETE' => 'CASCADE', 'UPDATE' => 'NO ACTION');
      $field['INDEX'][] = array('f_key' => 'group_id', 'ref_tbl' => 'auth_groups', 'ref_fld' => 'id', 'DELETE' => 'CASCADE', 'UPDATE' => 'NO ACTION');
   }
   return $field;
}

function getFields_auth_login_attempts($id = true, $action = '', $validation = '')
{
   if ($id)
      $field['id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', '', '');
   $field['ip_address'] = array('varchar', '15', 'NOT NULL', 'Ip Address', '');
   $field['login'] = array('varchar', '100', 'NOT NULL', 'Login', '');
   $field['time'] = array('int', '11', 'unsigned DEFAULT NULL', 'Login Time', '');
   return $field;
}

function getFields_firms($id = true, $action = '', $validation = '')
{
   if ($id)
      $field['firm_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', '', '');
   $field['firm_date'] = array('date', '', '', 'Date', '');
   $field['firm_name'] = array('varchar', '20', 'NOT NULL', 'Firm Name', 'required|max_length[20]|is_unique[firms.firm_name]|callback_allow_multiple');
   $field['firm_status'] = array('tinyint', '1', '', 'Status', '');
   return $field;
}

function getFields_workcentres($id = true, $action = '', $validation = '')
{
   if ($id)
      $field['wcntr_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', '', '');
   $field['wcntr_fk_firms'] = array('int', '11', 'unsigned NOT NULL', 'Firm', '');

   if ($validation && (($validation == 'add') || ($validation == 'edit')))
      $field['wcntr_date'] = array('date', '', '', 'Date', '');
   else
      $field['wcntr_date'] = array('date', '', '', 'Date', 'required');

   $field['wcntr_ownership'] = array('tinyint', '1', '', 'Ownership', 'required'); //array(1 => 'Owned',2 => 'Rental');
   $field['wcntr_capital'] = array('decimal', '13,2', 'NOT NULL', 'Capital', 'numeric');
   $field['wcntr_name'] = array('varchar', '20', '', 'Name', 'required|max_length[20]');
   $field['wcntr_fk_workcentre_registration_details'] = array('int', '11', 'unsigned NOT NULL', 'Registration', '');

   if ($validation && ($validation == 'edit'))
      $field['wcntr_status'] = array('tinyint', '1', '', 'Status', '');
   else
      $field['wcntr_status'] = array('tinyint', '1', '', 'Status', 'required');
   if ($action == 'DB Creation') // On creating database and tables
   {  //$field['INDEX'][] = array('f_key'=>'wcntr_fk_firms','ref_tbl'=>'firms','ref_fld'=>'firm_id','DELETE'=>'CASCADE', 'UPDATE'=>'RESTRICT');
   }
   return $field;
}

function getFields_workcentre_registration_details($id = true, $action = '', $validation = '')
{
   if ($id)
      $field['wrd_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', '', '');
   $field['wrd_fk_firms'] = array('int', '11', 'unsigned NOT NULL', 'Firm', '');
   $field['wrd_date'] = array('date', '', '', 'Date', 'required');

   // Registration name
   $field['wrd_name'] = array('varchar', '50', '', 'Reg. Name', 'required|callback_check_unique[wrd_name]|max_length[50]');

   $field['wrd_address'] = array('varchar', '250', '', 'Address', 'max_length[250]');
   $field['wrd_phone'] = array('varchar', '20', '', 'Phone No', 'max_length[20]');
   $field['wrd_email'] = array('varchar', '40', '', 'Email', 'max_length[40]|valid_email');
   $field['wrd_tin'] = array('varchar', '20', '', 'Tin No', 'callback_check_unique[wrd_tin]|max_length[20]');
   $field['wrd_licence'] = array('varchar', '20', '', 'Licence No', 'callback_check_unique[wrd_licence]|max_length[20]');
   $field['wrd_cst'] = array('varchar', '20', '', 'CST', 'callback_check_unique[wrd_cst]|max_length[20]');
   $field['wrd_status'] = array('tinyint', '1', '', 'Status', '');    //array(1 => 'Active',2 => 'Inactive');
   return $field;
}

// An owner once created is available all workcentres and firms.
function getFields_owners($id = true, $action = '', $validation = '')
{
   if ($id)
      $field['ownr_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', '', '');
   $field['ownr_date'] = array('date', '', '', 'Date', 'required');
   $field['ownr_name'] = array('varchar', '20', '', 'Name', 'required|max_length[20]');
   $field['ownr_address'] = array('varchar', '30', '', 'Address', 'required|max_length[30]');
   $field['ownr_phone'] = array('varchar', '20', '', 'Phone', 'max_length[20]');
   $field['ownr_status'] = array('tinyint', '1', '', 'Status', 'required');
   return $field;
}

function getFields_rental_details($id = true, $action = '', $validation = '')
{
   if ($id)
      $field['rntdt_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', '', '');
   if ($validation && ($validation == 'add'))
      $field['rntdt_fk_workcentre'] = array('int', '11', 'unsigned NOT NULL', 'Workcentre', '');
   else
      $field['rntdt_fk_workcentre'] = array('int', '11', 'unsigned NOT NULL', 'Workcentre', 'required');

   $field['rntdt_fk_owners'] = array('int', '11', 'unsigned NOT NULL', 'Owner', 'required');

   if ($validation && ($validation == 'add'))
      $field['rntdt_date'] = array('date', '', '', 'Date', '');
   else
      $field['rntdt_date'] = array('date', '', '', 'Date', 'required');

   $field['rntdt_advance'] = array('decimal', '13,2', 'NOT NULL', 'Advance Paid', 'numeric');
   $field['rntdt_ob'] = array('decimal', '13,2', 'NOT NULL', 'Old Balance', 'numeric');
   $field['rntdt_ob_mode'] = array('tinyint', '1', '', 'Mode', ''); //array(1 => 'Credit',2 => 'Debt');
   $field['rntdt_instalment_amount'] = array('decimal', '13,2', 'NOT NULL', 'Installment Amount', 'required|numeric');
   $field['rntdt_instalment_period'] = array('tinyint', '1', '', 'Installment Period', 'required'); //array( 1=>"daily", 2=>"monthly",3=> "yearly")
   $field['rntdt_auto_add'] = array('tinyint', '1', '', 'Auto Add', ''); //array(1=>true,2=>false)
   $field['rntdt_start_from'] = array('date', '', '', 'Rent Start From', 'required');
   return $field;
}

function getFields_rent_payables($id = true, $action = '', $validation = '')
{
   if ($id)
      $field['rntpybl_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', '', '');
   $field['rntpybl_fk_workcentre'] = array('int', '11', 'unsigned NOT NULL', 'Workcentre', 'required');
   $field['rntpybl_fk_owners'] = array('int', '11', 'unsigned NOT NULL', 'Owner', 'required');
   $field['rntpybl_date'] = array('date', '', '', 'Date', 'required');
   $field['rntpybl_period_belonged_to'] = array('varchar', '40', '', 'Belonged Period', 'required');
   $field['rntpybl_amount'] = array('decimal', '13,2', 'NOT NULL', 'Rent Payable', 'required|numeric');
   $field['rntpybl_amount_declared'] = array('decimal', '13,2', 'NOT NULL', 'Rent Declared', 'numeric');
   return $field;
}

function getFields_settings($id = true, $action = '', $validation = '')
{
   if ($id)
      $field['set_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', '', '');
   $field['set_title'] = array('varchar', '100', '', 'Title', 'required|max_length[100]');
   $field['set_key'] = array('varchar', '20', '', 'Key', 'required|max_length[20]|callback_key_format|is_unique[settings.set_key]');
   $field['set_default_value'] = array('varchar', '100', '', 'Default Value', 'required|callback_value_format|max_length[100]');
   return $field;
}

function getFields_firm_settings($id = true, $action = '', $validation = '')
{
   if ($id)
      $field['frmset_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', '', '');
   $field['frmset_fk_settings'] = array('int', '11', 'unsigned NOT NULL', 'Settings Title', '');
   $field['frmset_fk_firms'] = array('int', '11', 'unsigned NOT NULL', 'Firm', '');
   $field['frmset_value'] = array('varchar', '100', '', 'Value', 'required|callback_value_format|max_length[100]');

   if ($action == 'DB Creation') // On creating database and tables
   {
      $field['INDEX'][] = array('f_key' => 'frmset_fk_settings', 'ref_tbl' => 'settings', 'ref_fld' => 'set_id', 'DELETE' => 'CASCADE', 'UPDATE' => 'CASCADE');
      $field['INDEX'][] = array('f_key' => 'frmset_fk_firms', 'ref_tbl' => 'firms', 'ref_fld' => 'firm_id', 'DELETE' => 'CASCADE', 'UPDATE' => 'CASCADE');
   }
   return $field;
}

function getFields_employees($id = true, $action = '', $validation = '')
{
   if ($id)
   // The value of $field['emp_id'] must be equal & related to Tbl:auth_users.id
      $field['emp_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', '', '');
   $field['emp_category'] = array('tinyint', '1', 'NOT NULL', 'Category', 'required'); // array(1=>"Admin",2=>"Partner",3=>"Staff",4=>"Driver",5=>"Loader")
   $field['emp_name'] = array('varchar', '50', 'DEFAULT NULL', 'First Name', '');  // Alias of Tbl:auth_users.first_name.
   $field['emp_date'] = array('date', '', '', 'Date', '');
   $field['emp_address'] = array('varchar', '100', '', 'Address', 'max_length[100]');
   $field['emp_status'] = array('tinyint', '1', '', 'Status', 'required');

   if ($action == 'DB Creation') // On creating database and tables
   {   //$field['INDEX'][] = array('f_key'=>'emp_fk_auth_users','ref_tbl'=>'auth_users','ref_fld'=>'id','DELETE'=>'RESTRICT', 'UPDATE'=>'NO ACTION');
   }

   return $field;
}

function getFields_employee_work_centre($id = true, $action = '', $validation = '')
{
   if ($id)
      $field['ewp_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', '', '');
   $field['ewp_date'] = array('date', '', '', 'Date', 'required');
   $field['ewp_fk_auth_users'] = array('int', '11', 'unsigned NOT NULL', 'User Id', 'callback_checkUnique'); //User id in ion-auth
   $field['ewp_fk_workcentres'] = array('int', '11', 'unsigned NOT NULL', 'Workcentre', '');
   $field['ewp_ob'] = array('decimal', '13,2', '', 'Old Balance', 'callback_is_numeric|xss_clean');
   $field['ewp_ob_mode'] = array('tinyint', '1', '', 'Olde Balance Mode', ''); // 1=> Credit, 2=> Debt
   $field['ewp_day_wage'] = array('decimal', '13,2', '', 'Day Full Wage', 'callback_is_numeric|xss_clean');
   $field['ewp_day_hourly_wage'] = array('decimal', '13,2', '', 'Day hourly wage', 'callback_is_numeric|xss_clean');
   $field['ewp_day_ot_wage'] = array('decimal', '13,2', '', 'Day OT wage', 'callback_is_numeric|xss_clean');
   $field['ewp_night_wage'] = array('decimal', '13,2', '', 'Night Full Wage', 'callback_is_numeric|xss_clean');
   $field['ewp_night_hourly_wage'] = array('decimal', '13,2', '', 'Night hourly wage', 'callback_is_numeric|xss_clean');
   $field['ewp_night_ot_wage'] = array('decimal', '13,2', '', 'Night OT wage', 'callback_is_numeric|xss_clean');
   $field['ewp_salary_wage'] = array('decimal', '13,2', '', 'Salary amount', 'callback_is_numeric|xss_clean');
   $field['ewp_status'] = array('tinyint', '1', '', 'Status', 'required');
   return $field;
}

function getFields_worklogs($id = true, $action = '', $validation = '')
{
   if ($id)
      $field['wlog_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', '', '');
   $field['wlog_fk_auth_users'] = array('int', '11', 'unsigned NOT NULL', 'User Id', ''); //id of logged in user
   $field['wlog_firms'] = array('varchar', '100', '', 'Firm', '');
   $field['wlog_created'] = array('datetime', '', '', 'Created On', '');
   $field['wlog_warnings'] = array('tinyint', '1', '', 'Warnings', '');    // array(1=> 'Warning', 2=> 'Normal').
   $field['wlog_ref_table'] = array('varchar', '50', '', 'Reference Table', 'max_length[50]'); // Table related to current action.
   $field['wlog_ref_id'] = array('int', '11', 'unsigned NOT NULL', 'Reference Key', ''); //id of current action.
   $field['wlog_ref_url'] = array('varchar', '70', '', 'Reference Page url', 'max_length[70]'); // from which class/function we inserted the worklog data.
   $field['wlog_popup_id'] = array('varchar', '50', '', 'Popupbox ID', 'max_length[50]'); //The value of the id attribute of the popup box which will display the details about the worklog.  If there is no popup boxes for a worklog, leave this field as blank.

   $field['wlog_clsfunc'] = array('varchar', '70', '', 'Created From', 'max_length[70]'); // from which class/function we inserted the worklog data.
   $field['wlog_ref_action'] = array('tinyint', '1', '', 'Action', ''); //Action made on $field['wlog_ref_table'] array(1=>’Add’,2=>’Edit’,3=>’Delete’) 
   $field['wlog_status'] = array('tinyint', '1', '', 'Status', ''); //Array(1=> ‘Active’, 2=> ‘Inactive’)
   return $field;
}

function getFields_worklog_workcentres($id = true, $action = '', $validation = '')
{
   if ($id)
      $field['wlog_wc_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', '', '');
   $field['wlog_wc_fk_worklogs'] = array('int', '11', 'unsigned NOT NULL', 'Worklogs Id', ''); //id of worklog table
   $field['wlog_wc_fk_workcentres'] = array('int', '11', 'unsigned NOT NULL', 'Workcentre Id', ''); //id of Workcentre table
   $field['wlog_wc_message'] = array('varchar', '600', '', 'Reference Table', 'max_length[150]'); // Table related to current action.
   $field['wlog_wc_action'] = array('tinyint', '1', '', 'Action', ''); // How the action affect to the workcentre. array(1=>’Add’,2=>’Edit’,3=>’Delete’) 
   return $field;
}

function getFields_verify($id = true, $action = '', $validation = '')
{
   if ($id)
      $field['verify_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', '', '');
   $field['verify_fk_worklog_workcentres'] = array('int', '11', 'unsigned NOT NULL', 'Worklog id', '');
   $field['verify_fk_auth_users'] = array('int', '11', 'unsigned NOT NULL', 'Verifier', ''); //the id of the user who is verifying the data.
   $field['verify_datime'] = array('datetime', '', '', 'Verified On', '');
   $field['verify_status'] = array('tinyint', '1', '', 'Verify Status', ''); //array(1=>'verified',2=>'Non-verified',3=>'Marked').
   return $field;
}

function getFields_backups($id = true, $action = '', $validation = '')
{
   if ($id)
      $field['bkp_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', '', '');
   $field['bkp_fk_worklogs'] = array('int', '11', 'unsigned NOT NULL', 'Worklog id', '');
   $field['bkp_ref_table'] = array('varchar', '50', '', 'Reference Table', 'max_length[50]'); // Backing up data is from which Table.
   $field['bkp_ref_id'] = array('int', '11', 'unsigned NOT NULL', 'Reference Key', ''); //P_key value of the Backed up data it Table.
   $field['bkp_data'] = array('text', '', '', 'Back up data', '');
   return $field;
}

function getFields_vehicles($id = true, $action = '', $validation = '')
{
   if ($id)
      $field['vhcl_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', '', '');
   $field['vhcl_date'] = array('date', '', '', 'Created On', '');
   $field['vhcl_no'] = array('varchar', '20', '', 'Vehicle No', 'required|max_length[20]'); // KL 10 G 3254
   $field['vhcl_name'] = array('varchar', '25', '', 'Vehicle Name', 'max_length[25]'); // Eg: Friends Group, Fantastic Travels, etc
   $field['vhcl_length'] = array('decimal', '13,2', '', 'Length', 'numeric|xss_clean'); // In Inches.
   $field['vhcl_breadth'] = array('decimal', '13,2', '', 'Breadth', 'numeric|xss_clean'); // In Inches.
   $field['vhcl_height'] = array('decimal', '13,2', '', 'Height', 'numeric|xss_clean'); // In Inches.
   $field['vhcl_xheight'] = array('decimal', '13,2', '', 'Extra Height', 'numeric|xss_clean'); // In Inches.
   $field['vhcl_remarks'] = array('varchar', '50', '', 'Remarks', 'max_length[50]');
   $field['vhcl_ownership'] = array('tinyint', '1', '', 'Ownership', 'required');  //array(1 => 'Ours',2 => 'Others');
   $field['vhcl_status'] = array('tinyint', '1', '', 'Status', '');    //array(1 => 'Active',2 => 'Inactive');

   return $field;
}

function getFields_vehicle_workcentres($id = true, $action = '', $validation = '')
{
   if ($id)
      $field['vwc_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', '', '');
   $field['vwc_date'] = array('date', '', '', 'Created On', '');

   // If the action is ADD.
   if (!isset($_POST['vwc_id']))
   {
      $field['vwc_fk_workcentres'] = array('int', '11', 'unsigned NOT NULL', 'Workcentre', 'required|callback_is_wc_exist');
      $field['vwc_fk_vehicles'] = array('int', '11', 'unsigned NOT NULL', 'Vehicle id', 'required');
   }

   $field['vwc_cost'] = array('decimal', '13,2', '', 'Cost', 'numeric|xss_clean'); // Vehicle cost when bought it.
   $field['vwc_ob'] = array('decimal', '13,2', '', 'O.B', 'numeric|xss_clean');
   $field['vwc_ob_mode'] = array('tinyint', '1', '', 'O.B Mode', 'required');    //array(1 => 'Credit',2 => 'Debt');
   $field['vwc_hourly_rate'] = array('decimal', '13,2', '', 'Hourly Rent', 'numeric|xss_clean');
   $field['vwc_daily_rate'] = array('decimal', '13,2', '', 'Daily Rent', 'numeric|xss_clean');
   $field['vwc_monthly_rate'] = array('decimal', '13,2', '', 'Monthly Rent', 'numeric|xss_clean');
   $field['vwc_sold_price'] = array('decimal', '13,2', '', 'Sold Price', 'numeric|xss_clean');  // After vehicle sold.
   $field['vwc_status'] = array('tinyint', '1', '', 'Status', '');    //array(1 => 'Active',2 => 'Inactive');

   return $field;
}

// vehicles_employees.
function getFields_vehicles_employees($id = true, $action = '', $validation = '')
{
   if ($id)
      $field['vemp_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', '', '');
   $field['vemp_fk_employees'] = array('int', '11', 'unsigned NOT NULL', 'Employee', 'required');
   $field['vemp_fk_vehicles'] = array('int', '11', 'unsigned NOT NULL', 'Vehicle', 'required');
   $field['vemp_is_default'] = array('tinyint', '1', '', 'Is Default', 'required');    //array(1 => 'Active',2 => 'Inactive');

   return $field;
}

// freight_charges
function getFields_freight_charges($id = true, $action = '', $validation = '')
{
   if ($id)
      $field['fc_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', '', '');

   // If the action is ADD.
   if (!isset($_POST['fc_id']))
   {
      $field['fc_fk_workcentres'] = array('int', '11', 'unsigned NOT NULL', 'Workcentres', 'required');
      $field['fc_fk_party_destinations'] = array('int', '11', 'unsigned NOT NULL', 'Party Destination', 'required');
      $field['fc_fk_vehicles'] = array('int', '11', 'unsigned NOT NULL', 'Vehicle', 'required');
   }
   else
   {
      $field['fc_fk_workcentres'] = array('int', '11', 'unsigned NOT NULL', 'Workcentres', '');
      $field['fc_fk_party_destinations'] = array('int', '11', 'unsigned NOT NULL', 'Party Destination', '');
      $field['fc_fk_vehicles'] = array('int', '11', 'unsigned NOT NULL', 'Vehicle', '');
   }

   $field['fc_rent'] = array('decimal', '13,2', '', 'Rent', 'numeric|xss_clean');
   $field['fc_add_rent'] = array('tinyint', '1', '', 'Add Rent', '');              // 1 => Rent will be added to the bill amount
   // 2 => Rent will not be added to the bill amount.

   $field['fc_bata'] = array('decimal', '13,2', '', 'Bata', 'numeric|xss_clean');
   $field['fc_add_bata'] = array('tinyint', '1', '', 'Add Bata', 'required');      // 1 => Bata will be added to the bill amount
   // 2 => Bata will not be added to the bill amount.

   $field['fc_loading'] = array('decimal', '13,2', '', 'Loading', 'numeric|xss_clean');
   $field['fc_add_loading'] = array('tinyint', '1', '', 'Add Loading', 'required'); // 1 => Loading will be added to the bill amount
   // 2 => Loading won't be added to the bill amount.
   return $field;
}

// inter_freight_charges
function getFields_inter_freight_charges($id = true, $action = '', $validation = '')
{
   if ($id)
      $field['ifc_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', '', '');

   if ($validation && ($validation == 'add'))
   {
      $field['ifc_fk_workcentres_from'] = array('int', '11', 'unsigned NOT NULL', 'From Workcentre', 'required|callback_is_freight_exist');
      $field['ifc_fk_workcentres_to'] = array('int', '11', 'unsigned NOT NULL', 'To Workcentre', 'required');
      $field['ifc_fkey_vehicles'] = array('int', '11', 'unsigned NOT NULL', 'Vehicle', 'required');
   }
   else
   {
      $field['ifc_fk_workcentres_from'] = array('int', '11', 'unsigned NOT NULL', 'From Workcentre', '');
      $field['ifc_fk_workcentres_to'] = array('int', '11', 'unsigned NOT NULL', 'To Workcentre', '');
      $field['ifc_fkey_vehicles'] = array('int', '11', 'unsigned NOT NULL', 'Vehicle', '');
   }

   $field['ifc_rent'] = array('decimal', '13,2', '', 'Rent', 'required|numeric|xss_clean');
   $field['ifc_add_rent'] = array('tinyint', '1', '', 'Add Rent', '');              // 1 => Rent will be added to the bill amount
   // 2 => Rent will not be added to the bill amount.

   $field['ifc_bata'] = array('decimal', '13,2', '', 'Bata', 'numeric|xss_clean');
   $field['ifc_add_bata'] = array('tinyint', '1', '', 'Add Bata', 'required');      // 1 => Bata will be added to the bill amount
   // 2 => Bata will not be added to the bill amount.

   $field['ifc_loading'] = array('decimal', '13,2', '', 'Loading', 'numeric|xss_clean');
   $field['ifc_add_loading'] = array('tinyint', '1', '', 'Add Loading', 'required'); // 1 => Loading will be added to the bill amount
   // 2 => Loading won't be added to the bill amount.
   return $field;
}

function getFields_item_category($id = true, $action = '', $validation = '')
{
   if ($id)
      $field['itmcat_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', '', '');
   $field['itmcat_name'] = array('varchar', '20', '', 'Category Name', 'required|max_length[20]|is_unique[item_category.itmcat_name]');
   $field['itmcat_status'] = array('tinyint', '1', '', 'Status', 'required');    //array(1 => 'Active',2 => 'Inactive');
   return $field;
}

function getFields_item_heads($id = true, $action = '', $validation = '')
{
   if ($id)
      $field['itmhd_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', '', '');
   $field['itmhd_fk_item_category'] = array('int', '11', 'unsigned NOT NULL', 'Item Category', 'required');
   $field['itmhd_name'] = array('varchar', '20', '', 'Head Name', 'required|max_length[20]|callback_itemheads');
   $field['itmhd_status'] = array('tinyint', '1', '', 'Status', 'required');    //array(1 => 'Active',2 => 'Inactive');

   return $field;
}

function getFields_units($id = true, $action = '', $validation = '')
{
   if ($id)
      $field['unt_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', '', '');
   $field['unt_batch'] = array('int', '11', '', 'Batch No:', 'required');
   $field['unt_name'] = array('varchar', '20', '', 'Unit Name', 'required|max_length[8]');
   $field['unt_parent'] = array('int', '11', '', 'Parent', '');
   $field['unt_is_parent'] = array('tinyint', '1', '', 'Batch parent', 'required');  //Is Parent of the current unit batch.  
   $field['unt_relation'] = array('decimal', '13,2', '', 'Relation with parent', 'numeric|xss_clean');
   return $field;
}

function getFields_items($id = true, $action = '', $validation = '')
{
   if ($id)
      $field['itm_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', '', '');
   $field['itm_fk_item_head'] = array('int', '11', 'unsigned NOT NULL', 'Item Head', 'required');
   $field['itm_name'] = array('varchar', '20', '', 'Item Name', 'required|max_length[20]');
   if ($validation && ($validation == 'edit'))
      $field['itm_fk_units'] = array('int', '11', 'unsigned NOT NULL', 'Default Unit of Item', 'required');
   else
      $field['itm_fk_units'] = array('int', '11', 'unsigned NOT NULL', 'Default Unit of Item', '');
   $field['itm_p_vat'] = array('decimal', '13,2', '', 'VAT on purchase', 'numeric|xss_clean');
   $field['itm_p_cess'] = array('decimal', '13,2', '', 'CESS on purchase', 'numeric|xss_clean');
   $field['itm_s_vat'] = array('decimal', '13,2', '', 'VAT on sale', 'numeric|xss_clean');
   $field['itm_s_cess'] = array('decimal', '13,2', '', 'CESS on sale', 'numeric|xss_clean');
   $field['itm_status'] = array('tinyint', '1', '', 'Status', '');    //array(1 => 'Active',2 => 'Inactive');
   return $field;
}

function getFields_item_units_n_rates($id = true, $action = '', $validation = '')
{
   if ($id)
      $field['iur_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', '', '');
   $field['iur_fk_workcentres'] = array('int', '11', 'unsigned NOT NULL', 'Workcentre', '');
   $field['iur_fk_items'] = array('int', '11', 'unsigned NOT NULL', 'Items', '');
   $field['iur_fk_units'] = array('int', '11', 'unsigned NOT NULL', 'Units', '');
   $field['iur_p_rate'] = array('decimal', '13,2', '', 'Purchase rate', '');
   $field['iur_s_rate'] = array('decimal', '13,2', '', 'Selling rate', '');
   return $field;
}

function getFields_opening_stock($id = true, $action = '', $validation = '')
{
   if ($id)
      $field['ostk_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', '', '');
   $field['ostk_fk_workcentre'] = array('int', '11', 'unsigned NOT NULL', 'Workcentre', '');
   $field['ostk_fk_items'] = array('int', '11', 'unsigned NOT NULL', 'Item', '');
   $field['ostk_date'] = array('datetime', '', '', 'Date', '');
   $field['ostk_qty'] = array('decimal', '13,2', '', 'Quantity', '');
   $field['ostk_fk_units'] = array('int', '11', 'unsigned NOT NULL', 'Unit', '');
   $field['ostk_rate'] = array('decimal', '13,2', '', 'Stock value', '');

   return $field;
}

function getFields_individual_rates($id = true, $action = '', $validation = '')
{
   if ($id)
      $field['indv_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', '', '');

   // Edit action
   if ($validation && ($validation == 'edit'))
   {
      $field['indv_fk_workcentres'] = array('int', '11', 'unsigned NOT NULL', 'Workcentre', '');
      $field['indv_fk_party_destinations'] = array('int', '11', 'unsigned NOT NULL', 'Destination', '');
      $field['indv_fk_items'] = array('int', '11', 'unsigned NOT NULL', 'Item', '');
      $field['indv_fk_units'] = array('int', '11', 'unsigned NOT NULL', 'Unit', '');
   }
   else
   {
      $field['indv_fk_workcentres'] = array('int', '11', 'unsigned NOT NULL', 'Workcentre', 'callback_checkNull');
      $field['indv_fk_party_destinations'] = array('int', '11', 'unsigned NOT NULL', 'Destination', 'callback_checkNull');
      $field['indv_fk_items'] = array('int', '11', 'unsigned NOT NULL', 'Item', 'required');
      $field['indv_fk_units'] = array('int', '11', 'unsigned NOT NULL', 'Unit', 'required');
   }

   $field['indv_p_rate'] = array('decimal', '13,2', '', 'Purchase Rate', 'numeric');
   $field['indv_s_rate'] = array('decimal', '13,2', '', 'Selling Rate', 'numeric');

   return $field;
}

function getFields_workcentre_rates($id = true, $action = '', $validation = '')
{
   if ($id)
      $field['wrt_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', '', '');

   // Edit action
   if ($validation && ($validation == 'edit'))
   {
      $field['wrt_fk_workcentres_from'] = array('int', '11', 'unsigned NOT NULL', 'Workcentre From', '');
      $field['wrt_fk_workcentres_to'] = array('int', '11', 'unsigned NOT NULL', 'Workcentre To', '');
      $field['wrt_fk_items'] = array('int', '11', 'unsigned NOT NULL', 'Item', '');
      $field['wrt_fk_units'] = array('int', '11', 'unsigned NOT NULL', 'Unit', '');
   }
   else
   {
      $field['wrt_fk_workcentres_from'] = array('int', '11', 'unsigned NOT NULL', 'Workcentre From', 'callback_checkNull');
      $field['wrt_fk_workcentres_to'] = array('int', '11', 'unsigned NOT NULL', 'Workcentre To', 'callback_checkNull');
      $field['wrt_fk_items'] = array('int', '11', 'unsigned NOT NULL', 'Item', 'required');
      $field['wrt_fk_units'] = array('int', '11', 'unsigned NOT NULL', 'Unit', 'required');
   }
   $field['wrt_s_rate'] = array('decimal', '13,2', '', 'Selling Rate', 'numeric|required');

   return $field;
}

function getFields_parties($id = true, $action = '', $validation = '')
{
   if ($id)
      $field['pty_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', '', '');
   $field['pty_name'] = array('varchar', '20', '', 'Party Name', 'required|max_length[20]');
   $field['pty_date'] = array('date', '', '', 'Date', '');
   $field['pty_phone'] = array('varchar', '20', '', 'Phone No', 'max_length[20]');
   $field['pty_email'] = array('varchar', '30', '', 'Email', 'valid_email|max_length[30]');
   $field['pty_status'] = array('tinyint', '1', '', 'Status', '');    //array(1 => 'Active',2 => 'Inactive');

   return $field;
}

function getFields_party_license_details($id = true, $action = '', $validation = '')
{
   if ($id)
      $field['pld_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', '', '');
   $field['pld_date'] = array('date', '', '', 'Date', 'required');
   $field['pld_firm_name'] = array('varchar', '50', '', 'Firm Name', 'required|is_unique[party_license_details.pld_firm_name]|max_length[50]');   // Registration name
   $field['pld_address'] = array('varchar', '250', '', 'Address', 'max_length[250]');
   $field['pld_phone'] = array('varchar', '20', '', 'Phone No', 'max_length[20]');
   $field['pld_email'] = array('varchar', '30', '', 'Email', 'max_length[30]|valid_email');
   $field['pld_tin'] = array('varchar', '20', '', 'Tin No', 'is_unique[party_license_details.pld_tin]|max_length[20]');
   $field['pld_licence'] = array('varchar', '20', '', 'Licence No', 'is_unique[party_license_details.pld_licence]|max_length[20]');
   $field['pld_cst'] = array('varchar', '20', '', 'CST', 'is_unique[party_license_details.pld_cst]|max_length[20]');
   $field['pld_status'] = array('tinyint', '1', '', 'Status', '');    //array(1 => 'Active',2 => 'Inactive');
   return $field;
}

function getFields_party_destinations($id = true, $action = '', $validation = '')
{
   if ($id)
      $field['pdst_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', '', '');
   $field['pdst_date'] = array('date', '', '', 'Date', '');
   $field['pdst_fk_party_license_details'] = array('int', '11', 'unsigned NOT NULL', 'Billing Name', '');
   $field['pdst_fk_parties'] = array('int', '11', 'unsigned NOT NULL', 'Party', '');
   $field['pdst_name'] = array('varchar', '20', '', 'Destination Name', 'required|max_length[20]');
   $field['pdst_phone'] = array('varchar', '20', '', 'Phone No', 'max_length[20]');
   $field['pdst_email'] = array('varchar', '30', '', 'Email', 'max_length[30]');
   $field['pdst_category'] = array('tinyint', '1', '', 'Category', 'required');    //array(1=>supplier, 2=>customer, 3=>both);
   $field['pdst_status'] = array('tinyint', '1', '', 'Status', '');    //array(1 => 'Active',2 => 'Inactive');

   return $field;
}

function getFields_party_vehicles($id = true, $action = '', $validation = '')
{
   if ($id)
      $field['pvhcl_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', '', '');
   $field['pvhcl_fk_parties'] = array('int', '11', 'unsigned NOT NULL', 'Party', '');
   $field['pvhcl_name'] = array('varchar', '20', '', 'Name', 'max_length[20]');
   $field['pvhcl_no'] = array('varchar', '20', '', 'No', 'required|max_length[20]');
   $field['pvhcl_length'] = array('decimal', '13,2', '', 'Length', 'numeric');
   $field['pvhcl_breadth'] = array('decimal', '13,2', '', 'Breadth', 'numeric');
   $field['pvhcl_height'] = array('decimal', '13,2', '', 'Height', 'numeric');
   $field['pvhcl_xheight'] = array('decimal', '13,2', '', 'X-Height', 'numeric');
   $field['pvhcl_status'] = array('tinyint', '1', '', 'Status', '');    //array(1 => 'Active',2 => 'Inactive');

   return $field;
}

function getFields_destination_workcentres($id = true, $action = '', $validation = '')
{
   if ($id)
      $field['dwc_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', '', '');
   $field['dwc_fk_workcentres'] = array('int', '11', 'unsigned NOT NULL', 'Workcentre', '');
   $field['dwc_fk_party_destinations'] = array('int', '11', 'unsigned NOT NULL', 'Destination', '');
   $field['dwc_date'] = array('datetime', '', '', 'Date', '');
   $field['dwc_ob'] = array('decimal', '13,2', '', 'O.B', '');
   $field['dwc_ob_mode'] = array('tinyint', '1', '', 'O.B Mode', '');    //array(1 => 'Credit',2 => 'Debt');
   $field['dwc_credit_lmt'] = array('decimal', '13,2', '', 'Credit Limit', '');
   $field['dwc_debt_lmt'] = array('decimal', '13,2', '', 'Debt Limit', '');
   $field['dwc_status'] = array('tinyint', '1', '', 'Status', '');    //array(1 => 'Active',2 => 'Inactive');
   return $field;
}

function getFields_party_vehicle_rents($id = true, $action = '', $validation = '')
{
   if ($id)
      $field['pvr_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', '', '');
   $field['pvr_fk_workcentres'] = array('int', '11', 'unsigned NOT NULL', 'Workcentre', 'required');
   $field['pvr_fk_party_destinations'] = array('int', '11', 'unsigned NOT NULL', 'Destination', 'required');
   $field['pvr_fk_party_vehicles'] = array('int', '11', 'unsigned NOT NULL', 'Vehicle', 'required');
   $field['pvr_rent'] = array('decimal', '13,2', '', 'Freight Charge', 'required|numeric');
   $field['pvr_add_rent'] = array('tinyint', '1', '', 'Add rent', '');    //array(1 => Add to bill amount ,2 => Don't Add);
   return $field;
}


function getFields_purchase_billnumber_notax($id = true, $action = '', $validation = '')
{
   if ($id)
      $field['pbntx_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', '', '');
   $field['pbntx_fk_workcentres'] = array('int', '11', 'unsigned NOT NULL', 'Workcentre', 'required');
   $field['pbntx_no'] = array('int', '11', 'unsigned NOT NULL', 'Bill No', 'required');
   $field['pbntx_fyear'] = array('int', '11', 'unsigned NOT NULL', 'Financial Year', 'required');
   return $field;
}

function getFields_purchase_billnumber_tax($id = true, $action = '', $validation = '')
{
   if ($id)
      $field['pbtx_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', '', '');
   $field['pbtx_fk_workcentre_registration_details'] = array('int', '11', 'unsigned NOT NULL', 'Workcentre', 'required');
   $field['pbtx_no'] = array('int', '11', 'unsigned NOT NULL', 'Bill No', 'required');
   $field['pbtx_fyear'] = array('int', '11', 'unsigned NOT NULL', 'Financial Year', 'required');
   return $field;
}

function getFields_purchase_bill_head($id = true, $action = '', $validation = '')
{
   if ($id)
      $field['pbh_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', '', '');
   
   $field['pbh_datetime'] = array('datetime', '', '', 'Date', '');
   $field['pbh_fk_workcentres'] = array('int', '11', 'unsigned NOT NULL', 'Workcentre', 'required');
   $field['pbh_fk_party_destinations'] = array('int', '11', 'unsigned NOT NULL', 'Destination', 'required');
   $field['pbh_temp_party'] = array('varchar', '30', 'NULL', 'Party', 'max_length[30]');
   $field['pbh_fk_purchase_billnumber_tax'] = array('int', '11', 'unsigned NULL', 'Bill No', '');
   $field['pbh_fk_purchase_billnumber_notax'] = array('int', '11', 'unsigned NULL', 'Bill No', '');
   $field['pbh_ref_no'] = array('int', '11', 'NULL', 'Reference No:', 'integer');  
   $field['pbh_fk_party_vehicles'] = array('int', '11', 'unsigned NULL', 'Vehicle', '');
   $field['pbh_pty_veh_rent'] = array('decimal', '13,2', 'NULL', 'Vehicle Rent', 'numeric');
   $field['pbh_pty_veh_rent_declared'] = array('decimal', '13,2', 'NULL', 'Vehicle Rent Declared', '');
   $field['pbh_pty_add_rent'] = array('tinyint', '1', 'NULL', 'Add rent', '');    //array(1 => Add to bill amount ,2 => Don't Add);
   $field['pbh_pty_add_rent_declared'] = array('tinyint', '1', 'NULL', 'Add rent Declared', '');    //Declared.
   $field['pbh_fk_vehicles'] = array('int', '11', 'unsigned NULL', 'Vehicle', '');
   $field['pbh_temp_vehicle'] = array('varchar', '30', 'NULL', 'Vehicle', 'max_length[30]');
   $field['pbh_rent'] = array('decimal', '13,2', 'NULL', 'Vehicle Rent', 'numeric');
   $field['pbh_rent_declared'] = array('decimal', '13,2', 'NULL', 'Vehicle Rent Declared', '');
   $field['pbh_fk_driver'] = array('int', '11', 'unsigned NULL', 'Driver', '');
   $field['pbh_fk_driver_declared'] = array('int', '11', 'unsigned NULL', 'Driver Declared', 'required');
   $field['pbh_bata'] = array('decimal', '13,2', 'NULL', 'Bata', 'numeric');
   $field['pbh_bata_declared'] = array('decimal', '13,2', 'NULL', 'Bata Declared', 'numeric');
   $field['pbh_loading'] = array('decimal', '13,2', 'NULL', 'Loading Charge', 'numeric');
   $field['pbh_loading_declared'] = array('decimal', '13,2', 'NULL', 'Loading Charge Declared', '');
   $field['pbh_loading_mode'] = array('tinyint', '1', 'NULL', 'Loading Mode', '');    //array (1=>pay to each, 2=>Shared to all); 
   $field['pbh_loading_mode_declared'] = array('tinyint', '1', 'NULL', 'Loading Mode Declared', '');    
   $field['pbh_round_off'] = array('decimal', '13,2', 'NULL', 'Round Off', 'numeric');    
   $field['pbh_paid'] = array('decimal', '13,2', 'NULL', 'Paid', 'numeric');
   $field['pbh_remarks'] = array('varchar', '30', 'NULL', 'Remarks', 'max_length[200]');
   $field['pbh_status'] = array('tinyint','1','NOT NULL','Status',''); //array(1=>'Visited By Admin', 2=>'Not Visited', 3=>'Marked');
   return $field;
}

function getFields_purchase_bill_body($id = true, $action = '', $validation = '')
{
   if ($id)
      $field['pbb_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', '', '');
   $field['pbb_fk_purchase_bill_head'] = array('int', '11', 'unsigned NOT NULL', 'Purchase Bill Head', 'required');
   $field['pbb_fk_items'] = array('int', '11', 'unsigned NOT NULL', 'Item', 'required');
   $field['pbb_qty'] = array('decimal', '13,2', 'NULL', 'Quantity', 'numeric|required');
   $field['pbb_fk_units'] = array('int', '11', 'unsigned NOT NULL', 'Unit', 'required');
   $field['pbb_rate'] = array('decimal', '13,2', 'NULL', 'Rate', 'numeric|required');
   $field['pbb_rate_declared'] = array('decimal', '13,2', 'NULL', 'Vehicle Rent', 'numeric|required');
   $field['pbb_tax'] = array('decimal', '13,2', 'NULL', 'Vehicle Rent', 'numeric');
   $field['pbb_cess'] = array('decimal', '13,2', 'NULL', 'Vehicle Rent', 'numeric');
   
   if ($action == 'DB Creation') // On creating database and tables
   {
      $field['INDEX'][] = array('f_key' => 'pbb_fk_purchase_bill_head', 'ref_tbl' => 'purchase_bill_head', 'ref_fld' => 'pbh_id', 'DELETE' => 'CASCADE', 'UPDATE' => 'CASCADE');
   }
   
   return $field;
}

function getFields_purchase_bill_loaders($id = true, $action = '', $validation = '')
{
   if ($id)
      $field['pbl_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', '', '');
   $field['pbl_fk_purchase_bill_head'] = array('int', '11', 'unsigned NOT NULL', 'Purchase Bill Head', 'required');
   $field['pbl_loader'] = array('int', '11', 'unsigned NOT NULL', 'Loader', 'required');
   $field['pbl_loading_charge'] = array('decimal', '13,2', 'NULL', 'Loading Charge.', 'numeric');
   
   if ($action == 'DB Creation') // On creating database and tables
   {
      $field['INDEX'][] = array('f_key' => 'pbl_fk_purchase_bill_head', 'ref_tbl' => 'purchase_bill_head', 'ref_fld' => 'pbh_id', 'DELETE' => 'CASCADE', 'UPDATE' => 'CASCADE');
   }
   
   return $field;
}

function getFields_purchase_bill_additives($id = true, $action = '', $validation = '')
{
   if ($id)
      $field['pba_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', '', '');
   $field['pba_fk_purchase_bill_head'] = array('int', '11', 'unsigned NOT NULL', 'Purchase Bill Head', 'required');
   $field['pba_name'] = array('varchar', '25', 'NULL', 'Additive Name', 'required|max_length[25]');
   $field['pba_amount'] = array('decimal', '13,2', 'NULL', 'Additive Value', 'numeric|required');
   
   if ($action == 'DB Creation') // On creating database and tables
   {
      $field['INDEX'][] = array('f_key' => 'pba_fk_purchase_bill_head', 'ref_tbl' => 'purchase_bill_head', 'ref_fld' => 'pbh_id', 'DELETE' => 'CASCADE', 'UPDATE' => 'CASCADE');
   }
   
   return $field;
}

function getFields_purchase_bill_deductives($id = true, $action = '', $validation = '')
{
   if ($id)
      $field['pbd_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', '', '');
   $field['pbd_fk_purchase_bill_head'] = array('int', '11', 'unsigned NOT NULL', 'Purchase Bill Head', 'required');
   $field['pbd_name'] = array('varchar', '25', 'NULL', 'Deductive Name', 'required|max_length[25]');
   $field['pbd_amount'] = array('decimal', '13,2', 'NULL', 'Deductive Value', 'numeric|required');
   
   if ($action == 'DB Creation') // On creating database and tables
   {
      $field['INDEX'][] = array('f_key' => 'pbd_fk_purchase_bill_head', 'ref_tbl' => 'purchase_bill_head', 'ref_fld' => 'pbh_id', 'DELETE' => 'CASCADE', 'UPDATE' => 'CASCADE');
   }
   
   return $field;
}



function getFields_sale_billnumber_notax($id = true, $action = '', $validation = '')
{
   if ($id)
      $field['sbntx_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', '', '');
   $field['sbntx_fk_workcentres'] = array('int', '11', 'unsigned NOT NULL', 'Workcentre', 'required');
   $field['sbntx_no'] = array('int', '11', 'unsigned NOT NULL', 'Bill No', 'required');
   $field['sbntx_fyear'] = array('int', '11', 'unsigned NOT NULL', 'Financial Year', 'required');
   return $field;
}

function getFields_sale_billnumber_tax($id = true, $action = '', $validation = '')
{
   if ($id)
      $field['sbtx_id'] = array('int', '11', 'unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT', '', '');
   $field['sbtx_fk_workcentre_registration_details'] = array('int', '11', 'unsigned NOT NULL', 'Workcentre', 'required');
   $field['sbtx_no'] = array('int', '11', 'unsigned NOT NULL', 'Bill No', 'required');
   $field['sbtx_fyear'] = array('int', '11', 'unsigned NOT NULL', 'Financial Year', 'required');
   return $field;
}

/**
 * 
 * @param type $id
 * @param type $action
 * @return string
 *  The data to this table will be inserted at when the data to be inserted to the table employee.
  on insert:
  1.	it is possible that an employee can work at a time under multiple firms or multiple work centres. so when adding an employee, it should be possible to put him under any work_centre independent of which is the firm.

 */
function getFields_tasks($id = true, $action = '', $validation = '')
{
   $caseFunc = getCaseFunc();
   if ($id)
      $field['tsk_id'] = array('int', '', 'PRIMARY KEY NOT NULL AUTO_INCREMENT', '', '');
   $field['tsk_name'] = array('varchar', '50', '', 'Task Name', 'required');
   $field['tsk_description'] = array('varchar', '100', '', 'Task Description', 'required');
   $field['tsk_url'] = array('varchar', '50', '', 'Url', 'required');
   $field['tsk_parent'] = array('int', '', 'NULL', 'Parent', 'callback_checkParent');
   $field['tsk_pos'] = array('tinyint', '4', '', 'Position', 'required|callback_checkpos');
   $field['tsk_display'] = array('tinyint', '1', '', 'Display', '');
   $field['tsk_status'] = array('tinyint', '1', '', 'Status', '');
//    if ($action == 'DB Creation') // On creating database and tables
//    {
//        $field['INDEX'][] = array('f_key' => 'TSK_PARENT', 'ref_tbl' => 'TASKS', 'ref_fld' => 'TSK_ID', 'DELETE' => 'RESTRICT', 'UPDATE' => 'CASCADE');
//    }
   return $field;
}

function getFields_user_tasks($id = true, $action = '', $validation = '')
{
   $caseFunc = getCaseFunc();
   if ($id)
      $field['utsk_id'] = array('int', '', 'PRIMARY KEY NOT NULL AUTO_INCREMENT', '', '');
   $field['utsk_fk_auth_users'] = array('int', '11', '', 'Employees', 'required');
   $field['utsk_fk_tasks'] = array('int', '11', '', 'Tasks', 'callback_hasTask');
   return $field;
}

/**
 * To keep form input values on pagination
 * 
 * @param type $id
 * @param type $action
 * @return string
 */
function getFields_form_inputs($id = true, $action = '', $validation = '')
{
   $caseFunc = getCaseFunc();
//    if ($id)
//        $field['fip_id'] = array('int', '', 'PRIMARY KEY NOT NULL AUTO_INCREMENT', '', '');
   $field['fip_clsfunc'] = array('varchar', '100', '', '', '');
   $field['fip_values'] = array('TEXT', '', '', '', '');
   return $field;
}

?>
