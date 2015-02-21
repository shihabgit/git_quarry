<!--

Notes: 
    
    1.  All popup-box must have a class "popupBox" and its own id. id start with a prefix "pop_" then "ControllerName" then "MethodName".ie:-

    for exampleid = <div id="pop_controllerName_methodName" class="popupBox">   

-->
<!--        Add Owner      -->
<div id="pop_owners_add" class="popupBox">
    <div class="dv-popupTitle">
        <span class="clossButton popupAction" title="Close Window"> X</span>
        <span class="titleColumn">ADD</span> 
    </div>


    <div class="namespan_box"><span class="namespan" >Create New Owner</span></div>


    <input type="hidden" id="p_key" value="">

    <table>


        <tr>
            <th>Name: </th>
            <td>
                <input type="text" id="ownr_name" value="">
            </td>
        </tr>
        <tr>
            <th>Address: </th>
            <td>
                <input type="text" id="ownr_address" value="">
            </td>
        </tr>
        <tr>
            <th>Phone No:</th>
            <td>
                <input type="text" class="intOnly" id="ownr_phone" value="">
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
            <img src="images/save.png" class="save"> 

        </div>
    </div>
    <div class="clear_boath"></div>
    <p class="responseMessage"></p>
</div>




<script type="text/javascript">

    $(document).ready(function() {

        //Saving data
        $('#pop_owners_add .save').click(function() {
            //Showing Loading image till ajax responds
            $("#pop_owners_add .responseMessage").html('<img src="images/ajax-loader.gif" /> Loading....');

            $.post(site_url + "owners/add", {
                ownr_name: $('#pop_owners_add #ownr_name').val(),
                ownr_address: $('#pop_owners_add #ownr_address').val(),
                ownr_phone: $('#pop_owners_add #ownr_phone').val(),
                ownr_date: getToday(),
                ownr_status: 1
            }, function(result) {
                if (result)  //If any validation errors
                {
                    if (result == 1)
                    {
                        // Hiding save buttun to prevent the chance for re-entering of data.
                        $('#pop_owners_add .save').hide();

                        // Listing newly created firm in the dropdown.
                        setOptions('li.rental_list #rntdt_fk_owners', 'owners/get_active_owners');

                        $("#pop_owners_add .responseMessage").html('<div class="pop_success"><img src="images/success_1.gif" width="15" height="15"  /> Data saved successfully!!!</div>');

                        // Clossing popup box automatically after 1 seconds.
                        setTimeout(function() {
                            $('#pop_owners_add').hide()
                        }, 1000);

                    }
                    else
                        $("#pop_owners_add .responseMessage").html(result);
                }
            });



        });

    });


    function init_pop_owners_add() {
        // Setting initial values of elements.
        $('#pop_owners_add .namespan_box .namespan').html('Create New Owner');
        $('#pop_owners_add #p_key').val('');
        $('#pop_owners_add #ownr_name').val('');
        $('#pop_owners_add #ownr_address').val('');
        $('#pop_owners_add #ownr_phone').val('');

        $('#pop_owners_add #pop_drag').prop('checked', 'true');
        $('#pop_owners_add #pop_self_close').prop('checked', 'true');
        $('#pop_owners_add .save').show();
        $("#pop_owners_add .responseMessage").html('');

        //Making the popup box draggable.
        $("#pop_owners_add").draggable();
        dragUndrag($("#pop_owners_add"));

        // Initializing Popup-Window settings
        popupSettings($("#pop_owners_add"));
    }

</script>
