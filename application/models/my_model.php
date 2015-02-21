<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		Md Emran Hasan (phpfour@gmail.com)
 * @link		http://phpfour.com
 */
class my_model extends CI_Model
{

   /**
    * The name of the associate table name of the Model object
    * @var string
    * @access public
    */
   var $table = NULL;

   /**
    * Container for the fields of the table that this model gets from persistent storage (the database).
    *
    * @var array
    * @access public
    */
   var $fields = array();

   /**
    * The name of the ID field for this Model.
    *
    * @var string
    * @access public
    */
   var $p_key = NULL;

   /**
    * Container for the data that this model gets from persistent storage (the database).
    *
    * @var array
    * @access public
    */
   var $data = array();

   /**
    * Name of the field to sort
    *
    * @var unknown_type
    * @access public
    */
   var $nameField = null;

   /**
    * Name of the field determinig the status
    *
    * @var unknown_type
    * @access public
    */
   var $statusField = null;

   /**
    * The number of records returned by the last query
    *
    * @access private
    * @var int
    */
   var $__numRows = null;

   /**
    * Constructor
    *
    * @access public
    */
   function __construct()
   {
      parent::__construct();
   }

   /**
    * Load the associated database table.
    *
    * @author : "Shihabu Rahman K" <shihab@levoirsolutions.com>
    * @access public
    */
   function loadTable($table)
   {
      $this->table = $table;
      $this->fields = $this->db->list_fields($table);
   }

   /**
    * 
    * @param type $tables : Array of tables to be checked for its existance in database.
    * @return type
    */
   function checkForTables($tables = array())
   {
      $missed = array(); // Variable to store missed tables.

      foreach ($tables as $key => $tbl)
      {
         $query = $this->db->query("SHOW TABLES LIKE '$tbl'");

         // If table not found in database.
         if (!$query->row_array())
            $missed[$key] = $tbl;
      }

      return $missed;
   }

   function getTable()
   {
      return $this->table;
   }

   /**
    * Inserts a new record in the database.
    *
    * @author : "Shihabu Rahman K" <shihab@levoirsolutions.com>
    * @return boolean success
    * @access public
    */
   function insert($data = null, $table = '')
   {
      if ($data == null)
      {
         return FALSE;
      }

      $this->data = $data;
      $this->data['create_date'] = date("Y-m-d H:i:s");

      $TABLE = $table ? $table : $this->table;

      foreach ($this->data as $key => $value)
      {
         if (array_search($key, $this->fields) === FALSE)
         {
            unset($this->data[$key]);
         }
      }
      $this->db->insert($TABLE, $this->data);
      $insert_id = $this->db->insert_id();

//        echo "<br>".$this->db->last_query();
      return $insert_id;
   }

   /**
    * Saves model data to the database.
    *
    * @author : "Shihabu Rahman K" <shihab@levoirsolutions.com>
    * @return boolean success
    * @access public
    */
   function save($data = null, $id = null, $table = '')
   {
      if (!$data)
         return FALSE;

      $this->data = $data;


      $TABLE = $table ? $table : $this->table;


      foreach ($this->data as $key => $value)
      {
         if (array_search($key, $this->fields) === FALSE)
         {
            unset($this->data[$key]);
         }
      }

      if ($id)
      {
         $this->db->where($this->p_key, $id);
         $this->db->update($TABLE, $this->data);
         return $id;
      }
      else
      {
         $this->db->insert($TABLE, $this->data);
         return $this->db->insert_id();
      }
      
      //echo $this->db->last_query();
   }

   /**
    * 
    * @param type $data    : The new data to set
    * @param type $where   : Conditions as array/string. Eg:array('id' => $id, 'name'=> $name) or  "id = 4".
    * @param type $table
    */
   function update_where($data, $where = '', $table = '')
   {
      $TABLE = $table ? $table : $this->table;
      $this->db->update($TABLE, $data, $where);

      // Return primary key if existing one.
      return $this->getId($where);
   }

   function delete_where($where = '', $table = '')
   {
      $TABLE = $table ? $table : $this->table;
      return $this->db->delete($TABLE, $where);
   }

