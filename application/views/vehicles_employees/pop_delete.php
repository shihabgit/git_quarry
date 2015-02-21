<!--

Notes: 
    
    1.  All popup-box must have a class "popupBox".

-->

<div id="pop_vehicles_employees_delete" class="popupBox">
   <div class="dv-popupTitle">
      <span class="clossButton popupAction" title="Close Window"> X</span>
      <span class="titleColumn">DELETE LABOURS</span> 
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
      <input type="button" class="btn delete"  title="Delete Labour"  value="DELETE">
         <!--<img src="images/save.png" class="save" title="Save Data">--> 

      </div>
   </div>
   <div class="clear_boath"></div>
   <p class="responseMessage"></p>
</div>




<script type="text/javascript">



   $(document).ready(function() {

      //Saving data
      $('#pop_vehicles_employees_delete .delete').click(function() {
         
         // Setting variable to hold selected labours.
         var selected_labours = [];

         // Assigning each selected labours vemp_id to 'selected_labours'.
         $('#pop_vehicles_employees_delete .vemp_id').each(function() {
            if ($(this).prop('checked'))
               selected_labours.push($(this).val());
         });

         //If no labours selected.
         if (!selected_labours.length)
         {
            alert("No labours selected yet !!!");
            return;
         }
         
         //Showing Loading image till ajax responds
         $("#pop_vehicles_employees_delete .responseMessage").html('<img src="images/ajax-loader.gif" /> Loading....');
         
         if (!confirm('Do you want to delete the selected labours from the vehicle?'))
            return;

         // Disabling whole page background till Ajax respond.
         $.blockUI(); // Function is defined at "plugins/blockui-master/jquery.blockUI.js".

         $.post(site_url + "vehicles_employees/delete", {vemp_ids: selected_labours}, function(result) {

            if (result == 1)
            {
               // Hiding save buttun to prevent the chance for re-entering of data.
               $('#pop_vehicles_employees_delete .save').hide();

               $("#pop_vehicles_employees_delete .responseMessage").html('<div class="pop_success"><img src="images/success_1.gif" width="15" height="15"  /> Labours deleted from the vehicle successfully!!!</div>');

               // Refreshing page automatically after 1 seconds.
               setTimeout(function() {
                  location.reload(true);
               }, 500);

            }
            else
               $("#pop_vehicles_employees_delete .responseMessage").html(result);

            // enabling the whole page after ajax response.
            $(document).ajaxStop($.unblockUI); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"


         });


      });



   });

   //pty_name pty_date pty_phone  pty_email  

   function init_pop_vehicles_employees_delete() {

      // Setting initial values of elements.
      $('#pop_vehicles_employees_delete .drivers').html('');
      $('#pop_vehicles_employees_delete .loaders').html('');

      $('#pop_vehicles_employees_delete #pop_drag').prop('checked', 'true');
      $('#pop_vehicles_employees_delete #pop_self_close').prop('checked', 'true');
      $('#pop_vehicles_employees_delete .save').show();
      $("#pop_vehicles_employees_delete .responseMessage").html('');

      //Making the popup box draggable.
      $("#pop_vehicles_employees_delete").draggable();
      dragUndrag($("#pop_vehicles_employees_delete"));

   }
</script>
