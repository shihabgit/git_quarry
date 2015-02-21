<?php $this->load->view('owners/pop_add'); ?>
<?php $this->load->view('workcentre_registration_details/pop_add') ?>

<div class="dv-top-content" align="center">
    <?php echo form_open("workcentres/edit"); ?>
    <table width="75%" cellpadding="5" cellspacing="0" class="tbl_input">
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


                <ul class="main_container" style="width: 50%;height: 370px;"> 

                    <li>

                        <div class="sec_container">
                            <p class="input-categories">Workcentre Details</p>
                            <table class="sec_table">


                                <tr>
                                    <th>Ownership</th>
                                    <td><input type="radio" name="workcentres[wcntr_ownership]" value="1" <?php echo ifSetRadioGroupArray('workcentres', 'wcntr_ownership', 1, ($workcentres['wcntr_ownership'] == 1)) ?>  />
                                        <span class="multy_options">Owned</span>

                                        <input type="radio" name="workcentres[wcntr_ownership]" value="2" <?php echo ifSetRadioGroupArray('workcentres', 'wcntr_ownership', 2, ($workcentres['wcntr_ownership'] == 2)) ?> />
                                        <span class="multy_options">Rental</span>
                                    </td>
                                </tr>

                                <tr>
                                    <th>Capital</th>
                                    <td>
                                        <input type="text" class="intOnly" name="workcentres[wcntr_capital]" value="<?php echo set_value('workcentres[wcntr_capital]', $workcentres['wcntr_capital']) ?>" >
                                        <?php echo form_error('workcentres[wcntr_capital]'); ?><br>

                                    </td>
                                </tr>

                                <tr>
                                    <th>Name</th>
                                    <td>
                                        <input type="text" name="workcentres[wcntr_name]" value="<?php echo set_value('workcentres[wcntr_name]', $workcentres['wcntr_name']) ?>" >
                                        <?php echo form_error('workcentres[wcntr_name]'); ?>
                                    </td>
                                </tr>


                                <tr>
                                    <th>Reg. Name</th>
                                    <td>
                                        
                                        <div style="padding:0;float: left;">
                                            <select name="workcentres[wcntr_fk_workcentre_registration_details]" class="reg_id" >
                                                <?php echo get_options2($registrations, ifSet2("workcentres[wcntr_fk_workcentre_registration_details]",$workcentres['wcntr_fk_workcentre_registration_details']), true, '--- select ---'); ?>
                                            </select>
                                        </div>
                                        <div style="padding:1% 0 0 5px ;float: right;">
                                            <img src="images/add.png" id="addReg" title="Add New Owner">
                                        </div>
                                        <?php echo form_error('workcentres[wcntr_fk_workcentre_registration_details]'); ?>
                                        
                                    </td>
                                </tr>


                            </table>
                        </div>          <!--     End of Personal Details-->
                    </li>


                    <?php
                    $style = ifSetRadioGroupArray('workcentres', 'wcntr_ownership', 1, ($workcentres['wcntr_ownership'] == 1)) ? "pointer-events: none; opacity: 0.6" : '';
                    ?>

                    <li class="rental_list" style="<?php echo $style ?>" >
                        <div class="sec_container">
                            <p class="input-categories">Rental Details</p>


                            <table class="sec_table">
                                <tr>
                                    <th>Owner:</th>
                                    <td>
                                        <select name="rental_details[rntdt_fk_owners]" id="rntdt_fk_owners">
                                            <?php echo get_options2($owners, ifSetArray('rental_details', 'rntdt_fk_owners', $rental_details['rntdt_fk_owners']), true, '--- Select ---'); ?>
                                        </select>
                                        <img src="images/add.png" id="initOwnerAdd" title="Add New Owner">
                                        <?php echo form_error('rental_details[rntdt_fk_owners]'); ?>
                                    </td>
                                </tr>

                                <tr>
                                    <th>Advance Paid:</th>
                                    <td>
                                        <input type="text" class="intOnly" name="rental_details[rntdt_advance]" value="<?php echo set_value('rental_details[rntdt_advance]', $rental_details['rntdt_advance']) ?>" >
                                        <?php echo form_error('rental_details[rntdt_advance]'); ?>
                                    </td>
                                </tr>

                                <tr>
                                    <th>Old Balance:</th>
                                    <td>
                                        <input type="text" class="intOnly" name="rental_details[rntdt_ob]" value="<?php echo set_value('rental_details[rntdt_ob]', $rental_details['rntdt_ob']) ?>" >
                                        <?php echo form_error('rental_details[rntdt_ob]'); ?><br>
                                        <?php
                                        $cr = $dr = '';
                                        if (isset($rental_details['rntdt_ob_mode']))
                                        {
                                            $cr = ifSetRadioGroupArray('rental_details', 'rntdt_ob_mode', 1, ($rental_details['rntdt_ob_mode'] == 1));
                                            $dr = ifSetRadioGroupArray('rental_details', 'rntdt_ob_mode', 2, ($rental_details['rntdt_ob_mode'] == 2));
                                        }

                                        // if not set $rental_details['rntdt_ob_mode'], set 'Credit' mode as true default.
                                        if (!$cr && !$dr)
                                            $cr = " checked ";
                                        ?>

                                        <input type="radio" name="rental_details[rntdt_ob_mode]" value="1" <?php echo $cr ?>  />
                                        <span class="multy_options">Cr.</span>

                                        <input type="radio" name="rental_details[rntdt_ob_mode]" value="2" <?php echo $dr ?>/>
                                        <span class="multy_options">Dr.</span>
                                    </td>
                                </tr>


                                <tr>
                                    <th>Installment Amount:</th>
                                    <td>
                                        <input type="text" style="width:110px;" class="intOnly" name="rental_details[rntdt_instalment_amount]" value="<?php echo set_value('rental_details[rntdt_instalment_amount]', $rental_details['rntdt_instalment_amount']) ?>" >

                                        <?php if(!isset($rental_details['rntdt_auto_add'])) $rental_details['rntdt_auto_add'] = 2;?>
                                        <input title="Rent automatically added to rent account when period reaches." type="checkbox" name="rental_details[rntdt_auto_add]" value="<?php echo $rental_details['rntdt_auto_add'];?>" <?php echo set_checkbox('rental_details[rntdt_auto_add]', '1', ($rental_details['rntdt_auto_add'] == 1)); ?> />
                                        
                                        
                                        
                                        
                                        
                                        <span title="Rent automatically added to rent account when period reaches." class="multy_options">Auto Add</span>


