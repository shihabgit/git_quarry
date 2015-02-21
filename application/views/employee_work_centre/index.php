<div class="dv-top-content" align="center">
    <?php echo form_open("", array('id' => 'searchForm')); ?>

    <table width="70%" cellpadding="5" cellspacing="0" class="tbl_input">
        <tr>
            <td>
                <div class="title-box">
                    <div id="img-container">
                        <img src="images/search-user.png" width="35" height="35"/>
                    </div>
                    <div id="title-container">
                        <div class="title-alone"><?php echo $heading;?></div>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                
                <ul class="main_container" style="width: 100%;height: 300px;"> 
                    <li>
                        <div class="sec_container">
                            <p class="input-categories">Search Options</p>
                            <table class="sec_table">
                                <tr>
                                    <th>Employee Id : </th>
                                    <td>
                                        <input type="text" name="emp_id" value="<?php echo ifSet('emp_id') ?>" >
                                    </td>
                                    <th>Name : </th>
                                    <td>
                                        <input type="text" name="emp_name" value="<?php echo ifSet('emp_name') ?>" >
                                    </td>
                                </tr>
                                <tr>
                                    <th>Workcentre : </th>
                                    <td>
                                        <select name="ewp_fk_workcentres[]" multiple="multiple"  style="height:100px;">
                                            <?php echo get_options2($workcentres, ifSet('ewp_fk_workcentres')); ?>
                                        </select>
                                        <p class="help">Hold <b>Ctrl</b> Key to select multiple workcentres.</p>
                                    </td>
                                    <th>Category : </th>
                                    <td>
                                        <select name="emp_category[]" multiple="multiple"  style="height:100px;">
                                            <?php echo get_options2($emp_cats, ifSet('emp_category')); ?>
                                        </select>
                                        <p class="help">Hold <b>Ctrl</b> Key to select multiple categories.</p>
                                    </td>
                                </tr>
                             


                                <tr>
                                    <th>Status : </th>
                                    <td>

                                        <input type="radio" name="ewp_status" value="1" <?php echo ifSetRadio('ewp_status', 1, true) ?> />
                                        <span class="multy_options">Active</span>

                                        <input type="radio" name="ewp_status" value="2" <?php echo ifSetRadio('ewp_status', 2) ?> />
                                        <span class="multy_options">Inactive</span>

                                    </td>




                                    <th>Joined Date :</th>
                                    <td>
                                        <div class="dateContainer" style="padding: 0px;margin:0px;float: left;">
                                            <div style="margin-right: 2px;float: left;" >
                                                <input class="dateField inputDate" placeholder="From" readonly="" type="text" name="f_ewp_date" value="<?php echo ifSet('f_ewp_date') ?>" style="width:105px" id="f_ewp_date"  /> 
                                            </div>
                                            <div style="margin-bottom: 1px;float: left;">
                                                <img src="images/calendar.gif"  class="calendarButton"> 
                                            </div>
                                        </div>  

                                        <div class="dateContainer" style="padding: 0px;margin:0px;margin-left:2px; float: left;">
                                            <div style="margin-right: 2px;float: left;" >
                                                <input class="dateField inputDate" placeholder="To" readonly="" type="text" name="t_ewp_date" value="<?php echo ifSet('t_ewp_date') ?>" style="width:105px" id="t_ewp_date"  /> 
                                            </div>
                                            <div style="margin-bottom: 1px;float: left;">
                                                <img src="images/calendar.gif"  class="calendarButton"> 
                                            </div>
                                        </div>             

                                    </td>
                                </tr>


                                



                            </table>
                        </div>      <!--     End of Account Details-->
                    </li>


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

