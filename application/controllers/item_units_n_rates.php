<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require 'my_controller.php';

class Item_units_n_rates extends My_controller
{

    function __construct()
    {
        parent::__construct();

        $this->load->model('items_model', 'items');
        $this->load->model('units_model', 'units');
        $this->load->model('item_units_n_rates_model', 'iur');
        $this->load->model('opening_stock_model', 'os');

        // Determining is the logged in user allowed to go forward with the current action.
        $this->isAllowed();

        $this->table = 'item_units_n_rates';
        $this->p_key = 'iur_id';
    }
    
    function get_wlog() //Called by ajax
    {
        $model = $this->iur;
        $wlogs = $this->init_wlog($this->p_key, $model);

        if ($wlogs[0])
        {
            $latest_class = 'wlog_latest'; // Latest details about the worklog. Ie: the data from ref_tables, not from Tbl:backups.
            $wlog_fields = getWlogFields($this->table, 'keys');
            
            $units = $this->units->get_option();

            echo "<table>";

            // Creating headers.
            $headers = getWlogFields($this->table, 'all');
            echo '<tr>';
            foreach ($headers as $head)
                echo '<th>' . $head . '</th>';
            echo '</tr>';


            foreach ($wlogs as $key => $row)
            {
                echo '<tr class="' . $latest_class . '">';// Latest details about the worklog.
                $latest_class = '';
                foreach ($wlog_fields as $fld)
                {
                    $val = $row[$fld];
                    $edited = false;
                    if (isset($wlogs[$key + 1]) && ($wlogs[$key + 1][$fld] !== $val))
                        $edited = true;
                    
                    if ($fld == 'iur_fk_units')
                        $val = $units[$row[$fld]];

                    if ($edited)
                        echo '<td><span class="wlog_changed">' . $val . '</span></td>';
                    else
                        echo '<td>' . $val . '</td>';
                }
                echo '</tr>';
            }
            echo "</table>";
        }
        else
            echo "No Worklogs Found!!!";
    }


    function before_add()
    {
        // User is allowed to go forward iff he has assigned the task for "ADD ITEM".
        $this->isAllowedTask('items/add');

        if (($this->uri->segment(3) != 'jurk') || !$this->uri->segment(4)) // 'jurk' is just a string to identify what kind of the value passed through the uri_segment 4.
        {
            $msg = "Permission Error :- Bad Entry";
            $level = 2; // Having errors.
            $this->my_logout($msg, $level);
            return false;
        }

        $itm_id = $this->uri->segment(4);

        $data['title'] = 'Add Rates';
        $data['heading'] = 'ADD RATES';
        $data['firms'] = $this->firms->get_firms_options($this->user_id, 1);
        $data['workcentres'] = $this->workcentres->get_workcentres($this->user_id, '', 1);
        $data['item_details'] = $this->items->getById($itm_id);
        $data['units'] = $this->units->getUnitsOfItem($itm_id);
        $data['itm_id'] = $itm_id;
        $data['message'] = $this->session->flashdata('message');
        $data['message_level'] = $this->session->flashdata('message_level');

        //print_r($data['units']);
        $data['p_rates'] = array(); //Purchase rate.
        $data['s_rates'] = array(); // Sale rate.
        foreach ($data['workcentres'] as $wc)
            foreach ($data['units'] as $unt)
            {
                $data['p_rates'][$wc['wcntr_id']][$unt['unt_id']] = $this->iur->getPurchaseRate($wc['wcntr_id'], $unt['unt_id'], $itm_id);
                $data['s_rates'][$wc['wcntr_id']][$unt['unt_id']] = $this->iur->getSalesRate($wc['wcntr_id'], $unt['unt_id'], $itm_id);
            }



        // The values must be derived from the variable $data['units'], because it has sorted in parent->child order.
        $data['units_option'] = array();
        foreach ($data['units'] as $unit)
            $data['units_option'][$unit['unt_id']] = $unit['unt_name'];


        $this->_render_page($this->cls . '/add', $data);
        return;
    }

