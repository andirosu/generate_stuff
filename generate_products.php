<?php
use Magento\Framework\App\Bootstrap;
include(dirname(__FILE__).'/../app/bootstrap.php');
$bootstrap = Bootstrap::create(BP, $_SERVER);

$objectManager = $bootstrap->getObjectManager();

$state = $objectManager->get('Magento\Framework\App\State');
$state->setAreaCode('frontend');

for($i=1;$i<=30;$i++) {

    $_product = $objectManager->create('Magento\Catalog\Model\Product');
    $_product->setName("Produs Test nr. $i");
    $_product->setTypeId('simple');
    $_product->setAttributeSetId(4);
    $_product->setSku("prod-test-$i");
    $_product->setWebsiteIds([1]);
    $_product->setVisibility(4);
    $_product->setPrice(rand(1,1000));
    $_product->setStockData([
            'use_config_manage_stock' => 0, //'Use config settings' checkbox
            'manage_stock' => 1, //manage stock
            'min_sale_qty' => 1, //Minimum Qty Allowed in Shopping Cart
            'max_sale_qty' => 100, //Maximum Qty Allowed in Shopping Cart
            'is_in_stock' => 1, //Stock Availability
            'qty' => 100 //qty
        ]
    );
    $_product->save();
    echo "Created #$i".PHP_EOL;
}
?>