<h1>Toutes les actualités</h1>
<?php
		if($_SESSION['permission'] == "admin") { ?>
			<div class="outils admin">
				<a href="index.php?page=news&amp;outils=ajouter" class="btn btn-info"><i class="icon-plus"></i> Ajouter une actualité</a>
			</div>
<? 		}

	if ($_GET['id'] != '') {
		$rq = 'SELECT id, titre, texte, accroche, photo FROM '.$prefix.'news WHERE id = '.$_GET["id"].' ORDER BY id DESC';
		$rs = mysqli_query($link,$rq) OR die('Erreur : '.mysqli_error($link));
		
	} else {

		$rq = 'SELECT id, titre, texte, accroche, photo FROM '.$prefix.'news ORDER BY id DESC';
		$rs = mysqli_query($link,$rq) OR die('Erreur : '.mysqli_error($link));
	}
	
	while($content = mysqli_fetch_array($rs)) { ?>
		<div class="news liste">
		
		<? if($content['titre'] != "") { ?> 
			<h2><span><?=htmlspecialchars_decode($content['titre']);?></span></h2>
		<? }
			if($content['photo'] != "") { ?> 
			<div class="illu"><a href="images/news/<?=$content['photo'];?>"><img src="images/news/illu/<?=$content['photo'];?>" alt="<?=htmlspecialchars_decode($content['titre']);?>" /></a></div>
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
			if($content['texte'] != "") { ?>
				<p class='texte'><?=htmlspecialchars_decode($content['texte']);?></p>
			<? } ?>
		</div>
		<? if($_SESSION['permission'] == "admin") { ?>
			<div class="outils admin">
				<a href="index.php?page=news&amp;outils=modifier&amp;id=<?=$content['id'];?>" class="btn"><i class="icon-edit"></i> Modifier</a>
			</div>
		<?php } ?>
			</div>
	<?php } ?>