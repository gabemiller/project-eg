<?php
$version = "v0.8";

function showImageUploader($folder = NULL){
	$images = Object::getImgsFromFolder($folder);
	$filenum = sizeof($images);
?>
<div class="span4">
  <h3>Képek<small><span class="information" data-trigger="hover" data-html="true" data-content="<span style='color:#333;'>A kép beillesztéséhez egyszerűen kattints a képre.</span>"><i class="icon-info-sign"></i></span></small></h3>
  <div class="row-fluid">
    <div class="span12">
      <ul class="thumbnails" data-filenum="<?php echo $filenum; ?>">
        <?php
				for($i = 0; $i<$filenum;$i++){
					echo $images[$i];
				}
			?>
      </ul>
    </div>
  </div>
  <div class="row-fluid">
    <div id="jquery-wrapped-fine-uploader"></div>
  </div>
</div>
<?php
}

function showHTMLHeader($loginPage=false){
	global $version;
?>

<!doctype html>
<html lang="hu">
<head>
<meta charset="utf-8">
<title>Divide Admin<?php echo $version; ?></title>
<link href="css/bootstrap.css" rel="stylesheet" media="screen">
<link href="css/bootstrap-responsive.min.css" rel="stylesheet" media="screen">
<link href="css/font-awesome.min.css" rel="stylesheet">
<link href="css/divide-admin-0.1.css" rel="stylesheet" media="screen">
<link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
<link href="css/fineuploader-3.3.0.css" rel="stylesheet" media="screen">
<link href="css/bootstrap-notify.css" rel="stylesheet" media="screen">
<link href="css/jquery.dataTables.css" rel="stylesheet" media="screen">
<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js" type="text/javascript"></script>
<![endif]-->
</head>
<body <?php if($loginPage) echo 'class="login-page"'; ?> >
<?php
}
/////
function showHeader(){
global $version, $_SESSION;
showHTMLHeader();
?>
<header>
  <div class="container-fluid">
    <div class="row-fluid">
      <div class="span12">
        <div class="navbar navbar-fixed-top navbar-inverse">
          <div class="navbar-inner">
            <div class="container"> <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </a> <a class="brand" href="http://www.divide.hu" target="_blank">divide</a>
              <div class="nav-collapse collapse">
                <ul class="nav">
                  <li class="dropdown"> <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-home"></i> Főoldal</a>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                      <li><a href="index.php">Licensz</a></li>
                      <!--<li><a href="#">Idézetek</a></li>
                      <li><a href="#">Képgaléria</a></li>-->
                    </ul>
                  </li>
                  <li class="dropdown"> <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-pencil"></i>Beszámoló</a>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                      <li><a href="newsupload.php">Új beszámoló</a></li>
                      <li><a href="newsList.php">Beszámolókezelés</a></li>
                    </ul>
                  </li>
                  <li class="dropdown"> <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-calendar"></i> Esemény</a>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                      <li><a href="eventsupload.php">Új esemény</a></li>
                      <li><a href="eventList.php">Eseménykezelés</a></li>
                    </ul>
                  </li>
                  <li class="dropdown"> <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-file-alt"></i>Oldal</a>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                      <li><a href="pagesupload.php">Új oldal</a></li>
                      <li><a href="pagesList.php">Oldalkezelés</a></li>
                    </ul>
                  </li>
                </ul>
                <ul class="nav pull-right">
                  <li class="dropdown"> <a class="dropdown-toggle" data-toggle="dropdown" href="#"> <i class="icon-user"></i> <?php echo $_SESSION['admin_name']; ?> </a>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                      <!--<li><a href="#"><i class="icon-group"></i> Adminisztrátorok</a></li>-->
                      <li><a href="settings.php"><i class="icon-cogs"></i> Beállítások</a></li>
                      <li class="divider"></li>
                      <li><a href="logout.php"><i class="icon-off"></i> Kijelentkezés</a></li>
                    </ul>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row-fluid">
      <div class="span12"></div>
    </div>
    <noscript>
    <div class="row-fluid">
      <div class="span12">
        <div class="alert alert-danger"> <i class="icon-info-sign"></i> Ki van kapcsolva, vagy le van tiltva a böngésződben a Javascript. Kérlek engedélyezd újra! A weboldal funkciói nagy mértékben javascript technológiára épülnek, így enélkül nem tudod használni. </div>
      </div>
    </div>
    </noscript>
  </div>
</header>
<div class="notifications bottom-right span3"></div>
<?php
}
/////
function showLogin(){
	global $version;
?>
<div class="container">
  <form class="form-signin" action="loginHandler.php" method="post">
    <h3 class="form-signin-heading">Admin Bejelentkezés <small><?php echo $version; ?></small></h3>
    <div class="alert alert-danger" style="display:none;"></div>
    <input class="input-block-level" name="name" type="text"   placeholder="Név">
    <input class="input-block-level" name="pwd" type="password"  placeholder="Jelszó">
    <button class="btn btn-block btn-primary" type="submit">Bejelentkezés</button>
    <p class="text-center"><a class="btn btn-link">Elfeljtett jelszó?</a></p>
  </form>
</div>
<?php
}
/////
function showIndex(){
 	global $version,$admin;
?>
<div class="container">
  <div class="row-fluid">
    <div class="span12"> </div>
  </div>
  <?php
  	if($admin->checkDefaultPass()){
		echo ' <div class="row-fluid">'
    		.'<div class="span12">'
			.'<div class="alert alert-danger"><i class="icon-exclamation-sign"></i> Kérlek változtasd meg a jelszavad!</div>'
			.'</div>'
  			.'</div>';
	}	
  ?>
  <?php
  	if($admin->checkEmailIsEmpty()){
		echo ' <div class="row-fluid">'
    		.'<div class="span12">'
			.'<div class="alert alert-danger"><i class="icon-exclamation-sign"></i> Kérlek adj meg egy email címet a beállítások menüpont alatt! Ha elfelejtenéd a jelszavad, később ezzel tudsz jelszóemlékeztetőt kérni!</div>'
			.'</div>'
  			.'</div>';
	}	
  ?>
  <div class="row-fluid">
    <div class="span12">
      <div class="hero-unit">
        <h1>InDefinite</h1>
        <p>© <?php echo date('Y'); ?> Divide-Expo Kft. Minden jog fenntartva. Divide InDefinite
        <small class="muted"><?php echo $version; ?></small></p>
        <p>A Divide-Expo Kft. által készített adminisztrációs felületének 
		<?php echo substr($version,1,4); ?> verziója. Ez az adminisztrációs felület
        azért készült, hogy a felhasználó számára egy barátságos kezelőfelütet 
        biztosítson saját weboldala karbantartása érdekében. Ezen program forráskódja
        és bárminemű megvalósítása a Divide-Expo Kft tulajdonjoga, másolása, árusítása
        szerzői jogokat sért, aki ezt megteszi bűncselekményt követ el.</p>
        <p>A szoftver az InDefinite nevet viseli, ezt a webes felületet 
        <a class="btn btn-link btn-large" href="mailto:shon.gd8@gmail.com">Molnár Gábor</a> írta és fejlesztette.
        Jelenleg a <?php echo substr($version,1,4); ?> verziónál tart. További fejlesztések várhatók.
        Hibákat a névre kattintott email címen lehet jelezni, illetve a 
        <a class="btn btn-link btn-large" href="mailto:divide@divide.hu">divide@divide.hu</a> címen is.</p>
      </div>
    </div>
  </div>
  <div class="row-fluid">
    <div class="span12">
      <?php if(strcmp($_SESSION['admin_name'],'Molnár Gábor') == 0) include_once('changelog.php'); ?>
    </div>
  </div>
</div>
<?php
}
/////
function showNewsUpload(){
	global $_GET, $_SESSION;
	if(isset($_GET['id']) && !empty($_GET['id'])){
		$news = News::getArticle($_GET['id']);
		if(!empty($news)){
			$event = 'modify';
			$id = $news->__get('id');
			$title = $news->__get('title');
			$author = $news->__get('author');
			$content = $news->__get('content');
			$date = array(1=>date("Y.m.d H:i:s",strtotime($news->__get('publicDate')))
						 ,2=>date("Y.m.d H:i:s",strtotime($news->__get('createDate'))));
			$button = 'Módosít';
			$return = '<a class="btn pull-right" href="newsList.php">Vissza</a>';
			if($news->__get('hidden') == 1){
				$hidden = array(1=>'selected="selected"',2=>'');
			}elseif($news->__get('hidden') == 2){
				$hidden = array(1=>'',2=>'selected="selected"');
			}
			
		} 
	} else {
			
			$id = News::getNextId();
			$event = 'upload';
			$title = NULL;
			$author = $_SESSION['admin_name'];
			$content = NULL;
			$button = 'Mentés';
			$return = NULL;
			$hidden = array(1=>'selected="selected"',2=>'');
			$date = array(1=>date("Y.m.d H:i:s"),2=>date("Y.m.d H:i:s"));
	}
?>
<div class="container">
  <div class="row-fluid">
    <div class="span8 well well-small">
      <form class="ajax-form" data-event="<?php echo $event; ?>" name="news" action="newsHandler.php" method="post">
        <h3>Beszámoló <small> <?php echo '#'.$id; ?></small></h3>
        <input name="id" type="hidden" value="<?php echo $id; ?>" />
        <label for="title">Beszámoló címe <span data-trigger="hover" data-content="Lehetőleg rövid, frappáns címet adj meg. Maximum 80 karakter hosszú lehet."> <i class="icon-info-sign"></i> </span> </label>
        <input class="input-block-level" name="title" type="text" placeholder="Cím" value="<?php echo $title;?>" />
        <label for="author">Szerző <span data-trigger="hover" data-content="Mindig az adott felhasználónévvel belépett admin lesz a beszámoló szerzője."> <i class="icon-info-sign"></i> </span> </label>
        <input name="author" type="hidden" value="<?php echo $author;?>"/>
        <span class="input uneditable-input"><?php echo $author;?></span>
        <label for="date">Dátum <span data-trigger="hover" data-content="Ez a publikálás dátuma. Megadhatsz későbbi dátumot, ha nem azonnal szeretnéd publikálni a beszámolót. A megadáshoz kattints a naptár ikonra."> <i class="icon-info-sign"></i> </span> </label>
        <div class="input-append datetimepicker">
          <input name="publicDate" type="text" value="<?php echo $date[1]; ?>"/>
          <span class="add-on"> <i data-time-icon="icon-time" data-date-icon="icon-calendar"> </i> </span> </div>
        <input name="createDate" type="hidden" value="<?php echo $date[2]; ?>"/>
        <label for="hidden">Látható?</label>
        <select class="input-small" name="hidden">
          <option <?php echo $hidden[1];?> value="1">Igen</option>
          <option <?php echo $hidden[2];?> value="2">Nem</option>
        </select>
        <p>
          <textarea id="editor" name="newsContent">
          	<?php echo $content;?>
        	</textarea>
        </p>
        <button class="btn btn-primary" type="submit"><?php echo $button;?></button>
        <?php echo $return;?>
      </form>
    </div>
    <?php showImageUploader('../img/news/'.$id.'/mini'); ?>
  </div>
</div>
<?php
}
/////
function showNewsList(){
	$connection = Object::connectSQL();
	$query = $connection->query('SELECT * FROM news');
?>
<div class="container">
  <table id="data-table" cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" data-url="newsHandler.php" data-modify="newsupload.php">
    <thead>
      <tr>
        <th>#</th>
        <th>Cím</th>
        <th>Szerző</th>
        <th>Dátum</th>
        <th>Publikálás</th>
        <th class="edit-head"><i class="icon-circle"></i></th>
        <th class="watch-head"><i class="icon-circle"></i></th>
        <th class="remove-head"><i class="icon-circle"></i></th>
      </tr>
    </thead>
    <tbody>
      <?php
					while($row = $query->fetch_array()){
						echo '<tr>'
						     .'<td>'.$row['id'].'</td>'
							 .'<td>'.$row['title'].'</td>'
							 .'<td>'.$row['author'].'</td>'
							 .'<td>'.str_replace('-','.',$row['date']).'</td>'
							 .'<td>'.str_replace('-','.',$row['public']).'</td>'
							 .'<td class="table-btn edit-element"><i class="icon-pencil"></i></td>';
						
						if($row['hidden'] == 1)  
							echo '<td class="table-btn watch-element"><i class="icon-eye-open"></i></td>';
						else
							echo '<td class="table-btn watch-element element-hidden"><i class="icon-eye-close"></i></td>';
						
						echo '<td class="table-btn remove-element"><i class="icon-remove"></i></td>'
							 .'</tr>';
					}
                ?>
    </tbody>
  </table>
</div>
<?php
}
/////
function showEventsUpload(){
	global $_GET;
	if(isset($_GET['id']) && !empty($_GET['id'])){
		$news = Events::getEvent($_GET['id']);
		if(!empty($news)){
			$event = 'modify';
			$id = $news->__get('id');
			$name = $news->__get('name');
			$content = $news->__get('content');
			$date = array(1=>date("Y.m.d H:i:s",strtotime($news->__get('startDate')))
						 ,2=>date("Y.m.d H:i:s",strtotime($news->__get('endDate'))));
			$button = 'Módosít';
			$return = '<a class="btn pull-right" href="eventsList.php">Vissza</a>';
			if($news->__get('hidden') == 1){
				$hidden = array(1=>'selected="selected"',2=>'');
			}elseif($news->__get('hidden') == 2){
				$hidden = array(1=>'',2=>'selected="selected"');
			}
		} 
		
	} else {
			$id = Events::getNextId();
			$event = 'upload';
			$name = NULL;
			$content = NULL;
			$button = 'Mentés';
			$return = NULL;
			$hidden = array(1=>'selected="selected"',2=>'');
			$date = array(1=>date("Y.m.d H:i:s"),2=>date("Y.m.d H:i:s"));
	}
?>
<div class="container">
  <div class="row-fluid">
    <div class="span8 well well-small">
      <form  class="ajax-form" data-event="<?php echo $event; ?>" name="events" action="eventsHandler.php" method="post">
        <h3>Esemény <small><?php echo '#'.$id; ?></small></h3>
        <input class="id" name="id" type="hidden" value="<?php echo $id; ?>" data-id="<?php echo $id; ?>" />
        <label for="name">Esemény címe <span data-trigger="hover" data-content="Lehetőleg rövid, frappáns címet adj meg. Maximum 80 karakter hosszú lehet."> <i class="icon-info-sign"></i> </span> </label>
        <input class="input-block-level" name="name" type="text" placeholder="Cím" value="<?php echo $name; ?>"/>
        <label for="startdate">Esemény kezdete <span data-trigger="hover" data-html="true" data-content="Az esemény kezdete. <br>Ne adj meg a mai naptól korábbi dátumot!"> <i class="icon-info-sign"></i> </span> </label>
        <div class="input-append datetimepicker">
          <input name="startDate" type="text" value="<?php echo $date[1]; ?>"/>
          <span class="add-on"> <i data-time-icon="icon-time" data-date-icon="icon-calendar"> </i> </span> </div>
        <label for="enddate">Esemény vége <span data-trigger="hover" data-html="true" data-content="Az esemény vége. <br>Ne adj meg a kezdettől korábbi dátumot!"> <i class="icon-info-sign"></i> </span></label>
        <div class="input-append datetimepicker">
          <input name="endDate" type="text" value="<?php echo $date[1]; ?>"/>
          <span class="add-on"> <i data-time-icon="icon-time" data-date-icon="icon-calendar"> </i> </span> </div>
        <label for="hidden">Látható?</label>
        <select class="input-small" name="hidden">
          <option <?php echo $hidden[1]; ?> value="1">Igen</option>
          <option <?php echo $hidden[2]; ?> value="2">Nem</option>
        </select>
        <p>
          <textarea id="editor" name="eventContent">
           <?php echo $content;?>
          </textarea>
        </p>
        <button class="btn btn-primary" type="submit"> <?php echo $button;?></button>
        <?php echo $return;?>
      </form>
    </div>
    <?php showImageUploader('../img/events/'.$id.'/mini'); ?>
  </div>
</div>
<?php
}
/////
function showEventList(){
	$connection = Object::connectSQL();
	$query = $connection->query('SELECT * FROM events');
?>
<div class="container">
  <table id="data-table" cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" data-url="eventsHandler.php" data-modify="eventsupload.php">
    <thead>
      <tr>
        <th>#</th>
        <th>Cím</th>
        <th>Indulás</th>
        <th>Vége</th>
        <th class="edit-head"><i class="icon-circle"></i></th>
        <th class="watch-head"><i class="icon-circle"></i></th>
        <th class="remove-head"><i class="icon-circle"></i></th>
      </tr>
    </thead>
    <tbody>
      <?php
					while($row = $query->fetch_array()){
						echo '<tr>'
						     .'<td>'.$row['id'].'</td>'
							 .'<td>'.$row['name'].'</td>'
							 .'<td>'.str_replace('-','.',$row['start']).'</td>'
							 .'<td>'.str_replace('-','.',$row['end']).'</td>'
							 .'<td class="table-btn edit-element"><i class="icon-pencil"></i></td>';
						
						if($row['hidden'] == 1)  
							echo '<td class="table-btn watch-element"><i class="icon-eye-open"></i></td>';
						else
							echo '<td class="table-btn watch-element element-hidden"><i class="icon-eye-close"></i></td>';
						
						echo '<td class="table-btn remove-element"><i class="icon-remove"></i></td>'
							 .'</tr>';
					}
                ?>
    </tbody>
  </table>
</div>
<?php
}
/////
function showPagesUpload(){	
	global $_GET;
	if(isset($_GET['id']) && !empty($_GET['id'])){
		$news = Pages::getPage($_GET['id']);
		if(!empty($news)){
			$event = 'modify';
			$id = $news->__get('id');
			$name = $news->__get('name');
			$content = $news->__get('content');
			$button = 'Módosít';
			$return = '<a class="btn pull-right" href="pagesList.php">Vissza</a>';
			if($news->__get('hidden') == 1){
				$hidden = array(1=>'selected="selected"',2=>'');
			}elseif($news->__get('hidden') == 2){
				$hidden = array(1=>'',2=>'selected="selected"');
			}
		} 
		
	} else {
			$id = Pages::getNextId();
			$event = 'upload';
			$name = NULL;
			$content = NULL;
			$button = 'Mentés';
			$return = NULL;
			$hidden = array(1=>'selected="selected"',2=>'');
	}
?>
<div class="container">
  <div class="row-fluid">
    <div class="span8 well well-small">
      <form class="ajax-form" data-event="<?php echo $event; ?>" name="pages" action="pagesHandler.php" method="post">
        <h3>Oldal</h3>
        <input class="id" name="id" type="hidden" value="<?php echo $id; ?>" data-id="<?php echo $id; ?>"/>
        <label for="menu">Menüpont <span data-trigger="hover" data-content="A menüpont neve maximum 20 karakterből álljon."> <i class="icon-info-sign"></i> </span> </label>
        <input name="name" type="text" placeholder="Menüpont neve"  value="<?php echo $name; ?>"/>
        <label for="hidden">Látható?</label>
        <select class="input-small" name="hidden">
          <option <?php echo $hidden[1]; ?> value="1">Igen</option>
          <option <?php echo $hidden[2]; ?> value="2">Nem</option>
        </select>
        <label for="content">Tartalom</label>
        <p>
          <textarea id="editor" name="pageContent">
          <?php echo $content; ?>
          </textarea>
        </p>
        <button class="btn btn-primary" type="submit">Mentés</button>
        <?php echo $return; ?>
      </form>
    </div>
    <?php showImageUploader('../img/pages/'.$id.'/mini'); ?>
  </div>
</div>
<?php
}
/////
function showPagesList(){
	$connection = Object::connectSQL();
	$query = $connection->query('SELECT * FROM pages');
?>
<div class="container">
  <div class="row-fluid">
    <div class="span12">
      <h3>Oldalak</h3>
    </div>
  </div>
  <div class="row-fluid">
    <div class="span3">
      <table  cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" data-url="pagesHandler.php" data-modify="pagesupload.php">
        <thead>
          <tr>
            <th>#</th>
            <th>Menü</th>
            <th class="edit-head"><i class="icon-circle"></i></th>
            <th class="watch-head"><i class="icon-circle"></i></th>
            <th class="remove-head"><i class="icon-circle"></i></th>
          </tr>
        </thead>
        <tbody>
          <?php
					while($row = $query->fetch_array()){
						echo '<tr>'
						     .'<td>'.$row['id'].'</td>'
							 .'<td>'.$row['name'].'</td>'
							 .'<td class="table-btn edit-element"><i class="icon-pencil"></i></td>';
						
						if($row['hidden'] == 1)  
							echo '<td class="table-btn watch-element"><i class="icon-eye-open"></i></td>';
						else
							echo '<td class="table-btn watch-element element-hidden"><i class="icon-eye-close"></i></td>';
						
						echo '<td class="table-btn remove-element"><i class="icon-remove"></i></td>'
							 .'</tr>';
					}
                ?>
        </tbody>
      </table>
    </div>
    <div class="span9">
      <div class="well">
        <h1>Oldalak</h1>
        <p>Kérlek válassz ki egy oldalt, amit szeretnél megtekinteni.</p>
      </div>
    </div>
  </div>
</div>
<?php
}
/////
function showSettings(){
?>
<div class="container">
	<div class="row">
    	
        <div class="span3 well well-small">
        <h4><i class="icon-lock"></i> Jelszó</h4>
        	<form class="admin-form" data-event="password" method="post" action="settingsHandler.php">
            	<input class="input-block-level" name="old_pwd" type="password" placeholder="Régi jelszó"/>
                <hr>
                <input class="input-block-level" name="new_pwd" type="password" placeholder="Új jelszó"/>
                <input class="input-block-level" name="new_pwd_confirm" type="password" placeholder="Új jelszó megerősítése"/>
                <button class="btn btn-primary" type="submit">Módosít</button>
            </form>
        </div>
        <div class="span4 well well-small">
        <h4><i class="icon-envelope"></i> Email</h4>
        	<form class="admin-form" data-event="email" method="post" action="settingsHandler.php">
            	<div class="input-append">
                <input class="span3" name="email" type="text" value="<?php echo $_SESSION['email']; ?>" placeholder="Email"/>
                <button class="btn btn-primary" type="submit">Módosít</button></div>
            </form>
        </div>
        
    </div>
</div>
<?php
}
/////
function showFooter(){
?>
<script src="js/jquery/jquery-1.9.1.min.js"></script> 
<script src="js/bootstrap/bootstrap.min.js"></script> 
<script src="js/bootstrap-plugins/bootstrap-datetimepicker.min.js"></script> 
<script src="js/bootstrap-plugins/bootstrap-notify.js"></script> 
<script src="ckeditor/ckeditor.js"></script> 
<script src="js/jquery-plugins/jquery.fineuploader-3.3.0.js"></script> 
<script src="js//jquery-plugins/jquery.dataTables.min.js"></script> 
<script src="js/datatable-bootstrap.js"></script> 
<script src="js/dividescript-0.7.js"></script>
</body>
</html>
<?php
}

?>
