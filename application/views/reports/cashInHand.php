<link href="css/<?php echo $this->themes[$this->theme]['text']; ?>/reports.css"  rel="stylesheet" type="text/css"></link>
<div class="dv-top-content" align="center">
    <?php echo form_open("", array('id' => 'searchForm')); ?>

    <table width="35%" cellpadding="5" cellspacing="0" class="tbl_input">
        <tr>
            <td>
                <div class="title-box">
                    <div id="img-container">
                        <img src="images/search-user.png" width="35" height="35"/>
                    </div>
                    <div id="title-container">
                        <div class="title-alone"><?php echo $heading; ?></div>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td>

                <ul class="main_container" style="width: 100%;height: 300px;"> 
                    <li>
                        <div class="sec_container">
                            <p class="input-categories">Search Options</p>
                            <table class="sec_table">
                                <tr>
                                    <th>Workcentre : </th>
                                    <td>
                                        <select name="wcntr_id[]" multiple="multiple"  style="height:100px;">
                                            <?php echo get_options2($workcentres, ifSet('wcntr_id')); ?>
                                        </select>
                                        <p class="help">Hold <b>Ctrl</b> Key to select multiple workcentres.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Date :</th>
                                    <td>
                                        <div class="dateContainer" style="padding: 0px;margin:0px;float: left;">
                                            <div style="margin-right: 2px;float: left;" >
                                                <input class="dateField inputDate" placeholder="From" readonly="" type="text" name="f_date" value="<?php echo ifSet('f_date') ?>" style="width:105px" id="f_date"  /> 
                                            </div>
                                            <div style="margin-bottom: 1px;float: left;">
                                                <img src="images/calendar.gif"  class="calendarButton"> 
                                            </div>
                                        </div>  

                                        <div class="dateContainer" style="padding: 0px;margin:0px;margin-left:2px; float: left;">
                                            <div style="margin-right: 2px;float: left;" >
                                                <input class="dateField inputDate" placeholder="To" readonly="" type="text" name="t_date" value="<?php echo ifSet('t_date') ?>" style="width:105px" id="t_date"  /> 
                                            </div>
                                            <div style="margin-bottom: 1px;float: left;">
                                                <img src="images/calendar.gif"  class="calendarButton"> 
                                            </div>
                                        </div>             

                                    </td>
                                </tr>

                            </table>
                        </div>      <!--     End of Account Details-->
                    </li>


                </ul> <!--     End of <div class="main_container">-->
            </td>
        </tr>



    </table>
    <div id="submit_container">
        <hr /><br />

        <input type="submit" name="button2" class="collapse_btn" value="Submit" />
        <input type="button" class="collapse_btn reseter" name="button3" value="Reset" />
    </div>
    <?php echo form_close(); ?>
</div>





<div class="dv-bottom-content">
    <?php
    $totIncome = 0;
    $totExp = 0;
    $heads = array('Date', 'Workcentre', 'Description', 'Amount');
    $colspan = 5;
    ?>


    <table class="tbl_reports_container" cellpadding="0" cellspacing="0">
        <tr>
            <td id="td_income" class="td_box" valign="top" style="width:50%">
                <table class="tbl_reports_heads" cellspacing="0" style="width:100%">
                    <tr class="tr_mode"><th colspan="<?php echo $colspan; ?>">Income</th></tr>
                    <tr class="tr_header">

                        <th style="width:15%;">Date</th>
                        <th style="width:20%;">Workcentre</th>
                        <th>Description</th> <!--Leav at least one column without a specific 'width'.-->
                        <th style="width:20%;border-right:none;">Amount</th>	
                        <th style="width:13px;border-left:none;"></th> <!-- This column is to occuppy scroll bar -->
                    </tr>
                </table>
                <div class="dv_reports">
                    <table class="tbl_reports" cellspacing="0">

                        <?php
                        foreach ($cashInHand['credits'] as $val)
                        {
                            $totIncome = bcadd("$totIncome", "$val[AMOUNT]", 2);
                            echo '<tr class="tr_data">';
                            echo '<td>' . formatDate($val['DATE'],FALSE) . '</td>';
                            echo '<td>' . $val['WORKCENTRE'] . '</td>';
                            echo '<td>' . $val['DESCRIPTION'] . '</td>';
                            echo '<td>' . $val['AMOUNT'] . '</td>';
                            echo '</tr>';
                        }
                        ?>
                    </table>
                </div>     <!-- <div class="dv_reports">    -->   
                <p class="total"><?php echo "Total Income: " . $totIncome; ?></p>
            </td>



            <td id="td_expense" class="td_box" valign="top" style="width:50%">
                <table class="tbl_reports_heads" cellspacing="0" style="width:100%">
                    <tr class="tr_mode"><th colspan="<?php echo $colspan; ?>">Expense</th></tr>
                    <tr class="tr_header">

                        <th style="width:15%;">Date</th>
                        <th style="width:20%;">Workcentre</th>
                        <th>Description</th> <!--Leav at least one column without a specific 'width'.-->
                        <th style="width:20%;border-right:none;">Amount</th>	
                        <th style="width:13px;border-left:none;"></th> <!-- This column is to occuppy scroll bar -->
                    </tr>
                </table>
                <div class="dv_reports">
                    <table class="tbl_reports" cellspacing="0">

                        <?php
                        foreach ($cashInHand['debts'] as $val)
                        {
                            $totExp = bcadd("$totExp", "$val[AMOUNT]", 2);
                            echo '<tr class="tr_data">';
                            echo '<td>' . formatDate($val['DATE'],FALSE) . '</td>';
                            echo '<td>' . $val['WORKCENTRE'] . '</td>';
                            echo '<td>' . $val['DESCRIPTION'] . '</td>';
                            echo '<td>' . $val['AMOUNT'] . '</td>';
                            echo '</tr>';
                        }
                        ?>
                    </table>
                </div>     <!-- <div class="dv_reports">    -->   
                <p class="total"><?php echo "Total Expense: " . $totExp; ?></p>
            </td>

        </tr>

        <tr> 
            <td colspan="2">
                <p class="total">
                    <?php
                    $cb = bcsub("$totIncome", "$totExp", 2);
                    echo "Cash Balance : " . $cb;
                    //echo "(".$this->numberinwords->amount_in_text($cb).')';
                    ?>
                </p>
            </td>
        </tr>
    </table>




</div>   <!--<div class="dv-bottom-content"> -->




<script type="text/javascript">
    $(document).ready(function() {
        $('.tbl_reports_container .tbl_reports_heads').each(function() {
            $(this).find('tr.tr_header th').each(function() {
                var sourceOuterWidth = $(this).outerWidth();
                var col = $(this).index();
                var target = $(this).closest('.td_box').find('.tbl_reports  .tr_data:first td').eq(col);

                // Getting target widths.
                var targetWidth = target.width();
                var targetOuterWidth = target.outerWidth();
                var targetExtraWidth = targetOuterWidth - targetWidth;

                // Calculating new width for the target column.
                var newWidth = sourceOuterWidth - targetExtraWidth;

                // Resetting targetWidth.
                target.width(sourceOuterWidth);


            });
        });
    });
</script>
