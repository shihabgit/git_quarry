<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User_tasks_model extends my_model
{

    var $common_task = 0;

    function __construct()
    {
        parent::__construct();
        $this->loadTable(getTables('user_tasks'));
        $this->p_key = 'utsk_id';
        $this->nameField = '';
        $this->statusField = '';

        $this->common_task = 1;
    }
    
    function getEmployeeTasks($emp_id)
    {
        return $this->getIds(array('utsk_fk_auth_users' => $emp_id), '', 'utsk_fk_tasks');
    }

    function get_menu($user_id)
    {   
        if(!$user_id) return '';
        $tasks = get_tasks();
        return $this->get_recursion($tasks, NULL, $user_id);
    }

    function haveSubMenu($tasks, $tsk_id, $flag = false)
    {
        foreach ($tasks as $key => $val)
            if (($val['tsk_parent'] == $tsk_id))
            {
                if ($val['tsk_url'] == '#')
                    $flag = $this->haveSubMenu($tasks, $val['tsk_id'], $flag);
                else
                    $flag = true;
            }
        return $flag;
    }

    function createCheckbox($checked, $disabled, $value, $name = '')
    {
        $disabled = ($disabled == 'disabled') ? ' disabled="disabled" ' : '';
        $checked = ($checked == 'checked') ? ' checked="" ' : '';
        $name = $name ? ' name="' . $name . '[]" ' : '';
        return "<input type='checkbox' $name $disabled $checked value='$value' />";
    }

    function get_recursion($tasks, $tsk_parent, $user_id)
    {
        if (!$tsk_parent) //	Represents the outer most <ul>.
        $list = '<ul class="task">';
    else
        $list = '<ul class="innerUL">';
        $result = filter($tasks, $tsk_parent);
        $initMenu = $result['init'];  //	Parent Menu Column
        $tasks = $result['task']; //	Child Menu Column.
        foreach ($initMenu as $index => $row)
        {
            $url = $row['tsk_url'];
            $name = $row['tsk_name'];
            $desc = $row['tsk_description'];
            $id = $row['tsk_id'];
            $status = $row['tsk_status'];
            $enabled = taskEnabled($id, $user_id);
            $enabled = ($enabled == 1) ? TRUE : FALSE;
            if ($url != '#')  //	Don't have child menu
            {
                if ($status == $this->common_task)
                    $list .= '<li class="child">' . $this->createCheckbox('checked', 'disabled', $id) . "$name <span class='description'>($desc)</span>" ;
                else if ($enabled)
                    $list .= '<li class="child">' . $this->createCheckbox('checked', '', $id, 'utsk_fk_tasks') ."$name <span class='description'>($desc)</span>" ;
                else
                    $list .= '<li class="child">' . $this->createCheckbox('', '', $id, 'utsk_fk_tasks') ."$name <span class='description'>($desc)</span>" ;
            }
            else
            {
                if ($status == $this->common_task)
                    $list .= '<li class="parent" disabled="disabled">' . $name ;
                else
                    $list .= '<li class="parent"><img src="images/listDown.png" class="li_img"> <img src="images/listUp.png" class="li_img" style="display:none"  > ' . $name ;
            }
            if ($this->haveSubMenu($tasks, $id))
                $list .= $this->get_recursion($tasks, $id, $user_id);
            $list .= '</li>';
        }
        $list .= '</ul>';
        return $list;
    }
    
    function deleteUsersTasks($where)
    {
        $this->db->delete($this->table, $where);
    }

}
