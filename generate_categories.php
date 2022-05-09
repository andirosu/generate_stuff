<?php

use \Magento\Framework\App\Bootstrap;

include(dirname(__FILE__) . '/../app/bootstrap.php');
$bootstrap     = Bootstrap::create(BP, $_SERVER);
$objectManager = $bootstrap->getObjectManager();
$url           = \Magento\Framework\App\ObjectManager::getInstance();
$storeManager  = $url->get('\Magento\Store\Model\StoreManagerInterface');
$mediaurl      = $storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
$state         = $objectManager->get('\Magento\Framework\App\State');
$state->setAreaCode('frontend');
/// Get Website ID
$websiteId = $storeManager->getWebsite()->getWebsiteId();
echo 'websiteId: ' . $websiteId . " ";

/// Get Store ID
$store   = $storeManager->getStore();
$storeId = $store->getStoreId();
echo 'storeId: ' . $storeId . " ";

/// Get Root Category ID
$rootNodeId = $store->getRootCategoryId();
echo 'rootNodeId: ' . $rootNodeId . " ";
/// Get Root Category
$rootCat  = $objectManager->get('Magento\Catalog\Model\Category');
$cat_info = $rootCat->load($rootNodeId);


$roots = [
   32
];
foreach ($roots as $root) {
    $cat_info   = $rootCat->load($root);
    $categories = [];
    for ($i = 0; $i < 7; $i++) {
        $categories[] = uniqid($cat_info->getName() . " ");
    }

    foreach ($categories as $cat) {
        $name            = ucfirst(trim($cat));
        $url             = strtolower($cat);
        $cleanurl        = trim(preg_replace('/ +/', '', preg_replace('/[^A-Za-z0-9 ]/', '', urldecode(html_entity_decode(strip_tags($url))))));
        $categoryFactory = $objectManager->get('\Magento\Catalog\Model\CategoryFactory');
/// Add a new sub category under root category
        $categoryTmp = $categoryFactory->create();
        $categoryTmp->setName($name);
        $categoryTmp->setIsActive(true);
        $categoryTmp->setUrlKey($cleanurl);
        $categoryTmp->setData('description', 'description');
        $categoryTmp->setParentId($cat_info->getId());
//    $mediaAttribute = ['image', 'small_image', 'thumbnail'];
//    $categoryTmp->setImage('/m2.png', $mediaAttribute, true, false);// Path pub/meida/catalog/category/m2.png
        $categoryTmp->setStoreId($storeId);
        $categoryTmp->setPath($cat_info->getPath());
        try {
            $categoryTmp->save();
            echo "saved $name!" . $categoryTmp->getId() . PHP_EOL;
        } catch (Exception $e) {
            \Incognito\Debug\Helper\Dumper::dd($e->getMessage());
        }
    }
}