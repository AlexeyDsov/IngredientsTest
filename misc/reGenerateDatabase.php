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
	$productLime = IngProduct::dao()->add(IngProduct::create()->setName('Лайм'));
	$productLemon = IngProduct::dao()->add(IngProduct::create()->setName('Лимон'));
	$productSugar = IngProduct::dao()->add(IngProduct::create()->setName('Сахар'));
	$productWater = IngProduct::dao()->add(IngProduct::create()->setName('Вода'));
	$productRum = IngProduct::dao()->add(IngProduct::create()->setName('Ром'));
	$productMint = IngProduct::dao()->add(IngProduct::create()->setName('Мята'));
	$productSoda = IngProduct::dao()->add(IngProduct::create()->setName('Содовая'));
	$productMojito = IngProduct::dao()->add(IngProduct::create()->setName('Мохито'));
	$productLemonade = IngProduct::dao()->add(IngProduct::create()->setName('Лимонад (лимонный)'));
	
	$receiptsList = array(
		$productBloodMery->getId() => array(
			"Коктейль Кровавая Мэри",
			'Взять одну часть томатного сока и одну часть водки, смешать',
			array($productVodka, null, 1, 'взять одну часть'),
			array($productTomat, null, 1, 'взять одну часть'),
		),
		$productMojito->getId() => array(
			'Коктейль мохито',
			'50мл рома, 12 листиков мяты, 1/2 лайма, 15мл сахарного сиропа, 3-5 капель настойки Agnostura Bitter, Содовая',
			array($productLime, null, 0.5, '1/2 лайма'),
			array($productSugar, 15, null, '15мл сахарного сиропа'),
			array($productSoda, null, null, 'содовая'),
			array($productMint, null, 12, '12 листиков мяты'),
			array($productRum, 50, null, '50мл рома'),
		),
		$productLemonade->getId() => array(
			'Лимонад (Лимонный)',
			'Выжать сок лимона в воду',
			array($productLime, null, 0.5, '1/2 лайма'),
			array($productSugar, null, 15, '15мл сахарного сиропа'),
		),
	);
	
	foreach ($receiptsList as $receiptProductId => $receiptRow) {
		$receiptName = array_shift($receiptRow);
		$receiptDescription = array_shift($receiptRow);
		
		$receipt = IngReceipt::create()
			->setProductId($receiptProductId)
			->setDescription($receiptDescription)
			->setName($receiptName);
		$receipt = $receipt->dao()->add($receipt);
		
		foreach ($receiptRow as $ingredientRow) {
			$ingredient = IngIngredient::create()
				->setReceipt($receipt)
				->setProduct($ingredientRow[0])
				->setWeight($ingredientRow[1])
				->setCount($ingredientRow[2])
				->setComment($ingredientRow[3]);
			$ingredient->dao()->add($ingredient);
		}
	}
	
	print "finished success\n";
} catch	(Exception $e) {
	var_dump(get_class($e), $e->getMessage(), $e->getCode(), $e->getFile(), $e->getLine(), $e->getTraceAsString());
}