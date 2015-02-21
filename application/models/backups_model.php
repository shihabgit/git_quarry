<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Backups_model extends my_model
{

    function __construct()
    {
        parent::__construct();
        $this->loadTable(getTables('backups'));
        $this->p_key = 'bkp_id';
        $this->nameField = '';
        $this->statusField = '';
    }

    function backUpData($wlog_id, $data, $ref_table, $ref_id)
    {
        $backup['bkp_fk_worklogs'] = $wlog_id;
        $backup['bkp_data'] = serialize($data);
        $backup['bkp_ref_table'] = $ref_table;
        $backup['bkp_ref_id'] = $ref_id;
        $this->db->insert($this->table, $backup);
    }

    function getUsersBackupsByRef($ref_id, $ref_table, $user_id, $wcntr_id)
    {
        $this->db->from('worklog_workcentres,verify,backups');
        $this->db->select('bkp_data');
        
        $this->db->where('bkp_ref_id', $ref_id);
        $this->db->where('bkp_ref_table', $ref_table);

        $this->db->where('wlog_wc_fk_worklogs = bkp_fk_worklogs');
        $this->db->where('wlog_wc_fk_workcentres',$wcntr_id);
        $this->db->where('verify_fk_worklog_workcentres = wlog_wc_id');
        $this->db->where('verify_fk_auth_users',$user_id);
        
        
        //Getting latest backup first.
        $this->db->order_by('bkp_id', "desc");

        $result = $this->db->get();
        $backups = $result->result_array();
        
//        echo $this->db->last_query();
        
        $user_backups = array();
        foreach ($backups as $row)
            $user_backups[] = unserialize($row['bkp_data']);
        return $user_backups;
    }

   /* function getUsersBackupsByRef($ref_id, $ref_table, $user_id, $wcntr_id, $firm_id)
    {
        $backups = $this->getBackupsByRef($ref_id, $ref_table);
        foreach ($backups as $row)
        {
            
        }

        $user_backups = array();
        foreach ($backups as $row)
            $user_backups[] = unserialize($row['bkp_data']);
        //$data[] = array('details'=>unserialize($row['bkp_data']),'wlog_id'=>$row['bkp_fk_worklogs']);
        return $user_backups;
    }*/

}

?>