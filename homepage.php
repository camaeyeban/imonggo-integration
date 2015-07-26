<!DOCTYPE>

<?php
	session_start();
	if(!isset($_SESSION['token'])){
		header("location: index.php");
	}
?>

<html lang="en">


	<!----------------- PAGE HEAD: PAGE TITLE, STYLESHEETS, AND SCRIPTS ------------------>
	<head>
		<?php include("homepage_head.php");?>
	</head>
	<!--------------------------------- END OF PAGE HEAD --------------------------------->


	
	<!------------------------------------- PAGE BODY ------------------------------------>
	<body>
	
		<!---------------------------------- NAVIGATION BAR ---------------------------------->
		<header class="navbar-fixed">
		
			<nav role="navigation" class="grey darken-2">
				<div class="nav-wrapper">
					
					<a href="#" data-activates="side-nav" class="button-collapse"><i class="material-icons">menu</i></a>
					
					<ul class="hide-on-med-and-down">
						<li><a href="http://localhost/imonggo-integration/"><img src="assets/images/logo.png" class="nav-logo"></img></a></li>
						<li><a href="https://camae.c3.imonggo.com/en/store" target="_blank">Imonggo Store</a></li>
						<li><a href="https://camae.c3.imonggo.com/en/invoices" target="_blank">Imonggo Admin</a></li>
						<li><a href="http://support.imonggo.com/help/kb/api/introduction-to-imonggo-api" target="_blank">Imonggo API</a></li>
						<li><a href="http://sandbox-imonggo-com.3dcartstores.com" target="_blank">3dCart Store</a></li>
						<li><a href="https://sandbox-imonggo-com.3dcartstores.com/admin" target="_blank">3dCart Admin</a></li>
						<li><a href="https://apirest.3dcart.com/Help" target="_blank">3dCart API</a></li>
						<!--<li><a href="https://devportal.3dcart.com/dashboard.asp" target="_blank">Dev-portal</a></li>-->
						<li><a href="http://localhost/phpmyadmin" target="_blank">Localhost Database</a></li>
						<li><a href="https://github.com/camaeyeban/imonggo_integration" target="_blank">Git Repository</a></li>
						<li><a href="logout.php">Logout</a></li>
					</ul>
					
					<ul class="side-nav" id="side-nav">
						<li><a href="http://localhost/imonggo-integration/"><img src="assets/images/logo.png" class="nav-logo"></img></a></li>
						<li><a href="https://camae.c3.imonggo.com/en/store" target="_blank">Imonggo Store</a></li>
						<li><a href="https://camae.c3.imonggo.com/en/invoices" target="_blank">Imonggo Admin</a></li>
						<li><a href="http://support.imonggo.com/help/kb/api/introduction-to-imonggo-api" target="_blank">Imonggo API</a></li>
						<li><a href="http://sandbox-imonggo-com.3dcartstores.com" target="_blank">3dCart Store</a></li>
						<li><a href="https://sandbox-imonggo-com.3dcartstores.com/admin" target="_blank">3dCart Admin</a></li>
						<li><a href="https://apirest.3dcart.com/Help" target="_blank">3dCart API</a></li>
						<!--<li><a href="https://devportal.3dcart.com/dashboard.asp" target="_blank">Dev-portal</a></li>-->
						<li><a href="http://localhost/phpmyadmin" target="_blank">Localhost Database</a></li>
						<li><a href="https://github.com/camaeyeban/imonggo_integration" target="_blank">Git Repository</a></li>
						<li><a href="logout.php">Logout</a></li>
					</ul>
					
				</div>
			</nav>
			
		</header>
		<!------------------------------ END OF NAVIGATION BAR ------------------------------->
		
		
		<!----------------------------------- PAGE CONTENT ----------------------------------->
		<section class="row">

			<!----------------------------------- MODAL OUTPUT ----------------------------------->
			<div id="output_modal" class="modal modal-fixed-footer">
				<div class="modal-content">
					<h4 class="center modal-header">OUTPUT:</h4>
					<p>
						<?php include("main.php"); ?>
					</p>
				</div>
				<div class="modal-footer">
					<button href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat" onClick="close_modal();">Close</button>
				</div>
			</div>
			<!-------------------------------- END OF MODAL OUTPUT ------------------------------->
			
			
			<div class="panel">
			
				<div class="center col s12 header">
					<img src="assets/images/imonggo_logo.png" class="logos"></img><br/>
					<span class="integration">integration</span>
				</div>
				
				
				
				<!-------------------------------- ADD PRODUCTS PANEL --------------------------------->
				<div class="col s4">
					<div class="card col s12 teal lighten-5">
						<div class="card-image waves-effect waves-block waves-light">
							<button type="button" class="btn waves-effect waves-light teal col s12">
								<i class="material-icons left">add_shopping_cart</i>
								Update Products
							</button>
							<img class="card-img" src="assets/images/products.jpg">
						</div>
						<div class="col s12">
							<button class="add-filters btn waves-effect waves-light activator col s6">Add Filters</button>
							<form method="POST">
								<button class="products-submit btn waves-effect waves-light col s6" type="submit" name="update_products">
									Submit
								</button>
						</div>
							<div class="card-reveal">
								<span class="card-title center grey-text text-darken-4">Select Tag/s<i class="material-icons right">close</i></span>
								<p>
									<hr/>
									<p>
										<input type="checkbox" name="no_tags" class="filled-in" id="no-tags" />
										<label for="no-tags">No Tags</label>
									</p>
									<p>
										<input type="checkbox" class="filled-in" id="check_all_tags" />
										<label for="check_all_tags">Select/Deselect All Tags</label>
									</p>
									<hr/>
									<?php include("get_tags.php"); ?>
								</p>
							</div>
						</form>
					</div>
				</div>
				<!----------------------------- END OF ADD PRODUCTS PANEL ---------------------------->
				
				
				<!-------------------------------- POST INVOICES PANEL -------------------------------->
				<div class="col s4">
					<div class="card col s12 pink lighten-5">
						<div class="card-image waves-effect waves-block waves-light">
							<button type="button" class="btn waves-effect waves-light pink col s12">
								<i class="material-icons left">playlist_add</i>
								Post Invoices
							</button>
							<img class="activator" src="assets/images/invoices.jpg">
						</div>
						
						<div class="col s12">
							<form method="POST">
							
								<div class="description col s12 center">
									Post Invoices from 3dCart to Imonggo
									<button class="btn waves-effect waves-light pink invoices-submit" type="submit" name="update_invoices">
										Submit
									</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<!----------------------------- END OF POST INVOICES PANEL --------------------------->
				
				
				<!-------------------------------- ADD CUSTOMERS PANEL ------------------------------->
				<div class="col s4">
					<div class="card col s12 cyan lighten-5">
						<div class="card-image waves-effect waves-block waves-light">
							<button type="button" class="btn waves-effect waves-light cyan col s12">
								<i class="material-icons left">group_add</i>
								Update Customers
							</button>
							<img class="activator" src="assets/images/customers.jpg">
						</div>
						<div class="col s12">
							<form method="POST">
								<div class="col s12 center option-question">
									Where do you want to get the customer information to be saved in Imonggo?
								</div>
								<div class="center">
									<div class="col s6">
										<?php 
											$checked = "";
											if($choice == "Shipping"){
												$checked = "checked";
											}
										?>
										<input type="radio" id="shipping" value="Shipping" name="billing_shipping_choice" class="with-gap" <?php echo $checked; ?> />
										<label for="shipping">Shipping Info</label>
									</div>
									<div class="col s6">
										<?php 
											$checked = "";
											if($choice == "Billing"){
												$checked = "checked";
											}
										?>
										<input type="radio" id="billing" value="Billing" name="billing_shipping_choice" class="with-gap" <?php echo $checked; ?> />
										<label for="billing">Billing Info</label>
									</div>
								</div>
								<div class="center">
									<button class="btn waves-effect waves-light cyan customer-submit" type="submit" name="update_customers">Submit</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<!---------------------------- END OF ADD CUSTOMERS PANEL ----------------------------->
			</div>
			
			
		</section>
		
		
		<!-------------------------------------- FOOTER -------------------------------------->
        <footer class="center page-footer grey darken-2">
			<div class="footer col s12 row">
				&copy; 2015 <b>&middot;</b> Imonggo Integration. <br>
				Site developed and maintained by Erica Mae Magdaong Yeban.
				<!--
				<form method="GET">
					<button class="btn waves-effect waves-light" name="dummy_button">Dummy button</button>
				</form>
				-->
				<?php
					if (isset($_GET['dummy_button'])){
						$str = 'This is an encoded string';
						echo base64_encode($str);
					}
				?>
			</div>
		</footer>
		<!----------------------------------- END OF FOOTER ---------------------------------->
		
		
		
	</body>
	<!-------------------------------- END OF PAGE BODY ---------------------------------->
	
	
</html>