<h1>Espace privé <?php echo $_SESSION['identifiant']; ?></h1>

<nav class="navbar navbar-default">
	<div class="container-fluid">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="?page=prive">Administration</a>
		</div>

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">
				<li class="dropdown <? if ($_GET['show'] == 'gerer_res' OR !isset($_GET['show'])) echo "active"; ?>">
					<a href="?page=prive&amp;show=gerer_res" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Réservations <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="?page=prive">Liste</a></li>
						<li><a href="?page=formulaire_res">Ajouter une réservation manuelle</a></li>
					</ul>
				</li>
				<li class="dropdown <? if ($_GET['show'] == 'gerer_cli') echo "active"; ?>">
					<a href="?page=prive&amp;show=gerer_cli" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Clients <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="?page=prive&amp;show=gerer_cli">Liste</a></li>
						<li><a href="?page=prive&show=gerer_cli&ajouter=true">Ajouter un client</a></li>
					</ul>
				</li>
			</ul>
		</div><!-- /.navbar-collapse -->
	</div><!-- /.container-fluid -->
</nav>



<!-- Tab panes -->
<div class="content">
		<?php

		if ($_SESSION['permission'] == 'admin') {

			$afficher = 4;
			require "./calendrier.php";

			if ($_GET['show'] == 'gerer_res' OR !isset($_GET['show'])) {
				include "a_gerer_res.php";
			}
			else if ($_GET['show'] == 'gerer_cli') {
				include "a_gerer_cli.php";
			} else {
				include "a_gerer_res.php";
			}

		}

		?>
</div>


<!-- DISPLAY NONE -->


<div class="info light tools" style="display: none;">
	<p>Que voulez-vous faire ?</p>
	<ul class="a_menu">
		<li <?php if ($_GET['show'] == 'gerer_res') echo 'class="selected"'; ?>><a href="?page=prive&amp;show=gerer_res">Gérer les réservations</a></li>
		<li <?php if ($_GET['show'] == 'gerer_cli') echo 'class="selected"'; ?>><a href="?page=prive&amp;show=gerer_cli">Gérer les clients</a></li>
		<li <?php if ($_GET['show'] == 'com_con') echo 'class="selected"'; ?>><a href="?page=prive&amp;show=com_con#consultants">Consultants</a></li>
	</ul>
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