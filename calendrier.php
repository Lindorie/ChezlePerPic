
<?php
	require('date.php');
	$date = new Date();
	$yearActuel = date('Y');
	// Année de Départ
	$yearD = $yearActuel-1;
	// Année de Fin
	$yearF = $yearActuel+1;
	$year = date('Y');
	$month = date('n');
	$monthActuel = date('n');
	$events = $date -> getEvents($yearD,$yearF,$link,$prefix);
	$dates = $date -> getAll($year);
				$affich = 0;
	if (!isset($moisP)) { $moisP = $month; }
	if (!isset($moisN)) { $moisN = $month; }

?>
<div id="calendar">
		<?php $dates = current($dates); ?>						
		<div class="wrap">
			<div id="fleche_avant" class="fleche"><a href="#"><span><?php echo $yearActuel.'-'.$monthActuel; ?></span></a></div>
		<?php foreach($dates as $m=>$days): ?>
			<?php if($m >= $month AND $m <= $month+$afficher-1) { $class_current = ' current'; $affich++;} else { $class_current = ' invisible'; } ?>
			<div class="month<?php echo $class_current; if($m == $month) { echo ' actuel'; } ?>"  id="<?php echo $year; ?>month<?php echo $m; ?>">
				<?php
					if ($m == 1) { $moisP = 12; $annee = $year-1; }
					else $moisP = $month-$afficher;
					if ($m == 12) { $moisN = 1; $annee = $year+1; }
					else $moisN = $month+$afficher+4-1;
						if($moisN > 12) $moisN = $moisN-12;
						if($moisP < 1) $moisP = 12+$moisP;
				?>
				<div class="header">
					<?php echo $date->months[$m-1]; ?>
					<?php echo $year; ?>
				</div>
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
							<?php $time = strtotime("$year-$m-$d"); ?>
							<?php if($d == 1 AND $w != 1): ?>
								<td colspan="<?php echo $w-1; ?>" class="vide"></td>
							<?php endif; ?>
							<?php if(isset($events[$time])): 
							?>
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
							<td colspan="<?php echo 7-$end; ?>" class="vide">&nbsp;</td>
						<?php endif; ?>
						</tr>
					</tbody>
				</table>
			</div>
		<?php  endforeach; ?>
		<?php if(isset($annee)) {
			$year = $annee;
			$month = 1;
			$afficher -= $affich;
			$dates = $date -> getAll($year);
			$dates = current($dates); 
			foreach($dates as $m=>$days):
			if($m >= $month AND $m <= $month+$afficher-1) { $class_current = ' current'; } else { $class_current = ' invisible'; } ?>
			<div class="month<?php echo $class_current; if($m == $monthActuel AND $year == $yearActuel) { echo ' actuel'; } ?>"  id="<?php echo $year; ?>month<?php echo $m; ?>">
				<?php
					if ($m == 1) { $moisP = 13-$afficher; $annee = $year-1; }
					else $moisP = $month-$afficher;
					if ($m == 12) { $moisN = $afficher; $annee = $year+1; }
					else $moisN = $month+$afficher+4-1;
						if($moisN > 12) $moisN = $moisN-12;
						if($moisP < 1) $moisP = 12+$moisP;
				?>
				<div class="header">
					<?php echo $date->months[$m-1]; ?>
					<?php echo $year; ?>
				</div>
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
							<?php  $time = strtotime("$year-$m-$d"); ?>
							<?php if($d == 1 AND $w != 1): ?>
								<td colspan="<?php echo $w-1; ?>" class="vide"></td>
							<?php endif; ?>
							<?php if(isset($events[$time])): 
							?>
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
							<td colspan="<?php echo 7-$end; ?>" class="vide">&nbsp;</td>
						<?php endif; ?>
						</tr>
					</tbody>
				</table>
			</div>
		<?php endforeach;
		} else echo "test"; ?>
		
		<div id="fleche_apres" class="fleche"><a href="#"><span><?php echo $yearActuel.'-'.$monthActuel; ?></span></a></div>
<div class="clear"></div>
	</div>
</div>
<div class="clear"></div>