<?php
/*
	?? so wäre es am [aller]besten gewesen 
	*/
$drick = array(
	"anyy_method"=>function($message){ 
		print "anyy_method()\n";
		print_r($message);
		call("anyy_method_is_done", array("created"=>microtime()));
		return true; 
	},
	"some_method"=>function($message){
		print "some_method()\n";
		print_r($message);
		call("some_method_is_done", array("created"=>microtime()));
		return true; 
	},
	"drecks_method"=>function($message){
		print "drecks_method()\n";
		print_r($message);
		call("drecks_method_is_done", array("created"=>microtime()));
		return true;
	}
);

$dreck = array();

function bind($index, $method)
{
	global $dreck;
	if(!array_key_exists($index, $dreck)){
		$dreck[$index] = array();
	}
	if(!in_array($method, $dreck[$index])){
		$dreck[$index][] = $method;
	}
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

function call($index, $message){
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

bind("some_event", "some_method");
bind("some_event", "some_method");
bind("some_event", "some_method");
bind("some_event", "some_method");
bind("some_event", "anyy_method");
bind("anyy_event", "some_method");
bind("anyy_event", "no_such_met");
bind("some_event", "drecks_method");
bind("drecks_method_is_done", "some_method");

dnib("some_event", "some_method");
 
call("some_event", array("created"=>microtime()));
call("anyy_event", array("created"=>microtime()));
call("kaka_event", array("created"=>microtime()));
