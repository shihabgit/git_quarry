<!--

Notes: 
    
    1.  All popup-box must have a class "popupBox".

-->

<div id="pop_party_destinations_add" class="popupBox">
   <div class="dv-popupTitle">
      <span class="clossButton popupAction" title="Close Window"> X</span>
      <span class="titleColumn">ADD DESTINATIONS</span> 
   </div>

   <!--Value of the following element is what the value of '.itm_name_popup' in items/index.php. It will be added by JQuery on loading popup.-->
   <div class="namespan_box"><span class="namespan" ></span></div>



   <!--Value of the following element is what the value of '.itm_id_popup' in items/index.php. It will be added by JQuery on loading popup.-->
   <input type="hidden" id="pdst_fk_parties" value="">


   <table class="unt_tbl">

      <tbody>

         <tr>
            <th>Availability</th>
            <td colspan="3">
               <select name="wcntr_id[]" class="wcntr_ids" multiple="multiple"  style="height:100px;">
                  <?php echo get_options2($workcentres_2, ifSet('wcntr_id')); ?>
               </select>                              
                           <p class="help">Hold <b>Ctrl</b> Key to select multiple workcentres.</p>
            </td>
         </tr>


         <tr> 

            <th>Date</th>
            <td>
               <div class="dateContainer" style="padding: 0px;margin:0px;">
                  <div style="padding-top: 4px;float: left;">
                     <input class="dateField inputDate" readonly="" id="pdst_date" value="" /> 
                  </div>
                  <div style="padding-left: 5px;float: right;"><img src="images/calendar.gif"  class="calendarButton"> </div>
               </div>
            </td>      

            <th>Reg Name</th>
            <td>
               <select id="pdst_fk_party_license_details" > 
                  <?php echo get_options2($license_options, '', true, '--- select ---'); ?>
               </select>

               <div class="ajaxLoaderContainer"> 
                  <img src="images/ajax-loader2.gif"> 
                  <img src="images/ajax-loader2.gif"> 
               </div>  
            </td>  
         </tr> 
         <tr> 
            <th>Destination Name</th>
            <td>
               <input type="text" id="pdst_name" value="" >
            </td>    
            <th>Category</th>
            <td>
               <input type="radio" name="pdst_category" id="pdst_category" value="1" > Supplier 
               <input type="radio" name="pdst_category" id="pdst_category" value="2" checked="checked" > Customer 
               <input type="radio" name="pdst_category" id="pdst_category" value="3" > Both 
            </td>            
         </tr>  
         <tr>
            <th>Phone</th>
            <td>
               <input type="text" id="pdst_phone" value="" >
            </td>     
            <th>Email</th>
            <td>
               <input type="text" id="pdst_email" value="" >
            </td>                                   
         </tr> 

      </tbody>

   </table>

   <div class="dragSaveBox">
      <div class="dragColumn">
         <input type="checkbox" id="pop_drag"> Drag 
         <input type="checkbox" id="pop_self_close"> Self Close
      </div>
      <div class="saveColumn">
      <!--<input type="button" class="btn"  title="Save Data"  value="SAVE">-->
         <img src="images/save.png" class="save" title="Save Data"> 

      </div>
   </div>
   <div class="clear_boath"></div>
   <p class="responseMessage"></p>
</div>




<script type="text/javascript">



   $(document).ready(function() {


      afterAjax();

      //Saving data
      $('#pop_party_destinations_add .save').click(function() {
         //Showing Loading image till ajax responds
         $("#pop_party_destinations_add .responseMessage").html('<img src="images/ajax-loader.gif" /> Loading....');
         // Disabling whole page background till Ajax respond.
         $.blockUI(); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"

         if (!$('#pop_party_destinations_add .wcntr_ids :selected').val())
         {
            $("#pop_party_destinations_add .responseMessage").html('<div class="failureHead"><img src="images/failure.gif" width="15" height="15" /> Error!!!<div class="pop_failure">No workcentres selected!</div></div>');
            return;
         }


         var workcentres = [];

         //build an array of selected values
         $('#pop_party_destinations_add .wcntr_ids :selected').each(function(i, selected) {
            workcentres[i] = $(selected).val();
         });




         var postData = {
            wcntrs: workcentres,
            pdst_fk_parties: $('#pop_party_destinations_add #pdst_fk_parties').val(),
            pdst_date: $('#pop_party_destinations_add #pdst_date').val(),
            pdst_fk_party_license_details: $('#pop_party_destinations_add #pdst_fk_party_license_details').val(),
            pdst_name: $('#pop_party_destinations_add #pdst_name').val(),
            pdst_phone: $('#pop_party_destinations_add #pdst_phone').val(),
            pdst_email: $('#pop_party_destinations_add #pdst_email').val(),
            pdst_category: $('#pop_party_destinations_add #pdst_category:checked').val()
         };
         $.post(site_url + "party_destinations/add", postData, function(result) {

            if (result == 1)
            {
               // Hiding save buttun to prevent the chance for re-entering of data.
               $('#pop_party_destinations_add .save').hide();
               $("#pop_party_destinations_add .responseMessage").html('<div class="pop_success"><img src="images/success_1.gif" width="15" height="15"  /> Data saved successfully!!!</div>');
               // Refreshing page automatically after 1 seconds.
               setTimeout(function() {
                  location.reload(true);
               }, 1000);
            }
            else
               $("#pop_party_destinations_add .responseMessage").html(result);
            // enabling the whole page after ajax response.
            $(document).ajaxStop($.unblockUI); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"


         });
      });
   });


   function init_pop_party_destinations_add() {

      // Setting initial values of elements.

      $('#pop_party_destinations_add .wcntr_ids').val('');
      $('#pop_party_destinations_add #pdst_fk_parties').val('');
      $('#pop_party_destinations_add #pdst_fk_party_license_details').val('');
      $('#pop_party_destinations_add #pdst_name').val('');
      $('#pop_party_destinations_add #pdst_date').val(getToday());
      $('#pop_party_destinations_add #pdst_phone').val('');
      $('#pop_party_destinations_add #pdst_email').val('');

      $('#pop_party_destinations_add #pop_drag').prop('checked', 'true');
      $('#pop_party_destinations_add #pop_self_close').prop('checked', 'true');
      $('#pop_party_destinations_add .save').show();
      $("#pop_party_destinations_add .responseMessage").html('');
      //Making the popup box draggable.
      $("#pop_party_destinations_add").draggable();
      dragUndrag($("#pop_party_destinations_add"));
   }

   function setLicense2(pty_id, sel)
   {
      $('#pop_party_destinations_add #pdst_fk_party_license_details').html('<option value="">*** No Data ***</option>');
      if (!pty_id)
         return;

      var path = site_url + 'party_license_details/getAvailableLicenses';
      beforeAjax2();
      $.getJSON(path, {pty_id: pty_id}, function(data) {
         var options = '';
         for (var x = 0; x < data.length; x++) {
            options += '<option value="' + data[x]['value'] + '">' + data[x]['text'] + '</option>';
         }
         $('#pop_party_destinations_add #pdst_fk_party_license_details').html(options);
         if (sel)
         {
            $('#pop_party_destinations_add #pdst_fk_party_license_details').val('');
         }
         afterAjax2();
      });
   }

   function beforeAjax2()
   {
      $('#pdst_fk_party_license_details').hide();
      $('.ajaxLoaderContainer').show();
   }

   function afterAjax2()
   {
      $('#pdst_fk_party_license_details').show();
      $('.ajaxLoaderContainer').hide();
   }

</script>
<script type="text/javascript" src="plugins/blockui-master/jquery.blockUI.js"></script> 
