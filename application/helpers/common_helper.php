<?php

function redirect_me($url, $msg_level = "", $msg_val = "")
{
   $data['url'] = $url;
   $data['msg_level'] = $msg_level;
   $data['msg_val'] = $msg_val;
   $CI = & get_instance();
   $CI->load->view('redirect_banner', $data);
}

function get_combo_option($option, $title) //	For Ajax uses.
{
   $str = '=>***** No Options *****';
   if (!$option)
      return $str;
   $str = $title;
   foreach ($option as $key => $val)
      $str .= '|' . $key . '=>' . $val;
   return $str;
}

function get_options($options, $name, $default_value = 'NA', $flag = true, $default_text = 'default', $append = '', $preppend = '')
{ #	Where $append/$preppend are function names which returns reserved values that to be appended/preppended with options.
   if ($append)
      $options = $append() + $options;
   if ($preppend)
      $options = $options + $preppend();
   $option = "";
   $text = '******* Select *******';
   $default_text = ($default_text == 'default') ? $text : $default_text;
   if (!$options || !is_array($options))
   {
      $option .= '<option value=""> ****** No Options ****** </option>';
      return $option;
   }
   if ($flag)
      $option .= '<option value="">' . $default_text . '</option>';
   foreach ($options as $key => $value)
   {
      if ($default_value === $key)
         $option .= '<option value="' . $key . '" ' . set_select($name, $key, true) . '>' . $value . '</option>';
      else
         $option .= '<option value="' . $key . '" ' . set_select($name, $key) . '>' . $value . '</option>';
   }
   return $option;
}






/**
 * Example for how to use, visit http://localhost/the_quarry/individual_rates/add
 * 
 * 
 * @param type $opt_group        :  Array contains <optgroup> labels.
 *                                  Format: normal option format; array (p_key_1 => text_1,p_key_2 => text_2);
 *                                  Eg: Array (2 => Best, 5 => Parambadan); //[pty_id => pty_name]

 * @param type $options          :  It is a query result. Each row in the query result must contain 3 fields;
 *                                   1. key filed in $opt_group; It is the relation between <optgroup> and <option>.
 *                                   2. Key field for <option>.
 *                                   3. Text field for <option>.
 *                                  
 *                                  Eg:
 *                                     Array ( 
 *                                        [0] => Array ( [pty_id] => 2 [pdst_name] => Best H.b [pdst_id] => 2 ) 
 *                                        [1] => Array ( [pty_id] => 2 [pdst_name] => Best Interlocks [pdst_id] => 3 ) 
 *                                        [2] => Array ( [pty_id] => 3 [pdst_name] => H.b [pdst_id] => 4 ) 
 *                                        [3] => Array ( [pty_id] => 3 [pdst_name] => Interlocks [pdst_id] => 7 ) 
 *                                     );
 *                                  Here pty_id represents key filed in $opt_group;
 *                                  pdst_id represents Key field for <option> ;    
 *                                  pdst_name represents Text field for <option> ;    
 
 * @param type $group_key_field  : Name of the key filed related to $opt_group in $options array; Eg: pty_id
 * @param type $option_key_field : Name of the field related to value atribute of <option> in $options array; Eg: pdst_id 
 * @param type $option_text_field: Name of the field related to text of <option> in $options array; Eg: pdst_name 
 * @param type $default_value    : Array of values which should be selected in <option> list. Eg: array(4,8,2);
 * @param type $decoration       : Decoration text which will be appended and prepended to the option heading. 
 * @return string
 */
function get_optGroups($opt_group, $options, $group_key_field, $option_key_field, $option_text_field, $default_value = '', $decoration = '---')
{
   if (!$opt_group || !$options || !is_array($opt_group) || !is_array($options))
   {
      $html = '<optgroup label="No Data Found">';
      $html .= "<option value=''>$decoration No Options $decoration</option>";
      $html .= '</optgroup>';
      return $html;
   }
   
   $html = '';
   foreach ($opt_group as $p_key => $groupHead)
   {
      $html .= "<optgroup label='$groupHead'>";

      foreach ($options as $row)
      {
         if ($p_key == $row[$group_key_field])
         {
            $selected = '';
            if (is_array($default_value))
               if (in_array($row[$option_key_field], $default_value))
                  $selected = 'selected';
            $html .= "<option value='$row[$option_key_field]' $selected>";
            $html .= $row[$option_text_field];
            $html .= "</option>";
         }
      }
      $html .= '</optgroup>';
   }

   return $html;
}

