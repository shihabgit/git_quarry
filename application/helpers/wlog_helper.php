<?php

function get_popup_id($index='')
{
    $popup['firms'] = '';// No popup
    $popup['user_tasks'] = '';// No popup
    $popup['workcentres'] = 'pop_wlog_common';  // Common popup
    $popup['workcentre_registration_details'] = 'pop_wlog_common';  // Common popup
    //$popup['employees'] = 'pop_wlog_employees'; // A special popup for employees.
    $popup['employees'] = 'pop_wlog_common';  // Common popup
    $popup['employee_work_centre'] = 'pop_wlog_common';
    $popup['rental_details'] = 'pop_wlog_common'; // Common popup
    $popup['owners'] = 'pop_wlog_common';// Common popup
    $popup['vehicles'] = 'pop_wlog_common';// Common popup    
    $popup['vehicle_workcentres'] = 'pop_wlog_common';// Common popup
    $popup['vehicles_employees'] = '';// No popup
    $popup['freight_charges'] = 'pop_wlog_common';// Common popup
    $popup['inter_freight_charges'] = 'pop_wlog_common';// Common popup
    $popup['item_category'] = 'pop_wlog_common';// Common popup
    $popup['item_heads'] = 'pop_wlog_common';// Common popup
    $popup['units'] = '';// No Popups.
    $popup['items'] = 'pop_wlog_common';// Common popup
    $popup['item_units_n_rates'] = 'pop_wlog_common';// Common popup
    $popup['opening_stock'] = 'pop_wlog_common';// Common popup
    $popup['individual_rates'] = 'pop_wlog_common';// Common popup
    $popup['workcentre_rates'] = 'pop_wlog_common';// Common popup
    $popup['parties'] = 'pop_wlog_common';// Common popup
    $popup['party_license_details'] = 'pop_wlog_common';// Common popup
    $popup['party_destinations'] = 'pop_wlog_common';// Common popup
    $popup['party_vehicles'] = 'pop_wlog_common';// Common popup
    $popup['destination_workcentres'] = 'pop_wlog_common';// Common popup
    $popup['party_vehicle_rents'] = 'pop_wlog_common';// Common popup
    $popup['purchase_bill_head'] = 'pop_wlog_common';// Common popup
    
    
    
    if($index)
        return $popup[$index];
    return $popup;
}

function get_url($index='')
{
    $url['firms'] = '';
    $url['workcentres'] = 'workcentres/index';
    $url['workcentre_registration_details'] = '';
    $url['employees'] = '';
    $url['employee_work_centre'] = 'employee_work_centre/index';
    $url['rental_details'] = '';
    $url['owners'] = '';
    $url['vehicles'] = 'vehicles/index';
    $url['vehicle_workcentres'] = 'vehicle_workcentres/index';
    
    $url['vehicles_employees'] = '';
    $url['freight_charges'] = '';
    $url['inter_freight_charges'] = '';
    
    $url['item_category'] = '';
    $url['item_heads'] = '';
    $url['units'] = '';
    $url['items'] = 'items/index';
    $url['item_units_n_rates'] = '';
    $url['opening_stock'] = '';
    $url['individual_rates'] = '';
    $url['workcentre_rates'] = '';
    $url['parties'] = '';
    $url['party_license_details'] = '';
    $url['party_destinations'] = '';
    $url['party_vehicles'] = '';
    $url['destination_workcentres'] = '';
    $url['party_vehicle_rents'] = '';
    $url['purchase_bill_head'] = '';
    
    
    $url['user_tasks'] = '';
//    $url[''] = '';
    if($index)
        return $url[$index];
    return $url;
}

