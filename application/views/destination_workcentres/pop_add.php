<!--

Notes: 
    
    1.  All popup-box must have a class "popupBox".

-->
<div id="pop_party_availability_add" class="popupBox">
    <div class="dv-popupTitle">
        <span class="clossButton popupAction" title="Close Window"> X</span>
        <span class="titleColumn">ADD TO WORKCENTRE</span> 
    </div>

    <!--Value of the following element is what the value of '.itm_name_popup' in items/index.php. It will be added by JQuery on loading popup.-->
    <div class="namespan_box"><span class="namespan" ></span></div>



    <!--Value of the following element is what the value of '.itm_id_popup' in items/index.php. It will be added by JQuery on loading popup.-->
    <input type="hidden" id="dwc_fk_parties" value="">


    <div style="margin-bottom: 10px;">
        <div style="float:left;">Destination</div> 
        <div style="float:left;padding-left: 20px;">
            <select id="dwc_fk_party_destinations"  style="width: 220px" class="nextInput selunit">';
                <?php echo get_options2(array()); ?>
            </select>

            <div class="ajaxLoaderContainer"> 
                <img src="images/ajax-loader2.gif"> 
                <img src="images/ajax-loader2.gif"> 
            </div>  
        </div>
        <div class="clear_boath"></div>
    </div>
    <div style="text-align: center;margin: 10px 0px;" class="showAvailability"></div>



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

    function read_input(ele_name)
    {
        var arr = [];
        $('#pop_party_availability_add .' + ele_name).each(function() {

            if ($(this).closest('tr').find('.wcntr_id').prop('checked'))
            {
                var index = $(this).prop('name').match(/\[(.*?)\]/)[1];
                arr[index] = $(this).val();
            }

        });
        return arr;
    }

    function read_radio(ele_name)
    {
        var arr = [];
        $('#pop_party_availability_add .' + ele_name).each(function() {

            if (($(this).prop('checked')) && ($(this).closest('tr').find('.wcntr_id').prop('checked')))
            {
                var index = $(this).prop('name').match(/\[(.*?)\]/)[1];
                arr[index] = $(this).val();
            }

        });
        return arr;
    }
    
    function isWorkcentreSelected()
    {
        var selected = false;
        $('#pop_party_availability_add .wcntr_id').each(function(){
            if($(this).prop('checked'))
            {    
                selected = true;
            }
        });
        return selected;
    }

    $(document).ready(function() {


        //Saving data
        $('#pop_party_availability_add .save').click(function() {
            
            if(!isWorkcentreSelected())
            {
                $("#pop_party_availability_add .responseMessage").html('<div class="failureHead"><img src="images/failure.gif" width="15" height="15" /> Error!!!<div class="pop_failure">Please select any of workcentre !</div></div>');
                return;
            }
            
            //Showing Loading image till ajax responds
            $("#pop_party_availability_add .responseMessage").html('<img src="images/ajax-loader.gif" /> Loading....');

            // Disabling whole page background till Ajax respond.
            $.blockUI(); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"

//        dwc_fk_workcentres,dwc_fk_party_destinations,dwc_date,dwc_ob,dwc_ob_mode
//        dwc_credit_lmt,dwc_debt_lmt



            var postData = {
                dwc_fk_party_destinations: $('#pop_party_availability_add #dwc_fk_party_destinations').val(),
                dwc_ob: [],
                dwc_ob_mode: [],
                dwc_credit_lmt: [],
                dwc_debt_lmt: []
            };

            postData.dwc_ob = read_input('dwc_ob');
            postData.dwc_ob_mode = read_radio('dwc_ob_mode');
            postData.dwc_credit_lmt = read_input('dwc_credit_lmt');
            postData.dwc_debt_lmt = read_input('dwc_debt_lmt');

            $.ajax({
                url: site_url + "destination_workcentres/add",
                data: postData, // post the created object here
                type: 'POST',
                success: function(result) {

                    if (result == 1)
                    {
                        // Hiding save buttun to prevent the chance for re-entering of data.
                        $('#pop_party_availability_add .save').hide();

                        $("#pop_party_availability_add .responseMessage").html('<div class="pop_success"><img src="images/success_1.gif" width="15" height="15"  /> Data saved successfully!!!</div>');

                        // Refreshing page automatically after 1 seconds.
                        setTimeout(function() {
                            location.reload(true);
                        }, 1000);

                    }
                    else
                        $("#pop_party_availability_add .responseMessage").html(result);

                    // enabling the whole page after ajax response.
                    $(document).ajaxStop($.unblockUI); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"

                }
            });





        });



    });



    function init_pop_party_availability_add() {

        // Setting initial values of elements.
        $('#pop_party_availability_add #dwc_fk_parties').val('');
        $('#pop_party_availability_add .wcntr_id').prop('checked', false);
        $('#pop_party_availability_add .showAvailability').html('');

        $('#pop_party_availability_add #pop_drag').prop('checked', 'true');
        $('#pop_party_availability_add #pop_self_close').prop('checked', 'true');
        $('#pop_party_availability_add .save').show();
        $("#pop_party_availability_add .responseMessage").html('');

        //Making the popup box draggable.
        $("#pop_party_availability_add").draggable();
        dragUndrag($("#pop_party_availability_add"));

    }

    $('#pop_party_availability_add #dwc_fk_party_destinations').change(function() {
        var pdst_id = $(this).val();
        $("#pop_party_availability_add .responseMessage").html('');

        if (!pdst_id)
        {
            $('#pop_party_availability_add .showAvailability').html('<p class="nodata">Please select a destination</p>');
            $('#pop_party_availability_add .save').hide();
            return;
        }
        
        $('#pop_party_availability_add .save').show();
        var path = site_url + 'destination_workcentres/load_availability_details';
        var img = '<img src="images/ajax-loader2.gif">';
        $('#pop_party_availability_add .showAvailability').html(img + ' ' + img);
        $.post(path, {pdst_id: pdst_id}, function(data) {
            $('#pop_party_availability_add .showAvailability').html(data);
        });
    });


    function load_destinations(pty_id, sel)
    {
        $('#pop_party_availability_add #dwc_fk_party_destinations').html('<option value="">*** No Data ***</option>');
        if (!pty_id)
            return;

        var path = site_url + 'destination_workcentres/load_destinations';
        before_load_pdst();
        $.getJSON(path, {pty_id: pty_id}, function(data) {
            var options = '';
            for (var x = 0; x < data.length; x++) {
                options += '<option value="' + data[x]['value'] + '">' + data[x]['text'] + '</option>';
            }
            $('#pop_party_availability_add #dwc_fk_party_destinations').html(options);
            if (sel)
            {
                $('#pop_party_availability_add #dwc_fk_party_destinations').val(sel);
            }
            after_load_pdst();
        });
    }

    function before_load_pdst()
    {
        $('#pop_party_availability_add #dwc_fk_party_destinations').hide();
        $('#pop_party_availability_add .ajaxLoaderContainer').show();
    }

    function after_load_pdst()
    {
        $('#pop_party_availability_add #dwc_fk_party_destinations').show();
        $('#pop_party_availability_add .ajaxLoaderContainer').hide();
    }


</script>
<script type="text/javascript" src="plugins/blockui-master/jquery.blockUI.js"></script> 
