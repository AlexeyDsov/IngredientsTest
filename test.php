<?php
require dirname(__FILE__).'/conf/config.auto.inc.php';

try {
	$admin = Criteria::create(IngAdmin::dao())->setLimit(1)->get();
	/* @var $admin IngAdmin */
	$product = IngProduct::dao()->getById(1);
	/* @var $product IngProduct */
	
	$admin->getFavoriteProducts()->fetch()->setList(array($product))->save();
	
	var_dump('count: '.count($admin->getFavoriteProducts()->fetch()->getList()));
	var_dump('count: '.count(Criteria::create(IngAdmin::dao())->add(Expression::eq('favoriteProducts.id', '1'))->getList()));
	
	$admin->getFavoriteProducts()->fetch()->setList(array())->save();
	
	var_dump('count: '.count($admin->getFavoriteProducts()->fetch()->getList()));
	var_dump('count: '.count(Criteria::create(IngAdmin::dao())->add(Expression::eq('favoriteProducts.id', '1'))->getList()));
	
	print "finished success\n";
} catch	(Exception $e) {
	var_dump(get_class($e), $e->getMessage(), $e->getCode(), $e->getFile(), $e->getLine(), $e->getTraceAsString());
}