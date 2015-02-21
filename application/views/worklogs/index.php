<?php $this->load->view('worklogs/pop_wlog_common'); ?>
<div class="dv-top-content" align="center">
    <p style="background-color:#FFF;color:#000;text-align: left;padding: 5px;">
        1.Suppose, worklog_1 and worklog_2 are done under verifier_1. After this, we created a new verifier known as verifier_2. Then now we created next worklog known as worklog_3. Then when verifier_2 open his verify account, he will see only worklog_3. The next is our problem,,,,<br>
        ...verifier_2 also want to get worklogs verified by verifier_1. In this situation worklog_1 and worklog_2 should not be shown in front of verifier_2. Because he is not involved in these worklogs. He can see worklogs only which he was participated, ie: worklog_3 only.<br><br>
        2. When adding worklog under a 'Workcentre' it will get only verifiers under that workcentre's only. (Please check it).<br>

        3. <font color="red"><b>Imortant.</b></font>The "General" worklogs added before a firm creation, should not be accessed by that firm.<br>
        
        4. When creating a traversor through wlogs, take care about the worklogs those may not have a popup. Eg:- The worklog created when labours (Drivers/Loaders) added to a vehicle wouldn't have a popup.<br>
    </p>
    <?php echo form_open("worklogs", array('id' => 'searchForm')); ?>
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


                <ul class="main_container" style="width: 50%;height: 240px;"> 

                    <li>

                        <div class="sec_container">
                            <p class="input-categories">My Account</p>
                            <table class="sec_table">

                                <tr>
                                    <th>WLog Status</th>
                                    <td>                                      


                                        <input type="radio" value="1" name="wlog_status" <?php echo ifSetRadio('wlog_status', '1', TRUE); ?> />
                                        <span class="multy_options">Live</span>

                                        <input type="radio" value="2" name="wlog_status" <?php echo ifSetRadio('wlog_status', '2'); ?> />
                                        <span class="multy_options">Dead</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Verify Status</th>
                                    <td>
                                        <input type="checkbox" value="1" name="my_verify_status[1]" <?php echo ifSetCheckboxGroupArray('my_verify_status', 1) ?>  />
                                        <span class="multy_options"><?php echo $status[1]; ?></span>

                                        <input type="checkbox" value="2" name="my_verify_status[2]" <?php echo ifSetCheckboxGroupArray('my_verify_status', 2, TRUE) ?>  />
                                        <span class="multy_options"><?php echo $status[2]; ?></span>

                                        <input type="checkbox" value="3" name="my_verify_status[3]" <?php echo ifSetCheckboxGroupArray('my_verify_status', 3) ?>  />
                                        <span class="multy_options"><?php echo $status[3]; ?></span>
                                    </td>
                                </tr>

                                <tr>
                                    <th>Worklog Date</th>
                                    <td>

                                        <div class="dateContainer" style="padding: 0px;margin:0px;float: left;">
                                            <div style="margin-right: 2px;float: left;" >
                                                <input class="dateField inputDate" placeholder="From" readonly="" type="text" name="wlog_from" value="<?php echo ifSet('wlog_from') ?>" style="width:105px" id="f_emp_date"  /> 
                                            </div>
                                            <div style="margin-bottom: 1px;float: left;">
                                                <img src="images/calendar.gif"  class="calendarButton"> 
                                            </div>
                                        </div>  

                                        <div class="dateContainer" style="padding: 0px;margin:0px;margin-left:2px; float: left;">
                                            <div style="margin-right: 2px;float: left;" >
                                                <input class="dateField inputDate" placeholder="To" readonly="" type="text" name="wlog_to" value="<?php echo ifSet('wlog_to') ?>" style="width:105px" id="t_emp_date"  /> 
                                            </div>
                                            <div style="margin-bottom: 1px;float: left;">
                                                <img src="images/calendar.gif"  class="calendarButton"> 
                                            </div>
                                        </div>     

                                    </td>
                                </tr>

                                <tr> 
                                    <th>Status Date</th>
                                    <td>

                                        <div class="dateContainer" style="padding: 0px;margin:0px;float: left;">
                                            <div style="margin-right: 2px;float: left;" >
                                                <input class="dateField inputDate" placeholder="From" readonly="" type="text" name="my_verify_from" value="<?php echo ifSet('my_verify_from') ?>" style="width:105px" id="f_emp_date"  /> 
                                            </div>
                                            <div style="margin-bottom: 1px;float: left;">
                                                <img src="images/calendar.gif"  class="calendarButton"> 
                                            </div>
                                        </div>  

                                        <div class="dateContainer" style="padding: 0px;margin:0px;margin-left:2px; float: left;">
                                            <div style="margin-right: 2px;float: left;" >
                                                <input class="dateField inputDate" placeholder="To" readonly="" type="text" name="my_verify_to" value="<?php echo ifSet('my_verify_to') ?>" style="width:105px" id="t_emp_date"  /> 
                                            </div>
                                            <div style="margin-bottom: 1px;float: left;">
                                                <img src="images/calendar.gif"  class="calendarButton"> 
                                            </div>
                                        </div>     

                                    </td>
                                </tr>

