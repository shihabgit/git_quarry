<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Tasks_model extends my_model
{

    function __construct()
    {
        parent::__construct();
        $this->loadTable(getTables('tasks'));
        $this->p_key = 'tsk_id';
        $this->nameField = 'tsk_name';
        $this->statusField = 'tsk_status';
    }

    function get_menu()
    {
        $tasks = get_tasks();
        return $this->get_recursion($tasks, NULL);
    }

    function get_recursion($tasks, $tsk_parent)
    {
        if (!$tsk_parent) //	Represents the outer most <ul>.
            $list = '<ul style="list-style-type: none;">';
        else
            $list = '<ul style="list-style-type: none;">';
        $result = filter($tasks, $tsk_parent);
        $initMenu = $result['init'];  //	Parent Menu Column
        $tasks = $result['task']; //	Child Menu Column.
        foreach ($initMenu as $index => $row)
        {
            $url = $row['tsk_url'];
            $text = $row['tsk_name'];
            $list .= "<li style='line-height:35px'>$row[tsk_pos]. $text (Id:$row[tsk_id], '$row[tsk_url]')";
            $list .= $this->get_recursion($tasks, $row['tsk_id']);
            $list .= '</li>';
        }
        $list .= '</ul>';
        return $list;
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

}
