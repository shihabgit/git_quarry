<!--

Notes: 
    
    1.  All popup-box must have a class "popupBox".

-->

<div id="pop_freight_charges_edit" class="popupBox">
   <div class="dv-popupTitle">
      <span class="clossButton popupAction" title="Close Window"> X</span>
      <span class="titleColumn">EDIT FREIGHT CHARGES</span> 
   </div>

   <!--Value of the following element is what the value of '.itm_name_popup' in items/index.php. It will be added by JQuery on loading popup.-->
   <div class="namespan_box"><span class="namespan" ></span></div>


   <!--Value of the following element is what the value of '.itm_id_popup' in items/index.php. It will be added by JQuery on loading popup.-->
   <input type="hidden" id="p_key" value="">


   <table class="unt_tbl">

      <tbody>



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
      $('#pop_freight_charges_edit .save').click(function() {

         //Showing Loading image till ajax responds
         $("#pop_freight_charges_edit .responseMessage").html('<img src="images/ajax-loader.gif" /> Loading....');
         // Disabling whole page background till Ajax respond.
         $.blockUI(); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"


         var fc_add_rent = $('#pop_freight_charges_edit #fc_add_rent').prop('checked') ? 1 : 2;
         var fc_add_bata = $('#pop_freight_charges_edit #fc_add_bata').prop('checked') ? 1 : 2;
         var fc_add_loading = $('#pop_freight_charges_edit #fc_add_loading').prop('checked') ? 1 : 2;
         var postData = {
            fc_id: $('#pop_freight_charges_edit #p_key').val(),
            fc_rent: $('#pop_freight_charges_edit #fc_rent').val(),
            fc_bata: $('#pop_freight_charges_edit #fc_bata').val(),
            fc_loading: $('#pop_freight_charges_edit #fc_loading').val(),
            fc_add_rent: fc_add_rent,
            fc_add_bata: fc_add_bata,
            fc_add_loading: fc_add_loading
         };
         $.post(site_url + "freight_charges/edit", postData, function(result) {

            if (result == 1)
            {
               // Hiding save buttun to prevent the chance for re-entering of data.
               $('#pop_freight_charges_edit .save').hide();
               $("#pop_freight_charges_edit .responseMessage").html('<div class="pop_success"><img src="images/success_1.gif" width="15" height="15"  /> Data saved successfully!!!</div>');
               
               // Refreshing page automatically after 1 seconds.
               setTimeout(function() {
                  location.reload();
               }, 500);
            }
            else
               $("#pop_freight_charges_edit .responseMessage").html(result);
            // enabling the whole page after ajax response.
            $(document).ajaxStop($.unblockUI); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"


         });
      });



   });
   //pty_name pty_date pty_phone  pty_email  

   function init_pop_freight_charges_edit() {

      // Setting initial values of elements.
      $('#pop_freight_charges_edit #p_key').val('');
      $('#pop_freight_charges_edit #fc_rent').val('');
      $('#pop_freight_charges_edit #fc_bata').val('');
      $('#pop_freight_charges_edit #fc_loading').val('');
      $('#pop_freight_charges_edit #fc_add_rent').prop('checked', false);
      $('#pop_freight_charges_edit #fc_add_bata').prop('checked', false);
      $('#pop_freight_charges_edit #fc_add_loading').prop('checked', false);
// fc_rent,fc_add_rent,fc_bata,fc_add_bata,fc_loading,fc_add_loading

      $('#pop_freight_charges_edit #pop_drag').prop('checked', 'true');
      $('#pop_freight_charges_edit #pop_self_close').prop('checked', 'true');
      $('#pop_freight_charges_edit .save').show();
      $("#pop_freight_charges_edit .responseMessage").html('');
      //Making the popup box draggable.
      $("#pop_freight_charges_edit").draggable();
      dragUndrag($("#pop_freight_charges_edit"));
   }









</script>
