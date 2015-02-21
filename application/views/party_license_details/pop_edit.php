<!--

Notes: 
    
    1.  All popup-box must have a class "popupBox".

-->

<div id="pop_party_license_details_edit" class="popupBox">
    <div class="dv-popupTitle">
        <span class="clossButton popupAction" title="Close Window"> X</span>
        <span class="titleColumn">EDIT LICENCE DETAILS</span> 
    </div>

    <!--Value of the following element is what the value of '.itm_name_popup' in items/index.php. It will be added by JQuery on loading popup.-->
    <div class="namespan_box"><span class="namespan" ></span></div>



    <!--Value of the following element is what the value of '.itm_id_popup' in items/index.php. It will be added by JQuery on loading popup.-->
    <input type="hidden" id="p_key" value="">


    <table class="unt_tbl">
        
        <tbody>
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

        //Saving data
        $('#pop_party_license_details_edit .save').click(function() {
            //Showing Loading image till ajax responds
            $("#pop_party_license_details_edit .responseMessage").html('<img src="images/ajax-loader.gif" /> Loading....');

            // Disabling whole page background till Ajax respond.
            $.blockUI(); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"


            var postData = {
                pld_id: $('#pop_party_license_details_edit #p_key').val(),
                pld_date : $('#pop_party_license_details_edit #pld_date').val(),
                pld_firm_name : $('#pop_party_license_details_edit #pld_firm_name').val(),
                pld_address : $('#pop_party_license_details_edit #pld_address').val(),
                pld_phone : $('#pop_party_license_details_edit #pld_phone').val(),
                pld_email : $('#pop_party_license_details_edit #pld_email').val(),
                pld_tin : $('#pop_party_license_details_edit #pld_tin').val(),
                pld_licence : $('#pop_party_license_details_edit #pld_licence').val(),
                pld_cst : $('#pop_party_license_details_edit #pld_cst').val()
            };
            
            $.post(site_url + "party_license_details/edit", postData, function(result) {

                if (result == 1)
                {
                    // Hiding save buttun to prevent the chance for re-entering of data.
                    $('#pop_party_license_details_edit .save').hide();

                    $("#pop_party_license_details_edit .responseMessage").html('<div class="pop_success"><img src="images/success_1.gif" width="15" height="15"  /> Data saved successfully!!!</div>');
                    
                    // Refreshing page automatically after 1 seconds.
                    setTimeout(function() {
                        location.reload(true);
                    }, 1000);


                }
                else
                    $("#pop_party_license_details_edit .responseMessage").html(result);

                // enabling the whole page after ajax response.
                $(document).ajaxStop($.unblockUI); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"


            });


        });

        

    });

    //pld_name pld_date pld_phone  pld_email  

    function init_pop_party_license_details_edit() {
    
        // Setting initial values of elements.
        $('#pop_party_license_details_edit #pld_name').val('');
        $('#pop_party_license_details_edit #pld_date').val('');
        $('#pop_party_license_details_edit #pld_phone').val('');
        $('#pop_party_license_details_edit #pld_email').val('');

        $('#pop_party_license_details_edit #pop_drag').prop('checked', 'true');
        $('#pop_party_license_details_edit #pop_self_close').prop('checked', 'true');
        $('#pop_party_license_details_edit .save').show();
        $("#pop_party_license_details_edit .responseMessage").html('');

        //Making the popup box draggable.
        $("#pop_party_license_details_edit").draggable();
        dragUndrag($("#pop_party_license_details_edit"));

    }







</script>
<script type="text/javascript" src="plugins/blockui-master/jquery.blockUI.js"></script> 
