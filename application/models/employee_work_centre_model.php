<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Employee_work_centre_model extends my_model
{

   function __construct()
   {
      parent::__construct();
      $this->loadTable(getTables('employee_work_centre'));
      $this->p_key = 'ewp_id';
      $this->nameField = '';
      $this->statusField = '';
   }

   function get_employee_status()
   {
      return $this->get_status();
   }

   // If you want to make any changes to this function,
   // you must apply the corresponding chage to the function employees_model::get_employee_category()
   function get_employee_category($cat = 1, $index = '', $includAdmin = true)
   {
      if (($cat == 1) && $includAdmin) // Admin
         $empcat[1] = "Admin";
      if ($cat == 1 || $cat == 2) // Admin or Partner
         $empcat[2] = "Partner";
      $empcat[3] = "Staff";
      $empcat[4] = "Driver";
      $empcat[5] = "Loader";
      if ($index)
         return $empcat[$index];
      return $empcat;
   }

   function is_user_registered_in_workcentre($user_id, $workcentre_id, $firm_id = '', $wcntr_status = '', $ewp_status = '')
   {
      $this->db->select('ewp_id');
      $this->db->from('employee_work_centre,workcentres,firms');
      $this->db->where('ewp_fk_auth_users', $user_id);
      $this->db->where('ewp_fk_workcentres', $workcentre_id);
      $this->db->where('wcntr_id = ewp_fk_workcentres');
      $this->db->where('firm_id = wcntr_fk_firms');

      if ($firm_id)
         $this->db->where('wcntr_fk_firms', $firm_id);

      if ($wcntr_status)
      {
         $this->db->where('wcntr_status', $wcntr_status);

         // if active
         if ($wcntr_status == 1)
         {
            // A workcentre is considered as active only when both the workcentre itself and its parent "firm" are active.
            $this->db->where('firm_status', 1);
         }
      }

      if ($ewp_status)
         $this->db->where('ewp_status', $ewp_status);

      $result = $this->db->get();
      //print_r($result->row_array());
      return $result->row_array();
   }

   /**
    * 
    * @param type $user_id         :   Current user
    * @param type $returnFormat    :   There are three return formats
    *                                   options: return an option array for <select> element.
    *                                   ids    : returns array of ids as array(1,2,3,4);
    *                                   all    : returns all query result.
    * @param type $wcntr_status
    * @param type $ewp_status
    * @return type
    */
   function getUsersWorkcentres($user_id, $firm_id = '', $returnFormat = 'options', $wcntr_status = ACTIVE, $ewp_status = ACTIVE)
   {
      $this->db->from("$this->table,workcentres");
      $this->db->select('workcentres.*');

      $this->db->where('ewp_fk_auth_users', $user_id);

      if ($firm_id)
         $this->db->where('wcntr_fk_firms', $firm_id);

      if ($wcntr_status)
         $this->db->where('wcntr_status', $wcntr_status);

      if ($ewp_status)
         $this->db->where('ewp_status', $ewp_status);

      $this->db->where('wcntr_id = ewp_fk_workcentres');

      $this->db->order_by('wcntr_name', "asc");

      $result = $this->db->get();

      $result = $result->result_array();

      // Formating result.
      if ($returnFormat == 'options')
         return $this->make_options($result, 'wcntr_id', 'wcntr_name');
      else if ($returnFormat == 'ids')
         return $this->getIdsFromQueryResult($result, 'wcntr_id');
      else if ($returnFormat == 'all')
         return $result;
   }

   /**
    * 
    * @param type $user_id         :   Current User
    * @param type $emp_category    :   The employees in which category has to be get.
    * @param type $returnFormat    :   There are three return formats
    *                                   options: return an option array for <select> element.
    *                                   ids    : returns array of ids as array(1,2,3,4);
    *                                   all    : returns all query result.
    * @param type $workcentres     :   The employee in which workcentre has to be get. The user must be a member of the workcentre.
    *                                   Its value can be a single wcntr_id or array of workcentre ids as array(1,5,2)
    * @param type $firm_id         :   The employees in which firm has to be get.
    * @param type $incluedUser     :   TRUE: The rusult will contain the user.
    *                                   FALSE: The rusult won't contain the user.
    * @param type $emp_status      :   The status of the employee. Default is 1 (Active).
    * @param type $wcntr_status    :   The status of the workcentre. Default is 1 (Active).
    * @param type $ewp_status      :   The status of the Employee in the workcentre. Default is 1 (Active).
    */
   function getUsersEmployees($user_id, $emp_category = '', $returnFormat = 'options', $workcentres = '', $firm_id = '', $incluedUser = false, $emp_status = ACTIVE, $wcntr_status = ACTIVE, $ewp_status = ACTIVE)
   {
      // If no workcenters, getting all the workcentres of the user
      if (!$workcentres)
      {
         $workcentres = $this->getUsersWorkcentres($user_id, $firm_id, 'ids', $wcntr_status, ACTIVE);
      }
      else
      {
         // Checking is the user is the member of the given workcentre, if not, returns NULL.(Because it is a logical error).
         if (is_array($workcentres))
         {
            foreach ($workcentres as $wcntr_id)
               if (!$this->is_user_registered_in_workcentre($user_id, $wcntr_id, '', '', ACTIVE))
                  return NULL;
         }
         else
         {
            if (!$this->is_user_registered_in_workcentre($user_id, $workcentres, '', '', ACTIVE))
               return NULL;
         }
      }

      $this->db->from("$this->table,employees,workcentres");
      $this->db->select('employees.*');

      // All employees except the user.
      if (!$incluedUser)
         $this->db->where('emp_id != ', $user_id);

      if ($emp_category)
         $this->db->where('emp_category', $emp_category);

      if (is_array($workcentres))
      {
         $str = $this->array_query($workcentres, 'ewp_fk_workcentres');
         $this->db->where($str);
      }
      else
         $this->db->where('ewp_fk_workcentres', $workcentres);

      if ($firm_id)
         $this->db->where('wcntr_fk_firms', $firm_id);

      if ($emp_status)
         $this->db->where('emp_status', $emp_status);
      if ($wcntr_status)
         $this->db->where('wcntr_status', $wcntr_status);
      if ($ewp_status)
         $this->db->where('ewp_status', $ewp_status);

      $this->db->where('emp_id = ewp_fk_auth_users');
      $this->db->where('wcntr_id = ewp_fk_workcentres');
      $this->db->order_by('emp_name', "asc");

      $result = $this->db->get();

      $result = $result->result_array();

      // Formating result.
      if ($returnFormat == 'options')
         return $this->make_options($result, 'emp_id', 'emp_name');
      else if ($returnFormat == 'ids')
         return $this->getIdsFromQueryResult($result, 'emp_id');
      else if ($returnFormat == 'all')
         return $result;
   }

   // This function is also called by ajax when adding user tasks. 
   // 1. employees/getEmployees
   // 2. user_tasks/add
   function get_workcentres_employees_option($input, $ewp_status = ACTIVE, $wcntr_status = ACTIVE, $emp_status = ACTIVE)
   {
      $this->db->from('employees,employee_work_centre,workcentres');
      $this->db->select('emp_id,emp_name');
      if ($input['emp_category'])
         $this->db->where('emp_category', $input['emp_category']);

      if ($ewp_status)
         $this->db->where('ewp_status', $ewp_status); // Active in workcentre.
      if ($wcntr_status)
         $this->db->where('wcntr_status', $wcntr_status); //Active workcentres.
      if ($emp_status)
         $this->db->where('emp_status', $emp_status); //Active Employees only.

      if ($input['workcentres'])
      {
         $str = $this->array_query($input['workcentres'], 'ewp_fk_workcentres');
         $this->db->where($str);
      }
      $this->db->where("emp_id = ewp_fk_auth_users");
      $this->db->where('wcntr_id = ewp_fk_workcentres');
      $this->db->order_by('emp_name', 'asc');
      $query = $this->db->get();
      $result = $query->result_array();

      $options = $this->make_options($result, 'emp_id', 'emp_name');
      //echo "<br>".$this->db->last_query()."<br>";        
      return $options;
   }

   function getEmployeesInWorkcentres($wcntr_id, $option = TRUE, $emp_category = '', $ewp_status = 1, $wcntr_status = 1, $emp_status = 1)
   {
      $this->db->from('employees,employee_work_centre,workcentres');

      if (is_array($wcntr_id))
      {
         $str = $this->array_query($wcntr_id, 'ewp_fk_workcentres');
         $this->db->where($str);
      }
      else
      {
         $this->db->where('ewp_fk_workcentres', $wcntr_id);
      }

      if ($emp_category)
         $this->db->where('emp_category', $emp_category);

      if ($ewp_status)
         $this->db->where('ewp_status', $ewp_status); // Active in workcentre.
      if ($wcntr_status)
         $this->db->where('wcntr_status', $wcntr_status); //Active workcentres.
      if ($emp_status)
         $this->db->where('emp_status', $emp_status); //Active employee.
      
      $this->db->where("emp_id = ewp_fk_auth_users");
      $this->db->where('wcntr_id = ewp_fk_workcentres');
      $this->db->order_by('emp_name', 'asc');
      $query = $this->db->get();
      $result = $query->result_array();
      
      if($option)
         $result = $this->make_options($result, 'emp_id', 'emp_name');
      
      return $result;
   }

   /**
    * Function return array of workcentres where employee is available.
    * @param type $emp_id
    * @return type
    */
   function getEmployeesWorkcentres($emp_id, $firm_id = '', $field = 'wcntr_id', $order_by = 'wcntr_id', $ewp_status = 1, $wcntr_status = 1)
   {
      $this->db->from('workcentres,' . $this->table);
      if ($field != 'All')
         $this->db->select($field);
      $this->db->where('ewp_fk_auth_users', $emp_id);
      if ($ewp_status)
         $this->db->where('ewp_status', $ewp_status); // Active in workcentre.
      if ($wcntr_status)
         $this->db->where('wcntr_status', $wcntr_status); // Active workcentres.
      $this->db->where('wcntr_id = ewp_fk_workcentres');
      if ($firm_id)
         $this->db->where('wcntr_fk_firms', $firm_id);
      $this->db->order_by($order_by);
      $result = $this->db->get();
      $data = $result->result_array();

      if ($field == 'All')
         return $data;
      else
      {
         $arr = array();
         foreach ($data as $val)
            $arr[] = $val[$field];
         return $arr;
      }
   }

   function is_users_employee($user_id, $emp_id, $firm_id = '')
   {
      // Getting users workcentre ids.
      $users_workcentres = $this->getEmployeesWorkcentres($user_id);

      // Checking is employee is registered in users workcentre.
      foreach ($users_workcentres as $wcntr_id)
         if ($this->is_user_registered_in_workcentre($emp_id, $wcntr_id))
         {
            return true;
         }

      return FALSE;
   }

   function index($input, $empcat, $workcentres, $wlog_ref_id, $num_rows = false)
   {

      $this->db->from('employees,employee_work_centre');

      if ($wlog_ref_id)
         $this->db->where($this->p_key, $wlog_ref_id);

      #WHERE
      if (ifSetInput($input, 'emp_id'))
      {
         $this->db->where('emp_id', ifSetInput($input, 'emp_id'));
      }


      if (ifSetInput($input, 'emp_name'))
      {
         $this->db->where('emp_name', ifSetInput($input, 'emp_name'));
      }

      if (ifSetInput($input, 'ewp_status'))
      {
         $this->db->where('ewp_status', ifSetInput($input, 'ewp_status'));
      }

      if (ifSetInput($input, 'f_ewp_date'))
      {
         $this->db->where('ewp_date >= ', getSqlDate(ifSetInput($input, 'f_ewp_date')));
      }


      if (ifSetInput($input, 't_ewp_date'))
      {
         $this->db->where('ewp_date <= ', getSqlDate(ifSetInput($input, 't_ewp_date')));
      }

      if (ifSetInput($input, 'emp_category'))
      {
         $str = $this->array_query(ifSetInput($input, 'emp_category'), 'emp_category');
         $this->db->where($str);
      }


      // Non-admins are not accessible of Admin details
      if ($empcat != 1)    // Not admin
         $this->db->where('emp_category != 1');

      // Partners details are accessible only by Admins and partners. Others are not.
      if (($empcat != 1) && ($empcat != 2))// Not Admin && Not Partner
         $this->db->where('(emp_category != 1 AND emp_category != 2)');


      // User selected atleast one workcentre.
      if (ifSetInput($input, 'ewp_fk_workcentres'))
      {
         $str = $this->array_query(ifSetInput($input, 'ewp_fk_workcentres'), 'ewp_fk_workcentres');
         $this->db->where($str);
      }

      // Else all workcentres in which user has registered.
      else if (is_array($workcentres) && $workcentres)
      {
         $workcentres = array_flip($workcentres);
         $str = $this->array_query($workcentres, 'ewp_fk_workcentres');
         $this->db->where($str);
      }





      $this->db->where('ewp_fk_auth_users = emp_id');

      $this->db->order_by('ewp_date', 'asc');
      $this->db->order_by('emp_category', 'asc');
      $this->db->order_by('emp_name', 'asc');

      if ($num_rows)
      {
         $query = $this->db->get();
         return count($query->result_array());
      }


      if ($input['PER_PAGE'])
         $query = $this->db->get('', $input['PER_PAGE'], $input['offset']);
      else
         $query = $this->db->get();
      $result = $query->result_array();

//        echo "<br>".$this->db->last_query();echo "<br>";

      return $result;
   }

   function reports_OB($wcntr_id, $from, $to)
   {
      $this->db->from("employees,$this->table,workcentres");
      $emp_categories = $this->get_employee_category();

      $bs = " (CASE ";
      $bs .= " when ewp_ob_mode = 1 then 'Creditors' ";
      $bs .= " when ewp_ob_mode = 2 then 'Debtors' ";
      $bs .= " else 'UNKNOWN' ";
      $bs .= " END) ";

      $emp_cat = " (CASE ";
      $emp_cat .= " when emp_category = 1 then '$emp_categories[1]' ";
      $emp_cat .= " when emp_category = 2 then '$emp_categories[2]' ";
      $emp_cat .= " when emp_category = 3 then '$emp_categories[3]' ";
      $emp_cat .= " when emp_category = 4 then '$emp_categories[4]' ";
      $emp_cat .= " when emp_category = 5 then '$emp_categories[5]' ";
      $emp_cat .= " END) ";

      $select = 'ewp_id as ID, ';
      $select .= "'$this->table' as TBL, ";
      $select .= " ewp_date as DATE, ";
      $select .= "ewp_ob_mode as ACC_TYPE, ";       //  ACC_TYPE = 1 is Income/Credit. ACC_TYPE = 2 is Expense/Debt.
      $select .= "CONCAT(CONCAT(CONCAT($emp_cat,': ' ),emp_name),' O.B.') as DESCRIPTION, ";

      // Category for Balance Sheet. It must match with any of the 'Sub_category' described in my_controller/get_BS_Categories()
      $select .= " $bs as BS, ";

      // Category for Profit & Loss.
      $select .= " '' as PL, ";

      $select .= 'wcntr_name as WORKCENTRE, ';
      $select .= 'ewp_ob as AMOUNT';

      $this->db->select($select, FALSE);

      if (is_array($wcntr_id))
      {
         $str = $this->array_query($wcntr_id, 'ewp_fk_workcentres');
         $this->db->where($str);
      }
      else
         $this->db->where("ewp_fk_workcentres", $wcntr_id);

      if ($from)
         $this->db->where('emp_date >= ', getSqlDate($from));

      if ($to)
         $this->db->where('emp_date <= ', getSqlDate($to));

      $this->db->where("ewp_ob > ", 0);
      $this->db->where("emp_id = ewp_fk_auth_users");
      $this->db->where("wcntr_id = ewp_fk_workcentres");

      $this->db->order_by('ewp_date', 'asc');
      $this->db->order_by('wcntr_name', 'asc');
      $query = $this->db->get();
      $result = $query->result_array();

//        echo "<br>".$this->db->last_query()."<br>";
//        echo "<br>";print_r($result);

      return $result;
   }

   function reports($wcntr_id, $from, $to)
   {
      $data = $this->reports_OB($wcntr_id, $from, $to);
      return $data;
   }

}

?>