<link href="css/<?php echo $this->themes[$this->theme]['text']; ?>/index.css" rel="stylesheet" type="text/css" />
<div class="dv-top-content" align="center">
   <div style="background-color:#FFF;color:#000;text-align: left;padding: 5px;">
   <h2> Solve workcentre changing issue in the whole software.</h2>
   Some examples below:<br>

   1. All labours in a vehilce must be a member of any of the workcentres where vehicle is available. So later, if we removed the vehicle/labour from a workcentre, the labours who are not associated with vehicle's workcentres should be removed from labours list.<br>
   2. Suppose we have set freight charge for a vehicle from a workcentre to a party destination. In this case both the vehicle and the party destination must be have registered under this workcentre. So when we removed the party-destination/vehicle from the workcentre, the freight charge must be removed.<br>
   
   3. ........... etc ......<br>
   
   <h4>Some of the factors caused to the workcentre changing issue are following</h4>
   <ul>
      <li>Delete/deactivate Employees from a workcentre</li>
      <li>Delete/deactivate Vehicles from a workcentre</li>
      <li>Delete/deactivate Machineries from a workcentre</li>
      <li>Delete/deactivate Paryty destinations from a workcentre</li>
      <li>Freight Charges</li>
      <li>Inter Freight Charges.
         <ul>
            <li><h5 style="color:green;">How to solve this issue? </h5></li>
            <li>I have solved an issue related to Employee. The issue is that;<br>
               Suppose a labour of a vehicle is deleted/deactivated from a workcentre, he must be deleted from labours list of that vehicle if he currently not a member of any workcentres where the vehicle registered. So to know how I solved this issue look the middle section of the function 'employees/edit'. here I had been called a function 'troubleshoot_1()'. This function executes the system trouble shooter. In the same way solve all other issues.</li>
         </ul>
      </li>
   </ul>
   
   4. Suppose we changed the emp_category of a labour of a vehicle, he must be deleted from labours list of that vehicle. Because he is an illegal labour.<br>
   
   5. When changing units of an item, rates related to the item in Tbl:individual_rates, Tbl:workcentre_rates, Tbl:item_units_n_rates become invalid. so it must be deleted.<br>
   
   
   </div>

   <?php
   if ($settings_errors)
      echo '<p style="text-align:left; color:#FFF;">' . implode('<br>', $settings_errors) . '</p>';
   ?>


   <?php
   if ($workcentres)
   {
      ?>

      <div class="workcentre_container" >

         <p class="home_title">Select A Workcentre</p>    
         <?php
         $checked = 'checked="" '; // To make first workcentre checked by default.
         $sel_wc = ' selected'; //Adding class="selected" to default selected workcenter.
         $default_wc_id = ''; // To store the first worckcentres id as default.

         print_r($workcentres);

         foreach ($workcentres as $key => $wc)
         {
            if (!$default_wc_id)
               $default_wc_id = $key;
            echo '<div class="workcentre' . $sel_wc . '">';
            echo '<div class="img-dv"> <img src="images/workcentre.png" /> </div>';
            echo '<div class="name-dv">';
            echo '<input type="radio" ' . $checked . '  name="workcentre[]" class="sel_workcentre" value="' . $key . '" />';
            echo '<span>' . $wc . '</span></div>';
            echo '</div>';
            $checked = '';
            $sel_wc = '';
         }
         ?>

         <!--    To store the value of selected workcentre's id. It will be done by Jquery.  -->
         <input type="text" id="wcntr_id" value="<?php echo $default_wc_id; ?>">


      </div>  <!--<div class="workcentre_container" >--> 




      <div class="clear_boath"></div>







      <div class="members_container">

         <p class="home_title">Select A Division</p>


         <?php
         if ($is_allowed)
         {
            ?>
            <div class="members">
               <div class="img-dv"> <img src="images/workcentre.png" /> </div>
               <div class="name-dv">
                  <span>Workcentres</span></div>
            </div>
         <?php } ?>



         <a href="<?php echo site_url('employees/index/3'); ?>" class="account_anchor" >
            <div class="members">
               <div class="img-dv"> <img src="images/staffs.png" /> </div>
               <div class="name-dv">
                  <span>Staffs</span>
               </div>
            </div>
         </a>

         <a href="<?php echo site_url('employees/index/4'); ?>" class="account_anchor" >
            <div class="members">
               <div class="img-dv"> <img src="images/drivers.png" /> </div>
               <div class="name-dv">
                  <span>Drivers</span></div>
            </div>
         </a>

         <a href="<?php echo site_url('employees/index/5'); ?>" class="account_anchor" >
            <div class="members">
               <div class="img-dv"> <img src="images/loaders.png" /> </div>
               <div class="name-dv">
                  <span>Loaders</span></div>
            </div>
         </a>


         <div class="members">
            <div class="img-dv"> <img src="images/suppliers.png" /> </div>
            <div class="name-dv">
               <span>Suppliers</span></div>
         </div>

         <div class="members">
            <div class="img-dv"> <img src="images/customers.png" /> </div>
            <div class="name-dv">
               <span>Customers</span></div>
         </div>

      </div>	<!--<div class="members_container">-->
      <div class="clear_boath"></div>

      <div class="members_container">


         <div class="members">
            <div class="img-dv"> <img src="images/cash1.png" /> </div>
            <div class="name-dv">
               <span>Cash</span></div>
         </div>
         <?php
         if ($this->is_admin || $this->is_partner) // Variables set in My_controller.
         {
            ?>


            <div class="members">
               <div class="img-dv"> <img src="images/partners.png" /> </div>
               <div class="name-dv">
                  <span>Partners</span></div>
            </div>

         <?php } ?>

         <div class="members">
            <div class="img-dv"> <img src="images/owners.png" /> </div>
            <div class="name-dv">
               <span>Owners</span></div>
         </div> 

         <div class="members">
            <div class="img-dv"> <img src="images/rent.png" /> </div>
            <div class="name-dv">
               <span>Rental</span></div>
         </div> 

         <div class="members">
            <div class="img-dv"> <img src="images/machineries.png" /> </div>
            <div class="name-dv">
               <span>Machineries</span></div>
         </div>

         <div class="members">
            <div class="img-dv"> <img src="images/vehicles.png" /> </div>
            <div class="name-dv">
               <span>Vehicles</span></div>
         </div>


      </div>	<!--<div class="members_container">-->
      <?php
   }
   else
   {
      echo '<div class="no_workcentre">There are no work centres under ' . $firm . '. Please ' . anchor('workcentres/add', 'click here') . ' to create one.</div>';
   }
   ?>

