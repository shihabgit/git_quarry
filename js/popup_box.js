
//Alligning center-middle
jQuery.fn.center = function() {
//    this.css("position","absolute");
//    this.css("top", ( jQuery(window).height() - this.height() ) / 2+jQuery(window).scrollTop() + "px");
//    this.css("left", ( jQuery(window).width() - this.width() ) / 2+jQuery(window).scrollLeft() + "px");
//    return this;
    this.css("position", "fixed");
    this.css("top", (jQuery(window).height() / 2) - (this.outerHeight() / 2));
    this.css("left", (jQuery(window).width() / 2) - (this.outerWidth() / 2));
    return this;

}


function loadPopup(id)
{
    var popupBox = jQuery('#' + id); //Setting value of popupBox as an 'id' of a popup box.

    popupBox.show();
    popupBox.center();

}


//Clossing popups when clicking closs button
jQuery('.popupBox .clossButton').click(function() {
    jQuery(this).closest('.popupBox').hide();
});


jQuery('.popupBox #pop_drag').change(function() {
    dragUndrag(jQuery(this).closest('.popupBox'));
});

//Enable/Disable draggable feature of popup
function dragUndrag(obj)
{
    //alert(obj.attr('id'));
    if (obj.find('#pop_drag').prop('checked'))
        obj.draggable("enable");
    else
        obj.draggable({disabled: true});
}



/**
 * No need of this function because the following function (jQuery(document).mouseup()) will do the same.
 * @param {type} popupBox
 * @returns {undefined}
 */
function popupSettings(popupBox)
{
    // The duty of this function will do by the following function (jQuery(document).mouseup()). so just return.
    return;
    
    //Clossing popupBox when mouse clickin on outside of it.
    jQuery(document).mouseup(function(e)
    {
        if (popupBox.find('#pop_self_close').prop('checked'))
        {
            var datePicker = popupBox.find(".datepicker"); // Class name of datepicker displayed with popup (if any).
            //alert(jQuery(e.target).prop('class'));

            if (!popupBox.is(e.target) // if the target of the click isn't the popupBox...
                    && !datePicker.is(e.target) // if the target of the click isn't the datePicker...
                    && popupBox.has(e.target).length === 0  // ... nor a descendant of the popupBox
                    && datePicker.has(e.target).length === 0  // ... nor a descendant of the datePicker
                    )
            {
                popupBox.hide();
            }

        }

    });

}


/**
 * Function to closs popupBox when mouse clickin on outside of it.
 * When using datepicker in popup box, the above function (popupSettings) is not working expectedly. So it is defined.
 * @param {type} popupBox
 * @returns {undefined}
 */


//
jQuery(document).mouseup(function(e)
{
    var is_popupbox = jQuery(e.target).hasClass('popupBox')?true:false;
    var is_popupbox_children = (jQuery(e.target).closest(".popupBox").length > 0) ? true: false;
    var is_datePicker = jQuery(e.target).hasClass('datepicker')?true:false;
    var is_datePicker_children = (jQuery(e.target).closest(".datepicker").length > 0) ? true: false;
    
    if(is_popupbox || is_popupbox_children || is_datePicker || is_datePicker_children)
        return;
    
    else
    {
        jQuery('.popupBox').each(function() {
            if (jQuery(this).find('#pop_self_close').prop('checked'))
                jQuery(this).hide();
        });
    }


});


