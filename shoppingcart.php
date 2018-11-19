<?php 
include("includes/config.php");

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
$pID = filter_input(INPUT_GET, 'pID', FILTER_SANITIZE_STRING);

if($action == "empty") {

	$_SESSION['shoppingcart'] = NULL; // Sessie shoppingcart leeg gooien zodat je winkelmand weer leeg is.
	header("Location: ".PATH."/shoppingcart.php"); // En weer terug naar de winkelmand pagina.

} elseif($action == "addOne" && !empty($pID)) {

// pak het count op wat we hebben van een product en tel hier + 1 bij op.
	$_SESSION['shoppingcart'][$pID] ++;
	header("Location: ".PATH."/shoppingcart.php"); // En weer terug naar de winkelmand pagina.

} elseif($action == "removeOne" && !empty($pID)) {
	if ($_SESSION['shoppingcart'][$pID] === 1) {
		unset($_SESSION['shoppingcart'][$pID]);
	} else {
		$_SESSION['shoppingcart'][$pID] --;
	}
	header("Location: ".PATH."/shoppingcart.php"); // En weer terug naar de winkelmand pagina.
}

include("templates/header.php");
echo '<h1>Winkelmand</h1>';
if(!empty($_SESSION['shoppingcart'])) { // Alleen laten zien als er producten in de winkelmand zitten (in de sessie shoppingcart dus).
?>
<div class="row">
<?php

foreach($_SESSION['shoppingcart'] as $pId => $count) {
	$stmt = $pdo->prepare('SELECT * FROM stockitems WHERE StockItemID = :pID LIMIT 1');
	$stmt->execute(['pID' => $pId]); 
	$row = $stmt->fetch();
	echo '<div class="col-sm-4" style="padding-bottom: 15px;"><div class="card" style="width: 18rem;">
  <a href="'.PATH.'/product.php?pID='.$row['StockItemID'].'"><img class="card-img-top" src="https://developers.video.ibm.com/images/example-channel-nasa.jpg"></a>
  <div class="card-body">
    <h6 class="card-title"><strong>'.$row['StockItemName'].'</strong><p>'.$row['SearchDetails'].'</p><p><i>&euro; '.$row['UnitPrice'].'</i></p></h6>
    <p><a href="'.PATH.'/product.php?pID='.$row['StockItemID'].'" class="btn btn-primary">Product bekijken</a></p>
	<p>Aantal: <i>'.$count.'</i> <a href="'.PATH.'/shoppingcart.php?action=addOne&pID='.$row['StockItemID'].'" class="badge badge-dark">+</a> <a href="'.PATH.'/shoppingcart.php?action=removeOne&pID='.$row['StockItemID'].'" class="badge badge-dark">-</a></p>
  </div>
</div></div>';
}	

?>
</div>
<hr>
<a href="<?php echo PATH; ?>/shoppingcart.php?action=empty" class="btn btn-secondary">Winkelmand legen</a>
<?php } else { ?>

<p>Je hebt nog geen producten in je winkelmand...</p>

<?php } 

include("templates/footer.php");
?>