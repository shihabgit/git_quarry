
<!-- <div class="alert-box error"><span>error: </span>Write your error message here.</div>
 <div class="alert-box success"><span>success: </span>Write your success message here.</div> 
<div class="alert-box warning"><span>warning: </span>Write your warning message here.</div>
<div class="alert-box notice"><span>notice: </span>Write your notice message here.</div>-->
<?php
//echo '<div class="alert-box success"><span>success: </span>'.$message." Level : $message_level</div><br><br><br>";
if (!empty($message) && !empty($message_level))
{
    if ($message_level == 1)
        echo '<div class="alert-box success"><span class="spn_title">success: </span> '.$message. '</div> ';
    else if ($message_level == 2)
        echo '<div class="alert-box error"><span class="spn_title">Error: </span> '.$message. '</div> ';
    else if ($message_level == 3)
        echo '<div class="alert-box warning"><span class="spn_title">Warning: </span> '.$message. '</div> ';
    else if ($message_level == 4)
        echo '<div class="alert-box notice"><span class="spn_title">Notice: </span> '.$message. '</div> ';
    
         
    

}     ?> 