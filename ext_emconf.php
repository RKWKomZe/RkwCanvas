<?php

/***************************************************************
 * Extension Manager/Repository config file for ext: "rkw_canvas"
 *
 * Auto generated by Extension Builder 2015-04-09
 *
 * Manual updates:
 * Only the data in the array - anything else is removed by next write.
 * "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = [
	'title' => 'RKW Canvas',
	'description' => '',
	'category' => 'misc',
    'author' => 'Christian Dilger',
    'author_email' => 'c.dilger@addorange.de',
	'state' => 'beta',
	'internal' => '',
	'uploadfolder' => '0',
	'createDirs' => '',
	'clearCacheOnLoad' => 0,
	'version' => '9.5.2',
	'constraints' => [
		'depends' => [
            'typo3' => '7.6.0-8.7.99',
			'rkw_basics' => '8.7.80-8.7.99',
			'rkw_mailer' => '9.5.9-9.5.99',
            'rkw_registration' => '8.7.0-8.7.99'
		],
		'conflicts' => [
		],
		'suggests' => [
		],
	],
];
