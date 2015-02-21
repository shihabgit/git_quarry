<?php

function isCommonTask($tsk_id)
{
    $CI = & get_instance();
    $taskTable = getTables('tasks');
    $CI->db->select('tsk_status');
    $CI->db->where('tsk_id', $tsk_id);
    $resultSet = $CI->db->get($taskTable);
    $result = $resultSet->row_array();
    if (isset($result['tsk_status']))
    {
        if ($result['tsk_status'] == 1)
            return true;
    }
    else
        return false;
}

function getTaskUrl($tsk_id)
{
    $CI = & get_instance();
    $taskTable = getTables('tasks');
    $CI->db->select('tsk_url');
    $CI->db->where('tsk_id', $tsk_id);
    $resultSet = $CI->db->get($taskTable);
    $result = $resultSet->row_array();
    if (isset($result['tsk_url']))
        return $result['tsk_url'];
    else
        return false;
}

function getTaskId($tsk_url)
{
    $CI = & get_instance();
    $taskTable = getTables('tasks');
    $CI->db->select('tsk_id');
    $CI->db->where('tsk_url', $tsk_url);
    $resultSet = $CI->db->get($taskTable);
    $result = $resultSet->row_array();
    if (isset($result['tsk_id']) && $result['tsk_id'])
        return $result['tsk_id'];
    else
        return false;
}

function get_menu()
{
    $tasks = get_tasks();
//    echo "Tasks: ";
//    print_r($tasks);
    return get_recursion($tasks, NULL);
}

/* function get_user()
  {	$CI 		=	& get_instance();
  $user_name	=	$CI->admin_entry->get_admin();
  $CI->db->select(array('emp_id','empcat_id'));
  $CI->db->where('emp_usrnm',$user_name);
  $result		=	$CI->db->get(getTables('employees'));
  return $result->row_array();
  }
 */

function taskEnabled($task,$userId='')
{
    $CI = & get_instance();

    if (!is_numeric($task)) // If url
        $task = getTaskId($task);
    
    if (!$task)
        return "Task Not Defined!";

    // Check is common task
    if (isCommonTask($task))
        return 1; //Success.
    
    $userId = $userId ? : $CI->ion_auth->get_user_id();

    $CI->db->where('utsk_fk_auth_users', $userId);
    $CI->db->where('utsk_fk_tasks', $task);

    $count = $CI->db->count_all_results(getTables('user_tasks'));
    if (!$count)
        return "This task is not assigned to you.";
    return 1; //Success.
}


function get_TSK_STATUS($status)//	Values of TSK_STATUS
{
    switch ($status)
    {
        case "active": return 1;
            break;
        case "suspend": return 2;
            break;
    }
}

function get_TSK_DISPLAY($display)//	Values of TSK_DISPLAY
{
    switch ($display)
    {
        case "on": return 1;
            break;
        case "off": return 2;
            break;
    }
}

function get_recursion($tasks, $tsk_parent)
{
    if (!$tsk_parent) //	Represents the outer most <ul>.
        $list = '<ul id="MenuBar1" class="MenuBarHorizontal">';
    else
        $list = '<ul>';
    $result = filter($tasks, $tsk_parent);
    $initMenu = $result['init'];  //	Parent Menu Column
    $tasks = $result['task']; //	Child Menu Column.
    foreach ($initMenu as $index => $row)
    {
        $url = $row['tsk_url'];
        $text = $row['tsk_name'];
        if ($url != '#') //	Having No Child Menu
        {   $tskEnabled = taskEnabled($row['tsk_id']);
            if ($tskEnabled==1 && $row['tsk_display'] == get_TSK_DISPLAY('on'))
                $list .= '<li>' . anchor($url, $text, array('title' => $text)) . '</li>';
        }
        else
        {
            if (haveEnabledSubMenu($tasks, $row['tsk_id']) /* || ($row['tsk_status'] == get_TSK_STATUS('active')) */)
            {
                $list .= '<li><a class="MenuBarItemSubmenu">' . $text . '</a>';
                $list .= get_recursion($tasks, $row['tsk_id']);
                $list .= '</li>';
            }
        }
    }
    $list .= '</ul>';
    return $list;
}

function haveEnabledSubMenu($tasks, $tsk_id, $flag = false)
{
    foreach ($tasks as $key => $val)
        if (($val['tsk_parent'] == $tsk_id))
        {   $tskEnabled = taskEnabled($val['tsk_id']);
            if ($val['tsk_url'] == '#')
                $flag = haveEnabledSubMenu($tasks, $val['tsk_id'], $flag);
            else if (($tskEnabled == 1) && ($val['tsk_display'] == 1))
                $flag = true;
        }
    return $flag;
}

function filter($tasks, $tsk_parent)
{
    $filter = array('init' => array(), 'task' => array());
    foreach ($tasks as $index => $tsk)
    {
        if ($tsk['tsk_parent'] == $tsk_parent)
            $filter['init'][$index] = $tsk;
        else
            $filter['task'][$index] = $tsk;
    }
    return $filter;
}

function get_tasks()
{
    $taskTable = getTables('tasks');
//    echo "<br>Tsk_tbl: ".$taskTable;
    $CI = & get_instance();
    $CI->db->order_by('tsk_parent');
    $CI->db->order_by('tsk_pos');
    $result = $CI->db->get($taskTable);

//    echo $CI->db->last_query();
    return $result->result_array();
}

?>