   function delete_where2($where = '', $table = '')
   {
      // Getting primary key if existing one, before it is being delete.
      $p_key = $this->getId($where);

      $TABLE = $table ? $table : $this->table;
      $this->db->delete($TABLE, $where);

      // Return primary key.
      return $p_key;
   }

   /**
    * Function returns the value of the NAME Field by recieving the primary key value.
    * @param type $id 
    * @return type
    */
   function getNameById($id)
   {
      $name = '';

      if ($this->nameField)
      {
         $row = $this->getById($id);
         if (isset($row[$this->nameField]))
            $name = $row[$this->nameField];
      }

      return $name;
   }

   /**
    * Removes record for given id. If no id is given, the current id is used. Returns true on success.
    *
    * @author : "Shihabu Rahman K" <shihab@levoirsolutions.com>
    * @return boolean True on success
    * @access public
    */
   function remove($id = null, $table = '')
   {
      $TABLE = $table ? $table : $this->table;

      if ($id)
      {
         if ($this->db->delete($TABLE, array($this->p_key => $id)))
            return true;
         else
            return false;
      }
      else
      {
         return false;
      }
   }

   function get_status()
   {
      return array(1 => 'Active', 2 => 'Inactive');
   }

   function get_account_type($index)
   {
      if ($index == 1)
         return array(1 => 'Credit', 2 => 'Debt');
      else if ($index == 2)
         return array(1 => 'Cr.', 2 => 'Dr.');
      else if ($index == 3)
         return array(1 => 'Income', 2 => 'Expense');
   }

   /**
    * Returns the last query that was run (the query string, not the result).
    *
    * @author : "Shihabu Rahman K" <shihab@levoirsolutions.com>
    * @return string SQL statement
    * @access public
    */
   function lastQuery()
   {
      return $this->db->last_query();
   }

   /**
    * Returns the number of rows returned from the last query.
    *
    * @author : "Shihabu Rahman K" <shihab@levoirsolutions.com>
    * @return int
    * @access public
    */
   function getNumRows()
   {
      return $this->__numRows;
   }

   /**
    * Returns a single row
    *
    * @author : "Shihabu Rahman K" <shihab@levoirsolution.com>
    * @return int
    * @access public
    */
   function get_row($where = array(), $or_where = array(), $table = '')
   { #		where $or_where	=	array(	'EMPCAT_ID'=>array(16,11),'EMP_STATUS'=>array(11,12))
      #							Produce a query; OR EMPCAT_ID=16 OR EMPCAT_ID=11 OR EMP_STATUS=11 OR EMP_STATUS=12
      #		OR	$or_where	=	array('EMPCAT_ID'=>16);
      $TABLE = $table ? $table : $this->table;

      if ($where)
         $this->db->where($where);
      if ($or_where)
         foreach ($or_where as $field => $fieldset)
         {
            if (is_array($fieldset))
            {
               foreach ($fieldset as $value)
                  $this->db->or_where($field, $value);
            }
            else
               $this->db->or_where($field, $fieldset);
         }
      $result = $this->db->get($TABLE);

      //echo $this->db->last_query()."<br><br>";
      return $result->row_array();
   }

   function get_row2($from = '', $select = '', $where = '')
   {
      $from = $from ? : $this->table;
      $this->db->from($from);

      if ($select)
         $this->db->select($select);

      if ($where)
         $this->db->where($where);
      $result = $this->db->get();

      //echo $this->db->last_query()."<br><br>";
      return $result->row_array();
   }

   function get_data($select = '', $where = array(), $or_where = array(), $table = '', $order_by = '')
   { #		where $or_where	=	array(	'EMPCAT_ID'=>array(16,11),
      #									'EMP_STATUS'=>array(11,12))
      #							Produce a query; OR EMPCAT_ID=16 OR EMPCAT_ID=11 OR EMP_STATUS=11 OR EMP_STATUS=12
      #		OR	$or_where	=	array('EMPCAT_ID'=>16);
      $TABLE = $table ? $table : $this->table;
      $ORDER_BY = $order_by ? $order_by : $this->nameField;

      if ($select)
         $this->db->select($select);
      if ($where)
         $this->db->where($where);
      if ($or_where)
         foreach ($or_where as $field => $fieldset)
         {
            if (is_array($fieldset))
            {
               foreach ($fieldset as $value)
                  $this->db->or_where($field, $value);
            }
            else
               $this->db->or_where($field, $fieldset);
         }
      if ($ORDER_BY)
         $this->db->order_by($ORDER_BY, "asc");
      $result = $this->db->get($TABLE);

      // echo $this->db->last_query();

      return $result->result_array();
   }

