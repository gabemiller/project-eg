<?php

function latestNewsAndlatestEvents($limit = 5){

	$templateNews = '<a href="{{link}}"><div><h5>{{title}}</h5>'
					.'<span><i class="icon-user"></i> {{author}}</span>'
					.'</div></a>';
	$query = 'SELECT * FROM news
			  WHERE public <= NOW() AND hidden = 1
			  ORDER BY public DESC LIMIT '.$limit.'';
			  
	$news = new TemplateNews($query);
	
	$templateEvents = '<a href="{{link}}"><div><h5>{{title}}</h5>'
					 .'<span><i class="icon-calendar"></i> '
					 .'{{startDate}} - {{endDate}}</span>'
					 .'</div></a>';
					 
	$query = 'SELECT * FROM events
			  WHERE hidden = 1
			  ORDER BY start DESC LIMIT '.$limit.'';
			  
	$events = new TemplateEvents($query);


	$string =  '<h4 class="side-header">Legfrissebb beszámolók</h4>';
    $string .= '<div class="latest-news">';
	foreach($news->createTemplate($templateNews) as $obj){
		$string .= $obj;
	}
	$string .= '</div>';
    $string .= '<h4 class="side-header">Legfrissebb események</h4>';
    $string .= '<div class="latest-events">';
	foreach($events->createTemplate($templateEvents) as $obj){
		$string .= $obj;
	}
	$string .= '</div>';
	
	return $string;

}

function randomEvent($limit = 1){
	
	$templateEvents =  '<a href="{{link}}"><div class="event">'
						.'<div class="event-pic">'
						.'{{picture}}'
						.'</div>'
						.'<div class="event-content">'
						.'<h4>{{title}}</h4>'
						.'<p><i class="icon-calendar"></i> {{startDate}}</p>'
						.'<p>{{endDate}}</p>'	
						.'</hgroup>'
						.'</div>'
						.'</div></a>';
					 
	$query = 'SELECT * FROM events
			  WHERE hidden = 1
			  ORDER BY RAND() LIMIT '.$limit.'';
			  
	$events = new TemplateEvents($query);
	
    $string = '<div class="random-events">';
	foreach($events->createTemplate($templateEvents) as $obj){
		$string .= $obj;
	}
	$string .= '</div>';
	
	return $string;
}

function html_fejlec_megjelenitese(){
?>

<!doctype html>
<html lang="hu">
<head>
<title>Együtthangzás.hu</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href='http://fonts.googleapis.com/css?family=Open+Sans&amp;subset=latin' rel='stylesheet' type='text/css'>
<link href="css/bootstrap.css" rel="stylesheet" media="screen">
<link href="css/bootstrap-responsive.css" rel="stylesheet" media="screen">
<link href="css/font-awesome.min.css" rel="stylesheet">
<link href="css/font-awesome-ie7.min.css" rel="stylesheet">
<!--<link href="css/szimfonia.css" rel="stylesheet" media="screen">-->
<script src="js/jquery-1.9.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/dividescript-1.0.js"></script>
</head>
<body>
<div id="wrapper">
  <?php
}

