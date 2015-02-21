<?php $this->load->view('item_category/pop_add'); ?>
<?php $this->load->view('item_heads/pop_add'); ?>

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

            <div class="right_action"  title="Add new item category" id="add_category">
                <div class="settings_btn">
                    <img src="images/Add button.png" height="24" width="24" />
                </div>
                <div class="clear_boath"></div>
            </div>

            <div class="right_action"  title="Add new item head" id="add_head">
                <div class="settings_btn">
                    <img src="images/Add button.png" height="24" width="24" />
                </div>
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

    </div>  <!--<div class="search-header">-->


    <div class="datagrid" style="width: 70%;">
        <div class="tbl_grid_head_container">
            <!--  
            1. Don't set width for at least one 'tbl_grid_head' column (preferably the last one).
            2. The sum of the total width of 'tbl_grid_head' must be some less than that of the "datagrid" as it can include the last 'tbl_grid_head'.
            3. If the sum of the total width of 'tbl_grid_head' exceeds the width of "datagrid", the last 'tbl_grid_head' will be pushed out.
               So if this case occurred, you just decrease the with of any other 'tbl_grid_head' or increase the width of "datagrid"
                   
            -->
            <p class="tbl_grid_head" style="width:10%"><input type="checkbox" class="checkUncheckAll"  />Sl No</p>
            <p class="tbl_grid_head" style="width:35%">Category</p> 
            <p class="tbl_grid_head">Head</p>
            <p class="gridClearfix "></p>
        </div>


        <div class="tbl_grid_body_container">

            <?php
            $slNo = 0;

            if (!$table)
                echo '<table class="tbl_grid no-data-to-display"><tbody><tr><td colspan="5">No Data To Display.</td></tr></tbody></table>';
            else
            {
                echo '<table id="" class="tbl_grid">';
                echo '<tbody>';


                foreach ($table as $row)
                {
                    echo '<tr>';
                    echo '<td>';
                    echo '<input type="checkbox" value="' . $row['itmhd_id'] . '" name="checkbox" class="gridSlNo" />';
                    echo '<span class="spn_slNo">' . ++$slNo . '</span>';
                    echo '</td>';

                    $cat_status = ($row['itmcat_status'] == 2 ) ? ' Activate' : ' Deactivate';

                    echo '<td>' . $row['itmcat_name'] . anchor("item_category/toggle_status/$row[itmcat_id]",$cat_status) . '</td>';
                    
                     $hd_status = ($row['itmhd_status'] == 2 ) ? ' Activate' : ' Deactivate';

                    echo '<td>' . $row['itmhd_name'] . anchor("item_heads/toggle_status/$row[itmhd_id]",$hd_status) . '</td>';

                    echo '</tr>';
                }

                echo '</tbody>';
                echo '</table>';
            }
            ?>


        </div>	<!--<div class="tbl_grid_body_container">-->


    </div>  	<!--<div class="datagrid">-->


</div><!--<div class="dv-bottom-content"> -->

<script type="text/javascript">
    // This variable is used in 'item_category/pop_add.php' and in 'item_heads/pop_add.php' to determine from where the popup is loaded. Because the same popups is loading from 'items/add' also.
    var clsfunc = 'item_heads/index';
    
    $('.search-header #add_category').click(function() {

        //Initializing popup box  
        init_pop_category_add();

        // Removing if is there any value in primary key
        $('#pop_category_add #p_key').val('');

        //Loading popupBox.
        loadPopup('pop_category_add');
    });

    $('.search-header #add_head').click(function() {

        //Initializing popup box  
        init_pop_head_add();

        // Removing if is there any value in primary key
        $('#pop_head_add #p_key').val('');

        //Loading popupBox.
        loadPopup('pop_head_add');
    });
</script>
<script type="text/javascript" src="js/table.js"></script> 