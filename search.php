<?php 
include("includes/config.php");
$q = filter_input(INPUT_GET, 'q', FILTER_SANITIZE_STRING);
$category = filter_input(INPUT_GET, 'category', FILTER_SANITIZE_STRING);


include("templates/header.php");
?>



<?php
if(!empty($q)) {
?>
      <h1 class="mt-5">Zoeken</h1>

	  <form action="<?php echo PATH; ?>/search.php" method="get">
  <div class="form-group">
    <input type="search" class="form-control" name="q" placeholder="Zoeken....">
  </div>
  <div class="form-group">
    <label>Categorie</label>
	      <select id="inputState" name="category" class="form-control">
 <option value="">Alle categorieen</option>
  
 <?php 
$stmt = $pdo->query('SELECT * FROM suppliercategories');
while ($row = $stmt->fetch()) // Alle categorieen ophalen en tonen in HTML.
{
		echo '<option value="'.$row['SupplierCategoryID'].'">'.$row['SupplierCategoryName'].'</option>';
}
  ?>
 
</select>  </div>
  
  <button type="submit" class="btn btn-primary">Zoeken</button>
</form>

</form>

<h1 class="mt-5">Dit is gevonden op trefwoord: <?php echo $q; ?></h1>


<div class="row">
 
<?php

if(empty($category)) { // Als er alleen op naam gezocht wordt
$stmt = $pdo->prepare('SELECT * FROM stockitems WHERE StockItemName LIKE :q LIMIT 50');
$stmt->execute(['q' => '%'.$q.'%']); // LOL.... Fijn dat PDO (':
}


else { // Als er op naam EN categorie gezocht wordt
	$stmt = $pdo->prepare('SELECT SI.StockItemName, SI.StockItemID 
FROM stockitems as SI 
JOIN stockitemstockgroups SISG ON SI.StockItemID = SISG.StockItemID 
JOIN stockgroups SG ON SG.StockGroupID = SISG.StockGroupID
WHERE SG.StockGroupID = :category AND SI.StockItemName LIKE :q');
$stmt->execute(['q' => '%'.$q.'%', 'category' => $category]); // LOL.... Fijn dat PDO (':
}

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
?>

</div>

<?php } else { ?>

      <h1 class="mt-5">Zoeken</h1>
	  
	  
	  <form action="<?php echo PATH; ?>/search.php" method="get">
  <div class="form-group">
    <input type="search" class="form-control" name="q" placeholder="Zoeken....">
  </div>
  <div class="form-group">
    <label>Categorie</label>
	      <select id="inputState" name="category" class="form-control">
 <option value="">Alle categorieen</option>
  
 <?php 
$stmt = $pdo->query('SELECT * FROM suppliercategories');
while ($row = $stmt->fetch()) // Alle categorieen ophalen en tonen in HTML.
{
		echo '<option value="'.$row['SupplierCategoryID'].'">'.$row['SupplierCategoryName'].'</option>';
}
  ?>
 
</select>  </div>
  
  <button type="submit" class="btn btn-primary">Zoeken</button>
</form>

</form>
<?php } 

include("templates/footer.php");

?>