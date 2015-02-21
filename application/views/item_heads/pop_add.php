<!--

Notes: 
    
    1.  All popup-box must have a class "popupBox" and its own id. id start with a prefix "pop_" then "ControllerName" then "MethodName".ie:-

    for exampleid = <div id="pop_controllerName_methodName" class="popupBox">   

-->
<!--        Add Owner      -->
<div id="pop_head_add" class="popupBox">
    <div class="dv-popupTitle">
        <span class="clossButton popupAction" title="Close Window"> X</span>
        <span class="titleColumn">ADD ITEM HEAD</span> 
    </div>

    <!--<div class="namespan_box"><span class="namespan" >Firm: <?php echo strtoupper($this->firm_name) ?></span></div>-->



    <input type="hidden" id="p_key" value="">

    <table>

        <tr>
            <th>Category: </th>
            <td>
                <select id="itmcat_id" name="itmcat_id" style="width:150px;">
                    <?php echo get_options2($itmcats, ifSet('itmcat_id'), true, '--- select ---'); ?>
                </select>
            </td>
        </tr>
        <tr>
            <th>Name: </th>
            <td>
                <input type="text" id="itmhd_name" value="" style="width:150px;">
            </td>
        </tr>

    </table>
    <div class="dragSaveBox">
        <div class="dragColumn">
            <input type="checkbox" id="pop_drag"> Drag 
            <input type="checkbox" id="pop_self_close"> Self Close
        </div>
        <div class="saveColumn">
        <!--<input type="button" class="btn"  title="Save Data"  value="SAVE">-->
            <img src="images/save.png" class="save" title="Save Data"> 

        </div>
    </div>
    <div class="clear_boath"></div>
    <p class="responseMessage"></p>
</div>




<script type="text/javascript">



    $(document).ready(function() {

        //Saving data
        $('#pop_head_add .save').click(function() {

            //Showing Loading image till ajax responds
            $("#pop_head_add .responseMessage").html('<img src="images/ajax-loader.gif" /> Loading....');

            // Disabling whole page background till Ajax respond.
            $.blockUI(); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"



            $.post(site_url + "item_heads/add", {
                itmhd_fk_item_category: $('#pop_head_add #itmcat_id').val(),
                itmhd_name: $('#pop_head_add #itmhd_name').val(),
                itmhd_status: 1
            }, function(result) {

                if (result == 1)
                {
                    // Hiding save buttun to prevent the chance for re-entering of data.
                    $('#pop_head_add .save').hide();

                    $("#pop_head_add .responseMessage").html('<div class="pop_success"><img src="images/success_1.gif" width="15" height="15"  /> Data saved successfully!!!</div>');


                    // Following code is for when popup is used with items/add. 
                    // The variable clsfunc is defined @ from where the popup was loaded.
                    if (clsfunc == 'items/add')
                    {
                        $('.sec_table #itmcat_id').val(0);
                        $('.sec_table #itm_fk_item_head').val(0);

                        // Clossing popup box automatically after 1 seconds.
                        setTimeout(function() {
                            $('#pop_head_add').hide();
                        }, 1000);
                    }

                    // Following code is for when popup is used with item_heads/index.
                    else if (clsfunc == 'item_heads/index')
                    {
                        // Refreshing page automatically after 1 seconds.
                        setTimeout(function() {
                            location.reload(true);
                        }, 1000);
                    }

                }
                else
                    $("#pop_head_add .responseMessage").html(result);
                // enabling the whole page after ajax response.
                $(document).ajaxStop($.unblockUI); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"


            });


        });

    });


    function init_pop_head_add(action) {
        // Setting initial values of elements.                

        //You have to escape the brackets with \\ if the id is array.
        $('#pop_head_add #itmhd_name').val('');

        $('#pop_head_add #pop_drag').prop('checked', 'true');
        $('#pop_head_add #pop_self_close').prop('checked', 'true');
        $('#pop_head_add .save').show();
        $("#pop_head_add .responseMessage").html('');

        //Making the popup box draggable.
        $("#pop_head_add").draggable();
        dragUndrag($("#pop_head_add"));

        // Initializing Popup-Window settings
        popupSettings($("#pop_head_add"));
    }







</script>
<script type="text/javascript" src="plugins/blockui-master/jquery.blockUI.js"></script> 
