			<!--
				File name : navigation_bar.php
				Description :
					This file contains the end users' navigation bar
			-->
			
			<nav role="navigation" class="grey darken-3">
				<div class="nav-wrapper">
					
					<!-- if window is resized or mobile was used to open the homepage, show a collapsible side navigation bar through the menu icon -->
					<a href="#" data-activates="side-nav" class="button-collapse"><i class="material-icons">menu</i></a>
					
					<!-- Desktop view left side menu -->
					<ul class="hide-on-med-and-down left">
						<li><a href="http://localhost/imonggo-integration/"><img src="assets/images/logo.png" class="nav-logo"></img></a></li>
						<li><a href="https://camae.c3.imonggo.com/en/store" target="_blank">Imonggo Store</a></li>
						<li><a href="https://camae.c3.imonggo.com/en/invoices" target="_blank">Imonggo Manager View</a></li>
						<li><a href="http://sandbox-imonggo-com.3dcartstores.com" target="_blank">3dCart Store</a></li>
						<li><a href="https://sandbox-imonggo-com.3dcartstores.com/admin" target="_blank">3dCart Manager View</a></li>
					</ul>
					
					<!-- Desktop view right side menu -->
					<ul class="hide-on-med-and-down right">
						<li><a href="logout.php">Logout</a></li>
					</ul>
					
					
					<!-- mobile view collapsible side menu -->
					<ul class="side-nav" id="side-nav">
						<li><a href="https://camae.c3.imonggo.com/en/store" target="_blank">Imonggo Store</a></li>
						<li><a href="https://camae.c3.imonggo.com/en/invoices" target="_blank">Imonggo Manager View</a></li>
						<li><a href="http://sandbox-imonggo-com.3dcartstores.com" target="_blank">3dCart Store</a></li>
						<li><a href="https://sandbox-imonggo-com.3dcartstores.com/admin" target="_blank">3dCart Manager View</a></li>
						<li><a href="logout.php">Logout</a></li>
					</ul>
					
				</div>
			</nav>
			