   function activate($id)
   {
      if (!$this->statusField)
         return false;
      $this->save(array($this->statusField => 1), $id);
      return true;
   }

   function deactivate($id)
   {
      if (!$this->statusField)
         return false;
      $this->save(array($this->statusField => 2), $id);
      return true;
   }

   function is_active($id)
   {
      if (!$this->statusField)
         return false;
      $where[$this->p_key] = $id;
      $where[$this->statusField] = 1;
      $row = $this->get_row($where);
      if ($row)
         return TRUE;
      return FALSE;
   }

   function is_inactive($id)
   {
      if (!$this->statusField)
         return false;
      $where[$this->p_key] = $id;
      $where[$this->statusField] = 2;
      $row = $this->get_row($where);
      if ($row)
         return TRUE;
      return FALSE;
   }

   /**
    * 
    * @param type $id = id value
    * @param type $field= name of the id field.
    * @param type $table = name of the table.
    * @return type
    */
   function getById($id, $field = '', $table = '')
   {

      $TABLE = $table ? $table : $this->table;
      $FIELD = $field ? $field : $this->p_key;
      if (is_array($id))
      {
         $where = $this->array_query($id, $FIELD);
         return $this->get_data('', $where, '', $TABLE);
      }
      else
      {
         $where[$FIELD] = $id;
         return $this->get_row($where, '', $TABLE);
      }
   }
   
   

   function getFieldById($id, $field, $table = '')
   {
      $data = $this->getById($id, '', $table);
      if (isset($data[$field]))
         return $data[$field];
      return '';
   }

   function getId($where)
   {
      if (!$this->p_key)
         return '';
      $id = '';
      $data = $this->get_row($where);

      if (isset($data[$this->p_key]))
         $id = $data[$this->p_key];

      return $id;
   }

   function getIds($where = '', $table = '', $p_key = '')
   {
      $P_KEY = $p_key ? $p_key : $this->p_key;
      if (!$P_KEY)
         return array();

      $data = $this->get_data($P_KEY, $where, '', $table, $P_KEY);
      $arr = array();

      foreach ($data as $val)
         $arr[] = $val[$P_KEY];
      return $arr;
   }

   function getIdsFromOption($option)
   {
      $ids = array();

      if (!$option)
         return $ids;

      foreach ($option as $id => $text)
         $ids[] = $id;

      return $ids;
   }

   function getOptionFromIds($ids, $table = '', $p_key = '', $name_field = '', $status = '')
   {
      if (!$ids)
         return array();

      $TABLE = $table ? $table : $this->table;
      $P_KEY = $p_key ? $p_key : $this->p_key;
      $NAME = $name_field ? $name_field : $this->nameField;

      if (!$TABLE || !$P_KEY || !$NAME)
         return array();

      // If status value without a status field; it should be returned.
      if ($status && !$this->statusField)
         return array();

      $this->db->from($TABLE);
      $this->db->select("$this->p_key,$this->nameField");
      $str = $this->array_query($ids, $P_KEY);
      $this->db->where($str);

      if ($status)
         $this->db->where($this->statusField, $status);

      $this->db->order_by($this->nameField);

      $data = $this->db->get();
      $data = $data->result_array();

      $options = $this->make_options($data, $P_KEY, $NAME);
      return $options;
   }

   /**
    * 
    * @param type $result      : A query result as follows;
    *                              array(
    *                                      [0] => array('emp_id' = 1, 'emp_name' = 'Shihab');
    *                                      [1] => array('emp_id' = 2, 'emp_name' = 'Mujeeb');
    *                                      [2] => array('emp_id' = 3, 'emp_name' = 'Sameer');
    *                              );
    * @param type $id_field    : Name of the id field, But you can give any field rather id field.
    *                             By default the primary key field will be selected.
    * @return array            : Return array of $id_field values. For eg:-
    *                                  if the $id_field = 'emp_id', then return array(1,2,3)
    */
   function getIdsFromQueryResult($result, $id_field = '')
   {
      if (!$result || !is_array($result))
         return '';

      $P_KEY = $id_field ? $id_field : $this->p_key;
      $ids = array();

      foreach ($result as $row)
         $ids[] = $row[$P_KEY];

      return $ids;
   }

