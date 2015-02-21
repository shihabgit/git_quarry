<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo $title; ?></title>
        <base href="<?php echo base_url(); ?>" >
            
            
        
        <?php
        echo '<script type="text/javascript" >';
        echo 'var base_url = "' . base_url() . '";';
        echo 'var site_url = "' . site_url() . '/"; ';
        echo 'var user_name = "' . $this->user_name . '"; ';
        echo 'var firm_id = "' . $this->firm_id . '"; ';
        echo 'var firm_name = "' . $this->firm_name . '"; ';
        echo 'var firm_details = [];';
        foreach ($this->firm_dt as $key => $dt)
            echo "firm_details.push('$dt') ;";
        echo '</script>';
        ?>


        <link href="images/favicon.gif" rel="icon" type="image/x-icon" />
        
        <link href="css/collapsible.css" rel="stylesheet" type="text/css"/>
        
        
        
        
<!--        <link href="css/<?php echo $this->themes[$this->theme]['text']; ?>/main.css"  rel="stylesheet" type="text/css"/>
        <link href="css/<?php echo $this->themes[$this->theme]['text']; ?>/settings.css"  rel="stylesheet" type="text/css"/>
        <link href="css/<?php echo $this->themes[$this->theme]['text']; ?>/body.css" rel="stylesheet" type="text/css" />
        <link href="css/<?php echo $this->themes[$this->theme]['text']; ?>/shortcut_menu.css" rel="stylesheet" type="text/css" />-->
        
        
        <link href="css/black/main.css"  rel="stylesheet" type="text/css"/>
        <link href="css/black/settings.css"  rel="stylesheet" type="text/css"/>
        <link href="css/black/body.css" rel="stylesheet" type="text/css" />
        <link href="css/black/shortcut_menu.css" rel="stylesheet" type="text/css" />
        
        
        
        
        
        
        <link rel="stylesheet" type="text/css" href="css/error_tooltip.css" />
        <link href="css/tooltip.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" type="text/css" href="css/popup_box.css" />
        <link rel="stylesheet" type="text/css" href="css/table.css" />


        <link href="SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css" />
        <script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>




<!--        <script type="text/javascript" src="js/jquery.js"></script> -->

        <script type="text/javascript" src="js/jquery1.11.0.js"></script>
        <script type="text/javascript" src="js/jquery.min.js"></script>
        <script type="text/javascript" src="js/jquery-ui.js"></script>
        <script type="text/javascript" src="js/jquery-ui.min.js"></script>

        <!--    Date Picker -->
        <link rel="stylesheet" href="datepicker/css/datepicker.css" type="text/css" />
        <!--<script type="text/javascript" src="datepicker/js/jquery.js"></script>-->
        <script type="text/javascript" src="datepicker/js/datepicker.js"></script>
        <script type="text/javascript" src="datepicker/js/eye.js"></script>
        <script type="text/javascript" src="datepicker/js/utils.js"></script>
        <script type="text/javascript" src="datepicker/js/layout.js?ver=1.0.2"></script>





        <!--            Sticky navigation menu on scrolling.-->
        <link rel="stylesheet" href="plugins/sticky_menu_bar/css/style.css"> 
            <script src="plugins/sticky_menu_bar/js/jquery-scrolltofixed-min.js" type="text/javascript"></script>
            <script type="text/javascript">
                $(document).ready(function() {
                    $('.nav').scrollToFixed();
                });
            </script>





            <!--DropDown div element-->

            <!--<link href="css/<?php echo $this->themes[$this->theme]['text']; ?>/dropDownDiv.css" rel="stylesheet" type="text/css"> </link>-->
            
            <link href="css/black/dropDownDiv.css" rel="stylesheet" type="text/css"> </link>
            <script type="text/javascript" src="plugins/DropDownMenu/js/dropDownMenu.js" ></script>


    </head>
    <body> 
        <div class="dv_head_container" >
            <table width="100%" cellpadding="0" cellspacing="0" id="tbl-main">
                <tr class="logo_tr">
                    <td width="100%" align="center" style="padding:0px 10px; border-bottom: 1px solid #000;border-left: 1px solid #000;">
                        <div style="float:right">
                            <img src="images/banner-image2.png" height=150px /></div>
                        <div style="float:left">
                            <img src="images/banner-image1.png" height=150px /></div>
                        <p> 
                            <span id="logohead">The Quarry <span id="vesion">Version: <?php echo $this->version; ?></span></span><br />
                            <span id="logohead2" >The ultimate quarry & crusher solution</span> 
                        </p>
                    </td>
                </tr>


                <tr align="center">

                    <!-- class="nav" is the part of the plugin 'Sticky navigation menu'-->
                    <td id="main-menu-td"  > <!--  class="nav"-->
                        <?php
                        if ($show_shortcut_menu)
                            $this->load->view('shortcut_menu');
                        ?>

                        <div id="main-menu-container"> 
                            <div class="logout-nav"><?php echo anchor('index/logout', '<img src="images/logout.png" title="Logout"/>'); ?></div> 
                            <!--                            <div class="user-nav"></div> -->


                            <div class="dropDownBox">                    
                                <div class="dropdown">
                                    <p class="account" >
                                        <?php echo $this->user_cat_name . " : " . $this->user_name . ' @ ' . $this->firm_name; ?>
                                    </p>
                                    <div class="submenu" style="display: none; ">

                                        <ul class="root">
                                            <?php
                                            foreach ($this->all_firms as $firm)
                                            {
                                                $class = $firm['firm_status'] == 1 ? 'active' : 'inactive';
                                                $img = $firm['firm_status'] == 1 ? 'images/error.png' : 'images/success.png';
                                                echo '<li class="' . $class . '">';


                                                echo '<div style="float:left" class="firm_name_container">';
                                                echo '<span class="nmspn">' . $firm['firm_name'] . '</span>';
                                                echo '<input type="text" name="firm_name" class="firm_name" style="display:none;width:110px;" >';
                                                echo '<input type="hidden" value="' . $firm['firm_id'] . '" class="firm_id" >';
                                                echo '<input type="hidden" value="' . site_url("firms/changeLogin/$firm[firm_id]/$this->clsfunc") . '" class="url" >';
                                                echo '</div>';

                                                // A logged in firm must be active.
                                                // So the status of current firm couldn't be changed. Because we are logged in to it.
                                                // if you want to deactivate the current firm, log in to another firm, and then deactivate it.
                                                if ($firm['firm_id'] != $this->firm_id)
                                                    echo '<div style="float:right" class="firm_status"><img src="' . $img . '"></div>';

                                                echo '<div style="float:right" class="firm_edit"><img src="images/edit11.png"></div>';
                                                echo '<div class="clear_boath"></div>';

                                                echo '</li>';
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                </div>

                                <script type="text/javascript" src="js/firmEdit.js"></script>
                            </div>                           



                            <div style="float:left">
                                <?php
                                echo get_menu();
                                ?>
                            </div>
                        </div>
                    </td>
                </tr>





            </table>
        </div>
        <div class="dv_body_container" >

            <?php
            $this->load->view('message_box');
            $this->load->view($view);
            $this->load->view('footer');
            ?>