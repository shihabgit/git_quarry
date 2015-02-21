<!--

Notes: 
    
    1.  All popup-box must have a class "popupBox".

-->

<div id="pop_vehicles_employees_add" class="popupBox">
   <div class="dv-popupTitle">
      <span class="clossButton popupAction" title="Close Window"> X</span>
      <span class="titleColumn">ADD LABOURS TO THE VEHICLE</span> 
   </div>


   <div class="namespan_box"><span class="namespan" ></span></div>



   <!--<input type="hidden" id="p_key" value="">-->
   <input type="hidden" id="vemp_fk_vehicles" value="">

   <table class="unt_tbl">

      <tbody>
         <tr>
            <th>Labour Category</th>
            <td>
               <input type="radio" class="emp_category" name="emp_category" value="4" checked="" /> Driver &nbsp;
               <input type="radio" class="emp_category" name="emp_category" value="5" /> Loader 
            </td>                                   
         </tr>     

         <tr>
            <th>Labour</th>
            <td>
               <select id="vemp_fk_employees" > 
                  <option value="">No Labours</option>
               </select>
               <input type="checkbox" id="vemp_is_default"> <span class="is_default"></span>

               <div class="ajaxLoaderContainer"> 
                  <img src="images/ajax-loader2.gif"> 
                  <img src="images/ajax-loader2.gif"> 
               </div>  
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
      $('#pop_vehicles_employees_add .save').click(function() {
         //Showing Loading image till ajax responds
         $("#pop_vehicles_employees_add .responseMessage").html('<img src="images/ajax-loader.gif" /> Loading....');

         // Disabling whole page background till Ajax respond.
         $.blockUI(); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"
         
         var vemp_is_default = '';
         
         if($('#pop_vehicles_employees_add #vemp_is_default').prop('checked'))
            vemp_is_default = 1;
         else
            vemp_is_default = 2;
         

         var postData = {
            vemp_fk_vehicles: $('#pop_vehicles_employees_add #vemp_fk_vehicles').val(),
            vemp_fk_employees: $('#pop_vehicles_employees_add #vemp_fk_employees').val(),
            vemp_is_default: vemp_is_default
         };

         $.post(site_url + "vehicles_employees/add", postData, function(result) {

            if (result == 1)
            {
               // Hiding save buttun to prevent the chance for re-entering of data.
               $('#pop_vehicles_employees_add .save').hide();

               $("#pop_vehicles_employees_add .responseMessage").html('<div class="pop_success"><img src="images/success_1.gif" width="15" height="15"  /> Data saved successfully!!!</div>');

               // Refreshing page automatically after 1 seconds.
               setTimeout(function() {
                  location.reload();
               }, 500);

            }
            else
               $("#pop_vehicles_employees_add .responseMessage").html(result);

            // enabling the whole page after ajax response.
            $(document).ajaxStop($.unblockUI); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"


         });


      });



   });


   function init_pop_vehicles_employees_add(vhcl_id) {

      // Setting initial values of elements.
      $('#pop_vehicles_employees_add input[type=radio][name=emp_category][value=4]').prop('checked', true); // Setting DRIVERS as default labour
      $('#pop_vehicles_employees_add .is_default').html('Is default driver?');
      $('#pop_vehicles_employees_add #vemp_fk_vehicles').val('');
      $('#pop_vehicles_employees_add #vemp_fk_employees').html('<option value=""> -- No Labours-- </option>');
      $('#pop_vehicles_employees_add #vemp_is_default').prop('checked', false);
      $('#pop_vehicles_employees_add .ajaxLoaderContainer').hide();


      loadNewLabours(vhcl_id, 4); // Where 4 represents the emp_category value for DRIVERS. ie:- Here we are loading drivers only.

      $('#pop_vehicles_employees_add #pop_drag').prop('checked', 'true');
      $('#pop_vehicles_employees_add #pop_self_close').prop('checked', 'true');
      $('#pop_vehicles_employees_add .save').show();
      $("#pop_vehicles_employees_add .responseMessage").html('');

      //Making the popup box draggable.
      $("#pop_vehicles_employees_add").draggable();
      dragUndrag($("#pop_vehicles_employees_add"));

   }

   $('#pop_vehicles_employees_add .emp_category').change(function() {
      
      var category = $('#pop_vehicles_employees_add .emp_category:checked').val();
      var vhcl_id = $('#pop_vehicles_employees_add #vemp_fk_vehicles').val();
      
      if (category == '4')
         $('#pop_vehicles_employees_add .is_default').html('Is default driver?');
      else if (category == '5')
         $('#pop_vehicles_employees_add .is_default').html('Is default loader?');
      else
         alert('Logical Error...');
      
      loadNewLabours(vhcl_id, category);
   });


   function loadNewLabours(vhcl_id, emp_category)
   {

      // Showing ajax loading image.
      $('#pop_vehicles_employees_add .ajaxLoaderContainer').show();

      // Hiding dropdown till ajax load.
      $('#pop_vehicles_employees_add #vemp_fk_employees').hide();

      $.getJSON(site_url + 'vehicles_employees/getNewLabours',
              {vhcl_id: vhcl_id,
                 emp_category: emp_category
              }, function(data) {

         var options = '';
         for (var x = 0; x < data.length; x++) {
            options += '<option value="' + data[x]['value'] + '">' + data[x]['text'] + '</option>';
         }

         $('#pop_vehicles_employees_add #vemp_fk_employees').html(options);

         // Hiding ajax loading image.
         $('#pop_vehicles_employees_add .ajaxLoaderContainer').hide();

         // Showing dropdown after ajax load.
         $('#pop_vehicles_employees_add #vemp_fk_employees').show();

      });
   }

</script>
