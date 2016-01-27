<?php

class Date{
	
	var $days = array("Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi","Dimanche");
	var $months = array("Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre"); 
	
	function getEvents($yearD,$yearF,$link,$prefix) {
		$req = 'SELECT id, date_a,date_d,etat FROM '.$prefix.'reservation WHERE YEAR(date_a) BETWEEN '.$yearD.' AND '.$yearF;
		$res = mysqli_query($link,$req);
		$r = array();
		
		while($d = mysqli_fetch_object($res)) {
			$d1 = new DateTime($d->date_a);
			$d2 = new DateTime($d->date_d);
			$r[strtotime($d->date_a)][$d->id] = $d->etat;
			$r[strtotime($d->date_d)][$d->id] = $d->etat;
			$diff = $d1->diff($d2);
			$nb_jours = $diff->days; 
			if ($nb_jours > 1) {
				$jour = array();
				for($i=1;$i<$nb_jours;$i++) {
					$d1->add(new DateInterval('P1D'));
					$jour[$i] = $d1->format('Y-m-d');
					$r[strtotime($jour[$i])][$d->id] = $d->etat;
				}
			}
		}
		return $r;
	}	
	
	function getAll($year) {
		$r = array();
		$date = strtotime($year.'-01-01');
		while (date('Y',$date) <= $year) {
			$y = date('Y', $date);
			$m = date('n', $date);
			$d = date('j', $date);
			$w = str_replace('0','7',date('w', $date));
			$r[$y][$m][$d] = $w;
			$date = strtotime(date('Y-m-d',$date).' +1 DAY');
		}
		return $r;
	}

}

?>