<?php $this->load->view('units/pop_edit'); ?>

<link href="css/<?php echo $this->themes[$this->theme]['text']; ?>/tiles.css" rel="stylesheet" type="text/css" />




<!-- Fancy scrollbar START 	-->
<link type="text/css" href="plugins/fancy_scrollbars/by_shihab/css/jquery.jscrollpane.css" rel="stylesheet" media="all" />

<style type="text/css" id="page-css">
   /* Styles specific to this particular page */
   .scroll-pane
   {
      width: 98%;
      height: 300px;
      overflow: auto;
   }


   #tile_container #tbl_tile > tbody > tr > td {
      width:50% !important;
   }

</style>
<!-- the mousewheel plugin -->
<script type="text/javascript" src="plugins/fancy_scrollbars/by_shihab/js/jquery.mousewheel.js"></script>
<!-- the jScrollPane script -->
<script type="text/javascript" src="plugins/fancy_scrollbars/by_shihab/js/jquery.jscrollpane.min.js"></script>

<script type="text/javascript" id="sourcecode">
   $(function()
   {
      $('.scroll-pane').jScrollPane(
              {
                 showArrows: true,
                 horizontalGutter: 30,
                 verticalGutter: 30
              }
      );
   });
</script>
<!-- Fancy scrollbar END 	-->






<div class="dv-top-content" align="center">
   <?php echo form_open("items", array('id' => 'searchForm')); ?>

   <table width="40%" cellpadding="5" cellspacing="0" class="tbl_input">
      <tr>
         <td>
            <div class="title-box">
               <div id="img-container">
                  <img src="images/search-user.png" width="35" height="35"/>
               </div>
               <div id="title-container">
                  <div class="title-alone"><?php echo $heading; ?></div>
               </div>
            </div>
         </td>
      </tr>
      <tr>
         <td>
            <div style="background-color:#FFF;color:#000;text-align: left;padding: 5px;">
               1. If the units of an item edited, The unit rates of the item set in Tbl:individual_rates, Tbl:workcentre_rates, Tbl:item_units_n_rates become invalid. so it must be deleted. 
            </div>
            <div class="inputblok" style="width: 100%;">
               <div class="sec_container">
                  <p class="input-categories">Basic Details</p>
                  <table class="sec_table">
                     <tr>
                        <th>Item Category</th>
                        <td>
                           <select name="itmcat_id" id="itmcat_id" onchange="resetOptions(this, 'itm_fk_item_head', 'item_heads/getItemHead', beforeAjax, afterAjax);" >
                              <?php echo get_options2($itmcats, ifSet('itmcat_id'), true, '--- select ---'); ?>
                           </select>
                        </td>

                     </tr> 
                     <tr>
                        <th>Item Head</th>
                        <td>
                           <select name="itm_fk_item_head" id="itm_fk_item_head">
                              <?php echo get_options2($itm_heads, ifSet('itm_fk_item_head'), true, '--- Select ---'); ?>
                           </select>
                           <div class="ajaxLoaderContainer"> 
                              <img src="images/ajax-loader2.gif"> 
                              <img src="images/ajax-loader2.gif">
                           </div>  
                        </td>
                     </tr>

                     <tr>
                        <th>Item Name</th>
                        <td>
                           <input type="text" name="itm_name" value="<?php echo ifSet('itm_name') ?>" >

                           <br>
                           <input type="radio" name="itm_status" value="1" <?php echo ifSetRadio2('itm_status', 1, true) ?> />
                           <span class="multy_options">Active</span>

                           <input type="radio" name="itm_status" value="2" <?php echo ifSetRadio2('itm_status', 2) ?> />
                           <span class="multy_options">Inactive</span> 

                           <input type="radio" name="itm_status" value="0" <?php echo ifSetRadio2('itm_status', 0) ?> />
                           <span class="multy_options">All</span>
                        </td>
                     </tr>

                  </table>
               </div>      <!--     End of <div class="sec_container">     -->
            </div> <!--     End of  <div class="inputblok">         -->
         </td>
      </tr>



   </table>
   <div id="submit_container">
      <hr /><br />
      <input type="hidden" name="PER_PAGE"  value="<?php echo ifSet('PER_PAGE', $this->per_page) ?>" > <!-- its value will be filled onChange <select> near pagination links -->
      <input type="submit" name="button2" class="collapse_btn" value="Submit" />
      <input type="button" class="collapse_btn reseter" name="button3" value="Reset" />
   </div>
   <?php echo form_close(); ?>
