<?php 

$options = getopt('p:');
$prefix = empty($options['p']) 
    ? realpath('.')
    : realpath($options['p']);

if (empty($prefix)) {
    die("Bad prefix. Try again.\n");
}

require $prefix . '/admin/config.php';
//require $prefix . '/system/database/' . DB_DRIVER . '.php';
require $prefix . '/system/library/db.php';

$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$tables = array(
    'address',
    'category',
    'category_attribute',
    'category_attribute_description',
    'category_attribute_group',
    'category_attribute_group_description',
    'category_attribute_group_value',
    'category_attribute_group_value',
    'category_path',
    'category_description',
    'category_to_store',
    'coupon',
    'customer',
    'download',
    'download_description',
    'manufacturer',
    'manufacturer_to_store',
    'product',
    'product_description',
    'product_discount',
    'product_image',
    'product_option',
    'product_option_value',
    'product_related',
    'product_special',
    'product_to_download',
    'product_to_store',
    'review',
    'store',
    'order',
    'order_download',
    'order_history',
    'order_option',
    'order_product',
    'order_status',
    'order_total',
    'product_to_category',
    'coupon_product',
    'attribute',
    'attribute_description',
    'attribute_group_desciption',
    'banner',
    'banner_image',
    'banner_image_description',
    
);

foreach ($tables as $table) {
    $sql = sprintf('TRUNCATE TABLE %s%s', DB_PREFIX, $table);
    printf('%s %s ', $sql, str_repeat('.', 73 - strlen($sql)));
    $db->query($sql);
    echo "Done!\n";
}

?>