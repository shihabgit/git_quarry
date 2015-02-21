<div class="dv-top-content" align="center">
    <?php echo form_open(""); ?>
    <table width="100%" cellpadding="5" cellspacing="0" class="tbl_input">
        <tr>
            <td>

                <div class="title-box">
                    <div id="img-container">
                        <img src="images/staffs.png" width="35" height="35"/>
                    </div>
                    <div id="title-container">
                        <div class="title-alone">Add employee</div>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <ul class="main_container" style="width:33.333%;height: 600px;">


                    <li>
                        <div class="sec_container">
                            <p class="input-categories">Employee Availability</p>
                            <table class="sec_table">
                                <tr>
                                    <td>
                                        <?php
                                        if ($firms)
                                        {
                                            foreach ($firms as $id => $firm)
                                            {
                                                echo '<div class="frmwc">';
                                                echo '<table width="100%">';
                                                echo '<tr><td colspan="2" class="xxx"><span>' . $firm . '<span></td></tr>';
                                                $col = 1;
                                                
                                                $maxcol = 2;
                                                for ($i=0;$i<count($workcentres);$i++)
                                                {
                                                    if ($workcentres[$i]['wcntr_fk_firms'] == $id)
                                                    {   if($col == 1)
                                                            echo '<tr>';
                                                        echo '<td><input type="checkbox" name=""  />' . $workcentres[$i]['wcntr_name'] . '</td>';
                                                        
                                                        if($col == $maxcol)
                                                        {   echo '</tr>';
                                                            $col = 1;
                                                        }
                                                        else
                                                            $col++;
                                                    }
                                                }
                                                echo '</table>';
                                                echo '</div>';
                                            }
                                        }
                                        ?>
                                    </td>
                                </tr>

                            </table>
                        </div>      <!--     End of Employee Availability-->
                    </li>
                    <li>

                        <div class="sec_container">
                            <p class="input-categories">Personal Details</p>
                            <table class="sec_table">

                                <tr>
                                    <th>Category</th>
                                    <td><select name="select" id="select">
                                            <option value="1">Admin</option>
                                            <option value="2">Staff</option>
                                            <option value="3">Driver</option>
                                        </select>
                                        <?php echo form_error(''); ?>
                                    </td>
                                </tr>

                                <tr>
                                    <th>Name</th>
                                    <td>
                                        <?php echo form_input($first_name); ?>
                                        <?php echo form_error('first_name'); ?>
                                    </td>
                                </tr>
                                <!--                        
                                                       <tr>
                                                           <th>Last Name</th>
                                                           <td>
                                <?php echo form_input($last_name); ?>
                                                           </td>
                                                       </tr>
                                                       
                                                      <tr>
                                                           <th>Company</th>
                                                           <td>
                                <?php echo form_input($company); ?>
                                                           </td>
                                                       </tr>-->

                                <tr>
                                    <th>Email</th>
                                    <td>
                                        <?php echo form_input($email); ?>
                                        <?php echo form_error('email'); ?>
                                    </td>
                                </tr>

                                <tr>
                                    <th>Phone</th>
                                    <td>
                                        <?php echo form_input($phone); ?>
                                        <?php echo form_error(''); ?>
                                    </td>
                                </tr>

                                <tr>
                                    <th>Password</th>
                                    <td>
                                        <?php echo form_input($password); ?>
                                        <?php echo form_error(''); ?>
                                    </td>
                                </tr>

                                <tr>
                                    <th>Confirm Password</th>
                                    <td>
                                        <?php echo form_input($password_confirm); ?>
                                        <?php echo form_error(''); ?>
                                    </td>
                                </tr>


                                <tr>
                                    <th>Address</th>
                                    <td>
                                        <textarea name=""></textarea>
                                        <?php echo form_error(''); ?>
                                    </td>
                                </tr>

                            </table>
                        </div>          <!--     End of Personal Details-->
                    </li>
                    <li>





                        <div class="sec_container">
                            <p class="input-categories">Account Details</p>
                            <table class="sec_table">
                                <tr>
                                    <th>Old Balance</th>
                                    <td>
                                        <input type="text" name="emp_ob" ><br>
                                        <input type="radio" name="RadioGroup1" value="radio" id="RadioGroup1_0" checked="checked" />
                                        <font color="#FFCC00" size="2" face="Arial, Helvetica, sans-serif"><b>Cr.</b></font>
                                        <input type="radio" name="RadioGroup1" value="radio" id="RadioGroup1_1" />
                                        <font color="#FFCC00" size="2" face="Arial, Helvetica, sans-serif"><b>Dr.</b></font>
                                        <?php echo form_error('emp_ob'); ?>
                                    </td>
                                </tr>

                                <tr>
                                    <th>Last Name</th>
                                    <td>
                                        <?php echo form_input($last_name); ?>
                                    </td>
                                </tr>

                                <tr>
                                    <th>Company</th>
                                    <td>
                                        <?php echo form_input($company); ?>
                                    </td>
                                </tr>

                                <tr>
                                    <th>Email</th>
                                    <td>
                                        <?php echo form_input($email); ?>
                                    </td>
                                </tr>
                            </table>
                        </div>      <!--     End of Account Details-->
                    </li>


                </ul> <!--     End of <div class="main_container">-->
            </td>
        </tr>

        <tr>
            <td><hr /></td>
        </tr>
        <tr>
            <td align="center" valign="middle" style="text-align:center"><input type="submit" name="button2" class="collapse_btn" value="Submit" />
                <input type="button" class="collapse_btn reseter" name="button3" value="Reset" /></td>
        </tr>
    </table>
    <?php echo form_close(); ?>
</div> 	<!--<div class="dv-top-content" >-->