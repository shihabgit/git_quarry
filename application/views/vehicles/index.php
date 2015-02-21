<?php $this->load->view('vehicles/pop_edit'); ?>
<?php $this->load->view('vehicles_employees/pop_add'); ?>   
<?php $this->load->view('vehicles_employees/pop_edit'); ?>
<?php $this->load->view('vehicles_employees/pop_delete'); ?>
<?php $this->load->view('inter_freight_charges/pop_add'); ?>
<?php $this->load->view('inter_freight_charges/pop_edit'); ?>
<?php $this->load->view('vehicle_workcentres/pop_add'); ?>
<?php $this->load->view('vehicle_workcentres/pop_edit'); ?>
<?php $this->load->view('freight_charges/pop_add'); ?>
<?php $this->load->view('freight_charges/pop_edit'); ?>



<link href="css/<?php echo $this->themes[$this->theme]['text']; ?>/tiles.css" rel="stylesheet" type="text/css" />

<!-- Fancy scrollbar START 	-->
<link type="text/css" href="plugins/fancy_scrollbars/by_shihab/css/jquery.jscrollpane.css" rel="stylesheet" media="all" />

<style type="text/css" id="page-css">
    /* Styles specific to this particular page */
    .scroll-pane
    {
        width: 98%;
        height: 600px;
        overflow: auto;
    }


    #tile_container #tbl_tile > tbody > tr > td {
        width:50% !important;
    }

</style>
<!-- the mousewheel plugin -->
<script type="text/javascript" src="plugins/fancy_scrollbars/by_shihab/js/jquery.mousewheel.js"></script>
<!-- the jScrollPane script -->
<script type="text/javascript" src="plugins/fancy_scrollbars/by_shihab/js/jquery.jscrollpane.min.js"></script>

<script type="text/javascript" id="sourcecode">
   $(function ()
   {
       $('.scroll-pane').jScrollPane(
               {
                   showArrows: true,
                   horizontalGutter: 30,
                   verticalGutter: 30
               }
       );
   });
</script>
<!-- Fancy scrollbar END 	-->



