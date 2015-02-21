<!DOCTYPE html>
<html lang='en'>
    <head>
        <meta charset="UTF-8" /> 
        <title>Firm</title>
        <base href="<?php echo base_url(); ?>" >
        <?php
        echo '<script type="text/javascript" >var base_url = "' . base_url() . '"; var site_url = "' . site_url() . '"; </script>';
        ?>
        <link rel="stylesheet" type="text/css" href="css/login/style.css" />
        <link rel="stylesheet" type="text/css" href="css/error_tooltip.css" />


        <script type="text/javascript" src="js/jquery1.11.0.js"></script>
        <script type="text/javascript" src="js/dropdown.js"></script>
    </head>
    <body>
        <div id="container"> 
            <?php
            if (!empty($message) && !empty($message_level))
            {
                if ($message_level == 1)
                    echo '<div class="log-success"><span class="log-span">Success: </span> ' . $message . '</div> ';
                if ($message_level == 2)
                    echo '<div class="log-fail"><span class="log-span">Error: </span> ' . $message . '</div> ';
            }
            ?>
            <div class="content">
                <img src="images/login/logo.png" id="logo"  >
            </div>
            <div class="content">
                
<!--                <div><b>check is user is permited to 'Add Firms' Task.</b></div>-->
                <?php
                $task = taskEnabled("firms/add");
                if ($is_allowed && $task == 1)
                {
                    echo form_open("", array('id' => 'Add_form'));
                    ?>
                    <div class="inset">
                        <h4>Create New Firm</h4>
                        <p style="margin-bottom : 5px;"> <label for="firm">Firm Name</label>
                            <input type="text" maxlength="20" id="new_firm">
                            <input type="button" value="Add" id="go_btn">
                        </p>
                        <div id="reponse"></div>
                        <hr>
                    </div>
                    <?php
                    echo form_close();
                }
                ?>



<?php echo form_open("", array('id' => 'List_form')); ?>

                <div class="inset">

                    <p> <label for="firm">Select a Firm</label> 
                        <select name="firm_id" id="firm_id">
                        <?php echo get_options2($firms, ifSet('firm_id'), false); ?>
                        </select>
                        <?php echo form_submit('submit', "Go"); ?>
<?php echo form_error('firm_id'); ?>
                    </p>
                </div>

                <p class="p-container">
              <!--    <span><a href="forgot_password"><?php //echo lang('login_forgot_password');    ?></a></span>-->
                </p>
<?php echo form_close(); ?>




            </div>

        </div>
        <script type="text/javascript">
            //Data saving to database
            $('#Add_form #go_btn').click(function() {

                //Showing Loading image till ajax responds
                $("#Add_form #reponse").show();
                $("#Add_form #reponse").html('<img src="images/ajax-loader.gif" /> Loading....');

                //Sending Ajax 
                $.post(site_url + "firms/add", {
                    firm_name: $('#Add_form #new_firm').val()
                }, function(result) {
                    if (result)
                    {
                        $('#Add_form #reponse').show();
                        // if successfully created.
                        if (result == 1)
                        {
                            $("#Add_form #reponse").html('<div class="pop_success"><img src="images/success_1.gif" width="15" height="15"  /> Firm Created Successfully!!!</div>');
                            // Listing newly created firm in the dropdown.
                            setOptions('#List_form #firm_id', 'firms/get_users_active_firms');

                            // Clearing input data
                            $('#Add_form #new_firm').val('');

                        }
                        // If any validation errors
                        else
                            $("#Add_form #reponse").html(result);
                    }

                });
            });
        </script>



    </body>
</html>
