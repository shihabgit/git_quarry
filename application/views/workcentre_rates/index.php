<?php $this->load->view('workcentre_rates/pop_edit'); ?>

<link href="css/<?php echo $this->themes[$this->theme]['text']; ?>/tiles.css" rel="stylesheet" type="text/css" />




<!-- Fancy scrollbar START 	-->
<link type="text/css" href="plugins/fancy_scrollbars/by_shihab/css/jquery.jscrollpane.css" rel="stylesheet" media="all" />

<style type="text/css" id="page-css">
   /* Styles specific to this particular page */
   .scroll-pane
   {
      width: 98%;
      height: 450px;
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
   <?php echo form_open("workcentre_rates", array('id' => 'searchForm')); ?>

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
            <div class="inputblok" style="width: 100%;">
               <div class="sec_container">
                  <p class="input-categories">Basic Details</p>
                  <table class="sec_table">
                     <tr>
                        <th>Item Category</th>
                        <td>
                           <select name="itmcat_id" id="itmcat_id" onchange="resetOptions(this, 'itm_fk_item_head', 'item_heads/getItemHead', beforeAjax1, afterAjax1);" >
                              <?php echo get_options2($itmcats, ifSet2('itmcat_id'), true, '--- select ---'); ?>
                           </select>
                        </td>

                     </tr> 
                     <tr>
                        <th>Item Head</th>
                        <td>
                           <select name="itm_fk_item_head" id="itm_fk_item_head">
                              <?php echo get_options2($itm_heads, ifSet2('itm_fk_item_head'), true, '--- Select ---'); ?>
                           </select>
                           <div class="ajaxLoader_itm_head"> 
                              <img src="images/ajax-loader2.gif"> 
                              <img src="images/ajax-loader2.gif">
                           </div>  
                        </td>
                     </tr>



                     <tr>
                        <th>Item Name</th>
                        <td>
                           <input type="text" name="itm_name" value="<?php echo ifSet2('itm_name') ?>" >

                           <br>
                           <input type="radio" name="itm_status" value="1" <?php echo ifSetRadio2('itm_status', 1, true) ?> />
                           <span class="multy_options">Active</span>

                           <input type="radio" name="itm_status" value="2" <?php echo ifSetRadio2('itm_status', 2) ?> />
                           <span class="multy_options">Inactive</span> 

                           <input type="radio" name="itm_status" value="0" <?php echo ifSetRadio2('itm_status', 0) ?> />
                           <span class="multy_options">All</span>
                        </td>
                     </tr>



                     <tr>
                        <th>Workcentre</th>
                        <td>
                           <select name="wcntr_id[]" multiple="multiple"  style="height:100px;">
                              <?php echo get_options2($workcentres, ifSet2('wcntr_id')); ?>
                           </select>

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

         <a href="<?php echo site_url('workcentre_rates/add'); ?>" >
            <div class="right_action"  title="<?php echo lang('index_create_user_link') ?>">
               <div class="settings_btn">
                  <img  src="images/Add button.png" height="24" width="24" />
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
            ?>

            <td>


               <div class="tile scroll-pane blackScroll">

                  <div class="bring">
                     <?php
                     // Following hidden elements are to use with popup: units/pop_edit.php. 
                     echo '<input type="hidden" class="itm_id" value="' . $row['itm_id'] . '">';
                     echo "<input type='hidden' class='itm_name' value='" . $row['itm_name'] . "'>";
                     $GLOBALS['itm_id'] = $row['itm_id'];
                     $GLOBALS['itm_name'] = $row['itm_name'];

                     $status = ($row['itm_status'] == 1) ? 'active' : 'inactive';
                     ?>

                     <div class="tile_name <?php echo $status; ?>">
                        <?php echo $row['itm_name']; ?>
                     </div>
                     <div class="tile_description"><?php echo $row['itmcat_name'] ?> > <?php echo $row['itmhd_name'] ?></div>                  
                  </div>  <!-- <div class="bring">  -->

                  <hr>

                  <?php $this->load->view('workcentre_rates/index_wrt'); ?>


               </div>    <!-- <div class="tile scroll-pane">  -->
            </td>
            <?php
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

      afterAjax1();
      afterAjax2();

      $('.tile .wrt_edit').click(function() {

         // Disabling whole page background till Ajax respond.
         $.blockUI(); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"

         var itm_name = $(this).closest('.tile').find('.itm_name').val();
         var wcntr_to = $(this).closest('.wrt_tr').find('.wcTo_name').val();

         var wrt_ids = [];

         //build an array of ids.
         $(this).closest('.wrt_tr').find('.wrt_id').each(function(i, obj) {
            wrt_ids[i] = $(obj).val();
         });


         //Initializing popup box.
         init_pop_workcentre_rates_edit();

         $('#pop_workcentre_rates_edit .titleColumn').html('EDIT UNIT SELLING RATES OF "'+itm_name+'"');

         $('#pop_workcentre_rates_edit .namespan_box .namespan').text(wcntr_to);
         
         $.post(site_url + 'workcentre_rates/beforeEdit', {wrt_ids: wrt_ids}, function(data) {

            $("#pop_workcentre_rates_edit .tbl_container").html(data);

            //Loading popupBox.
            loadPopup('pop_workcentre_rates_edit');

            // enabling the whole page after ajax response.
            $(document).ajaxStop($.unblockUI); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"
         });

      });



      // Deleting Freight charges of The Vehicle between workcentres.
      $('.tile .wrt_delete').click(function() {

         var itm_name = $(this).closest('.tile').find('.itm_name').val();
         var wcntr_to = $(this).closest('.wrt_tr').find('.wcTo_name').val();

         var wrt_ids = [];

         //build an array of ids.
         $(this).closest('.wrt_tr').find('.wrt_id').each(function(i, obj) {
            wrt_ids[i] = $(obj).val();
         });





         var msg = "Do you want to delete the all selling rates of '"+itm_name+"' for Workcentre: " + wcntr_to;
         if (!confirm(msg))
            return;

         //Setting input.
         var inputs = {wrt_ids: wrt_ids}; // eg: {parent_id: parent_id, status: 1}

         // Disabling whole page background till Ajax respond.
         $.blockUI(); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"

         $.post(site_url + "workcentre_rates/delete", inputs, function(result) {

            alert(result);

            // enabling the whole page after ajax response.
            $(document).ajaxStop($.unblockUI); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"

            // Refreshing the browser & clearing cache.
            location.reload(true);

         });

      });



   });


   function beforeAjax1()
   {
      $('#itm_fk_item_head').hide();
      $('.ajaxLoader_itm_head').show();
   }

   function afterAjax1()
   {
      $('#itm_fk_item_head').show();
      $('.ajaxLoader_itm_head').hide();
   }


   function beforeAjax2()
   {
      $('#pdst_id').hide();
      $('.ajaxLoader_pdst').show();
   }

   function afterAjax2()
   {
      $('#pdst_id').show();
      $('.ajaxLoader_pdst').hide();
   }

</script>
<script type="text/javascript" src="plugins/blockui-master/jquery.blockUI.js"></script> 
