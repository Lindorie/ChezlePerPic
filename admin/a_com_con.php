<br />

<?php 
	if (isset($_GET['outils']) AND $_GET['outils'] == "supprimerdocs") {
		
		// ################### SUPPRIMER DOCUMENT #####################
		
		$rq = 'SELECT nom FROM '.$prefix.'consultants_docs WHERE id = '.$_GET['id'];
		$rs = mysqli_query($link,$rq) OR die(mysqli_error($link));
		$d = mysqli_fetch_assoc($rs);
		echo suppr_doc($_GET['id'],$d['nom']);
		echo '<div class="alignLeft"><a href="?page=prive&amp;show=com_con&amp;outils=voirdocs#documents" class="btn"><i class="fa fa-circle-arrow-left"></i> Retour à la liste</a></div>';
	
	} else if (isset($_GET['outils']) AND $_GET['outils'] == "supprimermsg") {
		// ################### SUPPRIMER MESSAGE #####################
		echo supprimer("consultants_msg",$_GET['id']);
		echo '<div class="alignLeft"><a href="?page=prive&amp;show=com_con&amp;outils=voirmsg#messages" class="btn"><i class="fa fa-circle-arrow-left"></i> Retour</a></div>';
	
	} else if (isset($_GET['outils']) AND $_GET['outils'] == "voirdocs") {
		
		// ################## LISTE DES DOCUMENTS #######################
		
		$rq = 'SELECT id, titre, nom, DATE_FORMAT(date, "%d/%m/%Y") as date FROM '.$prefix.'consultants_docs ORDER BY date DESC';
		$rs = mysqli_query($link,$rq) OR die (mysqli_error($link).'<br />'.$rq);
		
		$nb = mysqli_num_rows($rs);
		if ($nb == 0) {
			echo '<div class="info light">Il n\'y a aucun document partagé.</div>';
		} else { ?>
			<p><?php echo $nb; ?> documents partagés.</p>
			<table id="documents" class="liste_photos">
				<thead>
					<tr>
						<th>Titre du doc</th>
						<th>Nom du fichier</th>
						<th>Date d'envoi</th>
						<th>Outils</th>
					</tr>
				</thead>
				<tbody>
<?php 	
			while ($d = mysqli_fetch_assoc($rs)) { ?>
					<tr>
						<td><a href="admin/documents/<?php echo $d['nom'] ?>"><?php echo $d['titre'] ?></a></td>
						<td class="alignCenter"><?php echo $d['nom'] ?></td>
						<td class="alignCenter"><?php echo $d['date'] ?></td>
						<td class="alignCenter"><a href="?page=prive&amp;show=com_con&amp;outils=supprimerdocs&amp;id=<?php echo $d['id'] ?>" class="btn btn-default btn-danger"><i class="fa fa-trash"></i> Supprimer</a></td>
					</tr>
<?php 
			} ?>
				</tbody>
			</table>
			<div class="alignRight"><a href="?page=prive&amp;show=com_con&amp;outils=ajoutdocs#documents" class="btn btn-info"><i class="fa fa-plus"></i> Ajouter un document</a></div>
<?php 
		}
?>
<?php 
		$voirdocs = true;
	
	} else if (isset($_GET['outils']) AND ($_GET['outils'] == "voirmsg" OR $_GET['outils'] == 'voirmsgenvoyes')) {
	
		// ################## MESSAGERIE #######################
		
		if ($_GET['outils'] == "voirmsg") { $a_r = "active"; $a_e = ""; }
		elseif ($_GET['outils'] == "voirmsgenvoyes") { $a_r = ""; $a_e = "active"; }
		else  { $a_r = "active"; $a_e = ""; }
?>
	<div class="btn-group" id="messages">
		<a class="btn <?php echo $a_r; ?>" href="?page=prive&amp;show=com_con&amp;outils=voirmsg#messages"><i class="fa fa-envelope"></i> Messages reçus</a>
		<a class="btn <?php echo $a_e; ?>" href="?page=prive&amp;show=com_con&amp;outils=voirmsgenvoyes#messages"><i class="fa fa-share-alt"></i> Messages envoyés</a>
	</div>
	
	<table class="liste_photos">
		<thead>
			<tr>
				<th>Etat</th>
				<th>Message</th>
				<th>Réponses</th>
				<th>Date</th>
			</tr>
		</thead>
		<tbody>
<?php 
	if (isset($_GET['outils']) AND $_GET['outils'] == 'voirmsgenvoyes') {
		// MESSAGES ENVOYES
		$rq = 'SELECT titre, identifiant, consultants_msg.id, DATE_FORMAT(date, "%d/%m/%Y %H:%i") as date, lecture FROM '.$prefix.'consultants_msg, '.$prefix.'user WHERE auteur = '.$_SESSION['id'].' AND parent = "" AND user.id = consultants_msg.auteur ORDER BY date ASC;';
	} elseif (isset($_GET['outils']) AND $_GET['outils'] == 'voirmsg') {
		// MESSAGES RECUS
		$rq = 'SELECT titre, identifiant, consultants_msg.id, DATE_FORMAT(date, "%d/%m/%Y %H:%i") as date, lecture FROM '.$prefix.'consultants_msg, '.$prefix.'user WHERE destinataire = '.$_SESSION['id'].' AND parent = "" AND user.id = consultants_msg.auteur ORDER BY date ASC;';
	}
	$rs = mysqli_query($link,$rq) OR die (mysqli_error($link));
	$nb = mysqli_num_rows($rs);
	if ($nb == 0) { echo '<tr><td class="alignCenter" colspan="3">Aucun message.</td></tr>'; }
	else {
		while ($m = mysqli_fetch_assoc($rs)) {
			
			// Réponses 
			$req = 'SELECT COUNT(id) as nb_rep FROM '.$prefix.'consultants_msg WHERE parent = '.$m['id'];
			$res = mysqli_query($link,$req) OR die(mysqli_error($link));
			$r = mysqli_fetch_assoc($res);
	
?>
			<tr>
				<td class="alignCenter">
					<?php if (!$m['lecture']) { ?>
					<span class="non_lu" title="Le message n'a pas été lu par son destinataire"><i class="fa fa-envelope icon-large"></i></span>
					<?php } else { ?>
					<span class="lu" title="Le message a été lu par son destinataire"><i class="fa fa-envelope-alt icon-large"></i></span>
					<?php } ?>
				</td>
				<td><a href="?page=prive&amp;show=com_con&amp;outils=voirdetailmsg&amp;id=<?php echo $m['id']; ?>#messages"><?php echo htmlspecialchars_decode($m['titre']); ?></a><br /><span>par <?php echo $m['identifiant']; ?></span></td>
				<td class="alignCenter"><?php echo $r['nb_rep']; ?></td>
				<td class="alignCenter"><?php echo $m['date']; ?></td>
			</tr>
<?php 
		}
	} 
?>
		</tbody>
	</table>
	<div class="alignRight"><a href="?page=prive&amp;show=com_con&amp;outils=envoimsg#messages" class="btn btn-info"><i class="fa fa-edit"></i> Nouveau message</a></div>
<?php 
	} else if (isset($_GET['outils']) AND $_GET['outils'] == "voirdetailmsg") {
?>

	<div class="btn-group" id="messages">
		<a class="btn <?php echo $a_r; ?>" href="?page=prive&amp;show=com_con&amp;outils=voirmsg#messages"><i class="fa fa-envelope"></i> Messages reçus</a>
		<a class="btn <?php echo $a_e; ?>" href="?page=prive&amp;show=com_con&amp;outils=voirmsgenvoyes#messages"><i class="fa fa-share-alt"></i> Messages envoyés</a>
	</div>
	<div class="alignRight"><a href="?page=prive&amp;show=com_con&amp;outils=envoimsg#messages" class="btn btn-info"><i class="fa fa-edit"></i> Nouveau message</a></div>
<?php
	// DETAIL D'UN MESSAGE
	$rq_m = 'SELECT consultants_msg.id as id, auteur, titre, destinataire, message, identifiant, DATE_FORMAT(date, "%d/%m/%Y %H:%i") as date, lecture FROM '.$prefix.'consultants_msg, '.$prefix.'user WHERE consultants_msg.id = '.$_GET['id'].' AND consultants_msg.auteur = user.id';
	$rs_m = mysqli_query($link,$rq_m) OR die (mysqli_error($link));
	
?>
<?php 
	while ($m = mysqli_fetch_assoc($rs_m)) {
	
	// Vérification et actualisation du statut de lecture
	if (!$m['lecture'] AND $_SESSION['id'] == $m['destinataire']) {
		$req = 'UPDATE '.$prefix.'consultants_msg SET lecture = 1 WHERE id = '.$m['id'].'';
		mysqli_query($link,$req) OR die(mysqli_error($link));
		$m['lecture'] = 1;
	}
?>
	<table class="liste_messages parent">
		<thead>
			<tr>
				<th class="alignCenter">De : <?php echo $m['identifiant']; ?></th>
				<th><?php echo $m['titre']; ?></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td class="alignCenter">
					<p>Etat : 
					<?php if (!$m['lecture']) { ?>
					<span class="non_lu" title="Le message n'a pas été lu par son destinataire"><i class="fa fa-envelope icon-large"></i></span>
					<?php } else { ?>
					<span class="lu" title="Le message a été lu par son destinataire"><i class="fa fa-envelope-alt icon-large"></i></span>
					<?php } ?></p>
					<p><?php echo $m['date']; ?></p>
					<?php if ($_SESSION['permission'] == 'admin') { ?>
					<a href="?page=prive&amp;show=com_con&amp;outils=supprimermsg&amp;id=<?php echo $m['id']; ?>#messages" class="btn btn-danger btn-default"><i class="fa fa-trash"></i> Supprimer</a>
					<?php } ?>
				</td>
				<td><div class="message"><?php echo $m['message']; ?></div></td>
			</tr>
		</tbody>
	</table>
	
	<?php 
		// REPONSES DU MESSAGE
		$rq_r = 'SELECT consultants_msg.id as id, auteur, titre, message, destinataire, identifiant, DATE_FORMAT(date, "%d/%m/%Y %H:%i") as date, lecture FROM consultants_msg, user WHERE consultants_msg.parent = '.$m['id'].' AND consultants_msg.auteur = user.id ORDER BY date ASC';
		$rs_r = mysqli_query($link,$rq_r) OR die (mysqli_error($link));
		$nb = mysqli_num_rows($rs_r);
		if ($nb != 0) {
	?>
	<table class="liste_messages reponses">
		<thead>
			<tr>
				<th class="alignCenter" colspan="2">Réponses</th>
			</tr>
		</thead>
			<tbody>
	<?php 
		while ($r = mysqli_fetch_assoc($rs_r)) {
	
		// Vérification et actualisation du statut de lecture
		if (!$r['lecture'] AND $_SESSION['id'] == $r['destinataire']) {
			$req = 'UPDATE '.$prefix.'consultants_msg SET lecture = 1 WHERE id = '.$r['id'].'';
			mysqli_query($link,$req) OR die(mysqli_error($link));
			$r['lecture'] = 1;
		} 
	?>
				<tr class="thead">
					<td class="alignCenter">De : <?php echo $r['identifiant']; ?></td>
					<td><?php echo $r['titre']; ?></td>
				</tr>
				<tr>
					<td class="alignCenter">
						<p>Etat : 
					<?php if (!$r['lecture']) { ?>
					<span class="non_lu" title="Le message n'a pas été lu par son destinataire"><i class="fa fa-envelope icon-large"></i></span>
					<?php } else { ?>
					<span class="lu" title="Le message a été lu par son destinataire"><i class="fa fa-envelope-alt icon-large"></i></span>
					<?php } ?></p>
					<p><?php echo $r['date']; ?></p>
					<?php if ($_SESSION['permission'] == 'admin') { ?>
					<a href="?page=prive&amp;show=com_con&amp;outils=supprimermsg&amp;id=<?php echo $r['id']; ?>#messages" class="btn btn-danger btn-default"><i class="fa fa-trash"></i> Supprimer</a>
					<?php } ?>
					</td>
					<td><div class="message"><?php echo $r['message']; ?></div></td>
				</tr>
	<?php } ?>
		</tbody>
	</table>
	<?php } ?>
	<div class="alignRight"><a href="?page=prive&amp;show=com_con&amp;outils=envoimsg&amp;parent=<?php echo $m['id']; ?>#messages" class="btn btn-default btn-info"><i class="fa fa-reply"></i> Répondre</a></div>
<?php 
	}
?>
<?php
	} else if (isset($_GET['outils']) AND $_GET['outils'] == "envoimsg") {
		
		if (isset($_POST['submit_newmsg'])) {
		
		// ################ ENVOYER UN NOUVEAU MESSAGE ###################
		
			if ($_POST['titre'] == '' OR $_POST['msg'] == '') {
				echo '<div class="info bad">Tous les champs sont obligatoires.</div>';
			} else {
				$titre = htmlspecialchars($_POST['titre']);
				$msg = htmlspecialchars($_POST['msg']);
				
				if (isset($_POST['parent'])) { $parent = $_POST['parent']; }
				else { $parent = ""; }
				
				$rq = 'INSERT INTO '.$prefix.'consultants_msg VALUES ("", "'.$titre.'", '.$_SESSION['id'].', '.$_POST['dest'].', "'.$msg.'", NOW(), "'.$parent.'", false)';
				if (mysqli_query($link,$rq)) {
					echo '<div class="info good" id="messages">Le message a bien été envoyé.</div>';
					$voirmsg = true;
				} else {
					echo '<div class="info bad" id="messages">Une erreur est survenue : '.mysqli_error($link).'<br />'.$rq.'</div>';
				}
			}
		}
	if (!$voirmsg OR !isset($_POST['submit_newmsg'])) {
	
	// ################### FORMULAIRE D'ENVOI D'UN NOUVEAU MESSAGE #################
	
?>
<div class="form_modif">
	<?php if (isset($_GET['parent'])) { 
			$t = mysqli_query($link,'SELECT titre FROM '.$prefix.'consultants_msg WHERE id = '.$_GET['parent']) OR die (mysqli_error($link));
			$ti = mysqli_fetch_assoc($t);
			$titre = $ti['titre'];
			echo '<h3>Répondre au message : '.$titre.'</h3>';
	} else {
		if(isset($_POST['titre']) AND $_POST['titre'] != "") $titre = $_POST['titre'];
		echo '<h3>Nouveau message</h3>';
	}
	?>
	
	<form method="post" action="#messages" id="messages">
		<p>
			<label for="titre">Titre</label>
			<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-envelope-alt"></i></span>
				
				<i class="fa fa fanput class="span4" name="titre" id="titre" type="text" placeholder="Titre du message" value="<?php if($titre) echo $titre; ?>" />
			</div>
		</p>
		<p>
			<label for="dest">Destinataire</label>
			<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-user"></i></span>
				<select class="span4" name="dest" id="dest" placeholder="Destinataire">
					<?php if ($_SESSION['permission'] == 'consultant') { ?>
					<option value="1">Administrateur</option> 
					<?php } else { ?>
					<option value="2">Consultants</option> 
					<?php } ?>
				</select>
			</div>
		</p>
		<p>
			<label for="msg">Message</label>
			<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-comment"></i></span>
				<textarea class="span4" name="msg" id="msg" rows="9"><?php if(isset($_POST['msg']) AND $_POST['msg'] != "") echo $_POST['msg']; ?></textarea>
			</div>
		</p>
		<?php if (isset($_GET['parent'])) { ?>
		<p><i class="fa fa fanput type="hidden" name="parent" value="<?php echo $_GET['parent']; ?>" /></p>
		<?php } ?>
		<p class="alignCenter"><i class="fa fa fanput type="submit" name="submit_newmsg" class="btn btn-success" value="Envoyer" /></p>
	</form>
</div>
<?php 
		}
		if (isset($_GET['parent'])) { echo '<div class="alignLeft"><a href="?page=prive&amp;show=com_con&amp;outils=voirdetailmsg&amp;id='.$_GET['parent'].'#messages" class="btn"><i class="fa fa-circle-arrow-left"></i> Retour</a></div>'; }
		else { echo '<div class="alignLeft"><a href="?page=prive&amp;show=com_con#consultants" class="btn"><i class="fa fa-circle-arrow-left"></i> Retour</a></div>'; }
		
	} else if (isset($_GET['outils']) AND $_GET['outils'] == "ajoutdocs") {
	
		if (isset($_POST['submit_ajoutdoc'])) {
		
		// ################### AJOUTER UN DOCUMENT ###################
		
			$uploaddir = './admin/documents/';
			$uploadfile = $uploaddir . basename($_FILES['doc']['name']);
			
			$titre = htmlspecialchars($_POST['titre']);
			$nom = $_FILES['doc']['name'];
			
			$req = 'INSERT INTO '.$prefix.'consultants_docs VALUES ("", "'.$titre.'", "'.$nom.'", CURDATE())';
			if (mysqli_query($link,$req)) {
			
				if (move_uploaded_file($_FILES['doc']['tmp_name'], $uploadfile)) {
					echo '<div class="info good">Le fichier a été ajouté.</div>';
				} else {
					echo '<div class="info bad">Erreur dans le déplacement du fichier</div>';
					print_r($_FILES);
				}
			} else {
				echo '<div class="info bad">Erreur SQL : '.mysqli_error($link).'<br />'.$req.'</div>';
			}
		}
?>
<div class="form_modif">
	<h3>Ajouter un document</h3>
	<form id="documents" method="post" action="#documents" enctype="multipart/form-data">
			<p>
				<label for="titre">Titre</label>
				<div class="input-group">
					<span class="input-group-addon"><i class="fa fa-file"></i></span>
					<i class="fa fa fanput class="span4" name="titre" id="titre" type="text" placeholder="Titre du document" />
				</div>
			</p>
			<p>
				<label for="doc">Document</label>
				<i class="fa fa fanput name="doc" id="doc" type="file" />
			</p>
			<p><i class="fa fa fanput type="submit" name="submit_ajoutdoc" class="btn btn-success" value="Ajouter" /></p>
	</form>
</div>
<div class="alignLeft"><a href="?page=prive&amp;show=com_con&amp;outils=voirdocs#documents" class="btn"><i class="fa fa-circle-arrow-left"></i> Retour à la liste</a></div>
<?php 
		$ajoutdocs = true;
	} else {
?>


<?php 
	}
?>