</div>












<div class="dv-bottom-content">
   <div class="search-header">
      <div id="right_pan">



         <div class="collapse_container right_action">
            <div class="settings_btn">
               <img  src="images/listUp.png" title="Print Data" width="24" height="24">
            </div>

            <div class="settings p-collaps ">List Print</div>
            <div class="dv-collaps left_action_content" style="width:250px;">

               <div id="list_print_form" style="margin:20px;text-align: left;">       
                  <input type="checkbox" name="L_NO" checked="" > L No <br> 
                  <input type="checkbox" name="ADDRESS"  checked=""> Address <br>  


                  <div style="margin:20px 5px;text-align: center;"><input type="submit" name="button" value="PRINT" class="collapse_btn" /></div>                
               </div>   

            </div> 

            <div class="clear_boath"></div>
         </div>

         <div class="right_action" id="GROUP_ACCOUNT">
            <div class="settings_btn"><img  src="images/bird.png" title="ACCOUNTS" width="24" height="24"></div>
            <div class="settings">Accounts</div>         

            <?php echo form_open('subscriber_accounts/account', array('id' => 'frm_GROUP_ACCOUNT')) ?>
            <input type="hidden" name="SBR_ID" value="">
            <?php echo form_close(); ?>

            <div class="clear_boath"></div>
         </div>

         <a href="<?php echo site_url('items/add'); ?>" >
            <div class="right_action"  title="<?php echo lang('index_create_user_link') ?>">
               <div class="settings_btn">
                  <img src="images/Add button.png" height="24" width="24" />
               </div>
               <div class="clear_boath"></div>
            </div>
         </a>


      </div>  <!--<div id="right_pan">-->


      <div id="left_pan">

         <div class="collapse_container right_action">
            <div class="settings_btn">
               <img  src="images/bird.png" title="SETTINGS" width="24" height="24">
            </div>

            <div class="settings p-collaps ">Publish</div>
            <div class="dv-collaps left_action_content" style="width:250px;">

               <div id="publish_form" style="margin:20px;">


                  <div style="margin:20px 5px;text-align: left;"> 
                     <div class="dateContainer" style="padding: 0px;margin:0px;"> Date :
                        <input class="dateField inputDate" readonly="" id="GRP_PBL_DATE" value="<?php echo formatDate('', true, 1); ?>" style="width:80px;" /> 
                        <img src="images/calendar.png"  class="calendarButton"> 
                     </div>
                  </div>


                  <div style="margin:20px 5px;text-align: left;"> Lakkam : 
                     <select name="MONTH">
                        <?php echo get_options2(getMonthOptions(), date('m'), false); ?>
                     </select>
                     <select name="YEAR">
                        <?php echo get_options2(getYearOptions(2010, 2020), date('Y'), false); ?>
                     </select>
                  </div>
                  <div style="margin:20px 5px;text-align: left;"><input type="checkbox" name="PRINT"> Print</div>


                  <div style="margin:20px 5px;text-align: center;"><input type="submit" name="button" value="PUBLISH" class="collapse_btn" /></div>                
               </div>   

            </div> 

            <div class="clear_boath"></div>
         </div>



         <div class="collapse_container right_action" id="envelope">
            <div class="settings_btn"><img  src="images/bird.png" title="Print Data" width="24" height="24"></div>
            <div class="settings p-collaps">Envelope</div>
            <div class="dv-collaps left_action_content" style="width:180px;">

               <div style="margin:20px;">

                  <div style="margin:20px 5px;text-align: left;">
                     <p style="margin: 0px;padding: 0px; color: #FFBFAA; text-align: center; text-decoration: underline; line-height: 30px;"> Send Option</p>
                     <input type="radio" name="ENV_MODE" value="envelope_individual" checked="">  Individually <br>
                     <input type="radio" name="ENV_MODE" value="envelope_bulk">  Bulk
                  </div>

                  <div style="margin:20px 5px;text-align: center;">
                     <input type="submit" name="button" value="PRINT" class="collapse_btn" />
                  </div>                
               </div>   

            </div> 
            <div class="clear_boath"></div>
         </div>


         <div class="collapse_container right_action" id="group_delete">
            <div class="settings_btn"><img  src="images/bird2.png" title="Print Data" width="21" height="21"></div>
            <div class="settings">Delete</div>

            <div class="clear_boath"></div>
         </div>



      </div>		<!--<div id="left_pan">-->
      <div id="middle_pan">
         <h3>SEARCH RESULTS</h3>
      </div>

   </div>
   <!--<div class="search-header">-->
   <div id="tile_container">




      <?php
      if (!$table)
         echo '<table align="center"  width="100%" cellpadding="0" cellspacing="0" style="background-color:#FFF;"><tr><th style="width:100%;color:red">NO DATA TO DISPLAY</th></tr></table>';
      else
      {
         $slNo = 1;
         $col = 0;
         $max_cols = 2;
         $tr_tag = '';
         echo '<table id="tbl_tile"  cellpadding="0" cellspacing="0">';


         foreach ($table as $row)
         {
            ++$col;
            if ($col == 1)
            {
               $tr_tag = '<tr>';
               echo $tr_tag;
            }


            echo '<td>';


            echo '<div class="tile scroll-pane blackScroll">';

            echo '<div class="bring">';

            // Following hidden elements are to use with popup: units/pop_edit.php. 
            echo '<input type="hidden" class="itm_id_popup" value="' . $row['itm_id'] . '">';
            echo "<input type='hidden' class='itm_name_popup' value='" . $row['itm_name'] . "'>";
            echo '<input type="hidden" class="itm_status_popup" value="' . $row['itm_status'] . '">';

            if ($row['itm_status'] == 1)
            {
               $status = 'active';
               $img = 'active';
               $img2 = 'delete4';
               $title = "Deactivate Item";
            }
            else
            {
               $status = 'inactive';
               $img = 'delete2';
               $img2 = 'activate2';
               $title = "Activate Item";
            }

            echo '      <div class="tile_name ' . $status . '">';
            echo $row['itm_name'] . ' (' . $row['itm_id'] . ')&nbsp;&nbsp;';
            echo ' <img src="images/' . $img . '.png" title="' . ucfirst($status) . '.">';

            if ($row['itm_status'] == 1)
               echo anchor("items/before_edit/edit/$row[itm_id]", '<div class="tool_image"><img src="images/edit11.png" title="Edit Item" /></div>');
            echo '<div class="tool_image toggleStatus"><img src="images/' . $img2 . '.png" title="' . $title . '" /></div>';
            echo '          <div class="clear_boath"></div>';
            echo '      </div>';
            echo '      <div class="tile_description">' . $row['itmcat_name'] . ' > ' . $row['itmhd_name'] . '</div>';
            echo '</div>'; // End '<div class="bring">';





            echo '<hr>';













            $pcntg = '<span class="pcntg"> %</span>';

            echo '<div class="bring">';
            echo '<div class="title_content" style="width:49%;">';
            echo '<table class="tbl_tile_content">';
            echo '       <tr>';
            echo '                  <th><p class="tile_content_h_title"></p></th>';
            echo '                  <th><p class="tile_content_h_title">VAT</p></th>';
            echo '                  <th><p class="tile_content_h_title">CESS</p></th>';
            echo '        </tr>';

            echo '       <tr>';
            echo '                  <td><p class="title_content_data">Purchase</p></td>';
            echo '                  <td><p class="title_content_data">' . $row['itm_p_vat'] . $pcntg . '</p></td>';
            echo '                  <td><p class="title_content_data">' . $row['itm_p_cess'] . $pcntg . '</p></td>';
            echo '        </tr>';

            echo '       <tr>';
            echo '                  <td><p class="title_content_data">Sale</p></td>';
            echo '                  <td><p class="title_content_data">' . $row['itm_s_vat'] . $pcntg . '</p></td>';
            echo '                  <td><p class="title_content_data">' . $row['itm_s_cess'] . $pcntg . '</p></td>';
            echo '        </tr>';
            echo '</table>';
            echo '</div>';  // End <div class="title_content">
            // Showing units in table
            echo '<div class="title_content" style="width:49%; float:right;">';
            echo '   <table class="tbl_tile_content">';
            echo '        <tr>';
            echo '           <th><p class="tile_content_h_title">Unit</p></th>';

            echo '           <th style="width:75%;">';
            echo '               <p class="tile_content_h_title">Relation</p>';
            if ($row['itm_status'] == 1)
               echo '<div class="tile_action"><img src="images/edit11.png" title="Edit Units" id="edtunt" /></div>';
            echo '           </th>';
            echo '        </tr>';




            foreach ($units[$row['itm_id']] as $unit_row)
            {

               echo '        <tr>';
               echo '            <td>';

               echo '<p class="title_content_data">' . $unit_row['unt_name'] . '</p>';
               echo '            </td>';

               $defUnit = ($row['unt_id'] == $unit_row['unt_id']) ? ' <img src="images/default.png" title="Default unit">' : '';

               if ($unit_row['unt_is_parent'] == 1)
                  echo '              <td>Basic Unit <img src="images/parent.png" title="parent unit of the batch.">' . $defUnit . '</td>';
               else if (intval($unit_row['unt_relation']))
                  echo '            <td><p class="title_content_data">' . $unit_row['unt_relation'] . ' ' . $unit_row['parent_name'] . $defUnit . '</p></td>';
               else
                  echo '<td>Error</td>';

               echo '        </tr>';
            }

            echo '    </table>';
            echo '</div>';  //  End <div class="title_content">
            echo '</div>';  //  End <div class="bring">




            echo '<div class="bring">';
            if ($rates[$row['itm_id']] && $firms)
            {
               ?>
               <div class="title_content" style="width:100%;margin-top:3px;border:none;">
                  <table class="adbdr" cellpadding="0" cellspacing="0" style="width:100%;">   <!--tbl_tile_content-->
                     <tr>
                        <th rowspan="2"><p class="tile_content_h_title">Firm</p></th>
                     <th rowspan="2"><p class="tile_content_h_title">Workcentre</p></th>

                     <?php
                     // Displaying units.
                     $untcount = count($rates[$row['itm_id']]['units']);
                     $i = 0;
                     $anch = ($row['itm_status'] == 1) ? anchor("item_units_n_rates/before_add/jurk/$row[itm_id]", '<div class="tile_action"><img src="images/edit11.png" title="Edit Rates" /></div>') : '';
                     foreach ($rates[$row['itm_id']]['units'] as $unt)
                     {
                        $link = ($i < ($untcount - 1)) ? '' : $anch;
                        echo '<th colspan="2"><p class="tile_content_h_title" style="text-align:center">' . $unt['unt_name'] . '</p>' . $link . '</th>';
                        $i++;
                     }
                     ?>

                     </tr>

                     <tr>
                        <?php
                        foreach ($rates[$row['itm_id']]['units'] as $unt)
                        {
                           echo '<th><p class="tile_content_h_title">Purchase</p></th>';
                           echo '<th><p class="tile_content_h_title">Sale</p></th>';
                        }
                        ?>
                     </tr>



                     <?php
                     foreach ($firms as $firm_id => $firm_name)
                     {
                        foreach ($workcentres as $wc)
                        {
                           if ($wc['wcntr_fk_firms'] == $firm_id)
                           {
                              $wc_id = $wc['wcntr_id'];
                              ?> 
                              <tr>
                                 <td style="width:40px;word-wrap: break-word;"><?php echo $firm_name ?></td>


                                 <td style="width:40px;word-wrap: break-word;"><?php echo $wc['wcntr_name']; ?></td>

                                 <?php
                                 foreach ($rates[$row['itm_id']]['units'] as $unt)
                                 {
                                    ?>
                                    <td> <?php echo $rates[$row['itm_id']]['p_rates'][$wc_id][$unt['unt_id']] ?> </td>
                                    <td> <?php echo $rates[$row['itm_id']]['s_rates'][$wc_id][$unt['unt_id']] ?> </td>

                                 <?php }
                                 ?>



                              </tr>
                              <?php
                           }
                        }
                     }
                     ?>
                  </table>
               </div>
               <?php
            }

            echo '</div>';  //  End <div class="bring">





            echo '</div>';  //  End <div class="tile scroll-pane">
            echo '</td>';
            if ($col == $max_cols)
            {
               $tr_tag = '</tr>';
               echo $tr_tag;
               $col = 0;
            }
            $slNo++;
         }

         if ($col && ($col < $max_cols))
            for ($col; $col < $max_cols; $col++)
               echo '<td></td>';
         if ($tr_tag == '<tr>') // If <tr> is not clossed
            echo '</tr>';

         echo '</table>';
      }
      ?>
   </div>   <!--<div id="tile_container">-->










