<?php
// db settings

DBPool::me()->addLink(
	'ing',
	DB::spawn('PgSQL', 'user', 'password', 'address', 'databasename')
);

//cach settings

//session settings
