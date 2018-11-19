<?php 
include("includes/config.php");
$cID = filter_input(INPUT_GET, 'cID', FILTER_SANITIZE_NUMBER_INT);

include("templates/header.php");

if(!empty($cID)) {

 $stmt = $pdo->prepare('SELECT StockGroupName 
FROM stockgroups WHERE StockGroupID = :cID LIMIT 1');
$stmt->execute(['cID' => $cID]); 

$StockGroups = $stmt->fetch(); 
if(!$StockGroups) {
		header("Location: ".PATH."/category.php"); // Categorie bestaat niet dus we laten de gebruiker een wel bestaande categorie kiezen...
}


echo '<h1 class="mt-5">'.$StockGroups['StockGroupName'].'</h1>';

?>
<div class="row">
<?php

$stmt = $pdo->prepare('SELECT SI.StockItemName, SI.StockItemID 
FROM stockitems as SI 
JOIN stockitemstockgroups SISG ON SI.StockItemID = SISG.StockItemID 
JOIN stockgroups SG ON SG.StockGroupID = SISG.StockGroupID
WHERE SG.StockGroupID = :cID');
$stmt->execute(['cID' => $cID]); 

if($stmt->fetch()) {
while ($row = $stmt->fetch())
{
	echo '<div class="col-sm-4" style="padding-bottom: 15px;"><div class="card" style="width: 18rem;">
  <a href="'.PATH.'/product.php?pID='.$row['StockItemID'].'"><img class="card-img-top" src="https://developers.video.ibm.com/images/example-channel-nasa.jpg"></a>
  <div class="card-body">
    <h6 class="card-title"><strong>'.$row['StockItemName'].'</strong></h5>
    <a href="'.PATH.'/product.php?pID='.$row['StockItemID'].'" class="btn btn-primary">Product bekijken</a>
  </div>
</div></div>';
}
} else {
	echo '<p>Geen producten gevonden in deze categorie.</p>';
	
}
?>
</div>

<?php } else { ?>

<h1 class="mt-5">Categorie&euml;n</h1>


<?php

$stmt = $pdo->query('SELECT * FROM stockgroups');

while($row = $stmt->fetch()) {

	    echo '<a class="btn btn-light" style="margin-right:5px;margin-bottom:5px;" href="'.PATH.'/category.php?cID='.$row['StockGroupID'] . '">'.$row['StockGroupName'] . '</a></h3>';
		
}

?>

<?php } 

include("templates/footer.php");
?>