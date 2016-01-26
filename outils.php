<?php
	if($_REQUEST['outils']) {
		switch($_REQUEST['outils']) {
			case "modifier":
			// MODIFIER
				if(isset($_POST['submit'])) {
					$titre = htmlspecialchars($_POST['titre']);
					$texte = $_POST['texte'];
					/*$texte = tidy_repair_string(
                         $_POST['texte'],
                         array(
                                   'show-body-only' => true,
								   'clean' => true,
                                   'doctype' => '-//W3C//DTD XHTML 1.1//EN',
                                   'output-xhtml' => true
                         ),
						 'utf8'
					);	*/
					$texte = htmlspecialchars($texte, ENT_QUOTES);
					$id = $_POST['id'];
					if (isset($_POST['type'])) { $type = $_POST['type']; }
					else { $type = $_REQUEST['page']; }


					$accroche = htmlspecialchars($_POST['accroche']);
					$auteur = htmlspecialchars($_POST['auteur']);
					$mois_loc = htmlspecialchars($_POST['mois_loc']);

					
					if ($type == 'activites') {
						// Traitement des catégories
						$rq = 'SELECT * FROM categorie';
						$rs = mysql_query($rq) OR die('Erreur cat : '.mysql_error());
						$nb = mysql_num_rows($rs);
						
						$cat = '';
						for ($i=1;$i<=$nb;$i++) {
							if ($_POST['cat'.$i] != '') {
								$cat .= $_POST['cat'.$i].',';
							}
						}
						$cat = rtrim($cat, ',');
					
					
						// traitement des erreurs de l'image
						if ($_FILES['image']['size'] != 0) {
						
							$maxsize = 1048576;
							$extensions_valides = array('jpg,gif,png,jpeg,JPG');
							
							// gestion des erreurs
							if ($_FILES['image']['error'] > 0) $erreur .= "Erreur lors du transfert du fichier.<br />";
							if ($_FILES['image']['size'] > $maxsize) $erreur .= "Le fichier est trop gros.<br />";
							$extension_upload = strtolower(  substr(  strrchr($_FILES['image']['name'], '.')  ,1)  );
							if ( !in_array($extension_upload,$extensions_valides) ) $erreur .= "Extension incorrecte.<br />";
							
							$nom = "activite_".newChaine();
							$nom_dossier = "images/activites/".$nom.".".$extension_upload."";
							$resultat = move_uploaded_file($_FILES['image']['tmp_name'],$nom_dossier);
							
							
							// création de l'image miniature
							
							// Définition de la largeur et de la hauteur maximale
							$width = 230;
							$height = 230;			
							$width2 = 150;
							$height2 = 150;

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
							   $width2 = $height2*$ratio_orig;
							} else {
							   $height2 = $width2/$ratio_orig;
							}

							// Redimensionnement
							$image_p = imagecreatetruecolor($width, $height);
							$image_p2 = imagecreatetruecolor($width2, $height2);
							$image = imagecreatefromjpeg($nom_dossier);
							$image2 = imagecreatefromjpeg($nom_dossier);
							imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
							imagecopyresampled($image_p2, $image2, 0, 0, 0, 0, $width2, $height2, $width_orig, $height_orig);

							// Affichage
							imagejpeg($image_p, "images/activites/illu/".$nom.".".$extension_upload."");
							imagejpeg($image_p2, "images/activites/accueil/".$nom.".".$extension_upload."");
							
							if (isset($_POST['img_act'])) {
								unlink("images/activites/illu/".$_POST['img_act']);
								unlink("images/activites/accueil/".$_POST['img_act']);
							}
							
							$nom_image = $nom.".".$extension_upload;
							
							$rq = 'UPDATE activites SET titre="'.$titre.'", accroche="'.$accroche.'", texte="'.$texte.'", categorie="'.$cat.'", photo="'.$nom_image.'" WHERE id = "'.$id.'"';
						} else {
							if ($_POST['sup_img'] == 'sup_img') {
								unlink("images/activites/illu/".$_POST['img_act']);
								unlink("images/activites/accueil/".$_POST['img_act']);
								$photo = "";
							} else $photo = $_POST['img_act'];
						
							$rq = 'UPDATE activites SET titre="'.$titre.'", accroche="'.$accroche.'", texte="'.$texte.'", categorie="'.$cat.'", photo="'.$photo.'" WHERE id = '.$id.'';
						}
						
						
					} else if ($type == 'news') { 
						$rq = 'UPDATE news SET titre="'.$titre.'", accroche="'.$accroche.'", texte="'.$texte.'" WHERE id = '.$id.'';
					} else if ($type == 'livredor') { 
						$rq = 'UPDATE livre_dor SET auteur="'.$auteur.'", mois_loc="'.$mois_loc.'", texte="'.$texte.'" WHERE id = '.$id.'';
					} else {
						$rq = 'UPDATE content SET titre="'.$titre.'", texte="'.$texte.'" WHERE id = '.$id.'';
					}
					
					$rs = mysql_query($rq);
					if($rs) { echo '<div class="info good">La modification a été prise en compte.</div>'; }
					else { echo '<div class="info bad">Une erreur est survenue lors de la modification. Veuillez réessayer ou contacter le webmaster du site.<br /><p>'.mysql_error().'</p><br /><p><code>'.$rq.'</code></p></div>'; }
				} else {
					// MODIFIER ACTIVITES
					if($_REQUEST['page'] == 'activites') {
						$rq = 'SELECT * FROM activites WHERE id = '.$_REQUEST['id'].''; 
						$rs = mysql_query($rq) OR die('Erreur : '.mysql_error().'<br />'.$rq);
						
						while ($activites = mysql_fetch_array($rs)) {
						
							// Traitement des catégories
							// Catégories déjà cochées
							$cat = $activites['categorie'];
							$nb_cat = count($cat);
							if ($nb_cat > 0) {
								$rq2 = 'SELECT id, libelle FROM categorie WHERE id IN ('.$cat.');';
								$rs2 = mysql_query($rq2) OR die('Erreur 2 : '.mysql_error().'<br />'.$rq2);
							// liste autres catégories
							$rq3 = 'SELECT id, libelle FROM categorie WHERE id NOT IN ('.$cat.');';
							$rs3 = mysql_query($rq3) OR die('Erreur 3 : '.mysql_error());
							} else {
								
								$rq3 = 'SELECT id, libelle FROM categorie ';
								$rs3 = mysql_query($rq3) OR die('Erreur 2b : '.mysql_error().'<br />'.$rq3);
							}
							echo '<div class="form_modif">';
							echo '<h1>Modifier : <span>'.$activites['titre'].'</span></h1>';
							
							
							echo '<form method="post" action="" enctype="multipart/form-data" >';
								echo '<p><label for="titre">Titre</label><input type="text" id="titre" name="titre" value="'.$activites['titre'].'" /></p>';
								echo '<p><label for="cat">Catégories</label></p>';
								echo '<p class="checkbox">';
									while ($cat = mysql_fetch_array($rs2)) {
										echo '<input type="checkbox" name="cat'.$cat[0].'" id="cat'.$cat[0].'" value="'.$cat[0].'" checked="checked" /><label for="cat'.$cat[0].'">'.$cat[1].'</label>';
									}
								echo '</p>';								
								echo '<p class="checkbox">';
									while ($cat2 = mysql_fetch_array($rs3)) {
										echo '<input type="checkbox" name="cat'.$cat2[0].'" id="cat'.$cat2[0].'" value="'.$cat2[0].'" /><label for="cat'.$cat2[0].'">'.$cat2[1].'</label>';
									}
								echo '</p>';
								if ($activites['photo'] != "") {
									echo '<p class="image"><img src="images/activites/illu/'.$activites['photo'].'" alt="'.$activites['titre'].'" />';
									echo '<input type="checkbox" name="sup_img" id="sup_img" value="sup_img" />&nbsp;<label for="sup_img">Supprimer l\'image</label></p>';
								}
								echo '<p><label for="image">Image</label><input type="file" name="image" id="image" /></p>';
								echo '<p><label for="accroche">Accroche</label>';
								echo '<textarea name="accroche" id="accroche" rows="6" cols="100">'.$activites['accroche'].'</textarea></p>';
								echo '<p><label for="nicedit">Texte</label>';
								echo '<textarea name="texte" id="nicedit" rows="10" cols="100">'.$activites['texte'].'</textarea></p>';
								if ($activites['photo'] != "") {
									echo '<p><input type="hidden" id="img_act" name="img_act" value="'.$activites['photo'].'" /></p>';
								}
								echo '<p><input type="hidden" id="id" name="id" value="'.$_REQUEST['id'].'" /></p>';
								echo '<p><input type="hidden" id="type" name="type" value="activites" /></p>';
								echo '<p><input type="submit" name="submit" value="Modifier" /></p>';
							echo '</form>';
							echo '</div>';
						}
						
					
					} else { 
					
						// MODIFIER NEWS
						if ($_REQUEST['type'] == 'news' OR $_REQUEST['page'] == 'news') { 
							$rq = 'SELECT * FROM news WHERE id = '.$_REQUEST['id'].''; 
							$rs = mysql_query($rq) OR die('Erreur : '.mysql_error());
						} else if ($_REQUEST['type'] == 'livredor' OR $_REQUEST['page'] == 'livredor') {
						// MODIFIER TELMOIGNAGES
							$rq = 'SELECT * FROM livre_dor WHERE id = '.$_REQUEST['id'].''; 
							$rs = mysql_query($rq) OR die('Erreur : '.mysql_error());
						} else {
						// MODIFIER AUTRES
							$rq = 'SELECT * FROM content WHERE id = '.$_REQUEST['id'].''; 
							$rs = mysql_query($rq) OR die('Erreur : '.mysql_error());
						}
						
						while ($content = mysql_fetch_array($rs)) {
							echo '<div class="form_modif">';
							echo '<h1>Modifier : <span>'.htmlspecialchars_decode($content['titre']).'</span></h1>';
								echo '<form method="post" action="">';
									if ($_REQUEST['type'] == 'livredor' OR $_REQUEST['page'] == 'livredor') {
										echo '<p><label for="auteur">Auteur</label><input type="text" id="auteur" name="auteur" value="'.htmlspecialchars_decode($content['auteur']).'" /></p>';
									} else {
										echo '<p><label for="titre">Titre</label><input type="text" id="titre" name="titre" value="'.htmlspecialchars_decode($content['titre']).'" /></p>';
									}
									if ($_REQUEST['type'] == 'news' OR $_REQUEST['page'] == 'news') { 
										echo '<p><label for="accroche">Accroche</label>';
										echo '<textarea name="accroche" id="accroche" rows="6" cols="100">'.$content['accroche'].'</textarea></p>';
									} elseif ($_REQUEST['type'] == 'livredor' OR $_REQUEST['page'] == 'livredor') { 
										echo '<p><label for="mois_loc">Mois</label><input type="text" id="mois_loc" name="mois_loc" value="'.htmlspecialchars_decode($content['mois_loc']).'" /></p>';
									}
									echo '<p><label for="nicedit">Texte</label>';
									echo '<textarea name="texte" id="nicedit" rows="8" cols="100">'.htmlspecialchars_decode($content['texte']).'</textarea></p>';
									echo '<p><input type="hidden" id="id" name="id" value="'.$_REQUEST['id'].'" /></p>';
									if ($_REQUEST['type'] == 'news' OR $_REQUEST['page'] == 'news') { echo '<p><input type="hidden" id="type" name="type" value="news" /></p>'; }
									elseif ($_REQUEST['type'] == 'livredor' OR $_REQUEST['page'] == 'livredor') { echo '<p><input type="hidden" id="type" name="type" value="livredor" /></p>'; }
									echo '<p><input type="submit" name="submit" value="Modifier" /></p>';
								echo '</form>';
							echo '</div>';
						}
					}
				}
			break;
			
			case "ajouter":
				if(isset($_POST['submit'])) {
					$titre = htmlspecialchars($_POST['titre']);
					$texte = tidy_repair_string(
                         $_POST['texte'],
                         array(
                                   'show-body-only' => true,
								   // 'clean' => true,
                                   'doctype' => '-//W3C//DTD XHTML 1.1//EN',
                                   'output-xhtml' => true
                         ),
						 'utf8'
					);	
					$texte = htmlspecialchars($_POST['texte']);
					$accroche = htmlspecialchars($_POST['accroche']);
					$auteur = htmlspecialchars($_POST['auteur']);
					$mois_loc = htmlspecialchars($_POST['mois_loc']);

					$id = $_POST['id'];
					$type = $_REQUEST['type'];
					
					// Traitement des catégories
					$rq = 'SELECT * FROM categorie';
					$rs = mysql_query($rq) OR die('Erreur cat : '.mysql_error());
					$nb = mysql_num_rows($rs);
					
					$cat = '';
					for ($i=1;$i<=$nb;$i++) {
						if ($_POST['cat'.$i] != '') {
							$cat .= $_POST['cat'.$i].',';
						}
					}
					$cat = rtrim($cat, ', ');
					
					// AJOUTER ACTIVITE
					if ($type == 'activites') {
						// traitement des erreurs de l'image
						if ($_FILES['image']['size'] != 0) {
						
							$maxsize = 1048576;
							$extensions_valides = array('jpg,gif,png,jpeg,JPG');
							
							// gestion des erreurs
							if ($_FILES['image']['error'] > 0) $erreur .= "Erreur lors du transfert du fichier.<br />";
							if ($_FILES['image']['size'] > $maxsize) $erreur .= "Le fichier est trop gros.<br />";
							$extension_upload = strtolower(  substr(  strrchr($_FILES['image']['name'], '.')  ,1)  );
							if ( !in_array($extension_upload,$extensions_valides) ) $erreur .= "Extension incorrecte.<br />";
							
						}
						
						if ($_FILES['image']['size'] != 0) {
							// traitement de l'image
							$nom = "activite_".newChaine();
							$nom_dossier = "images/activites/".$nom.".".$extension_upload."";
							$resultat = move_uploaded_file($_FILES['image']['tmp_name'],$nom_dossier);
							
							
							// création de l'image miniature
							
							// Définition de la largeur et de la hauteur maximale
							$width = 230;
							$height = 230;			
							$width2 = 150;
							$height2 = 150;

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
							   $width2 = $height2*$ratio_orig;
							} else {
							   $height2 = $width2/$ratio_orig;
							}

							// Redimensionnement
							$image_p = imagecreatetruecolor($width, $height);
							$image_p2 = imagecreatetruecolor($width2, $height2);
							$image = imagecreatefromjpeg($nom_dossier);
							$image2 = imagecreatefromjpeg($nom_dossier);
							imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
							imagecopyresampled($image_p2, $image2, 0, 0, 0, 0, $width2, $height2, $width_orig, $height_orig);

							// Affichage
							imagejpeg($image_p, "images/activites/illu/".$nom.".".$extension_upload."");
							imagejpeg($image_p2, "images/activites/accueil/".$nom.".".$extension_upload."");
							
							$nom_image = $nom.".".$extension_upload;
							
							$rq = 'INSERT INTO activites(titre,accroche,texte,categorie,photo) VALUES( "'.$titre.'", "'.$accroche.'", "'.$texte.'", "'.$cat.'", "'.$nom_image.'")';
						} else {
							$rq = 'INSERT INTO activites(titre,accroche,texte,categorie) VALUES( "'.$titre.'", "'.$accroche.'", "'.$texte.'", "'.$cat.'")';
						}
					} 
					else if ($type == 'news') {
						$rq = 'INSERT INTO news(titre,accroche,texte,date) VALUES( "'.$titre.'", "'.$accroche.'", "'.$texte.'", CURDATE())';
					} else if ($type == 'livredor') {
						$rq = 'INSERT INTO livre_dor(auteur,mois_loc,texte) VALUES( "'.$auteur.'", "'.$mois_loc.'", "'.$texte.'")';
					} else {
						$page = $_REQUEST['page'];
						$rq = 'INSERT INTO content(titre,texte,page) VALUES ("'.$titre.'", "'.$texte.'", "'.$page.'")';
					}
					
					$rs = mysql_query($rq) OR die('Erreur : '.mysql_error());
					if($rs) { echo '<div class="info good">Le contenu a été ajouté.</div>'; }
					else { echo '<div class="info bad">Une erreur est survenue lors de la création du contenu. Veuillez réessayer ou contacter le webmaster du site.</div>'; }
				} else {
					// AJOUTER ACTIVITE
					if($_REQUEST['page'] == 'activites') { 
						
							// Traitement des catégories
							$rq3 = 'SELECT id, libelle FROM categorie';
							$rs3 = mysql_query($rq3) OR die('Erreur 3 : '.mysql_error());
							
							echo '<div class="form_modif">';
							echo '<h1>Ajouter : <span>Activité</span></h1>';
							
							echo '<form method="post" action="" enctype="multipart/form-data" >';
								echo '<p><label for="titre">Titre</label><input type="text" id="titre" name="titre" value="'.$_POST['titre'].'" /></p>';
								echo '<p><label for="cat">Catégories</label></p>';							
								echo '<p class="checkbox">';
									while ($cat2 = mysql_fetch_array($rs3)) {
										echo '<input type="checkbox" name="cat'.$cat2[0].'" id="cat'.$cat2[0].'" value="'.$cat2[0].'" /><label for="cat'.$cat2[0].'">'.$cat2[1].'</label>';
									}
								echo '</p>';
								echo '<p><label for="image">Image</label><input type="file" name="image" id="image" /></p>';
								echo '<p><label for="accroche">Accroche</label></p>';
								echo '<textarea name="accroche" id="accroche" rows="6" cols="100">'.$_POST['accroche'].'</textarea>';
								echo '<p><label for="nicedit">Texte</label>';
								echo '<textarea name="texte" id="nicedit" rows="10" cols="100">'.$_POST['texte'].'</textarea></p>';
								echo '<p><input type="hidden" id="type" name="type" value="activites" /></p>';
								echo '<p><input type="submit" name="submit" value="Ajouter" /></p>';
							echo '</form>';
							echo '</div>';
					} else if ($_REQUEST['type'] == 'news') {
						// AJOUTER ACTUALITES NEWS
							echo '<div class="form_modif">';
							echo '<h1>Ajouter : <span>Actualité</span></h1>';
						echo '<form method="post" action="">';
							echo '<p><label for="titre">Titre</label><input type="text" id="titre" name="titre" value="" /></p>';
							echo '<p><label for="accroche">Accroche</label>';
							echo '<textarea name="accroche" id="accroche" rows="3" cols="55"></textarea></p>';
							echo '<p><label for="nicedit">Texte</label>';
							echo '<textarea name="texte" id="nicedit" rows="6" cols="55"></textarea></p>';
							echo '<p><input type="submit" name="submit" value="Ajouter" /></p>';
						echo '</form>';
						echo '</div>';
					} else if ($_REQUEST['type'] == 'livredor') {
						// AJOUTER TEMOIGNAGE LIVRE DOR
							echo '<div class="form_modif">';
							echo '<h1>Ajouter : <span>Témoignage</span></h1>';
						echo '<form method="post" action="">';
							echo '<p><label for="auteur">Auteur</label><input type="text" id="auteur" name="auteur" value="" /></p>';
							echo '<p><label for="mois_loc">Mois</label><input type="text" id="mois_loc" name="mois_loc" value="" /></p>';
							echo '<p><label for="nicedit">Texte</label>';
							echo '<textarea name="texte" id="nicedit" rows="6" cols="55"></textarea></p>';
							echo '<p><input type="submit" name="submit" value="Ajouter" /></p>';
						echo '</form>';
						echo '</div>';
					} else { 
						echo '<div class="form_modif">';
							echo '<h1>Ajouter : <span>Contenu</span></h1>';
						echo '<form method="post" action="">';
							echo '<p><label for="titre">Titre</label><input type="text" id="titre" name="titre" value="" /></p>';
							echo '<p><label for="accroche">Accroche</label>';
							echo '<textarea name="accroche" id="accroche" rows="3" cols="55"></textarea></p>';
							echo '<p><label for="nicedit">Texte</label>';
							echo '<textarea name="texte" id="nicedit" rows="6" cols="55"></textarea></p>';
							echo '<p><input type="submit" name="submit" value="Ajouter" /></p>';
						echo '</form>';
						echo '</div>';
					}
				}
			break;
			
			case "supprimer":
				if ($_REQUEST['confirm'] == 'oui') {
					if($_REQUEST['type'] == 'news') {
						mysql_query('DELETE FROM news WHERE id = '.$_REQUEST['id']) OR die('Erreur : '.mysql_error());
					} else if ($_REQUEST['type'] == 'activites') {
					
					} else {
						mysql_query('DELETE FROM content WHERE id = '.$_REQUEST['id']) OR die('Erreur : '.mysql_error());
					}
				} else {
				
					if($_REQUEST['type'] == 'news') {
						echo '<div class="info">Voulez-vous vraiment supprimer cette actualité ?<br /><a href="?outils=supprimer&amp;type=news&amp;id='.$_REQUEST['id'].'&amp;confirm=oui">Oui, je veux supprimer</a><br /><a href="index.php">Non, je ne veux pas supprimer</a></div>';
					} else if ($_REQUEST['type'] == 'activites') {
					
					} else {
						if (isset($_REQUEST['page'])) {
							echo '<div class="info">Voulez-vous vraiment supprimer ce contenu ?<br /><a href="?page='.$_REQUEST['page'].'&amp;outils=supprimer&amp;id='.$_REQUEST['id'].'&amp;confirm=oui">Oui, je veux supprimer</a><br />';
							echo '<a href="?page='.$_REQUEST['page'].'">Non, je ne veux pas supprimer</a></div>';
						} else {
							echo '<a href="index.php">Non, je ne veux pas supprimer</a></div>';
							echo '<div class="info">Voulez-vous vraiment supprimer ce contenu ?<br /><a href="?outils=supprimer&amp;id='.$_REQUEST['id'].'&amp;confirm=oui">Oui, je veux supprimer</a><br />';
						}
					}
				}
			break;
			
			default:
			
			break;
		}
	}	
?>