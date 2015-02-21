<!--

Notes: 
    
    1.  All popup-box must have a class "popupBox" and its own id. id start with a prefix "pop_" then "ControllerName" then "MethodName".ie:-

    for exampleid = <div id="pop_controllerName_methodName" class="popupBox">   

-->
<!--        Add Owner      -->
<div id="pop_settings_add" class="popupBox">
    <div class="dv-popupTitle">
        <span class="clossButton popupAction" title="Close Window"> X</span>
        <span class="titleColumn">ADD FIRM SETTINGS</span> 
    </div>

    <div class="namespan_box"><span class="namespan" >Firm: <?php echo strtoupper($this->firm_name) ?></span></div>



    <input type="hidden" id="p_key" value="">

    <table>


        <tr>
            <th>Title: </th>
            <td>
                <input type="text" id="settings[set_title]" value="" style="width:200px;">
            </td>
        </tr>

        <tr>
            <th>Key: </th>
            <td>
                <input type="text" id="settings[set_key]" value="" style="width:200px;">
            </td>
        </tr>

        <tr>
            <th>Default: </th>
            <td>
                <input type="text" id="settings[set_default_value]" value=""  style="width:70px;">
            </td>
        </tr>


        <tr>
            <th>Value: </th>
            <td>
                <input type="text" id="firm_settings[frmset_value]" value="" style="width:70px;">
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
        $('#pop_settings_add .save').click(function() {

            //Showing Loading image till ajax responds
            $("#pop_settings_add .responseMessage").html('<img src="images/ajax-loader.gif" /> Loading....');



            var settings = {
                //You have to escape the brackets with \\ if the id is array.
                set_title: $('#pop_settings_add #settings\\[set_title\\]').val(),
                set_key: $('#pop_settings_add #settings\\[set_key\\]').val(),
                set_default_value: $('#pop_settings_add #settings\\[set_default_value\\]').val()
            };

            var firm_settings = {
                frmset_value: $('#pop_settings_add #firm_settings\\[frmset_value\\]').val()
            };

            var apply_to_all_firms = $('#pop_settings_add #apply_to_all_firms').prop('checked');

            $.ajax({
                url: site_url + "settings/add",
                data: {settings: settings, firm_settings: firm_settings, apply_to_all_firms: apply_to_all_firms},
                type: 'post',
                success: function(result) {
                    //alert(result);
                    if (result == 1)
                    {
                        // Hiding save buttun to prevent the chance for re-entering of data.
                        $('#pop_settings_add .save').hide();

                        var msg = '<div class="pop_success"><img src="images/success_1.gif" width="15" height="15"/> Data saved successfully!!!</div>';

                        $("#pop_settings_add .responseMessage").html(msg);

                        // Refresh browser 
                        setTimeout(function() {
                            location.reload(true);
                        }, 1000);
                    }
                    else
                        $("#pop_settings_add .responseMessage").html(result);

                }
            });

        });

    });


    function init_pop_settings_add(action) {
        // Setting initial values of elements.                

        //You have to escape the brackets with \\ if the id is array.
        $('#pop_settings_add #settings\\[set_title\\]').val('');
        $('#pop_settings_add #settings\\[set_key\\]').val('');
        $('#pop_settings_add #settings\\[set_default_value\\]').val('');
        $('#pop_settings_add #firm_settings\\[frmset_value\\]').val('');

        $('#pop_settings_add #apply_to_all_firms').prop('checked', 'true');

        $('#pop_settings_add #pop_drag').prop('checked', 'true');
        $('#pop_settings_add #pop_self_close').prop('checked', 'true');
        $('#pop_settings_add .save').show();
        $("#pop_settings_add .responseMessage").html('');

        //Making the popup box draggable.
        $("#pop_settings_add").draggable();
        dragUndrag($("#pop_settings_add"));

        // Initializing Popup-Window settings
        popupSettings($("#pop_settings_add"));
    }







</script>
