<!DOCTYPE html>

<!--
	File name: index.php
	Description:
		This file contains the user interface for the login page
-->

<?php
	session_start();
	
	/* if imonggo_api_key of the session is already initialized, direct user to homepage.php */
	if(isset($_SESSION['imonggo_api_key'])){
		header("location: homepage.php");
	}
	
	/* include necessary files */
	include("functions/general_functions.php");
	include("authentication.php");
?>

<html lang="en">


	<!----------------- PAGE HEAD: PAGE TITLE, STYLESHEETS, AND SCRIPTS ------------------>
	<head>
		<?php include("homepage_head.php");?>
	</head>
	<!--------------------------------- END OF PAGE HEAD --------------------------------->


	
	<!------------------------------------- PAGE BODY ------------------------------------>
	<body>
		<div class="container">
			<div class="row login-card">
				<form class="col l6 offset-l3 m8 offset-m2 s12 center" method="POST">
					<div class="card blue-grey darken-1">
						<div class="card-content white-text">
							<span class="card-title">Login to I3 Integrator</span>
							<p>
								<div class="input-field">
									<i class="material-icons prefix">account_box</i>
									<input id="account_id" name="imonggo_account_id" type="text" class="validate" required />
									<label for="account_id">Imonggo Account ID</label>
								</div>
								<div class="input-field">
									<i class="material-icons prefix">email</i>
									<input id="email" name="imonggo_email" type="email" class="validate" required/>
									<label for="email">Imonggo Email Address</label>
								</div>
								<div class="input-field">
									<i class="material-icons prefix">vpn_key</i>
									<input id="password" name="imonggo_password" type="password" class="validate" required />
									<label for="password">Imonggo Password</label>
								</div>
								<div class="input-field">
									<i class="material-icons prefix">lock</i>
									<input id="secure-url" name="secure_url" type="text" class="validate" required />
									<label for="secure-url">3dCart Secure URL</label>
								</div>
								<div class="input-field">
									<i class="material-icons prefix">toll</i>
									<input id="token" name="token" type="text" class="validate" required />
									<label for="token">3dCart Token</label>
								</div>
							</p>
						</div>
						<div class="card-action">
							<button class="btn light-green lighten-3 black-text" type="submit" name="login">Login</a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</body>
	
</html>