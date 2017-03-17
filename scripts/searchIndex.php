<?php
/**
 * @author Cyril Vermande (madislak@yahoo.fr)
 * @license MIT
 * @copyright All rights reserved 2017 Cyril Vermande
 */

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Routing\RouteCollection;

$searchIndexes = array();

$filenames = glob($srcPath.'/*Bundle/Resources/config/services.yml');
foreach($filenames as $filename){
	$yaml = Yaml::parse(file_get_contents($filename));
	
	$patterns = array();
	$replacements = array();
	if(!empty($yaml['parameters'])){
		foreach($yaml['parameters'] as $key=>$value){
			if(is_scalar($value)){
				$patterns[] = '/%'.$key.'%/';
				$replacements[] = $value;
			}
		}
	}
	
	$i=0;
	if(!empty($yaml['services'])){
		foreach($yaml['services'] as $name=>$service){
			if(isset($service['class'])){
				$newIndex = array();
				$newIndex['type'] = "Service";
				$newIndex['name'] = $name;
				$newIndex['doc'] = $name;
				$newIndex['link'] = preg_replace($patterns, $replacements, $service['class']).'.html';
				$newIndex['link'] = preg_replace('/\\\\/', '/', $newIndex['link']);
				$searchIndexes[] = $newIndex;
				$i++;
			}
		}
	}
	
	echo ($i ? $i : "No")." services indexed from file ".$filename."\n";
}

$filenames = glob('{'.$srcPath.'/*Bundle/Resources/config/routing*.yml,'.$srcPath.'/*Bundle/Resources/config/routing/*.yml}',GLOB_BRACE);
foreach($filenames as $filename){
	$yaml = Yaml::parse(file_get_contents($filename));
	
	$i=0;
	if(!empty($yaml)){
		foreach($yaml as $name=>$route){
			if(isset($route['defaults']['_controller'])){
				$elements = preg_split('/:/', $route['defaults']['_controller']);
				$bundle = preg_replace('/(?<!^)([A-Z])/', '/\\1', str_replace('Bundle', '', $elements[0])).'Bundle';
				$link = $bundle.'/Controller/'.$elements[1].'Controller.html';
				if(isset($elements[2])) $link .= '#method_'.$elements[2].'Action';
				
				$newIndex = array();
				$newIndex['type'] = "Route";
				$newIndex['name'] = $name;
				$newIndex['doc'] = $name;
				$newIndex['link'] = preg_replace('/\\\\/', '/', $link);
				$searchIndexes[] = $newIndex;
				$i++;
			}
		}
	}
	
	echo ($i ? $i : "No")." routes indexed from file ".$filename."\n";
}

$twig = "{% extends 'default/sami.js.twig' %}\n";
$twig .= "{% block search_index_extra %}\n";
foreach($searchIndexes as $index){
	$twig .= json_encode($index).",\n";
}
$twig .= "{% endblock %}\n";

file_put_contents($sami['template_dirs'][0].'/'.$sami['theme'].'/sami.js.twig', $twig);
