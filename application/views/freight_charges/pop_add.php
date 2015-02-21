<!--

Notes: 
    
    1.  All popup-box must have a class "popupBox".

-->

<div id="pop_freight_charges_add" class="popupBox">
   <div class="dv-popupTitle">
      <span class="clossButton popupAction" title="Close Window"> X</span>
      <span class="titleColumn">ADD FREIGHT CHARGES</span> 
   </div>

   <!--Value of the following element is what the value of '.itm_name_popup' in items/index.php. It will be added by JQuery on loading popup.-->
   <div class="namespan_box"><span class="namespan" ></span></div>



   <!--Value of the following element is what the value of '.itm_id_popup' in items/index.php. It will be added by JQuery on loading popup.-->
   <input type="hidden" id="fc_fk_vehicles" value="">
   <input type="hidden" id="fc_fk_workcentres" value="">
 
   <table class="unt_tbl">

      <tbody>

         <tr>
            <th>Party</th>
            <td>
               <select id="pty_id"></select>
            </td>                                   
         </tr>
         <tr>
            <th>Destination</th>
            <td>
               <select id="fc_fk_party_destinations"></select>
               <div class="ajaxLoaderContainer"> 
                  <img src="images/ajax-loader2.gif"> 
                  <img src="images/ajax-loader2.gif"> 
               </div>
            </td>                                  
         </tr>    

         <tr>
            <th>Rent</th>
            <td>
               <input type="text" class="numberOnly" value="" id="fc_rent">
               <input type="checkbox" id="fc_add_rent">  Add rent to bill amount.
            </td>
         </tr>  

         <tr>
            <th>Bata</th>
            <td>
               <input type="text" class="numberOnly" value="" id="fc_bata">
               <input type="checkbox" id="fc_add_bata">  Add bata to bill amount.
            </td>
         </tr>

         <tr>
            <th>Loading</th>
            <td>
               <input type="text" class="numberOnly" value="" id="fc_loading">
               <input type="checkbox" id="fc_add_loading">  Add loading charge to bill amount.
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
      $('#pop_freight_charges_add .save').click(function() {
         //Showing Loading image till ajax responds
         $("#pop_freight_charges_add .responseMessage").html('<img src="images/ajax-loader.gif" /> Loading....');

         // Disabling whole page background till Ajax respond.
         $.blockUI(); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"



         var fc_add_rent = $('#pop_freight_charges_add #fc_add_rent').prop('checked') ? 1 : 2;
         var fc_add_bata = $('#pop_freight_charges_add #fc_add_bata').prop('checked') ? 1 : 2;
         var fc_add_loading = $('#pop_freight_charges_add #fc_add_loading').prop('checked') ? 1 : 2;

         var postData = {
            fc_fk_vehicles: $('#pop_freight_charges_add #fc_fk_vehicles').val(),
            fc_fk_workcentres: $('#pop_freight_charges_add #fc_fk_workcentres').val(),
            fc_fk_party_destinations: $('#pop_freight_charges_add #fc_fk_party_destinations').val(),
            fc_rent: $('#pop_freight_charges_add #fc_rent').val(),
            fc_bata: $('#pop_freight_charges_add #fc_bata').val(),
            fc_loading: $('#pop_freight_charges_add #fc_loading').val(),
            fc_add_rent: fc_add_rent,
            fc_add_bata: fc_add_bata,
            fc_add_loading: fc_add_loading
         };

         $.post(site_url + "freight_charges/add", postData, function(result) {

            if (result == 1)
            {
               // Hiding save buttun to prevent the chance for re-entering of data.
               $('#pop_freight_charges_add .save').hide();

               $("#pop_freight_charges_add .responseMessage").html('<div class="pop_success"><img src="images/success_1.gif" width="15" height="15"  /> Data saved successfully!!!</div>');

               // Refreshing page automatically after 1 seconds.
               setTimeout(function() {
                  location.reload();
               }, 500);

            }
            else
               $("#pop_freight_charges_add .responseMessage").html(result);

            // enabling the whole page after ajax response.
            $(document).ajaxStop($.unblockUI); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"


         });


      });



   });

   

   function init_pop_freight_charges_add() {

      // Setting initial values of elements.
      
      $('#pop_freight_charges_add #fc_fk_vehicles').val('');
      $('#pop_freight_charges_add #fc_fk_workcentres').val('');
      $('#pop_freight_charges_add #pty_id').html('<option value="">No Parties</option>');
      $('#pop_freight_charges_add #fc_fk_party_destinations').html('<option value="">No Destinations</option>');
      $('#pop_freight_charges_add .ajaxLoaderContainer').hide();
      $('#pop_freight_charges_add #fc_rent').val('');
      $('#pop_freight_charges_add #fc_bata').val('');
      $('#pop_freight_charges_add #fc_loading').val('');
      $('#pop_freight_charges_add #fc_add_rent').prop('checked', false);
      $('#pop_freight_charges_add #fc_add_bata').prop('checked', false);
      $('#pop_freight_charges_add #fc_add_loading').prop('checked', false);



      $('#pop_freight_charges_add #pop_drag').prop('checked', 'true');
      $('#pop_freight_charges_add #pop_self_close').prop('checked', 'true');
      $('#pop_freight_charges_add .save').show();
      $("#pop_freight_charges_add .responseMessage").html('');

      //Making the popup box draggable.
      $("#pop_freight_charges_add").draggable();
      dragUndrag($("#pop_freight_charges_add"));

   }

   $('#pop_freight_charges_add #pty_id').change(function() {
      var vhcl_id = $('#pop_freight_charges_add #fc_fk_vehicles').val();
      var wcntr_id = $('#pop_freight_charges_add #fc_fk_workcentres').val();
      var pty_id = $(this).val();

      if (!pty_id)
         return;

      // Showing ajax loading image.
      $('#pop_freight_charges_add .ajaxLoaderContainer').show();

      // Hiding dropdown till ajax load.
      $('#pop_freight_charges_add #fc_fk_party_destinations').hide();

      $.getJSON(site_url + 'freight_charges/getFreeDestinations',
              {
                 vhcl_id: vhcl_id,
                 pty_id: pty_id,
                 wcntr_id : wcntr_id
              }, function(data) {

         var options = '';
         for (var x = 0; x < data.length; x++) {
            options += '<option value="' + data[x]['value'] + '">' + data[x]['text'] + '</option>';
         }
        
         $('#pop_freight_charges_add #fc_fk_party_destinations').html(options);

         // Hiding ajax loading image.
         $('#pop_freight_charges_add .ajaxLoaderContainer').hide();

         // Showing dropdown after ajax load.
         $('#pop_freight_charges_add #fc_fk_party_destinations').show();

      });
   });






</script>
