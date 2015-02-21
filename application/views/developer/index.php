<style type="text/css">
    .check_for
    {
        margin:auto;
        font-family:serif;
        text-align: left;
        font-size: 12pt;
        color: #D6B874;
    }

    .check_for h2
    {
        color: #CFDBC4;
    }
    .developer_btn{
        margin: 0px;
        border-left: 1px solid #999;
        border-right: 1px solid #000;
        border-top: 1px solid #999;
        border-bottom: 2px solid #000;
        color: #FF9900;
        /*clear: both;*/
        height: 27px;
        vertical-align: central;
        font-family: Sreda;
        font-size: 10pt;
        text-align: center;
        background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#333), to(#666)); /* Safari 5.1, Chrome 10+ */
        background: -webkit-linear-gradient(top, #333, #666); /* Firefox 3.6+ */
        background: -moz-linear-gradient(top, #333, #666); /* IE 10 */
        background: -ms-linear-gradient(top, #333, #666); /* Opera 11.10+ */
        background: -o-linear-gradient(top, #333, #666);
    }

    .developer_btn:hover{
        color: #FC3;
        text-shadow: 5px 5px 10px #FFF;
    }
</style>

<?php $this->load->view('developer/pop_add'); ?>
<div class="dv-top-content" align="center">
    <div class="check_for">
        <h2>Check the following before using the software</h2>
        <?php echo implode('<br>', $check); ?>
        <?php echo form_open("developer/add_all"); ?>
        <input type="submit" class="developer_btn" value="Set All" title="Set All.">
        <?php echo form_close(); ?><br>
        <?php if($after_set) echo implode('<br>', $after_set);?>
    </div>

    <table border="0" cellspacing="0" cellpadding="10" width="100%">
        <tr>
            <td style="width:70%" colspan="2"  valign="top">
                <div id="taskMenu" align="left">
                    <?php // echo htmlspecialchars(get_menu());?>
                    <?php echo $menu; ?>
                </div>
            </td>
            <td  valign="top">
                <table>
                    <tr>
                        <td>
                            <?php echo form_open("developer/createTable"); ?>
                            <input type="submit" class="developer_btn" value="Create Table" title="Createe Tables.">
                            <?php echo form_close(); ?>
                        </td>
                        <td>
                            <input type="button" class="developer_btn" value="Create Tasks" id="initTasksAdd" title="Add Task">
                        </td>
                    </tr>
                </table>


            </td>
        </tr>
    </table>
</div> 	<!--<div class="dv-top-content" >-->
<script type="text/javascript">

    $(document).ready(function() {

        // Opening popup box on clicking <img src="images/add.png" id="initOwnerAdd"> @ workcentres/pop_add.php
        $('#initTasksAdd').click(function() {

            //Initializing popup box  
            init_pop_tasks_add();

            //Loading popupBox.
            loadPopup('pop_tasks_add');
        });

    });
</script>