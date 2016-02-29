<?php
	$erreur = "";
	if (isset($_POST['submit_connexion'])) {
		if($_POST['identifiant'] != "" AND $_POST['password'] != "") {
			
			if($_POST['identifiant'] == 'admin' OR $_POST['identifiant'] == 'consultant') {
				$rq = 'SELECT id, identifiant, password, role FROM '.$prefix.'user WHERE identifiant = "'.$_POST['identifiant'].'"';
				$permission = NULL;
			} else {
				$rq = 'SELECT * FROM '.$prefix.'client WHERE email = "'.$_POST['identifiant'].'"';
				$permission = 'client';
			}
			
			$rs = mysqli_query($link,$rq) OR die('Erreur : '.mysqli_error($link));
			
			$nb = mysqli_num_rows($rs);
			if ($nb == 0) { $erreur .= "L'identifiant <span class='italic'>".$_POST['identifiant']."</span> est inconnu."; }
			else {
				while($user = mysqli_fetch_array($rs)) {
					if ($user['password'] != md5(sha1($_POST['password']))) { $erreur .= "Le mot de passe est incorrect."; }
					else {
						session_start();
						$_SESSION['permission'] = $permission;
						if ($permission == 'client') {
							$_SESSION['identifiant'] = $user['prenom'].' '.$user['nom'];
							$_SESSION['id'] = $user['id'];
						} else {
							$_SESSION['identifiant'] = $user['identifiant'];
							$permission = $user['role'];
							$_SESSION['permission'] = $permission;
							$_SESSION['id'] = $user['id'];
						}
						echo $info_head = '<div class="info good">Bienvenue, '.$_SESSION['identifiant'].' ('.$_SESSION['permission'].').</div>';
						require 'admin/a_accueil.php';
					}
				}
			}
			
		} else $erreur .= "Veuillez remplir tous les champs du formulaire.";
	}
	if(!isset($_POST['submit_connexion']) OR $erreur != ""){

?>

<h1>Connexion à l'espace privé du site</h1>

<p class="info light">Utilisez votre identifiant et votre mot de passe pour vous connecter à l'espace privé du site.<br />Pour les clients, votre identifiant est votre adresse email.<br /><br />En cas d'oubli de ces informations, <a href="mailto:pic.carine@gmail.com">contactez le webmaster</a>.</p>

<?php 
		if($erreur != "") { echo '<div class="info bad">'.$erreur.'</div>'; }
?>

<form method="post" action="" class="form-horizontal">
	<div class="form-group">
		<label for="identifiant" class="col-sm-2 control-label">Identifiant</label>
		<div class="col-sm-8">
			<input class="form-control" type="text" id="identifiant" name="identifiant" value="<?php if(isset($identifiant)) echo $identifiant; ?>" />
		</div>
	</div>
	<div class="form-group">
		<label for="password" class="control-label col-sm-2">Mot de passe</label>
		<div class="col-sm-8">
			<input class="form-control" type="password" id="password" name="password" value="<?php if(isset($password)) echo $password; ?>" />
		</div>
	</div>
	<p><input type="submit" name="submit_connexion" value="Connexion" /></p>
</form>

<?php } ?>