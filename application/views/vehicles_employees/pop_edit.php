<!--

Notes: 
    
    1.  All popup-box must have a class "popupBox".

-->

<div id="pop_vehicles_employees_edit" class="popupBox">
   <div class="dv-popupTitle">
      <span class="clossButton popupAction" title="Close Window"> X</span>
      <span class="titleColumn">EDIT LABOURS</span> 
   </div>

   <!--Value of the following element is what the value of '.itm_name_popup' in items/index.php. It will be added by JQuery on loading popup.-->
   <div class="namespan_box"><span class="namespan" ></span></div>



   <!--Value of the following element is what the value of '.itm_id_popup' in items/index.php. It will be added by JQuery on loading popup.-->

   <div class="drivers"></div> 


   <div class="loaders"></div>


   <div class="dragSaveBox">
      <div class="dragColumn">
         <input type="checkbox" id="pop_drag"> Drag 
         <input type="checkbox" id="pop_self_close"> Self Close
      </div>
      <div class="saveColumn">
      <input type="button" class="btn save"  title="Save Data"  value="Set As Default">
         <!--<img src="images/save.png" class="save" title="Save Data">--> 

      </div>
   </div>
   <div class="clear_boath"></div>
   <p class="responseMessage"></p>
</div>




<script type="text/javascript">



   $(document).ready(function() {

      //Saving data
      $('#pop_vehicles_employees_edit .save').click(function() {
         
         //Showing Loading image till ajax responds
         $("#pop_vehicles_employees_edit .responseMessage").html('<img src="images/ajax-loader.gif" /> Loading....');

         // Disabling whole page background till Ajax respond.
         $.blockUI(); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"


         var selected_driver = $('#pop_vehicles_employees_edit .drivers .DRIVERS:checked');
         var selected_loader = $('#pop_vehicles_employees_edit .loaders .LOADERS:checked');
         var default_driver = selected_driver.closest('td').find('.vemp_id').val();
         var default_loader = selected_loader.closest('td').find('.vemp_id').val();
         var postData = {
            default_driver: (typeof default_driver === "undefined") ? '' :default_driver,
            default_loader: (typeof default_loader === "undefined") ? '' :default_loader
         };

         $.post(site_url + "vehicles_employees/edit", postData, function(result) {

            if (result == 1)
            {
               // Hiding save buttun to prevent the chance for re-entering of data.
               $('#pop_vehicles_employees_edit .save').hide();

               $("#pop_vehicles_employees_edit .responseMessage").html('<div class="pop_success"><img src="images/success_1.gif" width="15" height="15"  /> Data saved successfully!!!</div>');

               // Refreshing page automatically after 1 seconds.
               setTimeout(function() {
                  location.reload();
               }, 500);

            }
            else
               $("#pop_vehicles_employees_edit .responseMessage").html(result);

            // enabling the whole page after ajax response.
            $(document).ajaxStop($.unblockUI); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"


         });


      });



   });

   //pty_name pty_date pty_phone  pty_email  

   function init_pop_vehicles_employees_edit() {

      // Setting initial values of elements.
      $('#pop_vehicles_employees_edit .drivers').html('');
      $('#pop_vehicles_employees_edit .loaders').html('');

      $('#pop_vehicles_employees_edit #pop_drag').prop('checked', 'true');
      $('#pop_vehicles_employees_edit #pop_self_close').prop('checked', 'true');
      $('#pop_vehicles_employees_edit .save').show();
      $("#pop_vehicles_employees_edit .responseMessage").html('');

      //Making the popup box draggable.
      $("#pop_vehicles_employees_edit").draggable();
      dragUndrag($("#pop_vehicles_employees_edit"));

   }
</script>
