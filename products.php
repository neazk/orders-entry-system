<?php include 'inc/header.inc'; ?>

<title>products</title>
</head>
<?php require_once('query.php'); ?>

<body>
    <?php include 'inc/navbar.inc'; ?>
    <form action="products.php"><input type="text" name="pname"><input type="submit" value="search"></form>
    <div>
        <table class="table table-condensed table-striped">
            <thead>
                <tr>
                    <th>id</th>
                    <th>اسم المنتج</th>
                    <th>السعر</th>
                    <th>كلفة التوصيل</th>
                    <th>الكمية</th>
                    <th>المباع</th>
                    <th></th>
                    <th colspan="2">
                        <form action="padd.php">
                            <button>منتح جديد</button>
                        </form>
                    </th>
                    <th></th>
                </tr>
            </thead>
            <?php
            if (array_key_exists('pname', $_GET)) {
                $products = searchProducts($_GET['pname']);
            } else {
                $products = fetchProducts();
            }
            foreach ($products as $key => $value) { ?>
                <tr>
                    <td><?php echo $products[$key]['id']; ?></td>
                    <td><?php echo $products[$key]['product_name']; ?></td>
                    <td><?php echo $products[$key]['price']; ?></td>
                    <td><?php echo $products[$key]['delivery_cost']; ?></td>
                    <td><?php echo $products[$key]['quantity']; ?></td>
                    <td><?php echo itemsCountByPId($products[$key]['id']); ?></td>
                    <td><?php echo ($products[$key]['pdisable'] == 0 ? '<div style="color:red;">غير نشط</div>' : '<div style="color:green;">نشط</div>'); ?></td>
                    <td>
                        <form action="pupd.php">
                            <button name="id" value="<?php echo $products[$key]['id'] ?>">تعديل</button>
                        </form>
                    </td>
                    <td>
                        <form action="pdel.php">
                            <button class="b1" name="id" value="<?php echo $products[$key]['id']; ?>">حذف</button>
                        </form>
                    </td>
                    <td>
                        <form action="orders.php">
                            <button name="pid" value="<?php echo $products[$key]['id'] ?>">عرض الطلبات</button>
                        </form>
                    </td>
                </tr>
            <?php
            } ?>
        </table>
    </div>
</body>

</html>