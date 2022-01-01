<?php include 'inc/header.inc'; ?>

<title>edit order</title>
</head>
<?php require_once('query.php'); ?>

<body>
    <?php $order = orderById($_GET['id']); ?>
    <?php include 'inc/navbar.inc'; ?>
    <br>
    <form>
        <div><input type="text" name="id" value="<?php echo $order['id']; ?>" hidden></div>
        <div><?php
                $customer = customerById($order['customer_id'])['0'];
                echo $customer['id'] . '- ' . $customer['phone'] . '\\' . $customer['phone2'] . '\\' . $customer['caddress']; ?> </div>


        <div>الخصم <input type="text" name="discount" placeholder="discount" value="<?php echo $order['discount']; ?>"></div>
        <div>المجموع
            <?php $ibo = itemsByOId($order['id']);
            $totalp = 0;
            foreach ($ibo as $k => $v) {
                $totalp += pPriceByIId($ibo[$k]['id']) * $ibo[$k]['quantity'];
            }
            $totalp -= $order['discount'];
            echo $totalp;
            ?>
        </div>
        <div>ملاحضات<input type="text" name="notes" placeholder="notes" value="<?php echo $order['notes']; ?>"></div>
        <div>التاريخ <?php echo $order['odate']; ?></div>
        <div>
            <?php
            $items = itemsByOId($order['id']);
            $i = 0;
            foreach ($items as $k => $v) {
                $i++;
                echo '<br><input type="hidden" name="itemid' . $i . '" value="' . $items[$k]['id'] . '">';
                echo '<select name="prdctid' . $i . '">';
                foreach (fetchActiveProducts() as $prod) {
                    if ($prod['id'] == $items[$k]['product_id']) {
                        echo '<option value="' . $prod['id'] . '" selected>' . $prod['product_name'] . '</option>';
                    } else {
                        echo '<option value="' . $prod['id'] . '">' . $prod['product_name'] . '</option>';
                    }
                }
                echo '</select>';
                echo '<input type="number" name="quatt' . $i . '" value="' . $items[$k]['quantity'] . '" style="width: 50px;">';
                if ($i > 1) {
                    echo '<a href="oupd.php?id=' . $order['id'] . '&idel=' . $items[$k]['id'] . '">حذف</a>';
                }
            } ?>
            <div>
                <!-- <a href="oupd.php?id=<?php echo $order['id']; ?>">اضف</a> -->
            </div>
        </div><br>
        <div>
            <button name="b2" id="b2"><b> حفظ </b></button>
        </div>
    </form>


    <?php
    if (array_key_exists('idel', $_GET)) {
        deleteItem($_GET['idel']);
        header('Location: oupd.php?id=' . $order["id"]);
    }

    if (array_key_exists('b2', $_GET)) {
        updateOrders($_GET['id'], $order['customer_id'], $_GET['discount'], $_GET['notes']);
        $items = itemsByOId($_GET['id']);
        $i = 0;
        foreach ($items as $k => $v) {
            $i++;
            updateItem($_GET['itemid' . $i], $_GET['prdctid' . $i], $_GET['quatt' . $i]);
        }
        header('Location: orders.php');
    } ?>
</body>

</html>