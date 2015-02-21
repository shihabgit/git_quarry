
<div class="dv-top-content" align="center">
    <?php echo form_open("employees", array('id' => 'searchForm')); ?>

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
                <p style="background-color:#FFF;color:#000;text-align: left;padding: 5px;">
                    
                    1. When changing emp_category of drivers or loaders those are the labours of any of the vehicle, they should be deleted from Tbl: vehicles_employees.<br>
                    2. When deactivate an employee in Tbl: employees or Tbl: employees_work_centres and the employee is a labour (driver or loader) in any of the vehicle, he must be deleted from that vehicles labours list (Tbl: vehicles_employees)<br>
                    
                    3. Create option to see the username of employees for staffs also if needed.
                </p>
                <ul class="main_container" style="width: 100%;height: 300px;"> 
                    <li>
                        <div class="sec_container">
                            <p class="input-categories">Search Options</p>
                            <table class="sec_table">

                                <tr>
                                    <th>Id : </th>
                                    <td><input type="text" name="id" value="<?php echo ifSet('id') ?>">  </td>
                                    <th>Name : </th>
                                    <td><input type="text" name="first_name" value="<?php echo ifSet('first_name') ?>">  </td>
                                </tr>


                                <tr>
                                    <th>Status : </th>
                                    <td>

                                        <input type="radio" name="emp_status" value="1" <?php echo ifSetRadio('emp_status', 1, true) ?> />
                                        <span class="multy_options">Active</span>

                                        <input type="radio" name="emp_status" value="2" <?php echo ifSetRadio('emp_status', 2) ?> />
                                        <span class="multy_options">Inactive</span>

<!--                                        <input type="radio" name="emp_status" value="0" <?php echo ifSetRadio('emp_status', 0) ?> />
                                        <span class="multy_options">All</span>-->
                                    </td>



                                    <th>Joined Date :</th>
                                    <td>
                                        <div class="dateContainer" style="padding: 0px;margin:0px;float: left;">
                                            <div style="margin-right: 2px;float: left;" >
                                                <input class="dateField inputDate" placeholder="From" readonly="" type="text" name="f_emp_date" value="<?php echo ifSet('f_emp_date') ?>" style="width:105px" id="f_emp_date"  /> 
                                            </div>
                                            <div style="margin-bottom: 1px;float: left;">
                                                <img src="images/calendar.gif"  class="calendarButton"> 
                                            </div>
                                        </div>  

                                        <div class="dateContainer" style="padding: 0px;margin:0px;margin-left:2px; float: left;">
                                            <div style="margin-right: 2px;float: left;" >
                                                <input class="dateField inputDate" placeholder="To" readonly="" type="text" name="t_emp_date" value="<?php echo ifSet('t_emp_date') ?>" style="width:105px" id="t_emp_date"  /> 
                                            </div>
                                            <div style="margin-bottom: 1px;float: left;">
                                                <img src="images/calendar.gif"  class="calendarButton"> 
                                            </div>
                                        </div>             

                                    </td>
                                </tr>


                                <tr>
                                    <th>Username : </th>
                                    <td><input type="text" name="username" value="<?php echo ifSet('username') ?>">  </td>
                                    <th>Address : </th>
                                    <td><input type="text" name="emp_address" value="<?php echo ifSet('emp_address') ?>">  </td>
                                </tr>

                                <tr>
                                    <th>Category : </th>
                                    <td>
                                        <select name="emp_category[]" multiple="multiple"  style="height:100px;">
                                            <?php echo get_options2($emp_cats, ifSet('emp_category')); ?>
                                        </select>
                                        <p class="help">Hold <b>Ctrl</b> Key to select multiple categories.</p>
                                    </td>
                                    <th>Workcentre : </th>
                                    <td>
                                        <select name="ewp_fk_workcentres[]" multiple="multiple"  style="height:100px;">
                                            <?php echo get_options2($workcentres, ifSet('ewp_fk_workcentres')); ?>
                                        </select>
                                        <p class="help">Hold <b>Ctrl</b> Key to select multiple workcentres.</p>
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

            <a href="<?php echo site_url('employees/add'); ?>" >
                <div class="right_action"  title="<?php echo lang('index_create_user_link') ?>">
                    <div class="settings_btn">
                        <img src="images/Add button.png" height="24" width="24" />
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
        <table cellpadding="0" cellspacing="0" id="tbl_search_results">
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

                    echo '<td style="color:green">';


                    echo '<div class="tools">';
                    echo '<div class="dv_tools_left">';
                    echo '<input type="checkbox" class="checkID"  value="' . $row['id'] . '" >&ensp;<span class="sl_no">' . $slNo . '</span>';
                    echo '</div>';
                    echo '<div class="dv_tools_right">';