<!--                                <tr style="height: 33px;">
                                    <th></th>
                                    <td></td>
                                </tr>-->

                            </table>
                        </div>          
                    </li>       <!--     End of My account -->



                    <li>

                        <div class="sec_container">
                            <p class="input-categories">Other Verifier's Account</p>
                            <table class="sec_table">



                                <tr>
                                    <th>Verifier</th>
                                    <td>
                                        <select name="other_verifiers_id">
                                            <?php echo get_options2($verifiers, ifSet('other_verifiers_id'), true, '--- Select ---'); ?>
                                        </select>

                                    </td>
                                </tr>

                                <tr>
                                    <th>Verify Status</th>
                                    <td>
                                        <input type="checkbox" name="other_verify_status[1]" <?php echo ifSetCheckboxGroupArray('other_verify_status', 1) ?>  />
                                        <span class="multy_options"><?php echo $status[1]; ?></span>

                                        <input type="checkbox" name="other_verify_status[2]" <?php echo ifSetCheckboxGroupArray('other_verify_status', 2, TRUE) ?>  />
                                        <span class="multy_options"><?php echo $status[2]; ?></span>

                                        <input type="checkbox" name="other_verify_status[3]" <?php echo ifSetCheckboxGroupArray('other_verify_status', 3, TRUE) ?>  />
                                        <span class="multy_options"><?php echo $status[3]; ?></span>
                                    </td>
                                </tr>




                                <tr> 
                                    <th>Status Date</th>
                                    <td>

                                        <div class="dateContainer" style="padding: 0px;margin:0px;float: left;">
                                            <div style="margin-right: 2px;float: left;" >
                                                <input class="dateField inputDate" placeholder="From" readonly="" type="text" name="other_verify_from" value="<?php echo ifSet('other_verify_from') ?>" style="width:105px" id="f_emp_date"  /> 
                                            </div>
                                            <div style="margin-bottom: 1px;float: left;">
                                                <img src="images/calendar.gif"  class="calendarButton"> 
                                            </div>
                                        </div>  

                                        <div class="dateContainer" style="padding: 0px;margin:0px;margin-left:2px; float: left;">
                                            <div style="margin-right: 2px;float: left;" >
                                                <input class="dateField inputDate" placeholder="To" readonly="" type="text" name="other_verify_to" value="<?php echo ifSet('other_verify_to') ?>" style="width:105px" id="t_emp_date"  /> 
                                            </div>
                                            <div style="margin-bottom: 1px;float: left;">
                                                <img src="images/calendar.gif"  class="calendarButton"> 
                                            </div>
                                        </div>     

                                    </td>
                                </tr>



                            </table>
                        </div>          
                    </li>   <!--  Other Verifier's Account -->









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



        </div>  <!--<div id="right_pan">-->


        <div id="left_pan">

            <div class="collapse_container right_action">
                <div class="settings_btn">
                    <img  src="images/verify1.png" title="Verify all" width="21" height="21">
                </div>
                <div class="settings p-collaps ">Verify</div>
                <div class="dv-collaps left_action_content" style="width:250px;">
                    <div style="margin:20px 5px;text-align: left;"> 
                        <input type="checkbox" class="verify_all" checked=""> Do the action for all same worklogs in other workcentres.
                    </div>
                    <div style="margin:20px 5px;text-align: center;">
                        <input type="button" value="DO" class="collapse_btn verify_actions" id="1" />
                    </div>   
                </div>  
            </div> 
            <div class="collapse_container right_action">
                <div class="settings_btn">
                    <img  src="images/verify2.png" title="Mark all" width="21" height="21">
                </div>
                <div class="settings p-collaps ">Mark</div>
                <div class="dv-collaps left_action_content" style="width:250px;">
                    <div style="margin:20px 5px;text-align: left;"> 
                        <input type="checkbox" class="verify_all" checked=""> Do the action for all same worklogs in other workcentres.
                    </div>
                    <div style="margin:20px 5px;text-align: center;">
                        <input type="button" value="DO" class="collapse_btn verify_actions" id="3" />
                    </div>   
                </div>  
            </div> 

            <?php
            // Only awailable for Developer
            if ($this->environment == 'Development')
            {
                ?>
                <div class="collapse_container right_action" id="delete_all">
                    <div class="settings_btn"><img  src="images/delete4.png" title="Delete All" width="21" height="21"></div>
                    <div class="settings">Delete All</div>

                    <div class="clear_boath"></div>
                </div>
            <?php } ?>
        </div>		<!--<div id="left_pan">-->

        <div id="middle_pan">
            <h3>SEARCH RESULTS</h3>
        </div>

    </div>
    <div class="search-header">
        <div id="right_pan">




        </div>

        <!--        <div class="add_action">
        
                </div>-->

        <p class="pagin_data">
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

    <div class="datagrid" style="width: 100%;">
        <div class="tbl_grid_head_container">
            <!--  
            1. Don't set width for at least one 'tbl_grid_head' column (preferably the last one).
            2. The sum of the total width of 'tbl_grid_head' must be some less than that of the "datagrid" as it can include the last 'tbl_grid_head'.
            3. If the sum of the total width of 'tbl_grid_head' exceeds the width of "datagrid", the last 'tbl_grid_head' will be pushed out.
               So if this case occurred, you just decrease the with of any other 'tbl_grid_head' or increase the width of "datagrid"
                   
            -->


            <p class="tbl_grid_head" style="width:5%"><input type="checkbox" class="checkUncheckAll"  />Sl No</p>
            <p class="tbl_grid_head" style="width:10%">Created On</p> 
            <p class="tbl_grid_head" style="width:10%">User</p>
            <p class="tbl_grid_head" style="width:40%">Action</p>
    <!--            <p class="tbl_grid_head" style="width:6%">Status</p>-->
            <p class="tbl_grid_head" style="width:10%">Anybody Verified</p>
            <p class="tbl_grid_head">Status Date</p>
            <p class="gridClearfix "></p>
        </div>


        <div style="" class="tbl_grid_body_container">

            <?php
            $colspan = 6;
            $wctr_count = 0; // used for script below;

            if (!$table)
                echo '<table class="tbl_grid no-data-to-display"><tbody><tr><td colspan="' . $colspan . '">No Data To Display.</td></tr></tbody></table>';
            else
            {
                echo '<table id="" class="tbl_grid">';
                echo '<tbody>';


                // For js/table.js
                echo '<tr>';
                for ($i = 1; $i <= $colspan; $i++)
                    echo '<td></td>';echo '</tr>';


                // Aranging 'General' things on top. 

                foreach ($workcentres as $wcntr_id => $name)
                {
                    echo '<tr class="wlog_title">';
                    echo '<td colspan="' . $colspan . '">';
                    echo '<input type="checkbox" class="wcntr" id="wcntr_' . ++$wctr_count . '">';
                    echo $name;
                    echo '</td></tr>';
                    $slNo = 0;
                    foreach ($table as $row)
                    {
                        if ($row['wcntr_id'] == $wcntr_id)
                        {


                            if ($row['verify_status'] == 1) //Verified
                                $img = '<img  src="images/verify1.png" title="Verified" width="16" height="16">';
                            if ($row['verify_status'] == 2) // Non Verified
                                $img = '<img  src="images/error.png" title="Non-verified" width="16" height="16">';
                            if ($row['verify_status'] == 3) //Marked
                                $img = '<img  src="images/verify2.png" title="Marked" width="16" height="16">';
                            $img = '<div class="vfy_img">' . $img . '</div>';

                            echo '<tr>';
                            echo '<td>' . $img;
                            echo '<input type="checkbox" value="' . $row['verify_id'] . '" name="checkbox" class="gridSlNo wcntr_' . $wctr_count . '" />';
                            echo '<input type="hidden" class="wlog_ref_id" value="' . $row['wlog_ref_id'] . '">';
                            echo '<input type="hidden" class="wlog_ref_table" value="' . $row['wlog_ref_table'] . '">';
                            echo '<input type="hidden" class="wlog_wc_fk_workcentres" value="' . $wcntr_id . '">';

                            echo '<span class="spn_slNo">' . ++$slNo . '</span>';
                            echo '</td>';

                            echo '<td>' . formatDate($row['wlog_created'], false, 0, true) . '</td>';
                            $user = ($this->user_id == $row['wlog_fk_auth_users']) ? 'Me' : $employees[$row['wlog_fk_auth_users']];

                            if ($row['wlog_status'] == 1) // If worklog is active
                            {
                                $link['go'] = $row['wlog_ref_url'] ? anchor("$row[wlog_ref_url]/wlogs/$row[wlog_ref_id]", "Go", array('target' => '_blank')) : '';
                                $link['show'] = $row['wlog_popup_id'] ? ' <span class="' . $row['wlog_popup_id'] . '_loader">Show</span>' : '';
                                $links = $link ? ' ' . implode(' | ', array_filter($link)) . ' ' : '';
                            }
                            else
                            {
                                $links = ' <font color="red">Dead</font>'; // Inactive
                            }
                            echo '<td>' . $user . $links . '</td>';

                            $wlog_warnings = $row['wlog_warnings'] == 1 ? 'wlog_warnings' : '';
                            echo '<td class="wlog_wc_message ' . $wlog_warnings . '">' . $row['wlog_wc_message'] . '</td>';
//                            echo '<td>' . $status[$row['verify_status']] . '</td>';
                            echo '<td>Not Done</td>';
                            echo '<td>' . formatDate($row['verify_datime'], false, 0, true) . '</td>';
                            echo '</tr>';
                        }
                    }
                }

                echo '</tbody>';
                echo '</table>';
            }
            ?>


        </div>	<!--<div class="tbl_grid_body_container">-->