<?php echo form_error('rental_details[rntdt_instalment_amount]'); ?>
                                    </td>
                                </tr>
<?php
                                        $daily = $monthly = $annually = '';
                                        if (isset($rental_details['rntdt_instalment_period']))
                                        {
                                            $daily = ifSetRadioGroupArray('rental_details', 'rntdt_instalment_period', 1, ($rental_details['rntdt_instalment_period'] == 1));
                                            $monthly = ifSetRadioGroupArray('rental_details', 'rntdt_instalment_period', 2, ($rental_details['rntdt_instalment_period'] == 2));
                                            $annually = ifSetRadioGroupArray('rental_details', 'rntdt_instalment_period', 3, ($rental_details['rntdt_instalment_period'] == 3));
                                        }

                                        // if not set $rental_details['rntdt_ob_mode'], set 'Credit' mode as true default.
                                        if (!$daily && !$monthly && !$annually)
                                            $monthly = " checked ";
                                        ?>
                                <tr>
                                    <th>Installment Period:</th>
                                    <td>
                                        <input type="radio" name="rental_details[rntdt_instalment_period]" value="1" <?php echo $daily ?>  />
                                        <span class="multy_options">Daily</span>

                                        <input type="radio" name="rental_details[rntdt_instalment_period]" value="2" <?php echo $monthly ?>/>
                                        <span class="multy_options">Monthly</span>

                                        <input type="radio" name="rental_details[rntdt_instalment_period]" value="3" <?php echo $annually ?>  />
                                        <span class="multy_options">Annually </span>
                                    </td>
                                </tr>

                                <tr>
                                    <th>Starts From :</th>
                                    <td>
                                        <div class="dateContainer" style="padding: 0px;margin:0px;">
                                            <div style="margin-right: 2px;float: left;" >
                                                <input class="dateField inputDate" readonly="" type="text" name="rental_details[rntdt_start_from]" value="<?php echo set_value('rental_details[rntdt_start_from]', $rental_details['rntdt_start_from']) ?>" style="width:110px" />
                                            </div>
                                            <div style="margin:2px;">
                                                <img src="images/calendar.gif"  class="calendarButton"> 
                                            </div>

<?php echo form_error('rental_details[rntdt_start_from]'); ?>
                                        </div>

                                    </td>
                                </tr>

                            </table>
                        </div>      <!--     End of Rental Details-->
                    </li>









                </ul> <!--     End of <div class="main_container">-->
            </td>
        </tr>



    </table>
    <div id="submit_container">
        <hr /><br />
        <input type="hidden" name="workcentres[wcntr_id]" value="<?php echo set_value('workcentres[wcntr_id]', $workcentres['wcntr_id']) ?>" >   
        <input type="submit" name="button2" class="collapse_btn" value="Submit" />
        <input type="button" class="collapse_btn reseter" name="button3" value="Reset" />
    </div>
<?php echo form_close(); ?>
</div> 	<!--<div class="dv-top-content" >-->


<script type="text/javascript">

    $(document).ready(function() {

        $('input[name="workcentres[wcntr_ownership]"]').change(function()
        {
            // If the workcentre is Owned, there is no need for rental details.
            if ($(this).val() == 1)
            {
                $('li.rental_list').css('pointer-events', 'none');
                $('li.rental_list').css('opacity', '0.6');
                $('li.rental_list').find(':input').val('');
            }
            // If the workcentre is Rental, it should be added rental details.
            else if ($(this).val() == 2)
            {
                $('li.rental_list').css('pointer-events', 'auto');
                $('li.rental_list').css('opacity', '2');
            }
        });


        // Opening popup box on clicking <img src="images/add.png" id="initOwnerAdd"> @ workcentres/add.php
        $('#initOwnerAdd').click(function() {

            //Initializing popup box  
            init_pop_owners_add();

            //Loading popupBox.
            loadPopup('pop_owners_add');
        });

        $('#addReg').click(function() {

            //Initializing popup box  
            init_pop_workcentre_registration_details_add();

            //Loading popupBox.
            loadPopup('pop_workcentre_registration_details_add');
        });

    });
</script>