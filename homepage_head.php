		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>

		<title>I3 Integrator</title>
		<link rel="icon" href="assets/images/logo.png">

		<!------------------------------------- CSS --------------------------------------->
		<link rel="stylesheet" type="text/css" href="assets/css/materialize.css">
		<link rel="stylesheet" type="text/css" href="assets/css/style.css">
		<link rel="stylesheet" type="text/css" href="assets/css/icon.css">

		<!---------------------------------- JAVASCRIPT ------------------------------------>
		<script type="text/javascript" src="assets/js/jquery-1.10.2.min.js"></script>
		<script type="text/javascript" src="assets/js/materialize.js"> </script>
		<script>
			$( document ).ready(function(){
				$(".button-collapse").sideNav();
				$('#check_all_tags').click(function(event) {  //on click 
					if(this.checked) { // check select status
						$('.tags').each(function() { //loop through each checkbox
							this.checked = true;  //select all checkboxes with class "checkbox1"               
						});
					}else{
						$('.tags').each(function() { //loop through each checkbox
							this.checked = false; //deselect all checkboxes with class "checkbox1"                       
						});         
					}
				});
			})
			
			function close_modal(){
				$('#output_modal').closeModal();
			}
		</script>