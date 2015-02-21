// JavaScript Document

function delete_conform(href, msg)
{
    var r = confirm(msg);
    if (r)
    {
        location.href = href;
    }//alert(href);
}


$('.reseter').click(function() {
    // Clear values of all input elements (except button,hidden,submit,radio,checkbox elements) when reset button pressed.
    // $(this).closest('form').find(":input").not(':button,:hidden,:submit,:radio,:checkbox').val('');
    
    // Clear values of all input type text,select,textarea.
    $(this).closest('form').find(":text , select, textarea").val('');
});

$(document).ready(function()
{

    // Changing Classes On Click
    $('.patient_info,.dv_tools_left').click(function() {
        if ($(this).closest("td").children(".patient_info").hasClass('no_sel'))
        {
            $(this).closest("td").children(".patient_info").removeClass('no_sel').addClass('sel');
            $(this).closest("td").find(':checkbox').prop("checked", true);/*.prop("tagName")*/
            ;
        }
        else
        {
            $(this).closest("td").children(".patient_info").removeClass('sel').addClass('no_sel');
            $(this).closest("td").find(':checkbox').prop("checked", false);
        }
    });

    $('.details-head').click(function() {
        //Code to format bottom boarder while collapsing
        $(this).toggleClass("round_boarder plane_boarder");

    });

    $('#checkUncheckAll').change(function() {
        $('#tbl_search_results input[type="checkbox"]').prop('checked', $(this).prop('checked'));
        if ($(this).prop('checked'))
        {
            $('#tbl_search_results .patient_info').each(function() {
                $(this).removeClass('no_sel').addClass('sel');
            });
        }
        else
        {
            $('#tbl_search_results .patient_info').each(function() {
                $(this).removeClass('sel').addClass('no_sel');
            });
        }
    });

    $('.tbl_input input[type=text]').dblclick(function() {
        $(this).val('');
    });

    //Allows only integer types
    $(".intOnly").keydown(function(event) {


        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(event.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
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
            });

    //$('#element').on('keyup keypress blur change', function() {});


    //Allows only number types
    $(".numberOnly").keydown(function(event)
    {


        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(event.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
                // Allow: Ctrl+A
                        (event.keyCode == 65 && event.ctrlKey === true) ||
                        // Allow: home, end, left, up, right
                                (event.keyCode >= 35 && event.keyCode <= 39) ||
                                //Allowing decimal point
                                        (event.keyCode == 110)) {
                            // let it happen, don't do anything
                            return;
                        }
                        else {
                            // Ensure that it is not a number and stop the keypress
                            if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105)) {
                                event.preventDefault();
                            }
                        }
                    });





            //After incorrect validation, hiding error messages when focusing in related input.            
            $('.tbl_input input[type=text], .tbl_input input[type=password],.tbl_input select, .tbl_input textarea').focusin(function() {

                if ($(this).hasClass('dateField'))
                {
                    $(this).closest('.dateContainer').find('.dialog-box-border').hide();
                }
                else
                    $(this).parent().find('.dialog-box-border').hide();
            });




        });     //  End $(document).ready(function(){});