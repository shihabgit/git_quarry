<div class="shortcut_menu_container">


    <div class="shortcut_menu left">
        <div class="menu_img"><?php echo anchor('', '<img  src="images/start.png" alt="" title="Start Page" />'); ?></div>
    </div>

    <?php
    if ($is_allowed && ((taskEnabled('workcentres/add') == 1) || (taskEnabled('workcentres') == 1)))
    {
        ?>   

        <div class="shortcut_menu left">
            <div class="menu_img"><img  src="images/workcentre.png" title="Workcentres"></div>
            <div class="action">
                <?php
                if (taskEnabled('workcentres/add') == 1)
                {
                    ?>
                    <div class="action_img"><?php echo anchor('workcentres/add', '<img  src="images/add1.png" title="Add New Workcentre">') ?> </div>    
                    <?php
                }
                if (taskEnabled('workcentres') == 1)
                {
                    ?>
                    <div class="action_img"><?php echo anchor('workcentres', '<img  src="images/list.png" title="List Workcentres">') ?></div>  
    <?php } ?>
            </div>
        </div>
        <?php
    }
    else if (taskEnabled('workcentres') == 1)
    {
        ?>
        <div class="shortcut_menu left">
            <div class="menu_img"><?php echo anchor('workcentres', '<img  src="images/workcentre.png" title="List Workcentres">'); ?></div>
        </div>

    <?php
    } ?>
    
    
    
    
    <?php
    if ((taskEnabled('employees/add') == 1) || (taskEnabled('employees') == 1))
    {
        ?>   

        <div class="shortcut_menu left">
            <div class="menu_img"><img  src="images/staffs.png" title="Employees"></div>
            <div class="action">
                <?php
                if (taskEnabled('employees/add') == 1)
                {
                    ?>
                    <div class="action_img"><?php echo anchor('employees/add', '<img  src="images/add1.png" title="Add New Employee">') ?> </div>    
                    <?php
                }
                if (taskEnabled('employees') == 1)
                {
                    ?>
                    <div class="action_img"><?php echo anchor('employees', '<img  src="images/list.png" title="List Employees">') ?></div>  
    <?php } ?>
            </div>
        </div>
        <?php
    }
    
    ?>
    
    
    
    
    <?php
    if ((taskEnabled('vehicles/add') == 1) || (taskEnabled('vehicles') == 1))
    {
        ?>   

        <div class="shortcut_menu left">
            <div class="menu_img"><img  src="images/vehicles.png" title="Vehicles"></div>
            <div class="action">
                <?php
                if (taskEnabled('vehicles/add') == 1)
                {
                    ?>
                    <div class="action_img"><?php echo anchor('vehicles/add', '<img  src="images/add1.png" title="Add New Vehicle">') ?> </div>    
                    <?php
                }
                if (taskEnabled('vehicles') == 1)
                {
                    ?>
                    <div class="action_img"><?php echo anchor('vehicles', '<img  src="images/list.png" title="List Vehicles">') ?></div>  
    <?php } ?>
            </div>
        </div>
        <?php
    }
    
    ?>
    
    
    
    
    
    <?php
    if ((taskEnabled('parties/add') == 1) || (taskEnabled('parties') == 1))
    {
        ?>   

        <div class="shortcut_menu left">
            <div class="menu_img"><img  src="images/party.png" title="Parties"></div>
            <div class="action">
                <?php
                if (taskEnabled('parties/add') == 1)
                {
                    ?>
                    <div class="action_img"><?php echo anchor('parties/add', '<img  src="images/add1.png" title="Add New Party">') ?> </div>    
                    <?php
                }
                if (taskEnabled('parties') == 1)
                {
                    ?>
                    <div class="action_img"><?php echo anchor('parties', '<img  src="images/list.png" title="List Parties">') ?></div>  
    <?php } ?>
            </div>
        </div>
        <?php
    }
    
    ?>
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    <?php
    if (taskEnabled('settings') == 1)
    {
        ?>

        <div class="shortcut_menu left">
            <div class="menu_img"><?php echo anchor('settings', '<img  src="images/firm-settings.png" alt="" title="Settings" />'); ?></div>
        </div>

        <?php }      ?>

    
    
    
    
    
    
     <?php
    if (taskEnabled('user_tasks/add') == 1)
    {
        ?>

    <div class="shortcut_menu left" style="padding: 5px 5px 0px 5px;height: 50px;">
            <div class="menu_img"><?php echo anchor('user_tasks/add', '<img  src="images/tasks.png" style="width:29px;height:33px;" title="Add Tasks" />'); ?></div>
        </div>

        <?php }      ?>

    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    <?php if(taskEnabled('worklogs')==1) 
    {   ?>
    <div class="shortcut_menu right">
<?php echo anchor('worklogs', '<div class="roundNumber blue">45</div>', array("class" => 'num')); ?>
<?php echo anchor('worklogs', '<div class="roundNumber red">8</div>', array("class" => 'num')); ?>
    </div>
    <?php } ?>
</div>	<!--<div class="shortcut_menu_container">-->