function get_options2($options, $default_value = NULL, $flag = true, $default_text = 'default', $append = '', $preppend = '', $decoration = '***')
{ #	where $default_value can hold;
   #	1.	A single value for single select options.
   #			$default_value	=	12;
   #	2.	An array for multiselect options.
   #			$default_value	=	array(12,15,2); 
   #	Where $append/$preppend are function names which returns reserved values that to be appended/preppended with options.
   if ($append)
      $options = $append() + $options;
   if ($preppend)
      $options = $options + $preppend();
   $option = "";

   $text = $decoration . ' Any ' . $decoration;

   $default_text = ($default_text == 'default') ? $text : $default_text;
   if (!$options || !is_array($options))
   {
      $option .= '<option value="">' . $decoration . ' No Options ' . $decoration . '</option>';
      return $option;
   }
   if ($flag)
      $option .= '<option value="">' . $default_text . '</option>';
   foreach ($options as $key => $value)
   {
      $selected = false;
      if (is_array($default_value))
      {
         if (in_array($key, $default_value))
            $selected = true;
      }
      else if ($default_value == 'all')
         $selected = true;
      else if ($default_value == $key)
         $selected = true;
      $option .= $selected ? '<option value="' . $key . '"  selected=' . $key . '>' . $value . '</option>' : '<option value="' . $key . '" >' . $value . '</option>';
   }
   return $option;
}

function getRootName($type = 0)
{
   $rootName = array(0 => "www.mazhar-ul-irfan.com",
       1 => "Mazhar-ul-Irfan.com",
       2 => "Mazhar-ul-Irfan",
       3 => '<img src="" />',
       4 => 'Name Of My Firm'
   );
   return $rootName[$type];
}

function getMSG($clsfunc)
{
   $msg[1] = "Patient Added Successfully";
   $msg[2] = "Patient Could Not Be Added";
   $msg[3] = "Case Sheet Added Successfully";
   $msg[4] = "Patient Edited Successfully";
   $msg[5] = "Patient Deleted Successfully";
   $msg[6] = "Case Sheet Edited Successfully";
   $msg[7] = "Case Sheet Deleted Successfully";
   return $msg[$clsfunc];
}

function getStatus()
{
   return array(1 => 'Active', 2 => 'Suspend', 3 => 'Delete');
}

/**
 * 
 * @param type $operants = Array of values to be operated by $operator.
 * @param type $operator = Which type of operation to be made on $operants. Supported only Addition (+) and Multiplication(*).
 * @param type $decimals = The resulting value must contain how many decimal places.
 */
function mybcmath($operants, $operator, $decimals = 2)
{
   if ($operator == '+')
   {
      $val = 0;
      foreach ($operants as $opt)
         $val = bcadd("$val", "$opt", $decimals);
   }
   else if ($operator == '*')
   {
      $val = 1;
      foreach ($operants as $opt)
         $val = bcmul("$val", "$opt", $decimals);
   }
   else
      $val = 0;
   return $val;
}

function ifChecked($field, $default = FALSE)
{
   /* $val = $default;
     if (isset($_POST[$field]))
     {
     if ($trim)
     {
     if (is_array($_POST[$field]))
     $val = array_filter($_POST[$field], 'trim');
     else
     $val = trim($_POST[$field]);
     }
     else
     $val = $_POST[$field];
     }

     return $val; */
}

/**
 * Function returns the value of $_POST.
 * Eg:- 
 *          ifSet2("pvhcl[pvhcl_name][0][hack]",'hellow');
 * 
 *      The above code will return the value of $_POST[pvhcl][pvhcl_name][0][hack]. 
 *      if the curresponding $_POST is not exist, the default value (here 'hellow') will be returned.
 * 
 * 
 * @param type $field
 * @param type $default
 * @return type
 */
function ifSet2($field, $default = '')
{
   //Checking the $field name is array or normal.
   $pos = strpos($field, "[");

   //  If the $field name is array (may be multi dimensional), extracting the top level key (as per the above example, 'pvhcl').
   $input_name = $pos ? substr($field, 0, $pos) : $field;

   // If the $field name is array getting the part after the top level key (as per the above example, [pvhcl_name][0][hack]).
   // and then putting each key in an array.
   $rest = $pos ? explode('][', trim(substr($field, $pos), '[]')) : '';

   $val = isset($_POST[$input_name]) ? $_POST[$input_name] : $default;
   if (isset($_POST[$input_name]) && $rest)
   {
      foreach ($rest as $r)
      {
         if (isset($val[$r]))
            $val = $val[$r];
         else
         {
            $val = $default;
            break;
         }
      }
   }
   return $val;
}

