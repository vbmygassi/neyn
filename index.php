<?php
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

$drick = array(
	
	"anyy_method"=>function($message){ 
		global $model;
		// print "anyy_method()\n";
		// print_r($message);
		$model["record"] = "109";
		include("view/nicknack.php");
		call("anyy_method_is_done", array("created"=>microtime()));
		return true; 
	},
	
	"some_method"=>function($message){
		global $model;
		// writes db
		date_default_timezone_set("Europe/Berlin");
		$db = new SQLite3("loggar.db");
		$sql = "CREATE TABLE IF NOT EXISTS loggar (
				id INTEGER NOT NULL, 
				date VARCHAR(128), 
				doc TEXT, 
				PRIMARY KEY(id)
			);";
		if(!($db->exec($sql))){
			return false;
		}
		$stamp = date("U");
		$temp = serialize($message);
		$sql = "INSERT INTO loggar (date, doc) VALUES('$stamp', '$temp')";
		if(!($q = $db->query($sql))){
			return false;
		}
		// 
		$model["gehtnicht"] = "abc";
		call("some_method_is_done", array("created"=>microtime()));
		return true; 
	},
	
	"drecks_method"=>function($message){
		global $model;
		// print "drecks_method()\n";
		// print_r($message);
		// print_r($model);
		$model["neyn"] = "-:-";;
		call("drecks_method_is_done", array("created"=>microtime()));
		return true;
	}
);

$dreck = array();
$model = array();

function bind($index, $method)
{
	global $dreck;
	if(!array_key_exists($index, $dreck)){
		$dreck[$index] = array();
	}
	if(in_array($method, $dreck[$index])){
		return false;
	}
	$dreck[$index][] = $method;
	return true;
}

function dnib($index, $method)
{
	global $dreck;
	if(!array_key_exists($index, $dreck)){
		return false;
	}
	$dreck[$index] = array_diff($dreck[$index], array($method));
	return true;
}

function call($index, $message)
{
	global $drick;
	global $dreck;
	if(!array_key_exists($index, $dreck)){
		return false;
	}	
	foreach($dreck[$index] as $m){
		if(!array_key_exists($m, $drick)){
			continue;
		}
		$drick[$m]($message);
	}
	return true;
}

bind("init", "some_method");
bind("init", "some_method");
bind("init", "some_method");
bind("init", "some_method");
bind("init", "anyy_method");
bind("anyy_event", "some_method");
bind("anyy_event", "no_such_met");
bind("some_event", "drecks_method");
bind("drecks_method_is_done", "some_method");

dnib("some_event", "some_method");
 
call("init", 
	array(
		"created"=>microtime(), 
		"type"=>"immer_feste_auf_die_nase",
		"wasweis"=>100, 
		"ichwasi"=>999,
		"wasiwes"=>101
	)
);

call("anyy_event", 
	array(
		"created"=>microtime(),
		"type"=>"sagt das auge zum bein",
		"x"=>"ich gehe dann mal,",
		"y"=>"sagt das bein zum auge,",
		"z"=>"das will ich aber sehen"
	)
);

call("kaka_event", array("created"=>microtime()));

