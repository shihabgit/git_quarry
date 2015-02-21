<?php $this->load->view('party_license_details/pop_add') ?>
<link href="css/<?php echo $this->themes[$this->theme]['text']; ?>/reports.css"  rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/tbl_taversor.js"></script>
<div class="dv-top-content" align="center">
    <?php echo form_open("parties/add", array('id' => 'add_form')); ?>
    <table width="40%" cellpadding="5" cellspacing="0" class="tbl_input">
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


                <div class="inputblok" style="width: 100%;">

                    <div class="sec_container">
                        <p class="input-categories">Party Basic Details</p>
                        <table class="sec_table">

                            <tr>
                                <th class="mandatory" style="width:40%;">Party Name</th>
                                <td>
                                    <input type="text" name="parties[pty_name]" value="<?php echo set_value('parties[pty_name]') ?>" >
                                    <?php echo form_error('parties[pty_name]'); ?>                                        
                                </td>
                            </tr>

                            <tr>
                                <th class="mandatory">Availability</th>
                                <td>
                                    <select name="dwc_fk_workcentres[]" multiple="" style="height:100px" > 
                                        <?php echo get_options2($workcentres, ifSet2('dwc_fk_workcentres'), true, '--- select ---'); ?>
                                    </select>
                                    <?php echo form_error('dwc_fk_workcentres'); ?>                                        
                                </td>
                            </tr>

                            <tr>
                                <th>Phone</th>
                                <td>
                                    <input type="text" name="parties[pty_phone]" value="<?php echo set_value('parties[pty_phone]') ?>" >
                                    <?php echo form_error('parties[pty_phone]'); ?>
                                </td>
                            </tr>

                            <tr>
                                <th>Email</th>
                                <td>
                                    <input type="text" name="parties[pty_email]" value="<?php echo set_value('parties[pty_email]') ?>" >
                                    <?php echo form_error('parties[pty_email]'); ?>
                                </td>
                            </tr>


                        </table>
                    </div>          <!--     End of Personal Details-->
                </div>
            </td>
        </tr>
    </table>
    <table class="tbl_reports_container" cellpadding="0" cellspacing="0">
        <tr>
            <td valign="top">
                <div class="highlevel"  style="min-height: 0;">
                    <div class="dv_heading">VEHICLE DETAILS</div>

                    <!--  
                    1. class 'tbl_traversor' is a table in which user can traverse through its rows-columns by pressing ARROW/ENTER/SHIFT keys.
                    2. class 'rowadd' means, when traversing reached at last column of last row, it will create a new row by append to the table.
                    it is used in 'js/focus_next_traversor.js'.
                    -->
                    <table class="ratesTable tbl_traversor" id="tbl_pvhcl" cellspacing="0" style="width:100%">
                        <thead>
                            <tr>
                                <th colspan="3">Name</th>
                                <th>No</th>
                                <th>Length (In Inches)</th>
                                <th>Breadth (In Inches)</th>
                                <th>Height (In Inches)</th>
                                <th>XHeight (In Inches)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            for ($i = 0; $i < $pvhcl_count; $i++)
                            {
                                ?>
                                <tr class="tr_traversor">
                                    <td class="trav_option"><div class="tools_col"><img src="images/remove.png" title="Delete Row" class="remove_row"></div></td>
                                    <td class="trav_option"><div class="rowNo"><?php echo $i + 1; ?></div></td>
                                    <td>
                                        <input type="text" class="nextInput pvhcl_name" name="pvhcl[pvhcl_name][]" value="<?php echo ifSet2("pvhcl[pvhcl_name][$i]"); ?>">
                                        <?php echo form_error("pvhcl[pvhcl_name][$i]"); ?>
                                    </td>
                                    <td>
                                        <input type="text" class="nextInput" name="pvhcl[pvhcl_no][]" value="<?php echo ifSet2("pvhcl[pvhcl_no][$i]"); ?>">
                                        <?php echo form_error("pvhcl[pvhcl_no][$i]"); ?>
                                    </td>
                                    <td>
                                        <input type="text" class="numberOnly nextInput" name="pvhcl[pvhcl_length][]" value="<?php echo ifSet2("pvhcl[pvhcl_length][$i]"); ?>">
                                        <?php echo form_error("pvhcl[pvhcl_length][$i]"); ?>
                                    </td>
                                    <td>
                                        <input type="text" class="numberOnly nextInput" name="pvhcl[pvhcl_breadth][]" value="<?php echo ifSet2("pvhcl[pvhcl_breadth][$i]"); ?>">
                                        <?php echo form_error("pvhcl[pvhcl_breadth][$i]"); ?>
                                    </td>
                                    <td>
                                        <input type="text" class="numberOnly nextInput" name="pvhcl[pvhcl_height][]" value="<?php echo ifSet2("pvhcl[pvhcl_height][$i]"); ?>">
                                        <?php echo form_error("pvhcl[pvhcl_height][$i]"); ?>
                                    </td>
                                    <td>
                                        <input type="text" class="numberOnly nextInput" name="pvhcl[pvhcl_xheight][]" value="<?php echo ifSet2("pvhcl[pvhcl_xheight][$i]"); ?>">
                                        <?php echo form_error("pvhcl[pvhcl_xheight][$i]"); ?>
                                    </td>
                                </tr>
                            <?php } ?>

                        </tbody>
                    </table>
                </div> <!--<div class="highlevel">-->
            </td>
        </tr>
    </table>

    <table class="tbl_reports_container" cellpadding="0" cellspacing="0">
        <tr>
            <td valign="top">
                <div class="highlevel" style="min-height: 0;">
                    <div class="dv_heading">DESTINATIONS DETAILS</div>

                    <!--  
                    1. class 'tbl_traversor' is a table in which user can traverse through its rows-columns by pressing ARROW/ENTER/SHIFT keys.
                    2. class 'rowadd' means, when traversing reached at last column of last row, it will create a new row by append to the table.
                    it is used in 'js/focus_next_traversor.js'.
                    -->
                    <table class="ratesTable tbl_traversor" id="tbl_pdst" cellspacing="0" style="width:100%">
                        <thead>
                            <tr>
                                <th class="mandatory" colspan="3">Name</th>
                                <th>
                        <div style="padding:1.5% 0 0 27%;float: left;">Reg Name</div>
                        <div style="padding:1.5% 5% 0 0 ;float: right;">
                            <img src="images/add.png" id="initPartyLicenseAdd" title="Add New Licence Details">
                        </div>
                        </th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Category</th>
                        </tr>
                        </thead>

                        <tbody>
                            <?php
                            for ($i = 0; $i < $pdst_count; $i++)
                            {
                                ?>
                                <tr class="tr_traversor">
                                    <td class="trav_option"><div class="tools_col"><img src="images/remove.png" title="Delete Row" class="remove_row"></div></td>
                                    <td class="trav_option"><div class="rowNo"><?php echo $i + 1; ?></div></td>
                                    <td>
                                        <input type="text" class="nextInput" name="pdst[pdst_name][]" value="<?php echo ifSet2("pdst[pdst_name][$i]"); ?>">
                                        <?php echo form_error("pdst[pdst_name][$i]"); ?>
                                    </td>
                                    <td>
                                        <select name="pdst[pdst_fk_party_license_details][]" class="nextInput pop_pdst_fk_party_license_details" >
                                            <?php echo get_options2($license, ifSet2("pdst[pdst_fk_party_license_details][$i]"),true,'--- select ---'); ?>
                                        </select>
                                        <?php echo form_error("pdst[pdst_fk_party_license_details][$i]"); ?>
                                    </td>
                                    <td>
                                        <input type="text" class="nextInput" name="pdst[pdst_phone][]" value="<?php echo ifSet2("pdst[pdst_phone][$i]"); ?>">
                                        <?php echo form_error("pdst[pdst_phone][$i]"); ?>
                                    </td>
                                    <td>
                                        <input type="text" class="nextInput" name="pdst[pdst_email][]" value="<?php echo ifSet2("pdst[pdst_email][$i]"); ?>">
                                        <?php echo form_error("pdst[pdst_email][$i]"); ?>
                                    </td>

                                    <td>
                                        <select name="pdst[pdst_category][]" class="nextInput pdst_category">
                                            <?php echo get_options2($pdst_category, ifSet2("pdst[pdst_category][$i]",2), FALSE); ?>
                                        </select>
                                        <?php echo form_error("pdst[pdst_category][$i]"); ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div> <!--<div class="highlevel">-->
            </td>
        </tr>
    </table>

    <div id="submit_container">
        <hr /><br />
        <input type="hidden" name="parties[pty_status]" value="1" >   <!--  Setting Default Status As "Active"  -->  
        <input type="submit" name="button2" class="collapse_btn" value="Submit" />
        <input type="button" class="collapse_btn reseter" name="button3" value="Reset" />
    </div>
    <?php echo form_close(); ?>
</div> 	<!--<div class="dv-top-content" >-->


<script type="text/javascript">

    $(document).ready(function() {

        // The function is defined @ js/tbl_traversor.js
        initTraversor(true, true, false);


        // Opening popup box on clicking <img src="images/add.png" id="initPartyLicenseAdd"> 
        $('#initPartyLicenseAdd').click(function() {

            //Initializing popup box  
            init_pop_party_license_details_add();

            //Loading popupBox.
            loadPopup('pop_party_license_details_add');
        });

    });


    // The function is defined for js/tbl_traversor.js to determin what the value should have the '.nextInput' of recently created row.
    function your_option(last_row)
    {
        var tbl = last_row.closest('.tbl_traversor');

        // making value of '.nextInput' in last row of table.id='pdst' to NULL,
        // exept '.pop_pdst_fk_party_license_details' and '.pdst_category';
        if (tbl.prop('id') == 'tbl_pdst')
            last_row.find('.nextInput').not('.pop_pdst_fk_party_license_details, .pdst_category').val('');

        // making value of '.nextInput' in last row of table.id='pdst' to NULL except '.pvhcl_name'.
        if (tbl.prop('id') == 'tbl_pvhcl')
            last_row.find('.nextInput').not('.pvhcl_name').val('');

        // Focusing to first '.nextInput' element of new row.
        last_row.find('.nextInput').eq(0).focus();
    }
</script>