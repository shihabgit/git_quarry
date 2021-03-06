<!--

Notes: 
    
    1.  All popup-box must have a class "popupBox".

-->

<div id="pop_party_vehicles_add" class="popupBox">
    <div class="dv-popupTitle">
        <span class="clossButton popupAction" title="Close Window"> X</span>
        <span class="titleColumn">ADD VEHICLE</span> 
    </div>

    <!--Value of the following element is what the value of '.itm_name_popup' in items/index.php. It will be added by JQuery on loading popup.-->
    <div class="namespan_box"><span class="namespan" ></span></div>



    <!--Value of the following element is what the value of '.itm_id_popup' in items/index.php. It will be added by JQuery on loading popup.-->
    <input type="hidden" id="pvhcl_fk_parties" value="">


    <table class="unt_tbl">

        <tbody>
            
            <tr>
                <th>Name</th>
                <td>
                    <input type="text" id="pvhcl_name" value="" >

                </td> 
                <th>No</th>
                <td>
                    <input type="text" id="pvhcl_no" value="" >
                </td>                                   
            </tr>    


            <tr>
                <th>Length (in inches)</th>
                <td>
                    <input type="text" class="numberOnly" id="pvhcl_length" value="" >
                </td>  
                <th>Breadth (in inches)</th>
                <td>
                    <input type="text" class="numberOnly" id="pvhcl_breadth" value="" >
                </td>                                   
            </tr>    

            <tr>
                <th>Height (in inches)</th>
                <td>
                    <input type="text" class="numberOnly" id="pvhcl_height" value="" >
                </td> 
                <th>XHeight (in inches)</th>
                <td>
                    <input type="text" class="numberOnly" id="pvhcl_xheight" value="" >
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
        $('#pop_party_vehicles_add .save').click(function() {
            //Showing Loading image till ajax responds
            $("#pop_party_vehicles_add .responseMessage").html('<img src="images/ajax-loader.gif" /> Loading....');

            // Disabling whole page background till Ajax respond.
            $.blockUI(); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"


            var postData = {
                pvhcl_fk_parties: $('#pop_party_vehicles_add #pvhcl_fk_parties').val(),
                pvhcl_name: $('#pop_party_vehicles_add #pvhcl_name').val(),
                pvhcl_no: $('#pop_party_vehicles_add #pvhcl_no').val(),
                pvhcl_length: $('#pop_party_vehicles_add #pvhcl_length').val(),
                pvhcl_breadth: $('#pop_party_vehicles_add #pvhcl_breadth').val(),
                pvhcl_height: $('#pop_party_vehicles_add #pvhcl_height').val(),
                pvhcl_xheight: $('#pop_party_vehicles_add #pvhcl_xheight').val()
            };

            $.post(site_url + "party_vehicles/add", postData, function(result) {

                if (result == 1)
                {
                    // Hiding save buttun to prevent the chance for re-entering of data.
                    $('#pop_party_vehicles_add .save').hide();

                    $("#pop_party_vehicles_add .responseMessage").html('<div class="pop_success"><img src="images/success_1.gif" width="15" height="15"  /> Data saved successfully!!!</div>');

                    // Refreshing page automatically after 1 seconds.
                    setTimeout(function() {
                        location.reload(true);
                    }, 1000);


                }
                else
                    $("#pop_party_vehicles_add .responseMessage").html(result);

                // enabling the whole page after ajax response.
                $(document).ajaxStop($.unblockUI); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"


            });


        });



    });

    //pvhcl_fk_parties, pvhcl_name, pvhcl_no, pvhcl_length, pvhcl_breadth, pvhcl_height, pvhcl_xheight, pvhcl_status 

    function init_pop_party_vehicles_add() {

        // Setting initial values of elements.
        $('#pop_party_vehicles_add #pvhcl_fk_parties').val('');
        $('#pop_party_vehicles_add #pvhcl_name').val('');
        $('#pop_party_vehicles_add #pvhcl_no').val('');
        $('#pop_party_vehicles_add #pvhcl_length').val('');
        $('#pop_party_vehicles_add #pvhcl_breadth').val('');
        $('#pop_party_vehicles_add #pvhcl_height').val('');
        $('#pop_party_vehicles_add #pvhcl_xheight').val('');

        $('#pop_party_vehicles_add #pop_drag').prop('checked', 'true');
        $('#pop_party_vehicles_add #pop_self_close').prop('checked', 'true');
        $('#pop_party_vehicles_add .save').show();
        $("#pop_party_vehicles_add .responseMessage").html('');

        //Making the popup box draggable.
        $("#pop_party_vehicles_add").draggable();
        dragUndrag($("#pop_party_vehicles_add"));

    }







</script>
<script type="text/javascript" src="plugins/blockui-master/jquery.blockUI.js"></script> 
