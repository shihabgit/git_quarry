/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function() {



    var currentTextBox = '';
    var previousTextBox = '';
    var currentFirm_id = '';
    var previousFirm_id = '';
    var firmNameBeforeChange = '';


    $('.dropDownBox li .firm_name_container').click(function(e) {
        var url = $(this).find('.url').val();
        if (!(currentTextBox && currentTextBox.is(':visible')))
            window.location.replace(url);
    });


    function refreshVals()
    {
        if (currentTextBox)
        {
            currentTextBox.val('');
            currentTextBox.hide();
            currentTextBox = '';
        }
        if (previousTextBox)
        {
            previousTextBox.val('');
            previousTextBox.hide();
            previousTextBox = '';
        }
        currentFirm_id = '';
        previousFirm_id = '';
        //firmNameBeforeChange = '';
    }

    $(document).mouseup(function(e)
    {
        if (currentTextBox)
        {
            if (currentTextBox.val())
            {
                saveFirm(currentTextBox);
            }
            else
                currentTextBox.closest('li').find('.nmspn').text(firmNameBeforeChange);

        }
        refreshVals();
    });

    $('.dropDownBox li').mouseup(function(e) {

        if (currentTextBox)
        {
            if (currentTextBox.is(':visible'))
            {

                if (!currentTextBox.is(e.target) // if the target of the click isn't the currentTextBox...
                        && currentTextBox.has(e.target).length === 0  // ... nor a descendant of the currentTextBox
                        )
                {
                    saveFirm(currentTextBox); 
                    //currentTextBox.hide();
                    //currentTextBox.closest('li').find('.nmspn').text(currentTextBox.val());
                    refreshVals();

                }
            }
        }
    });


    $('.dropDownBox li .firm_edit').click(function() {

        if (currentTextBox)
        {
            previousTextBox = currentTextBox;
            previousFirm_id = previousTextBox.closest('li').find('.firm_id').val();
        }

        currentTextBox = $(this).closest('li').find('.firm_name');
        currentFirm_id = $(this).closest('li').find('.firm_id').val();


        // When clicking on same firm
        if (previousFirm_id == currentFirm_id)
        {   return;
        }

        if (previousTextBox)
        {
            saveFirm(previousTextBox);
            previousTextBox.val('');
            previousTextBox.hide();
            previousTextBox.closest('li').find('.nmspn').text(previousTextBox.val());
            previousTextBox = '';
        }


        var curFirm = $(this).closest('li').find('.nmspn').text();
        firmNameBeforeChange = curFirm;
        $(this).closest('li').find('.nmspn').text('');
        currentTextBox.show();
        currentTextBox.val(curFirm);
    })



    $('.dropDownBox li input[name=firm_name]').keypress(function(event) {

        //In Firefox, you have to use event.which to get the keycode; while IE support both event.keyCode and event.which
        var keycode = (event.keyCode ? event.keyCode : event.which);

        if (keycode == '13') {
            saveFirm($(this));
            refreshVals();
        }

    });


    function saveFirm(txt)
    {
        var FirmId = txt.closest('li').find('.firm_id').val();
        var FirmName = txt.val();
        var prev = firmNameBeforeChange;
        if (!FirmName)
        {
            txt.closest('li').find('.nmspn').text(prev);
            alert("There is no firm name specified.");
            return;
        }
        $.post(site_url + "firms/saveName", {
            firm_id: FirmId,
            firm_name: FirmName
        }, function(result) {
            if (result)  //If any validation errors
            {
                if (result == 1) //Success.
                {
                    txt.closest('li').find('.nmspn').text(FirmName);
                    txt.hide();
                    // Should not call refreshVals() here.

                    if (firm_id == FirmId) // firm_id is defined in header.php. it represents current logged in firm's id.
                    {   //user_name is defined in header.php. it represents current logged in user's name.
                        $('.dropDownBox .dropdown p').text(user_name + ' @ ' + FirmName);

                    }
                }
                else
                {
                    txt.closest('li').find('.nmspn').text(prev);

                    // When clicking out side of dropdown it will be closed. so re-open it.
                    //openDropDown();
                    alert(result);
                }
            }
        });
    }
    
    
    $('.dropDownBox li .firm_status').click(function() {

        var img = $(this).find('img');
        var li = $(this).closest('li');
        var FirmId = li.find('.firm_id').val();

        var ask = (img.prop('src') == site_url + 'images/error.png') ? "Do you want to deactivate the firm ?" : "Do you want to activate the firm ?";

        var confirmed = confirm(ask);
        if (!confirmed)
            return;

        $.post(site_url + "firms/toggleStatus", {
            firm_id: FirmId
        }, function(result) {
            if (result)  //If any validation errors
            {   // If status is active
                if (result == 1)
                {
                    img.prop('src', site_url + 'images/error.png');
                    li.removeClass('inactive');
                    li.addClass('active');

                }

                // If status is inactive
                else if (result == 2)
                {
                    img.prop('src', site_url + 'images/success.png');
                    li.removeClass('active');
                    li.addClass('inactive');
                }
                else
                    img.prop('src', '');

            }
        });
    });
/*
    function openDropDown()
    {
        $(".submenu").show();
        $(".account").attr('id', '1');
    }

*/
});


 