function ifSetRadio2($field, $val, $default = false)
{
   $post_val = $default ? ifSet2($field, $val) : ifSet2($field);
   return ("$post_val" === "$val") ? " checked " : '';
}

function ifSet($field, $default = '', $trim = true)
{
   $val = $default;
   if (isset($_POST[$field]))
   {
      if ($trim)
      {
         if (is_array($_POST[$field]))
            $val = array_filter($_POST[$field], 'trim');
         else
            $val = trim($_POST[$field]);
      }
      else
         $val = $_POST[$field];
   }

   return $val;
}

function ifSetInput($input, $field, $default = '', $trim = true)
{
   $val = $default;
   if (isset($input[$field]))
   {
      if ($trim)
      {
         if (is_array($input[$field]))
            $val = array_filter($input[$field], 'trim');
         else
            $val = trim($input[$field]);
      }
      else
         $val = $input[$field];
   }

   return $val;
}

function ifSetArray($field_array, $field, $default = '', $trim = true)
{
   $val = $default;
   if (isset($_POST[$field_array][$field]))
   {
      if ($trim)
         $val = trim($_POST[$field_array][$field]);
      else
         $val = $_POST[$field_array][$field];
   }
   return $val;
}

function ifSetRadio($field, $which, $default = false)
{
   if (isset($_POST[$field]))
   {
      if ($_POST[$field] == $which)
         return " checked ";
   }
   else if ($default)
      return " checked ";
   return '';
}

/**
 * 
 * @param type $name
 * @param type $index
 * @param type $default
 * @return string
 *  Usage:
 *     <input type="checkbox" <?php echo ifSetCheckboxGroupArray('mycheck',1) ?> name="mycheck[1]" value="1" > check 1<br>
  <input type="checkbox" <?php echo ifSetCheckboxGroupArray('mycheck',2) ?> name="mycheck[2]" value="2" > check 2<br>
  <input type="checkbox" <?php echo ifSetCheckboxGroupArray('mycheck',3) ?> name="mycheck[3]" value="3" > check 3
 */
function ifSetCheckboxGroupArray($name, $index, $default = false)
{
   if ($_POST)
   {
      if (isset($_POST[$name][$index]))
         if (!empty($_POST[$name][$index]))
            return " checked ";
   }
   else if ($default)
      return " checked ";
   return '';
}

function ifSetRadioGroupArray($name, $index, $value, $default = false)
{
   if (isset($_POST[$name]))
   {
      if (isset($_POST[$name][$index]))
         if ($_POST[$name][$index] == $value)
            return " checked ";
   }
   else if ($default)
      return " checked ";
   return '';
}

function getStatusColor()
{
   $color[1] = array('bgColor' => 'green', 'color' => '#FFF'); // 'Active'
   $color[2] = array('bgColor' => 'orange', 'color' => '#FFF'); // 'Suspend'
   $color[3] = array('bgColor' => 'red', 'color' => '#FFF'); // 'Delete'
   $color[4] = array('bgColor' => '', 'color' => '');
   $color[5] = array('bgColor' => '', 'color' => '');
   return $color;
}

function get_pagination_configurations()
{
   $config['num_links'] = 3;
   $config['uri_segment'] = 4;
   $config['full_tag_open'] = '<div class="full_tag" onclick="saveScrollPosition();">';
   $config['full_tag_close'] = '</div>';

   $config['first_link'] = '<<';
   $config['first_tag_open'] = '<div class="first_tag">';
   $config['first_tag_close'] = '</div>';

   $config['last_link'] = '>>';
   $config['last_tag_open'] = '<div class="last_tag">';
   $config['last_tag_close'] = '</div>';

   $config['next_link'] = '>';
   $config['next_tag_open'] = '<div class="next_tag" >';
   $config['next_tag_close'] = '</div>';

   $config['prev_link'] = '<';
   $config['prev_tag_open'] = '<div class="prev_tag" >';
   $config['prev_tag_close'] = '</div>';

   $config['cur_tag_open'] = '<div class="current_tag" >';
   $config['cur_tag_close'] = '</div>';

   $config['num_tag_open'] = '<div class="num_tag">';
   $config['num_tag_close'] = '</div>';

   return $config;
}

?>