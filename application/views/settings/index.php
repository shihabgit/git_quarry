<?php $this->load->view('settings/pop_add'); ?>
<?php $this->load->view('firm_settings/pop_edit'); ?>
<div class="dv-bottom-content">
   <p style="background-color:#FFF;color:#000;text-align: left;padding: 5px;">
      1. When adding new settings make sure that the settings are added to the 'inactive' firms also. because it may activate later.<br>
      2. Connect with worklog.
   </p>
   <div class="datagrid" style="width: 100%;">
      <div class="tbl_grid_head_container">
         <!--  
         1. Don't set width for at least one 'tbl_grid_head' column (preferably the last one).
         2. The sum of the total width of 'tbl_grid_head' must be some less than that of the "datagrid" as it can include the last 'tbl_grid_head'.
         3. If the sum of the total width of 'tbl_grid_head' exceeds the width of "datagrid", the last 'tbl_grid_head' will be pushed out.
            So if this case occurred, you just decrease the with of any other 'tbl_grid_head' or increase the width of "datagrid"
                
         -->
         
         <?php
         // Only awailable for Developer
         $title_col_width = ($this->environment == 'Development')?'30%':'50%';
            ?>
         
         
         
         <p class="tbl_grid_head" style="width:8%"><input type="checkbox" class="checkUncheckAll"  />Sl No</p>
         <p class="tbl_grid_head" style="width:<?php echo $title_col_width;?>">Title</p> 
         <?php
         // Only awailable for Developer
         if ($this->environment == 'Development')
         {
            ?>
            <p class="tbl_grid_head" style="width:20%">Key</p>
         <?php } ?>
         <p class="tbl_grid_head" style="width:10%">Value</p>
         <p class="tbl_grid_head" >Default Value
         <div style="padding:8px 0px 0px 0px;"><img  src="images/add.png" id="initSettingsAdd" title="Add Settings" /></div>
         </p>  
         <p class="gridClearfix "></p>
      </div>


      <div class="tbl_grid_body_container">

         <?php
         $slNo = 0;

         if (!$table)
            echo '<table class="tbl_grid no-data-to-display"><tbody><tr><td colspan="5">No Data To Display.</td></tr></tbody></table>';
         else
         {
            echo '<table id="" class="tbl_grid">';
            echo '<tbody>';

            $not_set_message = 'Value has not set in settings_model/getSettingsValues';

            foreach ($table as $row)
            {
               echo '<tr>';
               echo '<td>';
               echo '<input type="checkbox" value="' . $row['set_key'] . '" name="checkbox" class="gridSlNo" />';
               echo '<span class="spn_slNo">' . ++$slNo . '</span>';
               echo '</td>';

               echo '<td>' . $row['set_title'] . '</td>';


               // Only awailable for Developer
               if ($this->environment == 'Development')
                  echo '<td class="td_key">' . $row['set_key'] . '</td>';

               // If the value is not a single,
               if (isset($values[$row['set_key']]['multiple_values']))
               {
                  $value = explode(',', $row['frmset_value']);
                  $color = '';
                  $default_value = explode(',', $row['set_default_value']);
                  foreach ($value as &$val)
                  {
                     if (isset($values[$row['set_key']]['multiple_values'][$val]))
                        $val = $values[$row['set_key']]['multiple_values'][$val];
                     else
                        $val = '<font color="red">' . $not_set_message . '</font>';
                  }

                  foreach ($default_value as &$val)
                  {
                     if (isset($values[$row['set_key']]['multiple_values'][$val]))
                        $val = $values[$row['set_key']]['multiple_values'][$val];
                     else
                        $val = '<font color="red">' . $not_set_message . '</font>';
                  }

                  $value = implode(',', $value);
                  $default_value = implode(',', $default_value);
               }
               else if (isset($values[$row['set_key']][$row['frmset_value']]['text']) && isset($values[$row['set_key']][$row['frmset_value']]['color']) && isset($values[$row['set_key']][$row['set_default_value']]['text']))
               {
                  $value = $values[$row['set_key']][$row['frmset_value']]['text'];
                  $color = $values[$row['set_key']][$row['frmset_value']]['color'];
                  $default_value = $values[$row['set_key']][$row['set_default_value']]['text'];
               }
               else
               {
                  $value = $not_set_message;
                  $color = '';
                  $default_value = $not_set_message;
               }

               $value = $color ? '<font color="' . $color . '">' . $value . '</font>' : $value;

               echo '<td class="edit_value" title="Click here to edit the value"><input type="hidden" class="frmset_id" value="' . $row['frmset_id'] . '"><b>' . $value . '</b></td>';
               echo '<td>' . $default_value . '</td>';

               echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
         }
         ?>


      </div>	<!--<div class="tbl_grid_body_container">-->


   </div>	<!--<div class="datagrid">-->
</div>   <!--<div class="dv-bottom-content"> -->





<script>
   //$('.gridSlNo').change(function(){location.reload();});

   // Opening popup box on clicking <img src="images/add.png" id="initOwnerAdd"> @ workcentres/pop_add.php
   $('#initSettingsAdd').click(function() {

      //Initializing popup box  
      init_pop_settings_add();

      // Removing if is there any value in primary key
      $('#pop_settings_add #p_key').val('');

      //Loading popupBox.
      loadPopup('pop_settings_add');
   });


   // Opening popup box on clicking <td class="edit_value"> @ workcentres/pop_add.php
   $('.edit_value').click(function() {

      //Initializing popup box  
      init_pop_firm_settings_edit();

      var p_key = $(this).find('.frmset_id').val();

      var key = $(this).closest('tr').find('td.td_key').html();

      $('#pop_firm_settings_edit .namespan').html(key);

      // Setting the primary key in popup
      $('#pop_firm_settings_edit #p_key').val(p_key);

      //Loading all possible settings values
      var inputs = {frmset_id: p_key}; // eg: {parent_id: parent_id, status: 1}

      $.getJSON(site_url + 'firm_settings/getOptions', inputs, function(data) {
         var options = '';
         if (data[0]['value'] == 'Multiple') // Determining is the dropdown is multiple or normal.
         {
            $('#pop_firm_settings_edit #frmset_value').prop('multiple', 'true');
            $('#pop_firm_settings_edit #frmset_value').height(100);
         }
         else if (data[0]['value'] == 'Normal')
         {
            $('#pop_firm_settings_edit #frmset_value').removeProp('multiple');
            $('#pop_firm_settings_edit #frmset_value').height(20);
         }
         for (var x = 1; x < data.length; x++) {
            options += '<option value="' + data[x]['value'] + '">' + data[x]['text'] + '</option>';
         }
         $('#pop_firm_settings_edit #frmset_value').html(options);
      });

      //Loading popupBox.
      loadPopup('pop_firm_settings_edit');
   });


   /*  To resolve jquery conflict when using "$(document).on()" function when we using both 
    jquery1.11.0.js and js/jquery.min.js libraries, use  $.noConflict();    
    Other wise it will show error as follows;
    TypeError: $(...).on is not a function
    
    */
   //jQuery.noConflict();
</script> 
<script type="text/javascript" src="js/table.js"></script> 
