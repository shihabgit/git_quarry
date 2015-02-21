<div class="dv-top-content" align="center">
    <?php echo form_open("employees/edit", array('id' => 'search_form')); ?>
    
    <?php $width = ($this->is_admin||$this->is_partner)?'75%'  :'35%' ?>
    <table width="<?php echo $width?>" cellpadding="5" cellspacing="0" class="tbl_input">
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
            <td><?php $width = ($this->is_admin||$this->is_partner)?'50%'  :'100%' ?>
                <ul class="main_container" style="width: <?php echo $width?>;height: 300px;"> 


                    <li>

                        <div class="sec_container">
                            <p class="input-categories">Personal Details</p>
                            <table class="sec_table">

                                <?php
                                // Category can be changed only by Admin/partner
                                if($this->is_admin||$this->is_partner)
                                {
                                ?>
                                
                                <tr>
                                    <th>Category</th>
                                    <td>
                                        <select name="employees[emp_category]" id="emp_category">
                                            <?php echo get_options2($emp_cats, ifSetArray('employees', 'emp_category', $employees['emp_category']), true, '--- Select ---'); ?>

                                        </select>
                                        <?php echo form_error('employees[emp_category]'); ?>
                                    </td>
                                </tr>
                                <?php
                                }
                                else
                                {
                                    ?>
                                <input type="hidden" name="employees[emp_category]" value="<?php echo ifSetArray('employees', 'emp_category', $employees['emp_category']);?>" >
                              <?php  }?>
                                <tr>
                                    <th>Name</th>
                                    <td>
                                        <input type="text" name="auth[first_name]" value="<?php echo set_value('auth[first_name]', $auth['first_name']) ?>" >
                                        <?php echo form_error('auth[first_name]'); ?>
                                    </td>
                                </tr>


                                <tr>
                                    <th>Email</th>
                                    <td>
                                        <input type="text" name="auth[email]" value="<?php echo set_value('auth[email]', $auth['email']) ?>" >
                                        <?php echo form_error('auth[email]'); ?>
                                    </td>
                                </tr>

                                <tr>
                                    <th>Phone</th>
                                    <td>
                                        <input type="text" class="intOnly" name="auth[phone]" value="<?php echo set_value('auth[phone]', $auth['phone']) ?>" >
                                        <?php echo form_error('auth[phone]'); ?>
                                    </td>
                                </tr>





                                <tr>
                                    <th>Address</th>
                                    <td>
                                        <textarea name="employees[emp_address]"><?php echo set_value('employees[emp_address]', $employees['emp_address']) ?></textarea>
                                        <?php echo form_error('employees[emp_address]'); ?>
                                    </td>
                                </tr>

                            </table>
                        </div>          <!--     End of Personal Details-->
                    </li>





