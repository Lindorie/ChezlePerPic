<?php 

function tronque($chaine, $longueur = 120) {
 
	if (empty ($chaine)) {
		return "";
	} elseif (strlen ($chaine) < $longueur)	{
		return $chaine;
	} elseif (preg_match ("/(.{1,$longueur})\s./ms", $chaine, $match)) {
		return $match [1] . "...";
	} else {
		return substr ($chaine, 0, $longueur) . "...";
	}
}

function envoyer_email($destinataire, $objet, $message) {
	$headers =  'From: "Chez le Pèr Pic"<contact@chezleperpic.fr>'."\n";
	$headers .= 'Reply-To: contact@chezleperpic.fr'."\n";
	$headers .= 'Content-Type: text/html; charset="iso-8859-1"'."\n";
	$headers .= 'Content-Transfer-Encoding: 8bit'; 
	if (mail($destinataire, $objet, $message, $headers)) {
		return true;
	} else {
		return false;
	}
} 

function liste_clients($id = NULL) {
	global $link, $prefix;
	$liste = array();
	
	if ($id != NULL) {
		$req = 'SELECT * FROM '.$prefix.'client WHERE id = '.$id.' ORDER BY nom ASC, prenom ASC';
	} else {
		$req = 'SELECT * FROM '.$prefix.'client ORDER BY nom ASC, prenom ASC';
	}
	$res = mysqli_query($link,$req) OR die(mysqli_error($link));
	
	$nb = mysqli_num_rows($res);
	if ($nb == 0) { $liste['erreur'] = 'Aucun client.'; }
	else {
		while ($c = mysqli_fetch_array($res)) {
			$liste[$c['id']]['nom'] = $c['nom'];
			$liste[$c['id']]['prenom'] = $c['prenom'];
			$liste[$c['id']]['email'] = $c['email'];
			$liste[$c['id']]['tel'] = $c['tel'];
			$liste[$c['id']]['tel2'] = $c['tel2'];
			$liste[$c['id']]['adresse'] = $c['adresse'];
			$liste[$c['id']]['code_postal'] = $c['code_postal'];
			$liste[$c['id']]['ville'] = $c['ville'];
			$liste[$c['id']]['pays'] = $c['pays'];
			$liste[$c['id']]['password'] = $c['password'];
			$liste[$c['id']]['pref_mail'] = $c['pref_mail'];
			$liste[$c['id']]['pref_tel'] = $c['pref_tel'];
			$liste[$c['id']]['newsletter'] = $c['newsletter'];
		}
	}
	
	return $liste;
}

function envoi_mail ( $objet, $message, $destinataires = "pic.carine@gmail.com, mireille.pic@gmail.com") {
	if (strtoupper(substr(PHP_OS,0,3)=='WIN')) {  $eol="\r\n";	} 
	elseif (strtoupper(substr(PHP_OS,0,3)=='MAC')) { $eol="\r"; } 
	else { $eol="\n"; }
	
	# To Email Address
	$emailaddress = $destinataires;
	# Message Subject
	$emailsubject="[Chez le Per'Pic] ".$objet;
	# Message Body
	$body = $message;
	
	# Common Headers
	$headers = "";
	$headers .= 'From: Chez le Per Pic <contact@chezleperpic.fr>'.$eol;
	$headers .= 'Reply-To: Chez le Per Pic <contact@chezleperpic.fr>'.$eol;
	$headers .= 'Return-Path: Chez le Per Pic <contact@chezleperpic.fr>'.$eol;     // these two to set reply address
	$headers .= "Message-ID:<TheSystem@".$_SERVER['SERVER_NAME'].">".$eol;
	$headers .= "X-Mailer: PHP v".phpversion().$eol;           // These two to help avoid spam-filters
	$msg = "";

	# HTML Version
	$headers .= "Content-Type: text/html; charset=utf-8".$eol;
	$headers .= "Content-Transfer-Encoding: 8bit".$eol;
	$msg .= $body.$eol.$eol;

	# SEND THE EMAIL
	ini_set(sendmail_from,'contact@chezleperpic.fr');  // the INI lines are to force the From Address to be used !
	  if ($_SERVER['SERVER_NAME'] != 'localhost') { mail($emailaddress, $emailsubject, $msg, $headers); }
	ini_restore(sendmail_from); 
}

function newChaine( $char = "") {
	if( $char == "" ) $char = 8;
	$list = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ123456789";
	mt_srand((double)microtime()*1000000);
	$newstring="";
	while( strlen( $newstring )< $char ) {
		$newstring .= $list[mt_rand(0, strlen($list)-1)];
	}
	return $newstring;
}

