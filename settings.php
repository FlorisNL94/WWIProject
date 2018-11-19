<?php 
include("includes/config.php");

if(!isset($_SESSION['user'])) { header("Location: ".PATH."/"); } // Als je niet bent ingelogd doorsturen...

include("templates/header.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Formulier wordt verzonden... Alles controleren en daarna updaten in de DB.
	
	$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
	$fullname = filter_input(INPUT_POST, 'fullname', FILTER_SANITIZE_STRING);
	$address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
	$address2 = filter_input(INPUT_POST, 'address2', FILTER_SANITIZE_STRING);
	$city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_STRING);
	$state = filter_input(INPUT_POST, 'state', FILTER_SANITIZE_STRING);
	$zip = filter_input(INPUT_POST, 'zip', FILTER_SANITIZE_STRING);

	if(!$email) {
		
		// Alleen nog een controle inbouwen om te kijken of een e-mailadres al gebruikt wordt bij bijvoorbeeld een andere gebruiker.
		$error = "Graag een geldig e-mailadres invullen.";
		
	} elseif($fullname >= 5) {
		
		$error = "Een volledige naam moet minstens 5 karakters lang zijn...";

	
	} else { // Controle voor adres etc moet er nog komen.
		
		$_SESSION['message'] = "Gegevens zijn geupdatet!";
		
		$sql = "UPDATE users SET email = ?, fullname = ?, address = ?, address2 = ?, city = ?, state = ?, zip = ?  WHERE id = ?";
		$pdo->prepare($sql)->execute([$email, $fullname, $address, $address2, $city, $state, $zip, $user_row['id']]);
		header("Location: ".PATH."/settings.php"); 
		exit;

		
	}
	
	// adres mag leeg zijn, alleen even controleren of die langer is dan een bepaalde waarde?
	
	
}


?>
<h1>Instellingen</h1>

		<?php if(isset($_SESSION['message'])) { echo '<div class="alert alert-success" role="alert">
  '.$_SESSION['message'].'
</div>'; UNSET($_SESSION['message']); } ?>
	<?php if(isset($error)) { echo '<div class="alert alert-warning" role="alert">
  '.$error.'
</div>'; } ?>
	  <form action="<?php echo PATH; ?>/settings.php" method="post">
    <div class="form-group">
      <label for="inputEmail4">Email</label>
      <input type="email" name="email" class="form-control" id="inputEmail4" value="<?php echo $user_row['email']; ?>" placeholder="Email">
    </div>
	<hr>
	<h2>Adresgegevens</h2><br>

   <div class="form-group">
    <label for="inputAddress">Full name</label>
    <input type="text" name="fullname" class="form-control" id="inputFullname" value="<?php echo $user_row['fullname']; ?>"  placeholder="Jan Smit">
  </div>
  <div class="form-group">
    <label for="inputAddress">Address</label>
    <input type="text" name="address" class="form-control" id="inputAddress" value="<?php echo $user_row['address']; ?>" placeholder="1234 Main St">
  </div>
  <div class="form-group">
    <label for="inputAddress2">Address 2</label>
    <input type="text" name="address2" class="form-control" id="inputAddress2" value="<?php echo $user_row['address2']; ?>" placeholder="Apartment, studio, or floor">
  </div>
  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="inputCity">City</label>
      <input type="text" name="city" class="form-control" value="<?php echo $user_row['city']; ?>" id="inputCity">
    </div>
    <div class="form-group col-md-4">
      <label for="inputState">State</label>
      <select id="inputState" name="state" class="form-control">
        <option>Kies...</option>
	<?php
$stmt = $pdo->query('SELECT * FROM stateprovinces');

while($row = $stmt->fetch()) { 
if($row['StateProvinceCode'] == $user_row['state']) { $selected = "selected "; } else { $selected = NULL; }

echo '<option '.$selected.'value="'.$row['StateProvinceCode'].'">'.$row['StateProvinceName'].'</option>'; }
	?>      </select>
    </div>
    <div class="form-group col-md-2">
      <label for="inputZip">Zip</label>
      <input type="text" class="form-control" name="zip" value="<?php echo $user_row['zip']; ?>" id="inputZip">
    </div>
  </div>
  
  <button type="submit" class="btn btn-primary">Opslaan</button>
</form>


<?php

include("templates/footer.php");

?>