<div class="dv-top-content" align="center">
    <?php echo form_open("vehicles", array('id' => 'searchForm')); ?>

    <table width="70%" cellpadding="5" cellspacing="0" class="tbl_input">
        <tr>
            <td>
                <div class="title-box">
                    <div id="img-container">
                        <img src="images/search-user.png" width="35" height="35"/>
                    </div>
                    <div id="title-container">
                        <div class="title-alone"><?php echo $heading; ?></div>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td>

                <p style="background-color:#FFF;color:#000;text-align: left;padding: 5px;">
                    1. When remove/deactivate a vehicle from a workcentre, all the employees (drivers & loaders) in that workcentre must be released from the vehicle. and also the freight charges of the vehicle related to that workcentre must also be deleted.<br>
                    2. When activate vehicle, the value of 'vwc_sold_price' field must be null.<br> 
                    3. Think is there a need to allow "other's" vehicle to add labours in it and to add freight charges. I think it is not a good idea. Other's vehicles could not have labours from our workcentres and not freight charges. If it is needed, ie:- Suppose we take a vehicle for rent from an out side person, add this vehicle as our's vehicle and leave its cost (Tbl: vehicle_workcentres.vwc_cost) as zero. By doing this, it will not affect our Accounts. But when making bills, if user need to add rent for an "other's" vehicle, he can do it manually. It must be shown in rent account of that vehicle.<br>
                    &ensp;<b>So after changed an "our's" vehicle as an "other's" vehicle, all labours and freight charges associated to the vehicle must deleted.</b>

                </p>
                <div class="inputblok" style="width: 50%;">
                    <div class="sec_container">
                        <p class="input-categories">Basic Details</p>
                        <table class="sec_table">

                            <tr>
                                <th>No : </th>
                                <td><input type="text" name="vhcl_no" value="<?php echo ifSet('vhcl_no') ?>">  </td>
                            </tr>


                            <tr>
                                <th>Name : </th>
                                <td><input type="text" name="vhcl_name" value="<?php echo ifSet('vhcl_name') ?>">  </td>
                            </tr>

                            <tr>
                                <th>Added Date :</th>
                                <td>
                                    <div class="dateContainer" style="padding: 0px;margin:0px;float: left;">
                                        <div style="margin-right: 2px;float: left;" >
                                            <input class="dateField inputDate" placeholder="From" readonly="" type="text" name="f_vhcl_date" value="<?php echo ifSet('f_vhcl_date') ?>" style="width:105px" id="f_vhcl_date"  /> 
                                        </div>
                                        <div style="margin-bottom: 1px;float: left;">
                                            <img src="images/calendar.gif"  class="calendarButton"> 
                                        </div>
                                    </div>  

                                    <div class="dateContainer" style="padding: 0px;margin:0px;margin-left:2px; float: left;">
                                        <div style="margin-right: 2px;float: left;" >
                                            <input class="dateField inputDate" placeholder="To" readonly="" type="text" name="t_vhcl_date" value="<?php echo ifSet('t_vhcl_date') ?>" style="width:105px" id="t_vhcl_date"  /> 
                                        </div>
                                        <div style="margin-bottom: 1px;float: left;">
                                            <img src="images/calendar.gif"  class="calendarButton"> 
                                        </div>
                                    </div>    
                                </td>
                            </tr>



                            <tr>
                                <th>Ownership : </th>
                                <td>

                                    <input type="radio" name="vhcl_ownership" value="1" <?php echo ifSetRadio('vhcl_ownership', 1) ?> />
                                    <span class="multy_options">Ours</span>

                                    <input type="radio" name="vhcl_ownership" value="2" <?php echo ifSetRadio('vhcl_ownership', 2) ?> />
                                    <span class="multy_options">Others</span>

                                    <input type="radio" name="vhcl_ownership" value="0" <?php echo ifSetRadio('vhcl_ownership', 0, true) ?> />
                                    <span class="multy_options">All</span>
                                </td>

                            </tr>
                            <tr>
                                <th>Status : </th>
                                <td>

                                    <input type="radio" name="vhcl_status" value="1" <?php echo ifSetRadio('vhcl_status', 1, true) ?> />
                                    <span class="multy_options">Active</span>

                                    <input type="radio" name="vhcl_status" value="2" <?php echo ifSetRadio('vhcl_status', 2) ?> />
                                    <span class="multy_options">Inactive</span>
                                </td>
                            </tr>

                        </table>
                    </div>      <!--     End of Account Details-->
                </div> <!--     End of  <div class="inputblok">-->



                <div class="inputblok" style="width: 50%;">
                    <div class="sec_container">
                        <p class="input-categories">Availability</p>
                        <table class="sec_table">


                            <tr>
                                <th>Workcentre : </th>
                                <td>
                                    <select name="vwc_fk_workcentres[]" multiple="multiple"  style="height:100px;width:290px;">
                                        <?php echo get_options2($workcentres, ifSet('vwc_fk_workcentres')); ?>
                                    </select>
                                    <p class="help">Hold <b>Ctrl</b> Key to select multiple workcentres.</p>
                                    <input type="radio" name="vwc_status" value="1" <?php echo ifSetRadio('vwc_status', 1, true) ?> />
                                    <span class="multy_options">Active</span>

                                    <input type="radio" name="vwc_status" value="2" <?php echo ifSetRadio('vwc_status', 2) ?> />
                                    <span class="multy_options">Inactive</span>
                                </td>
                            </tr>


                            <tr>
                                <th>Labours : </th>
                                <td>
                                    <select name="driver"  style="width:140px;">
                                        <?php echo get_options2($drivers, ifSet2('driver'), TRUE, '-- Driver --'); ?>
                                    </select>
                                    &nbsp;
                                    <select name="loader"  style="width:140px;">
                                        <?php echo get_options2($loaders, ifSet2('loader'), TRUE, '-- Loader --'); ?>
                                    </select>
                                </td>
                            </tr>


                            <tr height="15px;">
                                <th></th>
                                <td></td>
                            </tr>
                        </table>
                    </div>      <!--     End of Account Details-->
                </div> <!--     End of  <div class="inputblok">-->


            </td>
        </tr>



    </table>
    <div id="submit_container">
        <hr /><br />
        <input type="hidden" name="PER_PAGE"  value="<?php echo ifSet('PER_PAGE', $this->per_page) ?>" > <!-- its value will be filled onChange <select> near pagination links -->
        <input type="submit" name="button2" class="collapse_btn" value="Submit" />
        <input type="button" class="collapse_btn reseter" name="button3" value="Reset" />
    </div>
    <?php echo form_close(); ?>
</div>