function unzip_file($file, $destination_temp, $galerie) {
	$erreur = "";
	
	if ($file['size'] != 0) {
	
		$extensions_valides = array('zip');
		//print_r($extensions_valides);
		
		// gestion des erreurs
		if ($file['error'] > 0) $erreur .= "Erreur lors du transfert du fichier.<br />";
		$extension_upload = strtolower(  substr(  strrchr($file['name'], '.')  ,1)  );
		if ( !in_array($extension_upload,$extensions_valides) ) $erreur .= "Extension incorrecte : ".$extension_upload.".<br />";
		
	} else $erreur .= "taille du fichier ?<br />";
	
	$zip = new ZipArchive() ;
	
	if ($zip->open($file['tmp_name']) !== true) {
		$erreur .= "Impossible d'ouvrir l'archive";
	}
	
	$zip->extractTo($destination_temp);
	
	$zip->close();
	
	$dirname = 'photos/temp/';
	$dir = opendir($dirname); 

	$succes = '<div class="info light good"><ul>';
	$total = 0;
	while($fichier = readdir($dir)) {
		if($fichier != '.' && $fichier != '..' && !is_dir($dirname.$fichier) && $fichier != 'Thumbs.db')
		{
			// récupérer l'extension
			$extension_photo = strtolower(  substr(  strrchr($fichier, '.')  ,1)  );
			$ajout = ajouter_photo($fichier,$extension_photo);
			if(is_array($ajout)) { $erreur .= $ajout['erreur']; }
			else {
				// on ajoute la photo en base
				
				$nom = $ajout;
				$req = 'INSERT INTO '.$prefix.'photos VALUES ("", "", "'.$nom.'", "'.$galerie.'", 0)';
				if(mysqli_query($link,$req)) { $succes .= '<li>La photo '.$fichier.' a bien été ajoutée.</li>'; }
				else $erreur .= mysqli_error($link);
			}
		$total++;
		}
	}
	$succes .= '</ul><p>'.$total.' photos ajoutées.</p></div>';

	closedir($dir);
	
	if ($erreur != "") {
		return $err = '<div class="info bad">'.$erreur.'</div>';
	}
	else return $succes;
}
 

function supprimer ($table, $id) {
	$erreur = "";
	$req = 'DELETE FROM '.$prefix.''.$table.' WHERE id = '.$id;
	if(mysqli_query($link,$req)) {
		$succes = '<div class="info good">La suppression a bien été effectuée.</div>';
	} else $erreur .= mysqli_error($link);
	if ($erreur != "") {
			return $err = '<div class="info bad">'.$erreur.'<br />'.$req.'</div>';
		}
	else return $succes;
}

function suppr_doc($id,$nom) {
	$req = 'DELETE FROM '.$prefix.'consultants_docs WHERE id = '.$id;
	if(mysqli_query($link,$req)) {
		unlink("admin/documents/".$nom);
		$succes = '<div class="info good">Le document a bien été supprimé.</div>';
	} else $erreur .= mysqli_error($link);
	if ($erreur != "") {
			return $err = '<div class="info bad">'.$erreur.'<br />'.$req.'</div>';
		}
	else return $succes;
}

function affich_photo($id) {
	$req = 'SELECT id, titre, nom FROM '.$prefix.'photos WHERE id = '.$id.';';
	$res = mysqli_query($link,$req) OR die(mysqli_error($link));
	
	$p = mysqli_fetch_array($res);
	$re = '<div class="info photo"><img src="photos/mini/'.$p['nom'].'" alt="'.$p['titre'].'" /><p>'.$p['titre'].'</p></div>';
	return $re;
}

function suppr_photo($id,$nom) {
	$req = 'DELETE FROM '.$prefix.'photos WHERE id = '.$id;
	if(mysqli_query($link,$req)) {
		unlink("photos/".$nom);
		unlink("photos/mini/".$nom);
		$succes = '<div class="info good">La photo a bien été supprimée.</div>';
	} 
	if ($erreur != "") {
			$err = array('erreur' => $erreur);
			return $err; 
		}
	else return $succes;
}

