<!--

Notes: 
    
    1.  All popup-box must have a class "popupBox" and its own id. id start with a prefix "pop_" then "ControllerName" then "MethodName".ie:-

    for exampleid = <div id="pop_controllerName_methodName" class="popupBox">   

-->
<!--        Add Owner      -->
<div id="pop_party_license_details_add" class="popupBox">
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
                <input type="text" id="pld_firm_name" value="">
            </td>
             <th>Address: </th>
            <td>
                <textarea id="pld_address"></textarea>
            </td>
        </tr>


        <tr>
            <th>Date: </th>
            <td>
                <div class="dateContainer" style="padding: 0px;margin:0px;">
                    <div style="padding-top: 4px;float: left;"><input class="dateField inputDate" readonly="" id="pld_date" value="" /> </div>
                    <div style="padding-left: 5px;float: right;"><img src="images/calendar.gif"  class="calendarButton"> </div>
                </div>
            </td>
            
            <th>Phone: </th>
            <td>
                <input type="text" id="pld_phone" value="">
            </td>
        </tr>




        <tr>
            
            <th>Email: </th>
            <td>
                <input type="text" id="pld_email" value="">
            </td>
            <th>Tin: </th>
            <td>
                <input type="text" id="pld_tin" value="">
            </td>
        </tr>




        <tr>
            <th>CST: </th>
            <td>
                <input type="text" id="pld_cst" value="">
            </td>
            <th>Licence: </th>
            <td>
                <input type="text" id="pld_licence" value="">
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
        $('#pop_party_license_details_add .save').click(function() {
            //Showing Loading image till ajax responds
            $("#pop_party_license_details_add .responseMessage").html('<img src="images/ajax-loader.gif" /> Loading....');





            $.post(site_url + "party_license_details/add", {
                pld_date: $('#pop_party_license_details_add #pld_date').val(),
                pld_firm_name: $('#pop_party_license_details_add #pld_firm_name').val(),
                pld_address: $('#pop_party_license_details_add #pld_address').val(),
                pld_phone: $('#pop_party_license_details_add #pld_phone').val(),
                pld_email: $('#pop_party_license_details_add #pld_email').val(),
                pld_tin: $('#pop_party_license_details_add #pld_tin').val(),
                pld_licence: $('#pop_party_license_details_add #pld_licence').val(),
                pld_cst: $('#pop_party_license_details_add #pld_cst').val()

            }, function(result) {
                if (result)  //If any validation errors
                {
                    if (result == 1)
                    {
                        // Hiding save buttun to prevent the chance for re-entering of data.
                        $('#pop_party_license_details_add .save').hide();

                        // Retrieving all licence details including recently added.
                        var path = site_url + 'party_license_details/get_free_licenses';
                        var options = '';
                        $.getJSON(path, '', function(data) {
                            for (var x = 0; x < data.length; x++) {
                                options += '<option value="' + data[x]['value'] + '">' + data[x]['text'] + '</option>';
                            }
                            // Adding license details in all related dropdowns including recently created .
                            $('.pop_pdst_fk_party_license_details').each(function() {
                                var dropdown = $(this);
                                var selected = dropdown.val();
                                dropdown.html(options);
                                dropdown.val(selected);
                            });
                        });

                        $("#pop_party_license_details_add .responseMessage").html('<div class="pop_success"><img src="images/success_1.gif" width="15" height="15"  /> Data saved successfully!!!</div>');

                        // Clossing popup box automatically after 1 seconds.
                        setTimeout(function() {
                            $('#pop_party_license_details_add').hide()
                        }, 1000);

                    }
                    else
                        $("#pop_party_license_details_add .responseMessage").html(result);
                }
            });



        });

    });



    function init_pop_party_license_details_add() {
        // Setting initial values of elements.
        $('#pop_party_license_details_add #p_key').val('');
        $('#pop_party_license_details_add #pld_date').val(getToday());

        $('#pop_party_license_details_add #pld_firm_name').val('');
        $('#pop_party_license_details_add #pld_address').val('');
        $('#pop_party_license_details_add #pld_phone').val('');
        $('#pop_party_license_details_add #pld_email').val('');
        $('#pop_party_license_details_add #pld_tin').val('');
        $('#pop_party_license_details_add #pld_licence').val('');
        $('#pop_party_license_details_add #pld_cst').val('');



        $('#pop_party_license_details_add #pop_drag').prop('checked', 'true');
        $('#pop_party_license_details_add #pop_self_close').prop('checked', 'true');
        $('#pop_party_license_details_add .save').show();
        $("#pop_party_license_details_add .responseMessage").html('');

        //Making the popup box draggable.
        $("#pop_party_license_details_add").draggable();
        dragUndrag($("#pop_party_license_details_add"));
    }




</script>