<div class="dv-bottom-content">
    <div class="search-header">
        <div id="right_pan">


            <div class="collapse_container right_action">
                <div class="settings_btn">
                    <img  src="images/listUp.png" title="Print Data" width="24" height="24">
                </div>

                <div class="settings p-collaps ">List Print</div>
                <div class="dv-collaps left_action_content" style="width:250px;">

                    <div id="list_print_form" style="margin:20px;text-align: left;">       
                        <input type="checkbox" name="L_NO" checked="" > L No <br> 
                        <input type="checkbox" name="ADDRESS"  checked=""> Address <br>  


                        <div style="margin:20px 5px;text-align: center;"><input type="submit" name="button" value="PRINT" class="collapse_btn" /></div>                
                    </div>   

                </div> 

                <div class="clear_boath"></div>
            </div>

            <div class="right_action" id="GROUP_ACCOUNT">
                <div class="settings_btn"><img  src="images/bird.png" title="ACCOUNTS" width="24" height="24"></div>
                <div class="settings">Accounts</div>         

                <?php echo form_open('subscriber_accounts/account', array('id' => 'frm_GROUP_ACCOUNT')) ?>
                <input type="hidden" name="SBR_ID" value="">
                <?php echo form_close(); ?>

                <div class="clear_boath"></div>
            </div>
        </div>  <!--<div id="right_pan">-->


        <div id="left_pan">

            <div class="collapse_container right_action">
                <div class="settings_btn">
                    <img  src="images/bird.png" title="SETTINGS" width="24" height="24">
                </div>

                <div class="settings p-collaps ">Publish</div>
                <div class="dv-collaps left_action_content" style="width:250px;">

                    <div id="publish_form" style="margin:20px;">


                        <div style="margin:20px 5px;text-align: left;"> 
                            <div class="dateContainer" style="padding: 0px;margin:0px;"> Date :
                                <input class="dateField inputDate" readonly="" id="GRP_PBL_DATE" value="<?php echo formatDate('', true, 1); ?>" style="width:80px;" /> 
                                <img src="images/calendar.png"  class="calendarButton"> 
                            </div>
                        </div>


                        <div style="margin:20px 5px;text-align: left;"> Lakkam : 
                            <select name="MONTH">
                                <?php echo get_options2(getMonthOptions(), date('m'), false); ?>
                            </select>
                            <select name="YEAR">
                                <?php echo get_options2(getYearOptions(2010, 2020), date('Y'), false); ?>
                            </select>
                        </div>
                        <div style="margin:20px 5px;text-align: left;"><input type="checkbox" name="PRINT"> Print</div>


                        <div style="margin:20px 5px;text-align: center;"><input type="submit" name="button" value="PUBLISH" class="collapse_btn" /></div>                
                    </div>   

                </div> 

                <div class="clear_boath"></div>
            </div>



            <div class="collapse_container right_action" id="envelope">
                <div class="settings_btn"><img  src="images/bird.png" title="Print Data" width="24" height="24"></div>
                <div class="settings p-collaps">Envelope</div>
                <div class="dv-collaps left_action_content" style="width:180px;">

                    <div style="margin:20px;">

                        <div style="margin:20px 5px;text-align: left;">
                            <p style="margin: 0px;padding: 0px; color: #FFBFAA; text-align: center; text-decoration: underline; line-height: 30px;"> Send Option</p>
                            <input type="radio" name="ENV_MODE" value="envelope_individual" checked="">  Individually <br>
                            <input type="radio" name="ENV_MODE" value="envelope_bulk">  Bulk
                        </div>

                        <div style="margin:20px 5px;text-align: center;">
                            <input type="submit" name="button" value="PRINT" class="collapse_btn" />
                        </div>                
                    </div>   

                </div> 
                <div class="clear_boath"></div>
            </div>


            <div class="collapse_container right_action" id="group_delete">
                <div class="settings_btn"><img  src="images/bird2.png" title="Print Data" width="21" height="21"></div>
                <div class="settings">Delete</div>

                <div class="clear_boath"></div>
            </div>



        </div>		<!--<div id="left_pan">-->
        <div id="middle_pan">
            <h3>SEARCH RESULTS</h3>
        </div>

    </div>
    <div class="search-header">
        <div id="right_pan">



            <a href="<?php echo site_url('vehicles/add'); ?>" >
                <div class="right_action"  title="<?php echo lang('index_create_user_link') ?>">
                    <div class="settings_btn">
                        <img src="images/Add button.png" height="24" width="24" title="Add Vehicle" />
                    </div>
                    <div class="clear_boath"></div>
                </div>
            </a>

        </div>

        <!--        <div class="add_action">
        
                </div>-->

        <p class="pagin_data">
            <input type="checkbox" id="checkUncheckAll"  />
            <?php
            if ($table)
            {
               $num_row_text = $num_rows > 1 ? 'Records' : 'Record';
               $num_page_text = $page_count > 1 ? 'Pages' : 'Page';
               ?>
               Total 
               <b><font color="#00FF00"><?php echo $num_rows; ?></font></b> <?= $num_row_text ?> In  
               <b><font color="#00FF00"><?php echo $page_count; ?></font></b> <?= $num_page_text ?>
            <?php } ?>
        </p>
        <div class="pagin_links"><?php echo $this->pagination->create_links(); ?></div>


        Show 
        <select onchange="$('input[name=PER_PAGE]').val($(this).val());
               $('#searchForm').submit();" name="PER_PAGE">
                    <?php echo get_options2($per_pages, ifSet('PER_PAGE', $this->per_page), FALSE); ?>
        </select> Records/page
    </div>	<!--<div class="search-header">-->






















    <div id="tile_container">
        <?php
        if (!$table)
           echo '<table align="center"  width="100%" cellpadding="0" cellspacing="0" style="background-color:#FFF;"><tr><th style="width:100%;color:red">NO DATA TO DISPLAY</th></tr></table>';
        else
        {
           $slNo = 1;
           $col = 0;
           $max_cols = 2;
           $tr_tag = '';
           echo '<table id="tbl_tile"  cellpadding="0" cellspacing="0">';
           echo '<tbody>';

           foreach ($table as $row)
           {
              ++$col;
              if ($col == 1)
              {
                 $tr_tag = '<tr>';
                 echo $tr_tag;
              }
              echo '<td>';

              // Putting vhcl_id in global scope, to get it in included files like 'vehicles/index_vehicles_employees.php'.
              $GLOBALS['vhcl_id'] = $row['vhcl_id'];
              $GLOBALS['vhcl_ownership'] = $row['vhcl_ownership'];

              if ($row['vhcl_status'] == 1)
              {
                 $class = 'main_title_active';
                 $status = 'Active';
                 $img = 'images/delete4.png';
                 $tooltip = "Deactivate Vehicle";
                 $update = '<div class="tool_image vhcl_edit"> <img title="Edit Vehicle" src="images/edit11.png"> </div>';
              }
              else
              {
                 $class = 'inactive';
                 $status = 'Inactive';
                 $img = 'images/activate2.png';
                 $tooltip = "Activate Vehicle";
                 $update = '';
              }

              $ownership_class = ($row['vhcl_ownership'] == 1) ? 'our_vehicle' : 'other_vehicle';
              $ownership_text = ($row['vhcl_ownership'] == 1) ? 'Our vehicle' : 'Other\'s vehicle';
              ?>


              <div class="tile scroll-pane blackScroll">

                  <input type="hidden" id="vhcl_id" value="<?php echo $row['vhcl_id']; ?>" >
                  <input type="hidden" id="vhcl_no" value="<?php echo $row['vhcl_no']; ?>" >
                  <input type="hidden" id="vhcl_ownership" value="<?php echo $row['vhcl_ownership']; ?>" >
                  <input type="hidden" id="vhcl_status" value="<?php echo $row['vhcl_status']; ?>" >


                  <div class="bring">
                      <div class="bring_slNo"><?php echo $slNo; ?></div>
                      <div class="tile_name  msctr" style="width:80%;">
                          <div class="main_name <?php echo $class; ?>">
                              <div class="main_name2 <?php echo $class; ?>" title="<?php echo $status; ?> vehicle">
                                  <?php
                                  $vehicle = $row['vhcl_name'] ? $row['vhcl_name'] . ': ' . $row['vhcl_no'] : $row['vhcl_no'];
                                  echo $vehicle . '. (' . $row['vhcl_id'] . ')';
                                  ?>

                              </div>     
                              <div>
                                  <span class="<?php echo $ownership_class; ?>" title="<?php echo $ownership_text; ?>"> 
                                      <?php echo $ownership_text; ?>
                                  </span> 
                                  <span class="dob" title="Added Date"><?php echo formatDate($row['vhcl_date'], FALSE); ?></span> 
                              </div>
                              <div class="clear_boath"></div>
                          </div>


                      </div>

                      <div class="toolics"> 
                          <?php echo $update; ?>                            
                          <div class="tool_image vhcl_toggle"><img title="<?php echo $tooltip; ?>" src="<?php echo $img; ?>"></div>
                      </div>


                      <div class="clear_boath"></div>
                  </div>

                  <div class="detailbox"> 
                      <span class="measurement">Length: </span>       
                      <span class="quantity"><?php echo $row['vhcl_length']; ?>"</span> 
                      <span class="measurement">Breadth: </span>       
                      <span class="quantity"><?php echo $row['vhcl_breadth']; ?>"</span> 
                      <span class="measurement">Height:</span>       
                      <span class="quantity"><?php echo $row['vhcl_height']; ?>"</span> 
                      <span class="measurement">X-Height:</span>       
                      <span class="quantity"><?php echo $row['vhcl_xheight']; ?>"</span>  
                      <?php
                      //  1 CFT = 12 Inches.
                      //  CFT Formula : (Length" x Width" x Height")/ (12*12*12) = Cubic feet (CFT) 
                      //  ie: CFT Formula : (Length" x Width" x Height")/1728 = Cubic feet (CFT) 
                      // Calculating vehicle capacity when normal body height.                    
                      $operants = array();
                      $operants[] = $row['vhcl_length'];
                      $operants[] = $row['vhcl_breadth'];
                      $operants[] = $row['vhcl_height'];

                      // Volume in inches.
                      $normal_volume = mybcmath($operants, '*');

                      // Volume in CFT.
                      $normal_volume = bcdiv("$normal_volume", "1728", 2);

                      // Calculating vehicle capacity when extra body height.               
                      $operants = array();
                      $operants[] = $row['vhcl_length'];
                      $operants[] = $row['vhcl_breadth'];
                      $operants[] = $row['vhcl_xheight'];

                      // Volume in inches.
                      $extra_volume = mybcmath($operants, '*');

                      // Volume in Feet.
                      $extra_volume = bcdiv("$extra_volume", "1728", 2);
                      ?>
                      <div>
                          <span class="measurement">Capacity: </span>       
                          <span class="quantity"><?php echo $normal_volume; ?> CFT</span>  
                          <span class="measurement">X-Capacity:</span>       
                          <span class="quantity"><?php echo $extra_volume; ?> CFT</span>  
                      </div>
                      <?php
                      if ($row['vhcl_remarks'])
                      {
                         ?>
                         <div style="word-wrap: break-word; font-size:12px; font-family: sans-serif; color:#FFF;text-align: left;">
                             <span class="remarks">Remarks: </span>  
                             <?php echo $row['vhcl_remarks']; ?>
                         </div>

                      <?php } ?>
                  </div>

                  <hr>



                  <?php
                  if ($row['vhcl_ownership'] == 1) // The following is required iff the vehicle ownership is ours
                  {
                     ?>
                     <div class="bring">
                         <?php $this->load->view('vehicles/index_vehicles_employees'); ?>                        
                     </div>

                     <div class="bring" style="margin-top: 5px;">
                         <?php $this->load->view('vehicles/index_inter_freight_charges'); ?>   
                     </div>

                     <?php
                  }
                  else
                  {
                     echo '<p style="font-size:18px;color:#FFF;text-align:center;" >No Labours or Freight Charges Provided For "Other\'s" Vehicle.</p>';
                  }
                  ?>

                  <div class="bring" style="margin-top: 5px;">
                      <?php $this->load->view('vehicles/index_vehicle_workcentres'); ?>   
                  </div>
              </div>



              <?php
              echo '</td>';
              if ($col == $max_cols)
              {
                 $tr_tag = '</tr>';
                 echo $tr_tag;
                 $col = 0;
              }
              $slNo++;
           }

           if ($col && ($col < $max_cols))
              for ($col; $col < $max_cols; $col++)
                 echo '<td></td>';
           if ($tr_tag == '<tr>') // If <tr> is not clossed
              echo '</tr>';
           echo '</tbody>';
           echo '</table>';
        }
        ?>


    </div>
