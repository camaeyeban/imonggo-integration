<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>

<title>Imonggo Integration</title>
<link rel="icon" href="assets/images/logo.png">

<link rel="stylesheet" type="text/css" href="assets/css/materialize.css">
<link rel="stylesheet" type="text/css" href="assets/css/style.css">
<link rel="stylesheet" type="text/css" href="assets/css/icon.css">

<script type="text/javascript" src="assets/js/jquery-1.11.1.min.js"> </script>
<script type="text/javascript" src="assets/js/materialize.js"> </script>
<script>
	$( document ).ready(function(){
		$(".button-collapse").sideNav();
	})
	
	var countBox = 1;
	var boxName = 0;
	
	function addInput(){
		var boxName="tag"+countBox; 
		document.getElementById('tag-input-fields').innerHTML+='<br/><i class="material-icons prefix">label</i><input type="text" placeholder="Product Tag" name="'+boxName+'" " />';
		countBox += 1;
	}
	
	function close_modal(){
		$('#output_modal').closeModal();
	}
</script>
		