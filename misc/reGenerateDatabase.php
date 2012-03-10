<?php
require dirname(dirname(__FILE__)).'/conf/config.auto.inc.php';

try {
	DatabaseGenerator::create()->
		setSchemaPath(PATH_CLASSES . 'Auto/schema.php')->
		setDBName('ing')->
		run();
	
	$loginHelper = new LoginHelperDigest();
	$loginHelper->of('IngAdmin');
	
	$admin = IngAdmin::create()->
		setEmail('alexeydsov@gmail.com')->
		setPasswordHash($loginHelper->getHash('alexeydsov@gmail.com', '123456'))->
		setName('AlexeyDsovv');
	$admin = $admin->dao()->add($admin);
	
	$productTomat = IngProduct::dao()->add(IngProduct::create()->setName('Томатный сок'));
	$productVodka = IngProduct::dao()->add(IngProduct::create()->setName('Хорошая водка'));
	$productBloodMery = IngProduct::dao()->add(IngProduct::create()->setName('Коктель, Кровавая Мэри'));
	
	$receiptBloodMery = IngReceipt::create()->
		setDescription('Взять одну часть томатного сока и одну часть водки, смешать')->
		setProduct($productBloodMery)->
		setName('Коктель, Кровавая Мэри');
	$receiptBloodMery = $receiptBloodMery->dao()->add($receiptBloodMery);
	
	$meryIngredientTomat = IngIngredient::create()->
		setComment('взять одну часть')->
		setCount(1)->
		setReceipt($receiptBloodMery)->
		setProduct($productTomat);
	$meryIngredientTomat->dao()->add($meryIngredientTomat);
	
	$meryIngredientVodka = IngIngredient::create()->
		setComment('взять одну часть')->
		setCount(1)->
		setReceipt($receiptBloodMery)->
		setProduct($productVodka);
	$meryIngredientTomat->dao()->add($meryIngredientVodka);
	
	print "finished success\n";
} catch	(Exception $e) {
	var_dump(get_class($e), $e->getMessage(), $e->getCode(), $e->getFile(), $e->getLine(), $e->getTraceAsString());
}