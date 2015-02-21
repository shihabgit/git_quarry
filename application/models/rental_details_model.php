<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rental_details_model extends my_model
{
    
    function __construct()
    {
        parent::__construct();
        $this->loadTable(getTables('rental_details'));
        $this->p_key = 'rntdt_id';
        $this->nameField = '';
        $this->statusField = '';
    }
    
    function getInstallmentPeriods()
    {   
        $inst[1] = "Daily";
        $inst[2] = "Monthly";
        $inst[3] = "Annually";
        return $inst;
    }
    
    // Rental Advance paid for Owner
    function reports_Advance($wcntr_id,$from,$to)
    {   
        $this->db->from("$this->table,owners,workcentres");
        
        $select = 'rntdt_id as ID, ';
        $select .= "'$this->table' as TBL, ";
        $select .= " rntdt_date as DATE, ";
        $select .= "2 as ACC_TYPE, ";       //  ACC_TYPE = 1 is Income/Credit. ACC_TYPE = 2 is Expense/Debt.
        $select .= "CONCAT(CONCAT('Owner: ',ownr_name),' Advance.') as DESCRIPTION, "; 
        
        // Category for Balance Sheet. It must match with any of the 'Sub_category' described in my_controller/get_BS_Categories()
        $select .= "'Deposits' as BS, "; 
        
        // Category for Profit & Loss.
        $select .= "'' as PL, "; 
        $select .= 'wcntr_name as WORKCENTRE, ';
        $select .= 'rntdt_advance as AMOUNT';
        
        $this->db->select($select,FALSE);
                
        if(is_array($wcntr_id))
        {
            $str = $this->array_query($wcntr_id, 'rntdt_fk_workcentre');
            $this->db->where($str);
        }
        else
            $this->db->where("rntdt_fk_workcentre",$wcntr_id);
        
        if($from)
            $this->db->where('rntdt_date >= ', getSqlDate($from));
        
        if($to)
            $this->db->where('rntdt_date <= ', getSqlDate($to));
        
        $this->db->where("rntdt_advance > ",0);
        $this->db->where("ownr_id = rntdt_fk_owners");   
        $this->db->where("wcntr_id = rntdt_fk_workcentre");
        
        $this->db->order_by('rntdt_date', 'asc');
        $this->db->order_by('wcntr_name', 'asc');
        $query = $this->db->get();
        $result = $query->result_array();

//        echo "<br>".$this->db->last_query()."<br>";
//        echo "<br>";print_r($result);
        
        return $result;
        
    }
    
    // Old balance of Owner
    function reports_OB($wcntr_id,$from,$to)
    {   
        $this->db->from("$this->table,owners,workcentres");
        
        
        $bs = " (CASE ";
        $bs .= " when rntdt_ob_mode = 1 then 'Creditors' ";
        $bs .= " when rntdt_ob_mode = 2 then 'Debtors' ";
        $bs .= " else 'UNKNOWN' ";
        $bs .= " END) ";
     
            
            
        $select = ' rntdt_id as ID, ';
        $select .= "'$this->table' as TBL, ";
        $select .= " rntdt_date as DATE, ";
        $select .= " rntdt_ob_mode as ACC_TYPE, ";       //  ACC_TYPE = 1 is Income/Credit. ACC_TYPE = 2 is Expense/Debt.
        $select .= " CONCAT(CONCAT('Owner: ',ownr_name),' OB.') as DESCRIPTION, "; 
        
        // Category for Balance Sheet. It must match with any of the 'Sub_category' described in my_controller/get_BS_Categories()
        $select .= " $bs as BS, "; 
        
        // Category for Profit & Loss.
        $select .= " '' as PL, "; 
        $select .= 'wcntr_name as WORKCENTRE, ';
        $select .= ' rntdt_ob as AMOUNT';
  
        
        $this->db->select($select,FALSE);
        
        
        if(is_array($wcntr_id))
        {
            $str = $this->array_query($wcntr_id, 'rntdt_fk_workcentre');
            $this->db->where($str);
        }
        else
            $this->db->where("rntdt_fk_workcentre",$wcntr_id);
        
        if($from)
            $this->db->where('rntdt_date >= ', getSqlDate($from));
        
        if($to)
            $this->db->where('rntdt_date <= ', getSqlDate($to));
        
        $this->db->where("rntdt_ob > ",0);
        $this->db->where("ownr_id = rntdt_fk_owners");   
        $this->db->where("wcntr_id = rntdt_fk_workcentre");
        
        $this->db->order_by('rntdt_date', 'asc');
        $this->db->order_by('wcntr_name', 'asc');        

        $query = $this->db->get();
        $result = $query->result_array();
        
//        echo "<br>".$this->db->last_query()."<br>";
//        echo "<br>";print_r($result);
        
        return $result;
        
    }
    
    
    function reports($wcntr_id, $from, $to)
    {
        $data = $this->reports_Advance($wcntr_id, $from, $to);
        $data = array_merge($data,$this->reports_OB($wcntr_id, $from, $to));
        return $data;
    }
    
}