<?php if ($this->is_admin||$this->is_partner) {  ?>

                    <li id="login_details" >

                        <div class="sec_container">
                            <p class="input-categories">Login Details</p>
                            <table class="sec_table">


                                <tr>
                                    <th>Username</th>
                                    <td>                                        
                                        <input type="text" name="auth[username]" value="<?php echo set_value('auth[username]', $auth['username']) ?>" >
                                        <?php echo form_error('auth[username]'); ?>
                                    </td>
                                </tr>

<!--                                <tr>
                                    <th>Old Password</th>
                                    <td>
                                        <input type="password" name="old_password" value="<?php echo set_value('old_password') ?>" >
                                        <?php echo form_error('old_password'); ?>
                                    </td>
                                </tr>-->


                                <tr>
                                    <th>New Password</th>
                                    <td>
                                        <input type="password" name="auth[password]" value="<?php echo set_value('auth[password]') ?>" >
                                        <?php echo form_error('auth[password]'); ?>
                                    </td>
                                </tr>


                                <tr>
                                    <th>Confirm Password</th>
                                    <td>                                        
                                        <input type="password" name="password_confirm" value="<?php echo set_value('password_confirm') ?>" >
                                        <?php echo form_error('password_confirm'); ?>
                                    </td>
                                </tr>
                                <tr height="95px;">
                                    <th></th>
                                    <td>
                                        
                                    </td>
                                </tr>

                            </table>
                        </div>
                    </li>
<?php } ?>



                </ul> <!--     End of <div class="main_container">-->
            </td>
        </tr>
    </table>
    <table class="sec_table2 tbl_input">
        <tr><th colspan="11"><h3>EMPLOYEE AVAILABILITY</h3></th></tr>
        <tr>
            <th rowspan="2" valign="center" align="center" style="width:100px;">Firm</th>
            <th rowspan="2" valign="center" align="center" style="width:150px;">Workcentre</th>
            <th rowspan="2" valign="center" align="center">Old Balance</th>
            <th colspan="3" valign="center" align="center">Day Wages</th>
            <th colspan="3" valign="center" align="center">Night Wages</th>
            <th rowspan="2" valign="center" align="center">Salary</th>
            <th rowspan="2" valign="center" align="center">Status</th>
        </tr>
        <tr>
            <th>Full</th>
            <th>Hourly</th>
            <th>OT</th>
            <th>Full</th>
            <th>Hourly</th>
            <th>OT</th>
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
                        $checked = ($empwc_flds['ewp_fk_workcentres'][$wc_id]) ? " checked " : '';
                        echo '<input type="checkbox"' . $checked . ' name="empwc_flds[ewp_fk_workcentres][' . $wc_id . ']" class="wcntre_id"  />' . $wc['wcntr_name'];
                        echo '</td>';


                        echo '<td>';
                        echo '<input type="text" class="intOnly" style="width:80px;" name="empwc_flds[ewp_ob][' . $wc_id . ']" value="' . $empwc_flds['ewp_ob'][$wc_id] . '" >';

                        $checked = ($empwc_flds['ewp_ob_mode'][$wc_id] == 1) ? " checked" : '';
                        echo '<input type="radio" name="empwc_flds[ewp_ob_mode][' . $wc_id . ']" value="1" ' . $checked . ' />';
                        echo '<span class="multy_options">Cr.</span>';

                        $checked = ($empwc_flds['ewp_ob_mode'][$wc_id] != 1) ? " checked" : '';
                        echo '<input type="radio" name="empwc_flds[ewp_ob_mode][' . $wc_id . ']" value="2" ' . $checked . ' />';
                        echo '<span class="multy_options">Dr.</span>';
                        echo '</td>';


                        echo '<td><input name="empwc_flds[ewp_day_wage][' . $wc_id . ']" value="' . $empwc_flds['ewp_day_wage'][$wc_id] . '" type="text" class="intOnly" style="width:80px;"></td>';
                        echo '<td><input name="empwc_flds[ewp_day_hourly_wage][' . $wc_id . ']" value="' . $empwc_flds['ewp_day_hourly_wage'][$wc_id] . '" type="text" class="intOnly" style="width:80px;"></td>';
                        echo '<td><input name="empwc_flds[ewp_day_ot_wage][' . $wc_id . ']" value="' . $empwc_flds['ewp_day_ot_wage'][$wc_id] . '" type="text" class="intOnly" style="width:80px;"></td>';
                        echo '<td><input name="empwc_flds[ewp_night_wage][' . $wc_id . ']" value="' . $empwc_flds['ewp_night_wage'][$wc_id] . '" type="text" class="intOnly" style="width:80px;"></td>';
                        echo '<td><input name="empwc_flds[ewp_night_hourly_wage][' . $wc_id . ']" value="' . $empwc_flds['ewp_night_hourly_wage'][$wc_id] . '" type="text" class="intOnly" style="width:80px;"></td>';
                        echo '<td><input name="empwc_flds[ewp_night_ot_wage][' . $wc_id . ']" value="' . $empwc_flds['ewp_night_ot_wage'][$wc_id] . '" type="text" class="intOnly" style="width:80px;"></td>';
                        echo '<td><input name="empwc_flds[ewp_salary_wage][' . $wc_id . ']" value="' . $empwc_flds['ewp_salary_wage'][$wc_id] . '" type="text" class="intOnly" style="width:80px;"></td>';


                        echo '<td>';
                        echo '<select name="empwc_flds[ewp_status][' . $wc_id . ']" style="width:90px;">';
                        echo get_options2(array(1 => 'Active', 2 => 'Inactive'), $empwc_flds['ewp_status'][$wc_id], false);
                        echo '</select>';
                        echo '</td>';




                        echo '</tr>';
                    }
                }
            }
        }
        ?>


    </table>


    <?php echo $availability_errors; ?>
    <div id="submit_container">
        <hr /><br />
        <input type="hidden" name="employees[emp_status]" value="<?php echo $employees['emp_status'] ?>" > 
        <input type="hidden" name="employees[emp_id]" value="<?php echo $employees['emp_id'] ?>" >  
        <input type="submit" name="button2" class="collapse_btn" value="Submit" />
        <input type="button" class="collapse_btn reseter" name="button3" value="Reset" />
    </div>
    <?php echo form_close(); ?>
</div> 	<!--<div class="dv-top-content" >-->


<script type="text/javascript">

    $(document).ready(function() {

        function toggleDisable(ele)
        {
            if (ele.prop('checked'))
            {
                ele.closest('tr').find('input[type=text]').prop("disabled", false);
                ele.closest('tr').find('input[type=text]').css('border', '1px solid #8f6030');

                ele.closest('tr').find('select').prop("disabled", false);
                ele.closest('tr').find('select').css('border', '1px solid #8f6030');

                ele.closest('tr').css('opacity', '1');

            }
            else
            {
                ele.closest('tr').find('input[type=text]').prop("disabled", true);
                ele.closest('tr').find('input[type=text]').val('');
                ele.closest('tr').find('input[type=text]').css('border', 'none');

                ele.closest('tr').find('select').prop("disabled", true);
                ele.closest('tr').find('select').css('border', 'none');

                ele.closest('tr').css('opacity', '0.3');
            }

        }


        $('.sec_table2 input[type=checkbox]').each(function() {

            toggleDisable($(this));
        });

        $('.sec_table2 input[type=checkbox]').change(function() {
            toggleDisable($(this));
        });

        $('#emp_category').change(function() {
            checkAdmin();
        });

        //Calling on page load
        checkAdmin();

        function checkAdmin()
        {
            if ($('#emp_category').val() == 1) // Admin
            {
                $('.sec_table2').find('input[type=checkbox]').prop('checked', true);
                $('.sec_table2').find('input[type=checkbox]').prop('disabled', true);
                $('.sec_table2').find('input[type=text]').prop('disabled', false);
                $('.sec_table2').find('input[type=text]').css('border', '1px solid #8f6030');

                $('.sec_table2').find('select').prop("disabled", true);
                $('.sec_table2').find('select').val('1');
                $('.sec_table2').find('select').css('border', '1px solid #8f6030');

                $('.sec_table2').find('tr').css('opacity', '1');
            }
            else
            {
                $('.sec_table2').find('select').prop("disabled", false);
                $('.sec_table2').find('input[type=checkbox]').prop('disabled', false);
            }
        }

        $('#search_form').submit(function() {
            $('.sec_table2').find('select').prop("disabled", false);
            $('.sec_table2').find('input[type=checkbox]').prop('disabled', false);
        });

    });
</script>