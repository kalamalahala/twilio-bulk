(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	// Replace "#twilio-header" with "Hello Hello" on click
	$('#twilio-header').click(function() {
		$('#twilio-header').text('Hello Hello');
	}
	)



	
})( jQuery );


// animate #twilio-header with vanilla javascript
// https://www.w3schools.com/howto/howto_js_animate.asp

let counter = document.getElementById("counter-block");
let counterValue = 0;
let counterCurrent = /* query number of sales */ 1283;
let delimiter = ",";

// get current number of sales from wordpress gravityforms json api
// https://docs.gravityforms.com/rest-api-v2/#get-entries-entry-id
let route = 'https://thejohnson.group/wp-json/gf/v2/entries/13';
const results = async () => {
	const response = await fetch(route);
	const data = await response.json();
	console.log(data);
	counterCurrent = data.length;
	console.log(counterCurrent);
}
// for (let i = counterCurrent; i > counterCurrent; i++) {
// 	counterValue++;
// }

// animate #counter-block innerHTML with vanilla javascript
// from 0 to current amount of sales

// regex insert comma every 3 digits

let counterInterval = setInterval(function() {
	counter.innerHTML = counterValue.toString().replace(/\B(?=(\d{3})+(?!\d))/g, delimiter);
	// count faster
	counterValue = counterValue + Math.floor(Math.random() * 10);
	// counterValue++;
	if (counterValue > counterCurrent) {
		clearInterval(counterInterval);
	}
});