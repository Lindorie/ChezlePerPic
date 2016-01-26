<h1>Ils sont venus... ils en parlent !</h1>

<?php
		if($_SESSION['permission'] == "admin") { ?>
			<div class="outils admin">
				<a href="index.php?page=livredor&amp;outils=ajouter&amp;type=livredor" class="btn btn-info"><i class="fa fa-plus"></i> Ajouter un témoignage</a>
			</div>
<? 		}
	
	$rq = 'SELECT * FROM livre_dor ORDER BY id DESC';
	$rs = mysql_query($rq) OR die('Erreur : '.mysql_error());
	
	while($content = mysql_fetch_array($rs)) { 
?>
		<div class="livredor liste" id="id<?=$content['id'];?>">
		
		<h2><span><?=$content['auteur'];?> - <?=$content['mois_loc'];?></span></h2>

		<div class="detail">
	
			<p class="texte"><?php echo htmlspecialchars_decode($content['texte']); ?></p>  

		</div>

		<? if($_SESSION['permission'] == "admin") { ?>
			<div class="outils admin">
				<a class="btn" href="index.php?page=livredor&amp;outils=modifier&amp;id=<?=$content['id'];?>"><i class="fa fa-edit"></i> Modifier</a>
			</div>
		<?php } ?>
		</div>
	<?php } ?>