</div><!--<div class="dv-bottom-content"> -->



<script type="text/javascript">

   $(document).ready(function() {

      $('.tile .toggleStatus').click(function() {

         var item_name = $(this).closest('.tile').find('.itm_name_popup').val();

         item_name.replace('"', '\"');
         var item_id = $(this).closest('.tile').find('.itm_id_popup').val();
         var item_status = $(this).closest('.tile').find('.itm_status_popup').val();
         var msg = (item_status == 1) ? " deactivate " : " activate ";
         msg += "'" + item_name + "' ";

         if (!confirm('Do you want to ' + msg))
            return;

         //Setting input.
         var inputs = {itm_id: item_id}; // eg: {parent_id: parent_id, status: 1}

         // Disabling whole page background till Ajax respond.
         $.blockUI(); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"

         $.post(site_url + "items/toggleStatus", inputs, function(result) {

            alert(result);

            // enabling the whole page after ajax response.
            $(document).ajaxStop($.unblockUI); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"


            // Refreshing the browser after 1 second
            setTimeout(function() {
               location.reload();
            }, 1000);

         });
      });

      $('#tile_container .title_content .tile_action #edtunt').click(function() {

         var p_key = $(this).closest('.tile').find('.itm_id_popup').val();
         var itm_name = $(this).closest('.tile').find('.itm_name_popup').val();

         //Initializing popup box  
         init_pop_unit_edit();

         $('#pop_unit_edit .namespan_box .namespan').text(itm_name);
         $('#pop_unit_edit #p_key').val(p_key);

         $.post(site_url + 'units/getParentUnit', {itm_id: p_key}, function(result) {
            // Accidentally some white spaces have preppended with the result. I couldn't understand how it was came?. so trimming the whitespaces manually.
            result = $.trim(result);
            $('#pop_unit_edit .unt_tbl tbody tr:first .unit_name').focus().val(result);
         });

         //Loading popupBox.
         loadPopup('pop_unit_edit');
      });

   });


   function beforeAjax()
   {
      $('#itm_fk_item_head').hide();
      $('.ajaxLoaderContainer').show();
   }

   function afterAjax()
   {
      $('#itm_fk_item_head').show();
      $('.ajaxLoaderContainer').hide();
   }

</script>
