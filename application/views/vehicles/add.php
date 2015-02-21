<div class="dv-top-content" align="center">
    <?php echo form_open("vehicles/add", array('id' => 'add_form')); ?>
    <table width="50%" cellpadding="5" cellspacing="0" class="tbl_input">
        <tr>
            <td>

                <div class="title-box">
                    <div id="img-container">
                        <img src="images/staffs.png" width="35" height="35"/>
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
                    1. Vehicle No must be unique.<br>
                </p>

                <div class="inputblok" style="width: 100%;">

                    <div class="sec_container">
                        <p class="input-categories">Basic Details</p>
                        <table class="sec_table">

                            <tr>
                                <th>Name</th>
                                <td>
                                    <input type="text" name="vehicles[vhcl_name]" value="<?php echo set_value('vehicles[vhcl_name]') ?>" >
                                    <?php echo form_error('vehicles[vhcl_name]'); ?>




                                </td>
                            </tr>
                            <tr>
                                <th>Number</th>
                                <td>
                                    <input type="text" name="vehicles[vhcl_no]" value="<?php echo set_value('vehicles[vhcl_no]') ?>" >
                                    <?php echo form_error('vehicles[vhcl_no]'); ?>
                                </td>
                            </tr>

                            <tr>
                                <th>Capacity 
                                    <span class="help">(in inches)</span>
                                </th>
                                <td>
                                    <input type="text" class="numberOnly" placeholder="Lenght" name="vehicles[vhcl_length]" value="<?php echo set_value('vehicles[vhcl_length]') ?>" style="width:68px;" >
                                    <input type="text" class="numberOnly" placeholder="Breadth" name="vehicles[vhcl_breadth]" value="<?php echo set_value('vehicles[vhcl_breadth]') ?>" style="width:68px;" >
                                    <input type="text" class="numberOnly" placeholder="Height" name="vehicles[vhcl_height]" value="<?php echo set_value('vehicles[vhcl_height]') ?>" style="width:68px;" >

                                    <input type="text" class="numberOnly" placeholder="XHeight" name="vehicles[vhcl_xheight]" value="<?php echo set_value('vehicles[vhcl_xheight]') ?>" style="width:68px;" >
                                </td>
                            </tr>

                            <tr>
                                <th>Remarks</th>
                                <td>
                                    <input type="text" name="vehicles[vhcl_remarks]" value="<?php echo set_value('vehicles[vhcl_remarks]') ?>" >
                                    <?php echo form_error('vehicles[vhcl_remarks]'); ?>
                                </td>
                            </tr>

                            <tr>
                                <th>Ownership</th>
                                <td>
                                    <input type="radio" name="vehicles[vhcl_ownership]" class="vhcl_ownership" value="1" <?php echo ifSetRadioGroupArray('vehicle', 'vhcl_ownership', 1, TRUE) ?>  />
                                    <span class="multy_options">Ours</span>

                                    <input type="radio" name="vehicles[vhcl_ownership]" class="vhcl_ownership" value="2" <?php echo ifSetRadioGroupArray('vehicle', 'vhcl_ownership', 2) ?> />
                                    <span class="multy_options">Others</span>
                                </td>
                            </tr>

                        </table>
                    </div>          <!--     End of Personal Details-->
                </div>
            </td>
        </tr>
    </table>
    <table class="sec_table2 tbl_input" style="width: 80%;">
        <tr><th colspan="7"><h3>EMPLOYEE AVAILABILITY.</h3></th></tr>
        <tr>
            <th rowspan="2" valign="center" align="center" style="width:100px;">Firm</th>
            <th rowspan="2" valign="center" align="center" style="width:150px;">Workcentre</th>
            <th rowspan="2" valign="center" align="center">Cost</th>
            <th rowspan="2" valign="center" align="center">Old Balance</th>
            <th colspan="3" valign="center" align="center">Rent Rates</th>
        </tr>
        <tr>
            <th>Hourly</th>
            <th>Daily</th>
            <th>Monthly</th>
        </tr>

        <?php
        if ($firms)
        {
           foreach ($firms as $firm_id => $firm_name)
           {
              foreach ($workcentres as $wc)
              {
                 if ($wc['wcntr_fk_firms'] == $firm_id)
                 {
                    $wc_id = $wc['wcntr_id'];
                    echo '<tr class="wc_row">';
                    echo '<td class="wc_col">' . $firm_name . '</td>';


                    echo '<td class="wc_col">';
                    $checked = ($vhclwc_flds['vwc_fk_workcentres'][$wc_id]) ? " checked " : '';
                    echo '<input type="checkbox"' . $checked . ' name="vhclwc_flds[vwc_fk_workcentres][' . $wc_id . ']" class="wcntre_id"  />' . $wc['wcntr_name'];
                    echo '</td>';

                    echo '<td style="width: 100px;"><input name="vhclwc_flds[vwc_cost][' . $wc_id . ']" value="' . $vhclwc_flds['vwc_cost'][$wc_id] . '" type="text" class="intOnly valuable" style="width:80px;"></td>';

                    echo '<td>';
                    echo '<input type="text" class="intOnly" style="width:80px;" name="vhclwc_flds[vwc_ob][' . $wc_id . ']" value="' . $vhclwc_flds['vwc_ob'][$wc_id] . '" >';

                    $checked = ($vhclwc_flds['vwc_ob_mode'][$wc_id] == 1) ? " checked" : '';
                    echo '<input type="radio" name="vhclwc_flds[vwc_ob_mode][' . $wc_id . ']" value="1" ' . $checked . ' />';
                    echo '<span class="multy_options">Cr.</span>';

                    $checked = ($vhclwc_flds['vwc_ob_mode'][$wc_id] != 1) ? " checked" : '';
                    echo '<input type="radio" name="vhclwc_flds[vwc_ob_mode][' . $wc_id . ']" value="2" ' . $checked . ' />';
                    echo '<span class="multy_options">Dr.</span>';
                    echo '</td>';

                    echo '<td style="width: 100px;"><input name="vhclwc_flds[vwc_hourly_rate][' . $wc_id . ']" value="' . $vhclwc_flds['vwc_hourly_rate'][$wc_id] . '" type="text" class="intOnly valuable" style="width:80px;"></td>';
                    echo '<td style="width: 100px;"><input name="vhclwc_flds[vwc_daily_rate][' . $wc_id . ']" value="' . $vhclwc_flds['vwc_daily_rate'][$wc_id] . '" type="text" class="intOnly valuable" style="width:80px;"></td>';
                    echo '<td style="width: 100px;"><input name="vhclwc_flds[vwc_monthly_rate][' . $wc_id . ']" value="' . $vhclwc_flds['vwc_monthly_rate'][$wc_id] . '" type="text" class="intOnly valuable" style="width:80px;"></td>';
                    echo '</tr>';
                 }
              }
           }
        }
        ?>


    </table>


    <?php echo '<div style="width: 65%; text-align:left;">' . $availability_errors . '</div>'; ?>
    <div id="submit_container">
        <hr /><br />
        <input type="hidden" name="vehicles[vhcl_status]" value="1" >   <!--  Setting Default Status As "Active"  -->
        <input type="submit" name="button2" class="collapse_btn" value="Submit" />
        <input type="button" class="collapse_btn reseter" name="button3" value="Reset" />
    </div>
    <?php echo form_close(); ?>
</div> 	<!--<div class="dv-top-content" >-->


<script type="text/javascript">

   $(document).ready(function () {

       change_cost_mode();

       $('.vhcl_ownership').change(function () {
           change_cost_mode();
       });

       function change_cost_mode()
       {
           var ownership = $('.vhcl_ownership:checked').val();

           if (ownership == 1) // Our's vehicle
           {
               $('.valuable').show();
           }
           else if (ownership == 2) // Other's vehicle
           {
               $('.valuable').val('');
               $('.valuable').hide();
           }
       }

   });
</script>