<!--

Notes: 
    
    1.  All popup-box must have a class "popupBox" and its own id. id start with a prefix "pop_" then "ControllerName" then "MethodName".ie:-

    for exampleid = <div id="pop_controllerName_methodName" class="popupBox">   

-->
<!--        Add Owner      -->
<div id="pop_workcentre_registration_details_edit" class="popupBox">
    <div class="dv-popupTitle">
        <span class="clossButton popupAction" title="Close Window"> X</span>
        <span class="titleColumn">EDIT REGISTRATION DETAILS</span> 
    </div>


    <div class="namespan_box"><span class="namespan" ></span></div>


    <input type="hidden" id="p_key" value="">

    <table>


        <tr>
            <th>Reg Name: </th>
            <td>
                <input type="text" id="wrd_name" value="">
            </td>
            <th>Address: </th>
            <td>
                <textarea id="wrd_address"></textarea>
            </td>
        </tr>


        <tr>
            <th>Date: </th>
            <td>
                <div class="dateContainer" style="padding: 0px;margin:0px;">
                    <div style="padding-top: 4px;float: left;"><input class="dateField inputDate" readonly="" id="wrd_date" value="" /> </div>
                    <div style="padding-left: 5px;float: right;"><img src="images/calendar.gif"  class="calendarButton"> </div>
                </div>
            </td>

            <th>Phone: </th>
            <td>
                <input type="text" id="wrd_phone" value="">
            </td>
        </tr>




        <tr>

            <th>Email: </th>
            <td>
                <input type="text" id="wrd_email" value="">
            </td>
            <th>Tin: </th>
            <td>
                <input type="text" id="wrd_tin" value="">
            </td>
        </tr>




        <tr>
            <th>CST: </th>
            <td>
                <input type="text" id="wrd_cst" value="">
            </td>
            <th>Licence: </th>
            <td>
                <input type="text" id="wrd_licence" value="">
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
        $('#pop_workcentre_registration_details_edit .save').click(function() {
            
            //Showing Loading image till ajax responds
            $("#pop_workcentre_registration_details_edit .responseMessage").html('<img src="images/ajax-loader.gif" /> Loading....');
            
            // Disabling whole page background till Ajax respond.
            $.blockUI(); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"

            $.post(site_url + "workcentre_registration_details/edit", {
                wrd_id: $('#pop_workcentre_registration_details_edit #p_key').val(),
                wrd_date: $('#pop_workcentre_registration_details_edit #wrd_date').val(),
                wrd_name: $('#pop_workcentre_registration_details_edit #wrd_name').val(),
                wrd_address: $('#pop_workcentre_registration_details_edit #wrd_address').val(),
                wrd_phone: $('#pop_workcentre_registration_details_edit #wrd_phone').val(),
                wrd_email: $('#pop_workcentre_registration_details_edit #wrd_email').val(),
                wrd_tin: $('#pop_workcentre_registration_details_edit #wrd_tin').val(),
                wrd_licence: $('#pop_workcentre_registration_details_edit #wrd_licence').val(),
                wrd_cst: $('#pop_workcentre_registration_details_edit #wrd_cst').val()

            }, function(result) {

                if (result == 1)
                {
                    // Hiding save buttun to prevent the chance for re-entering of data.
                    $('#pop_workcentre_registration_details_edit .save').hide();
                    
                    $("#pop_workcentre_registration_details_edit .responseMessage").html('<div class="pop_success"><img src="images/success_1.gif" width="15" height="15"  /> Data saved successfully!!!</div>');
                    
                    // Refreshing page automatically after 1 seconds.
                    setTimeout(function() {
                        location.reload(true);
                    }, 500);
                }
                else
                    $("#pop_workcentre_registration_details_edit .responseMessage").html(result);
                
                // enabling the whole page after ajax response.
                $(document).ajaxStop($.unblockUI); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"

            });

        });

    });



    function init_pop_workcentre_registration_details_edit() {
    
        // Setting initial values of elements.
        $('#pop_workcentre_registration_details_edit #p_key').val('');
        $('#pop_workcentre_registration_details_edit #wrd_date').val('');
        $('#pop_workcentre_registration_details_edit #wrd_name').val('');
        $('#pop_workcentre_registration_details_edit #wrd_address').val('');
        $('#pop_workcentre_registration_details_edit #wrd_phone').val('');
        $('#pop_workcentre_registration_details_edit #wrd_email').val('');
        $('#pop_workcentre_registration_details_edit #wrd_tin').val('');
        $('#pop_workcentre_registration_details_edit #wrd_licence').val('');
        $('#pop_workcentre_registration_details_edit #wrd_cst').val('');



        $('#pop_workcentre_registration_details_edit #pop_drag').prop('checked', 'true');
        $('#pop_workcentre_registration_details_edit #pop_self_close').prop('checked', 'true');
        $('#pop_workcentre_registration_details_edit .save').show();
        $("#pop_workcentre_registration_details_edit .responseMessage").html('');

        //Making the popup box draggable.
        $("#pop_workcentre_registration_details_edit").draggable();
        dragUndrag($("#pop_workcentre_registration_details_edit"));
        
    }




</script>
