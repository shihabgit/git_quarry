



$(document).ready(function () {

    initTraversor(true, false, true); // Function defined in js/tbl_traversor2.js.

    // Tax type : 1=> Taxable, 2=> Compounted (Non-taxable)
    var tax_type = 2; // Default as 'Compounted'


    // Workcentre
    var wcntr = $('#dv_bill .basic_details #wcntr_id');

    var wcntr_license = $('#dv_bill .basic_details #wrd_id');


    // Party' Details  
    var party = $('#dv_bill .basic_details #pty_id');

    var tmp_party = $('#dv_bill .basic_details #tmp_party');

    var destination = $('#dv_bill .basic_details #pdst_id');

    var party_license = $('#dv_bill .basic_details #pld_id');

    var party_ob = $('#dv_bill .basic_details #party_ob');

    var party_cb = $('#dv_bill .basic_details #party_cb');

    var party_type = 1; // 1=> Existing party, 2=> New (Temperory) party




    // Vehicle's Details	
    var vhcl = $('#dv_bill .dv_vown_block #vhcl');

    var tmp_vhcl = $('#dv_bill .dv_vown_block #tmp_vhcl');

    var rent = $('#dv_bill .dv_vown_block #rent');

    var add_rent = $('#dv_bill .dv_vown_block #add_rent');

    var driver = $('#dv_bill .dv_vown_block #driver');

    var bata = $('#dv_bill .dv_vown_block #bata');

    var loaders = $('#dv_bill .dv_vown_block .loaders');

    var ld_charge = $('#dv_bill .dv_vown_block #ld_charge');

    var ld_mode = $('#dv_bill .dv_vown_block #ld_mode');

    var vhcl_ownership = '';


    // Bill Settings   
    var all_drivers_obj = $('#dv_bill .bill_settings #all_drivers');

    var all_loaders_obj = $('#dv_bill .bill_settings #all_loaders');

    var all_drivers = '';// 1 => Show All drivers, 2=>Show only drivers in the selected vehicle (the vhcl_ownership must be 1 or 2))

    var all_loaders = '';// 1 => Show All loaders, 2=>Show only loaders in the selected vehicle (the vhcl_ownership must be 1 or 2))



    // Initializing the bill. This function must becalled only after the declaration of variables related to the elements 
    // (Eg:- var wcntr ,var party, var destination,var vhcl, etc)
    initBill();


    on_party_type_changed();    // Initializing party_type.
    on_vehicle_ownership_changed(); //Initiallizing vhcl_ownership
    on_all_drivers_changed(); // Initializing the variable 'all_drivers'.
    on_all_loaders_changed();// Initializing the variable 'all_loaders'.



    $('#top_head .tax_type').change(function () {
        on_tax_type_changed();
    });

    $('#botom_head #wcntr_id').change(function () {
        on_workcentre_changed();
    });


    $('#dv_bill .basic_details input[name=party_type]').change(function () {
        on_party_type_changed();
    });

    $('#botom_head #pty_id').change(function () {
        on_party_changed();
    });

    $('#botom_head #pdst_id').change(function () {
        on_destination_changed();
    });

    $('#dv_bill .dv_vown input[name=vhcl_owner]').change(function () {
        on_vehicle_ownership_changed();
    });

    $('#botom_head #vhcl').change(function () {
        on_vehicle_changed();
    });

    $('#botom_head #driver').change(function () {
        on_driver_changed();
    });

    $('#dv_bill .loaders').change(function () {
        on_loader_changed();
    });

    add_rent.change(function () {
        on_addrent_changed();
    });

    rent.keyup(function () {
        on_rent_changed();
        // complete_check_up();
    });

    $('#dv_bill .bill_settings #all_drivers').change(function () {
        on_all_drivers_changed();
    });

    $('#dv_bill .bill_settings #all_loaders').change(function () {
        on_all_loaders_changed();
    });

    function on_tax_type_changed()
    {
        tax_type = $('#top_head .tax_type:checked').val();

        if (tax_type == 1) // Taxable
        {
            $('#top_head .spn_tax_no').hide();
            $('#top_head .spn_tax').show();
            $('#dv_bill #p_bill_type').removeClass('non-taxable').addClass('taxable');
        }
        else if (tax_type == 2) // Compounted (Non-taxable)
        {
            $('#top_head .spn_tax_no').show();
            $('#top_head .spn_tax').hide();
            $('#dv_bill #p_bill_type').removeClass('taxable').addClass('non-taxable');
        }

        initBill();
    }



    function load_workcentre()
    {
        $('#dv_bill .basic_details #wcntr_id').html('<option value="">No Workcentres</option>');
        tax_type = $('#top_head .tax_type:checked').val();

        if (!tax_type)
            return;

        $('#dv_bill .basic_details #wcntr_id').hide();
        $('#dv_bill .basic_details #wcntr_id').after('<div class="loader">&nbsp</div>');

        $.post(site_url + 'workcentres/load_users_workcentres', {tax_type: tax_type}, function (data) {
            $('#dv_bill .basic_details #wcntr_id').html(data);

            // Deleting ajax loader image and showing again the child element.
            $('#dv_bill .basic_details #wcntr_id').show();
            $('#botom_head .loader').remove();
        });
    }


    function initBill()
    {
        load_workcentre();

        wcntr_license.hide();
        party_license.hide();

        $('#dv_bill .basic_details input[name=party_type][value=1]').prop('checked', true); // Default party is an existing one.

        // For Taxable bills, temperory parties are not allowed.
        if ($('#top_head .tax_type:checked').val() == 1)
        {
            $('#dv_bill .basic_details input[name=party_type][value=1]').prop('checked', true); // Set party type as existing.
            $('#dv_bill .basic_details input[name=party_type]').closest('tr').hide();
        }
        else
        {
            $('#dv_bill .basic_details input[name=party_type]').closest('tr').show();
        }

        $('#dv_bill .basic_details #pty_id').show();

        $('#dv_bill .basic_details #tmp_party').hide();

        $('#dv_bill .basic_details #pty_id').html('<option value="">No Parties</option>');

        $('#dv_bill .basic_details #pdst_id').html('<option value="">No Destinations</option>');

        $('#dv_bill .basic_details #party_ob').html('');

        $('#dv_bill .basic_details #party_cb').html('');




        $('#dv_bill .dv_vown input[name=vhcl_owner][value=1]').prop('checked', true); // Default vehicle ownership is our's.

        ours_vehicle();

        $('#dv_bill .dv_vown_block #rent').val('');

        $('#dv_bill .dv_vown_block #bata').val('');

        $('#dv_bill .dv_vown_block #ld_charge').val('');


        $('#dv_bill .bill_settings #all_drivers').prop('disabled', false);

        $('#dv_bill .bill_settings #all_drivers').css('opacity', '1');

        $('#dv_bill .bill_settings #all_loaders').prop('disabled', false);

        $('#dv_bill .bill_settings #all_loaders').css('opacity', '1');

        if (typeof $('.tbl_bill_body .dv_freights').find('#rent_to_add').prop('id') !== "undefined")
        {
            $('#rent_to_add').closest('.dv_freights_row').remove();
        }


    }

    function on_workcentre_changed()
    {
        rent.val('');
        on_rent_changed();
        bata.val('');
        ld_charge.val('');
        add_rent.prop('checked', false);
        on_addrent_changed();

        if (tax_type == 1)// Taxable bill
        {
            wcntr_license.hide();
            wcntr_license.html('');
            
            party_license.hide();
            party_license.html('');
            
            var wc_id = wcntr.val();

            if (wc_id)
            {
                wcntr_license.show();
                wcntr_license.html('<div class="loader">&nbsp</div>');

                $.post(site_url + 'workcentre_registration_details/get_license_name', {wcntr_id: wc_id}, function (data) {
                    wcntr_license.html(data);
                });
            }
        }


        if ((vhcl_ownership == 1) || (vhcl_ownership == 2))
            load_vehicles();// If vehicle ownership is our's, other's, party's; load the vehicles.

        on_party_type_changed();
    }

    function on_party_type_changed()
    {
        party_type = $('#dv_bill .basic_details input[name=party_type]:checked').val();

        if (tax_type == 1)
        {
            party_license.hide();
            party_license.html('');
        }


        check_party_type();

        if (vhcl_ownership == 3)// If vehicle ownership is party's.
        {
            load_vehicles();
        }
    }

    function on_party_changed()
    {
        if (tax_type == 1)
        {
            party_license.hide();
            party_license.html('');
        }

        load_destination();

        if ((vhcl_ownership == 3)) // parties's Vehicle.
        {
            load_vehicles();
        }
    }

    function on_destination_changed()
    {
        party_license.hide();
        party_license.html('');
        
        var pdst_id = destination.val();

        if ((tax_type == 1) && pdst_id) // Taxable
        {
            party_license.show();
            party_license.html('<div class="loader">&nbsp</div>');

            $.post(site_url + 'party_license_details/get_license_name', {pdst_id: pdst_id}, function (data) {
                party_license.html(data);
            });
        }

        if (vhcl_ownership == 1) // If Our's/Other's Vehicle.
        {
            load_freights();
        }
        else if (vhcl_ownership == 3) // If Our's/Other's Vehicle.
        {
            load_party_vehicles_rent();
        }
    }

    function on_vehicle_ownership_changed()
    {
        vhcl_ownership = $('#dv_bill .dv_vown input[name=vhcl_owner]:checked').val();
        vhcl.html('<option value="">No Vehicles</option>');
        driver.html('<option value="">No Drivers</option>');
        loaders.html('<option value="">No Loaders</option>');
        tmp_vhcl.val('');

        rent.val('');
        on_rent_changed();

        bata.val('');
        ld_charge.val('');

        add_rent.prop('checked', false);
        on_addrent_changed();

        check_vehicle_ownership();
    }

    function on_addrent_changed()
    {
        add_remove_frieght_rows(add_rent, get_add_rent_html, '#rent_to_add');
    }

    function on_rent_changed()
    {
        insert_freight_val_to_bill(rent.val(), add_rent, '#rent_to_add');
    }

    function on_vehicle_changed()
    {
        driver.html('<option value="">No Drivers</option>');
        loaders.html('<option value="">No Loaders</option>');
        rent.val('');
        on_rent_changed();
        bata.val('');
        ld_charge.val('');
        add_rent.prop('checked', false);
        on_addrent_changed();

        if (vhcl_ownership == 1) // If Our's Vehicle.
        {
            load_freights();
            load_drivers();
            load_loaders();
        }
        else if ((vhcl_ownership == 3)) // If Our's/Other's Vehicle.
        {
            load_party_vehicles_rent();
        }
    }



    function on_driver_changed()
    {

    }

    function on_loader_changed()
    {

    }


    function on_all_drivers_changed()
    {
        // 1 => Show All drivers, 2=>Show only drivers in the selected vehicle (if the vhcl_ownership == 1)
        all_drivers = $('#dv_bill .bill_settings #all_drivers').prop('checked') ? 1 : 2;
        if (vhcl_ownership == 1) // If Our's/Other's Vehicle.
        {
            load_drivers();
        }
    }

    function on_all_loaders_changed()
    {
        // 1 => Show All loaders, 2=>Show only loaders in the selected vehicle (if the vhcl_ownership == 1)
        all_loaders = $('#dv_bill .bill_settings #all_loaders').prop('checked') ? 1 : 2;
        if (vhcl_ownership == 1)// If Our's/Other's Vehicle.
        {
            load_loaders();
        }
    }

    function load_freights()
    {
        rent.val('');
        on_rent_changed();

        bata.val('');
        ld_charge.val('');
        add_rent.prop('checked', false);
        on_addrent_changed();

        if (!wcntr.val() || !vhcl.val() || !destination.val() || vhcl_ownership != 1)
            return;

        var input = {
            wcntr_id: wcntr.val(),
            vhcl_id: vhcl.val(),
            pdst_id: destination.val()
        }

        $.getJSON(site_url + 'freight_charges/get_freights', input, function (data) {

            rent.val(data['fc_rent']);
            on_rent_changed();
            bata.val(data['fc_bata']);
            ld_charge.val(data['fc_loading']);
            on_addrent_changed();
        });
    }

    function load_party_vehicles_rent()
    {
        rent.val('');
        add_rent.prop('checked', false);

        on_rent_changed();
        on_addrent_changed();

        if (!wcntr.val() || !vhcl.val() || !destination.val() || vhcl_ownership != 3)
            return;

        var input = {
            wcntr_id: wcntr.val(),
            vhcl_id: vhcl.val(),
            pdst_id: destination.val()
        }

        $.getJSON(site_url + 'party_vehicle_rents/get_freights', input, function (data) {

            //,
            rent.val(data['pvr_rent']);
            if (data['pvr_add_rent'] == 1) //1 => Add rent to bill amount ,2 => Don't Add
                add_rent.prop('checked', true);
            else
                add_rent.prop('checked', false);

            on_rent_changed();
            on_addrent_changed();
        });
    }


    function load_vehicles()
    {
        vhcl.html('<option value="">No Vehicles</option>');
        driver.html('<option value="">No Drivers</option>');
        loaders.html('<option value="">No Loaders</option>');

        rent.val('');
        on_rent_changed();

        bata.val('');
        ld_charge.val('');

        add_rent.prop('checked', false);
        on_addrent_changed();

        switch (vhcl_ownership)
        {
            case '1':
                load_ours_vehicles(); // Our vehicle.
                break;
            case '2':
                load_others_vehicles();  // Other's vehicle.
                break;
            case '3':
                load_parties_vehicles();    // Party's vehicle.
                break;
            case '4':
                vhcl.html('<option value="">No Vehicles</option>');// Temperory Vehicle.
                break;
        }

    }

    function load_drivers()
    {
        driver.html('<option value="">No Drivers</option>');

        if (!wcntr.val() || !vhcl.val() || vhcl_ownership != 1)
            return;

        var input = {
            wcntr_id: wcntr.val(),
            vhcl_id: vhcl.val(),
            all_labours: all_drivers, // 1 => Show All drivers, 2=>Show only drivers in the selected vehicle.
            emp_categroy: 4 // emp_category of Drivers = 4
        }

        // Showing an ajax loading image in the place of child element
        driver.hide();
        driver.after('<div class="loader">&nbsp</div>');

        $.post(site_url + 'vehicles_employees/get_labours_option', input, function (data) {
            driver.html(data);

            // Deleting ajax loader image and showing again the child element.
            driver.show();
            $('#botom_head .loader').remove();
        });
    }

    function load_loaders()
    {
        loaders.html('<option value="">No Loaders</option>');

        if (!wcntr.val() || !vhcl.val() || vhcl_ownership != 1)
            return;

        var input = {
            wcntr_id: wcntr.val(),
            vhcl_id: vhcl.val(),
            all_labours: all_loaders, // 1 => Show All loaders, 2=>Show only loaders in the selected vehicle.
            emp_categroy: 5 // emp_category of Drivers = 4
        }


        // Showing an ajax loading image in the place of child element
        loaders.hide();
        loaders.after('<div class="loader">&nbsp</div>');

        $.post(site_url + 'vehicles_employees/get_labours_option', input, function (data) {
            loaders.html(data);

            // Deleting ajax loader image and showing again the child element.
            loaders.show();
            $('#botom_head .loader').remove();
        });
    }


    // Add ADDITIVES.
    $('.tbl_bill_body #add_adtv').click(function () {
        $('.tbl_bill_body #adtv').show();
        var html = get_additive_html();
        $('.tbl_bill_body #adtv .total_additionals').before(html);
        // complete_check_up();
    });

    // Add DEDUCTIVES.
    $('.tbl_bill_body #add_ddtv').click(function () {
        $('.tbl_bill_body #ddtv').show();
        var html = get_deductive_html();
        $('.tbl_bill_body #ddtv .total_additionals').before(html);
        // complete_check_up();
    });



    /*  To resolve jquery conflict when using "$(document).on()" function when we using both 
     "js/jquery1.11.0.js"> and "js/jquery.min.js" libraries, use  $.noConflict();   
     Other wise it will show error as follows;
     TypeError: $(...).on is not a function
     
     */
    $.noConflict();

    // Delete Additives / Deductive rows.
    $(document).on('click', '.tbl_bill_body .dv_additional_body .delete_row', function () {
        $(this).closest('.dv_additionals_row').remove();
        show_hide_additionals();
        // complete_check_up();
    });


    function check_vehicle_ownership(obj)
    {
        var checked = '';

        if (obj)
            checked = obj.val();
        else
            checked = vhcl_ownership;

        if (typeof checked === "undefined")
            return;
        switch (checked)
        {
            case '1':
                ours_vehicle();
                break; // Our vehicle.
            case '2':
                others_vehicle();
                break;  // Other's vehicle.
            case '3':
                partys_vehicle();
                break;    // Party's vehicle.
            case '4':
                temperoryVehicle();
                break;   // Temperory Vehicle.
                //default: alert('Unknown Vehicle Ownership Category!!!');
        }
    }

    function check_party_type(obj)
    {
        var checked = '';

        if (obj)
            checked = obj.val();
        else
            checked = party_type;

        if (typeof checked === "undefined")
            return;
        switch (checked)
        {
            case '1':
                existing_party();
                break; // Existing party in our parties list.
            case '2':
                temperory_party();
                break;  // Temperory party.
                //default: alert('Unknown Party Type!!!');
        }
    }

    function insert_freight_val_to_bill(val, parent, child)
    {
        // If parent not checked, return;
        if (!parent.prop('checked'))
            return;

        // If child not existing, return.
        if (typeof $('.tbl_bill_body .dv_freights').find(child).prop('id') === "undefined")
            return;

        $('.tbl_bill_body .dv_freights').find(child).html(val);
    }

    function add_remove_frieght_rows(parent, func_html, child)
    {
        // If Add Rent
        if (parent.prop('checked'))
        {
            // If already created; return.
            if (typeof $('.tbl_bill_body .dv_freights').find(child).prop('id') !== "undefined")
                return;

            var html = func_html();
            $('.tbl_bill_body .dv_freights').append(html);
            $('.tbl_bill_body .dv_freights').show();
        }

        // Else if child existing, delete its row.
        else if (typeof $('.tbl_bill_body .dv_freights').find(child).prop('id') !== "undefined")
        {
            $(child).closest('.dv_freights_row').remove();
        }
    }

    function get_add_rent_html()
    {
        var rent_val = rent.val();
        var html = '		<div class="dv_freights_row" id="">';
        html += '              <div class="dv_freights_col">Rent</div>';
        html += '              <div class="dv_freights_col" id="rent_to_add">' + rent_val + '</div>';
        html += '              <div class="clear_boath"></div>';
        html += '            </div>';
        return html;
    }

    function ours_vehicle()
    {
        var enable = [vhcl, rent, driver, bata, loaders, ld_charge, ld_mode, all_drivers_obj, all_loaders_obj];
        var disable = [add_rent, tmp_vhcl];

        changeObj(disable, 'disable');
        changeObj(enable, 'enable');
        add_rent.prop('checked', false);
        on_addrent_changed();
        on_all_drivers_changed();
        on_all_loaders_changed();
        tmp_vhcl.hide();
        vhcl.show();
        load_ours_vehicles();
    }

    function others_vehicle()
    {
        var enable = [vhcl, rent];
        var disable = [add_rent, tmp_vhcl, driver, bata, loaders, ld_charge, ld_mode, all_drivers_obj, all_loaders_obj];
        changeObj(disable, 'disable');
        changeObj(enable, 'enable');
        add_rent.prop('checked', false);
        on_addrent_changed();
        on_all_drivers_changed();
        on_all_loaders_changed();
        tmp_vhcl.hide();
        vhcl.show();
        load_others_vehicles();
    }

    function partys_vehicle()
    {
        var enable = [vhcl, rent, add_rent];
        var disable = [tmp_vhcl, driver, bata, loaders, ld_charge, ld_mode, all_drivers_obj, all_loaders_obj];
        changeObj(disable, 'disable');
        changeObj(enable, 'enable');
        on_addrent_changed();
        on_all_drivers_changed();
        on_all_loaders_changed();
        tmp_vhcl.hide();
        vhcl.show();
        load_parties_vehicles();
    }

    function temperoryVehicle()
    {
        var enable = [tmp_vhcl, rent, add_rent];
        var disable = [vhcl, driver, bata, loaders, ld_charge, ld_mode, all_drivers_obj, all_loaders_obj];
        changeObj(disable, 'disable');
        changeObj(enable, 'enable');
        on_addrent_changed();
        on_all_drivers_changed();
        on_all_loaders_changed();
        tmp_vhcl.show();
        vhcl.html('<option value="">No Vehicles</option>');
        ;
        vhcl.hide();
    }

    function existing_party()
    {
        enable = [party, destination];
        disable = [tmp_party];
        changeObj(disable, 'disable');
        changeObj(enable, 'enable');
        party.show();
        load_party();
        party_ob.show();
        party_cb.show();
        tmp_party.hide();
    }

    function temperory_party()
    {
        enable = [tmp_party];
        disable = [party, destination];
        changeObj(disable, 'disable');
        changeObj(enable, 'enable');
        party.hide();
        party.html('<option value="">No Parties</option>');
        destination.html('<option value="">No Destinations</option>');
        party_ob.hide();
        party_cb.hide();
        tmp_party.show();
    }

    function load_party()
    {
        // If the party is a temperory party, no need to laod from database.
        if ($('#dv_bill .basic_details input[name=party_type]:checked').val() == 2)
            return;

        party.html('<option value="">No Parties</option>');
        destination.html('<option value="">No Destinations</option>');

        var wc_id = wcntr.val();
        if (!wc_id || !tax_type)
            return;

        // Showing an ajax loading image in the place of child element
        $('#botom_head #pty_id').hide();
        $('#botom_head #pty_id').after('<div class="loader">&nbsp;</div>');

        $.getJSON(site_url + 'parties/getPartiesByWorkcentres2', {wcntr_ids: wc_id, tax_type: tax_type}, function (data) {
            var options = '';
            for (var x = 0; x < data.length; x++) {
                options += '<option value="' + data[x]['value'] + '">' + data[x]['text'] + '</option>';
            }
            $('#botom_head #pty_id').html(options);

            // Deleting ajax loader image and showing again the child element.
            $('#botom_head #pty_id').show();
            $('#botom_head .loader').remove();
        });
    }

    function load_destination()
    {
        destination.html('<option value="">No Destinations</option>');

        var wcntr_id = wcntr.val();
        var pty_id = party.val();

        if (!pty_id || !wcntr_id || !tax_type)
            return;

        var input = {parent_id: pty_id, wcntr_id: wcntr_id, tax_type: tax_type};

        // Showing an ajax loading image in the place of child element
        $('#botom_head #pdst_id').hide();
        $('#botom_head #pdst_id').after('<div class="loader">&nbsp</div>');

        $.getJSON(site_url + 'party_destinations/getDestinationsByParty2', input, function (data) {
            var options = '';
            for (var x = 0; x < data.length; x++) {
                options += '<option value="' + data[x]['value'] + '">' + data[x]['text'] + '</option>';
            }
            $('#botom_head #pdst_id').html(options);

            // Deleting ajax loader image and showing again the child element.
            $('#botom_head #pdst_id').show();
            $('#botom_head .loader').remove();
        });
    }

    function load_parties_vehicles()
    {
        vhcl.html('<option value="">No Vehicles</option>');

        var pty_id = party.val();

        if (!pty_id)
            return;

        // Showing an ajax loading image in the place of child element
        vhcl.hide();
        vhcl.after('<div class="loader">&nbsp</div>');


        $.getJSON(site_url + 'party_vehicles/load_vehicles', {pty_id: pty_id}, function (data) {
            var options = '';
            for (var x = 0; x < data.length; x++) {
                options += '<option value="' + data[x]['value'] + '">' + data[x]['text'] + '</option>';
            }
            $('#botom_head #vhcl').html(options);

            // Deleting ajax loader image and showing again the child element.
            $('#botom_head #vhcl').show();
            $('#botom_head .loader').remove();
        });
    }

    function load_ours_vehicles()
    {
        vhcl.html('<option value="">No Vehicles</option>');

        var wcntr_id = wcntr.val();
        var vhcl_ownership = 1; //Our's Vehicles.
        if (!wcntr_id)
            return;

        // Showing an ajax loading image in the place of child element
        vhcl.hide();
        vhcl.after('<div class="loader">&nbsp</div>');

        var input = {
            wcntr_id: wcntr_id,
            vhcl_ownership: vhcl_ownership
        }

        $.getJSON(site_url + 'vehicle_workcentres/get_vehicles_in_workcentre', input, function (data) {
            var options = '';
            for (var x = 0; x < data.length; x++) {
                options += '<option value="' + data[x]['value'] + '">' + data[x]['text'] + '</option>';
            }
            $('#botom_head #vhcl').html(options);

            // Deleting ajax loader image and showing again the child element.
            $('#botom_head #vhcl').show();
            $('#botom_head .loader').remove();
        });
    }

    function load_others_vehicles()
    {
        vhcl.html('<option value="">No Vehicles</option>');

        var wcntr_id = wcntr.val();
        var vhcl_ownership = 2; //Others's Vehicles.
        if (!wcntr_id)
            return;

        // Showing an ajax loading image in the place of child element
        vhcl.hide();
        vhcl.after('<div class="loader">&nbsp</div>');

        var input = {
            wcntr_id: wcntr_id,
            vhcl_ownership: vhcl_ownership
        }

        $.getJSON(site_url + 'vehicle_workcentres/get_vehicles_in_workcentre', input, function (data) {
            var options = '';
            for (var x = 0; x < data.length; x++) {
                options += '<option value="' + data[x]['value'] + '">' + data[x]['text'] + '</option>';
            }
            $('#botom_head #vhcl').html(options);

            // Deleting ajax loader image and showing again the child element.
            $('#botom_head #vhcl').show();
            $('#botom_head .loader').remove();
        });
    }

    function show_hide_additionals()
    {
        //alert($('.tbl_bill_body #adtv').find('.dv_additionals_row').length);
        if ($('.tbl_bill_body #adtv').find('.dv_additionals_row').length == 0)
        {
            $('.tbl_bill_body #adtv').hide();
            $('.tbl_bill_body #adtv .total_additionals_col .nTotal_val').text('');
        }
        if ($('.tbl_bill_body #ddtv').find('.dv_additionals_row').length == 0)
        {
            $('.tbl_bill_body #ddtv').hide();
            $('.tbl_bill_body #ddtv .total_additionals_col .nTotal_val').text('');
        }
    }

    function get_additive_html()
    {
        var html = '<div class="dv_additionals_row">';
        html += '<div class="dv_additionals_col">';
        html += '<input type="text" class="left adtv_txt" style="width:110px;" placeholder="Name" />';
        html += '</div>';
        html += '<div class="dv_additionals_col">';
        html += '<input type="text" class="right adtv_val" style="width: 87px;" placeholder="Value" />';
        html += '</div>';
        html += '<div class="row_remvr_right"><img class="delete_row" src="images/delete5.png" /></div>';
        html += '<div class="clear_boath"></div>';
        html += '</div>';
        return html;
    }

    function get_deductive_html()
    {
        var html = '<div class="dv_additionals_row">';
        html += '<div class="dv_additionals_col">';
        html += '<input type="text" class="left ddtv_txt" style="width:110px;" placeholder="Name" />';
        html += '</div>';
        html += '<div class="dv_additionals_col">';
        html += '<input type="text" class="right ddtv_val" style="width: 87px;" placeholder="Value" />';
        html += '</div>';
        html += '<div class="row_remvr_right"><img class="delete_row" src="images/delete5.png" /></div>';
        html += '<div class="clear_boath"></div>';
        html += '</div>';
        return html;
    }



    /*$(document).on('change', '.tbl_bill_body #adtv .adtv_val', function() {
     //alert($(this).val());
     });*/


});	// End of $(document).ready();


function changeObj(obj, mode)
{
    for (var i = 0; i < obj.length; i++)
    {
        if (mode == 'disable')
        {
            obj[i].prop('disabled', true);
            if (obj[i].is(':checkbox'))
                obj[i].prop('checked', false);
            else
                obj[i].val('');
            obj[i].css('opacity', '0.3');
        }
        else if (mode == 'enable')
        {
            obj[i].prop('disabled', false);
            obj[i].css('opacity', '1');
        }
    }
}