//                    echo '<input type="hidden" class="p_key" value="' . $row['id'] . '">';
//                    echo '<input type="hidden" class="name_data" value="' . $row['first_name'] . '">';

                    echo '<p class="tools_case">';
                    echo anchor("employees/before_edit/edit/$row[id]", '<img  src="images/edit.png" title="Edit" class="toolbar_imgs">') . '&nbsp;';
                    echo '</p>';



                    echo '<p class="tools_case cash">';
                    echo '<img   title="Cash" src="images/icons/cash.png" class="toolbar_imgs">' . '&nbsp;';
                    echo '</p>';


                    echo '<p class="tools_case publish">';
                    echo '<img  src="images/bird.png" title="Publish" class="toolbar_imgs">&nbsp;';
                    echo '</p>';



                    echo '<p class="tools_case plan">';
                    echo '<img  src="images/icons/home_plan.png" title="Plan" class="toolbar_imgs">&nbsp;';
                    echo '</p>';







                    echo '</div>';
                    echo '</div>';


                    echo '<div class="patient_info no_sel" >';
                    echo '<span class="nameSpan">';
                    echo $row['first_name'];
                    echo '&nbsp;&nbsp;<font color="#CC00FF">[Id: ' . $row['id'] . ']</font>';
                    echo '</span>';
                    if($row['emp_category']==1 && isset($emp_cats[$row['emp_category']])) //Admin
                        echo '<span class="admin_color empcat">'.$emp_cats[$row['emp_category']].'</span>';
                    else if($row['emp_category']==2 && isset($emp_cats[$row['emp_category']])) // Partner
                        echo '<span class="partner_color empcat">'.$emp_cats[$row['emp_category']].'</span>';
                    else if($row['emp_category']==3 && isset($emp_cats[$row['emp_category']])) // Staff
                        echo '<span class="staff_color empcat">'.$emp_cats[$row['emp_category']].'</span>';
                    else if($row['emp_category']==4 && isset($emp_cats[$row['emp_category']])) // Driver
                        echo '<span class="driver_color empcat">'.$emp_cats[$row['emp_category']].'</span>';
                    else if($row['emp_category']==5 && isset($emp_cats[$row['emp_category']])) // Loader
                        echo '<span class="loader_color empcat">'.$emp_cats[$row['emp_category']].'</span>';
 
                    if ($this->is_admin || $this->is_partner)
                    {
                        if ($row['username'])
                            echo '<br>Username : ' . $row['username'];

                        if ($row['email'])
                            echo '<br>Email : ' . $row['email'];
                    }
                    
//                    echo '<font style="background-color:#060;color:#FFF;padding:0px 4px;">';
//                    echo ($row['active']) ? anchor("index/deactivate/" . $row['id'], "Inactive") : anchor("index/activate/" . $row['id'], "Active");
//                    echo '</font>';

                    if ($row['emp_address'])
                        echo '<br>' . nl2br($row['emp_address']);
                    if ($row['phone'])
                        echo '<br>Phone : ' . $row['phone'] ;
                    if ($row['emp_date'])
                        echo '<br>Joined On : ' . formatDate($row['emp_date'], false) ;
                    
                    if($wlog[$row['emp_id']]['user'])
                        echo '<br><font color="blue">Last Change by <b>'.$wlog[$row['emp_id']]['user'].'</b> On '.formatDate($wlog[$row['emp_id']]['wlog_created'], false).'</font>';
                    
                    if($availability[$row['emp_id']])
                        echo '<br><font color="green">Availability :'.$availability[$row['emp_id']].'</font>';
                    
                    echo '</div> ';

                    echo '<div class="collapse_container">';
                    echo '<p class="bottom_slab"></p> ';
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