   function getTableStatus($id)
   {  // Returns if no status field in Table
      if (!$this->statusField)
         return NULL;
      $details = $this->getById($id);
      if (isset($details[$this->statusField]))
         return $details[$this->statusField];
      return NULL;
   }

   function setTableStatus($status, $id)
   {
      // Returns if no status field in Table
      if (!$this->statusField)
         return NULL;
      $new_status[$this->statusField] = $status;
      $this->save($new_status, $id);
   }

   function toggleTableStatus($id)
   {
      $currentStatus = $this->getTableStatus($id);
      $newStatus = ($currentStatus == 1) ? 2 : 1;
      $this->setTableStatus($newStatus, $id);
   }

   function make_options($data, $key_field, $value_field)
   {
      $option = array();
      foreach ($data as $row)
         $option[$row[$key_field]] = $row[$value_field];
      return $option;
   }

   // Change the function name as makeOptionFromQueryResult
   function make_options_2($data, $key_field = '', $value_field = '')
   {
      $KEY = $key_field ? : $this->p_key;
      $VAL = $value_field ? : $this->nameField;
      $option = array();
      foreach ($data as $row)
         $option[$row[$KEY]] = $row[$VAL];
      return $option;
   }

   /**
    * The function formats the query result by replacing array index by the value of primary keys in the result.
    * For eg: 
    *    Suppose the query result as;
    *              Array (
    *                 [0] => Array ( [unt_id] => 4 [unt_batch] => 4 [unt_name] => meter )
    *                 [1] => Array ( [unt_id] => 5 [unt_batch] => 4 [unt_name] => roll )
    *                 [2] => Array ( [unt_id] => 6 [unt_batch] => 4 [unt_name] => bundle)
    *              );
    * The function will replace all keys with the primary key value in value array. Here it will be like [unt_id] => array();
    * The formated result will be as follows;

    *              Array (
    *                 [4] => Array ( [unt_id] => 4 [unt_batch] => 4 [unt_name] => meter )
    *                 [5] => Array ( [unt_id] => 5 [unt_batch] => 4 [unt_name] => roll )
    *                 [6] => Array ( [unt_id] => 6 [unt_batch] => 4 [unt_name] => bundle)
    *              );


    * @param type $result       :  A query result.
    * @param type $key_field  : Primary key field.
    */
   function groupQueryResultById($result, $key_field = '')
   {
      $KEY = $key_field ? : $this->p_key;

      if (!$KEY || !$result)
         return $result;

      $arr = array();

      foreach ($result as $row)
         $arr[$row[$KEY]] = $row;

      return $arr;
   }

   /**
    * @return $option array as $option[$key] => $field
    * @params:
    * $key     : Name of the field whose value should be set as key of $option array
    * $field   : Name of a field or an array of fields that will return as the value of $option[$key]. Eg;
    *          
    *          //  To get the value of a single field
    *          $field = 'SBR_NAME';
    *      
    *          // To get the value of multiple fields.
    *          $field = array('SBR_NAME','SBR_ID',SBR_ADDRESS');
    * 
    *          // To get the value of all fields.
    *          $field = '*'
    * 
    * @author : "Shihabu Rahman K" <shihab@levoirsolutions.com>
    */
   function get_option($where = '', $or_where = '', $key = '', $field = '', $table = '')
   {
      $TABLE = $table ? $table : $this->table;
      $this->p_key = $key ? $key : $this->p_key;
      $field = $field ? $field : $this->nameField;
      $options = $this->get_data('', $where, $or_where, $TABLE);
      $option = array();
      foreach ($options as $row)
      {
         if ($field == '*')
            $option[$row[$this->p_key]] = $row;
         else if (is_array($field))
         {
            $value_array = array();
            foreach ($field as $value)
               $value_array[$value] = $row[$value];
            $option[$row[$this->p_key]] = $value_array;
         }
         else
            $option[$row[$this->p_key]] = $row[$field];
      }
      return $option;
   }

