"use strict";
window.addEventListener('load', function() {
	AOS.init({
		easing: 'ease-in-out-sine'
	});
});

document.addEventListener('DOMContentLoaded', function() {
//------------------------------------------------------------------------------------
//								COUNT UP SCRIPT
//------------------------------------------------------------------------------------
var counter_4col = $('#counter-4col').waypoint({
	handler: function(direction) {
		$(this.element).find('.count-up-data').each(function(i, el){
			var endVal = el && Number.isInteger(el.textContent * 1) ? el.textContent * 1 : 100 ;
			$(el ).countup({endVal: endVal, options: { separator : ''}});
		});
        counter_4col[0].disable();
	},
	offset: '90%'
    });

});
