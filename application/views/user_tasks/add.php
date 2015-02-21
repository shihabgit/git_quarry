
<link href="css/<?php echo $this->themes[$this->theme]['text']; ?>/user_tasks.css"  rel="stylesheet" type="text/css"></link>

<div class="dv-top-content" align="center">
    <?php echo form_open(""); ?>





    <table width="35%" cellpadding="5" cellspacing="0" class="tbl_input"> 
        <tr>
            <td>

                <div class="title-box">
                    <div id="img-container">
                        <img src="images/tasks.png" width="35" height="35"/>
                    </div>
                    <div id="title-container">
                        <div class="title-alone"><?php echo $heading; ?></div>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <p style="background-color:#FFF;color:#000;text-align: left;padding: 5px;">
                    1. Worklog producing unexpected results.
                </p>
                <ul class="main_container" style="width: 100%;height: 150px;"> 

                    <li>

                        <div class="sec_container">
                            <p class="input-categories">Employee Details</p>
                            <table class="sec_table">

                                <tr>   
                                    <th>Category</th>
                                    <td>

                                        <select name="emp_category" id="emp_category" onchange="resetOptions(this, 'utsk_fk_auth_users', 'employees/getEmployees', beforeAjax, afterAjax);
                                                settings();" >
                                                    <?php echo get_options2($emp_cats, ifSet('emp_category'), true, '--- select ---'); ?>
                                        </select>
                                    </td>
                                </tr>


                                <tr>
                                    <th>Employees</th>
                                    <td>

                                        <select name="utsk_fk_auth_users" id="utsk_fk_auth_users" >
                                            <?php echo get_options2($employees, set_value('utsk_fk_auth_users'), true, 'select', '', '', '---'); ?>
                                        </select>                                        


                                    <div class="ajaxLoaderContainer"> 
                                        <img src="images/ajax-loader2.gif"> 
                                        <img src="images/ajax-loader2.gif"> 
                                    </div>  

                                    </td>
                                </tr>

                            </table>
                        </div>
                    </li>
                </ul>
            </td>
        </tr>
    </table>
    <div id="taskMenu">
        <?php $style = $menu ? '' : 'style="display: none"'; ?>
        <div id="togle_img" <?php echo $style ?> >
            <img src="images/listDown.png" class="toggle_list" width="20px" height="20px"> 
            <img src="images/listUp.png" class="toggle_list" style="display:none" width="20px" height="20px">
        </div>
        <div id="menu_container">
            <?php if ($menu) echo $menu; ?> 
        </div>

    </div>
    <div id="submit_container">

        <input type="submit" name="button2" class="collapse_btn" value="Submit" />
    </div>
    <?php echo form_close(); ?>
</div> 	<!--<div class="dv-top-content" >-->


<script type="text/javascript">

    $(document).ready(function() {

        $('.toggle_list').click(function() {
            $(this).closest('#taskMenu').find('.toggle_list').toggle();

            // getting src of visible img.
            var src = $(this).closest('#taskMenu').find('.toggle_list:visible').prop('src');

            $('#taskMenu').find('.li_img').each(function() {
                if ($(this).prop('src') == src)
                    $(this).show();
                else
                    $(this).hide();
            })


            if (src == site_url + "images/listDown.png")
                $('#taskMenu').find('.innerUL').slideUp(500);
            else
                $('#taskMenu').find('.innerUL').slideDown(500);
        });


        $('#utsk_fk_auth_users').change(function() {
            $('#togle_img').hide();
            $.ajax({
                url: site_url + "user_tasks/getEmployeeTaskAjax",
                data: {emp_id: $(this).val()},
                type: 'post',
                success: function(result) {
                    //alert(result);
                    if (result)
                    {
                        $('#menu_container').html(result);
                        $('#togle_img').show();
                    }
                    else
                        $('#menu_container').html('No Tasks Found');
                }
            });

        });

    });

    function settings()
    {
        $('#menu_container').html('');
        $('#togle_img').hide();
    }

    function beforeAjax()
    {
        $('#utsk_fk_auth_users').hide();
        $('.ajaxLoaderContainer').show();
    }

    function afterAjax()
    {
        $('#utsk_fk_auth_users').show();
        $('.ajaxLoaderContainer').hide();
    }
</script>