function ajouter_photo($photo, $ext) {
	// traitement de l'image
		$nom = "photo_".newChaine();
		$nom_dossier = "photos/".$nom.".".$ext."";
		$ancien_dossier = 'photos/temp/'.$photo;
		$resultat = rename($ancien_dossier,$nom_dossier);
		if ($resultat == false) { echo $ancien_dossier; } 
		
		// création de l'image miniature
		
		// Définition de la largeur et de la hauteur maximale
		$width = 800;
		$height = 600;			
		$width2 = 90;
		$height2 = 90;

		// Content type
		//header('Content-type: image/jpeg');

		// Cacul des nouvelles dimensions
		list($width_orig, $height_orig) = getimagesize($nom_dossier);

		$ratio_orig = $width_orig/$height_orig;

		if ($width/$height > $ratio_orig) {
		   $width = $height*$ratio_orig;
		} else {
		   $height = $width/$ratio_orig;
		}
		if ($width2/$height2 > $ratio_orig) {
		   //$width2 = $height2*$ratio_orig;
		   $crop_v = $height2 - (($height2-$width2)/2);
		   $crop_h = 0;
		} else {
		   //$height2 = $width2/$ratio_orig;
		   $crop_h = $width2 - (($width2-$height2)/2);
		   $crop_v = 0;
		}

		// Redimensionnement
		$image_p = imagecreatetruecolor($width, $height);
		$image_p2 = imagecreatetruecolor($width2, $height2);
		$image = imagecreatefromjpeg($nom_dossier);
		$image2 = imagecreatefromjpeg($nom_dossier);
		imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
		imagecopyresampled($image_p2, $image2, 0, 0, $crop_h, $crop_v, $width2, $height2, $width_orig-2*$crop_h, $height_orig-2*$crop_v);

		// Affichage
		imagejpeg($image_p, "photos/".$nom.".".$ext."");
		imagejpeg($image_p2, "photos/mini/".$nom.".".$ext."");
		
	
		if ($erreur != "") {
			$err = array('erreur' => $erreur);
			return $err; 
		}
		else return $nom_image = $nom.".".$ext;
}

function ajout_photo($file) {

	// traitement des erreurs de l'image
		$erreur = "";
	if ($file['size'] != 0) {
	
		$maxsize = 1048576;
		$extensions_valides = array('jpg','gif','png','jpeg','JPG');
		//print_r($extensions_valides);
		
		// gestion des erreurs
		if ($file['error'] > 0) $erreur .= "Erreur lors du transfert du fichier.<br />";
		if ($file['size'] > $maxsize) $erreur .= "Le fichier est trop gros.<br />";
		$extension_upload = strtolower(  substr(  strrchr($file['name'], '.')  ,1)  );
		if ( !in_array($extension_upload,$extensions_valides) ) $erreur .= "Extension incorrecte : ".$extension_upload.".<br />";
		
	} else $erreur .= "taille du fichier ?";
	
	if ($file['size'] != 0) {
		// traitement de l'image
		$nom = "photo_".newChaine();
		$nom_dossier = "photos/".$nom.".".$extension_upload."";
		$resultat = move_uploaded_file($file['tmp_name'],$nom_dossier);
		
		
		// création de l'image miniature
		
		// Définition de la largeur et de la hauteur maximale
		$width = 800;
		$height = 600;			
		$width2 = 90;
		$height2 = 90;

		// Content type
		//header('Content-type: image/jpeg');

		// Cacul des nouvelles dimensions
		list($width_orig, $height_orig) = getimagesize($nom_dossier);

		$ratio_orig = $width_orig/$height_orig;

		if ($width/$height > $ratio_orig) {
		   $width = $height*$ratio_orig;
		} else {
		   $height = $width/$ratio_orig;
		}
		if ($width2/$height2 > $ratio_orig) {
		   //$width2 = $height2*$ratio_orig;
		   $crop_v = $height2 - (($height2-$width2)/2);
		   $crop_h = 0;
		} else {
		   //$height2 = $width2/$ratio_orig;
		   $crop_h = $width2 - (($width2-$height2)/2);
		   $crop_v = 0;
		}

		// Redimensionnement
		$image_p = imagecreatetruecolor($width, $height);
		$image_p2 = imagecreatetruecolor($width2, $height2);
		$image = imagecreatefromjpeg($nom_dossier);
		$image2 = imagecreatefromjpeg($nom_dossier);
		imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
		imagecopyresampled($image_p2, $image2, 0, 0, $crop_h, $crop_v, $width2, $height2, $width_orig-2*$crop_h, $height_orig-2*$crop_v);

		// Affichage
		imagejpeg($image_p, "photos/".$nom.".".$extension_upload."");
		imagejpeg($image_p2, "photos/mini/".$nom.".".$extension_upload."");
		

	} 
	
		if ($erreur != "") {
			$err = array('erreur' => $erreur);
			return $err; 
		}
		else return $nom_image = $nom.".".$extension_upload;
}


?>