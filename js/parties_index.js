$(document).ready(function() {


    // Opening popup box on clicking <img src="images/add.png" class="initPartyLicenseAdd"> 
    $('.initPartyLicenseAdd').click(function() {

        //Initializing popup box  
        init_pop_party_license_details_add();

        //Loading popupBox.
        loadPopup('pop_party_license_details_add');
    });


    // Editing party.
    $('.tile .pty_edit').click(function() {
        var p_key = $(this).closest('.tile').find('.pty_id_popup').val();
        var pty_name = $(this).closest('.tile').find('.pty_name_popup').val();

        //Initializing popup box.
        init_pop_party_edit();

        $('#pop_party_edit .namespan_box .namespan').text(pty_name);
        $('#pop_party_edit #p_key').val(p_key);

        $.getJSON(site_url + 'parties/beforeEdit', {pty_id: p_key}, function(data) {
            $('#pop_party_edit #pty_name').val(data['pty_name']);
            $('#pop_party_edit #pty_date').val(data['pty_date']);
            $('#pop_party_edit #pty_phone').val(data['pty_phone']);
            $('#pop_party_edit #pty_email').val(data['pty_email']);

            //Loading popupBox.
            loadPopup('pop_party_edit');
        });

    });


    // Toggling party's status.
    $('.tile .toggleStatus').click(function() {

        var party_name = $(this).closest('.tile').find('.pty_name_popup').val();
        party_name.replace('"', '\"');
        party_name.replace("'", "\'");
        var party_id = $(this).closest('.tile').find('.pty_id_popup').val();
        var party_status = $(this).closest('.tile').find('.pty_status_popup').val();
        var msg = (party_status == 1) ? " deactivate " : " activate ";
        msg += "'" + party_name + "' ";

        if (!confirm('Do you want to ' + msg))
            return;

        //Setting input.
        var inputs = {pty_id: party_id}; // eg: {parent_id: parent_id, status: 1}

        // Disabling whole page background till Ajax respond.
        $.blockUI(); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"

        $.post(site_url + "parties/toggleStatus", inputs, function(result) {

            alert(result);

            // enabling the whole page after ajax response.
            $(document).ajaxStop($.unblockUI); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"


            // Refreshing the browser after 1 second
            setTimeout(function() {
                location.reload();
            }, 1000);

        });
    });


    // Edit Vehicle
    $('.ptyvhcl #edit_icon').click(function() {
        var p_key = $(this).closest('.ptyvhcl').find('#pvhcl_id').val();
        var name = $(this).closest('.ptyvhcl').find('#pvhcl_no').val();

        //Initializing popup box  
        init_pop_party_vehicles_edit();

        $('#pop_party_vehicles_edit #p_key').val(p_key);
        $('#pop_party_vehicles_edit .namespan_box .namespan').text(name);

        $.getJSON(site_url + 'party_vehicles/beforeEdit', {pvhcl_id: p_key}, function(data) {

            $('#pop_party_vehicles_edit #pvhcl_fk_parties').val(data['pvhcl_fk_parties']);
            $('#pop_party_vehicles_edit #pvhcl_name').val(data['pvhcl_name']);
            $('#pop_party_vehicles_edit #pvhcl_no').val(data['pvhcl_no']);
            $('#pop_party_vehicles_edit #pvhcl_length').val(data['pvhcl_length']);
            $('#pop_party_vehicles_edit #pvhcl_breadth').val(data['pvhcl_breadth']);
            $('#pop_party_vehicles_edit #pvhcl_height').val(data['pvhcl_height']);
            $('#pop_party_vehicles_edit #pvhcl_xheight').val(data['pvhcl_xheight']);

            //Loading popupBox.
            loadPopup('pop_party_vehicles_edit');
        });

    });


    // Toggling vehicle status
    $('.ptyvhcl #toggle_icon').click(function() {

        var p_key = $(this).closest('.ptyvhcl').find('#pvhcl_id').val();
        var status = $(this).closest('.ptyvhcl').find('#pvhcl_status').val();
        var name = $(this).closest('.ptyvhcl').find('#pvhcl_no').val();
        name.replace('"', '\"');
        name.replace("'", "\'");

        var msg = (status == 1) ? " deactivate " : " activate ";
        msg += "the vehicle '" + name + "' ?";

        if (!confirm('Do you want to ' + msg))
            return;

        //Setting input.
        var inputs = {pvhcl_id: p_key}; // eg: {parent_id: parent_id, status: 1}

        // Disabling whole page background till Ajax respond.
        $.blockUI(); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"

        $.post(site_url + "party_vehicles/toggleStatus", inputs, function(result) {

            alert(result);

            // enabling the whole page after ajax response.
            $(document).ajaxStop($.unblockUI); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"


            // Refreshing the browser after 1 second
            setTimeout(function() {
                location.reload();
            }, 1000);

        });
    });



    // Edit Destination
    $('.ptydst #edit_icon').click(function() {
        var p_key = $(this).closest('.ptydst').find('#pdst_id').val();
        var name = $(this).closest('.ptydst').find('#pdst_name').val();

        //Initializing popup box.
        init_pop_party_destinations_edit();

        $('#pop_party_destinations_edit #p_key').val(p_key);
        $('#pop_party_destinations_edit .namespan_box .namespan').text(name);

        $.getJSON(site_url + 'party_destinations/beforeEdit', {pdst_id: p_key}, function(data) {

            $('#pop_party_destinations_edit #pdst_date').val(data['pdst_date']);
            //$('#pop_party_destinations_edit #pdst_fk_party_license_details').val(data['pdst_fk_party_license_details']);
            $('#pop_party_destinations_edit #pdst_fk_parties').val(data['pdst_fk_parties']);
            setLicense(data['pdst_fk_parties'], data['pdst_fk_party_license_details']);
            $('#pop_party_destinations_edit #pdst_name').val(data['pdst_name']);
            $('#pop_party_destinations_edit #pdst_phone').val(data['pdst_phone']);
            $('#pop_party_destinations_edit #pdst_email').val(data['pdst_email']);
            $('#pop_party_destinations_edit input[type=radio][name=pdst_category][value=' + data['pdst_category'] + ']').prop('checked', true);
            //Loading popupBox.
            loadPopup('pop_party_destinations_edit');
        });

    });


    // Toggling destination status
    $('.ptydst #toggle_icon').click(function() {

        var p_key = $(this).closest('.ptydst').find('#pdst_id').val();
        var status = $(this).closest('.ptydst').find('#pdst_status').val();
        var name = $(this).closest('.ptydst').find('#pdst_name').val();
        name.replace('"', '\"');
        name.replace("'", "\'");

        var msg = (status == 1) ? " deactivate " : " activate ";
        msg += "the destination '" + name + "' ?";

        if (!confirm('Do you want to ' + msg))
            return;

        //Setting input.
        var inputs = {pdst_id: p_key}; // eg: {parent_id: parent_id, status: 1}

        // Disabling whole page background till Ajax respond.
        $.blockUI(); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"

        $.post(site_url + "party_destinations/toggleStatus", inputs, function(result) {

            alert(result);

            // enabling the whole page after ajax response.
            $(document).ajaxStop($.unblockUI); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"

            // Refreshing the browser & clearing cache.
            location.reload(true);

        });
    });

    // Edit License details
    $('.ptylic #edit_icon').click(function() {
        var p_key = $(this).closest('.ptylic').find('#pld_id').val();
        var name = $(this).closest('.ptylic').find('#pld_firm_name').val();

        //Initializing popup box.
        init_pop_party_license_details_edit();

        $('#pop_party_license_details_edit #p_key').val(p_key);
        $('#pop_party_license_details_edit .namespan_box .namespan').text(name);

        $.getJSON(site_url + 'party_license_details/beforeEdit', {pld_id: p_key}, function(data) {
            $('#pop_party_license_details_edit #pld_date').val(data['pld_date']);
            $('#pop_party_license_details_edit #pld_firm_name').val(data['pld_firm_name']);
            $('#pop_party_license_details_edit #pld_address').val(data['pld_address']);
            $('#pop_party_license_details_edit #pld_phone').val(data['pld_phone']);
            $('#pop_party_license_details_edit #pld_email').val(data['pld_email']);
            $('#pop_party_license_details_edit #pld_tin').val(data['pld_tin']);
            $('#pop_party_license_details_edit #pld_licence').val(data['pld_licence']);
            $('#pop_party_license_details_edit #pld_cst').val(data['pld_cst']);

            //Loading popupBox.
            loadPopup('pop_party_license_details_edit');
        });

    });



    // Toggling License status
    $('.ptylic #toggle_icon').click(function() {

        var p_key = $(this).closest('.ptylic').find('#pld_id').val();
        var status = $(this).closest('.ptylic').find('#pld_status').val();
        var name = $(this).closest('.ptylic').find('#pld_firm_name').val();
        name.replace('"', '\"');
        name.replace("'", "\'");

        var msg = (status == 1) ? " deactivate " : " activate ";
        msg += "the license '" + name + "' ?";

        if (!confirm('Do you want to ' + msg))
            return;

        //Setting input.
        var inputs = {pld_id: p_key}; // eg: {parent_id: parent_id, status: 1}

        // Disabling whole page background till Ajax respond.
        $.blockUI(); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"

        $.post(site_url + "party_license_details/toggleStatus", inputs, function(result) {

            alert(result);

            // enabling the whole page after ajax response.
            $(document).ajaxStop($.unblockUI); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"


            // Refreshing the browser & clearing cache.
            location.reload(true);

        });
    });

    $('.add_vehicle').click(function() {
        var pty_id = $(this).closest('.tile').find('.pty_id_popup').val();
        var pty_name = $(this).closest('.tile').find('.pty_name_popup').val();

        //Initializing popup box.
        init_pop_party_vehicles_add();

        $('#pop_party_vehicles_add #pvhcl_fk_parties').val(pty_id);
        $('#pop_party_vehicles_add .namespan_box .namespan').text(pty_name);

        //Loading popupBox.
        loadPopup('pop_party_vehicles_add');
    });

    $('.add_destination').click(function() {
        var pty_id = $(this).closest('.tile').find('.pty_id_popup').val();
        var pty_name = $(this).closest('.tile').find('.pty_name_popup').val();

        //Initializing popup box.
        init_pop_party_destinations_add();

        setLicense2(pty_id);
        $('#pop_party_destinations_add #pdst_fk_parties').val(pty_id);
        $('#pop_party_destinations_add .namespan_box .namespan').text(pty_name);

        //Loading popupBox.
        loadPopup('pop_party_destinations_add');
    });

    $('.add_to_workcentre').click(function() {
        var pty_id = $(this).closest('.tile').find('.pty_id_popup').val();
        var pty_name = $(this).closest('.tile').find('.pty_name_popup').val();

        //Initializing popup box.
        init_pop_party_availability_add();

        load_destinations(pty_id);

        $('#pop_party_availability_add #dwc_fk_parties').val(pty_id);
        $('#pop_party_availability_add .namespan_box .namespan').text(pty_name);

        //Loading popupBox.
        loadPopup('pop_party_availability_add');
    });

    $('.avail_row .add_freight').click(function() {

        var pty_id = $(this).closest('.tile').find('.pty_id_popup').val();
        var pdst_name = $(this).closest('.avail_row').find('.pdst_name_ns').val();
        var wcntr_name = $(this).closest('.avail_row').find('.wcntr_name_ns').val();
        var pdst_id = $(this).closest('.avail_row').find('.dwc_fk_party_destinations').val();
        var wcntr_id = $(this).closest('.avail_row').find('.dwc_fk_workcentres').val();

        //Initializing popup box.
        init_pop_party_vehicle_rents_add();

        load_p_vehicle(pty_id, pdst_id, wcntr_id);

        $('#pop_party_vehicle_rents_add .namespan_box .namespan').html(pdst_name + ' <img src="images/arrow-both.png"> ' + wcntr_name);

        $('#pop_party_vehicle_rents_add #pvr_id').val('');
        $('#pop_party_vehicle_rents_add #pvr_fk_party_destinations').val(pdst_id);
        $('#pop_party_vehicle_rents_add #pvr_fk_workcentres').val(wcntr_id);

        //Loading popupBox.
        loadPopup('pop_party_vehicle_rents_add');
    });


    // Toggling License status
    $('.availability #toggle_icon').click(function() {

        var p_key = $(this).closest('.availability').find('.dwc_id').val();
        var status = $(this).closest('.availability').find('.dwc_status').val();

        var wcntr = $(this).closest('.availability').find('.wcntr_name_ns').val();
        var destination = $(this).closest('.availability').find('.pdst_name_ns').val();

        var msg = (status == 1) ? " deactivate " : " activate ";
        msg = "Do you want to" + msg + destination + " in " + wcntr;

        if (!confirm(msg))
            return;

        //Setting input.
        var inputs = {dwc_id: p_key}; // eg: {parent_id: parent_id, status: 1}

        // Disabling whole page background till Ajax respond.
        $.blockUI(); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"

        $.post(site_url + "destination_workcentres/toggleStatus", inputs, function(result) {

            alert(result);

            // enabling the whole page after ajax response.
            $(document).ajaxStop($.unblockUI); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"


            // Refreshing the browser & clearing cache.
            location.reload(true);

        });
    });



    // Edit License details
    $('.freight .edit_icon').click(function() {

        var p_key = $(this).closest('.freight').find('.pvr_id').val();
        var freight_vehicle = $(this).closest('.freight').find('.freight_vehicle').text();
        var freight_charge = $(this).closest('.freight').find('.pvr_rent').val();
        var add_rent = $(this).closest('.freight').find('.pvr_add_rent').val();
        var pdst_name = $(this).closest('.avail_row').find('.pdst_name_ns').val();
        var wcntr_name = $(this).closest('.avail_row').find('.wcntr_name_ns').val();
        var name = wcntr_name + ' <img src="images/arrow-both.png"> ' + pdst_name;

        //Initializing popup box.
        init_pop_party_vehicle_rents_edit();

        $('#pop_party_vehicle_rents_edit #pvr_id').val(p_key);
        $('#pop_party_vehicle_rents_edit .namespan_box .namespan').html(name);
        $('#pop_party_vehicle_rents_edit .freight_vehicle').html(freight_vehicle);
        $('#pop_party_vehicle_rents_edit #pvr_rent').val(freight_charge);
        if (add_rent == 1)
            $('#pop_party_vehicle_rents_edit #pvr_add_rent').prop('checked', true);
        else if (add_rent == 2)
            $('#pop_party_vehicle_rents_edit #pvr_add_rent').prop('checked', false);


        //Loading popupBox.
        loadPopup('pop_party_vehicle_rents_edit');
    });


    // Toggling License status
    $('.freight .delete_icon').click(function() {



        var p_key = $(this).closest('.freight').find('.pvr_id').val();
        var freight_vehicle = $(this).closest('.freight').find('.freight_vehicle').text();
        var pdst_name = $(this).closest('.avail_row').find('.pdst_name_ns').val();
        var wcntr_name = $(this).closest('.avail_row').find('.wcntr_name_ns').val();

        var msg = "Do you want to delete the freight charge of " + freight_vehicle + " from " + pdst_name + " to " + wcntr_name;

        if (!confirm(msg))
            return;

        //Setting input.
        var inputs = {pvr_id: p_key}; // eg: {parent_id: parent_id, status: 1}

        // Disabling whole page background till Ajax respond.
        $.blockUI(); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"

        $.post(site_url + "party_vehicle_rents/delete", inputs, function(result) {

            if (result == 1)
                alert("Successfully Deleted");
            else
                alert(result);

            // enabling the whole page after ajax response.
            $(document).ajaxStop($.unblockUI); // Function is defined at "plugins/blockui-master/jquery.blockUI.js"


            // Refreshing the browser & clearing cache.
            location.reload(true);

        });
    });

});