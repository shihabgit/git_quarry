
<?php $this->load->view('workcentre_registration_details/pop_edit') ?>
<div class="dv-top-content" align="center">

    <?php echo form_open("workcentres", array('id' => 'searchForm')); ?>
    <table width="80%" cellpadding="5" cellspacing="0" class="tbl_input">
        <tr>
            <td>

                <div class="title-box">
                    <div id="img-container">
                        <img src="images/add_workcentre.png" width="35" height="35"/>
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

                <ul class="main_container" style="width: 100%;height: 240px;"> 

                    <li>

                        <div class="sec_container">
                            <p class="input-categories">Workcentres</p>
                            <table class="sec_table">


                                <tr>
                                    <th>Reg. Name:</th>
                                    <td>
                                        <select name="wcntr_fk_workcentre_registration_details">
                                            <?php echo get_options2($reg_names, ifSet('wcntr_fk_workcentre_registration_details')); ?>
                                        </select>
                                    </td>

                                    <th>Ownership : </th>
                                    <td>

                                        <input type="radio" name="wcntr_ownership" value="1" <?php echo ifSetRadio('wcntr_ownership', 1) ?> />
                                        <span class="multy_options">Owned</span>

                                        <input type="radio" name="wcntr_ownership" value="2" <?php echo ifSetRadio('wcntr_ownership', 2) ?> />
                                        <span class="multy_options">Rental</span>

                                        <input type="radio" name="wcntr_ownership" value="0" <?php echo ifSetRadio('wcntr_ownership', 0, true) ?> />
                                        <span class="multy_options">All</span>
                                    </td>
                                </tr>


                                <tr>
                                    <th>Workcentre Name:</th>
                                    <td>
                                        <input type="text" name="wcntr_name" value="<?php echo ifSet('wcntr_name') ?>" >
                                    </td>

                                    <th>Billing Name:</th>
                                    <td>
                                        <input type="text" name="wcntr_bill_name" value="<?php echo ifSet('wcntr_bill_name') ?>" >
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status : </th>
                                    <td>

                                        <input type="radio" name="wcntr_status" value="1" <?php echo ifSetRadio('wcntr_status', 1, true) ?> />
                                        <span class="multy_options">Active</span>

                                        <input type="radio" name="wcntr_status" value="2" <?php echo ifSetRadio('wcntr_status', 2) ?> />
                                        <span class="multy_options">Inactive</span>
                                    </td>



                                    <th>Date :</th>
                                    <td>
                                        <div class="dateContainer" style="padding: 0px;margin:0px;float: left;">
                                            <div style="margin-right: 2px;float: left;" >
                                                <input class="dateField inputDate" placeholder="From" readonly="" type="text" name="wcntr_date_f" value="<?php echo ifSet('wcntr_date_f') ?>" style="width:105px" id="wcntr_date_f"  /> 
                                            </div>
                                            <div style="margin-bottom: 1px;float: left;">
                                                <img src="images/calendar.gif"  class="calendarButton"> 
                                            </div>
                                        </div>  

                                        <div class="dateContainer" style="padding: 0px;margin:0px;margin-left:2px; float: left;">
                                            <div style="margin-right: 2px;float: left;" >
                                                <input class="dateField inputDate" placeholder="To" readonly="" type="text" name="wcntr_date_t" value="<?php echo ifSet('wcntr_date_t') ?>" style="width:105px" id="wcntr_date_t"  /> 
                                            </div>
                                            <div style="margin-bottom: 1px;float: left;">
                                                <img src="images/calendar.gif"  class="calendarButton"> 
                                            </div>
                                        </div>             

                                    </td>
                                </tr>
                            </table>
                        </div>          
                    </li>       <!--     End of Workcentres -->



                </ul> <!--     End of <div class="main_container">-->
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
</div> 	<!--<div class="dv-top-content" >-->



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



            <a href="<?php echo site_url('workcentres/add'); ?>" >
                <div class="right_action"  title="<?php echo lang('index_create_user_link') ?>">
                    <div class="settings_btn">
                        <img width="24" height="24" src="images/Add button.png">
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


    <div class="dv_patient_search_result_container">
        <?php
        $page_no = $offset ? $offset : 0;
        if (!$table)
            echo '<table align="center"  width="100%" cellpadding="0" cellspacing="0" style="background-color:#FFF;"><tr><th style="width:100%;color:red">NO DATA TO DISPLAY</th></tr></table>';
        else
        {
            $slNo = $page_no + 1;
            $col = 0;
            $max_cols = 4;
            $tr_tag = '';
            echo '<table cellpadding="0" cellspacing="0" id="tbl_search_results">';


            foreach ($table as $row)
            {
                ++$col;
                if ($col == 1)
                {
                    $tr_tag = '<tr>';
                    echo $tr_tag;
                }

                echo '<td class="house" style="color:green">';

                echo '<div class="tools">';
                echo '<div class="dv_tools_left">';
                echo '<input type="checkbox" class="checkID"  value="' . $row['wcntr_id'] . '" >&ensp;<span class="sl_no">' . $slNo . '</span>';
                echo '</div>';
                echo '<div class="dv_tools_right">';

                echo '<input type="hidden" class="p_key" value="' . $row['wcntr_id'] . '">';
                echo '<input type="hidden" class="name_data" value="' . $row['wcntr_name'] . '">';


                echo '<p class="tools_case">';
                echo anchor("workcentres/before_edit/$row[wcntr_id]", '<img  src="images/edit.png" title="Edit" class="toolbar_imgs">') . '&nbsp;';
                echo '</p>';

                echo '<p class="tools_case" id="activator" style="margin-top:3px;">';
                echo '<input type="hidden" id="wcntr_status" value="' . $row['wcntr_status'] . '">';
                if ($row['wcntr_status'] == 1)//Active
                    echo '<img  src="images/delete2.png" title="Deactivate" width="17" height="17">&nbsp;';
                else
                    echo '<img  src="images/activate.png" title="Activate" width="17" height="17">&nbsp;';
                echo '</p>';
//
                echo '</div>';
                echo '</div>';


                // To identify that a subscriber is verified or not after transfer from old software to new.
                $verification = ''; //($row['SBR_VERIFICATION'] == 2) ? 'nonVerified' : '';

                echo '<div class="patient_info no_sel ' . $verification . '" >';


                echo '<span class="nameSpan">';
                echo $row['wcntr_name'];
                echo '&nbsp;&nbsp;<font color="#CC00FF">[Id: ' . $row['wcntr_id'] . ']</font>';
                echo '</span>';
                if ($row['wcntr_ownership'] == 1) //Owned
                    echo '<span class="admin_color empcat">Owned</span>';
                else if ($row['wcntr_ownership'] == 2) // Rental
                    echo '<span class="partner_color empcat">Rental</span>';

                if ($row['wcntr_status'] == 2) //Inactive
                    echo ' <span class="inactive_color empcat"> Inactive</span>';


                if ($row['wrd_status'] == 1)
                {
                    $class = 'active';
                    $img = 'images/delete5.png';
                    $tooltip = "Deactivate Reg.Name";
                    $update = '<img id="edit_reg" title="Edit Reg.Name" src="images/edit12.png"/>';
                }
                else
                {
                    $class = 'inactive';
                    $img = 'images/activate3.png';
                    $tooltip = "Activate Reg.Name";
                    $update = '';
                }

                if ($row['wrd_name'])
                {
                    echo '<br><span class="' . $class . '">Reg.Name : ' . $row['wrd_name'] . '</span> &nbsp;';
                    echo '<input type="hidden" id="wrd_id" value="' . $row['wrd_id'] . '">';
                    echo '<input type="hidden" id="wrd_name" value="' . $row['wrd_name'] . '">';
                    echo '<input type="hidden" id="wrd_status" value="' . $row['wrd_status'] . '">';
                    echo $update;
                    echo ' <img id="toggle_status" title="' . $tooltip . '" src="' . $img . '"/>';
                }


                if ($this->is_admin)
                {
                    echo '<br><b>Firm : ' . $row['firm_name'] . '</b>';
                }

                echo '<br>Created On : ' . formatDate($row['wcntr_date'], false);


                if ($this->is_admin || $this->is_partner)
                {
                    echo '<br>Capital : ' . $row['wcntr_capital'];
                }


                if ($wlog[$row['wcntr_id']]['user'])
                    echo '<br><font color="blue">Last Change by <b>' . $wlog[$row['wcntr_id']]['user'] . '</b> On ' . formatDate($wlog[$row['wcntr_id']]['wlog_created'], false) . '</font>';
                echo '</div> ';






                echo '<div class="collapse_container">';
                echo '<p class="p-collaps details-head round_boarder">Details</p> ';
                echo '<div class="dv-collaps patient_deatails">';

                echo '<table class="dt_tbl" style="width:100%">';
                if ($row['wrd_tin'])
                    echo '<tr><th>Tin No : </th><td>' . $row['wrd_tin'] . '</td></tr>';
                if ($row['wrd_licence'])
                    echo '<tr><th>Licence No : </th><td>' . $row['wrd_licence'] . '</td></tr>';
                if ($row['wrd_cst'])
                    echo '<tr><th>CST : </th><td>' . $row['wrd_cst'] . '</td></tr>';


                if ($row['rntdt_id'])
                {
                    echo '<tr><th colspan="2" class="wage-bord">Rental Details</th></tr>';
                    echo '<tr><th>Owner : </th><td>' . $row['ownr_name'] . '</td></tr>';
                    echo '<tr><th>Date : </th><td>' . formatDate($row['rntdt_date'], false, 1) . '</td></tr>';
                    echo '<tr><th>Advance : </th><td>' . $row['rntdt_advance'] . '</td></tr>';
                    $mode = ($row['rntdt_ob_mode'] == 1) ? ' Cr' : ' Dr';
                    if (intval($row['rntdt_ob']) == 0)
                        $mode = '';
                    echo '<tr><th>O.B : </th><td>' . $row['rntdt_ob'] . $mode . '</td></tr>';
                    echo '<tr><th>Installment : </th><td>' . $row['rntdt_instalment_amount'] . ' ' . $insallments[$row['rntdt_instalment_period']] . '</td></tr>';
                    $autoAdd = $row['rntdt_auto_add'] == 1 ? 'Yes' : 'No';
                    echo '<tr><th>Auto Add : </th><td>' . $autoAdd . '</td></tr>';
                    echo '<tr><th>Rent Start : </th><td>' . formatDate($row['rntdt_start_from'], false, 1) . '</td></tr>';
                }

                echo '</table>';


                echo '</div> ';
                echo '</div> ';


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

            echo '</table>';
        }
        ?>


    </div>	<!--<div class="dv_patient_search_result_container">-->


</div>   <!--<div class="dv-bottom-content"> -->

<script type="text/javascript">
    $('.tools #activator').click(function() {
        var status = $(this).find('#wcntr_status').val();
        var ask = '';
        if (status == 1)
            ask = "Are you sure to deactivate the workcentre ?";
        else
            ask = "Are you sure to activate the workcentre ?";
        var confirmed = confirm(ask);
        if (!confirmed)
            return;
        var wrkcntr_id = $(this).closest('.tools').find('.p_key').val();
        window.location.replace(site_url + 'workcentres/toggleStatus/' + wrkcntr_id);
    });

    $(document).ready(function() {

        // Edit Destination
        $('.patient_info #edit_reg').click(function() {

            var p_key = $(this).closest('.patient_info').find('#wrd_id').val();
            var name = $(this).closest('.patient_info').find('#wrd_name').val();

            //Initializing popup box.
            init_pop_workcentre_registration_details_edit();

            $('#pop_workcentre_registration_details_edit #p_key').val(p_key);
            $('#pop_workcentre_registration_details_edit .namespan_box .namespan').text(name);

            $.getJSON(site_url + 'workcentre_registration_details/beforeEdit', {wrd_id: p_key}, function(data) {

                $('#pop_workcentre_registration_details_edit #wrd_date').val(data['wrd_date']);
                $('#pop_workcentre_registration_details_edit #wrd_name').val(data['wrd_name']);
                $('#pop_workcentre_registration_details_edit #wrd_address').val(data['wrd_address']);
                $('#pop_workcentre_registration_details_edit #wrd_phone').val(data['wrd_phone']);
                $('#pop_workcentre_registration_details_edit #wrd_email').val(data['wrd_email']);
                $('#pop_workcentre_registration_details_edit #wrd_tin').val(data['wrd_tin']);
                $('#pop_workcentre_registration_details_edit #wrd_licence').val(data['wrd_licence']);
                $('#pop_workcentre_registration_details_edit #wrd_cst').val(data['wrd_cst']);

                //Loading popupBox.
                loadPopup('pop_workcentre_registration_details_edit');
            });

        });


        // Toggling reg.name status
        $('.patient_info #toggle_status').click(function() {

            var p_key = $(this).closest('.patient_info').find('#wrd_id').val();
            var name = $(this).closest('.patient_info').find('#wrd_name').val();

            var status = $(this).closest('.patient_info').find('#wrd_status').val();
            name.replace('"', '\"');
            name.replace("'", "\'");

            var msg = (status == 1) ? " deactivate " : " activate ";
            msg += "the Reg.Name '" + name + "' ?";

            if (!confirm('Do you want to ' + msg))
                return;

            //Setting input.
            var inputs = {wrd_id: p_key}; // eg: {parent_id: parent_id, status: 1}

            // Disabling whole page background till Ajax respond.
            $.blockUI(); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"

            $.post(site_url + "workcentre_registration_details/toggleStatus", inputs, function(result) {

                alert(result);

                // enabling the whole page after ajax response.
                $(document).ajaxStop($.unblockUI); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"


                // Refreshing the browser after 1 second
                setTimeout(function() {
                    location.reload();
                }, 500);

            });
        });




    });


</script>


<script type="text/javascript" src="plugins/blockui-master/jquery.blockUI.js"></script> 
