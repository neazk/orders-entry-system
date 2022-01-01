<?php
require_once 'query.php';
$prod = $_GET['productid'];
if ($prod == 'none') {
    echo 'اختر منتجا';
} else {
    foreach ($_GET['ck'] as $k => $v) {
        $items = itemsByOId($_GET['ck'][$k]);
        // updateItem($items['id'], $prod, '1');
    }
}
