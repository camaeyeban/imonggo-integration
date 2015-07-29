<!DOCTYPE>

<!--
	File name: homepage.php
	Description:
		This file contains the user interface for the homepage
-->

<?php
	session_start();
	
	/* if imonggo_api_key of the session is uninitialized, direct user to the login page (login.php) */
	if(!isset($_SESSION['imonggo_api_key'])){
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
			<?php include("navigation_bar.php"); ?>
		</header>
		<!------------------------------ END OF NAVIGATION BAR ------------------------------->
		
		
		<!----------------------------------- PAGE CONTENT ----------------------------------->
		<section class="row">

			<!----------------------------------- MODAL OUTPUT ----------------------------------->
			<div id="output_modal" class="modal modal-fixed-footer">
				<div class="modal-content">
					<h4 class="center modal-header">OUTPUT:</h4>
					<p>
						<?php include("functions/buttons_functions.php"); ?>
					</p>
				</div>
				<div class="modal-footer">
					<button href="#!" class=" modal-action modal-close waves-effect waves-teal btn-flat" onClick="close_modal();">Close</button>
				</div>
			</div>
			<!-------------------------------- END OF MODAL OUTPUT ------------------------------->
			
			
			<div class="panel">
			
				<div class="center col s12 header">
					<img src="assets/images/imonggo_logo.png" class="imonggo-logo"></img><br/>
					<span class="integration">integration</span>
				</div>
				
				
				
				<!-------------------------------- ADD PRODUCTS PANEL --------------------------------->
				<div class="col l4">
					<div class="card col l12 teal lighten-4 z-depth-3">
						<div class="card-image waves-effect waves-block waves-light">
							<button type="button" class="btn waves-effect waves-light teal darken-2 col s12">
								<i class="material-icons left">add_shopping_cart</i>
								Update Products
							</button>
							<img class="card-img" src="assets/images/products.jpg">
						</div>
						<div class="col s12">
							<button class="add-filters btn waves-effect waves-light activator col s6 teal darken-2">Add Filters</button>
							<form method="POST">
								<button class="products-submit btn waves-effect waves-light col s6 teal darken-2" type="submit" name="update_products">
									Submit
								</button>
						</div>
							<div class="card-reveal">
								<span class="card-title center grey-text text-darken-4">Select Tag/s<i class="material-icons right">close</i></span>
								<p>
									<?php include("get_tags.php"); ?>
									
									<button class="btn waves-effect waves-light col s12 teal darken-2" type="submit" name="update_products">
										Submit
									</button>
								</p>
							</div>
						</form>
					</div>
				</div>
				<!----------------------------- END OF ADD PRODUCTS PANEL ---------------------------->
				
				
				<!-------------------------------- POST INVOICES PANEL -------------------------------->
				<div class="col l4">
					<div class="card col l12 yellow lighten-4 z-depth-3">
						<div class="card-image waves-effect waves-block waves-light">
							<button type="button" class="btn waves-effect waves-light yellow darken-3 col s12">
								<i class="material-icons left">playlist_add</i>
								Post Invoices
							</button>
							<img class="activator card-img" src="assets/images/invoices.jpg">
						</div>
						
						<div class="col s12">
							<form method="POST">
							
								<div class="description col s12 center">
									<button class="btn waves-effect waves-light yellow darken-3 invoices-submit" type="submit" name="update_invoices">
										Submit
									</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<!----------------------------- END OF POST INVOICES PANEL --------------------------->
				
				
				<!-------------------------------- ADD CUSTOMERS PANEL ------------------------------->
				<div class="col l4">
					<div class="card col l12 cyan lighten-4 z-depth-3">
						<div class="card-image waves-effect waves-block waves-light">
							<button type="button" class="btn waves-effect waves-light cyan darken-2 col s12">
								<i class="material-icons left">group_add</i>
								Update Customers
							</button>
							<img class="activator card-img" src="assets/images/customers.jpg">
						</div>
						<div class="col l12 m12">
							<form method="POST">
								<div class="col l12 m12 center option-question">
									Where do you want to get the customer information to be saved in Imonggo?
								</div>
								<div class="center">
									<div class="col l6 s12 m12">
										<?php 
											$checked = "";
											if($choice == "Shipping"){
												$checked = "checked";
											}
										?>
										<input type="radio" id="shipping" value="Shipping" name="billing_shipping_choice" class="with-gap" <?php echo $checked; ?> />
										<label for="shipping">Shipping Info</label>
									</div>
									<div class="col l6 s12 m12">
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
								<div class="col s12 center">
									<button class="btn waves-effect waves-light cyan darken-2 customer-submit" type="submit" name="update_customers">
										Submit
									</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<!---------------------------- END OF ADD CUSTOMERS PANEL ----------------------------->
			</div>
			
			
		</section>
		
		
		<!-------------------------------------- FOOTER -------------------------------------->
		<footer class="center page-footer grey darken-4">
			<div class="footer col l12 row">
				&copy; 2015 <b>&middot;</b> Imonggo Integration. <br>
				Site developed and maintained by Erica Mae Magdaong Yeban.
			</div>
		</footer>
		<!----------------------------------- END OF FOOTER ---------------------------------->
		
		
		
	</body>
	<!-------------------------------- END OF PAGE BODY ---------------------------------->
	
	
</html>