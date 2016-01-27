<h1>Activités</h1>


<?php
		if($_SESSION['permission'] == "admin") { ?>
			<div class="outils admin">
				<a href="index.php?page=activites&amp;outils=ajouter" class="btn btn-info"><i class="fa fa-plus"></i> Ajouter une activité</a>
			</div>
<? 		}
	if (isset($_GET['detail'])) {
	
		$rq = 'SELECT id, titre, texte, accroche, photo, categorie FROM '.$prefix.'activites WHERE id = '.$_GET['detail'].'';
	} else {
	
		$rq = 'SELECT id, titre, texte, accroche, photo, categorie FROM '.$prefix.'activites ORDER BY id DESC';
	}
	$rs = mysqli_query($link,$rq) OR die('Erreur : '.mysqli_error($link));
	
	while($content = mysqli_fetch_array($rs)) { ?>
		<div class="activite liste" id="id<?=$content['id'];?>">
		
		<? if($content['titre'] != "") { ?> 
			<h2><span><?=$content['titre'];?></span></h2>
		<? }
			if($content['photo'] != "") { ?> 
			<div class="illu"><a href="images/activites/<?=$content['photo'];?>" class="colorbox" title="<?=$content['titre'];?>"><img src="images/activites/illu/<?=$content['photo'];?>" alt="<?=$content['titre'];?>" /></a></div>
		<? } ?>
		<div class="detail">
			<?
			if($content['categorie'] != "") { 
				$rq2 = 'SELECT libelle FROM '.$prefix.'categorie WHERE id IN ('.$content['categorie'].') ORDER BY libelle';
				$rs2 = mysqli_query($link,$rq2) OR die('Erreur : '.mysqli_error($link));
				$categories = array();
				while ($cat = mysqli_fetch_array($rs2)) {
					$categories[] = $cat['libelle'];
				}
				$nb_cat = count($categories);
				$cate = "";
				for($i=0;$i<$nb_cat;$i++) {
					if($i==($nb_cat-1)) {
					$cate .= $categories[$i]; 
					} else {
					$cate .= $categories[$i].', '; }
				} ?>
				<p class='cat'><?=$cate;?></p>
			<? }
			if($content['accroche'] != "") { ?>
				<p class='accroche'><?=htmlspecialchars_decode($content['accroche']);?></p>
			<? } 
			if($content['texte'] != "") { 
				if (isset($_GET['detail'])) { ?>
					<p class="texte"><?php echo htmlspecialchars_decode($content['texte']); ?></p>  
					<div class="alignLeft"><a href="index.php?page=activites" class="btn"><i class="fa fa-arrow-left"></i> Retour à la liste</a></div>
				<?php } else { ?>
				<div class="alignRight"><a href="index.php?page=activites&amp;detail=<?=$content['id'];?>" class="btn btn-info">En savoir plus <i class="fa fa-chevron-circle-right"></i></a></div>
				<?php }
			} ?>
			
		</div>
		<? if($_SESSION['permission'] == "admin") { ?>
			<div class="outils admin">
				<a class="btn" href="index.php?page=activites&amp;outils=modifier&amp;id=<?=$content['id'];?>"><i class="fa fa-edit"></i> Modifier</a>
			</div>
		<?php } ?>
			</div>
	<?php } ?>