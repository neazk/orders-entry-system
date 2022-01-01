<?php include 'inc/header.inc'; ?>

<title>delete order</title>
</head>
<?php require_once('query.php'); ?>

<body>
    <?php $carray = orderById($_GET['id']); ?>
    <?php include 'inc/navbar.inc'; ?>
    <form>
        <div>العميل: <?php
                        $customer = customerById($carray['customer_id'])['0'];
                        echo $customer['id'] . '- ' . $customer['phone'] . '\\' . $customer['phone2'] . '\\' . $customer['caddress']; ?></div>
        <div>الخصم <?php echo $carray['discount']; ?></div>
        <div>المجموع
            <?php $ibo = itemsByOId($carray['id']);
            $totalp = 0;
            foreach ($ibo as $k => $v) {
                $totalp += pPriceByIId($ibo[$k]['id']) * $ibo[$k]['quantity'];
            }
            $totalp -= $carray['discount'];
            echo $totalp; ?>
        </div>
        <div>ملاحضات <?php echo $carray['notes']; ?></div>
        <div>التاريح <?php echo date('Y-m-d', strtotime($carray['odate'])); ?></div>
        <div>المنتجات
            <?php
            $items = itemsByOId($_GET['id']);
            $i = 0;
            foreach ($items as $k => $v) {
                $i++;
                productById($items[$k]['product_id']);
                echo productById($items[$k]['product_id'])['product_name'];
                echo ' :' . $items[$k]['quantity'] . '<br>';
            }
            ?>
        </div>
        <div>
            <button name="b1" id="b1" value="<?php echo $carray['id']; ?>" onclick="return confirm('هل انت متأكد انك تريد حذف الطلب؟');"><b> حذف </b></button>
        </div>
    </form>
    <?php if (array_key_exists('b1', $_GET)) {
        deleteOrders($_GET['b1']);
        header('Location: orders.php');
    } ?>

</body>

</html>