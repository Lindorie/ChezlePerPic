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
	
	mysql_query("SET NAMES UTF8"); 
	
	mysql_query("SET lc_time_names = 'fr_FR'");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
	"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
	
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Chez le Pèr' Pic - Appartement en location saisonnière - Luc-en-diois (26)</title>
		
		<!--script type="text/javascript" src="js/jquery-1.9.1.js"></script-->
		<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
		<script type="text/javascript" src="js/jquery.ui.core.js"></script>
		<script type="text/javascript" src="js/jquery.colorbox.js"></script>
		<script type="text/javascript" src="js/dateinput.js"></script>
		<script src="nicEdit/nicEdit.js" type="text/javascript"></script>
		<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDBRUteS9Sm5AWG-gPettVu9550lCn4BXo&amp;sensor=false&amp;language=fr&amp;region=fr"></script>
		<script type="text/javascript">
		  function initialize() {
			var mapOptions = {
			  center: new google.maps.LatLng(44.615393,5.45384),
			  zoom: 17,
			  mapTypeId: google.maps.MapTypeId.ROADMAP
			};
			var map = new google.maps.Map(document.getElementById("googlemap"),
				mapOptions);
			//var panel = document.getElementById("panel");
			//var dir = new GDirections(map, panel);
			//dir.load(+<?=$depart;?>+" to 44.615393,5.45384");
			//dir.load("Sonnay, France to Luc-en-diois, France");
				
			var image = 'images/house.png';
		    var myLatLng = new google.maps.LatLng(44.615393,5.45384);
		    var beachMarker = new google.maps.Marker({
			    position: myLatLng,
			    map: map,
			    icon: image
		    });
			var image2 = 'images/car.png';
		    var myLatLng2 = new google.maps.LatLng(44.615049,5.454634);
		    var beachMarker2 = new google.maps.Marker({
			    position: myLatLng2,
			    map: map,
			    icon: image2
		    });

		  }
		  google.maps.event.addDomListener(window, 'load', initialize);
		</script>

		<script type="text/javascript">bkLib.onDomLoaded(function() {new nicEditor({fullPanel : true}).panelInstance('nicedit'); });</script>
					
		<link rel="stylesheet" type="text/css" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
		<link rel="stylesheet" type="text/css" href="styles.css" />
		<link rel="stylesheet" type="text/css" href="flight.css" />
		<link rel="stylesheet" type="text/css" href="flight-calendar.css" />
		<link rel="stylesheet" type="text/css" href="colorbox.css" />
	
		<script type="text/javascript">
		<!--
		
			$(document).ready(function(){
					
			
				$(".colorbox").colorbox({rel:'colorbox', transition:"elastic", width:"75%", height:"75%"});
				
				$.datepicker.setDefaults( $.datepicker.regional[ "fr" ] );
				$(".datepicker").datepicker();
				$("#datepicker").datepicker();
				
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
				  
				  // the french localization
				$.tools.dateinput.localize("fr",  {
				   months:        'Janvier,F&eacute;vrier,Mars,Avril,Mai,Juin,Juillet,Ao&ucirc;t,' +
									'Septembre,Octobre,Novembre,D&eacute;cembre',
				   shortMonths:   'jan,f&eacute;v,mar,avr,mai,jun,jul,ao&ucirc;,sep,oct,nov,d&eacute;c',
				   days:          'dimanche,lundi,mardi,mercredi,jeudi,vendredi,samedi',
				   shortDays:     'dim,lun,mar,mer,jeu,ven,sam'
				});
				
					  // initialize dateinput
				var page = "<?php echo $page; ?>";
				if (page == "reservation") {
					$("#selection :date").dateinput({ 
						trigger: true, 
						format: 'yyyy-mm-dd', 
						min: -1,
						lang: 'fr', 
						firstDay: 1,
						yearRange: 	[-1, 5],
						offset: [30, 0]
					  });
					  // use the same callback for two different events. possible with bind
					$("#selection :date").bind("onShow onHide", function()  {
						$(this).parent().toggleClass("active");
					});
					// when first date input is changed
					$("#selection :date:first").data("dateinput").change(function() {
						// we use it's value for the seconds input min option
						$("#selection :date:last").data("dateinput").setMin(this.getValue(), true);
					});
					 
				}  else {
					
					$("#calendar :date").dateinput( {
							
						// closing is not possible
						onHide: function()  {
							return false; 
						},
						
						onShow: function() {
						$("#calendar .calweek a").each(function(){
						var that = this;
						$.get("jour.php",{jour:$(this).attr("href")},  
							function success(data){   
								$(that).addClass(data);
							}
							); 
						 });
						 },

						change: function(e, date)  {
							$("#calendar #theday").html(this.getValue("dd mmmm yyyy")); 
							$.get("event.php",{date:this.getValue("yyyy-mm-dd")},  
							function success(data){ // au succès on renvoie le résultat de la requête  
								$("#calendar #evenements").html(data);  // on l'affiche 
							});  
							
						},
							lang: 'fr', 
							firstDay: 1,
							format: 'dddd dd, mmmm yyyy',
							yearRange: 	[-1, 5],
							offset: [30, 0],
							min: 0

							
					// set initial value and show dateinput when page loads	
					}).data("dateinput").setValue(0).show();
					
					$("#calendar #calprev").click(function() {
					$("#calendar .calweek a").each(function(){
					var that = this;
					$.get("jour.php",{jour:$(this).attr("href")},  
						function success(data){   
							$(that).addClass(data);
						}
						); 
					 });
				} );			
				
				$("#calendar #calnext").click(function() {
					$("#calendar .calweek a").each(function(){
					var that = this;
					$.get("jour.php",{jour:$(this).attr("href")},  
						function success(data){   
							$(that).addClass(data);
						}
						); 
					 });
				} );
			
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
	<body class="<?=$bodyclass;?>" onload="initialize()">
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
						<li><a href="index.php?page=reservation">Réservation</a></li>
						<li><a href="index.php?page=acces">Accès</a></li>
						<li><a href="index.php?page=contact">Contact</a></li>
						<!--li><a href="index.php?page=livredor">Ils sont venus...</a></li-->
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
					<img src="images/image_top.jpg" alt="Chez le Pèr' Pic" />
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
				</div>
			</div>
		</div>
	</body>
</html>
<?php mysql_close($connexion); ?>