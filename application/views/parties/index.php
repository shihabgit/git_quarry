<?php
$this->load->view('parties/pop_edit');
$this->load->view('party_vehicles/pop_add');
$this->load->view('party_vehicles/pop_edit');
$this->load->view('party_destinations/pop_add');
$this->load->view('party_destinations/pop_edit');
$this->load->view('party_license_details/pop_add');
$this->load->view('party_license_details/pop_edit');
$this->load->view('destination_workcentres/pop_add');
$this->load->view('party_vehicle_rents/pop_add');
$this->load->view('party_vehicle_rents/pop_edit');
?>



<link href="css/<?php echo $this->themes[$this->theme]['text']; ?>/tiles.css" rel="stylesheet" type="text/css" />




<!-- Fancy scrollbar START 	-->
<link type="text/css" href="plugins/fancy_scrollbars/by_shihab/css/jquery.jscrollpane.css" rel="stylesheet" media="all" />

<style type="text/css" id="page-css">
   /* Styles specific to this particular page */
   .scroll-pane
   {
      width: 98%;
      height: 500px;
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
   $(function()
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
   <?php echo form_open("parties", array('id' => 'searchForm')); ?>

   <table width="50%" cellpadding="5" cellspacing="0" class="tbl_input">
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
               1. Make an option to view all licences and to activate/deactivate it.
            </p>

            <div class="inputblok" style="width: 100%;">
               <div class="sec_container">
                  <p class="input-categories">Basic Details</p>
                  <table class="sec_table">
                     <tr>
                        <th>Category</th>
                        <td>
                           <select name="pdst_category" >
                              <?php echo get_options2($pdst_category, ifSet2('pdst_category'), true, '--- select ---'); ?>
                           </select>
                        </td>

                     </tr> 
                     <tr>
                        <th>Party Name</th>
                        <td>
                           <input type="text" name="pty_name" value="<?php echo ifSet2('pty_name') ?>">
                           <input type="radio" name="pty_status" value="1" <?php echo ifSetRadio2('pty_status', 1, true) ?> />
                           <span class="multy_options">Active</span>

                           <input type="radio" name="pty_status" value="2" <?php echo ifSetRadio2('pty_status', 2) ?> />
                           <span class="multy_options">Inactive</span> 

                           <input type="radio" name="pty_status" value="0" <?php echo ifSetRadio2('pty_status', 0) ?> />
                           <span class="multy_options">All</span>
                        </td>
                     </tr>
                     <tr>
                        <th>Party Vehicle No</th>
                        <td>
                           <input type="text" name="pvhcl_no" value="<?php echo ifSet2('pvhcl_no') ?>" >
                           <input type="radio" name="pvhcl_status" value="1" <?php echo ifSetRadio2('pvhcl_status', 1) ?> />
                           <span class="multy_options">Active</span>

                           <input type="radio" name="pvhcl_status" value="2" <?php echo ifSetRadio2('pvhcl_status', 2) ?> />
                           <span class="multy_options">Inactive</span>   

                           <input type="radio" name="pvhcl_status" value="0" <?php echo ifSetRadio2('pvhcl_status', 0, true) ?> />
                           <span class="multy_options">All</span>                                 
                        </td>
                     </tr>


                     <tr>
                        <th>Party Destination</th>
                        <td>
                           <input type="text" name="pdst_name" value="<?php echo ifSet2('pdst_name') ?>" >
                           <input type="radio" name="pdst_status" value="1" <?php echo ifSetRadio2('pdst_status', 1) ?> />
                           <span class="multy_options">Active</span>

                           <input type="radio" name="pdst_status" value="2" <?php echo ifSetRadio2('pdst_status', 2) ?> />
                           <span class="multy_options">Inactive</span>    

                           <input type="radio" name="pdst_status" value="0" <?php echo ifSetRadio2('pdst_status', 0, true) ?> />
                           <span class="multy_options">All</span>                                
                        </td>
                     </tr>




                     <tr>
                        <th>Availability : </th>
                        <td>
                           <select name="wcntr_id[]" multiple="multiple"  style="height:100px;">
                              <?php echo get_options2($workcentres, ifSet('wcntr_id')); ?>
                           </select>
                           <input type="radio" name="dwc_status" value="1" <?php echo ifSetRadio2('dwc_status', 1) ?> />
                           <span class="multy_options">Active</span>

                           <input type="radio" name="dwc_status" value="2" <?php echo ifSetRadio2('dwc_status', 2) ?> />
                           <span class="multy_options">Inactive</span>    

                           <input type="radio" name="dwc_status" value="0" <?php echo ifSetRadio2('dwc_status', 0, true) ?> />
                           <span class="multy_options">All</span>                                
                           <p class="help">Hold <b>Ctrl</b> Key to select multiple workcentres.</p>
                        </td>
                     </tr>




                  </table>
               </div>      <!--     End of <div class="sec_container">     -->
            </div> <!--     End of  <div class="inputblok">         -->
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

         <a href="<?php echo site_url('parties/add'); ?>" >
            <div class="right_action"  title="Add party">
               <div class="settings_btn">
                  <img src="images/Add button.png" height="24" width="24" />
               </div>
               <div class="clear_boath"></div>
            </div>
         </a>


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

   </div>    <!--<div class="search-header">-->


   <div class="search-header">
      <div id="right_pan">




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
   </div>    <!--<div class="search-header">-->




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



            if ($row['pty_status'] == 1)
            {
               $class = 'main_title_active';
               $status = 'Active';
               $img = 'images/delete4.png';
               $tooltip = "Deactivate Item";
               $update = '<div class="tool_image pty_edit"> <img title="Edit Party" src="images/edit11.png"> </div>';
            }
            else
            {
               $class = 'inactive';
               $status = 'Inactive';
               $img = 'images/activate2.png';
               $tooltip = "Activate Item";
               $update = '';
            }
            ?>

            <div class="tile scroll-pane blackScroll">
               <?php
               // Following hidden elements are to use with popup: units/pop_edit.php. 
               echo '<input type="hidden" class="pty_id_popup" value="' . $row['pty_id'] . '">';
               echo "<input type='hidden' class='pty_name_popup' value='" . $row['pty_name'] . "'>";
               echo '<input type="hidden" class="pty_status_popup" value="' . $row['pty_status'] . '">';
               ?>
               <div class="bring">
                  <div class="bring_slNo"><?php echo $slNo; ?></div>
                  <div class="tile_name  msctr">
                     <div class="main_name <?php echo $class; ?>" title="<?php echo $status; ?> party">
                        <?php echo $row['pty_name'] . '. (' . $row['pty_id'] . ')'; ?>
                     </div>
                     <div class="detailbox"> 
                        <?php
                        if ($row['pty_date'])
                           echo '<span class="dob">' . formatDate($row['pty_date'], false, 1) . '</span>';
                        if ($row['pty_phone'])
                           echo '<span class="phoneno">' . $row['pty_phone'] . '</span>';
                        if ($row['pty_email'])
                           echo '<span class="emailid">' . $row['pty_email'] . '</span>';
                        ?>
                     </div>
                  </div>

                  <div class="toolics"> 
                     <?php echo $update; ?>                            
                     <div class="tool_image toggleStatus"><img title="<?php echo $tooltip; ?>" src="<?php echo $img; ?>"></div>
                  </div>


                  <div class="clear_boath"></div>
               </div>
               <hr>





               <div class="bring">

                  <div style="width:50%;margin-top:3px;border:none;float:left;" class="title_content">
                     <table cellspacing="0" cellpadding="0" style="width:100%;" class="adbdr">

                        <tbody>
                           <tr><th class="xpl" colspan="2">Vechicle Details</th></tr>
                           <tr>
                              <th>
                        <p class="tile_content_h_title">Vehicle</p>
                        <div style="float:right;padding:2px 2px 0 0;cursor: pointer;">
                           <img class="add_vehicle" title="Add Vehicle" src="images/add3.png"/>
                        </div>
                        </th>

                        <th><p class="tile_content_h_title">Capacity <span>(X-Cap) CFT</span></p></th>

                        </tr>

                        <?php
                        if (!$vehicles[$row['pty_id']])
                           echo '<tr class="nodata"><th colspan="2">No Vehicles Found</th></tr>';
                        else
                        {
                           foreach ($vehicles[$row['pty_id']] as $vhcl_row)
                           {
                              ?>
                              <tr style="height:30px;">
                                 <td>
                                    <div style="padding:2px; text-align: left;">
                                       <?php
                                       // Taking first 7 characters from the name.
                                       $shortName = '<span class="vnm" title="' . $vhcl_row['pvhcl_name'] . '">' . substr($vhcl_row['pvhcl_name'], 0, 6) . '</span>';

                                       // If the no: of characters in name is greater than 7, appending dots.
                                       if (strlen($vhcl_row['pvhcl_name']) > 7)
                                          $shortName .= '<span class="vnmdot">....</span>';
                                       $shortName .= '<span class="vno">' . $vhcl_row['pvhcl_no'] . '</span>';

                                       if ($vhcl_row['pvhcl_status'] == 1)
                                       {
                                          $class = 'active';
                                          $img = 'images/delete5.png';
                                          $tooltip = "Deactivate Vehicle";
                                          $update = '<img id="edit_icon" title="Edit Vehicle" src="images/edit12.png" />';
                                       }
                                       else
                                       {
                                          $class = 'inactive';
                                          $img = 'images/activate3.png';
                                          $tooltip = "Activate Vehicle";
                                          $update = '';
                                       }
                                       ?>
                                       <div class="<?php echo $class; ?>" style="float:left;"><?php echo $shortName; ?></div>
                                       <div class="ptyvhcl" style="float:right;padding-top:1px;">
                                          <input type="hidden" id="pvhcl_id" value="<?php echo $vhcl_row['pvhcl_id']; ?>" >
                                          <input type="hidden" id="pvhcl_no" value="<?php echo $vhcl_row['pvhcl_no']; ?>" >
                                          <input type="hidden" id="pvhcl_status" value="<?php echo $vhcl_row['pvhcl_status']; ?>" >

                                          <?php echo $update; ?>
                                          <img id="toggle_icon" title="<?php echo $tooltip; ?>" src="<?php echo $img; ?>"/>
                                       </div>

                                    </div>
                                 </td>
                                 <td align="left">
                                    <?php
                                    //  1 CFT = 12 Inches.
                                    //  CFT Formula : (Length" x Width" x Height")/ (12*12*12) = Cubic feet (CFT) 
                                    //  ie: CFT Formula : (Length" x Width" x Height")/1728 = Cubic feet (CFT) 
                                    // Calculating vehicle capacity when normal body height.                    
                                    $operants = array();
                                    $operants[] = $vhcl_row['pvhcl_length'];
                                    $operants[] = $vhcl_row['pvhcl_breadth'];
                                    $operants[] = $vhcl_row['pvhcl_height'];

                                    // Volume in inches.
                                    $normal_volume = mybcmath($operants, '*');

                                    // Volume in Feet.
                                    $normal_volume = bcdiv("$normal_volume", "1728", 2);

                                    // Calculating vehicle capacity when extra body height.               
                                    $operants = array();
                                    $operants[] = $vhcl_row['pvhcl_length'];
                                    $operants[] = $vhcl_row['pvhcl_breadth'];
                                    $operants[] = $vhcl_row['pvhcl_xheight'];

                                    // Volume in inches.
                                    $extra_volume = mybcmath($operants, '*');

                                    // Volume in Feet.
                                    $extra_volume = bcdiv("$extra_volume", "1728", 2);

                                    echo $normal_volume . ' (' . $extra_volume . ')';
                                    ?>
                                 </td>
                              </tr>
                              <?php
                           }
                        }
                        ?>
                        </tbody>
                     </table>
                  </div>

                  <div style="width:49%;margin-top:3px;border:none;float:right;" class="title_content">
                     <table cellspacing="0" cellpadding="0" style="width:100%;" class="adbdr">

                        <tbody>
                           <tr><th class="xpl" colspan="2">Destination Details</th></tr>
                           <tr>
                              <th>
                        <p class="tile_content_h_title">Destination</p>

                        <div style="float:right;padding:2px 2px 0 0;cursor: pointer;">
                           <img class="add_destination" title="Add Destination" src="images/add3.png" />
                        </div>
                        </th>

                        <th>
                        <p class="tile_content_h_title">Reg.Name</p>

                        <div style="float:right;padding:2px 2px 0 0;cursor: pointer;">
                           <img class="initPartyLicenseAdd" title="Add Reg. Name" src="images/add3.png"/>
                        </div>

                        </th>

                        </tr>
                        <?php
                        foreach ($destinations[$row['pty_id']] as $dst_row)
                        {
                           ?>
                           <tr>
                              <td>
                                 <div> 
                                    <?php
                                    if ($dst_row['pdst_category'] == 1)    // Supplier
                                    {
                                       echo '<div class="tooltip" data-tooltip="Supplier" style="float:left;padding:2px;">';
                                       echo '<img src="images/suppliers2.png" />';
                                       echo '</div>';
                                    }
                                    else if ($dst_row['pdst_category'] == 2)    // Customer
                                    {
                                       echo '<div class="tooltip" data-tooltip="Customer" style="float:left;padding:2px;">';
                                       echo '<img src="images/customer2.png" />';
                                       echo '</div>';
                                    }
                                    else if ($dst_row['pdst_category'] == 3)    // Both Supplier  & Customer.
                                    {
                                       echo '<div class="tooltip" data-tooltip="Both Supplier & Customer" style="float:left;padding:2px;">';
                                       echo '<img src="images/both.png" />';
                                       echo '</div>';
                                    }

                                    if ($dst_row['pdst_status'] == 1)
                                    {
                                       $class = 'active';
                                       $img = 'images/delete5.png';
                                       $tooltip = "Deactivate Destination";
                                       $update = '<img id="edit_icon" title="Edit Destination" src="images/edit12.png"/>';
                                    }
                                    else
                                    {
                                       $class = 'inactive';
                                       $img = 'images/activate3.png';
                                       $tooltip = "Activate Destination";
                                       $update = '';
                                    }
                                    ?>
                                    <div style="padding:5px 2px;float:left;text-align: left;" class="<?php echo $class; ?>"><?php echo $dst_row['pdst_name'] ?>   </div>

                                    <div class="ptydst" style="float:right;padding-top:5px;">
                                       <input type="hidden" id="pdst_id" value="<?php echo $dst_row['pdst_id']; ?>">
                                       <input type="hidden" id="pdst_name" value="<?php echo $dst_row['pdst_name']; ?>">
                                       <input type="hidden" id="pdst_status" value="<?php echo $dst_row['pdst_status']; ?>" >

                                       <?php echo $update; ?>
                                       <img id="toggle_icon" title="<?php echo $tooltip; ?>" src="<?php echo $img; ?>"/>
                                    </div>
                                 </div>

                              </td>
                              <td align="left">

                                 <?php
                                 if ($dst_row['pld_firm_name'])
                                 {

                                    // Taking first 9 characters from the name.
                                    $shortName = '<span>' . substr($dst_row['pld_firm_name'], 0, 8) . '</span>';

                                    // If the no: of characters in name is greater than 9, appending dots.
                                    if (strlen($dst_row['pld_firm_name']) > 9)
                                       $shortName .= '<span class="vnmdot">..... </span>';

                                    if ($dst_row['pld_status'] == 1)
                                    {
                                       $class = 'active';
                                       $img = 'images/delete5.png';
                                       $tooltip = "Deactivate Licence";
                                       $update = '<img id="edit_icon" title="Edit License" src="images/edit12.png"/>';
                                    }
                                    else
                                    {
                                       $class = 'inactive';
                                       $img = 'images/activate3.png';
                                       $tooltip = "Activate Licence";
                                       $update = '';
                                    }
                                    ?>
                                    <div title="<?php echo $dst_row['pld_firm_name'] ?>" class="<?php echo $class; ?>" style="padding:5px 2px;float:left;text-align:left;">
                                       <?php echo $shortName; ?>
                                    </div>

                                    <div class="ptylic" style="float:right;padding-top:5px;">
                                       <input type="hidden" id="pld_id" value="<?php echo $dst_row['pld_id']; ?>">
                                       <input type="hidden" id="pld_firm_name" value="<?php echo $dst_row['pld_firm_name']; ?>">
                                       <input type="hidden" id="pld_status" value="<?php echo $dst_row['pld_status']; ?>">

                                       <?php echo $update; ?>
                                       <img id="toggle_icon" title="<?php echo $tooltip; ?>" src="<?php echo $img; ?>"/>
                                    </div>
                                 <?php } ?>
                              </td>
                           </tr>

                           <?php
                        }
                        ?>



                        </tbody>
                     </table>
                  </div>
               </div>










               <div class="bring">

                  <div style="width:100%;margin-top:3px;border:none;" class="title_content">
                     <table cellspacing="0" cellpadding="0" style="width:100%;" class="adbdr">

                        <tbody>
                           <tr>
                              <th class="xpl" colspan="7">
                                 Availability Details
                        <div style="float:right;padding:2px 2px 0 0;cursor: pointer;">
                           <img class="add_to_workcentre" title="Add to workcentre" src="images/add3.png" />
                        </div>
                        </th>
                        </tr>


                        <tr>
                           <th><p class="tile_content_h_title">Date</p></th>
                        <th style="width:70px;"><p class="tile_content_h_title">Workcentre</p> </th>
                        <th style="width:80px;"><p class="tile_content_h_title">Destination</p></th>
                        <th><p class="tile_content_h_title">OB</p></th>
                        <th><p class="tile_content_h_title">Cr.Lmt</p></th>
                        <th><p class="tile_content_h_title">Dr.Lmt</p></th>
                        <th><p class="tile_content_h_title">Freight Charge</p></th>
                        </tr>


                        <?php
                        if ($dst_wnctr)
                        {
                           foreach ($destinations[$row['pty_id']] as $dst_row)
                           {
                              foreach ($dst_wnctr[$dst_row['pdst_id']] as $dwc)
                              {
                                 if ($dwc['dwc_status'] == 1)
                                 {
                                    $class = 'active';
                                    $img = 'images/delete5.png';
                                    $tooltip = "Deactivate";
                                 }
                                 else
                                 {
                                    $class = 'inactive';
                                    $img = 'images/activate3.png';
                                    $tooltip = "Activate";
                                 }
                                 ?>  
                                 <tr class="avail_row <?php echo $class; ?>">
                                    <td>
                                       <div class="availability" style="float:left;">
                                          <input type="hidden" class="dwc_id" value="<?php echo $dwc['dwc_id']; ?>">
                                          <input type="hidden" class="dwc_status" value="<?php echo $dwc['dwc_status']; ?>">

                                          <input type="hidden" class="dwc_fk_party_destinations" value="<?php echo $dwc['dwc_fk_party_destinations']; ?>" >
                                          <input type="hidden" class="dwc_fk_workcentres" value="<?php echo $dwc['dwc_fk_workcentres']; ?>" >
                                          <input type="hidden" class="pdst_name_ns" value="<?php echo $dwc['pdst_name']; ?>" >
                                          <input type="hidden" class="wcntr_name_ns" value="<?php echo $dwc['wcntr_name']; ?>" >

                                          <img id="toggle_icon" title="<?php echo $tooltip; ?>" src="<?php echo $img; ?>"/>
                                       </div>
                                       <div style="float: right;">
                                          <?php echo date('d/m/y', strtotime($dwc['dwc_date'])); ?>  
                                       </div>
                                    </td>
                                    <td><?php echo $dwc['wcntr_name']; ?></td>
                                    <td><?php echo $dwc['pdst_name']; ?></td>

                                    <td>
                                       <?php
                                       $crdr = ($dwc['dwc_ob_mode'] == 1) ? ' Cr.' : (($dwc['dwc_ob_mode'] == 2) ? ' Dr.' : '');
                                       echo $dwc['dwc_ob'] . $crdr;
                                       ?>
                                    </td>
                                    <td><?php echo $dwc['dwc_credit_lmt']; ?></td>
                                    <td><?php echo $dwc['dwc_debt_lmt']; ?></td>  
                                    <td>

                                       <div style="float: right;padding:2px 2px 0 0;cursor: pointer;width:12px;">
                                          <img class="add_freight" title="Add Freight" src="images/add3.png" />
                                       </div>
                                       <div class="clear_boath"></div>
                                       <?php
                                       if (isset($freight[$dwc['dwc_id']]))
                                       {
                                          foreach ($freight[$dwc['dwc_id']] as $f_charge)
                                          {
                                             ?>
                                             <div class="freight"> 
                                                <input type="hidden" class="pvr_id" value="<?php echo $f_charge['pvr_id']; ?>">
                                                <input type="hidden" class="pvr_rent" value="<?php echo $f_charge['pvr_rent']; ?>">
                                                <input type="hidden" class="pvr_add_rent" value="<?php echo $f_charge['pvr_add_rent']; ?>">

                                                <div style="float: left; margin-right: 5px;">
                                                   <img class="edit_icon" title="Edit Freight" src="images/edit12.png"/>
                                                   <img class="delete_icon" title="Delete Freight" src="images/delete5.png"/>
                                                </div>                                                          
                                                <div class="freight_vehicle" style="float: left;"><?php echo $f_charge['pvhcl_no']; ?></div>
                                                <div style="width: 10px;float: right;">
                                                   <?php
                                                   if ($f_charge['pvr_add_rent'] == 1)
                                                      echo '<img title="Rent Will Be Added To The Bill Amount" src="images/tick.png">';
                                                   else echo '&nbsp';
                                                   ?>
                                                </div>
                                                <div class="freight_charge" style="float: right;">
                                                   <?php echo $f_charge['pvr_rent']; ?>
                                                </div>
                                                <div class="clear_boath"></div>

                                             </div>
                                             <?php
                                          }
                                       }
                                       ?>

                                    </td>
                                 </tr>
                                 <?php
                              }
                           }
                        }
                        ?>
                        </tbody>
                     </table>
                  </div>
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


   </div>       <!--<div id="tile_container">-->







</div><!--<div class="dv-bottom-content"> -->



<script src="js/parties_index.js" type="text/javascript"></script>
