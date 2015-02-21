<!--

Notes: 
    
    1.  All popup-box must have a class "popupBox".

-->

<div id="pop_vehicle_workcentres_add" class="popupBox">
   <div class="dv-popupTitle">
      <span class="clossButton popupAction" title="Close Window"> X</span>
      <span class="titleColumn">ADD VEHICLE TO THE VEHICLE</span> 
   </div>


   <div class="namespan_box"><span class="namespan" ></span></div>



   <!--<input type="hidden" id="p_key" value="">-->
   <input type="hidden" id="vwc_fk_vehicles" value="">

   <table class="unt_tbl">

      <tbody>
         <tr>
            <th>Workcentre</th>
            <td>
               <select id="vwc_fk_workcentres"></select>
            </td>                                   
         </tr>  

         <tr>
            <th>Vehicle Cost</th>
            <td>
               <input type="text" id="vwc_cost" class="valuable">
            </td>
         </tr>


         <tr>
            <th>O.B</th>
            <td>
               <input type="text" id="vwc_ob" class="numberOnly"><br>
               <input type="radio" name="vwc_ob_mode" value="1" checked="checked"> Cr. &nbsp;
               <input type="radio" name="vwc_ob_mode" value="2" > Dr. 
            </td>
         </tr>

         <tr>
            <th>Hourly Rent</th>
            <td>
               <input type="text" id="vwc_hourly_rate" class="numberOnly valuable">
            </td>
         </tr>


         <tr>
            <th>Daily Rent</th>
            <td>
               <input type="text" id="vwc_daily_rate" class="numberOnly valuable">
            </td>
         </tr>

         <tr>
            <th>Monthly Rent</th>
            <td>
               <input type="text" id="vwc_monthly_rate" class="numberOnly valuable">
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

      //Saving data
      $('#pop_vehicle_workcentres_add .save').click(function() {

         //Showing Loading image till ajax responds
         $("#pop_vehicle_workcentres_add .responseMessage").html('<img src="images/ajax-loader.gif" /> Loading....');

         // Disabling whole page background till Ajax respond.
         $.blockUI(); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"

         var vwc_ob_mode = $('#pop_vehicle_workcentres_add input[type=radio][name=vwc_ob_mode]:checked').val();

         var postData = {
            vwc_fk_vehicles: $('#pop_vehicle_workcentres_add #vwc_fk_vehicles').val(),
            vwc_fk_workcentres: $('#pop_vehicle_workcentres_add #vwc_fk_workcentres').val(),
            vwc_cost: $('#pop_vehicle_workcentres_add #vwc_cost').val(),
            vwc_ob: $('#pop_vehicle_workcentres_add #vwc_ob').val(),
            vwc_ob_mode: vwc_ob_mode,
            vwc_hourly_rate: $('#pop_vehicle_workcentres_add #vwc_hourly_rate').val(),
            vwc_daily_rate: $('#pop_vehicle_workcentres_add #vwc_daily_rate').val(),
            vwc_monthly_rate: $('#pop_vehicle_workcentres_add #vwc_monthly_rate').val()
         };

         $.post(site_url + "vehicle_workcentres/add", postData, function(result) {

            if (result == 1)
            {
               // Hiding save buttun to prevent the chance for re-entering of data.
               $('#pop_vehicle_workcentres_add .save').hide();
               $("#pop_vehicle_workcentres_add .responseMessage").html('<div class="pop_success"><img src="images/success_1.gif" width="15" height="15"  /> Data saved successfully!!!</div>');
               // Refreshing page automatically after 1 seconds.
               setTimeout(function() {
                  location.reload();
               }, 500);
            }
            else
               $("#pop_vehicle_workcentres_add .responseMessage").html(result);
            
            // enabling the whole page after ajax response.
            $(document).ajaxStop($.unblockUI); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"

         });
      });
   });
   
   
   function init_pop_vehicle_workcentres_add(vhcl_id) {

      // Setting initial values of elements.
      $('#pop_vehicle_workcentres_add #vwc_fk_vehicles').val('');
      $('#pop_vehicle_workcentres_add #vwc_fk_workcentres').html('<option value="">-- No Workcentres --</option>');
      $('#pop_vehicle_workcentres_add #vwc_cost').val('');
      $('#pop_vehicle_workcentres_add #vwc_ob').val('');
      $('#pop_vehicle_workcentres_add input[type=radio][name=vwc_ob_mode][value=1]').prop('checked', true); // Setting Cr. as default mode
      $('#pop_vehicle_workcentres_add #vwc_hourly_rate').val('');
      $('#pop_vehicle_workcentres_add #vwc_daily_rate').val('');
      $('#pop_vehicle_workcentres_add #vwc_monthly_rate').val('');
      
      
      $('#pop_vehicle_workcentres_add #pop_drag').prop('checked', 'true');
      $('#pop_vehicle_workcentres_add #pop_self_close').prop('checked', 'true');
      $('#pop_vehicle_workcentres_add .save').show();
      $("#pop_vehicle_workcentres_add .responseMessage").html('');
      
      //Making the popup box draggable.
      $("#pop_vehicle_workcentres_add").draggable();
      dragUndrag($("#pop_vehicle_workcentres_add"));
   }





</script>
