<?php 
	ini_set('arg_separator.output', '&amp;');
	ini_set("url_rewriter.tags","a=href,area=href,frame=src,iframe=src,input=src");
	session_start();
	
	if (!isset($_REQUEST['page'])) { $page=""; }
	else { $page = $_REQUEST['page']; }
	
	if (!isset($_REQUEST['lvl2'])) { $lvl2=""; }
	else { $lvl2 = $_REQUEST['lvl2']; }
	
	// gestion titre meta
	if (isset($show) AND $show != "") {
		$titre_meta = $titre[$show]." - ";
	} else if (isset($lvl2) AND $lvl2 != "") {
		$titre_meta = $titre[$lvl2]." - ";
	} else if (isset($page) AND $page != "") {
		$titre_meta = $titre[$page]." - ";
	} else $titre_meta = "";
	
	require_once ("inc-connexion.php");
	include_once ("titre-description.php");
	include_once ("functions.php");
?>
<!doctype html>
<html lang="fr">
	<head>
		<meta charset="UTF-8">
		<!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge"><![endif]-->
		<meta name="viewport" content="initial-scale=1.0">
		<!--[if lt IE 9]>
		<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<title>Chez le Pèr' Pic - Appartement en location saisonnière - Luc-en-diois (26)</title>
		
		
		<script type="text/javascript" src="js/jquery-2.0.3.min.js"></script>
		<!--script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script-->
		<script type="text/javascript" src="js/jquery.ui.core.js"></script>
		<script type="text/javascript" src="js/jquery.colorbox.js"></script>
		<script type="text/javascript" src="js/datepicker/js/datepicker.js"></script>
		<script type="text/javascript" src="js/datepicker/js/eye.js"></script>
		<script type="text/javascript" src="js/datepicker/js/utils.js"></script>
		<!--link rel="stylesheet" media="screen" type="text/css" href="js/datepicker/css/layout.css" /-->
		<script src="nicEdit/nicEdit.js" type="text/javascript"></script>

		
		<!-- SET OF ICONS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

		<!-- Latest compiled and minified JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
		
		<link rel="stylesheet" type="text/css" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
		<link rel="stylesheet" type="text/css" href="styles.css" />
		<link rel="stylesheet" type="text/css" href="js/datepicker/css/datepicker.css" />
		<link rel="stylesheet" type="text/css" href="colorbox.css" />
		<script src="calendrier.js" type="text/javascript"></script>
	
		<script type="text/javascript">
		<!--
		
			$(window).load(function(){
				$('[data-toggle="popover"]').popover();

				$(".colorbox").colorbox({rel:'colorbox', transition:"elastic", width:"75%", height:"75%"});
				
				// Boutons + et - de l'ordre des photos / galerie
				$('.ordre .plusmoins .plus').click(function() {
					var id = $(this).attr('id').replace('plus','');
					var exval = parseInt($('#ordre'+id).val());
					var newval = exval + 1;
					$('#ordre'+id).val(newval);
					return false;
				});
				$('.ordre .plusmoins .moins').click(function() {
					var id = $(this).attr('id').replace('moins','');
					var exval = parseInt($('#ordre'+id).val());
					var newval = exval - 1;
					if (newval < 0) {
						alert("L'ordre ne peut pas être inférieur à 0.");
					} else {
						$('#ordre'+id).val(newval);
					}
					return false;
				});
				
				// Déplacer une photo dans une autre galerie
				$('a.deplacer').click(function() {
					$(this).next('div.deplacer').css('display','block');
					return false;
				});
					// fermer
				$('a.fermer').click(function() {
					$(this).parents('div.deplacer').css('display','none');
					return false;
				});
				
				// Tout cocher 
				$('input[name="all"]').bind('click',function(){
					var id = $(this).attr('id').replace('all','');
					var ischecked = this.checked;
					var cases = $('form#gal'+id).find(':checkbox');
					if(ischecked) {
						cases.prop('checked', true);
					} else {
						cases.prop('checked', false);
					}
				});
				
				// Afficher le div "mmodifier client"
				$('a#maj_client').click(function() {
					$('div#form_maj_client').css('display','block');
					return false;
				});
					// fermer
				$('div#form_maj_client a.cancel').click(function() {
					$(this).parents('div#form_maj_client').css('display','none');
					return false;
				});
				
				// Afficher le div "mettre à jour l'état de la réservation"
				$('a#maj_etat').click(function() {
					$('div#form_maj_etat').css('display','block');
					return false;
				});
					// fermer
				$('div#form_maj_etat a.cancel').click(function() {
					$(this).parents('div#form_maj_etat').css('display','none');
					return false;
				});
				
				
				
				
					var result = [];
				function isReserve(date) {
					var jour = date.getDate();
					var month = date.getMonth()+1;
					var year = date.getFullYear();
					var args = "jour="+year+"-"+month+"-"+jour;
					/*$.get("reserve.php",  args,
						function success(data){   
							return data;
							alert(data);
						}
					); */
					$.get("reserve.php",  args).done(function(data) {
						if (data.result == "true") { result = [true, "", ""]; }
						else if (data.result == "false") { result = [false, "", ""]; }
					},"json");
					return result;
					//return [true, "res="+result, ""];
				}
			
					// modèle 04
				 var currentPosition = 0;
				  var slideWidth = 415;
				  var slides = $('.slide');
				  var numberOfSlides = slides.length;

				  // Remove scrollbar in JS
				  $('#slidesContainer').css('overflow', 'hidden');

				  // Wrap all .slides with #slideInner div
				  slides
					.wrapAll('<div id="slideInner"><\/div>')
					// Float left to display horizontally, readjust .slides width
					.css({
					  'float' : 'left',
					  'width' : slideWidth
					});

				  // Set #slideInner width equal to total width of all slides
				  $('#slideInner').css('width', slideWidth * numberOfSlides);

				  // Insert controls in the DOM
				  $('#slideshow')
					.prepend('<span class="control" id="leftControl">Clicking moves left<\/span>')
					.append('<span class="control" id="rightControl">Clicking moves right<\/span>');

				  // Hide left arrow control on first load
				  manageControls(currentPosition);

				  // Create event listeners for .controls clicks
				  $('.control')
					.bind('click', function(){
					// Determine new position
					currentPosition = ($(this).attr('id')=='rightControl') ? currentPosition+1 : currentPosition-1;
					
					// Hide / show controls
					manageControls(currentPosition);
					// Move slideInner using margin-left
					$('#slideInner').animate({
					  'marginLeft' : slideWidth*(-currentPosition)
					});
				  });

				  // manageControls: Hides and Shows controls depending on currentPosition
				  function manageControls(position){
					// Hide left arrow if position is first slide
					if(position==0){ $('#leftControl').hide() } else{ $('#leftControl').show() }
					// Hide right arrow if position is last slide
					if(position==numberOfSlides-1){ $('#rightControl').hide() } else{ $('#rightControl').show() }
				  }
				  
				var page = "<?php echo $page; ?>";
				var now = new Date();
				var reservation = [];
				if (page == "formulaire_res") {
					$('#selection #arrivee').DatePicker({
						format: 'Y-m-d',
						date: $('#arrivee').val(),
						current: $('#arrivee').val(),
						starts: 1,
						calendars: 1,
						position: 'right',
						locale: {
							days: ["Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche"],
							daysShort: ["Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam", "Dim"],
							daysMin: ["Di", "Lu", "Ma", "Me", "Je", "Ve", "Sa", "Di"],
							months: ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"],
							monthsShort: ["Jan", "Fev", "Mar", "Avr", "Mai", "Juin", "Jui", "Aou", "Sep", "Oct", "Nov", "Dec"],
							weekMin: 'Se'
						},
						onRender: function(date) {
							var date1 = new Date(date.valueOf());
							var year = date1.getFullYear();
							var month = date1.getMonth();
							var month2 = date1.getMonth()+1;
							if (month < 10) { month = "0"+month; }
							var day = date1.getDate();
							if (day < 10) { day = "0"+day; }
							var date2 = year+"-"+month2+"-"+day;  
							$.get("reserve.php",{jour:date2},  
								function success(data){ 
									if (data != "false") {
										reservation.push('['+year+','+month+','+day+',0,0,0]');
									}
								}
								); 
							var dis = date.valueOf()+" < "+now.valueOf();
							var dis2 = "";
							var vardate = "";
							var tabDate = [];
							for(var i = 0; i < reservation.length; i++){
								vardate = new Date(reservation[i].substr(1,4),reservation[i].substr(6,2),reservation[i].substr(9,2),0,0,0);
								tabDate[i] = vardate;
								dis += " || "+date.valueOf()+" == "+tabDate[i].valueOf();
								if (i == 0) dis2 += date.valueOf()+" == "+tabDate[i].valueOf();
								else  dis2 += " || "+date.valueOf()+" == "+tabDate[i].valueOf();
							}
							return {
								disabled: (eval(dis)),
								className: (eval(dis2)) ? 'rouge' : false
							}
						},
						onBeforeShow: function(){
							$('#arrivee').DatePickerSetDate($('#arrivee').val(), true);
						},
						onChange: function(formated, dates){
							$('#arrivee').val(formated);
							$('#arrivee').DatePickerHide();
						}
					});
					$('#selection #depart').DatePicker({
						format: 'Y-m-d',
						date: $('#depart').val(),
						current: $('#depart').val(),
						starts: 1,
						calendars: 1,
						position: 'right',
						locale: {
							days: ["Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche"],
							daysShort: ["Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam", "Dim"],
							daysMin: ["Di", "Lu", "Ma", "Me", "Je", "Ve", "Sa", "Di"],
							months: ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"],
							monthsShort: ["Jan", "Fev", "Mar", "Avr", "Mai", "Juin", "Jui", "Aou", "Sep", "Oct", "Nov", "Dec"],
							weekMin: 'Se'
						},
						onRender: function(date) {
							var arrivee = new Date($('#arrivee').val());					
							var date1 = new Date(date.valueOf());
							var year = date1.getFullYear();
							var month = date1.getMonth();
							var month2 = date1.getMonth()+1;
							if (month < 10) { month = "0"+month; }
							var day = date1.getDate();
							if (day < 10) { day = "0"+day; }
							var date2 = year+"-"+month2+"-"+day;  
							$.get("reserve.php",{jour:date2},  
								function success(data){ 
									if (data != "false") {
										reservation.push('['+year+','+month+','+day+',0,0,0]');
									}
								}
							); 
							var dis = date.valueOf() < arrivee.valueOf();
							var dis2 = "";
							var vardate = "";
							var tabDate = [];
							for(var i = 0; i < reservation.length; i++){
								vardate = new Date(reservation[i].substr(1,4),reservation[i].substr(6,2),reservation[i].substr(9,2),0,0,0);
								tabDate[i] = vardate;
								dis += " || "+date.valueOf()+" == "+tabDate[i].valueOf();
								if (i == 0) dis2 += date.valueOf()+" == "+tabDate[i].valueOf();
								else  dis2 += " || "+date.valueOf()+" == "+tabDate[i].valueOf();
							}
							return {
								disabled: (eval(dis)),
								className: (eval(dis2)) ? 'rouge' : false
							}
							
						},
						onBeforeShow: function(){
							$('#depart').DatePickerSetDate($('#depart').val(), true);
						},
						onChange: function(formated, dates){
							$('#depart').val(formated);
							$('#depart').DatePickerHide();
						}
					});
					
				
				}
				
				
			});
		//-->	 
		</script>
	</head>
	<?php
		if($_GET['page'] != '' AND $_GET['page'] != 'accueil') {
			$bodyclass = 'interne';
		} else if (!isset($_GET['page']) OR $_GET['page'] == "accueil") {
			$bodyclass = 'home';
		}
	?>
	<body class="<?=$bodyclass;?>">
		<div id="body2">
		<div id="wrap">
			<div id="col_gauche">
				<div class="titres">
					<h1>Chez le Pèr' Pic</h1>
					<h2>Appartement en location saisonnière</h2>
					<p>Luc-en-diois, Drôme (26)</p>
				</div>
				<div class="menu">
					<ul>
						<li><a href="index.php">Accueil</a></li>
						<li><a href="index.php?page=formules">Formules et tarifs</a></li>
						<li><a href="index.php?page=photos">Photos</a></li>
						<li><a href="index.php?page=activites">Activités</a></li>
						<li><a href="index.php?page=reservation">Réservation</a>
							<ul>
								<li><a href="index.php?page=modalites_res">Modalités</a></li>
								<li><a href="index.php?page=formulaire_res">Formulaire</a></li>
							</ul>
						</li>
						<li><a href="index.php?page=acces">Accès</a></li>
						<li><a href="index.php?page=contact">Contact</a></li>
						<li><a href="index.php?page=livredor">Ils sont venus...</a></li>
						<?php if(isset($_SESSION['identifiant'])) { ?>
							<li><a href="index.php?page=prive">Espace privé</a></li>
						<?php } ?>
					</ul>
				</div>
				<div class="footer">
					<div class="menu_f">
						<ul class="connexion">
							<?php if(isset($_SESSION['identifiant'])) { ?>
							<li><a href="deconnexion.php">Déconnexion</a> (<?php echo $_SESSION['identifiant']; ?>)</li>
							<?php } else { ?>
							<li><a href="index.php?page=connexion">Connexion</a></li>
							<?php } ?>
						</ul>
						<ul>
							<li><a href="index.php?page=mentions">Mentions légales</a> - </li>
							<li><a href="documents/convention_conditions.pdf" target="_blank" title="Conditions Générales de Location">CGL</a></li>
						</ul>
					</div>
				</div>
			</div>
			<div id="principal">
				<div id="img_top">
					<!--<img src="images/lavande_img.jpg" alt="Chez le Pèr' Pic" />-->
					<img src="images/luc02_img.jpg" alt="Chez le Pèr' Pic" />
				</div>
				<div id="contenu">
				<?php
					
					include_once ("outils.php");
					
					switch($page) {
						case "accueil":
						include "accueil.php";
						break;
						
						case "news":
						include "news.php";
						break;
						
						case "formules":
						include "formules.php";
						break;
						
						case "photos":
						include "photos.php";
						break;
						
						case "activites":
						include "activites.php";
						break;
						
						case "reservation":
						include "reservation.php";
						break;
						
						case "modalites_res":
						include "modalites_res.php";
						break;
						
						case "formulaire_res":
						include "formulaire_res.php";
						break;
						
						case "acces":
						include "acces.php";
						break;
						
						case "contact":
						include "contact.php";
						break;
						
						case "prive":
						include "admin/a_accueil.php";
						break;
						
						case "livredor":
						include "livredor.php";
						break;
						
						case "mentions":
						include "mentions.html";
						break;
						
						case "connexion":
						include "connexion.php";
						break;
						
						case "deconnexion":
						include "deconnexion.php";
						break;
						
						default:
						include "accueil.php";
						break;
					}
				?>		
				</div> <!-- contenu -->
			</div> <!-- principal -->
		</div> <!-- wrap -->
		</div> <!-- body2 -->
	</body>
</html>
<?php mysqli_close($link); ?>