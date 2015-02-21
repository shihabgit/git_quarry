<!--

Notes: 
    
    1.  All popup-box must have a class "popupBox".

-->

<div id="pop_party_vehicle_rents_edit" class="popupBox">
    <div class="dv-popupTitle">
        <span class="clossButton popupAction" title="Close Window"> X</span>
        <span class="titleColumn"> EDIT FREIGHT CHARGES</span> 
    </div>

    <!--Value of the following element is what the value of '.itm_name_popup' in items/index.php. It will be added by JQuery on loading popup.-->
    <div class="freight_vehicle" style="font-family: PlateiaBold; font-size: 14pt; font-weight: bolder; text-align: center; color: #00F;"></div>
    <div class="namespan_box"><span class="namespan" ></span></div>



    <!--Value of the following element is what the value of '.itm_id_popup' in items/index.php. It will be added by JQuery on loading popup.-->
    <input type="hidden" id="pvr_id" value="">

    <table class="unt_tbl">

        <tbody>

            
            <tr>
                
                <th>Freight Charge</th>
                <td>
                    <input type="text" class="intOnly" id="pvr_rent" value="" ><br>
                   <input type="checkbox" id="pvr_add_rent"> Add freight charge to the bill amount. 
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
        $('#pop_party_vehicle_rents_edit .save').click(function() {
            
            //Showing Loading image till ajax responds
            $("#pop_party_vehicle_rents_edit .responseMessage").html('<img src="images/ajax-loader.gif" /> Loading....');

            // Disabling whole page background till Ajax respond.
            $.blockUI(); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"
            
            var pvr_add_rent = $('#pop_party_vehicle_rents_edit #pvr_add_rent').prop('checked') ? 1 : 2;

            var postData = {
                pvr_id: $('#pop_party_vehicle_rents_edit #pvr_id').val(),
                pvr_rent: $('#pop_party_vehicle_rents_edit #pvr_rent').val(),
                pvr_add_rent: pvr_add_rent
            };

            $.post(site_url + "party_vehicle_rents/edit", postData, function(result) {

                if (result == 1)
                {
                    // Hiding save buttun to prevent the chance for re-entering of data.
                    $('#pop_party_vehicle_rents_edit .save').hide();

                    $("#pop_party_vehicle_rents_edit .responseMessage").html('<div class="pop_success"><img src="images/success_1.gif" width="15" height="15"  /> Data saved successfully!!!</div>');

                    // Refreshing page automatically after 0.5 seconds.
                    setTimeout(function() {
                        location.reload(true);
                    }, 500);
                }
                else
                    $("#pop_party_vehicle_rents_edit .responseMessage").html(result);

                // enabling the whole page after ajax response.
                $(document).ajaxStop($.unblockUI); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"
            });
        });
    });



    function init_pop_party_vehicle_rents_edit() {


        // Setting initial values of elements.
        $('#pop_party_vehicle_rents_edit #pvr_id').val('');
        $('#pop_party_vehicle_rents_edit #pvr_rent').val('');
        $('#pop_party_vehicle_rents_edit #pvr_add_rent').prop('checked',false);

        $('#pop_party_vehicle_rents_edit #pop_drag').prop('checked', 'true');
        $('#pop_party_vehicle_rents_edit #pop_self_close').prop('checked', 'true');
        $('#pop_party_vehicle_rents_edit .save').show();
        $("#pop_party_vehicle_rents_edit .responseMessage").html('');

        //Making the popup box draggable.
        $("#pop_party_vehicle_rents_edit").draggable();
        dragUndrag($("#pop_party_vehicle_rents_edit"));

    }



</script>
<script type="text/javascript" src="plugins/blockui-master/jquery.blockUI.js"></script> 