   /**
    * Returns option list with the value of 'status' field = "Active"
    * @param type $where
    * @param type $or_where
    * @param type $key
    * @param type $field
    * @param type $table
    * @return type
    */
   function get_active_option($where = '', $or_where = '', $key = '', $field = '', $table = '', $status_field = '', $active_value = 1)
   {


      $TABLE = $table ? $table : $this->table;

      if (!$this->statusField && !$status_field)
         return;

      $this->statusField = $status_field ? $status_field : $this->statusField;
      $where[$this->statusField] = $active_value;

      $this->p_key = $key ? $key : $this->p_key;

      $field = $field ? $field : $this->nameField;

      $options = $this->get_data('', $where, $or_where, $TABLE);


      $option = array();
      foreach ($options as $row)
      {
         if ($field == '*')
            $option[$row[$this->p_key]] = $row;
         else if (is_array($field))
         {
            $value_array = array();
            foreach ($field as $value)
               $value_array[$value] = $row[$value];
            $option[$row[$this->p_key]] = $value_array;
         }
         else
            $option[$row[$this->p_key]] = $row[$field];
      }
      return $option;
   }

   function is_exists($unique, $id = '', $table = '')
   {
      $TABLE = $table ? $table : $this->table;
      if ($id)
         $this->db->where("$this->p_key !=", $id);
      $this->db->where($unique);

      if ($this->db->count_all_results($TABLE))
         return TRUE;
      return FALSE;
   }

   /**
    * 
    * @param type $unique  :  Conditions to check.
    * @param type $id      :  Primary key value. The search result will not contain the row that contains the given primary key value. 
    * @param type $return  :  Return format. Possible values are 'id','row'.
    *                            'id' : if the searched data found, returns the primary key value. Else return ''.
    *                            'row': if the searched data found, return it.
    * @param type $table
    * @return string
    */
   function is_exists_2($unique, $id = '', $return = 'id', $table = '')
   {
      $TABLE = $table ? $table : $this->table;
      if ($id)
         $this->db->where("$this->p_key !=", $id);
      $this->db->where($unique);

      $result = $this->db->get($TABLE);
      $result = $result->row_array();

      if ($return == 'id')
      {
         if (isset($result[$this->p_key]))
            return $result[$this->p_key];
         else
            return '';
      }
      else if ($return == 'row')
         return $result;
   }

   function array_query($input, $field)
   {
      if (!is_array($input))
         return '';
      $i = 0;
      $str = "($field = '";
      foreach ($input as $status)
      {
         $str .= $status;
         if ($i == (count($input) - 1))
            $str .= "')";
         else
            $str .= "' OR $field = '";
         $i++;
      }
      return $str;
   }

   /**
    * @return returns array after trim its value
    * @params:
    * $value     : a two dimensional array, Eg:
     $value[] = array(' 1','d    ');
     $value[] = array('b ','  g  ');
     $value[] = array('f ','   y ');
    * 
    * @author : "Shihabu Rahman K" <shihab@levoirsolutions.com>
    */
   function trim_array(&$value)
   {
      foreach ($value as &$val)
         $val = array_map('trim', $val);
      // return $value; 
   }

   // To save form input values on pagination
   function set_form_inputs($clsfunc, $values)
   {
      // The 'fip_clsfunc' must be unique. So we have to delete if previous one of this is existing.
      $this->db->delete('form_inputs', array('fip_clsfunc' => $clsfunc));

      $values = serialize($values);
      $data = array('fip_clsfunc' => $clsfunc, 'fip_values' => $values);
      $this->db->insert('form_inputs', $data);
   }

   // To retrieve saved values of form inputs on pagination
   function get_form_inputs($clsfunc, $delete = true)
   {
      $this->db->select('fip_values');
      $this->db->where('fip_clsfunc', $clsfunc);
      $result = $this->db->get('form_inputs');
      $values = $result->row_array();
      if ($values)
         $values = unserialize($values['fip_values']);

      // Deleting the full row from database after getting data.
      if ($delete)
      {
         $this->db->delete('form_inputs', array('fip_clsfunc' => $clsfunc));
      }
      return $values;
   }

}

// END Model Class