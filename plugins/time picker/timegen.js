$(document).ready(function () {

    var TGen_input = '';
    var TGen_default_format = 12; // 12 or 24.
    var TGen_format = TGen_default_format;
    var TGen_hh, TGen_ampm, TGen_mm, TGen_ss = '';

    $('.TGen .TGen_input').click(function () {
        TGen_input = $(this);
        load_time_generator(TGen_input);

    })

    function load_time_generator(TGen_input)
    {
        var TGenHTML = getTGenBody();
        TGen_input.closest('.TGen').append(TGenHTML);
        TGen_hh = (TGen_format == 12) ? $('.time_generator .TGen12hr .TGen_hh') : $('.time_generator .TGen24hr .TGen_hh');
        TGen_ampm = $('.time_generator .TGen_ampm');
        TGen_mm = $('.time_generator .TGen_mm');
        TGen_ss = $('.time_generator .TGen_ss');
        initTGen();

        TGen_input.closest('.TGen').find('.time_generator .TGInput').on('mousemove keyup', setTGenTime);//mousemove
        $('.time_generator .TGen_format').on('change', onTGenFormatChange);
        $('.time_generator .TGen_time_box input').on('keyup', function (e) {

            if ($(this).prop('id') == "TGen_time_ampm")
                TGen_ampm.not(':checked').prop("checked", true);
            else
                changeTGen($(this).prop('id'), e);
            setTGenTime();
        });

        setTGenTime();

        // Hiding timepicker when click out of it.
        $(document).mouseup(function (e)
        {
            // if the target of the click isn't the $('.time_generator') and not a descendant of the $('.time_generator') 
            if (!$('.time_generator').is(e.target) && $('.time_generator').has(e.target).length === 0)
            {
                $('.time_generator').hide();
            }
        });
    }



    function onTGenFormatChange() {

        TGen_format = $(this).val();

        if (TGen_format == 12)
        {
            TGen_hh = $('.time_generator .TGen12hr .TGen_hh');
            $('.time_generator .TGen12hr').show();
            $('.TGen_time_box #TGen_time_ampm').show();
            $('.time_generator .TGen24hr').hide();
        }
        else if (TGen_format == 24)
        {
            TGen_hh = $('.time_generator .TGen24hr .TGen_hh');
            $('.time_generator .TGen12hr').hide();
            $('.TGen_time_box #TGen_time_ampm').hide();
            $('.time_generator .TGen24hr').show();
        }

        setTGenTime();
    }





    function changeTGen(id, e)
    {
        var leftArrow = 37;
        var upArrow = 38;
        var rightArrow = 39;
        var downArrow = 40;
        var key = e.keyCode || e.which;
        var target = '';
        var targetVal, max_limit, min_limit = '';

        switch (id)
        {
            case "TGen_time_hh":
                target = TGen_hh;
                break;
            case "TGen_time_mm":
                target = TGen_mm;
                break;
            case "TGen_time_ss":
                target = TGen_ss;
                break;
        }

        targetVal = target.val();
        target.map(function () {
            max_limit = this.max;
            min_limit = this.min;
        });


        if (((key == leftArrow) || (key == downArrow)) && (eval(targetVal) > eval(min_limit)))
            target.val(--targetVal);
        else if (((key == rightArrow) || (key == upArrow)) && (eval(targetVal) < eval(max_limit)))
        {
            target.val(++targetVal);
        }

        return;
    }


    function setTGenTime()
    {
        var hh, mm, ss, ampm, TGen_time = '';

        hh = TGen_hh.val();
        mm = TGen_mm.val();
        ss = TGen_ss.val();
        ampm = (TGen_format == 12) ? (' ' + $('.time_generator .TGen_ampm:checked').val()) : '';

        TGen_time = hh + ':' + mm + ':' + ss + ampm;

        TGen_input.val(TGen_time);

        $('.TGen_time_box #TGen_time_hh').val(hh);
        $('.TGen_time_box #TGen_time_mm').val(mm);
        $('.TGen_time_box #TGen_time_ss').val(ss);
        $('.TGen_time_box #TGen_time_ampm').val(ampm);
    }


    function initTGen()
    {
        if (TGen_default_format == 12)
        {
            $('.time_generator .TGen12hr').show();
            $('.time_generator .TGen24hr').hide();
            $('.time_generator input[name=TGen_format][value=12]').prop('checked', true);
        }
        else if (TGen_default_format == 24)
        {
            $('.time_generator .TGen12hr').hide();
            $('.time_generator .TGen24hr').show();
            $('.time_generator input[name=TGen_format][value=24]').prop('checked', true);
        }
    }

    function  getTGenBody()
    {
        var tGenBody = '<div class="time_generator">';
        tGenBody += '<table class="TGen_table">';
        tGenBody += '    <tbody>';
        tGenBody += '        <tr>';
        tGenBody += '            <th>Format</th>';
        tGenBody += '            <td>';
        tGenBody += '                <input type="radio" name="TGen_format" class="TGen_format" value="12">12hr. &nbsp;';
        tGenBody += '                <input type="radio" name="TGen_format" class="TGen_format" value="24">24hr.';
        tGenBody += '            </td>';
        tGenBody += '        </tr>';
        tGenBody += '        <tr>';
        tGenBody += '            <th>Hour</th>';
        tGenBody += '            <td>';
        tGenBody += '                <div class="TGen12hr">';
        tGenBody += '                    <input type="range" class="TGInput TGen_hh" min="1" max="12">';
        tGenBody += '                    &nbsp;';
        tGenBody += '                    <input type="radio" name="TGen_ampm" class="TGInput TGen_ampm" value="AM" checked="">AM &nbsp;';
        tGenBody += '                    <input type="radio" name="TGen_ampm" class="TGInput TGen_ampm" value="PM">PM.';
        tGenBody += '                </div>';
        tGenBody += '                <div class="TGen24hr">';
        tGenBody += '                    <input type="range" class="TGInput TGen_hh" min="0" max="23">';
        tGenBody += '                </div>';
        tGenBody += '            </td>';
        tGenBody += '        </tr>';
        tGenBody += '        <tr>';
        tGenBody += '            <th>Minutes</th>';
        tGenBody += '            <td>';
        tGenBody += '                <input type="range" class="TGInput TGen_mm" min="0" max="59">';
        tGenBody += '            </td>';
        tGenBody += '        </tr>';
        tGenBody += '        <tr>';
        tGenBody += '            <th>Seconds</th>';
        tGenBody += '            <td>';
        tGenBody += '                <input type="range" class="TGInput TGen_ss" min="0" max="59">';
        tGenBody += '            </td>';
        tGenBody += '        </tr>';
        tGenBody += '        <tr>';
        tGenBody += '            <td colspan="2">';
        tGenBody += '                <div class="TGen_time_box">';
        tGenBody += '                    <input type="text" id="TGen_time_hh" readonly="" title="Click in and press arrow keys to change values">';
        tGenBody += '                    <input type="text" id="TGen_time_mm" readonly="" title="Click in and press arrow keys to change values">';
        tGenBody += '                    <input type="text" id="TGen_time_ss" readonly="" title="Click in and press arrow keys to change values">';
        tGenBody += '                    <input type="text" id="TGen_time_ampm" readonly="" title="Click in and press arrow keys to change values">';
        tGenBody += '                </div>';
        tGenBody += '            </td>';
        tGenBody += '        </tr>';
        tGenBody += '    </tbody>';
        tGenBody += '</table>';
        tGenBody += '</div>';

        return tGenBody;
    }

});

