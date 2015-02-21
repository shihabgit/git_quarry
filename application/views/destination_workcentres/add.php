<link href="css/<?php echo $this->themes[$this->theme]['text']; ?>/reports.css"  rel="stylesheet" type="text/css"></link>
<script type="text/javascript" src="js/BC_Math/bcmath_library_for_jscript.js"></script>
<script type="text/javascript" src="js/tbl_taversor.js"></script>

<div class="dv-bottom-content">


    <table class="tbl_reports_container" cellpadding="0" cellspacing="0">
        <tr>
            <td valign="top">
                <div class="highlevel">
                    <div class="dv_heading">ADD RATES TO ITEM : <?php echo $item_details['itm_name'] ?></div>
                    <?php echo form_open("item_units_n_rates/add", array('id' => 'add_form')); ?>

                    <!--  class 'tbl_traversor' is a table in which user can traverse through its rows-columns by pressing ARROW/ENTER/SHIFT keys. it is used in 'js/focus_next_traversor.js'.-->
                    <table class="ratesTable tbl_traversor" cellspacing="0" style="width:100%">
                        <thead>
                            <tr>
                                <th>Firm</th>
                                <th>Workcentre</th>
                                <th>Rate</th>
                                <th style="width:90px;">Rate</th>
                            </tr>

                            
                        </thead>
                        <tbody>
                            <?php
                            if ($firms)
                            {


                                foreach ($firms as $firm_id => $firm_name)
                                {
                                    foreach ($workcentres as $wc)
                                    {
                                        if ($wc['wcntr_fk_firms'] == $firm_id)
                                        {
                                            $wc_id = $wc['wcntr_id'];

                                            // className 'tr_traversor' is used with 'js/focus_next_traversor.js'
                                            echo '<tr class="tr_traversor">';
                                            echo '<td style="padding:5px">' . $firm_name . '</td>';


                                            echo '<td style="padding:5px">';
                                            //$checked = " checked ";
                                            //echo '<input type="checkbox"' . $checked . ' name="iur_fk_workcentres[' . $wc_id . ']" /> ';

                                            echo '<input type="hidden" name="iur_fk_workcentres[' . $wc_id . ']" /> ';
                                            echo $wc['wcntr_name'];

                                            echo '</td>';





                                            echo '<td>';
                                            echo '<input type="text" name="ostk_qty[' . $wc_id . ']" class="numberOnly nextInput" style="width: 50%"/> ';
                                            echo '<select name="ostk_fk_units[' . $wc_id . ']" style="width: 44%" class="nextInput selunit">';
                                            echo get_options2($units_option, $item_details['itm_fk_units'], FALSE);

                                            echo '</select>';

                                            echo '</td>';

                                            echo '<td>';
                                            echo '<input type="text" name="ostk_rate[' . $wc_id . ']" class="numberOnly nextInput"/>';
                                            echo '</td>';
                                            echo '</tr>';
                                        }
                                    }
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                    <div id="submit_container">
                        <br><br> 
                        <input type="hidden" name="pty_id" value="<?php echo $pty_id; ?>">
                        <input type="submit" name="button2" class="collapse_btn" value="Submit" />
                        <input type="button" class="collapse_btn reseter" name="button3" value="Reset" />
                        <input type="checkbox" id="autofill"> Fill rates automatically.
                    </div>
                    <?php echo form_close(); ?>   
                </div> <!--<div class="highlevel">-->
            </td>
        </tr>
    </table>
</div> 	<!--<div class="dv-bottom-content" >-->



<script type="text/javascript">

    // The function is defined @ js/tbl_traversor.js
    initTraversor(false, true, true);

    $(document).ready(function() {

  /*      $('.basic').keyup(function(e) {
            var leftArrow = 37;
            var upArrow = 38;
            var rightArrow = 39;
            var downArrow = 40;
            var enter = 13;
            var shift = 16;
            var key = e.keyCode || e.which;

            if (key == enter)
            {
                e.preventDefault();
                return false;
            }

            // If user don't want to auto filling rates, return;
            if (!$('#submit_container #autofill').prop('checked'))
                return;

            // if not enter/shift/arrow key pressed.
            else if (key != enter && key != leftArrow && key != upArrow && key != rightArrow && key != downArrow && key != shift)
            {
                var mode = '';
                var relation = '';
                var parent_rate = '';//$(this).val();
                if ($(this).hasClass('pur'))
                    mode = '.pur';
                else if ($(this).hasClass('sale'))
                    mode = '.sale';
                else
                    alert('Logical Error Occured.');

                var fields = $(this).closest('tr').find(mode);
                $(this).closest('tr').find(mode).not(this).each(function() {
                    var index = fields.index(this);
                    parent_rate = fields.eq(index - 1).val();
                    relation = $(this).closest('td').find('.unt_relation').val();
                    $(this).val(bcmul(parent_rate, relation, 2));
                });


            }



        });
*/
/*
        $('.ratesTable tbody tr:eq(2) .rates').keyup(function(e) {
            var key = e.keyCode || e.which;
            if (key == 13)
            {
                e.preventDefault();
                return false;
            }

            // If user don't want to auto filling rates, return;
            if (!$('#submit_container #autofill').prop('checked'))
                return;

            zerox(2);
        });
*/


        /**
         * Function to copy entering rates of item in first workcentre to all workcentres.
         * @param {type} curRowIndex
         * @returns {undefined}
         */
/*        function zerox(curRowIndex)
        {
            // If user don't want to auto filling rates, return;
            if (!$('#submit_container #autofill').prop('checked'))
                return;

            var rowLength = $('.ratesTable tbody tr').length;
            var nextRow = curRowIndex + 1;
            var inputIndex = '';
            $('.ratesTable tbody tr').eq(curRowIndex).find('.rates').each(function() {
                inputIndex = $('.ratesTable tbody tr').eq(curRowIndex).find('.rates').index($(this));
                for (i = nextRow; i < rowLength; i++)
                    $('.ratesTable tbody tr').eq(i).find('.rates').eq(inputIndex).val($(this).val());
            });

        }
*/
    });
</script>