<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Destination_workcentres_model extends my_model
{

    function __construct()
    {
        parent::__construct();
        $this->loadTable(getTables('destination_workcentres'));
        $this->p_key = 'dwc_id';
        $this->nameField = '';
        $this->statusField = 'dwc_status';
    }

    function getPartyWorkcentres($pty_id, $flag = true, $dwc_status = 1, $wcntr_status = 1, $pdst_status = 1)
    {
        $workcentres = array();

        $this->load->model('party_destinations_model', 'party_destinations');

        $pdst_ids = $this->party_destinations->getDestinationByParty($pty_id, $flag, $pdst_status);
        if (!$pdst_ids)
            return $workcentres;

        $this->db->from('destination_workcentres,workcentres');
        $this->db->select("DISTINCT(wcntr_id),workcentres.*");
        $str = $this->array_query($pdst_ids, 'dwc_fk_party_destinations');
        $this->db->where($str);

        if ($dwc_status)
            $this->db->where('dwc_status', $dwc_status);

        if ($wcntr_status)
            $this->db->where('wcntr_status', $wcntr_status);

        $this->db->where('wcntr_id = dwc_fk_workcentres');
        $this->db->order_by('wcntr_name', "asc");
        $result = $this->db->get();
        $workcentres = $result->result_array();
        if ($flag && $workcentres)
        {
            $wcntr_ids = array();
            foreach ($workcentres as $wc)
                $wcntr_ids[] = $wc['wcntr_id'];
            return $wcntr_ids;
        }
        return $workcentres;
    }

    function getDestinationWorkcentres($pdst_id, $flag = true, $dwc_status = 1, $wcntr_status = 1)
    {
        $workcentres = array();
        $this->db->from('destination_workcentres,workcentres');
        //$this->db->select("workcentres.*");
        $this->db->where('dwc_fk_party_destinations', $pdst_id);

        if ($dwc_status)
            $this->db->where('dwc_status', $dwc_status);

        if ($wcntr_status)
            $this->db->where('wcntr_status', $wcntr_status);

        $this->db->where('wcntr_id = dwc_fk_workcentres');
        $this->db->order_by('wcntr_name', "asc");
        $result = $this->db->get();
        $workcentres = $result->result_array();
        if ($flag && $workcentres)
        {
            $wcntr_ids = array();
            foreach ($workcentres as $wc)
                $wcntr_ids[] = $wc['wcntr_id'];
            return $wcntr_ids;
        }
        return $workcentres;
    }

    function index($pdst_id)
    {
        $this->db->from('destination_workcentres,workcentres,party_destinations');
        $this->db->where('dwc_fk_party_destinations', $pdst_id);
        $this->db->where('wcntr_id = dwc_fk_workcentres');
        $this->db->where('pdst_id = dwc_fk_party_destinations');
        $this->db->order_by('wcntr_name', "asc");
        $this->db->order_by('pdst_name', "asc");
        $result = $this->db->get();
        return $result->result_array();
    }

    function getByDestination($pdst_id, $flag = true, $dwc_status = 1)
    {
        $this->db->where('dwc_fk_party_destinations', $pdst_id);
        if ($dwc_status)
            $this->db->where('dwc_status', $dwc_status);
        $result = $this->db->get($this->table);
        $result = $result->result_array();
        if ($flag)
        {
            $ids = array();
            foreach ($result as $row)
                $ids[] = $row['dwc_id'];
            return $ids;
        }
        return $result;
    }

    function is_active_in_any_workcentre($pdst_id, $wcntr_status = 1)
    {
        $this->db->from("workcentres,$this->table");
        $this->db->where('dwc_status', 1);
        $this->db->where('dwc_fk_party_destinations', $pdst_id);
        $this->db->where('wcntr_id = dwc_fk_workcentres');

        if ($wcntr_status)
            $this->db->where('wcntr_status', $wcntr_status);

        $result = $this->db->get();
        $result = $result->result_array();

        if ($result)
            return TRUE;

        return FALSE;
    }

    function deactivate_in_all_workcentres($pdst_id)
    {
        $data[$this->statusField] = 2;

        $this->db->where('dwc_fk_party_destinations', $pdst_id);
        $this->db->update($this->table, $data);
    }
    
    // Old balance of Owner
    function reports_OB($wcntr_id,$from,$to)
    {   
        $this->db->from("$this->table,parties,party_destinations,workcentres");
        
        
        $bs = " (CASE ";
        $bs .= " when dwc_ob_mode = 1 then 'Creditors' ";
        $bs .= " when dwc_ob_mode = 2 then 'Debtors' ";
        $bs .= " else 'UNKNOWN' ";
        $bs .= " END) ";
     
            
            
        $select = ' dwc_id as ID, ';
        $select .= "'$this->table' as TBL, ";
        $select .= " dwc_date as DATE, ";
        $select .= " dwc_ob_mode as ACC_TYPE, ";       //  ACC_TYPE = 1 is Income/Credit. ACC_TYPE = 2 is Expense/Debt.
        $select .= " CONCAT(CONCAT(CONCAT('O.B of Destination: ',pdst_name),' of Party: '),pty_name) as DESCRIPTION, "; 
        
        // Category for Balance Sheet. It must match with any of the 'Sub_category' described in my_controller/get_BS_Categories()
        $select .= " $bs as BS, "; 
        
        // Category for Profit & Loss.
        $select .= " '' as PL, "; 
        $select .= ' wcntr_name as WORKCENTRE, ';
        $select .= ' dwc_ob as AMOUNT';
  
        
        $this->db->select($select,FALSE);
        
        
        if(is_array($wcntr_id))
        {
            $str = $this->array_query($wcntr_id, 'dwc_fk_workcentres');
            $this->db->where($str);
        }
        else
            $this->db->where("dwc_fk_workcentres",$wcntr_id);
        
        if($from)
            $this->db->where('dwc_date >= ', getSqlDate($from));
        
        if($to)
            $this->db->where('dwc_date <= ', getSqlDate($to));
        
        $this->db->where("dwc_ob > ",0);
        $this->db->where("pdst_id = dwc_fk_party_destinations"); 
        $this->db->where("pty_id = pdst_fk_parties"); 
        $this->db->where("wcntr_id = dwc_fk_workcentres");
        
        $this->db->order_by('dwc_date', 'asc');
        $this->db->order_by('wcntr_name', 'asc');        

        $query = $this->db->get();
        $result = $query->result_array();
        
//        echo "<br>".$this->db->last_query()."<br>";
//        echo "<br>";print_r($result);
        
        return $result;
        
    }
    
    
    function reports($wcntr_id, $from, $to)
    {
        $data = $this->reports_OB($wcntr_id, $from, $to);
        return $data;
    }

}

?>