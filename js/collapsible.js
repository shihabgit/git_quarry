// JavaScript Document
$(document).ready(function(){
	//Collapsible Div
	$(".p-collaps").click(function()
	{	//Positioning the 'dv-collapse' to just below the 'p-collaps'.
                var height	=	$(this).height() + parseInt($(this).css('padding-top')) + parseInt($(this).css('padding-bottom'));     
		$(this).next('.dv-collaps').css('top',height);
                
		//Code to collapse/expand
		jQuery(this).next(".dv-collaps").slideToggle(500);
		
	});
});	