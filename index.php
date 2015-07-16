<!DOCTYPE>

<html lang="en">


	<!----------------- PAGE HEAD: PAGE TITLE, STYLESHEETS, AND SCRIPTS ------------------>
	<head>
		<?php include("index_head.php");?>
	</head>
	<!--------------------------------- END OF PAGE HEAD --------------------------------->


	
	<!------------------------------------- PAGE BODY ------------------------------------>
	<body>
	
		<!---------------------------------- NAVIGATION BAR ---------------------------------->
		<header class="navbar-fixed">
		
			<nav role="navigation" class="grey darken-2">
				<div class="nav-wrapper">
					
					<a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
					
					<ul class="left hide-on-med-and-down">
						<img src="assets/images/logo.png" class="brand-logo"></img>
					</ul>
					
					<ul class="right hide-on-med-and-down">
						<li><a href="https://camae.c3.imonggo.com/en/products" target="_blank">Imonggo Stockroom</a></li>
						<li><a href="https://camae.c3.imonggo.com/en/store" target="_blank">Imonggo Store</a></li>
						<li><a href="https://camae.c3.imonggo.com/en/invoices" target="_blank">Imonggo Office</a></li>
						<li><a href="http://sandbox-imonggo-com.3dcartstores.com" target="_blank">3dCart Store</a></li>
						<li><a href="https://sandbox-imonggo-com.3dcartstores.com/admin" target="_blank">3dCart Admin</a></li>
						<li><a href="https://devportal.3dcart.com/dashboard.asp" target="_blank">3dCart DevPortal</a></li>
						<li><a href="https://github.com/camaeyeban/imonggo_integration" target="_blank">Git Repository</a></li>
						<li><a href="http://localhost/phpmyadmin/#PMAURL-3:db_structure.php?db=imonggo_3dcart_integration_db&table=&server=1&target=&token=45b28188c43abbc46f8fe279c64c50a8" target="_blank">Localhost Database</a></li>
					</ul>
					
					<ul class="side-nav" id="mobile-demo">
						<li><a href="https://camae.c3.imonggo.com/en/products" target="_blank">Imonggo Stockroom</a></li>
						<li><a href="https://camae.c3.imonggo.com/en/store" target="_blank">Imonggo Store</a></li>
						<li><a href="https://camae.c3.imonggo.com/en/invoices" target="_blank">Imonggo Office</a></li>
						<li><a href="http://sandbox-imonggo-com.3dcartstores.com/" target="_blank">3dCart Store</a></li>
						<li><a href="https://sandbox-imonggo-com.3dcartstores.com/admin" target="_blank">3dCart Admin</a></li>
						<li><a href="https://devportal.3dcart.com/dashboard.asp" target="_blank">3dCart DevPortal</a></li>
						<li><a href="https://github.com/camaeyeban/imonggo_integration" target="_blank">Git Repository</a></li>
						<li><a href="http://localhost/phpmyadmin/#PMAURL-3:db_structure.php?db=imonggo_3dcart_integration_db&table=&server=1&target=&token=45b28188c43abbc46f8fe279c64c50a8" target="_blank">Localhost Database</a></li>
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
						<h4>OUTPUT:</h4>
						<p>
							<?php include("main.php"); ?>
						</p>
					</div>
					<div class="modal-footer">
						<button href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat" onClick="close_modal();">Close</button>
					</div>
				</div>
				<!-------------------------------- END OF MODAL OUTPUT ------------------------------->
				
				
			<div class="panel" style="margin-bottom: -40px !important;">
			
				<div class="col s12 header">
					<img src="assets/images/imonggo_logo.png" class="logos"></img><br/>
					<span class="integration">integration</span>
				</div>
				
				
				
				<!-------------------------------- ADD PRODUCTS PANEL --------------------------------->
				<div class=" col s4">
					<div class="card-panel teal lighten-5 col s12">
						<button type="button" class="btn waves-effect waves-light teal col s12"><i class="material-icons left">add_shopping_cart</i>Add Products</button>
						<div class="col s12">
							<div id="tag_inputs">
								<p class="description">Add Products from Imonggo to 3dCart</p>
								<form method="POST">
									<a class="btn-floating btn-large waves-effect waves-light cyan" onclick="addInput()"><i class="material-icons">add</i></a>
									
									<div class="input-field col s12 tag">
									  <i class="material-icons prefix">label</i>
									  <input type="text" placeholder="Product Tag" name="tag0" />
									  <span id="tag-input-fields"></span>
									</div>
									<div class="col s12">
										<button class="btn waves-effect waves-light add-product-submit-button" type="submit" name="update_products">
											<i class="material-icons right">send</i>
											Submit
										</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<!----------------------------- END OF ADD PRODUCTS PANEL ---------------------------->
				
				
				<!-------------------------------- POST INVOICES PANEL -------------------------------->
				<div class="col s4">
					<div class="card-panel pink lighten-5 col s12">
						<button type="button" class="btn waves-effect waves-light pink col s12" name="update_invoices"><i class="material-icons left">playlist_add</i>Post Invoices</button>
						
						<div class="col s12">
							<div id="checkbox_choice">
								<form method="POST">
								
									<div class="description col s12 row">
										Post Invoices from 3dCart to Imonggo
									</div>
									<button class="btn waves-effect waves-light pink" type="submit" name="update_invoices"><i class="material-icons right">send</i>Submit</button>
								</form>
							</div>
						</div>
					</div>
				</div>
				<!----------------------------- END OF POST INVOICES PANEL --------------------------->
				
				
				<!-------------------------------- ADD CUSTOMERS PANEL ------------------------------->
				<div class="col s4">
					<div class="card-panel cyan lighten-5 col s12">
						<button type="button" class="btn waves-effect waves-light cyan col s12"><i class="material-icons left">group_add</i>Add Customers</button>
						<div class="col s12">
							<div id="checkbox_choice">
								<form method="POST">
									<div class="description col s12 row">
										Add Customers from 3dCart to Imonggo
									</div>
									<div class="col s12 row">
										Where do you want to get the customer information to be saved in Imonggo?
									</div>
									<div class="row">
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
									<button class="btn waves-effect waves-light cyan" type="submit" name="update_customers"><i class="material-icons right">send</i>Submit</button>
									
								</form>
							</div>
						</div>
					</div>
				</div>
				<!---------------------------- END OF ADD CUSTOMERS PANEL ----------------------------->
			</div>
			
			
		</section>
		
		
		<!-------------------------------------- FOOTER -------------------------------------->
        <footer class="page-footer grey darken-2">
			<div class="col s12 row">
				© 2015 &middot Developed and maintained by Erica Mae Yeban
			</div>
		</footer>
		<!----------------------------------- END OF FOOTER ---------------------------------->
		
		
		
	</body>
	<!-------------------------------- END OF PAGE BODY ---------------------------------->
	
	
</html>