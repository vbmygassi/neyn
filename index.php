<?php date_default_timezone_set("Europe/Berlin");

/*
	?? so wäre es am [aller]besten gewesen 
		da kann man alles reinschreiben dann
		der router ist dann 
		[host|/[name der route]/test.php
		! nee!
		noch viel, viel, viel besser...
		ich habe noch EINE VIEL BESSERE IDEE...
		[host|/[name der route]/index.php 
		also
		[host|/[name der route]/
		skrass 
	*/

$commis = array(
	
	"render"=>function($message){ 
		global $model;
		$model["test1"] = "109";
		$model["test2"] = "Mühsam nährt sich das Eichhörnchen";
		include("view/nicknack.php");
		notify("render_is_done", array("time"=>microtime()));
		return true; 
	},

	"renderror"=>function($message){
		include("view/error.php");
		return true;
	},
	
	"setup_loggar_db"=>function($message){
		global $model;
		try{
			$model["db"] = new SQLite3(dirname(__file__) . "/db/loggar");
		}
		catch(Exception $e){
			notify("setup_loggar_db_failed", 
				array(
					"time"=>microtime(),
					"message"=>$e->getMessage(),
				)
			);
			return false;
		}
		$sql = "CREATE TABLE IF NOT EXISTS loggar (
			id INTEGER NOT NULL, 
			date VARCHAR(128), 
			seri TEXT, 
			json TEXT, 
			PRIMARY KEY(id)
		);";
		if(!($model["db"]->exec($sql))){
			notify("setup_loggar_db_failed", 
				array(	
					"time"=>microtime(),
					"message"=>"could not write table",
					"sql"=>$sql
				)
			);
			return false;
		}
		notify("setup_loggar_db_is_done", array("time"=>microtime()));
		return true;
	},

	"nokkedli"=>function($message){	
		global $model;
		if(null == $model["db"]){
			notify("error_no_loggar_db", array("time"=>microtime()));
			return false;
		}
		$seri = serialize($message);
		$json = json_encode($message);
		$stmp = date("U");
		$sql = "INSERT INTO loggar (date, seri, json) VALUES('$stmp', '$seri', '$json')";
		if(!($q = $model["db"]->query($sql))){
			notify("nokkedli_failed", 
				array(
					"time"=>microtime(),
					"message"=>"no insert",
					"sql"=>$sql
				)
			);
			return false;
		}
		notify("nokkedli_is_done", array("time"=>microtime()));
		return true; 
	},

	"hach, wer will das noch lesen.."=>function($message){
		// social, message driven rapid app prototyping 
		$message["owner"] = array(
			"kostenstelle"=>696,
			"avatar"=>"gesicht.png",
			"about"=>"Meine Mutter Hausfrau, der Vater Taxifahrer..."
		);
	}
);

$sched = array();
$model = array();

function bind($index, $method)
{
	global $sched;
	if(!array_key_exists($index, $sched)){
		$sched[$index] = array();
	}
	if(in_array($method, $sched[$index])){
		return false;
	}
	$sched[$index][] = $method;
	return true;
}

function dnib($index, $method)
{
	global $sched;
	if(!array_key_exists($index, $sched)){
		return false;
	}
	$sched[$index] = array_diff($sched[$index], array($method));
	return true;
}

function notify($index, $message)
{
	global $commis;
	global $sched;
	if(!array_key_exists($index, $sched)){
		return false;
	}	
	foreach($sched[$index] as $m){
		if(!array_key_exists($m, $commis)){
			continue;
		}
		$commis[$m]($message);
	}
	return true;
}

bind("init", "setup_loggar_db");
bind("setup_loggar_db_failed", "renderror");
bind("setup_loggar_db_is_done", "nokkedli");
bind("nokkedli_failed", "renderror");
bind("no_loggar_db", "renderror");
bind("nokkedli_is_done", "render");

notify("init", 
	array(
		"type"=>"was tun, wenn der bär kommt...",
		"message"=>"immer kräftig auf die nase schlagen..."
	)
);

