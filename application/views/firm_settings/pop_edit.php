<!--

Notes: 
    
    1.  All popup-box must have a class "popupBox" and its own id. id start with a prefix "pop_" then "ControllerName" then "MethodName".ie:-

    for exampleid = <div id="pop_controllerName_methodName" class="popupBox">   

-->
<!--        Add Owner      -->
<div id="pop_firm_settings_edit" class="popupBox">
    <div class="dv-popupTitle">
        <span class="clossButton popupAction" title="Close Window"> X</span>
        <span class="titleColumn">EDIT FIRM SETTINGS</span> 
    </div>

    <div class="namespan_box"><span class="namespan" ></span></div>



    <input type="hidden" id="p_key" value="">

    <table>
        <tr>
            <th>Value: </th>
            <td>
                <select id="frmset_value"style="width:150px;"></select><br>
                <input type="checkbox" id="apply_to_all_firms"> Apply to all firms.
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
        $('#pop_firm_settings_edit .save').click(function() {

            //Showing Loading image till ajax responds
            $("#pop_firm_settings_edit .responseMessage").html('<img src="images/ajax-loader.gif" /> Loading....');
         
 
            $.ajax({
                url: site_url + "firm_settings/edit",
                data: {
                    frmset_value: $('#pop_firm_settings_edit #frmset_value').val(),
                    frmset_id: $('#pop_firm_settings_edit #p_key').val(),
                    apply_to_all_firms : $('#pop_firm_settings_edit #apply_to_all_firms').prop('checked')
                },
                type: 'post',
                success: function(result) {
                    //alert(result);
                    if (result == 1)
                    {
                        // Hiding save buttun to prevent the chance for re-entering of data.
                        $('#pop_firm_settings_edit .save').hide();

                        var msg = '<div class="pop_success"><img src="images/success_1.gif" width="15" height="15"/> Data saved successfully!!!</div>';

                        $("#pop_firm_settings_edit .responseMessage").html(msg);

                        // Refresh browser 
                        setTimeout(function() {
                            location.reload();
                        }, 500);
                    }
                    else
                        $("#pop_firm_settings_edit .responseMessage").html(result);

                }
            });

        });

    });


    function init_pop_firm_settings_edit(action) {
        // Setting initial values of elements.                

        
        $('#pop_firm_settings_edit #frmset_value').val('');
        $('#pop_firm_settings_edit #apply_to_all_firms').prop('checked', false);

        $('#pop_firm_settings_edit #pop_drag').prop('checked', 'true');
        $('#pop_firm_settings_edit #pop_self_close').prop('checked', 'true');
        $('#pop_firm_settings_edit .save').show();
        $("#pop_firm_settings_edit .responseMessage").html('');

        //Making the popup box draggable.
        $("#pop_firm_settings_edit").draggable();
        dragUndrag($("#pop_firm_settings_edit"));

        // Initializing Popup-Window settings
        popupSettings($("#pop_firm_settings_edit"));
    }







</script>
