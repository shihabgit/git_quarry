$(document).ready(function () {

    var TTool_format = 12;
    var target = '';

    $('.TTool .TTool_target').click(load_TTool);

    function load_TTool()
    {
        var html = getTToolBody();
        target = $(this);

        //alert(new Date());

        target.after(html);

        $(".timetool .TTool_format").on('change', TToolFormateChange);

        $(".timetool .tbl_timetool_input input[type=text]").on('keydown', function (event) {
            TToolIntOnly(event);
        });

        $(".timetool .tbl_timetool_input input[type=text]").on('keyup focus click', TToolCheckLimit);

        $(".timetool .tbl_timetool_input input[type=text]").on('click focus', function () {
            $(this).select();
        });
        
        $(".timetool .TTool_close").on('click', function () {
            setTime();
            $('.timetool').hide();
            $('.timetool').html('');
        });

// Hiding timepicker when click out of it.
//    $(document).mouseup(function (e)
//    {
//        // if the target of the click isn't the $('.time_generator') and not a descendant of the $('.time_generator') 
//        if (!$('.timetool').is(e.target) && $('.timetool').has(e.target).length === 0)
//        {
//            setTime();
//            $('.timetool').hide();
//            $('.timetool').html('');
//        }
//    });

        readTime();
    }






    function readTime()
    {
        // The source time (ie:- $('.TTool .TTool_target').val()) must be in the format : "hh:mm:ss ampm".

        var time = target.val();
        var hh, mm, ss, ampm;

        if (time)
        {
            time = time.split(':');
            hh = time[0];
            mm = time[1];
            var temp = time[2].split(" ");
            ss = temp[0];
            ampm = temp[1];
            ampm = ampm ? ampm.toUpperCase() : '';
            TTool_format = ampm ? 12 : 24;

        }

        // If no time, setting current time.
        else
        {
            var d = new Date();
            hh = d.getHours();
            mm = d.getMinutes();
            ss = d.getSeconds();

            //Converting to 12hr format.
            TTool_format = 12; // sETTING 12HR FORMAT.
            ampm = (hh >= 12) ? 'PM' : 'AM';
            hh = (hh > 12) ? (hh - 12) : hh;
            if (!hh)
                hh = 12; // if hh is zero it will be 12 AM.
        }

        if (TTool_format == 12)
        {
            TTool_format = 12;
            $('.timetool .TTool_meridiem[value="' + ampm + '"]').prop('checked', true);
            $('.timetool').find('.TR_TTool_meridiem').show();
        }
        else
        {
            $('.timetool').find('.TR_TTool_meridiem').hide();
        }

        $('.timetool .TTool_format[value="' + TTool_format + '"]').prop('checked', true);
        $('.timetool .TTool_hh').val(hh);
        $('.timetool .TTool_mm').val(mm);
        $('.timetool .TTool_ss').val(ss);
    }

    function setTime()
    {
        var ampm = (TTool_format == 12) ? ' ' + $('.timetool .TTool_meridiem:checked').val() : '';
        var hh = $('.timetool .TTool_hh').val();
        var mm = $('.timetool .TTool_mm').val();
        var ss = $('.timetool .TTool_ss').val();
        var time = hh + ':' + mm + ':' + ss + ampm;
        target.val(time);
    }

    function TToolFormateChange() {

        TTool_format = $(".timetool .TTool_format:checked").val();

        if (TTool_format == 12)
        {
            $('.timetool').find('.TR_TTool_meridiem').show();
        }
        else
        {
            $('.timetool').find('.TR_TTool_meridiem').hide();
        }

        convert_meridiem();
    }

    function convert_meridiem()
    {
        var hr = eval($('.timetool .TTool_hh').val());

        if (TTool_format == 12)
        {
            if (hr < 12)
            {
                $('.timetool .TTool_meridiem[value="AM"]').prop('checked', true);
                if (hr == 0)
                    hr = 12;
            }
            else
            {
                $('.timetool .TTool_meridiem[value="PM"]').prop('checked', true);
                if (hr > 12)
                    hr -= 12;
            }
        }
        else if (TTool_format == 24)
        {
            if ($('.timetool .TTool_meridiem:checked').val() == 'AM')
            {
                if (hr == 12)
                    hr = 0;
            }
            else if ($('.timetool .TTool_meridiem:checked').val() == 'PM')
            {
                if (hr < 12)
                    hr += 12;
            }
        }

        $('.timetool .TTool_hh').val(addZero(hr));
    }

    function addZero(val)
    {
        var time = ('0' + val).slice(-2);
        return time;
    }

    function TToolCheckLimit()
    {
        var val = $(this).val();
        var min_limit, max_limit = '';
        switch ($(this).prop('class'))
        {
            case "TTool_hh":
                if (TTool_format == 12)
                {
                    min_limit = 1;
                    max_limit = 12;
                }
                else
                {
                    min_limit = 0;
                    max_limit = 23;
                }
                break;
            default:
                min_limit = 0;
                max_limit = 59;
        }

        if (eval($(this).val()) > max_limit)
            val = max_limit;
        else if (eval($(this).val()) < min_limit)
            val = min_limit;

        $(this).val(addZero(val));
    }


    function getTToolBody()
    {
        var html = '<div class="timetool">';
        html += '    <table class="tbl_timetool">';
        html += '        <tbody>';
        html += '            <tr>';
        html += '                <th>Format</th>';
        html += '                <td colspan="2">';
        html += '                    <input type="radio" name="TTool_format" value="12" checked="" class="TTool_format"> &nbsp;12 hrs.';
        html += '                    <input type="radio" name="TTool_format" value="24" class="TTool_format"> &nbsp;24 hrs.';
        html += '                </td>';
        html += '            </tr>';
        html += '            <tr class="TR_TTool_meridiem">';
        html += '                <th>Meridiem</th>';
        html += '                <td colspan="2">';
        html += '                    <input type="radio" name="TTool_meridiem" value="AM" checked="" class="TTool_meridiem"> &nbsp;AM';
        html += '                    <input type="radio" name="TTool_meridiem" value="PM" class="TTool_meridiem"> &nbsp;PM';
        html += '                </td>';
        html += '            </tr>';
        html += '            <tr>';
        html += '                <td colspan="3">';
        html += '                    <table class="tbl_timetool_input">';
        html += '                        <tbody>';
        html += '                            <tr>';
        html += '                                <th>Hours</th>';
        html += '                                <th>Minuts</th>';
        html += '                                <th>Seconds</th>';
        html += '                            </tr>';
        html += '                            <tr>';
        html += '                                <th><input type="text" class="TTool_hh" value=""></th>';
        html += '                                <th><input type="text" class="TTool_mm" value=""></th>';
        html += '                                <th><input type="text" class="TTool_ss" value=""></th>';
        html += '                            </tr>';
        html += '                        </tbody>';
        html += '                    </table>';
        html += '                </td>';
        html += '            </tr>';
        
        html += '            <tr>';
        html += '                <td colspan="3">';
        html += '                   <div class="TTool_close">Set Time</div>';
        html += '                </td>';
        html += '            </tr>';
        
        html += '        </tbody>';
        html += '    </table>';
        html += '</div>';
        return html;
    }



    //Allows only integer types

    function TToolIntOnly(event)
    {
        // Allow: backspace, delete, tab, escape, enter and .
        var allowed = $.inArray(event.keyCode, [46, 8, 9, 27, 13, 190]);
        if (allowed !== -1 ||
                // Allow: Ctrl+A
                        (event.keyCode == 65 && event.ctrlKey === true) ||
                        // Allow: home, end, left, up, right
                                (event.keyCode >= 35 && event.keyCode <= 39)) {
                    // let it happen, don't do anything
                    return;
                }
                else {
                    // Ensure that it is not a number and stop the keypress
                    if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105)) {
                        event.preventDefault();
                    }
                }
            }








        });

