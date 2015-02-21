<!--

Notes: 
    
    1.  All popup-box must have a class "popupBox".

-->

<div id="pop_individual_rates_edit" class="popupBox">
   <div class="dv-popupTitle">
      <span class="clossButton popupAction" title="Close Window"> X</span>
      <span class="titleColumn"></span>
   </div>

   <!--Value of the following element is what the value of '.itm_name_popup' in items/index.php. It will be added by JQuery on loading popup.-->
   <div class="namespan_box"><span class="namespan" ></span></div>



   <div class="tbl_container"></div>


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
      $('#pop_individual_rates_edit .save').click(function() {

         //Showing Loading image till ajax responds
         $("#pop_individual_rates_edit .responseMessage").html('<img src="images/ajax-loader.gif" /> Loading....');
         // Disabling whole page background till Ajax respond.
         $.blockUI(); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"


         var ids = [];     // Primary keys
         var p_rate = [];  // Purchase rates.
         var s_rate = [];  // Selling rates.




         //build an array of ids.
         $('#pop_individual_rates_edit').find('.unt_tbl .indv_id').each(function(i, obj) {
            ids[i] = $(obj).val();
         });

         //build an array of purchase rates.
         $('#pop_individual_rates_edit').find('.unt_tbl .indv_p_rate').each(function(i, obj) {
            p_rate[i] = $(obj).val();
         });

         //build an array of selling rates.
         $('#pop_individual_rates_edit').find('.unt_tbl .indv_s_rate').each(function(i, obj) {
            s_rate[i] = $(obj).val();
         });

         var postData = {
            ids: ids,
            p_rate: p_rate,
            s_rate: s_rate
         };
         $.post(site_url + "individual_rates/edit", postData, function(result) {

            if (result == 1)
            {
               // Hiding save buttun to prevent the chance for re-entering of data.
               $('#pop_individual_rates_edit .save').hide();
               $("#pop_individual_rates_edit .responseMessage").html('<div class="pop_success"><img src="images/success_1.gif" width="15" height="15"  /> Data saved successfully!!!</div>');

               // Refreshing page automatically after 1 seconds.
               setTimeout(function() {
                  location.reload();
               }, 500);
            }
            else
               $("#pop_individual_rates_edit .responseMessage").html(result);
            // enabling the whole page after ajax response.
            $(document).ajaxStop($.unblockUI); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"


         });
      });



   });
   //pty_name pty_date pty_phone  pty_email  

   function init_pop_individual_rates_edit() {

      // Setting initial values of elements.
      $("#pop_individual_rates_edit .tbl_container").html('');


      $('#pop_individual_rates_edit #pop_drag').prop('checked', 'true');
      $('#pop_individual_rates_edit #pop_self_close').prop('checked', 'true');
      $('#pop_individual_rates_edit .save').show();
      $("#pop_individual_rates_edit .responseMessage").html('');
      //Making the popup box draggable.
      $("#pop_individual_rates_edit").draggable();
      dragUndrag($("#pop_individual_rates_edit"));
   }









</script>
