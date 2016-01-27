<h1>Ils sont venus... ils en parlent !</h1>

<?php
		if($_SESSION['permission'] == "admin") { ?>
			<div class="outils admin">
				<a href="index.php?page=livredor&amp;outils=ajouter&amp;type=livredor" class="btn btn-info"><i class="fa fa-plus"></i> Ajouter un témoignage</a>
			</div>
<? 		}
	
	$rq = 'SELECT * FROM '.prefix.'livre_dor ORDER BY id DESC';
	$rs = mysqli_query($link,$rq) OR die('Erreur : '.mysqli_error($link));
	
	while($content = mysqli_fetch_array($rs)) {
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