// Data related to which table to be backed up.
function to_be_backed_up($table)
{
    # Format:
    # $tbl['tableName'] = TRUE/FALSE;
    # TRUE: Data related to the talbe must be backed up.
    # FALSE: Data related to the talbe not nee to be backed up.
    
    $tbl['firms'] = FALSE;
    $tbl['workcentres'] = TRUE;
    $tbl['workcentre_registration_details'] = TRUE;
    $tbl['rental_details'] = TRUE;
    $tbl['employees'] = TRUE;
    $tbl['employee_work_centre'] = TRUE;
    $tbl['vehicles'] = TRUE;
    $tbl['vehicle_workcentres'] = TRUE;
    $tbl['vehicles_employees'] = FALSE;
    $tbl['freight_charges'] = TRUE;
    $tbl['inter_freight_charges'] = TRUE;    
    $tbl['item_category'] = FALSE;
    $tbl['item_heads'] = FALSE;
    $tbl['units'] = FALSE;
    $tbl['items'] = TRUE;
    $tbl['item_units_n_rates'] = TRUE;
    $tbl['opening_stock'] = TRUE;
    $tbl['individual_rates'] = TRUE;
    $tbl['workcentre_rates'] = TRUE;
    $tbl['parties'] = TRUE;
    $tbl['party_license_details'] = TRUE;
    $tbl['party_destinations'] = TRUE;
    $tbl['party_vehicles'] = TRUE;
    $tbl['destination_workcentres'] = TRUE;
    $tbl['party_vehicle_rents'] = TRUE;
    $tbl['purchase_bill_head'] = TRUE;
    
    
    return $tbl[$table];
}

function getWlogFields($table,$type='all')
{   
    $fnc_name = 'wlogFields_'.$table;
    $wlg = $fnc_name();
    if($type=='all')
        return $wlg;
    else if($type=='keys')
        return array_keys($wlg);
    else if($type=='values')
        return  array_values($wlg);
}

function wlogFields_employees()
{   
    $wlf['emp_date'] = 'Date';
    $wlf['emp_category'] = 'Category';
    $wlf['emp_name'] = 'Name';
    $wlf['username'] = 'Username';
    $wlf['password'] = 'Password';
    $wlf['emp_address'] = 'Address';
    $wlf['email'] = 'Email';
    $wlf['phone'] = 'Phone';
    $wlf['emp_status'] = 'Status';
    return $wlf;
}


function wlogFields_employee_work_centre()
{   
    $wlf['ewp_date'] = 'Date';
    $wlf['ewp_ob'] = 'O.B';
    $wlf['ewp_ob_mode'] = 'O.B Mode';
    $wlf['ewp_day_wage'] = 'Day Full';
    $wlf['ewp_day_hourly_wage'] = 'Day Hrly';
    $wlf['ewp_day_ot_wage'] = 'Day OT';
    $wlf['ewp_night_wage'] = 'Night Full';
    $wlf['ewp_night_hourly_wage'] = 'Night Hrly';
    $wlf['ewp_night_ot_wage'] = 'Night OT';
    $wlf['ewp_salary_wage'] = 'Salary';
    $wlf['ewp_status'] = 'Status';
    return $wlf;
}

function wlogFields_workcentres()
{   
    $wlf['wcntr_date'] = 'Date';
    $wlf['wcntr_ownership'] = 'Ownership';
    $wlf['wcntr_capital'] = 'Capital';
    $wlf['wcntr_name'] = 'Name';
    $wlf['wcntr_fk_workcentre_registration_details'] = 'Reg.Name';
    $wlf['wcntr_status'] = 'Status';
    return $wlf;
}

function wlogFields_workcentre_registration_details()
{   
    $wlf['wrd_date'] = 'Date';
    $wlf['wrd_name'] = 'Reg Name';
    $wlf['wrd_address'] = 'Adress';
    $wlf['wrd_phone'] = 'Phone';
    $wlf['wrd_email'] = 'Email';
    $wlf['wrd_tin'] = 'Tin';
    $wlf['wrd_licence'] = 'License';
    $wlf['wrd_cst'] = 'CST';
    $wlf['wrd_status'] = 'Status';
    return $wlf;
}

function wlogFields_owners()
{   
    $wlf['ownr_date'] = 'Date';
    $wlf['ownr_name'] = 'Name';
    $wlf['ownr_address'] = 'Address';
    $wlf['ownr_phone'] = 'Phone';
    $wlf['ownr_status'] = 'Status';
    return $wlf;
}

function wlogFields_rental_details()
{   
    $wlf['rntdt_date'] = 'Date';
    $wlf['rntdt_fk_owners'] = 'Owner';
    $wlf['rntdt_advance'] = 'Advance';
    $wlf['rntdt_ob'] = 'O.B';
    $wlf['rntdt_ob_mode'] = 'O.B Mode';
    $wlf['rntdt_instalment_amount'] = 'Installment';
    $wlf['rntdt_instalment_period'] = 'Period';
    $wlf['rntdt_auto_add'] = 'Auto Add';
    $wlf['rntdt_start_from'] = 'Start From';
    return $wlf;
}

