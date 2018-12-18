			$(document).ready( function() {
				
				//
				// Enabling miniColors
				//
				
				$(".color-picker").miniColors({
					letterCase: 'uppercase',
					change: function(hex, rgb) {
						logData(hex, rgb);
					}
				});
				
				
				
				
				//
				// Only for the demo
				//
				
				function logData(hex, rgb) {
					$("#console").prepend('HEX: ' + hex + ' (RGB: ' + rgb.r + ', ' + rgb.g + ', ' + rgb.b + ')<br />');
				}
				
				$("#disable").click( function() {
					$("#console").prepend('disable<br />');
					$(".color-picker").miniColors('disabled', true);
					$("#disable").prop('disabled', true);
					$("#enable").prop('disabled', false);
				});
				
				$("#enable").click( function() {
					$("#console").prepend('enable<br />');
					$(".color-picker").miniColors('disabled', false);
					$("#disable").prop('disabled', false);
					$("#enable").prop('disabled', true);
				});
				
				$("#makeReadonly").click( function() {
					$("#console").prepend('readonly = true<br />');
					$(".color-picker").miniColors('readonly', true);
					$("#unmakeReadonly").prop('disabled', false);
					$("#makeReadonly").prop('disabled', true);
				});
				
				$("#unmakeReadonly").click( function() {
					$("#console").prepend('readonly = false<br />');
					$(".color-picker").miniColors('readonly', false);
					$("#unmakeReadonly").prop('disabled', true);
					$("#makeReadonly").prop('disabled', false);
				});
				
				$("#destroy").click( function() {
					$("#console").prepend('destroy<br />');
					$(".color-picker").miniColors('destroy');
					$("INPUT[type=button]:not(#create)").prop('disabled', true);
					$("#destroy").prop('disabled', true);
					$("#create").prop('disabled', false);
				});
				
				$("#create").click( function() {
					$("#console").prepend('create<br />');
					$(".color-picker").miniColors({
						letterCase: 'uppercase',
						change: function(hex, rgb) {
							logData(hex, rgb);
						}
					});
					$("#makeReadonly, #disable, #destroy, #randomize").prop('disabled', false);
					$("#destroy").prop('disabled', false);
					$("#create").prop('disabled', true);
				});
				
				$("#randomize").click( function() {
					$(".color-picker").miniColors('value', '#' + Math.floor(Math.random() * 16777215).toString(16));
				});
				
			});