<h1>Espace privé <?php echo $_SESSION['identifiant']; ?></h1>

<!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
	<li role="presentation" class="active"><a href="#reservations" aria-controls="reservations" role="tab" data-toggle="tab">Réservations</a></li>
	<li role="presentation"><a href="#clients" aria-controls="clients" role="tab" data-toggle="tab">Clients</a></li>
</ul>



<div class="info light tools" style="display: none;">
    <p>Que voulez-vous faire ?</p>
    <ul class="a_menu">
        <li <?php if ($_GET['show'] == 'gerer_res') echo 'class="selected"'; ?>><a href="?page=prive&amp;show=gerer_res#liste_reservations">Gérer les réservations</a></li>
        <li <?php if ($_GET['show'] == 'gerer_cli') echo 'class="selected"'; ?>><a href="?page=prive&amp;show=gerer_cli#liste_clients">Gérer les clients</a></li>
        <li <?php if ($_GET['show'] == 'com_con') echo 'class="selected"'; ?>><a href="?page=prive&amp;show=com_con#consultants">Consultants</a></li>
    </ul>
</div>


<!-- Tab panes -->
<div class="tab-content">
	<div role="tabpanel" class="tab-pane active" id="reservations">
		<?php

		if ($_SESSION['permission'] == 'admin') {

			$afficher = 4;
			require "./calendrier.php";
			include "a_gerer_res.php";

		}

		?>

	</div>
	<div role="tabpanel" class="tab-pane" id="clients">
		<?php

		if ($_SESSION['permission'] == 'admin') {

            include "a_gerer_cli.php";

		}

		?>

	</div>
</div>

			<div id="consultants" style="display:none;">
				<div class="table">
					<div id="documents" class="bloc" style="display:none;">
						<h3>Documents partagés</h3>
						<?php 
							$rq = 'SELECT COUNT(id) as nb FROM '.$prefix.'consultants_docs';
							$rs = mysqli_query($link,$rq) OR die (mysqli_error($link).'<br />'.$rq);
							$count = mysqli_fetch_assoc($rs);
						?>
						<p><?php echo $count['nb']; ?> document(s) partagés.</p>
						<ul>
							<li><a href="?page=prive&amp;show=com_con&amp;outils=voirdocs#documents" class="btn btn-info"><i class="fa fa-folder-open-o"></i> Voir les documents</a></li>
							<li><a href="?page=prive&amp;show=com_con&amp;outils=ajoutdocs#documents" class="btn btn-info"><i class="fa fa-plus"></i> Ajouter un document</a></li>
						</ul>
					</div>

					<div id="messages" class="bloc" style="display:none;">
						<h3>Messagerie</h3>
						<?php 
							$rq = 'SELECT COUNT(id) as nb FROM '.$prefix.'consultants_msg WHERE lecture = 0 AND destinataire = '.$_SESSION['id'].'';
							$rs = mysqli_query($link,$rq) OR die (mysqli_error($link).'<br />'.$rq);
							$count = mysqli_fetch_assoc($rs);
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
						$rq = 'SELECT COUNT(id) as nb FROM '.$prefix.'reservation WHERE date_a >= CURDATE()';
						$rs = mysqli_query($link,$rq) OR die (mysqli_error($link).'<br />'.$rq);
						$count = mysqli_fetch_assoc($rs);
					?>
					<p><?php echo $count['nb']; ?> réservations futures.</p>
					<p class="info">Cliquez sur les dates des réservations qui vous intéressent dans le calendrier ci-dessus pour voir le détail.</p>
				</div>
			</div>