function wlogFields_vehicles()
{   
    $wlf['vhcl_date'] = 'Date';
    $wlf['vhcl_no'] = 'No';
    $wlf['vhcl_name'] = 'Name';
    $wlf['vhcl_length'] = 'Length';
    $wlf['vhcl_breadth'] = 'Breadth';
    $wlf['vhcl_height'] = 'Height';
    $wlf['vhcl_remarks'] = 'Remarks';
    $wlf['vhcl_ownership'] = 'Ownership';
    $wlf['vhcl_status'] = 'Status';
    return $wlf;
}

function wlogFields_vehicle_workcentres()
{   
    $wlf['vwc_date'] = 'Date';
    $wlf['vwc_cost'] = 'Cost';
    $wlf['vwc_ob'] = 'O.B';
    $wlf['vwc_ob_mode'] = 'O.B Mode';
    $wlf['vwc_hourly_rate'] = 'Hourly';
    $wlf['vwc_daily_rate'] = 'Daily';
    $wlf['vwc_monthly_rate'] = 'Monthly';
    $wlf['vwc_sold_price'] = 'Sold Price';
    $wlf['vwc_status'] = 'Status';
    return $wlf;
}


function wlogFields_vehicles_employees()
{
    $wlf['vemp_fk_employees'] = 'Labour';
    $wlf['vemp_fk_vehicles'] = 'Vehicle';
    $wlf['vemp_is_default'] = 'Is default';
    
    return $wlf;
}


// freight_charges
function wlogFields_freight_charges()
{
    $wlf['fc_fk_workcentres'] = 'Workcentre';
    $wlf['fc_fk_party_destinations'] = 'Destination';
    $wlf['fc_fk_vehicles'] = 'Vehicle';
    $wlf['fc_rent'] = 'Rent';
    $wlf['fc_add_rent'] = 'Add Rent';   
    $wlf['fc_bata'] = 'Bata';
    $wlf['fc_add_bata'] = 'Add Bata';
    $wlf['fc_loading'] = 'Loading';
    $wlf['fc_add_loading'] = 'Add Loading';
    
    return $wlf;
}

// inter_freight_charges
function wlogFields_inter_freight_charges()
{
    $wlf['ifc_fk_workcentres_from'] = 'Workcentre From';
    $wlf['ifc_fk_workcentres_to'] = 'Workcentre To';
    $wlf['ifc_fkey_vehicles'] = 'Vehicle';
    $wlf['ifc_rent'] = 'Rent';
    $wlf['ifc_add_rent'] = 'Add Rent'; 
    $wlf['ifc_bata'] = 'Bata';
    $wlf['ifc_add_bata'] = 'Add Bata';  
    $wlf['ifc_loading'] = 'Loading';
    $wlf['ifc_add_loading'] = 'Add Loading';
    
    return $wlf;
}

function wlogFields_item_category()
{   
    $wlf['itmcat_name'] = 'Name';
    $wlf['itmcat_status'] = 'Status';
    return $wlf;
}

function wlogFields_item_heads()
{   
    $wlf['itmhd_fk_item_category'] = 'Category';
    $wlf['itmhd_name'] = 'Name';
    $wlf['itmhd_status'] = 'Status';
    return $wlf;
}

function wlogFields_units()
{   
    $wlf['unt_batch'] = 'Batch No:';
    $wlf['unt_name'] = 'Unit Name';
    $wlf['unt_parent'] = 'Parent';
    $wlf['unt_is_parent'] = 'Batch parent';
    $wlf['unt_relation'] = 'Relation with parent';
    return $wlf;
}


function wlogFields_items()
{   
    $wlf['itm_fk_item_head'] = 'Item Head';
    $wlf['itm_name'] = 'Name';
    $wlf['itm_fk_units'] = 'Default Unit';
    $wlf['itm_p_vat'] = 'VAT on Purchase';
    $wlf['itm_p_cess'] = 'CESS on Purchase';
    $wlf['itm_s_vat'] = 'VAT on sale';
    $wlf['itm_s_cess'] = 'CESS on sale';
    $wlf['itm_status'] = 'Status';
    return $wlf;
}

function wlogFields_item_units_n_rates()
{   
    $wlf['iur_fk_units'] = 'Unit';
    $wlf['iur_p_rate'] = 'Purchase Rate';
    $wlf['iur_s_rate'] = 'Selling Rate';
    return $wlf;
}



