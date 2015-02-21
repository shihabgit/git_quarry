<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Units_model extends my_model
{

    function __construct()
    {
        parent::__construct();
        $this->loadTable(getTables('units'));
        $this->p_key = 'unt_id';
        $this->nameField = 'unt_name';
        $this->statusField = '';
    }
    
    function getBatchNo($unt_id)
    {
        $data = $this->getById($unt_id);
        if($data['unt_batch'])
            return $data['unt_batch'];
        return;
    }
    
    /**
     * Function returns all other units in the batch of the unit represented by $unt_id (Including the current unit)
     * @param type $unt_id : Current unit.
     */
    function getSiblings($unt_id)
    {
        $batch_no = $this->getBatchNo($unt_id);
        $units = $this->getUnitsOfBatch($batch_no);
        $options = $this->make_options($units, 'unt_id', 'unt_name');
        return $options;
    }

    function getNextBatchNo()
    {
        $this->db->select_max('unt_batch');
        $query = $this->db->get($this->table);
//        $result = $query->row_data();
        $result = $query->row_array();
        return $result['unt_batch'] + 1;
    }

    /**
     * function returns the parent unit of the given batch.
     * @param type $unt_batch
     */
    function getBatchParent($unt_batch,$detailed=true)
    {   
        $where['unt_batch'] = $unt_batch;
        $where['unt_is_parent'] = 1;
        $data = $this->get_row($where);
        if($detailed)
            return $data;
        return $data['unt_id'];
    }
    
    /**
     * function returns the parent unit of the batch of the given unit.
     * @param type $unt_id
     */
    function getBatchParentByUnit($unt_id)
    {
        $batch_no = $this->getBatchNo($unt_id);
        $parent = $this->getBatchParent($batch_no);
        return $parent;
    }

    function getUnitsOfBatch($unt_batch)
    {
        // Getting parent unit first.
        $units[] = $this->getBatchParent($unt_batch);
        $units[0]['parent_name'] = ''; // There are no parent units.
        $parents_id = $this->getBatchParent($unt_batch,FALSE);
        $unsorted_units = $this->get_data('', array('unt_batch' => $unt_batch), '', '', 'unt_is_parent');
        $copy_of_unsorted_units = $unsorted_units;
        
        // Sorting in the order of parent -> child.
        foreach ($unsorted_units as $row1)
            foreach ($copy_of_unsorted_units as $row2)
                if ($row2['unt_parent'] == $parents_id)
                {   
                    // Getting details of parent.
                    $parent_details = $this->getById($parents_id);
                    $unit_row = $row2;
                    $unit_row['parent_name'] = $parent_details['unt_name'];
                    $units[] = $unit_row;
                    $parents_id = $row2['unt_id'];
                    break;
                }
        return $units;
    }
    
    function getItemBatch($itm_id)
    {
        $item_details = $this->getById($itm_id,'itm_id','items');
        $unt_id = $item_details['itm_fk_units'];
        $unit_details = $this->getById($unt_id);
        return $unit_details['unt_batch'];
    }
    
    function getUnitsOfItem($itm_id)
    {
        $unt_batch = $this->getItemBatch($itm_id);
        return $this->getUnitsOfBatch($unt_batch);
    }

}

?>