    function add()
    {

        // User is allowed to go forward iff he has assigned the task for "ADD ITEM".
        $this->isAllowedTask('items/add');

        $itm_id = $this->input->post('itm_id');

        $item_details = $this->items->getById($itm_id);

        // Recieving input 
        $input = $this->input->post();

        // Backing up previous unit-rates for worklog backup.
        $prev_iur = array();
        foreach ($input['iur_fk_workcentres'] as $wcntr_id => $on_off)
        {
            $where['iur_fk_workcentres'] = $wcntr_id;
            $where['iur_fk_items'] = $itm_id;
            $prev_iur[$wcntr_id] = $this->iur->get_data('', $where);
        }


        // Before insert new data, deleting previous data.
        foreach ($input['iur_fk_workcentres'] as $wcntr_id => $on_off)
        {
            $where['iur_fk_workcentres'] = $wcntr_id;
            $where['iur_fk_items'] = $itm_id;
            $this->iur->delete_where($where);
        }

        foreach ($input['iur_fk_workcentres'] as $wcntr_id => $on_off)
        {
            $tbl_iur = array();
            $tbl_iur['iur_fk_workcentres'] = $wcntr_id;
            $tbl_iur['iur_fk_items'] = $itm_id;
            foreach ($input['iur_p_rate'][$wcntr_id] as $iur_fk_units => $iur_p_rate)
            {
                $tbl_iur['iur_fk_units'] = $iur_fk_units;
                $tbl_iur['iur_p_rate'] = $iur_p_rate;
                $tbl_iur['iur_s_rate'] = $input['iur_s_rate'][$wcntr_id][$iur_fk_units];
                $iur_id = $this->iur->insert($tbl_iur);

                // Setting details for worklogs for Tbl:item_units_n_rates.
                $wlog_wc1 = array();
                $msg = 'New rates for item: ';
                $msg .= ' <span class="wlg_name">' . $item_details['itm_name'] . '</span> has been added.';
                $wlog_wc1[$wcntr_id]['msg'] = $msg;
                $wlog_wc1[$wcntr_id]['action'] = $this->add;

                // Adding worklogs of Tbl:item_units_n_rates.
                $wlog_id = $this->add_logs($this->table, $iur_id, get_url($this->table), get_popup_id($this->table), $wlog_wc1, $this->add);

                // Backing up previous details for data recovery needs.
                if (to_be_backed_up($this->table) && isset($prev_iur[$wcntr_id])) //If need to be backed up and data has been edited.
                {
                    foreach ($prev_iur[$wcntr_id] as $pre_row)
                        if ($pre_row['iur_fk_units'] == $iur_fk_units)
                            $this->backups->backUpData($wlog_id, $pre_row, $this->table, $iur_id);
                }
            }


            if ($input['ostk_qty'][$wcntr_id])
            {
                $tbl_os = array();
                $tbl_os['ostk_fk_workcentre'] = $wcntr_id;
                $tbl_os['ostk_date'] = getSqlDateTime();
                $tbl_os['ostk_fk_items'] = $itm_id;
                $tbl_os['ostk_qty'] = $input['ostk_qty'][$wcntr_id];
                $tbl_os['ostk_fk_units'] = $input['ostk_fk_units'][$wcntr_id];
                $tbl_os['ostk_rate'] = $input['ostk_rate'][$wcntr_id];
                $ostk_id = $this->os->insert($tbl_os);

                // Setting details for worklogs for Tbl:item_units_n_rates.
                $wlog_wc2 = array();
                $msg = 'Opening stock of item: ';
                $msg .= ' <span class="wlg_name">' . $item_details['itm_name'] . '</span> on ' . date('d/m/Y H:i', strtotime($tbl_os['ostk_date'])) . ' has been added.';
                $wlog_wc2[$wcntr_id]['msg'] = $msg;
                $wlog_wc2[$wcntr_id]['action'] = $this->add;

                // Adding worklogs of Tbl:item_units_n_rates.
                $this->add_logs('opening_stock', $ostk_id, get_url('opening_stock'), get_popup_id('opening_stock'), $wlog_wc2, $this->add);
            }
        }

        //redirecting
        $this->session->set_flashdata('message', "Item rates and opening stock added successfully");
        $this->session->set_flashdata('message_level', 1); // Success
        redirect("items/index/action", 'refresh');
    }

}

?>