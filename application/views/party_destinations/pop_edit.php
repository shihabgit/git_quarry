<!--

Notes: 
    
    1.  All popup-box must have a class "popupBox".

-->

<div id="pop_party_destinations_edit" class="popupBox">
    <div class="dv-popupTitle">
        <span class="clossButton popupAction" title="Close Window"> X</span>
        <span class="titleColumn">EDIT DESTINATIONS</span> 
    </div>

    <!--Value of the following element is what the value of '.itm_name_popup' in items/index.php. It will be added by JQuery on loading popup.-->
    <div class="namespan_box"><span class="namespan" ></span></div>



    <!--Value of the following element is what the value of '.itm_id_popup' in items/index.php. It will be added by JQuery on loading popup.-->
    <input type="hidden" id="p_key" value="">


    <table class="unt_tbl">

        <tbody>


            <tr> 
                <th>Party</th>
                <td>
                    <select id="pdst_fk_parties">
                        <?php echo get_options2($party_opt, '', TRUE, 'Select'); ?>
                    </select>
                </td> 

              
                <th>Reg Name</th>
                <td>
                    <select id="pdst_fk_party_license_details" > 
                        <?php echo get_options2($license_options, '', true, '--- select ---'); ?>
                    </select>

                    <div class="ajaxLoaderContainer"> 
                        <img src="images/ajax-loader2.gif"> 
                        <img src="images/ajax-loader2.gif"> 
                    </div>  
                </td>  
            </tr> 
            <tr> 
                <th>Destination Name</th>
                <td>
                    <input type="text" id="pdst_name" value="" >
                </td> 
                <th>Date</th>
                <td>
                    <div class="dateContainer" style="padding: 0px;margin:0px;">
                        <div style="padding-top: 4px;float: left;">
                            <input class="dateField inputDate" readonly="" id="pdst_date" value="" /> 
                        </div>
                        <div style="padding-left: 5px;float: right;"><img src="images/calendar.gif"  class="calendarButton"> </div>
                    </div>
                </td>                    
            </tr>  
            
            
            <tr>
                <th>Phone</th>
                <td>
                    <input type="text" id="pdst_phone" value="" >
                </td>     
                <th>Email</th>
                <td>
                    <input type="text" id="pdst_email" value="" >
                </td>                                   
            </tr>  
            <tr>
                <th colspan="2">Category</th>
                <td colspan="2">
                    <input type="radio" name="pdst_category" id="pdst_category" value="1" > Supplier 
                    <input type="radio" name="pdst_category" id="pdst_category" value="2" > Customer 
                    <input type="radio" name="pdst_category" id="pdst_category" value="3" > Both 
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


        afterAjax();

        //Saving data
        $('#pop_party_destinations_edit .save').click(function() {
            //Showing Loading image till ajax responds
            $("#pop_party_destinations_edit .responseMessage").html('<img src="images/ajax-loader.gif" /> Loading....');
            // Disabling whole page background till Ajax respond.
            $.blockUI(); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"

            var postData = {
                pdst_id: $('#pop_party_destinations_edit #p_key').val(),
                pdst_date: $('#pop_party_destinations_edit #pdst_date').val(),
                pdst_fk_party_license_details: $('#pop_party_destinations_edit #pdst_fk_party_license_details').val(),
                pdst_fk_parties: $('#pop_party_destinations_edit #pdst_fk_parties').val(),
                pdst_name: $('#pop_party_destinations_edit #pdst_name').val(),
                pdst_phone: $('#pop_party_destinations_edit #pdst_phone').val(),
                pdst_email: $('#pop_party_destinations_edit #pdst_email').val(),
                pdst_category: $('#pop_party_destinations_edit #pdst_category:checked').val()
            };
            $.post(site_url + "party_destinations/edit", postData, function(result) {

                if (result == 1)
                {
                    // Hiding save buttun to prevent the chance for re-entering of data.
                    $('#pop_party_destinations_edit .save').hide();
                    $("#pop_party_destinations_edit .responseMessage").html('<div class="pop_success"><img src="images/success_1.gif" width="15" height="15"  /> Data saved successfully!!!</div>');
                    // Refreshing page automatically after 1 seconds.
                    setTimeout(function() {
                        location.reload(true);
                    }, 1000);
                }
                else
                    $("#pop_party_destinations_edit .responseMessage").html(result);
                // enabling the whole page after ajax response.
                $(document).ajaxStop($.unblockUI); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"


            });
        });
    });
    //pty_name pty_date pty_phone  pty_email  

    function init_pop_party_destinations_edit() {

        // Setting initial values of elements.
        
        $('#pop_party_destinations_edit #pdst_fk_parties').val('');
        $('#pop_party_destinations_edit #pdst_fk_party_license_details').val('');
        $('#pop_party_destinations_edit #pdst_name').val('');
        $('#pop_party_destinations_edit #pdst_date').val('');
        $('#pop_party_destinations_edit #pdst_phone').val('');
        $('#pop_party_destinations_edit #pdst_email').val('');        
        
        $('#pop_party_destinations_edit #pop_drag').prop('checked', 'true');
        $('#pop_party_destinations_edit #pop_self_close').prop('checked', 'true');
        $('#pop_party_destinations_edit .save').show();
        $("#pop_party_destinations_edit .responseMessage").html('');
        //Making the popup box draggable.
        $("#pop_party_destinations_edit").draggable();
        dragUndrag($("#pop_party_destinations_edit"));
    }

    $('#pop_party_destinations_edit #pdst_fk_parties').change(function() {

        var msg = 'If you change the Party, all accounts regarding to this destination will be converted to new party\'s account.';

        if (!confirm(msg))
            return;
        setLicense($(this).val());
    });

    function setLicense(pty_id, sel)
    {
        $('#pop_party_destinations_edit #pdst_fk_party_license_details').html('<option value="">*** No Data ***</option>');
        if (!pty_id)
            return;
        
        var path = site_url + 'party_license_details/getAvailableLicenses';
        beforeAjax();
        $.getJSON(path, {pty_id: pty_id}, function(data) {
            var options = '';
            for (var x = 0; x < data.length; x++) {
                options += '<option value="' + data[x]['value'] + '">' + data[x]['text'] + '</option>';
            }
            $('#pop_party_destinations_edit #pdst_fk_party_license_details').html(options);
            if (sel)
            {
                $('#pop_party_destinations_edit #pdst_fk_party_license_details').val(sel);
            }
            afterAjax();
        });
    }

    function beforeAjax()
    {
        $('#pdst_fk_party_license_details').hide();
        $('.ajaxLoaderContainer').show();
    }

    function afterAjax()
    {
        $('#pdst_fk_party_license_details').show();
        $('.ajaxLoaderContainer').hide();
    }

</script>
<script type="text/javascript" src="plugins/blockui-master/jquery.blockUI.js"></script> 