</div>
<!--<div class="dv-top-content" >--> 
<script type="text/javascript">

   $(document).ready(function() {

      // By default the first workcentre will be selected. Then when on page refreshing after we selected another workcentre, the first workcentre will be displayed again as selected. But the radio button shows as checked is second workcentre's. So now we reseting the radio buttun.
      $('.workcentre_container').find('.selected').find('.sel_workcentre').prop('checked', 'true');



      $(".workcentre,.members").hover(
              function() {
                 $(this).find('.img-dv img').animate({"width": "110px", "height": "110px", "opacity": "1"}, 100);
              },
              function() {

                 if ($(this).hasClass('selected'))
                    $(this).find('.img-dv img').animate({"width": "110px", "height": "110px", "opacity": "1"}, 1);
                 else
                    $(this).find('.img-dv img').animate({"width": "100px", "height": "100px", "opacity": "0.7"}, 100);
              });


      $(".workcentre").click(function() {

         $(".workcentre").removeClass("selected");
         $(".workcentre").not(this).find('.img-dv img').animate({"width": "100px", "height": "100px", "opacity": "0.7"}, 1);

         $(this).addClass("selected");
         $(this).find('.sel_workcentre').prop("checked", "checked");

      });


      // Storing selected workcentres id to an input element of id '#wcntr_id'
      $('.workcentre_container .workcentre').click(function() {
         $('.workcentre_container #wcntr_id').val($(this).find('.sel_workcentre').val());
      });

      $('.account_anchor .members_container').click(function() {
         var href = $(this).prop('href') + '/wc';
         $(this).prop('href', href);
      });

      /*  To resolve jquery conflict when using "$(document).on()" function when we using both 
       "js/jquery1.11.0.js"> and "js/jquery.min.js" libraries, use  $.noConflict();   
       Other wise it will show error as follows;
       TypeError: $(...).on is not a function
       
       */
//$.noConflict();

      /*$('.sel_workcentre').on('click', function() {
       $(this).attr("checked", !$(this).attr("checked"));
       });*/

   });
</script>