/////////
function fejlec_megjelenitese(){
	html_fejlec_megjelenitese();
	
	$query = 'SELECT * FROM pages WHERE hidden = 1';
	$pages = new TemplatePages($query);
	
	$navButtons = TemplatePages::createMenu();
	
?>
  <header>
    <div class="container">
      <h1 class="logo">Szimfónia Nyugdíjas Egyesület</h1>
      <ul class="nav main nav-pills hidden-phone">
        <?= $navButtons ?>
      </ul>
      <ul class="nav main nav-pills nav-stacked visible-phone">
        <?= $navButtons ?>
      </ul>
      
    </div>
  </header>
  <?php
}
//////
function fooldal_megjelenitese(){
?>
	<!--div id="jump-up-box" class="modal hide fade">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Köszönetnyilvánítás!</h3>
      </div>
      <div class="modal-body">
        <p class="text-center"><img src="../img/sznye.jpg" height="200" alt="Köszönetnyilvánítás"  /></p>
      </div>
    </div-->

  <div class="container">
  	<ul class="breadcrumb">
      <li class="active">Kezdőlap</li>
    </ul>
    <div id="tenpoints" class="alert alert-danger">
      <span class="counting">15</span>
      <h4><span class="id-num">1</span> Időt kell szánni a másik meghallgatására, a dolgok megvitatására (kétirányú utca).</h4>
    </div>
    <div class="row">
      <div class="span8">
        <?php
	  $template = '<article class="clearfix">'
				  .'<header>'
				  .'<a href="{{link}}"><h2>{{shortTitle}}</h2></a>'
				  .'<p>Írta: {{author}} {{publicDate}} '
				  .'</header>'
				  .'<p>{{shortContent}}</p>'
				  .'</article>';
					
		$query = 'SELECT * FROM news WHERE 
				  public <= NOW() AND hidden = 1
				  ORDER BY public DESC LIMIT 3';
				
		$latestNews = new TemplateNews($query);
				
		TemplateProcessor::printTemplate($latestNews->createTemplate($template));
	  ?>
      	<div class="more-news-button">
        	<a href="beszamolok.php">Még több beszámoló</a>
        </div>
      </div>
      <div class="span4"> 
           <?php
			$template = '<a href="{{link}}"><div class="event">'
						.'<div class="event-pic">'
						.'{{picture}}'
						.'</div>'
						.'<div class="event-content">'
						.'<h4>{{title}}</h4>'
						.'<p><i class="icon-calendar"></i> {{startDate}}</p>'
						.'<p>{{endDate}}</p>'	
						.'</hgroup>'
						.'</div>'
						.'</div></a>';
						
			$query = 'SELECT * FROM events 
					  WHERE hidden = 1 
					  ORDER BY start DESC LIMIT 5';
					
			$latestEvents = new TemplateEvents($query);
			
			TemplateProcessor::printTemplate($latestEvents->createTemplate($template));
			
		?>
        <div class="more-events-button">
        	<a href="esemenyek.php">Még több esemény</a>
        </div>
       </div>
    </div>
    
 
   </div>
  <?php
}
/////
function beszamolok_megjelenitese(){
	$template = '<article class="clearfix">'
				.'<header>'
				.'<a href="{{link}}"><h2>{{shortTitle}}</h2></a>'
				.'<p>Írta: {{author}} {{publicDate}} '
				.'</header>'
				.'<p>{{shortContent}}</p>'
				.'</article>';
	
	$query = 'SELECT * FROM news WHERE 
			  public <= NOW() AND hidden = 1 
			  ORDER BY public DESC';
				
	$news = new TemplateNews($query);
	
	$pagination = new Pagination($news->createTemplate($template),8,1);
	
	$pageNav = $pagination->printPagination();
	
?>
  <div class="container">
  	<ul class="breadcrumb">
      <li><a href="http://www.egyutthangzas.hu">Kezdőlap</a> <span class="divider">></span></li>
      <li class="active">Beszámolók</li>
    </ul>
    <div class="row">
      <div class="span8">
        <?= $pageNav ?>
        <?=	$pagination->printArray() ?>
        <?= $pageNav ?>
      </div>
      <div class="span4"> <?= randomEvent(4) ?> </div>
    </div>
  </div>
  <?php
}
//////
function egy_beszamolo_megjelenitese(){
	global $_GET, $connection;	
	$article = News::getArticle($_GET['id']);
?>
  <div class="container">
  	<ul class="breadcrumb">
      <li><a href="http://www.egyutthangzas.hu">Kezdőlap</a> <span class="divider">></span></li>
      <li><a href="beszamolok.php?">Beszámolók</a> <span class="divider">></span></li>
      <li class="active"><?= $article->__get('title') ?></li>
    </ul>
    <div class="row">
      <div class="span9">
      	
            <article class="one-news">
                <h1>
                  <?= $article->__get('title') ?>
                </h1>
                <h4>
                  <?= 'Írta: '.$article->__get('author').' '.$article->getPublicDate() ?>
                </h4>
                <div>
                  <?= $article->__get('content') ?>
                </div>
                <ul class="pager">
                  <?= $article->getPrevButton() ?>
                  <?= $article->getNextButton() ?>
                </ul>
            </article>
            
      </div>
      <div class="span3 hidden-phone">
        <?= latestNewsAndlatestEvents() ?>
      </div>
    </div>
  </div>
  <?php
}
//////
function bemutatkozas_megjelenitese(){
?>
  <div class="container">
  	<ul class="breadcrumb">
      <li><a href="http://www.egyutthangzas.hu">Kezdőlap</a> <span class="divider">></span></li>
      <li class="active">Bemutatkozás</li>
    </ul>
    <div class="row">
      <div class="span12">
        <?php require_once('bemutatkozas_text.php'); ?>
      </div>
    </div>
  </div>
  <?php
}
/////
function kapcsolat_megjelenitese(){
?>
  <div class="container">
  	<ul class="breadcrumb">
      <li><a href="http://www.egyutthangzas.hu">Kezdőlap</a> <span class="divider">></span></li>
      <li class="active">Kapcsolat</li>
    </ul>
    <div class="row">
      <div class="span12">
      
      	<iframe width="100%" height="400" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.hu/maps?f=q&amp;source=s_q&amp;hl=hu&amp;geocode=&amp;q=1112+Budapest,+Puskapor+u.+2%2Fa&amp;aq=&amp;sll=48.113942,21.090606&amp;sspn=0.916867,2.705383&amp;t=h&amp;ie=UTF8&amp;hq=&amp;hnear=1112+Budapest,+Puskapor+utca&amp;ll=47.446723,18.986435&amp;spn=0.020316,0.036478&amp;z=14&amp;iwloc=A&amp;output=embed"></iframe><br /><p><a class="btn btn-link btn-small" href="https://maps.google.hu/maps?f=q&amp;source=embed&amp;hl=hu&amp;geocode=&amp;q=1112+Budapest,+Puskapor+u.+2%2Fa&amp;aq=&amp;sll=48.113942,21.090606&amp;sspn=0.916867,2.705383&amp;t=h&amp;ie=UTF8&amp;hq=&amp;hnear=1112+Budapest,+Puskapor+utca&amp;ll=47.446723,18.986435&amp;spn=0.020316,0.036478&amp;z=14&amp;iwloc=A" target="_blank">Nagyobb térképre váltás</a></p>
        
        <ul class="thumbnails">
          <li class="span4 contact">
            <h5><span class="muted"><i class="icon-user"></i></span> Pacsai János <small>elnök</small></h5>
            <p><span class="muted"><i class="icon-phone"></i></span>+36-30/953-6067</p>
            <p><span class="muted"><i class="icon-envelope"></i></span><a href="mailto:pacsai.janos@egyutthangzas.hu">pacsai.janos@egyutthangzas.hu</a></p>
          </li>
          <li class="span4 contact">
            <h5><span class="muted"><i class="icon-user"></i></span> Dósa György <small>irodavezető</small></h5>
            <p><span class="muted"><i class="icon-phone"></i></span> +36-30/846-4967</p>
            <p><span class="muted"><i class="icon-envelope"></i></span><a href="mailto:dosa.gyorgy@egyutthangzas.hu">dosa.gyorgy@egyutthangzas.hu</a></p>
          </li>
          <li class="span4 contact">
            <h5><span class="muted"><i class="icon-user"></i></span> Dr. Gyulai Ákos <small>jogász</small></h5>
            <p><span class="muted"><i class="icon-phone"></i></span>+36-70/394-6480</p>
            <p><span class="muted"><i class="icon-envelope"></i></span><a href="mailto:gyulai.akos@egyutthangzas.hu">gyulai.akos@egyutthangzas.hu</a></p>
          </li>
          <li class="span4 contact">
            <h5><span class="muted"><i class="icon-user"></i></span> Dr. Szemán Ilona <small>könyvelő</small></h5>
            <p><span class="muted"><i class="icon-phone"></i></span>+36-30/915-3289</p>
            <p><span class="muted"><i class="icon-envelope"></i></span><a href="mailto:szeman.ilona@egyutthangzas.hu">szeman.ilona@egyutthangzas.hu</a></p>
          </li>
          <li class="span4 contact">
            <h5><span class="muted"><i class="icon-user"></i></span> Molnár Gábor <small>számítástechnikai szakember</small></h5>
            <p><span class="muted"><i class="icon-phone"></i></span>+36-30/623-1030</p>
            <p><span class="muted"><i class="icon-envelope"></i></span><a href="mailto:molnar.gabor@egyutthangzas.hu">molnar.gabor@egyutthangzas.hu</a></p>
          </li>
        </ul>
      </div>
    </div>
    <div class="row">
    	<div class="span12">
        	<table class="table table-hover">
            	<thead>
                	<tr>
                     	<th>Név</th>
                        <th>Megye</th>
                        <th>Város</th>
                        <th>Email</th>
                    </tr>
                </thead>
                
                <tbody>
                	<?php 
                		$array = Employee::getAllEmployee();
						foreach($array as $key=>$value){
							echo '<tr>'
								.'<td>'.$value->name.'</td>'
								.'<td>'.$value->county.'</td>'
								.'<td>'.$value->city.'</td>'
								.'<td><a href="mailto:'.$value->email.'">'.$value->email.'</a></td>'
							    .'</tr>';
							
						}
					?>
                </tbody>
            </table>
        </div>
    </div>
  </div>
  <?php
}
//////
function esemenyek_megjelenitese(){
	
	$template = '<a href="{{link}}"><div class="event">'
				.'<div class="event-pic">'
				.'{{picture}}'
				.'</div>'
				.'<div class="event-content">'
				.'<h4>{{title}}</h4>'
				.'<p><i class="icon-calendar"></i> {{startDate}}</p>'
				.'<p>{{endDate}}</p>'	
				.'</hgroup>'
				.'</div>'
				.'</div></a>';
	
	$query = 'SELECT * FROM events
			  WHERE hidden = 1
			  ORDER BY start DESC';
	
	$events = new TemplateEvents($query);
	
	$arrayEvents =	$events->createTemplate($template);
	
	$pagination = new Pagination($arrayEvents,10,1);
	
	$pageNav = $pagination->printPagination();
?>
  <div class="container">
  <ul class="breadcrumb">
      <li><a href="http://www.egyutthangzas.hu">Kezdőlap</a> <span class="divider">></span></li>
      <li class="active">Események</li>
    </ul>
    <div class="row">
      <div class="span12">
        <?= $pageNav ?>
        <div class="events">
        	<?= $pagination->printArray() ?>
        </div>
        <?= $pageNav ?>
      </div>
    </div>
  </div>
  <?php
}
//////
function egy_esemeny_megjelenitese(){
	global $_GET;
	$event = Events::getEvent($_GET['id']);
	$images = $event->__get('pictureArray');
?>
  <div class="container">
  	<ul class="breadcrumb">
      <li><a href="http://www.egyutthangzas.hu">Kezdőlap</a> <span class="divider">></span></li>
      <li><a href="esemenyek.php?">Események</a> <span class="divider">></span></li>
      <li class="active"><?= $event->__get('name') ?></li>
    </ul>
    <div class="row">
      <div class="span9">
        <article class="one-event">
          <h1><?= $event->__get('name') ?></h1>
          <h4><i class="icon-calendar"></i> <?= $event->getStartDate().' - '.$event->getEndDate() ?></h4>
          <?= $event->__get('content') ?>
          <?php if(sizeof($images)>0){ ?>
          <h3>Képek</h3>
            <ul class="thumbnails">
              <li class="span2"><?= $images[0] ?></li>
              <?php 
                            foreach(array_slice($images,1) as $obj){
                                echo '<li class="span1 hidden-phone">'.$obj.'</li>';
                            }
                        ?>
            </ul>
            <?php } ?>
          <ul class="pager">
            <li class="previous"> <a href="<?= $_SERVER['HTTP_REFERER'] ?>">Vissza</a> </li>
          </ul>
        </article>
      </div>
      <div class="span3 hidden-phone">
        <?= latestNewsAndlatestEvents() ?>
      </div>
    </div>
  </div>
  <?php
}
//////
function oldalak_megjelenitese(){
	global $_GET;
	
	$page = Pages::getPage($_GET['id']);
	
?>
  <div class="container">
  	<ul class="breadcrumb">
      <li><a href="http://www.egyutthangzas.hu">Kezdőlap</a> <span class="divider">></span></li>
      <li class="active"><?= $page->__get('name') ?></li>
    </ul>
    <div class="row">
      <div class="span9">
        <?= $page->__get('content') ?>
      </div>
      <div class="span3 hidden-phone">
        <?= latestNewsAndlatestEvents() ?>
      </div>
    </div>
  </div>
  <?php
}
//////
function linkek_megjelenitese(){
	global $connection;
?>
  <div class="container">
  	<ul class="breadcrumb">
      <li><a href="http://www.egyutthangzas.hu">Kezdőlap</a> <span class="divider">></span></li>
      <li class="active">Link ajánló</li>
    </ul>
        <?php 
		$result = $connection->database->prepare('SELECT * FROM link_category ORDER BY name ASC');
		$result->execute();
		
		
		foreach($result->fetchAll(PDO::FETCH_ASSOC) as $key => $value){
			
			
			$result2 = $connection->database->prepare('SELECT * FROM links WHERE cat_id = :cat_id ORDER BY name ASC');
			$result2->execute(array(':cat_id'=>$value['id']));
			
			$linkArray = $result2->fetchAll(PDO::FETCH_ASSOC);
			
			if(!empty($linkArray)){
				echo '<div class="well well-small vendor-links">';
				echo '<ul class="nav nav-list">'
 					 .'<li class="nav-header">'.$value['name'].'</li>';
				foreach($linkArray as $k => $v){
					echo '<li><a href="'.$v['url'].'" target="_blank">'.$v['name'].'</a></li>';
				}
				echo '</ul></div>';
			}
			
		}
	?>
  </div>
  <?php
}
//////
function hirlevelek_megjelenitese(){
?>
  <div class="container">
  	<ul class="breadcrumb">
      <li><a href="http://www.egyutthangzas.hu">Kezdőlap</a> <span class="divider">></span></li>
      <li class="active">Hírlevelek</li>
    </ul>
    <div class="alert alert-block alert-success">
      <p>A Hírlevelünk (.doc) formátumának letöltésével Ön, illetve szervezete egy kész "újságocskát" kap, melynek utolsó oldalán rögzítheti saját híreit információit. Ha kinyomtatja egy (lehetőség szerint kétoldalas) nyomtatóval, vagy kétoldalasan fénymásolja, úgy azonnal egy 4 oldalas Hírlevéllel rendelkezik, mely tartalmazza a Szimfónia Nyugdíjas Egyesület híreit, valamint az ön közérdekű információit. És már terjesztheti is a nyugdíjas körökben.</p>
      <p>Ha elakadna és segítségre van szüksége, írjon bizalommal szakemberünknek, Molnár Gábornak a <a href="mailto:molnar.gabor@egyutthangzas.hu">molnar.gabor@egyutthangzas.hu</a> címre.</p>
      <p>Sok örömet és sikert kívánunk újságához!</p>
    </div>
    <h2 class="newsletter-download">Letöltés</h2>
    <ul class="thumbnails">
      <?php 
			$test = new NewsletterFile();
			$array = $test->__get('files');
	
			for($i = 0; $i < sizeof($array); $i++){
				echo '<li class="span2 newsletter-files">'
					 .'<h3>'.$array[$i]->getMonth().'</h3>'
					 .'<h4>'.$array[$i]->__get('year').'</h4>'
					 .'<a href="'
				 	 .$array[$i]->getLink()
					 .'">'
					 .'<span><i class="icon-paper-clip"></i></span>'
					 .'letöltés'
					 .' ('
					 .$array[$i]->__get('ext')
					 .')</a>';
				 if(strcmp($array[$i]->__get('name'),$array[$i+1]->__get('name')) == 0){
					
				 echo	'<a href="'
				 		.$array[$i+1]->getLink()
						.'">'
				 		.'<span><i class="icon-paper-clip"></i></span>'
						.'letöltés'
						.' ('
						.$array[$i+1]->__get('ext')
						.')</a>';
						
				 $i++;
				 }
				 
				 echo '</li>';
			}

		
		?>
    </ul>
  </div>
  <?php
}
function folyo_projektek_megjelenitese(){
?>
  <div class="container">
    <ul class="breadcrumb">
      <li><a href="http://www.egyutthangzas.hu">Kezdőlap</a> <span class="divider">></span></li>
      <li><a href="projektek.php?oldal=folyo-projektek">Projektek</a> <span class="divider">></span></li>
      <li class="active">Folyó projektek</li>
    </ul>
    <div class="row">
      <div class="span6">
        <h3>Lépésről-lépésre projekt</h3>
        <p>TÁMOGATJA AZ EMBERI ERŐFORRÁSOK MINISZTÉRIUMA</p>
        <button id="lepesrol-lepesre" class="btn">Részletek</button> </div>
    </div>
    <div id="lepesrol-lepesre-info" class="row" style="display:none;">
      <div class="span12" >
        <?php require('projects/lepesrol-lepesre.php'); ?>
      </div>
    </div>
  </div>
  <script>
    	$(document).ready(function(e) {
			
			
            $('#lepesrol-lepesre').on('click',this,function(e){
					$('#lepesrol-lepesre-info').slideToggle('slow');
			});
        });
    </script>
  <?php
}
//////
function indulo_projektek_megjelenitese(){
?>
  <div class="container">
    <ul class="breadcrumb">
      <li><a href="http://www.egyutthangzas.hu">Kezdőlap</a> <span class="divider">></span></li>
      <li><a href="projektek.php?oldal=indulo-projektek">Projektek</a> <span class="divider">></span></li>
      <li class="active">Induló projektek</li>
    </ul>
    <div class="row">
      <div class="span6">
        <h4>NAGYSZÜLŐI HÁLÓZAT</h4>
        <p>"Unokátlan" nagyszülő – Segítségre szoruló gyermek, „unoka”.</p>
        <a class="btn disabled" href="#">Részletek</a> </div>
      <div class="span6">
        <h4>NYUGDÍJ ELŐTT – ÖTVENÖT ÉVESEN</h4>
        <p>Előttem a nyugdíjas élet – Hogyan tovább?</p>
        <a class="btn disabled" href="#">Részletek</a> </div>
    </div>
    <div class="row">
      <div class="span6">
        <h4>RESTART</h4>
        <p>Megürült lakás – Újra kezdőknek</p>
        <a class="btn disabled" href="#">Részletek</a> </div>
      <div class="span6">
        <h4>ÉLELMISZER-KOSÁR</h4>
        <p>Élelmiszer adomány – Rászorulók</p>
        <a class="btn disabled" href="#">Részletek</a> </div>
    </div>
    <div class="row">
      <div class="span6">
        <h4>ÜRES TEREK</h4>
        <p>Kihasználatlan, üres, „elhagyva álló” épületek (önkormányzati, egyházi, magán), helyiségek élettel megtöltése.</p>
        <a class="btn disabled" href="#">Részletek</a> </div>
      <div class="span6">
        <h4>EBÉD–PROGRAM</h4>
        <p>Heti, havi rendszerességű közös ebédje idős csoportoknak – A közös étkezés együttléte a fontos.</p>
        <a class="btn disabled" href="#">Részletek</a> </div>
    </div>
    <hr>
    <div class="row">
      <div class="span6 well well-small">
        <h4>Induló KÉPZÉSEK a koordinátori területeken</h4>
        <ul>
          <li>Önkéntesek a szociális intézményekben</li>
          <li>Betegeket, időseket látogatók
            <ul>
              <li>Nagyszülő és a net</li>
              <li>Klub vezetői képzés</li>
              <li>„Nagyszülői” képzés ( központi)</li>
              <li>Depresszióban lévők támogatása</li>
              <li>Ötlettár - 2 napos ötletelés</li>
              <li>„Közös felelősség” - elkötelezzük magunkat mások szolgálatában</li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </div>
  <?php
}
//////
function kerdesek_valaszok_megjelenitese(){
?>
<div class="container">
	<ul class="breadcrumb">
      <li><a href="http://www.egyutthangzas.hu">Kezdőlap</a> <span class="divider">></span></li>
      <li class="active">Kérdések és válaszok</li>
    </ul>
    
    <div class="row">
    	<div class="span3">
            <div class="well well-small question-info">
            	<h4 class="to-name">Pacsai János</h4>
                <h5 class="to-title">esperes-parókus</h5>
            	<p><i class="icon-envelope"></i> <a class="to-email" href="mailto:pacsai.janos@egyutthangzas.hu">pacsai.janos@egyutthangzas.hu</a></p>
            </div>
            <div class="well well-small">
            <ul class="nav nav-list to-navigation">
              <li class="active">
              	<a href="#" data-name="Pacsai János" data-title="esperes-parókus" data-email="pacsai.janos@egyutthangzas.hu">A lelkész válaszol</a></li>
              <li>
              	<a href="#" data-name="Dr. Gyulai Ákos" data-title="jogász" data-email="gyulai.akos@egyutthangzas.hu">A jogász válaszol</a>
              </li>
              <li>
              	<a href="#" data-name="Molnár Gábor" data-title="számítástechnikai szakember" data-email="molnar.gabor@egyutthangzas.hu">A számítástechnikus válaszol</a>
              </li>
              <li>
              	<a href="#" data-name="Dr. Szemán Ilona" data-title="könyvelő" data-email="szeman.ilona@egyutthangzas.hu">A könyvelő válaszol</a>
              </li>
              <li>
              	<a href="#" data-name="Dósa György" data-title="irodavezető" data-email="dosa.gyorgy@egyutthangzas.hu">Az iroda válaszol</a>
              </li>
            </ul>
            </div>
        </div>
        <div class="span9">
        	<form class="to-form" method="post" action="questionHandler.php">
                <label for="inputTo">Címzett</label>
                  <input type="hidden" id="inputTo" value="Pacsai János">
                  <span class="span4 uneditable-input">Pacsai János</span>

                <label for="inputName">Név</label>
                  <input class="span4" type="text" id="inputName" placeholder="Név">
                  
                <label for="inputEmail">Email</label>
                  <input class="span4" type="text" id="inputEmail" placeholder="Email">
                  
                <label for="inputText">Kérdés</label>
                  <textarea class="span8" rows="8" type="text" id="inputText"></textarea>
				<div>
                  <button type="submit" class="btn btn-primary">Küldés</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
	$(document).ready(function(e) {
        $('.to-navigation').find('a').on('click',this,function(e){
			e.preventDefault();
			var $this = $(this);
			$this.parent('li').siblings('li').removeClass('active');
			$this.parent('li').addClass('active');
			$('.to-name').html($this.data('name'));
			$('.to-title').html($this.data('title'));
			$('.to-email').html($this.data('email'));
			$('.to-email').attr('href','mailto:'+$this.data('email'));
			$('#inputTo').attr('value',$this.data('name'));
			$('.to-form').find('span').first().html($this.data('name'));
		});
    });
</script>
<?php
}
//////
function lablec_megjelenitese(){
?>
  <div id="push"></div>
</div>
<footer>
  <div class="container">
  	<div class="colors">
      <div class="color-line red-line"></div>
      <div class="color-line yellow-line"></div>
      <div class="color-line green-line"></div>
      <div class="color-line blue-line"></div>
      <div class="color-line violet-line"></div>
    </div>
    <p class="mute">© 2013 Szimfónia Nyugdíjas Egyesület</p>
  </div>
</footer>

<script>
$(document).ready(function(e) {
    var wrapperHeight = $('#wrapper').height();
	var headerHeight = $('header').height();
	var footerHeight = $('footer').height();
	$('#wrapper').children('.container').first().css('min-height',wrapperHeight-(headerHeight+footerHeight));
});
</script>
</body>
</html>
<?php
}

?>
