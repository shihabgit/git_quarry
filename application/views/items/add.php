<?php $this->load->view('item_category/pop_add'); ?>
<?php $this->load->view('item_heads/pop_add'); ?>
<div class="dv-top-content" align="center">
    <?php echo form_open("items/add", array('id' => 'add_form')); ?>
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

                <div class="inputblok" style="width: 100%;">

                    <div class="sec_container">
                        <p class="input-categories">Basic Details</p>
                        <table class="sec_table">

                            <tr>
                                <th>Item Category</th>
                                <td>
                                    <select name="itmcat_id" id="itmcat_id" onchange="resetOptions(this, 'itm_fk_item_head', 'item_heads/getItemHead', beforeAjax, afterAjax);" >
                                        <?php echo get_options2($itmcats, ifSet('itmcat_id'), true, '--- select ---'); ?>
                                    </select>
                                    <img src="images/add.png" id="add_category" />
                                    <?php echo form_error('itmcat_id'); ?>
                                </td>
                            </tr>

                            <tr>
                                <th>Item Head</th>
                                <td>
                                    <select name="items[itm_fk_item_head]" id="itm_fk_item_head">
                                        <?php echo get_options2($itm_heads, ifSetArray('items', 'itm_fk_item_head'), true, '--- Select ---'); ?>
                                    </select>
                                    <img src="images/add.png" id="add_head" />
                                    <?php echo form_error('items[itm_fk_item_head]'); ?>
                                    <div class="ajaxLoaderContainer"> 
                                        <img src="images/ajax-loader2.gif"> 
                                        <img src="images/ajax-loader2.gif">
                                    </div>  
                                </td>
                            </tr>

                            <tr>
                                <th>Item Name</th>
                                <td>
                                    <input type="text" name="items[itm_name]" value="<?php echo set_value('items[itm_name]') ?>" >
                                    <?php echo form_error('items[itm_name]'); ?>
                                </td>
                            </tr>

                            <tr>
                                <th>Tax on Purchase</th>
                                <td>
                                    <input type="text" name="items[itm_p_vat]" placeholder="VAT %" class="numberOnly" value="<?php echo set_value('items[itm_p_vat]') ?>" style="width:80px;" >
                                    <input type="text" name="items[itm_p_cess]" placeholder="CESS %" class="numberOnly" value="<?php echo set_value('items[itm_p_cess]') ?>"  style="width:80px;"> 
                                    <?php echo form_error('items[itm_p_vat]'); ?>
                                    <?php echo form_error('items[itm_p_cess]'); ?>
                                </td>
                            </tr>

                            <tr>
                                <th>Tax on Sales</th>
                                <td>
                                    <input type="text" name="items[itm_s_vat]" placeholder="VAT %" class="numberOnly" value="<?php echo set_value('items[itm_s_vat]') ?>"  style="width:80px;" >
                                    <input type="text" name="items[itm_s_cess]" placeholder="CESS %" class="numberOnly" value="<?php echo set_value('items[itm_s_cess]') ?>" style="width:80px;"> 
                                    <?php echo form_error('items[itm_s_vat]'); ?>
                                    <?php echo form_error('items[itm_s_cess]'); ?>
                                </td>
                            </tr>
                            <tr><td colspan="2"><hr></td></tr>

                            <tr>
                                <td colspan="2">
                                    <div style="text-align: center;width:60%;margin: auto;">
                                        <table  class="sec_table2 tbl_input" style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th style="width: 20%;">Default</th>
                                                    <th style="min-width: 40%;">Unit Name</th>
                                                    <th style="min-width: 30%;">
                                                        Relation
                                            <div style="float:right;margin-top:5px">
                                                <img src="images/Add button.png" height="20" width="20" id="add_row" title="Add unit"/>
                                                <img src="images/delete2.png" height="20" width="20" id="remove_row" title="Delete unit"/>
                                            </div>
                                            </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                for ($i = 0; $i < $unit_row_count; $i++)
                                                {
                                                    // If only one row
                                                    if ($unit_row_count == 1)
                                                        $defChecked = 'checked=""';
                                                    else
                                                        $defChecked = ifSetRadioGroupArray('unit', 'def', $i);
                                                    ?>

                                                    <tr>
                                                        <td>
                                                            <input type="radio" name="unit[def]" class="unit_def" value="<?php echo $i; ?>" <?php echo $defChecked; ?> >
                                                        </td>
                                                        <td>
                                                            <?php $val = isset($_POST['unit']['name'][$i]) ? $_POST['unit']['name'][$i] : ''; ?>
                                                            <input type="text" name="unit[name][]" class="unit_name" style="width:95%;" value="<?php echo $val; ?>" >
                                                            <?php echo form_error('unit[name][' . $i . ']'); ?>
                                                        </td>
                                                        <td class="td_last">
                                                            <?php $val = isset($_POST['unit']['rel'][$i]) ? $_POST['unit']['rel'][$i] : ''; ?>
                                                            <input type="text" name="unit[rel][]" class="numberOnly unit_rel" style="width:95%;" value="<?php echo $val; ?>">
                                                            <?php echo form_error('unit[rel][' . $i . ']'); ?>
                                                        </td>                                            
                                                    </tr>   
                                                <?php } ?>       
                                            </tbody>

                                        </table>
                                    </div>
                                </td>
                            </tr>





                        </table>
                    </div>          <!--     End of Personal Details-->
                </div>
            </td>
        </tr>
    </table>

    <div id="submit_container">
        <hr /><br />
        <input type="hidden" name="items[itm_status]" value="1" >   <!--  Setting Default Status As "Active"  -->
        <input type="submit" name="button2" class="collapse_btn" value="Submit" />
        <input type="button" class="collapse_btn reseter" name="button3" value="Reset" />
    </div>
    <?php echo form_close(); ?>