<!--            <a href="<?php echo site_url('index/create_group'); ?>" >
                <div class="right_action"  title="<?php echo lang('index_create_group_link') ?>">
                    <div class="settings_btn">
                        <img src="images/user_group.png" height="24" width="24" />
                    </div>
                    <div class="clear_boath"></div>
                </div>
            </a>-->

            <a href="<?php echo site_url('employee_work_centre/add'); ?>" >
                <div class="right_action"  title="<?php echo lang('index_create_user_link') ?>">
                    <div class="settings_btn">
                        <img src="images/user2.png" height="24" width="24" />
                    </div>
                    <div class="clear_boath"></div>
                </div>
            </a>

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
            <p class="tbl_grid_head" style="width:5%">Joined</p>
            <p class="tbl_grid_head" style="width:14%">Employee</p>
            <p class="tbl_grid_head" style="width:8%">OB</p>
            <p class="tbl_grid_head" style="width:21%">Day Wage</p>
            <p class="tbl_grid_head" style="width:21%">Night Wage</p>
            <p class="tbl_grid_head" style="width:5%">Salary</p>
            <p class="tbl_grid_head">Status</p>
            <p class="gridClearfix "></p>
        </div>


       <div style="" class="tbl_grid_body_container">
            <?php
            $colspan = 8;
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
                    //Before displaying workcentre name title, checing is there any employees under the workcentre.
                    $row_found = FALSE;
                    foreach ($table as $row)
                    {    if ($row['ewp_fk_workcentres'] == $wcntr_id)
                        {
                            $row_found = true;
                            break;
                        }
                    }
                    if(!$row_found)
                        continue;
                    
                    echo '<tr class="wlog_title">';
                    echo '<td colspan="' . $colspan . '">';
                    echo '<input type="checkbox" class="wcntr" id="wcntr_' . ++$wctr_count . '">';
                    echo $name;
                    echo '</td></tr>';
                    $slNo = 0;
                    
                    
                    foreach ($table as $row)
                    {
                        if ($row['ewp_fk_workcentres'] == $wcntr_id)
                        {
                            echo '<tr>';
                            echo '<td>';
                            echo '<input type="checkbox" value="' . $row['ewp_id'] . '" name="checkbox" class="gridSlNo wcntr_' . $wctr_count . '" />';
                            echo '<span class="spn_slNo">' . ++$slNo . '</span>';
                            echo '</td>';
                            echo '<td>' . formatDate($row['ewp_date'], false, 0) . '</td>';
                            
                            
                            echo '<td>';
                            
                            $content =  '<div class="wagedet">';
                            $content .=  '<div class="cat">' . $emp_cats[$row['emp_category']].'</div><div class="name">' ;
                            $content .=  $row['emp_name'];
                            
                            $content .=  '</div></div>';
                            echo $content;
                            
                            echo '</td>';
                            
                            
                            if($row['ewp_ob_mode']==1)$mode = ' Cr.';
                            else if($row['ewp_ob_mode']==2)$mode = ' Dr.';
                            else $mode = ' Unknown.';
                            if(!intval($row['ewp_ob']))$mode = ' &nbsp;&nbsp; &nbsp;&nbsp;';
                                
                            echo '<td align="right">' . $row['ewp_ob'].$mode. '</td>';
                            
                            
                            echo '<td align="center" style="padding:0px;">';
                            echo '<div class="wagedet"><div class="txt">Full</div><div class="val">'. $row['ewp_day_wage'].'</div></div>';
                            echo '<div class="wagedet"><div class="txt">Hrly</div><div class="val">'. $row['ewp_day_hourly_wage'].'</div></div>' ;
                            echo '<div class="wagedet"><div class="txt">OT</div><div class="val">'. $row['ewp_day_ot_wage'].'</div></div>' ;
                            echo '</td>';
                            
                            
                            echo '<td style="padding:0px;text-align:centre">';
                            echo '<div class="wagedet"><div class="txt">Full</div><div class="val">'. $row['ewp_night_wage'].'</div></div>';
                            echo '<div class="wagedet"><div class="txt">Hrly</div><div class="val">'. $row['ewp_night_hourly_wage'].'</div></div>' ;
                            echo '<div class="wagedet"><div class="txt">OT</div><div class="val">'. $row['ewp_night_ot_wage'].'</div></div>' ;
                            echo '</td>';
                            
                            
                            
                            echo '<td>' . $row['ewp_salary_wage'] . '</td>';
                            echo '<td>' . $status[$row['ewp_status']] . '</td>';
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
            <p class="tbl_grid_head" ></p>
            <p class="tbl_grid_head"></p>
            <p class="tbl_grid_head"></p>
            <p class="tbl_grid_head"></p>
            <p class="tbl_grid_head"></p><!-- Don't set width for at least one column (preferably the 'last column'). -->

            <p class="gridClearfix "></p>
        </div>
        <div class="dv_img_def">
            <ul class="img_defenission">
                <li><a><img src="images/edit.png" width="16" height="16" alt="Edit"><span>EDIT</span></a></li>
                <li><a><img src="images/delete.png" width="16" height="16" alt="Delete"><span>DELETE</span></a></li>
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
    });
</script>
<script type="text/javascript" src="js/table.js"></script> 