<!-- The width of <p class="tbl_grid_head"> will be reseted by jquery if <div class="tbl_grid_footer_container> has the class 'auto_set_width'. -->    
        <div class="tbl_grid_footer_container auto_set_width">  
            <p class="tbl_grid_head" ></p> 
            <p class="tbl_grid_head" ></p>
            <p class="tbl_grid_head"></p>
            <p class="tbl_grid_head"></p>
            <p class="tbl_grid_head"></p>
            <p class="tbl_grid_head"></p><!-- Don't set width for at least one column (preferably the 'last column'). -->

            <p class="gridClearfix "></p>
        </div>
        <div class="dv_img_def">
            <ul class="img_defenission">                
                <li><a><img  src="images/verify1.png" width="16" height="16"><span>VERIFIED</span></a></li>
                <li><a><img  src="images/error.png" width="16" height="16"><span>NON VERIFIED</span></a></li>
                <li><a><img  src="images/verify2.png" width="16" height="16"><span>MARKED</span></a></li>
            </ul>
        </div>
    </div>	<!--<div class="datagrid">-->


</div>   <!--<div class="dv-bottom-content"> -->

<script type="text/javascript">



    $(document).ready(function() {
        $('.tbl_grid tr.wlog_title .wcntr').change(function() {
            var group = $(this).prop('id');
            $('.tbl_grid tbody .' + group).prop('checked', $(this).prop('checked'));
        });

        function init_pop_wlog(pop) {

            $(pop).find('.responseMessage').html('');


            //Making the popup box draggable.
            jQuery(pop).draggable();
            dragUndrag(jQuery(pop));

            // Initializing Popup-Window settings
            popupSettings(jQuery(pop));
        }

        var ajxImg = '<img src="images/ajax-loader3.gif"> ';

        // Opening common popup box of worklogs.
        $(".pop_wlog_common_loader").click(function(e) {

            var verify_id = $(this).closest('tr').find('.gridSlNo').val();
            var ref_id = $(this).closest('tr').find('.wlog_ref_id').val();
            var ref_table = $(this).closest('tr').find('.wlog_ref_table').val();
            var wcntr_id = $(this).closest('tr').find('.wlog_wc_fk_workcentres').val();

            $("#pop_wlog_common .wlogData").html('<div align="center">' + ajxImg + ajxImg + '</div>');
            $('#pop_wlog_common #p_key').val(verify_id);
            $('#pop_wlog_common .namespan_box .wlogtitle').html($(this).closest('tr').find('.wlog_wc_message').html());

            //Initializing popup box  
            init_pop_wlog('#pop_wlog_common');

            //Loading popupBox.
            loadPopup('pop_wlog_common');

            //Getting contents of worklog.
            var inputs = {ref_id: ref_id, ref_table: ref_table, wcntr_id: wcntr_id}; // eg: {parent_id: parent_id, status: 1}

            $.post(site_url + ref_table + "/get_wlog", inputs, function(result) {
                if (result)
                {
                    $("#pop_wlog_common .wlogData").html(result);
                    
//                    var popwidth = $("#pop_wlog_common .wlogData>table").width();
//                    $("#pop_wlog_common").width(popwidth+50);
                    
                } 
                jQuery('#pop_wlog_common').center();
            });

            // Preventing the table row becoming selected/not-selected when we click on pupup load button/link.
            e.preventDefault();
            return false;
        });

        $('.popupBox .save').click(function() {
            var id = $(this).closest('.popupBox').find('#p_key').val();
            var status = $(this).attr('id');
            var do_all = $(this).closest('.popupBox').find('#pop_do_all').prop('checked');

            //Setting input.
            var inputs = {verify_id: id, verify_status: status, do_all: do_all}; // eg: {parent_id: parent_id, status: 1}

            $.post(site_url + "worklogs/changeStatus", inputs, function(result) {
                // Refresh browser and clearing cache.
                location.reload(true);
            });
        });

        $('.search-header #delete_all').click(function() {

            if (!confirm('Do you want to delete all worklogs ?'))
                return;

            $.post(site_url + "worklogs/truncate_wlogs", '', function(result) {
                // Refresh browser  and clearing cache.
                location.reload(true);
            });
        });

        $('.search-header .verify_actions').click(function() {

            var do_all = $(this).closest('.left_action_content').find('.verify_all').prop('checked');
            var status = $(this).attr('id');

            // Setting variable to hold selected worklogs.
            var selected_wlogs = [];

            // Assigning each selected worklogs id to 'selected_wlogs'.
            $('.tbl_grid .gridSlNo').each(function() {
                if ($(this).prop('checked'))
                    selected_wlogs.push($(this).val());
            });

            //If no worklogs selected.
            if (!selected_wlogs.length)
            {
                alert("No worklogs selected yet !!!");
                return;
            }

            var msg = (status == 1) ? "verify" : "mark";
            if (!confirm('Do you want to ' + msg + ' the selected worklogs ?'))
                return;

            //Setting input.
            var inputs = {verify_ids: selected_wlogs, verify_status: status, do_all: do_all}; // eg: {parent_id: parent_id, status: 1}

            // Disabling whole page background till Ajax respond.
            $.blockUI(); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"

            $.post(site_url + "worklogs/groupChange", inputs, function(result) {

                // enabling the whole page after ajax response.
                $(document).ajaxStop($.unblockUI); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"

                // Refreshing the browser and clearing cache.
                location.reload(true);
            });
        });

    });
</script>



<script type="text/javascript" src="plugins/blockui-master/jquery.blockUI.js"></script> 
<script type="text/javascript" src="js/table.js"></script> 


<script type="text/javascript">
    /*  To resolve jquery conflict when using "$(document).on()" function in "js/table_dynamic.js" because of the usage of both 
     jquery1.11.0.js and js/jquery.min.js libraries, use  $.noConflict();    
     Other wise it will show error as follows;
     TypeError: $(...).on is not a function
     
     */

    //$.noConflict();
</script>
<!--<script type="text/javascript" src="js/table_dynamic.js"></script>--> 


