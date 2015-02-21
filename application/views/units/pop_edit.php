<!--

Notes: 
    
    1.  All popup-box must have a class "popupBox".

-->

<div id="pop_unit_edit" class="popupBox">
    <div class="dv-popupTitle">
        <span class="clossButton popupAction" title="Close Window"> X</span>
        <span class="titleColumn">EDIT UNITS</span> 
    </div>

    <!--Value of the following element is what the value of '.itm_name_popup' in items/index.php. It will be added by JQuery on loading popup.-->
    <div class="namespan_box"><span class="namespan" ></span></div>



    <!--Value of the following element is what the value of '.itm_id_popup' in items/index.php. It will be added by JQuery on loading popup.-->
    <input type="hidden" id="p_key" value="">


    <table class="unt_tbl">
        <thead>
            <tr>
                <th>Default</th>
                <th>Unit Name</th>
                <th>
                    Relation
        <div style="float:right;margin-top:5px">
            <img src="images/Add button.png" height="15" width="15" id="add_row" title="Add unit"/>
            <img src="images/delete2.png" height="15" width="15" id="remove_row" title="Delete unit"/>
        </div>
        </th>
        </tr>
        </thead>
        <tbody>

            <tr>
                <td>
                    <input type="radio" name="def" class="unit_def" value="0" >
                </td>
                <td>
                    <input type="text" name="unit_name[]" class="unit_name" value="" style="width:95%;">
                </td>
                <td class="td_last">
                    <input type="text" name="unt_relation[]" class="numberOnly unit_rel" value="1" style="width:95%;">
                </td>                                            
            </tr>   

        </tbody>

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


        toggleRemover();
        checkIsCheckedOne();
        disableFirst();

        //Saving data
        $('#pop_unit_edit .save').click(function() {
            //Showing Loading image till ajax responds
            $("#pop_unit_edit .responseMessage").html('<img src="images/ajax-loader.gif" /> Loading....');

            // Disabling whole page background till Ajax respond.
            $.blockUI(); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"
            
            var itm_id = $('#pop_unit_edit #p_key').val();
            var unit_name = $('input[name^=unit_name]');
            var unt_relation = $('input[name^=unt_relation]');

            // create an new object with properties PLN_ID and NUM_PLANS
            var postData = {
                itm_id: itm_id,//$('#pop_unit_edit #p_key').val(),
                def: $('#pop_unit_edit .unit_def:checked').val(),
                unit_name: [], // the unit_name is an array
                unt_relation: [] // the NUM_PLANS is an array
            };

            // loop over the PLN_ID[] input elements and place the value in the postData object
            $.each(unit_name, function(index, el) {
                // push the value to the unit_name array
                postData.unit_name.push($(el).val());
            });

            // loop over the NUM_PLANS[] input elements and place the value in the postData object
            $.each(unt_relation, function(index, el) {
                // push the value to the unt_relation array
                postData.unt_relation.push($(el).val());
            });


            $.post(site_url + "units/edit", postData, function(result) {

                if (result == 1)
                {
                    // Hiding save buttun to prevent the chance for re-entering of data.
                    $('#pop_unit_edit .save').hide();

                    $("#pop_unit_edit .responseMessage").html('<div class="pop_success"><img src="images/success_1.gif" width="15" height="15"  /> Data saved successfully!!!</div>');
                    
                    // Refreshing page automatically after 1 seconds.
                    setTimeout(function() {
                        //location.reload(true);
                        window.location.replace(site_url+'item_units_n_rates/before_add/jurk/'+itm_id);
                    }, 1000);


                }
                else
                    $("#pop_unit_edit .responseMessage").html(result);

                // enabling the whole page after ajax response.
                $(document).ajaxStop($.unblockUI); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"


            });


        });

        $('#pop_unit_edit .unt_tbl .unit_name').keypress(function(e) {
            var index = $(this).closest('#pop_unit_edit .unt_tbl tbody tr').index();
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


        $('#pop_unit_edit .unt_tbl tbody .unit_rel').keypress(function(e) {
            var current_row = $(this).closest('#pop_unit_edit .unt_tbl tbody tr').index();
            var last_row = $('#pop_unit_edit .unt_tbl tbody tr:last').index();
            var next_row = current_row + 1;
            var key = e.keyCode || e.which;
            if (key == 13)  // the enter key code
            {
                if (current_row == last_row)
                    createRow();
                else
                    $('#pop_unit_edit .unt_tbl tbody tr:eq(' + next_row + ')').find('.unit_name').focus();
                e.preventDefault();
                return false;
            }
        });


        $('#pop_unit_edit .unt_tbl #add_row').click(function() {
            createRow();
        });

        $('#pop_unit_edit .unt_tbl #remove_row').click(function() {
            var row_index = $("#pop_unit_edit .unt_tbl tbody tr:last").index();

            // Not the first row.             
            if (row_index != 0)
                $("#pop_unit_edit .unt_tbl tbody tr:last").remove();
            toggleRemover();
            checkIsCheckedOne();
        });

    });

    function toggleRemover()
    {
        if ($("#pop_unit_edit .unt_tbl tbody tr").length <= 1)
            $('#pop_unit_edit .unt_tbl #remove_row').hide();
        else
            $('#pop_unit_edit .unt_tbl #remove_row').show();
    }


    function createRow()
    {
        $('#pop_unit_edit .unt_tbl tbody tr:last').clone(true).insertAfter('#pop_unit_edit .unt_tbl tr:last');
        $("#pop_unit_edit .unt_tbl tbody tr:last input").val('');
        $("#pop_unit_edit .unt_tbl tbody tr:last .unit_name").focus();
        $("#pop_unit_edit .unt_tbl tbody tr:last .dialog-box-container").empty();
        $("#pop_unit_edit .unt_tbl tbody tr:last .unit_rel").prop('disabled', false);
        setDefaultValue();
        toggleRemover();
        checkIsCheckedOne();
    }

    function disableFirst()
    {
        // The first unit is considered as parent. So its 'relation with parent' field is meaningless.
        $("#pop_unit_edit .unt_tbl tbody tr:first .unit_rel").prop('disabled', true);
    }

    function setDefaultValue()
    {
        $('#pop_unit_edit .unt_tbl tbody tr').each(function() {
            var index = $(this).index();
            $(this).find('.unit_def').val(index);
        });
    }

    function checkIsCheckedOne()
    {
        var isChecked = false;
        $('#pop_unit_edit .unt_tbl tbody .unit_def').each(function() {
            if ($(this).prop('checked'))
                isChecked = true;
        });

        // If no radios selected, checks first one.
        if (!isChecked)
            $('#pop_unit_edit .unt_tbl tbody tr:first').find('.unit_def').prop('checked', true);
    }




    function init_pop_unit_edit() {

        // Setting initial values of elements.  
        
        $('#pop_unit_edit .unt_tbl tbody tr:first .unit_name').val('');

        // Atleast one of the '.unit_def' must be checked.
        $('#pop_unit_edit .unt_tbl tbody tr:first .unit_def').prop('checked', true);

        // Removing all rows except first.
        $("#pop_unit_edit .unt_tbl tbody").find("tr:gt(0)").remove();

        // Hiding delete row button.
        toggleRemover();

        $('#pop_unit_edit #pop_drag').prop('checked', 'true');
        $('#pop_unit_edit #pop_self_close').prop('checked', 'true');
        $('#pop_unit_edit .save').show();
        $("#pop_unit_edit .responseMessage").html('');

        //Making the popup box draggable.
        $("#pop_unit_edit").draggable();
        dragUndrag($("#pop_unit_edit"));

        // Initializing Popup-Window settings
        popupSettings($("#pop_unit_edit"));
    }







</script>
<script type="text/javascript" src="plugins/blockui-master/jquery.blockUI.js"></script> 