</div> 	<!--<div class="dv-top-content" >-->


<script type="text/javascript">

    // This variable is used in 'item_category/pop_add.php' and in 'item_heads/pop_add.php' to determine from where the popup is loaded. Because the same popups is loading from 'item_heads/index' also.
    var clsfunc = 'items/add';

    $(document).ready(function() {
        toggleRemover();
        checkIsCheckedOne();
        hideFirst();


        $('.sec_table2 .unit_name').keypress(function(e) {
            var index = $(this).closest('.sec_table2 tbody tr').index();
            var key = e.keyCode || e.which;
            if (key == 13)  // the enter key code
            {
                // If '.unit_name' of tr:first, creating new row. Because it is the parent. so there is no need of relations.
                if (index == 0)
                    createRow();

                // It is a childe unit. So focus to relation field.
                else
                    $(this).closest('tr').find('.unit_rel').focus();

                e.preventDefault();
                return false;
            }
        });


        $('.sec_table2 tbody .unit_rel').keypress(function(e) {
            var current_row = $(this).closest('.sec_table2 tbody tr').index();
            var last_row = $('.sec_table2 tbody tr:last').index();
            var next_row = current_row + 1;
            var key = e.keyCode || e.which;
            if (key == 13)  // the enter key code
            {
                if (current_row == last_row)
                    createRow();
                else
                    $('.sec_table2 tbody tr:eq(' + next_row + ')').find('.unit_name').focus();
                e.preventDefault();
                return false;
            }
        });


        $('.sec_table2 #add_row').click(function() {
            createRow();
        });

        $('.sec_table2 #remove_row').click(function() {
            var row_index = $(".sec_table2 tbody tr:last").index();

            // Not the first row.
            if (row_index != 0)
                $(".sec_table2 tbody tr:last").remove();
            toggleRemover();
            checkIsCheckedOne();
        });

        function toggleRemover()
        {
            if ($(".sec_table2 tbody tr").length <= 1)
                $('.sec_table2 #remove_row').hide();
            else
                $('.sec_table2 #remove_row').show();
        }


        function createRow()
        {
            $('.sec_table2 tbody tr:last').clone(true).insertAfter('.sec_table2 tr:last');
            $(".sec_table2 tbody tr:last input").val('');
            $(".sec_table2 tbody tr:last .unit_name").focus();
            $(".sec_table2 tbody tr:last .dialog-box-container").empty();
            $(".sec_table2 tbody tr:last .unit_rel").show();
            setDefaultValue();
            toggleRemover();
            checkIsCheckedOne();
        }

        function hideFirst()
        {
            // The first unit is considered as parent. So its 'relation with parent' field is meaningless.
            $(".sec_table2 tbody tr:first .unit_rel").hide();
        }

        function setDefaultValue()
        {
            $('.sec_table2 tbody tr').each(function() {
                var index = $(this).index();
                $(this).find('.unit_def').val(index);
            });
        }

        function checkIsCheckedOne()
        {
            var isChecked = false;
            $('.sec_table2 tbody .unit_def').each(function() {
                if ($(this).prop('checked'))
                    isChecked = true;
            });

            // If no radios selected, checks first one.
            if (!isChecked)
                $('.sec_table2 tbody tr:first').find('.unit_def').prop('checked', true);
        }

        $('.sec_table #add_category').click(function() {

            //Initializing popup box  
            init_pop_category_add();

            //Loading popupBox.
            loadPopup('pop_category_add');
        });

        $('.sec_table #add_head').click(function() {

            //Initializing popup box  
            init_pop_head_add();

            //Loading popupBox.
            loadPopup('pop_head_add');
        });

    });

    function beforeAjax()
    {
        $('#itm_fk_item_head').hide();
        $('#add_head').hide();
        $('.ajaxLoaderContainer').show();
    }

    function afterAjax()
    {
        $('#itm_fk_item_head').show();
        $('#add_head').show();
        $('.ajaxLoaderContainer').hide();
    }
</script>