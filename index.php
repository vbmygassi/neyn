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

$command = array(
	
	"render"=>function($message){ 
		global $model;
		$model["test1"] = "109";
		$model["test2"] = "Mühsam nährt sich das Eichhörnchen";
		include("view/nicknack.php");
		call("anyy_method_is_done", array("created"=>microtime()));
		return true; 
	},
	
	"setup_db"=>function($message){
		$db = new SQLite3("loggar.db");
		$sql = "CREATE TABLE IF NOT EXISTS loggar (
				id INTEGER NOT NULL, 
				date VARCHAR(128), 
				log TEXT, 
				PRIMARY KEY(id)
			);";
		if(!($db->exec($sql))){
			call("setup_db_failed", array("created"=>microtime()));
			return false;
		}
		$temp = serialize($message);
		$stmp = date("U");
		$sql = "INSERT INTO loggar (date, log) VALUES('$stmp', '$temp')";
		if(!($q = $db->query($sql))){
			call("setup_db_failed", array("created"=>microtime()));
			return false;
		}
		call("setup_db_is_done", array("created"=>microtime()));
		return true; 
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

function call($index, $message)
{
	global $command;
	global $sched;
	if(!array_key_exists($index, $sched)){
		return false;
	}	
	foreach($sched[$index] as $m){
		if(!array_key_exists($m, $command)){
			continue;
		}
		$command[$m]($message);
	}
	return true;
}

bind("init", "setup_db");
bind("setup_db_is_done", "render");

call("init", 
	array(
		"created"=>microtime(), 
		"type"=>"immer_feste_auf_die_nase",
		"wasweis"=>100, 
		"ichwasi"=>999,
		"wasiwes"=>101
	)
);