</div><!--<div class="dv-bottom-content"> -->

<script type="text/javascript">
   $(document).ready(function () {

       $('.tile .vhcl_edit').click(function () {

           // Disabling whole page background till Ajax respond.
           $.blockUI(); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"

           var p_key = $(this).closest('.tile').find('#vhcl_id').val();
           var name = $(this).closest('.tile').find('#vhcl_no').val();

           //Initializing popup box.
           init_pop_vehicle_edit();

           $('#pop_vehicle_edit .namespan_box .namespan').text(name);
           $('#pop_vehicle_edit #p_key').val(p_key);

           $.getJSON(site_url + 'vehicles/beforeEdit', {vhcl_id: p_key}, function (data) {
               $('#pop_vehicle_edit #vhcl_name').val(data['vhcl_name']);
               $('#pop_vehicle_edit #vhcl_no').val(data['vhcl_no']);
               $('#pop_vehicle_edit #vhcl_length').val(data['vhcl_length']);
               $('#pop_vehicle_edit #vhcl_breadth').val(data['vhcl_breadth']);
               $('#pop_vehicle_edit #vhcl_height').val(data['vhcl_height']);
               $('#pop_vehicle_edit #vhcl_xheight').val(data['vhcl_xheight']);
               $('#pop_vehicle_edit #vhcl_remarks').val(data['vhcl_remarks']);
               $('#pop_vehicle_edit input[type=radio][name=vhcl_ownership][value=' + data['vhcl_ownership'] + ']').prop('checked', true);

               //Loading popupBox.
               loadPopup('pop_vehicle_edit');

               // enabling the whole page after ajax response.
               $(document).ajaxStop($.unblockUI); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"
           });
       });


       // Toggling vehicle status
       $('.tile .vhcl_toggle').click(function () {


           var p_key = $(this).closest('.tile').find('#vhcl_id').val();
           var name = $(this).closest('.tile').find('#vhcl_no').val();
           var status = $(this).closest('.tile').find('#vhcl_status').val();
           name.replace('"', '\"');
           name.replace("'", "\'");

           var msg = (status == 1) ? " deactivate " : " activate ";
           msg += "the vehicle '" + name + "' ?";

           if (!confirm('Do you want to ' + msg))
               return;

           //Setting input.
           var inputs = {vhcl_id: p_key}; // eg: {parent_id: parent_id, status: 1}

           // Disabling whole page background till Ajax respond.
           $.blockUI(); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"

           $.post(site_url + "vehicles/toggleStatus", inputs, function (result) {

               alert(result);

               // enabling the whole page after ajax response.
               $(document).ajaxStop($.unblockUI); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"

               // Refreshing the browser & clearing cache.
               location.reload(true);

           });
       });

       // Adding labours to the vehicle
       $('.tile #vemp_add').click(function () {

           // Disabling whole page background till Ajax respond.
           $.blockUI(); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"


           var vhcl_no = $(this).closest('.tile').find('#vhcl_no').val();
           var vhcl_id = $(this).closest('.tile').find('#vhcl_id').val();

           //Initializing popup box.
           init_pop_vehicles_employees_add(vhcl_id);

           $('#pop_vehicles_employees_add #vemp_fk_vehicles').val(vhcl_id);
           $('#pop_vehicles_employees_add .namespan_box .namespan').text(vhcl_no);

           //Loading popupBox.
           loadPopup('pop_vehicles_employees_add');

           // enabling the whole page after ajax response.
           $(document).ajaxStop($.unblockUI); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"

       });

       // Changing default labours (Drivers/Loaders) of the vehicle.
       $('.tile .vemp_edit').click(function () {

           // Disabling whole page background till Ajax respond.
           $.blockUI(); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"

           var p_key = $(this).closest('.tile').find('#vhcl_id').val();
           var name = $(this).closest('.tile').find('#vhcl_no').val();

           //Initializing popup box.
           init_pop_vehicles_employees_edit();

           $('#pop_vehicles_employees_edit .namespan_box .namespan').text(name);

           $.getJSON(site_url + 'vehicles_employees/beforeEdit', {vhcl_id: p_key}, function (data) {

               $('#pop_vehicles_employees_edit .drivers').html(data['drivers']);
               $('#pop_vehicles_employees_edit .loaders').html(data['loaders']);

               //Loading popupBox.
               loadPopup('pop_vehicles_employees_edit');

               // enabling the whole page after ajax response.
               $(document).ajaxStop($.unblockUI); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"
           });
       });

       // Deleting Labours From The Vehicle.
       $('.tile .vemp_delete').click(function () {
           var p_key = $(this).closest('.tile').find('#vhcl_id').val();
           var name = $(this).closest('.tile').find('#vhcl_no').val();

           //Initializing popup box.
           init_pop_vehicles_employees_delete();

           $('#pop_vehicles_employees_delete .namespan_box .namespan').text(name);

           $.getJSON(site_url + 'vehicles_employees/beforeDelete', {vhcl_id: p_key}, function (data) {

               $('#pop_vehicles_employees_delete .drivers').html(data['drivers']);
               $('#pop_vehicles_employees_delete .loaders').html(data['loaders']);

               //Loading popupBox.
               loadPopup('pop_vehicles_employees_delete');
           });
       });




       // Adding freight charges between workcentres for the vehicle
       $('.tile .add_ifc').click(function () {

           // Disabling whole page background till Ajax respond.
           $.blockUI(); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"


           var vhcl_no = $(this).closest('.tile').find('#vhcl_no').val();
           var vhcl_id = $(this).closest('.tile').find('#vhcl_id').val();

           //Initializing popup box.
           init_pop_inter_freight_charges_add();

           $('#pop_inter_freight_charges_add #ifc_fkey_vehicles').val(vhcl_id);
           $('#pop_inter_freight_charges_add .namespan_box .namespan').text(vhcl_no);

           // ,ifc_fk_workcentres_from,ifc_fk_workcentres_to

           $.getJSON(site_url + 'vehicle_workcentres/getVehiclesWorkcentres', {vhcl_id: vhcl_id}, function (data) {

               var options = '';
               for (var x = 0; x < data.length; x++) {
                   options += '<option value="' + data[x]['value'] + '">' + data[x]['text'] + '</option>';
               }

               $('#pop_inter_freight_charges_add #ifc_fk_workcentres_from').html(options);

               //Loading popupBox.
               loadPopup('pop_inter_freight_charges_add');

               // enabling the whole page after ajax response.
               $(document).ajaxStop($.unblockUI); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"

           });


       });





       // Editing freight charges between workcentres for the vehicle.
       $('.tile .ifc_edit').click(function () {

           // Disabling whole page background till Ajax respond.
           $.blockUI(); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"

           var vhcl_id = $(this).closest('.tile').find('#vhcl_id').val();
           var vhcl_name = $(this).closest('.tile').find('#vhcl_no').val();
           var p_key = $(this).closest('td').find('.ifc_id').val();
           var from = $(this).closest('td').find('.ifc_from').val();
           var to = $(this).closest('td').find('.ifc_to').val();


           //Initializing popup box.
           init_pop_inter_freight_charges_edit();

           $('#pop_inter_freight_charges_edit #p_key').val(p_key);
           $('#pop_inter_freight_charges_edit .namespan_box .namespan').text('Freight Charges for :' + vhcl_name);
           $('#pop_inter_freight_charges_edit .location_dv').html(from + ' ---- ' + to);

           $.getJSON(site_url + 'inter_freight_charges/beforeEdit',
                   {
                       ifc_id: p_key

                   }, function (data) {

               $('#pop_inter_freight_charges_edit #ifc_rent').val(data['ifc_rent']);
               $('#pop_inter_freight_charges_edit #ifc_bata').val(data['ifc_bata']);
               $('#pop_inter_freight_charges_edit #ifc_loading').val(data['ifc_loading']);

               if (data['ifc_add_rent'] == 1)
                   $('#pop_inter_freight_charges_edit #ifc_add_rent').prop('checked', true);
               else
                   $('#pop_inter_freight_charges_edit #ifc_add_rent').prop('checked', false);

               if (data['ifc_add_bata'] == 1)
                   $('#pop_inter_freight_charges_edit #ifc_add_bata').prop('checked', true);
               else
                   $('#pop_inter_freight_charges_edit #ifc_add_bata').prop('checked', false);

               if (data['ifc_add_loading'] == 1)
                   $('#pop_inter_freight_charges_edit #ifc_add_loading').prop('checked', true);
               else
                   $('#pop_inter_freight_charges_edit #ifc_add_loading').prop('checked', false);

               //Loading popupBox.
               loadPopup('pop_inter_freight_charges_edit');

               // enabling the whole page after ajax response.
               $(document).ajaxStop($.unblockUI); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"
           });
       });



       // Deleting Freight charges of The Vehicle between workcentres.
       $('.tile .ifc_delete').click(function () {

           var vhcl_name = $(this).closest('.tile').find('#vhcl_no').val();
           var p_key = $(this).closest('td').find('.ifc_id').val();
           var from = $(this).closest('td').find('.ifc_from').val();
           var to = $(this).closest('td').find('.ifc_to').val();

           var msg = "Do you want to delete the freight charges of the vehicle: " + vhcl_name;
           msg += ' between ' + from + ' and ' + to;
           if (!confirm(msg))
               return;

           //Setting input.
           var inputs = {ifc_id: p_key}; // eg: {parent_id: parent_id, status: 1}

           // Disabling whole page background till Ajax respond.
           $.blockUI(); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"

           $.post(site_url + "inter_freight_charges/delete", inputs, function (result) {

               alert(result);

               // enabling the whole page after ajax response.
               $(document).ajaxStop($.unblockUI); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"

               // Refreshing the browser & clearing cache.
               location.reload(true);

           });

       });




       // Adding vehicle to the workcentres
       $('.tile .add_vwc').click(function () {

           // Disabling whole page background till Ajax respond.
           $.blockUI(); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"


           var vhcl_no = $(this).closest('.tile').find('#vhcl_no').val();
           var vhcl_id = $(this).closest('.tile').find('#vhcl_id').val();
           var vhcl_ownership = $(this).closest('.tile').find('#vhcl_ownership').val();

           //Initializing popup box.
           init_pop_vehicle_workcentres_add(vhcl_id);

           $('#pop_vehicle_workcentres_add #vwc_fk_vehicles').val(vhcl_id);
           $('#pop_vehicle_workcentres_add .namespan_box .namespan').text(vhcl_no);

           if (vhcl_ownership == 1) // Our's vehicle
           {
               $('#pop_vehicle_workcentres_add .valuable').closest('tr').show();
           }
           else if (vhcl_ownership == 2) // Other's vehicle
           {
               $('#pop_vehicle_workcentres_add .valuable').val('');
               $('#pop_vehicle_workcentres_add .valuable').closest('tr').hide();
           }

           $.getJSON(site_url + 'vehicle_workcentres/eligible_workcentres_for_vehicle', {vhcl_id: vhcl_id}, function (data) {

               var options = '';
               for (var x = 0; x < data.length; x++) {
                   options += '<option value="' + data[x]['value'] + '">' + data[x]['text'] + '</option>';
               }

               $('#pop_vehicle_workcentres_add #vwc_fk_workcentres').html(options);

               // enabling the whole page after ajax response.
               $(document).ajaxStop($.unblockUI); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"

               //Loading popupBox.
               loadPopup('pop_vehicle_workcentres_add');

           });


       });




       // Edit vehicle's details in workcentres.
       $('.tile .vwc_edit').click(function () {

           // Disabling whole page background till Ajax respond.
           $.blockUI(); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"

           var vhcl_id = $(this).closest('.tile').find('#vhcl_id').val();
           var vhcl_name = $(this).closest('.tile').find('#vhcl_no').val();
           var vhcl_ownership = $(this).closest('.tile').find('#vhcl_ownership').val();
           var p_key = $(this).closest('.vwc_dv').find('.vwc_id').val();
           var wcntr_name = $(this).closest('.vwc_dv').find('.vwc_wc_name').val();


           //Initializing popup box.
           init_pop_vehicle_workcentres_edit();

           $('#pop_vehicle_workcentres_edit #vwc_id').val(p_key);
           $('#pop_vehicle_workcentres_edit .namespan_box .namespan').text('Details of vehicle ' + vhcl_name + ' in ' + wcntr_name);

           $.getJSON(site_url + 'vehicle_workcentres/beforeEdit',
                   {
                       vwc_id: p_key

                   }, function (data) {

               $('#pop_vehicle_workcentres_edit #vwc_cost').val(data['vwc_cost']);
               $('#pop_vehicle_workcentres_edit #vwc_ob').val(data['vwc_ob']);
               $('#pop_vehicle_workcentres_edit input[type=radio][name=vwc_ob_mode][value=' + data['vwc_ob_mode'] + ']').prop('checked', true);
               $('#pop_vehicle_workcentres_edit #vwc_hourly_rate').val(data['vwc_hourly_rate']);
               $('#pop_vehicle_workcentres_edit #vwc_daily_rate').val(data['vwc_daily_rate']);
               $('#pop_vehicle_workcentres_edit #vwc_monthly_rate').val(data['vwc_monthly_rate']);
               $('#pop_vehicle_workcentres_edit #vwc_sold_price').val(data['vwc_sold_price']);

               if (vhcl_ownership == 1) // Our's vehicle
               {
                   $('#pop_vehicle_workcentres_edit .valuable').closest('tr').show();
               }
               else if (vhcl_ownership == 2) // Other's vehicle
               {
                   $('#pop_vehicle_workcentres_edit .valuable').val('');
                   $('#pop_vehicle_workcentres_edit .valuable').closest('tr').hide();
               }

               //Loading popupBox.
               loadPopup('pop_vehicle_workcentres_edit');

               // enabling the whole page after ajax response.
               $(document).ajaxStop($.unblockUI); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"
           });
       });



       // toggle the vehicle's status in workcentre
       $('.tile .vwc_delete').click(function () {

           var vhcl_name = $(this).closest('.tile').find('#vhcl_no').val();
           var p_key = $(this).closest('.vwc_dv').find('.vwc_id').val();
           var wcntr_name = $(this).closest('.vwc_dv').find('.vwc_wc_name').val();
           var status = $(this).closest('.vwc_dv').find('.vwc_status').val();



           var msg = (status == 1) ? " deactivate " : " activate ";
           msg += "the vehicle '" + vhcl_name + "' in the workcentre '" + wcntr_name + "'?";

           if (!confirm('Do you want to ' + msg))
               return;

           //Setting input.
           var inputs = {vwc_id: p_key}; // eg: {parent_id: parent_id, status: 1}

           // Disabling whole page background till Ajax respond.
           $.blockUI(); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"

           $.post(site_url + "vehicle_workcentres/toggleStatus", inputs, function (result) {

               alert(result);

               // enabling the whole page after ajax response.
               $(document).ajaxStop($.unblockUI); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"

               // Refreshing the browser & clearing cache.
               location.reload(true);

           });

       });



       // Adding freight charges to party destinations for the vehicle
       $('.tile .add_fc').click(function () {

           // Disabling whole page background till Ajax respond.
           $.blockUI(); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"

           var vhcl_no = $(this).closest('.tile').find('#vhcl_no').val();
           var vhcl_id = $(this).closest('.tile').find('#vhcl_id').val();
           var wcntr_name = $(this).closest('.xpl').find('.fc_wcntr').val();
           var wcntr_id = $(this).closest('.xpl').find('.fc_wcntr_id').val();

           var msg_1 = "ADD FREIGHT CHARGE FOR VEHICLE: " + vhcl_no;
           var msg_2 = "From Workcentre: " + wcntr_name;

           //Initializing popup box.
           init_pop_freight_charges_add();

           $('#pop_freight_charges_add #fc_fk_vehicles').val(vhcl_id);
           $('#pop_freight_charges_add #fc_fk_workcentres').val(wcntr_id);
           $('#pop_freight_charges_add .titleColumn').text(msg_1);
           $('#pop_freight_charges_add .namespan_box .namespan').text(msg_2);

           $.getJSON(site_url + 'freight_charges/getFreeParties', {vhcl_id: vhcl_id, wcntr_id: wcntr_id}, function (data) {
               var options = '';
               for (var x = 0; x < data.length; x++) {
                   options += '<option value="' + data[x]['value'] + '">' + data[x]['text'] + '</option>';
               }

               $('#pop_freight_charges_add #pty_id').html(options);

               //Loading popupBox.
               loadPopup('pop_freight_charges_add');

               // enabling the whole page after ajax response.
               $(document).ajaxStop($.unblockUI); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"

           });


       });

       // Editing freight charges to party destinations for the vehicle.
       $('.tile .fc_edit').click(function () {

           // Disabling whole page background till Ajax respond.
           $.blockUI(); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"

           var vhcl_no = $(this).closest('.tile').find('#vhcl_no').val();
           var wcntr_name = $(this).closest('.adbdr').find('.fc_wcntr').val();
           var pty_name = $(this).closest('td').find('.fc_party').val();
           var p_key = $(this).closest('td').find('.fc_id').val();

           var msg_1 = "EDIT FREIGHT CHARGE FOR VEHICLE: " + vhcl_no;
           var msg_2 = "From Workcentre: " + wcntr_name;
           msg_2 += " To Party: " + pty_name;

           //Initializing popup box.
           init_pop_freight_charges_edit();

           $('#pop_freight_charges_edit #p_key').val(p_key);
           $('#pop_freight_charges_edit .titleColumn').text(msg_1);
           $('#pop_freight_charges_edit .namespan_box .namespan').text(msg_2);

           $.getJSON(site_url + 'freight_charges/beforeEdit',
                   {
                       fc_id: p_key

                   }, function (data) {

               $('#pop_freight_charges_edit #fc_rent').val(data['fc_rent']);
               $('#pop_freight_charges_edit #fc_bata').val(data['fc_bata']);
               $('#pop_freight_charges_edit #fc_loading').val(data['fc_loading']);

               if (data['fc_add_rent'] == 1)
                   $('#pop_freight_charges_edit #fc_add_rent').prop('checked', true);
               else
                   $('#pop_freight_charges_edit #fc_add_rent').prop('checked', false);

               if (data['fc_add_bata'] == 1)
                   $('#pop_freight_charges_edit #fc_add_bata').prop('checked', true);
               else
                   $('#pop_freight_charges_edit #fc_add_bata').prop('checked', false);

               if (data['fc_add_loading'] == 1)
                   $('#pop_freight_charges_edit #fc_add_loading').prop('checked', true);
               else
                   $('#pop_freight_charges_edit #fc_add_loading').prop('checked', false);

               //Loading popupBox.
               loadPopup('pop_freight_charges_edit');

               // enabling the whole page after ajax response.
               $(document).ajaxStop($.unblockUI); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"
           });
       });



       // Deleting Freight charges for The Vehicle to party destinations.
       $('.tile .fc_delete').click(function () {

           var vhcl_no = $(this).closest('.tile').find('#vhcl_no').val();
           var wcntr_name = $(this).closest('.adbdr').find('.fc_wcntr').val();
           var pty_name = $(this).closest('td').find('.fc_party').val();
           var p_key = $(this).closest('td').find('.fc_id').val();

           var msg = "Do you want to delete the freight charges for the vehicle '" + vhcl_no + "' from '" + wcntr_name + "'";
           msg += " to 'party: " + pty_name + "'.";

           if (!confirm(msg))
               return;

           //Setting input.
           var inputs = {fc_id: p_key}; // eg: {parent_id: parent_id, status: 1}

           // Disabling whole page background till Ajax respond.
           $.blockUI(); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"

           $.post(site_url + "freight_charges/delete", inputs, function (result) {

               alert(result);

               // enabling the whole page after ajax response.
               $(document).ajaxStop($.unblockUI); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"

               // Refreshing the browser & clearing cache.
               location.reload(true);

           });

       });






   });

</script>
<script type="text/javascript" src="plugins/blockui-master/jquery.blockUI.js"></script> 