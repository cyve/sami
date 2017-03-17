<?php
/**
 * @author Cyril Vermande (madislak@yahoo.fr)
 * @license MIT
 * @copyright All rights reserved 2017 Cyril Vermande
 * @see https://github.com/FriendsOfPHP/Sami
 */

use Sami\Sami;
use Sami\RemoteRepository\GitHubRemoteRepository;
use Sami\Version\GitVersionCollection;
use Sami\Parser\Filter\TrueFilter;
use Symfony\Component\Finder\Finder;

$srcPath = 'src';

$iterator = Finder::create()
	->files()
	->name('*.php')
	->exclude('Resources')
	->exclude('Tests')
	->in($srcPath)
;

$sami = new Sami($iterator, array(
	'theme' => 'cyve',
	'title' => 'Documentation technique',
	'build_dir' => 'documentation',
	'cache_dir' => 'documentation/cache',
));
$sami['filter'] = function () {
	return new TrueFilter();
};
$sami['template_dirs'] = array(__DIR__.'/themes');

// Execute scripts
foreach(glob(__DIR__.'/scripts/*.php') as $filename) {
	include $filename;
}

return $sami;
