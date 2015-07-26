<!DOCTYPE>

<?php
	session_start();
	include("functions/general_functions.php");
	
	if(isset($_SESSION['token'])){
		header("location: homepage.php");
	}
	
	if(isset($_POST['login'])){
		$account_id = $_POST['account_id'];
		$email = $_POST['email'];
		$password = $_POST['password'];
		$_SESSION['host'] = $_POST['host'];
		$_SESSION['secure_url'] = $_POST['secure_url'];
		$_SESSION['token'] = $_POST['token'];
		
		$_SESSION['account_id'] = $account_id;
		$_SESSION['email'] = $email;
		$_SESSION['password'] = $password;
		
		$imonggo_url = "https://" . $account_id . ".c3.imonggo.com/api/tokens.xml?email=" . $email . "&password=" . $password;
		$imonggo_token = (string)get_imonggo_token($imonggo_url);
		
		if($imonggo_token != null){
			$_SESSION['token'] = $imonggo_token;
			header("location:homepage.php");
		}
		else{
			echo "destroy";
			session_unset();
			session_destroy();	
			header("location:index.php");
		}
		
	}
?>

<html lang="en">


	<!----------------- PAGE HEAD: PAGE TITLE, STYLESHEETS, AND SCRIPTS ------------------>
	<head>
		<?php include("index_head.php");?>
	</head>
	<!--------------------------------- END OF PAGE HEAD --------------------------------->


	
	<!------------------------------------- PAGE BODY ------------------------------------>
	<body>
		<div class="container">
			<div class="container"><br/>
				<div class="row">
					<form class="col s12" method="POST">
						<div class="card blue-grey darken-1">
							<div class="card-content white-text">
								<span class="card-title">Login to I3 Integrator</span>
								<p>
									<div class="input-field">
										<i class="material-icons prefix">account_box</i>
										<input id="account_id" name="account_id" type="text" class="validate" required />
										<label for="account_id">Account ID</label>
									</div>
									<div class="input-field">
										<i class="material-icons prefix">email</i>
										<input id="email" name="email" type="email" class="validate" required/>
										<label for="email">Email Address</label>
									</div>
									<div class="input-field">
										<i class="material-icons prefix">vpn_key</i>
										<input id="password" name="password" type="password" class="validate" required />
										<label for="password">Password</label>
									</div>
									<div class="input-field">
										<i class="material-icons prefix">web</i>
										<input id="host" name="host" type="text" class="validate" required />
										<label for="host">3dCart Store Host</label>
									</div>
									<div class="input-field">
										<i class="material-icons prefix">lock</i>
										<input id="secure-url" name="secure_url" type="text" class="validate" required />
										<label for="secure-url">3dCart Secure URL</label>
									</div>
									<div class="input-field">
										<i class="material-icons prefix">toll</i>
										<input id="token" name="token" type="text" class="validate" required />
										<label for="token">Token</label>
									</div>
								</p>
							</div>
							<div class="card-action">
								<button class="btn" type="submit" name="login">Login</a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</body>
	
</html>