<!--

Notes: 
    
    1.  All popup-box must have a class "popupBox".

-->

<div id="pop_vehicle_edit" class="popupBox">
   <div class="dv-popupTitle">
      <span class="clossButton popupAction" title="Close Window"> X</span>
      <span class="titleColumn">EDIT VEHICLE</span> 
   </div>

   <!--Value of the following element is what the value of '.itm_name_popup' in items/index.php. It will be added by JQuery on loading popup.-->
   <div class="namespan_box"><span class="namespan" ></span></div>



   <!--Value of the following element is what the value of '.itm_id_popup' in items/index.php. It will be added by JQuery on loading popup.-->
   <input type="hidden" id="p_key" value="">


   <table class="unt_tbl">

      <tbody>

         <tr>
            <th>Name</th>
            <td>
               <input type="text" id="vhcl_name" value="" >
            </td>                                   
         
            <th>No:</th>
            <td>
               <input type="text" id="vhcl_no" value="" >
            </td>                                   
         </tr> 

         <tr>
            <th>Length</th>
            <td>
               <input type="text" id="vhcl_length" value="" class="numberOnly" >
            </td>                                   
         
            <th>Breadth</th>
            <td>
               <input type="text" id="vhcl_breadth" value="" class="numberOnly" >
            </td>                                   
         </tr> 

         <tr>
            <th>Height</th>
            <td>
               <input type="text" id="vhcl_height" value="" class="numberOnly" >
            </td>                                   
         
            <th>X-Height</th>
            <td>
               <input type="text" id="vhcl_xheight" value="" class="numberOnly" >
            </td>                                   
         </tr> 

         <tr>
            <th>Remarks</th>
            <td>
               <input type="text" id="vhcl_remarks" value="">
            </td>                                   
         
            <th>Ownership</th>
            <td>
               <input type="radio" id="vhcl_ownership" name="vhcl_ownership" value="1" />
               Ours &nbsp;

               <input type="radio" id="vhcl_ownership" name="vhcl_ownership" value="2" />
               Others
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
      $('#pop_vehicle_edit .save').click(function() {
         //Showing Loading image till ajax responds
         $("#pop_vehicle_edit .responseMessage").html('<img src="images/ajax-loader.gif" /> Loading....');

         // Disabling whole page background till Ajax respond.
         $.blockUI(); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"
// vhcl_name,vhcl_no,vhcl_length,vhcl_breadth,vhcl_height,vhcl_xheight,vhcl_remarks,vhcl_ownership
         var postData = {
            vhcl_id: $('#pop_vehicle_edit #p_key').val(),
            vhcl_name: $('#pop_vehicle_edit #vhcl_name').val(),
            vhcl_no: $('#pop_vehicle_edit #vhcl_no').val(),
            vhcl_length: $('#pop_vehicle_edit #vhcl_length').val(),
            vhcl_breadth: $('#pop_vehicle_edit #vhcl_breadth').val(),
            vhcl_height: $('#pop_vehicle_edit #vhcl_height').val(),
            vhcl_xheight: $('#pop_vehicle_edit #vhcl_xheight').val(),
            vhcl_remarks: $('#pop_vehicle_edit #vhcl_remarks').val(),
            vhcl_ownership: $('#pop_vehicle_edit #vhcl_ownership:checked').val()
         };

         $.post(site_url + "vehicles/edit", postData, function(result) {

            if (result == 1)
            {
               // Hiding save buttun to prevent the chance for re-entering of data.
               $('#pop_vehicle_edit .save').hide();

               $("#pop_vehicle_edit .responseMessage").html('<div class="pop_success"><img src="images/success_1.gif" width="15" height="15"  /> Data saved successfully!!!</div>');

               // Refreshing page automatically after 1 seconds.
               setTimeout(function() {
                  location.reload(true);
               }, 500);

            }
            else
               $("#pop_vehicle_edit .responseMessage").html(result);

            // enabling the whole page after ajax response.
            $(document).ajaxStop($.unblockUI); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"


         });


      });



   });

   //pty_name pty_date pty_phone  pty_email  

   function init_pop_vehicle_edit() {

      // Setting initial values of elements.
      $('#pop_vehicle_edit #p_key').val();
      $('#pop_vehicle_edit #vhcl_name').val();
      $('#pop_vehicle_edit #vhcl_no').val();
      $('#pop_vehicle_edit #vhcl_length').val();
      $('#pop_vehicle_edit #vhcl_breadth').val();
      $('#pop_vehicle_edit #vhcl_height').val();
      $('#pop_vehicle_edit #vhcl_xheight').val();
      $('#pop_vehicle_edit #vhcl_remarks').val();
      $('#pop_vehicle_edit #vhcl_ownership:checked').val();

      $('#pop_vehicle_edit #pop_drag').prop('checked', 'true');
      $('#pop_vehicle_edit #pop_self_close').prop('checked', 'true');
      $('#pop_vehicle_edit .save').show();
      $("#pop_vehicle_edit .responseMessage").html('');

      //Making the popup box draggable.
      $("#pop_vehicle_edit").draggable();
      dragUndrag($("#pop_vehicle_edit"));

   }

</script>