function wlogFields_opening_stock()
{   
    $wlf['ostk_fk_workcentre'] = 'Workcentre';
    $wlf['ostk_fk_items'] = 'Item';
    $wlf['ostk_date'] = 'Date';
    $wlf['ostk_qty'] = 'Quantity';
    $wlf['ostk_fk_units'] = 'Unit';
    $wlf['ostk_rate'] = 'Stock value';
    return $wlf;
}

    
function wlogFields_individual_rates()
{   
    $wlf['indv_fk_workcentres'] = 'Workcentre';
    $wlf['indv_fk_party_destinations'] = 'Destination';
    $wlf['indv_fk_items'] = 'Item';
    $wlf['indv_fk_units'] = 'Unit';
    $wlf['indv_p_rate'] = 'Purchase Rate';
    $wlf['indv_s_rate'] = 'Selling Rate';
    return $wlf;
}

function wlogFields_workcentre_rates()
{   
    $wlf['wrt_fk_workcentres_to'] = 'Workcentre';
    $wlf['wrt_fk_items'] = 'Item';
    $wlf['wrt_fk_units'] = 'Unit';
    $wlf['wrt_s_rate'] = 'Selling Rate';
    return $wlf;
}


function wlogFields_parties()
{
    $wlf['pty_name'] = 'Name';
    $wlf['pty_date'] = 'Date';
    $wlf['pty_phone'] = 'Phone';
    $wlf['pty_email'] = 'Email';
    $wlf['pty_status'] = 'Status';
    return $wlf;
}

function wlogFields_party_license_details()
{   
    $wlf['pld_date'] = 'Date';
    $wlf['pld_firm_name'] = 'Reg Name';
    $wlf['pld_address'] = 'Adress';
    $wlf['pld_phone'] = 'Phone';
    $wlf['pld_email'] = 'Email';
    $wlf['pld_tin'] = 'Tin';
    $wlf['pld_licence'] = 'License';
    $wlf['pld_cst'] = 'CST';
    $wlf['pld_status'] = 'Status';
    return $wlf;
}

function wlogFields_party_destinations()
{
    $wlf['pdst_date'] = 'Date';              
    $wlf['pdst_name'] = 'Destination';                
    $wlf['pdst_fk_party_license_details'] = 'Reg Name';          
    $wlf['pdst_fk_parties'] =  'Party';  
    $wlf['pdst_phone'] = 'Phone';          
    $wlf['pdst_email'] = 'Email';          
    $wlf['pdst_category'] = 'Category';          
    $wlf['pdst_status'] = 'Status';          
    
    return $wlf;
}

function wlogFields_party_vehicles()
{
    $wlf['pvhcl_fk_parties'] = 'Party';          
    $wlf['pvhcl_name'] = 'Name';          
    $wlf['pvhcl_no'] = 'No:';          
    $wlf['pvhcl_length'] = 'Lenght';          
    $wlf['pvhcl_breadth'] = 'Breadth';          
    $wlf['pvhcl_height'] = 'Height';          
    $wlf['pvhcl_xheight'] = 'XHeight';          
    $wlf['pvhcl_status'] = 'Status';    
    return $wlf;
}

function wlogFields_destination_workcentres()
{
    $wlf['dwc_fk_workcentres'] = 'Workcentre';  
    $wlf['dwc_fk_party_destinations'] = 'Destination';  
    $wlf['dwc_date'] = 'Date';  
    $wlf['dwc_ob'] = 'O.B';  
    $wlf['dwc_ob_mode'] = 'Mode';  
    $wlf['dwc_credit_lmt'] = 'Cr Limit';  
    $wlf['dwc_debt_lmt'] = 'Dr Limit';  
    $wlf['dwc_status'] = 'Status';  
    return $wlf;
}

function wlogFields_party_vehicle_rents()
{
    $wlf['pvr_fk_workcentres'] = 'Workcentre'; 
    $wlf['pvr_fk_party_destinations'] = 'Destination';
    $wlf['pvr_fk_party_vehicles'] = 'Vehicle';
    $wlf['pvr_rent']    = 'Freight Charge';
    return $wlf;
}

function wlogFields_purchase_bill_head()
{
//    $wlf[''] = ''; ;
//    return $wlf;
}

?>