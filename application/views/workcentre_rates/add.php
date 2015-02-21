<?php $this->load->view('item_category/pop_add'); ?>
<?php $this->load->view('item_heads/pop_add'); ?>
<div class="dv-top-content" align="center">
   <?php echo form_open("workcentre_rates/add", array('id' => 'add_form')); ?>
   <table width="70%" cellpadding="5" cellspacing="0" class="tbl_input">
      <tr>
         <td>

            <div class="title-box">
               <div id="img-container">
                  <img src="images/staffs.png" width="35" height="35"/>
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
                        <th rowspan="2">Workcentre From</th>
                        <td rowspan="2">
                           <select name="wrt_fk_workcentres_from[]" multiple="multiple" id="f_wcntr_id" style="height:100px; width: 300px;">
                              <?php echo get_options2($workcentres, ifSet2('wrt_fk_workcentres_from')); ?>
                           </select>
                           <p class="help">Hold <b>Ctrl</b> Key to select multiple workcentres.</p>
                           <?php echo form_error('wrt_fk_workcentres_from'); ?>
                        </td>

                        <th style="padding-left: 60px;white-space: nowrap;">Item Category</th>
                        <td>
                           <select name="itmcat_id" id="itmcat_id" onchange="resetOptions(this, 'itmhd_id,wrt_fk_items,wrt_fk_units', 'item_heads/getItemHead', beforeAjax1, afterAjax1);" >
                              <?php echo get_options2($itmcats, ifSet2('itmcat_id'), true, '--- select ---'); ?>
                           </select>
                           <?php echo form_error('itmcat_id'); ?>
                        </td>
                     </tr> 


                     <tr>
                        <th style="padding-left: 60px;white-space: nowrap;">Item Head</th>
                        <td>
                           <select name="itmhd_id" id="itmhd_id" onchange="resetOptions(this, 'wrt_fk_items,wrt_fk_units', 'items/getItems', beforeAjax2, afterAjax2);">
                              <?php echo get_options2($itm_heads, ifSet2('itmhd_id'), true, '--- Select ---'); ?>
                           </select>
                           <?php echo form_error('itmhd_id'); ?>
                           <div class="ajaxLoaderContainer_1"> 
                              <img src="images/ajax-loader2.gif"> 
                              <img src="images/ajax-loader2.gif">
                           </div>  
                        </td>
                     </tr>



                     <tr>
                        <th rowspan="3">Workcentre To</th>
                        <td rowspan="3">
                           <select name="wrt_fk_workcentres_to[]" multiple="multiple" id="t_wcntr_id" style="height:100px; width: 300px;">
                              <?php echo get_options2($workcentres, ifSet2('wrt_fk_workcentres_to')); ?>
                           </select>
                           <p class="help">Hold <b>Ctrl</b> Key to select multiple workcentres.</p>
                           <?php echo form_error('wrt_fk_workcentres_to'); ?> 
                        </td>
                        <th style="padding-left: 60px;white-space: nowrap;">Item</th>
                        <td>
                           <select name="wrt_fk_items" id="wrt_fk_items" onchange="resetOptions(this, 'wrt_fk_units', 'units/getUnitsByItem', beforeAjax3, afterAjax3);">
                              <?php echo get_options2($items, ifSet2('wrt_fk_items'), true, '--- Select ---'); ?>
                           </select>
                           <?php echo form_error('wrt_fk_items'); ?>
                           <div class="ajaxLoaderContainer_2"> 
                              <img src="images/ajax-loader2.gif"> 
                              <img src="images/ajax-loader2.gif">
                           </div> 
                        </td>
                     </tr>

                     <tr>
                        <th style="padding-left: 60px;white-space: nowrap;">Unit</th>
                        <td>
                           <select name="wrt_fk_units" id="wrt_fk_units">
                              <?php echo get_options2($units, ifSet2('wrt_fk_units'), true, '--- Select ---'); ?>
                           </select>
                           <?php echo form_error('wrt_fk_units'); ?>
                           <div class="ajaxLoaderContainer_3"> 
                              <img src="images/ajax-loader2.gif"> 
                              <img src="images/ajax-loader2.gif">
                           </div> 
                        </td>
                     </tr>

                     <tr>
                        <th style="padding-left: 60px;white-space: nowrap;">Selling Rate</th>
                        <td>
                           <input type="text" name="wrt_s_rate" value="<?php echo set_value('wrt_s_rate') ?>" class="numberOnly">
                           <?php echo form_error('wrt_s_rate'); ?>
                        </td>
                     </tr>

                  </table>
               </div>          <!--     End of Personal Details-->
            </div>
         </td>
      </tr>
   </table>

   <div id="submit_container">
      <hr />
      <div style="margin: 0; padding: 5px; color:#E70; font-family: fantasy; font-size: 11pt; text-align: left;"><b>Note:</b> 
         <br>1. If both the 'Workcentre From' and 'Workcentre To' are same, system will neglect the entry when inserting.
         <br>2. If the unit selling rate of an item between any of the workcentres already added, it will be replaced by the new unit rates.
         
      </div>
      <input type="submit" name="button2" class="collapse_btn" value="Submit" />
      <input type="button" class="collapse_btn reseter" name="button3" value="Reset" />
   </div>

   <?php echo form_close(); ?>


</div> 	<!--<div class="dv-top-content" >-->


<script type="text/javascript">

   $(document).ready(function() {
      afterAjax1();
      afterAjax2();
      afterAjax3();
   });

   function beforeAjax1()
   {
      $('#itmhd_id').hide();
      $('.ajaxLoaderContainer_1').show();
   }

   function afterAjax1()
   {
      $('#itmhd_id').show();
      $('.ajaxLoaderContainer_1').hide();
   }


   function beforeAjax2()
   {
      $('#wrt_fk_items').hide();
      $('.ajaxLoaderContainer_2').show();
   }

   function afterAjax2()
   {
      $('#wrt_fk_items').show();
      $('.ajaxLoaderContainer_2').hide();
   }

   function beforeAjax3()
   {
      $('#wrt_fk_party_destinations').hide();
      $('.ajaxLoaderContainer_3').show();
   }

   function afterAjax3()
   {
      $('#wrt_fk_party_destinations').show();
      $('.ajaxLoaderContainer_3').hide();
   }


</script>

<script type="text/javascript" src="plugins/blockui-master/jquery.blockUI.js"></script> 