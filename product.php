<?php 
include("includes/config.php");
$pID = filter_input(INPUT_GET, 'pID', FILTER_SANITIZE_NUMBER_INT);
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);

include("templates/header.php");


if($action == "shoppingcart") {
	
	// Code om items toe te voegen aan de winkelmand....
	if ((! isset($_SESSION['shoppingcart'])) || (! is_array($_SESSION['shoppingcart']))) {
		$_SESSION['shoppingcart'] = [];
	}
	if ((! is_null($pID)) && (! in_array($pID, $_SESSION['shoppingcart']))) {
		$_SESSION['shoppingcart'][$pID] = 1;
	}
	
	header("Location: ".PATH."/product.php?pID=".$pID);
}

if(!empty($pID)) {
?>
<?php

$stmt = $pdo->prepare('SELECT * FROM stockitems WHERE StockItemID = :pID LIMIT 1');
$stmt->execute(['pID' => $pID]); // LOL.... Fijn dat PDO (':

$row = $stmt->fetch();
		echo '<p>';
	    echo '<h1>'.$row['StockItemName'] . '</h1>';
		echo '<p><img src="https://developers.video.ibm.com/images/example-channel-nasa.jpg" /></p><br />';
		echo '<p><strong>Produtct beschrijving:</strong><br /><i>'.$row['SearchDetails'].'</i></p>';
		
		echo '<p>Product is op voorraad<br>';
		echo 'Levertijd: 2 - 5 dagen.</p>';

		if(!empty($row['Size'])) {
			echo '<br><br><strong>Product details:</strong>';
			echo '<p>Maat: <i>'.$row['Size'].'</i></p>';
		}
		echo '</p><p><i>Prijs: &euro; '.$row['UnitPrice'].'</i></p>';
		if(isset($_SESSION['user'])) { // Je mag alleen dingen plaatsen als gebruiker.
		echo '<a class="btn btn-primary btn-sm" href="'.PATH.'/product.php?pID='.$pID.'&action=shoppingcart">Plaatsen in Winkelmand</a>';
		}
?>



<?php } else { ?>

<h1>Geef een product ID op...</h1>

<?php } 

include("templates/footer.php");

?>