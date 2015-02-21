<?php $this->load->view('item_category/pop_add'); ?>
<?php $this->load->view('item_heads/pop_add'); ?>
<div class="dv-top-content" align="center">
    <?php echo form_open("items/edit", array('id' => 'add_form')); ?>
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
                                        <?php echo get_options2($itmcats, ifSet('itmcat_id',$itmcat_id), true, '--- select ---'); ?>
                                    </select>
                                    <img src="images/add.png" id="add_category" />
                                    <?php echo form_error('itmcat_id'); ?>
                                </td>
                            </tr>

                            <tr>
                                <th>Item Head</th>
                                <td>
                                    <select name="items[itm_fk_item_head]" id="itm_fk_item_head">
                                        <?php echo get_options2($itm_heads, ifSetArray('items', 'itm_fk_item_head',$items['itm_fk_item_head']), true, '--- Select ---'); ?>
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
                                    <input type="text" name="items[itm_name]" value="<?php echo set_value('items[itm_name]',$items['itm_name']) ?>" >
                                    <?php echo form_error('items[itm_name]'); ?>
                                </td>
                            </tr>

                            <tr>
                                <th>Tax on Purchase</th>
                                <td>
                                    <input type="text" name="items[itm_p_vat]" placeholder="VAT %" class="numberOnly" value="<?php echo set_value('items[itm_p_vat]',$items['itm_p_vat']) ?>" style="width:80px;" >
                                    <input type="text" name="items[itm_p_cess]" placeholder="CESS %" class="numberOnly" value="<?php echo set_value('items[itm_p_cess]',$items['itm_p_cess']) ?>"  style="width:80px;"> 
                                    <?php echo form_error('items[itm_p_vat]'); ?>
                                    <?php echo form_error('items[itm_p_cess]'); ?>
                                </td>
                            </tr>

                            <tr>
                                <th>Tax on Sales</th>
                                <td>
                                    <input type="text" name="items[itm_s_vat]" placeholder="VAT %" class="numberOnly" value="<?php echo set_value('items[itm_s_vat]',$items['itm_s_vat']) ?>"  style="width:80px;" >
                                    <input type="text" name="items[itm_s_cess]" placeholder="CESS %" class="numberOnly" value="<?php echo set_value('items[itm_s_cess]',$items['itm_s_cess']) ?>" style="width:80px;"> 
                                    <?php echo form_error('items[itm_s_vat]'); ?>
                                    <?php echo form_error('items[itm_s_cess]'); ?>
                                </td>
                            </tr>

                            
<tr>
                                <th>Default Unit</th>
                                <td>
                                    <select name="items[itm_fk_units]">
                                        <?php echo get_options2($units, ifSetArray('items', 'itm_fk_units',$items['itm_fk_units']), true, '--- Select ---'); ?>
                                    </select>
                                    <?php echo form_error('items[itm_fk_units]'); ?>
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
        <input type="hidden" name="items[itm_id]" value="<?php echo $items['itm_id'];?>" >   
        <input type="submit" name="button2" class="collapse_btn" value="Submit" />
        <input type="button" class="collapse_btn reseter" name="button3" value="Reset" />
    </div>
    <?php echo form_close(); ?>
</div> 	<!--<div class="dv-top-content" >-->


<script type="text/javascript">

    // This variable is used in 'item_category/pop_add.php' and in 'item_heads/pop_add.php' to determine from where the popup is loaded. Because the same popups is loading from 'item_heads/index' also.
    var clsfunc = 'items/add';

    $(document).ready(function() {
        
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