<!--

Notes: 
    
    1.  All popup-box must have a class "popupBox" and its own id. id start with a prefix "pop_" then "ControllerName" then "MethodName".ie:-

    for exampleid = <div id="pop_controllerName_methodName" class="popupBox">   

-->
<!--        Add Owner      -->
<div id="pop_workcentre_registration_details_add" class="popupBox">
    <div class="dv-popupTitle">
        <span class="clossButton popupAction" title="Close Window"> X</span>
        <span class="titleColumn">ADD</span> 
    </div>


    <div class="namespan_box"><span class="namespan" >Add Party's Licence Details</span></div>


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
        $('#pop_workcentre_registration_details_add .save').click(function() {
            //Showing Loading image till ajax responds
            $("#pop_workcentre_registration_details_add .responseMessage").html('<img src="images/ajax-loader.gif" /> Loading....');





            $.post(site_url + "workcentre_registration_details/add", {
                wrd_date: $('#pop_workcentre_registration_details_add #wrd_date').val(),
                wrd_name: $('#pop_workcentre_registration_details_add #wrd_name').val(),
                wrd_address: $('#pop_workcentre_registration_details_add #wrd_address').val(),
                wrd_phone: $('#pop_workcentre_registration_details_add #wrd_phone').val(),
                wrd_email: $('#pop_workcentre_registration_details_add #wrd_email').val(),
                wrd_tin: $('#pop_workcentre_registration_details_add #wrd_tin').val(),
                wrd_licence: $('#pop_workcentre_registration_details_add #wrd_licence').val(),
                wrd_cst: $('#pop_workcentre_registration_details_add #wrd_cst').val()

            }, function(result) {
                if (result)  //If any validation errors
                {
                    if (result == 1)
                    {
                        // Hiding save buttun to prevent the chance for re-entering of data.
                        $('#pop_workcentre_registration_details_add .save').hide();

                        // Retrieving all licence details including recently added.
                        var path = site_url + 'workcentre_registration_details/get_registrations';
                        var options = '';
                        $.getJSON(path, '', function(data) {
                            for (var x = 0; x < data.length; x++) {
                                options += '<option value="' + data[x]['value'] + '">' + data[x]['text'] + '</option>';
                            }
                            // Adding registration details the related dropdown.
                            
                            var selected = $('.reg_id').val();
                            $('.reg_id').html(options);
                            $('.reg_id').val(selected);

                            $("#pop_workcentre_registration_details_add .responseMessage").html('<div class="pop_success"><img src="images/success_1.gif" width="15" height="15"  /> Data saved successfully!!!</div>');

                            // Clossing popup box automatically after 1 seconds.
                            setTimeout(function() {
                                $('#pop_workcentre_registration_details_add').hide()
                            }, 1000);

                        });
                    }
                    else
                        $("#pop_workcentre_registration_details_add .responseMessage").html(result);
                }
            });



        });

    });



    function init_pop_workcentre_registration_details_add() {
        // Setting initial values of elements.
        $('#pop_workcentre_registration_details_add #p_key').val('');
        $('#pop_workcentre_registration_details_add #wrd_date').val(getToday());

        $('#pop_workcentre_registration_details_add #wrd_name').val('');
        $('#pop_workcentre_registration_details_add #wrd_address').val('');
        $('#pop_workcentre_registration_details_add #wrd_phone').val('');
        $('#pop_workcentre_registration_details_add #wrd_email').val('');
        $('#pop_workcentre_registration_details_add #wrd_tin').val('');
        $('#pop_workcentre_registration_details_add #wrd_licence').val('');
        $('#pop_workcentre_registration_details_add #wrd_cst').val('');



        $('#pop_workcentre_registration_details_add #pop_drag').prop('checked', 'true');
        $('#pop_workcentre_registration_details_add #pop_self_close').prop('checked', 'true');
        $('#pop_workcentre_registration_details_add .save').show();
        $("#pop_workcentre_registration_details_add .responseMessage").html('');

        //Making the popup box draggable.
        $("#pop_workcentre_registration_details_add").draggable();
        dragUndrag($("#pop_workcentre_registration_details_add"));
    }




</script>
