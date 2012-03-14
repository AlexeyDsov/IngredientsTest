<?php
require dirname(__FILE__).'/conf/config.auto.inc.php';

try {
	$getAll = function () {
		IngAdmin::dao()->dropIdentityMap();
		IngProduct::dao()->dropIdentityMap();
		return array(
			IngAdmin::dao()->getById(1),
			IngProduct::dao()->getById(1),
		);
	};
	list($admin, $product) = $getAll();
	
	var_dump('count: '.count($admin->getFavoriteProducts()->fetch()->getList()));
	var_dump('count: '.count(Criteria::create(IngAdmin::dao())->add(Expression::eq('favoriteProducts.id', '1'))->getList()));
	
	$admin->getFavoriteProducts()->fetch()->setList(array($product))->save();
	list($admin, $product) = $getAll();
	
	var_dump('count: '.count($admin->getFavoriteProducts()->fetch()->getList()));
	var_dump('count: '.count(Criteria::create(IngAdmin::dao())->add(Expression::eq('favoriteProducts.id', '1'))->getList()));
	
	$admin->getFavoriteProducts()->fetch()->setList(array())->save();
	list($admin, $product) = $getAll();
	
	var_dump('count: '.count($admin->getFavoriteProducts()->fetch()->getList()));
	var_dump('count: '.count(Criteria::create(IngAdmin::dao())->add(Expression::eq('favoriteProducts.id', '1'))->getList()));
	
	print "finished success\n";
} catch	(Exception $e) {
	var_dump(get_class($e), $e->getMessage(), $e->getCode(), $e->getFile(), $e->getLine(), $e->getTraceAsString());
}