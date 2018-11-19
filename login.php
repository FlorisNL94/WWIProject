<?php 
include("includes/config.php");

if(isset($_SESSION['user'])) { header("Location: ".PATH."/"); } // Als je al bent ingelogd doorsturen...

$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING); // Geen idee of dit goed gaat omdat een wachtwoord vreemde tekens kan bevatten.

if(!empty($email)) { 

	$stmt = $pdo->prepare('SELECT id FROM users WHERE email = :email LIMIT 1');
	$stmt->execute(['email' => $email]); 
	$row = $stmt->fetch();

	if(!empty($row['id'])) {
		// Jeuj, we krijgen een ID terug dus we mogen er van uitgaan dat de login goed is.
		$_SESSION['user'] = $row['id'];
		header("Location: ".PATH."/"); exit;
	} else {
		
		$message = "Foute gebruikersnaam of wachtwoord.";
	}
	
}

include("templates/header.php");
?>
 <div class="row">
      <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
        <div class="card card-signin my-5">
          <div class="card-body">
            <h5 class="card-title text-center">Inloggen</h5>
			<?php if(isset($message)) { echo '<div class="alert alert-warning" role="alert">
  '.$message.'
</div>'; } ?>
            <form class="form-signin" action="<?php echo PATH; ?>/login.php" method="post">
              <div class="form-label-group">
			                  <label for="inputEmail">E-mailadres:</label>

                <input type="email" id="inputEmail" name="email" class="form-control" placeholder="E-mailadres" required autofocus>
</div>
              <div class="form-label-group">
			                  <label for="inputPassword">Wachtwoord:</label>
                <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Wachtwoord" required>
              </div>

             <br>
              <button class="btn btn-lg btn-primary btn-block text-uppercase" type="submit">Inloggen</button>
            </form>
          </div>
        </div>
      </div>
    </div>



<?php

include("templates/footer.php");

?>