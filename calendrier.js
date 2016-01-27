$(window).load(function(){
	$('#calendar #fleche_apres a').click(function() {
	
		var i = $(this).find("span").html();
		var date = i.split("-");
		var mois = parseInt(date[1]);
		var annee = parseInt(date[0]);
		var anneeD = annee - 1;
		var anneeP = annee + 1;

		var moisT, moisD, moisA, moisDbis;
			
			if (mois == 1) { moisT = 12; } else { moisT = mois - 1; } // Numéro du dernier mois de l'affichage "avant" pour repère nextUntil
			if (mois > 9) { moisD = mois - 12 + 3; } else { moisD = mois + 3; } // Numéro du 4ème et dernier mois affiché
			if (mois == 9) { moisA = 1; } else { moisA = moisD + 1; } // Numéro du dernier mois de l'affichage "avant" : repère pour le nextUntil
			if (mois >= 5 && mois <= 8) { moisDbis = moisA - 12 + 4; } else  { moisDbis = moisA + 4; } // Numéro du premier mois de l'affichage "après" x2 : repère pour le nextUntil

			//alert("mois = "+mois+" moisA = "+moisA+" moisD = "+moisD+" moisDbis = "+moisDbis);
		
		// Supprimer tous les mois vides
		$('#calendar .month.vide').each( function() { 
			$(this).remove();
		});
		// Rendre invisible tous les mois
		$('#calendar .month').each( function() { 
			$(this).removeClass('current').addClass('invisible').hide();
		});
		
		
		if (mois <= 4) {	
			
			// Afficher les 4 mois d'après
			$('#calendar #'+annee+'month'+moisD).nextUntil('#calendar #'+annee+'month'+moisDbis).removeClass('invisible').addClass('current').fadeIn();
			$(this).find("span").html(annee+'-'+(moisA));
			$('#calendar #fleche_avant a').find("span").html(annee+'-'+(moisA));
			
		} else if (mois >= 5 && mois < 9) {
		
			testRepere(annee,mois,moisDbis,anneeP,moisD);
			
			// Afficher les 4 mois d'après
			$('#calendar #'+annee+'month'+moisD).nextUntil('#calendar #'+anneeP+'month'+moisDbis).removeClass('invisible').addClass('current').fadeIn();
			$(this).find("span").html(annee+'-'+(moisA));
			$('#calendar #fleche_avant a').find("span").html(annee+'-'+(moisA));
		} else if (mois == 9) {
			
			testRepere(annee,mois,moisDbis,anneeP,moisD);
			
			// Afficher les 4 mois d'après
			$('#calendar #'+annee+'month'+moisD).nextUntil('#calendar #'+anneeP+'month'+moisDbis).removeClass('invisible').addClass('current').fadeIn();
			$(this).find("span").html(annee+'-'+(moisA));
			$('#calendar #fleche_avant a').find("span").html(anneeP+'-'+(moisA));
		} else if (mois > 9) {
			
			testRepere(annee,mois,moisDbis,anneeP,moisD);
			
			// Afficher les 4 mois d'après
			$('#calendar #'+anneeP+'month'+moisD).nextUntil('#calendar #'+anneeP+'month'+moisDbis).removeClass('invisible').addClass('current').fadeIn();
			$(this).find("span").html(anneeP+'-'+(moisA));
			$('#calendar #fleche_avant a').find("span").html(anneeP+'-'+(moisA));
		}
		$('#calendar #fleche_avant').removeClass('invisible').removeClass('current').show();
		return false;
	});
	
	
			
	function testRepere(annee,mois,moisDbis,anneeP,moisD) {
		// Vérifier si le repère pour le nextUntil existe, sinon, rajouter des mois vides
		var test = 99;
		var	moisDter = moisDbis;
		var anneeX = anneeP;

		// On cherche le dernier div qui existe avec une année et un mois
		do {
			if (test == 0) {
				moisDter--;
				if (moisDter < 1) { anneeX--; moisDter = 12; }
				test = $('#calendar #'+anneeX+'month'+moisDter).length;
			} else {
				test = $('#calendar #'+anneeX+'month'+moisDbis).length;
			}
		}
		while (test == 0);
		var target = $('#calendar #'+anneeX+'month'+moisDter).next();
		var length = $('#calendar #'+anneeX+'month'+moisD).nextUntil(target).length;
		//alert('#calendar #'+anneeX+'month'+moisD);
		//alert("length="+length);

		// Si le nombre d'éléments (length) entre les 2 repères est inférieur à 4 alors on lance la fonction moisvide pour rajouter des mois
		var vide;
		if (length < 4) { vide = moisvide(annee,mois,length,"apres"); }
		else vide = false;
		
		if (vide != false) {
			$('#calendar .vide').last().attr('id', anneeP+'month'+(moisDbis));
			if (vide > 1) $('#calendar  #'+anneeP+'month'+(moisDbis)).prev().attr('id', anneeP+'month'+(moisDbis-1));
		}
		
	}
	
	function moisvide(annee,mois,length,sens) {
			
				for(var i=0; i <= (4-length); i++) {
					if (sens == "apres") {
						$('#calendar #fleche_apres').before('<div class="month vide">&nbsp;</div>');

					} else if (sens == "avant") {
						$('#calendar #fleche_avant').after('<div class="month vide">&nbsp;</div>');
					}
				}								
				if (sens == "apres") { $('#calendar #fleche_apres').hide(); }
				else if (sens == "avant") { $('#calendar #fleche_avant').hide(); }
				return i;
	}
	
	$('#calendar #fleche_avant a').click(function() {
		var i = $(this).find("span").html();
		var date = i.split("-");
		var mois = parseInt(date[1]);
		var annee = parseInt(date[0]);
		var anneeD = annee - 1;
		var anneeP = annee + 1;

		var moisT, moisD, moisA, moisDbis;
		
		if (mois == 1) { moisT = 12; } else { moisT = mois - 1; } // Numéro du dernier mois de l'affichage "avant" pour repère nextUntil
		if (mois <= 4) { moisD = mois - 4 + 12; } else { moisD = mois - 4; } // Numéro du 4ème et dernier mois affiché
		if (mois <= 9) { moisA = mois + 4 - 12; } else { moisA = mois + 4; } // Numéro du premier mois de l'affichage "après" pour repère nextUntil
		if (mois == 5) { moisDbis = 12; } else  { moisDbis = moisD - 1; } // Numéro du dernier mois de l'affichage "avant"x2 pour repère prevUntil
			
		// Rendre invisible tous les mois
		$('#calendar .month').each( function() { 
			$(this).removeClass('current').addClass('invisible').hide();
		});
		
		// Supprimer tous les mois vides
		$('#calendar .month.vide').each( function() { 
			$(this).remove();
		});
		 if (mois >= 1 && mois <= 5) {
			// Afficher les 4 mois d'avant
			var ladate = new Date();
			var anneeActuelle = ladate.getFullYear();
			if (annee == anneeActuelle) {

				var length = $('#calendar #'+annee+'month'+mois).prevUntil('#calendar #'+anneeD+'month'+moisDbis).length - 1;
				
				if (length < 4) {
					for(var i=0; i <= length; i++) {
						$('#calendar #fleche_avant').after('<div class="month vide">&nbsp;</div>');
					}
					$('#calendar .vide').first().attr('id', anneeD+'month'+moisDbis);
					$('#calendar  #'+anneeD+'month'+moisDbis).next().attr('id', anneeD+'month'+moisD);
					$('#calendar #fleche_avant').hide();
				}
			}

			console.log(anneeD + ' ' + moisD); //2015 12

			var actuel = $('#calendar .month.actuel').attr('id').split('month');
			var moisActuel = parseInt(actuel[1]);
			var anneeActuel = parseInt(actuel[0]);

			if (anneeD < anneeActuel) {
				$(this).parent().hide();
				$(this).find("span").html(anneeActuel+'-'+moisActuel);
				$('#calendar #fleche_apres a').find("span").html(anneeActuel+'-'+moisActuel);
			} else {
				$(this).parent().show();
				$(this).find("span").html(anneeD+'-'+moisD);
				$('#calendar #fleche_apres a').find("span").html(anneeD+'-'+moisD);
			}

			$('#calendar #'+annee+'month'+mois).prevUntil('#calendar #'+anneeD+'month'+moisDbis).removeClass('invisible').addClass('current').fadeIn();
			flechePrev();

		} else if (mois >= 6 && mois <= 8) {
			// Afficher les 4 mois d'avant
			$('#calendar #'+annee+'month'+mois).prevUntil('#calendar #'+annee+'month'+moisDbis).removeClass('invisible').addClass('current').fadeIn();
			$(this).find("span").html(annee+'-'+moisD);
			$('#calendar #fleche_apres a').find("span").html(annee+'-'+moisD);
		} else if (mois >= 9 && mois <= 12) {
			// Afficher les 4 mois d'avant
			$('#calendar #'+annee+'month'+mois).prevUntil('#calendar #'+annee+'month'+moisDbis).removeClass('invisible').addClass('current').fadeIn();
			$(this).find("span").html(annee+'-'+moisD);
			$('#calendar #fleche_apres a').find("span").html(annee+'-'+moisD);
		} else alert("test");
		$('#calendar #fleche_apres').removeClass('invisible').removeClass('current').show();
		return false;
	});

	// GESTION DU CALENDRIER DE LA PAGE D'ACCUEIL calendar_home
	
	$('#calendar.home .prev').click(function() {
		
		var date = $('#calendar.home .month.current').attr('id').split("month");
		var mois = parseInt(date[1]);
		var annee = parseInt(date[0]);
		//alert(date);
		var datePrev = $(this).attr('id').split("-");
		var moisPrev = parseInt(datePrev[1]);
		var anneePrev = parseInt(datePrev[0]);
		//alert(datePrev);
		
			$('#calendar.home #'+annee+'month'+mois).removeClass('current').hide();
			$('#calendar.home #'+anneePrev+'month'+moisPrev).addClass('current').fadeIn();
			
		return false;
	});
	$('#calendar.home .next').click(function() {
	
		var date = $('#calendar.home .month.current').attr('id').split("month");
		var mois = parseInt(date[1]);
		var annee = parseInt(date[0]);
		//alert(date);
		var dateNext = $(this).attr('id').split("-");
		var moisNext = parseInt(dateNext[1]);
		var anneeNext = parseInt(dateNext[0]);
		//alert(datePrev);
		
			$('#calendar.home #'+annee+'month'+mois).removeClass('current').hide();
			$('#calendar.home #'+anneeNext+'month'+moisNext).addClass('current').fadeIn();
			
		return false;
	});


	// Calendrier en page d'accueil
	$('#calendar .month').hide();
	$('#calendar').find('div.current').show();
	$('#calendar td.couleur_attente').each(function() {
		var couleur = $(this).find('span').attr('class');
		$(this).addClass(couleur);
		$(this).removeClass('couleur_attente');
	});

	function flechePrev() {
		// Enlever la flèche "précédent" si le premier mois est le mois actuel
		var dateNow = new Date();
		var moisNow = dateNow.getMonth()+1;
		var anneeNow = dateNow.getFullYear();
		var fleche = $('#calendar #fleche_avant a').find("span").html();
		var flecheArray = fleche.split('-');


		if (flecheArray[0] == anneeNow && flecheArray[1] == moisNow) {
			$('#calendar #fleche_avant').hide();
		} else {
			$('#calendar #fleche_avant').show();
		}
	}
	flechePrev();
});

