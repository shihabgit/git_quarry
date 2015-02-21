// JavaScript Document
$(document).ready(function(e) {
	var mpick_target = '';
	var position = '';
	var posL = '';
	var posT = '';
	var container = $('.month_picker_container');
	
	//Month Picker Settings.
	monthpicker_settings();
	
    $('.month_picker_container table td').click(function(){
		var year = $('.month_picker_container #pick-year').val();
		var month = $(this).html();
		mpick_target.val(month+'-'+year)
		container.hide();
	});
	
	$('.mpick-input').click(function(){
		mpick_target = $(this);
		load_monthpicker();
	});
	
	$('.mpick-img').click(function(){
		mpick_target = $(this).closest('.mpick_box').find('.mpick-input');
		load_monthpicker();
	});
	
	function load_monthpicker()
	{	position = mpick_target.position();
        posL = position.left;
        posT = position.top + mpick_target.height();
		container.show();
		container.css({
			top: posT,
			left: posL
		});
	}
	
	function monthpicker_settings()
    {   
        //Hiding popup window on click on outside of it.
        $(document).mouseup(function(e)
        {
            if (!container.is(e.target) // if the target of the click isn't the popupBox...
                    && container.has(e.target).length === 0  // ... nor a descendant of the popupBox
                    )
            {
                container.hide();
            }
        });
    }
	
});