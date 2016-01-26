<h1>Espace privé <?php echo $_SESSION['identifiant']; ?></h1>

<?php
	if ($_SESSION['permission'] == 'client') {
	
	
	} else { ?>
			
			<!-- MENU -->
			<?php if ($_SESSION['permission'] == 'admin') { ?>
		
			<?php $afficher = 4; require "./calendrier.php"; ?>
			
			<div class="info light tools">
				<p>Que voulez-vous faire ?</p>
				<ul class="a_menu">
					<li <?php if ($_GET['show'] == 'gerer_res') echo 'class="selected"'; ?>><a href="?page=prive&amp;show=gerer_res#liste_reservations">Gérer les réservations</a></li>
					<li <?php if ($_GET['show'] == 'gerer_cli') echo 'class="selected"'; ?>><a href="?page=prive&amp;show=gerer_cli#liste_clients">Gérer les clients</a></li>
					<li <?php if ($_GET['show'] == 'com_con') echo 'class="selected"'; ?>><a href="?page=prive&amp;show=com_con#consultants">Consultants</a></li>
				</ul>
			</div>
			<?php }  ?>
			<div id="consultants">
				<div class="table">
					<div id="documents" class="bloc">
						<h3>Documents partagés</h3>
						<?php 
							$rq = 'SELECT COUNT(id) as nb FROM consultants_docs';
							$rs = mysql_query($rq) OR die (mysql_error().'<br />'.$rq);
							$count = mysql_fetch_assoc($rs);
						?>
						<p><?php echo $count['nb']; ?> document(s) partagés.</p>
						<ul>
							<li><a href="?page=prive&amp;show=com_con&amp;outils=voirdocs#documents" class="btn btn-info"><i class="fa fa-folder-open-o"></i> Voir les documents</a></li>
							<li><a href="?page=prive&amp;show=com_con&amp;outils=ajoutdocs#documents" class="btn btn-info"><i class="fa fa-plus"></i> Ajouter un document</a></li>
						</ul>
					</div>

					<div id="messages" class="bloc">
						<h3>Messagerie</h3>
						<?php 
							$rq = 'SELECT COUNT(id) as nb FROM consultants_msg WHERE lecture = 0 AND destinataire = '.$_SESSION['id'].'';
							$rs = mysql_query($rq) OR die (mysql_error().'<br />'.$rq);
							$count = mysql_fetch_assoc($rs);
						?>
						<p><?php echo $count['nb']; ?> message(s) non lu(s).</p>
						<ul>
							<li><a href="?page=prive&amp;show=com_con&amp;outils=voirmsg#messages" class="btn btn-info"><i class="fa fa-envelope-o"></i> Voir les messages</a></li>
							<li><a href="?page=prive&amp;show=com_con&amp;outils=envoimsg#messages" class="btn btn-info"><i class="fa fa-edit"></i> Nouveau message</a></li>
						</ul>
					</div>
				</div>
				<div id="reservations" class="bloc large">
					<h3>Réservations</h3>
					<?php 
						$rq = 'SELECT COUNT(id) as nb FROM reservation WHERE date_a >= CURDATE()';
						$rs = mysql_query($rq) OR die (mysql_error().'<br />'.$rq);
						$count = mysql_fetch_assoc($rs);
					?>
					<p><?php echo $count['nb']; ?> réservations futures.</p>
					<p class="info">Cliquez sur les dates des réservations qui vous intéressent dans le calendrier ci-dessus pour voir le détail.</p>
				</div>
			</div>
			<?php 
				if (isset($_GET['show'])) $show = $_GET['show'];
				else $show = "";
				
				
					
				
				switch($show) {
					case "gerer_res":
					include "a_gerer_res.php";
					break;
					
					case "gerer_cli":
					include "a_gerer_cli.php";
					break;
					
					case "com_con":
					include "a_com_con.php";
					break;
					
					case "gerer_site":
					include "a_gerer_site.php";
					break;
					
					case "":
					break;
				} 
			?>
	<?php
	}
?>