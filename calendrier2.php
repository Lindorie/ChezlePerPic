
<?php
	require('date.php');
	$date = new Date();
	$yearActuel = date('Y');
	// Année de Départ
	$yearD = $yearActuel;
	// Année de Fin
	$yearF = $yearActuel+1;
	$month = date('n');
	$monthActuel = date('n');
	$events = $date -> getEvents($yearD,$yearF,$link);
	$affich = 0;
	if (!isset($moisP)) {$moisP = $month; }
	if (!isset($moisN)) {$moisN = $month; }
?>
<div id="calendar" class="home">			
		<div class="wrap">
		<?php 
			for ($i = $yearD; $i <= $yearF; $i++) {
				$year = $i;
				$dates = $date -> getAll($year);
				$dates = current($dates); 
		?>	
		<?php foreach($dates as $m=>$days): ?>
			<div class="month<?php if($m >= $month AND $m <= $month+$afficher-1 AND  $year == $yearActuel) { echo ' current'; } if($m == $monthActuel AND $year == $yearActuel) { echo ' actuel'; } ?>"  id="<?php echo $year; ?>month<?php echo $m; ?>">
				<?php
					$annee = $year; $anneeP = $year; $anneeN = $year;
					if ($m == 1) { $moisP = 12; $anneeP--;  $moisN = $m+$afficher; }
					else { $moisP = $m-$afficher;  $moisN = $m+$afficher; }
				?>
				<?php if ($anneeP >= $yearD) : ?>
					<div class="prev" id="<?php echo $anneeP; ?>-<?php echo $moisP; ?>"><a href="#"><img src="images/fl_violet2.png" alt="precedent" /></a></div>
				<?php endif; ?>
				<?php
					if ($m == 12) { $moisN = 1; $anneeN++;  $moisP = $m-$afficher;}
					else { $moisN = $m+$afficher;  $moisP = $m-$afficher;}
						if($moisN > 12) $moisN = $moisN-12;
						if($moisP < 1) $moisP = 12+$moisP;
				?>
				<div class="header">
					<?php echo $date->months[$m-1]; ?>
					<?php echo $year; ?>
				</div>
				<?php if ($anneeN <= $yearF) : ?>
					<div class="next" id="<?php echo $anneeN; ?>-<?php echo $moisN; ?>"><a href="#"><img src="images/fl_violet.gif" alt="suivant" /></a></div>
				<?php endif; ?>
				<table>
					<thead>
						<tr>
							<?php foreach ($date->days as $d): ?>
								<th><?php echo substr($d,0,3);?></th>
							<?php endforeach; ?>
						</tr>
					</thead>
					<tbody>
						<tr>
						<?php $end = end($days); foreach ($days as $d=>$w): ?>
							<?php  $time = strtotime("$year-$m-$d"); 
								?>
							<?php if($d == 1 AND $w != 1): ?>
								<td colspan="<?php echo $w-1; ?>" class="vide"></td>
							<?php endif; ?>
							<?php if(isset($events[$time])): ?>
								<td class="couleur_attente">
									<?php foreach($events[$time] as $k => $e): ?>
										<?php $nb = count($events[$time]);
											if($nb == 1) { ?> 
												<div class="day id<?php echo $k; ?>">
													<?php if ($_SESSION['permission'] == 'admin') { ?>
													<a href="?page=prive&amp;show=gerer_res&amp;voir=<?php echo $k; ?>" title="Voir la réservation"><?php echo $d; ?></a>	
													<?php } else echo $d;   ?>
												</div> 
												<div class="event <?php echo $e; ?>">
													<span class="<?php echo $e; ?>"></span>
													<?php if($e == 'rose'): ?>
														Réservation en cours de confirmation
													<?php elseif ($e == 'rouge'): ?>
														Réservation confirmée
													<?php elseif ($e == 'gris'): ?>
														Indisponible
													<?php endif; ?>
												</div>	
											<?php  }
											else { ?> 
												<div class="day double">
													<?php echo $d; ?>
													<div class="infobulle">Jour de croisement</div>
													<span class="double"></span>
												</div> 
											<?php break;}
										?>
									<?php endforeach;?>
								</td><?php else : ?>
							<td>
								<div class="day"><?php echo $d; ?></div>
							</td>
							<?php endif; ?>
							<?php if ($w == 7): ?>
						</tr><tr>
							<?php endif; ?>
						<?php endforeach; ?>
						<?php if($end != 7): ?>
							<td colspan="<?php echo 7-$end; ?>" class="vide"></td>
						<?php endif; ?>
						</tr>
					</tbody>
				</table>
			</div>
		<?php endforeach; ?>
		<?php } ?>
	</div>
</div>
<div class="clear"></div>