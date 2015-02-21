<!--

Notes: 
    
    1.  All popup-box must have a class "popupBox".

-->

<div id="pop_inter_freight_charges_add" class="popupBox">
   <div class="dv-popupTitle">
      <span class="clossButton popupAction" title="Close Window"> X</span>
      <span class="titleColumn">ADD FREIGHT CHARGES</span> 
   </div>

   <!--Value of the following element is what the value of '.itm_name_popup' in items/index.php. It will be added by JQuery on loading popup.-->
   <div class="namespan_box"><span class="namespan" ></span></div>



   <!--Value of the following element is what the value of '.itm_id_popup' in items/index.php. It will be added by JQuery on loading popup.-->
   <input type="hidden" id="ifc_fkey_vehicles" value="">


   <table class="unt_tbl">

      <tbody>

         <tr>
            <th>Workcentre From</th>
            <td>
               <select id="ifc_fk_workcentres_from"></select>
            </td>                                   
         </tr>  
         <tr>
            <th>Workcentre To</th>
            <td>
               <select id="ifc_fk_workcentres_to"></select>
               <div class="ajaxLoaderContainer"> 
                  <img src="images/ajax-loader2.gif"> 
                  <img src="images/ajax-loader2.gif"> 
               </div>  
            </td>                                  
         </tr>    

         <tr>
            <th>Rent</th>
            <td>
               <input type="text" class="numberOnly" value="" id="ifc_rent">
               <input type="checkbox" id="ifc_add_rent">  Add rent to bill amount.
            </td>
         </tr>  

         <tr>
            <th>Bata</th>
            <td>
               <input type="text" class="numberOnly" value="" id="ifc_bata">
               <input type="checkbox" id="ifc_add_bata">  Add bata to bill amount.
            </td>
         </tr>

         <tr>
            <th>Loading</th>
            <td>
               <input type="text" class="numberOnly" value="" id="ifc_loading">
               <input type="checkbox" id="ifc_add_loading">  Add loading charge to bill amount.
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
      $('#pop_inter_freight_charges_add .save').click(function() {
         //Showing Loading image till ajax responds
         $("#pop_inter_freight_charges_add .responseMessage").html('<img src="images/ajax-loader.gif" /> Loading....');

         // Disabling whole page background till Ajax respond.
         $.blockUI(); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"


         var ifc_add_rent = $('#pop_inter_freight_charges_add #ifc_add_rent').prop('checked') ? 1 : 2;
         var ifc_add_bata = $('#pop_inter_freight_charges_add #ifc_add_bata').prop('checked') ? 1 : 2;
         var ifc_add_loading = $('#pop_inter_freight_charges_add #ifc_add_loading').prop('checked') ? 1 : 2;

         var postData = {
            ifc_fkey_vehicles: $('#pop_inter_freight_charges_add #ifc_fkey_vehicles').val(),
            ifc_fk_workcentres_from: $('#pop_inter_freight_charges_add #ifc_fk_workcentres_from').val(),
            ifc_fk_workcentres_to: $('#pop_inter_freight_charges_add #ifc_fk_workcentres_to').val(),
            ifc_rent: $('#pop_inter_freight_charges_add #ifc_rent').val(),
            ifc_bata: $('#pop_inter_freight_charges_add #ifc_bata').val(),
            ifc_loading: $('#pop_inter_freight_charges_add #ifc_loading').val(),
            ifc_add_rent: ifc_add_rent,
            ifc_add_bata: ifc_add_bata,
            ifc_add_loading: ifc_add_loading
         };

         $.post(site_url + "inter_freight_charges/add", postData, function(result) {

            if (result == 1)
            {
               // Hiding save buttun to prevent the chance for re-entering of data.
               $('#pop_inter_freight_charges_add .save').hide();

               $("#pop_inter_freight_charges_add .responseMessage").html('<div class="pop_success"><img src="images/success_1.gif" width="15" height="15"  /> Data saved successfully!!!</div>');

               // Refreshing page automatically after 1 seconds.
               setTimeout(function() {
                  location.reload(true);
               }, 500);

            }
            else
               $("#pop_inter_freight_charges_add .responseMessage").html(result);

            // enabling the whole page after ajax response.
            $(document).ajaxStop($.unblockUI); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"


         });


      });



   });

   //pty_name pty_date pty_phone  pty_email  

   function init_pop_inter_freight_charges_add() {

      // Setting initial values of elements.
      $('#pop_inter_freight_charges_add #ifc_fkey_vehicles').val('');
      $('#pop_inter_freight_charges_add #ifc_fk_workcentres_from').val('');
      $('#pop_inter_freight_charges_add #ifc_fk_workcentres_to').html('<option value="">No Workcentres</option>');
      $('#pop_inter_freight_charges_add .ajaxLoaderContainer').hide();
      $('#pop_inter_freight_charges_add #ifc_rent').val('');
      $('#pop_inter_freight_charges_add #ifc_bata').val('');
      $('#pop_inter_freight_charges_add #ifc_loading').val('');
      $('#pop_inter_freight_charges_add #ifc_add_rent').prop('checked', false);
      $('#pop_inter_freight_charges_add #ifc_add_bata').prop('checked', false);
      $('#pop_inter_freight_charges_add #ifc_add_loading').prop('checked', false);


// ifc_fkey_vehicles,ifc_fk_workcentres_from,ifc_fk_workcentres_to,ifc_rent,ifc_add_rent,ifc_bata,ifc_add_bata,ifc_loading,ifc_add_loading,

      $('#pop_inter_freight_charges_add #pop_drag').prop('checked', 'true');
      $('#pop_inter_freight_charges_add #pop_self_close').prop('checked', 'true');
      $('#pop_inter_freight_charges_add .save').show();
      $("#pop_inter_freight_charges_add .responseMessage").html('');

      //Making the popup box draggable.
      $("#pop_inter_freight_charges_add").draggable();
      dragUndrag($("#pop_inter_freight_charges_add"));

   }

   $('#pop_inter_freight_charges_add #ifc_fk_workcentres_from').change(function() {

      var vhcl_id = $('#pop_inter_freight_charges_add #ifc_fkey_vehicles').val();
      var ifc_fk_workcentres_from = $(this).val();

      if (!ifc_fk_workcentres_from)
         return;

      // Showing ajax loading image.
      $('#pop_inter_freight_charges_add .ajaxLoaderContainer').show();

      // Hiding dropdown till ajax load.
      $('#pop_inter_freight_charges_add #ifc_fk_workcentres_to').hide();

      $.getJSON(site_url + 'inter_freight_charges/getFreeWorkcentres',
              {
                 vhcl_id: vhcl_id,
                 ifc_fk_workcentres_from: ifc_fk_workcentres_from
              }, function(data) {

         var options = '';
         for (var x = 0; x < data.length; x++) {
            options += '<option value="' + data[x]['value'] + '">' + data[x]['text'] + '</option>';
         }
        
         $('#pop_inter_freight_charges_add #ifc_fk_workcentres_to').html(options);

         // Hiding ajax loading image.
         $('#pop_inter_freight_charges_add .ajaxLoaderContainer').hide();

         // Showing dropdown after ajax load.
         $('#pop_inter_freight_charges_add #ifc_fk_workcentres_to').show();

      });











   });






</script>
