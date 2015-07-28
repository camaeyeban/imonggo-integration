		<!--
			File name: homepage_head.php
			Description:
				This file contains the <head> contents of homepage.php (I3 Integrator homepage)
		-->
		
		<!-- Meta tags -->
		<meta charset="UTF-8">
		<meta name="author" content="Erica Mae Magdaong Yeban">
		<meta name="description" content="I3 Integrator login page">
		<meta name="viewport" content="width=device-width, initial-scale=1"/>

		<!-- Page title and icon -->
		<title>I3 Integrator</title>
		<link rel="icon" href="assets/images/logo.png">

		<!-- Cascading Style Sheets (CSS) -->
		<link rel="stylesheet" type="text/css" href="assets/css/materialize.css">
		<link rel="stylesheet" type="text/css" href="assets/css/style.css">
		<link rel="stylesheet" type="text/css" href="assets/css/icon.css">

		<!-- JavaScripts (JS) -->
		<script type="text/javascript" src="assets/js/jquery-1.10.2.min.js"></script>
		<script type="text/javascript" src="assets/js/materialize.js"> </script>
		<script>
			/* initialize/execute upon loading the page */
			$( document ).ready(function(){
				
				/* show the side navigation bar once the corresponding icon was clicked */
				$(".button-collapse").sideNav();
				
				/* action for the "Select/Deselect All" checkbox */
				$('#check_all_tags').click(function(event) {
					
					/* if "Select/Deselect All" checkbox was checked, check all other checkboxes */
					if (this.checked) {
						$('.tags').each(function() {	//loop through each checkbox
							this.checked = true;		//select all checkboxes with class "tags"               
						});
					}
					
					/* if "Select/Deselect All" checkbox was unchecked, deselect all other checkboxes */
					else{
						$('.tags').each(function() {	//loop through each checkbox
							this.checked = false;		//deselect all checkboxes with class "tags"                       
						});         
					}
				});
				
			})
			
			/* if the user clicks the "Close" button at the bottom right of the modal, close the corresponding modal */
			function close_modal(){
				$('#output_modal').closeModal();
			}
		</script>