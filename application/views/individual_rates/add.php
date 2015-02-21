<?php $this->load->view('item_category/pop_add'); ?>
<?php $this->load->view('item_heads/pop_add'); ?>
<div class="dv-top-content" align="center">
   <?php echo form_open("individual_rates/add", array('id' => 'add_form')); ?>
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
            <div style="background-color:#FFF;color:#000;text-align: left;padding: 5px;">
               1. In a workcentre the unit-rate of an item for a destination must be unique. It must be checked before add.
            </div>
            <div class="inputblok" style="width: 100%;">

               <div class="sec_container">
                  <p class="input-categories">Basic Details</p>
                  <table class="sec_table">

                     <tr>
                        <th rowspan="2">Workcentre</th>
                        <td rowspan="2">
                           <select name="indv_fk_workcentres[]" multiple="multiple" id="wcntr_id" style="height:100px; width: 300px;">
                              <?php echo get_options2($workcentres, ifSet2('indv_fk_workcentres')); ?>
                           </select>
                           <p class="help">Hold <b>Ctrl</b> Key to select multiple workcentres.</p>
                           <?php echo form_error('indv_fk_workcentres'); ?>
                        </td>

                        <th style="padding-left: 60px;white-space: nowrap;">Item Category</th>
                        <td>
                           <select name="itmcat_id" id="itmcat_id" onchange="resetOptions(this, 'itmhd_id,indv_fk_items,indv_fk_units', 'item_heads/getItemHead', beforeAjax1, afterAjax1);" >
                              <?php echo get_options2($itmcats, ifSet2('itmcat_id'), true, '--- select ---'); ?>
                           </select>
                           <?php echo form_error('itmcat_id'); ?>
                        </td>
                     </tr> 


                     <tr>
                        <th style="padding-left: 60px;white-space: nowrap;">Item Head</th>
                        <td>
                           <select name="itmhd_id" id="itmhd_id" onchange="resetOptions(this, 'indv_fk_items,indv_fk_units', 'items/getItems', beforeAjax2, afterAjax2);">
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
                        <th rowspan="2">Party</th>
                        <td rowspan="2">
                           <select name="pty_id[]" multiple="multiple" id="pty_id"  style="height:100px; width: 300px;">
                              <?php echo get_options2($parties, ifSet2('pty_id'), true, '--- select ---'); ?>
                           </select>
                           <div class="partyLoader"> 
                              <img src="images/ajax-loader2.gif"> 
                              <img src="images/ajax-loader2.gif">
                           </div>  
                           <p class="help help_pty">Hold <b>Ctrl</b> Key to select multiple parties.</p>
                           <?php echo form_error('pty_id'); ?>
                        </td>
                        <th style="padding-left: 60px;white-space: nowrap;">Item</th>
                        <td>
                           <select name="indv_fk_items" id="indv_fk_items" onchange="resetOptions(this, 'indv_fk_units', 'units/getUnitsByItem', beforeAjax4, afterAjax4);">
                              <?php echo get_options2($items, ifSet2('indv_fk_items'), true, '--- Select ---'); ?>
                           </select>
                           <?php echo form_error('indv_fk_items'); ?>
                           <div class="ajaxLoaderContainer_2"> 
                              <img src="images/ajax-loader2.gif"> 
                              <img src="images/ajax-loader2.gif">
                           </div> 
                        </td>
                     </tr>

                     <tr>
                        <th style="padding-left: 60px;white-space: nowrap;">Unit</th>
                        <td>
                           <select name="indv_fk_units" id="indv_fk_units">
                              <?php echo get_options2($units, ifSet2('indv_fk_units'), true, '--- Select ---'); ?>
                           </select>
                           <?php echo form_error('indv_fk_units'); ?>
                           <div class="ajaxLoaderContainer_4"> 
                              <img src="images/ajax-loader2.gif"> 
                              <img src="images/ajax-loader2.gif">
                           </div> 
                        </td>
                     </tr>

                     <tr>
                        <th rowspan="3">Destination</th>
                        <td rowspan="3" style="height: 210px;">
                           <select name="indv_fk_party_destinations[]" multiple="multiple" id="indv_fk_party_destinations"  style="height:200px; width: 300px;">
                              <?php echo get_optGroups($opt_group, $destinations, 'pty_id', 'pdst_id', 'pdst_name', ifSet2('indv_fk_party_destinations')); ?>
                           </select>



                           <p class="help help_dest">Hold <b>Ctrl</b> Key to select multiple destinations.</p>
                           <?php echo form_error('indv_fk_party_destinations'); ?>
                           <div class="ajaxLoaderContainer_3"> 
                              <img src="images/ajax-loader2.gif"> 
                              <img src="images/ajax-loader2.gif">
                           </div>  
                        </td>

                        <th style="padding-left: 60px;white-space: nowrap;">Rate</th>
                        <td>
                           <input type="text" name="indv_p_rate" placeholder="Purachase" value="<?php echo set_value('indv_p_rate') ?>" class="numberOnly" style="width:100px">
                           <input type="text" name="indv_s_rate" placeholder="Selling" value="<?php echo set_value('indv_s_rate') ?>" class="numberOnly" style="width:100px">
                           <?php echo form_error('indv_p_rate'); ?>
                           <?php echo form_error('indv_s_rate'); ?>
                        </td>
                     </tr>


                     <tr>
                        <th></th>
                        <td></td>
                     </tr>


                  </table>
               </div>          <!--     End of Personal Details-->
            </div>
         </td>
      </tr>
   </table>

   <div id="submit_container">
      <hr />
      <p style="margin: 0; padding: 5px; color:#E70; font-family: fantasy; font-size: 11pt; text-align: left;"><b>Note:</b> <br>1. On adding rates, the rate of an Item in a Workcentre for a Party Destinations will be neglected by the system automatically if the Destination is not registered under the Workcentre.<br>
      2. If the rate of an item for a party in any of the selected workcentres already added, it will be replaced by the new rates.
      </p>
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
      afterAjax4();
      $('.partyLoader').hide();

      $("#pty_id").change(function() {

         if (!$('#pty_id :selected').val())
         {
            $('#indv_fk_party_destinations').html('<option value="">--No Destinations--</option>');
            return;
         }

         var workcentres = [];
         var parties = [];

         //build an array of selected values
         $('#wcntr_id :selected').each(function(i, selected) {
            workcentres[i] = $(selected).val();
         });

         //build an array of selected values
         $('#pty_id :selected').each(function(i, selected) {
            parties[i] = $(selected).val();
         });

         // Showing ajax loading image.
         $('.ajaxLoaderContainer_3').show();

         // Hiding dropdown till ajax load.
         $('#indv_fk_party_destinations').hide();

         //Hiding help text.
         $('.help_dest').hide();

         $.getJSON(site_url + 'party_destinations/getDestinationsByParties', {pty_ids: parties,wcntr_ids:workcentres}, function(json) {

            // Creating <optgroup> from json out put
            creatOptGroup($('#indv_fk_party_destinations'), json);

            // Hiding ajax loading image.
            $('.ajaxLoaderContainer_3').hide();

            // Showing dropdown after ajax load.
            $('#indv_fk_party_destinations').show();

            // Showing help text.
            $('.help_dest').show();
         });
      });


      $("#wcntr_id").change(function() {
               
         $('#indv_fk_party_destinations').html('<option value="">--No Destinations--</option>');
         $('#pty_id').html('<option value="">--No Parties--</option>');
         

         var workcentres = [];

         //build an array of selected values
         $('#wcntr_id :selected').each(function(i, selected) {
            workcentres[i] = $(selected).val();
         });

         // Showing ajax loading image.
         $('.partyLoader').show();

         // Hiding dropdown till ajax load.
         $('#pty_id').hide();

         //Hiding help text.
         $('.help_pty').hide();




         $.getJSON(site_url + 'parties/getPartiesByWorkcentres', {wcntr_ids: workcentres}, function(data) {

            var options = '';
            for (var x = 0; x < data.length; x++) {
               options += '<option value="' + data[x]['value'] + '">' + data[x]['text'] + '</option>';
            }
            $('#pty_id').html(options);

            // Hiding ajax loading image.
            $('.partyLoader').hide();

            // Showing dropdown after ajax load.
            $('#pty_id').show();

            // Showing help text.
            $('.help_pty').show();
         });
      });

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
      $('#indv_fk_items').hide();
      $('.ajaxLoaderContainer_2').show();
   }

   function afterAjax2()
   {
      $('#indv_fk_items').show();
      $('.ajaxLoaderContainer_2').hide();
   }

   function beforeAjax3()
   {
      $('#indv_fk_party_destinations').hide();
      $('.ajaxLoaderContainer_3').show();
   }

   function afterAjax3()
   {
      $('#indv_fk_party_destinations').show();
      $('.ajaxLoaderContainer_3').hide();
   }

   function beforeAjax4()
   {
      $('#indv_fk_units').hide();
      $('.ajaxLoaderContainer_4').show();
   }

   function afterAjax4()
   {
      $('#indv_fk_units').show();
      $('.ajaxLoaderContainer_4').hide();
   }

</script>

<script type="text/javascript" src="plugins/blockui-master/jquery.blockUI.js"></script> 