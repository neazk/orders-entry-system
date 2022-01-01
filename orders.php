<?php include 'inc/header.inc'; ?>

<title>orders</title>
<style>
    #rr {
        border: 1px solid black;
        padding-left: 6px;
        padding-right: 6px;
    }
</style>
</head>
<?php require_once('query.php'); ?>

<body>
    <?php include 'inc/navbar.inc'; ?>
    <div>
        <?php
        if (array_key_exists('pid', $_GET)) { ?>
            <table id="rr">
                <tr>
                    <?php
                    $time = time();
                    for ($i = 0; $i < 30; $i++) {
                        echo '<th id="rr">' . date('m-d', $time) . '</th>';
                        $time -= 86400;
                    }
                    ?>
                </tr>
                <tr>
                    <?php
                    $time = time();
                    for ($i = 0; $i < 30; $i++) {
                        echo '<td id="rr">' . itemsCountByPIdDate($_GET['pid'], date('Y-m-d', $time)) . '</td>';
                        $time -= 86400;
                    }
                    ?>
                </tr>
            </table>
        <?php
        } ?>

        <!-- pagination -->
        <?php
        if (array_key_exists('pid', $_GET)) {
            $totalrecords = orderCountByPId($_GET['pid']);
            $url = '&pid=' . $_GET['pid'];
        } elseif (array_key_exists('cid', $_GET)) {
            $totalrecords = orderCountByCId($_GET['cid']);
            $url = '&cid=' . $_GET['cid'];
        } else {
            $totalrecords = orderCount();
            $url = '';
        }
        $limit = 100;
        $page_num = ceil($totalrecords / $limit);
        $page = 1;
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
        }
        $slimit = ($page - 1) * $limit; ?>

        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-end">
                <?php if ($page > 1) { ?>
                    <li class="page-item"><a class="page-link" href="?page=1">الاولى</a></li>
                    <li class="page-item"><a class="page-link" href="?page=<?php echo $page - 1 . $url; ?>">السابق</a></li>
                <?php } ?>
                <?php for ($i = -5; $i <= 5; $i++) : ?>
                    <?php if ($page + $i >= 1 && $page + $i <= $page_num) { ?>
                        <li class="page-item"><a class="page-link" href="<?php echo "?page=" . $page + $i . $url; ?>"><?php echo $page + $i; ?></a></li>
                    <?php } ?>
                <?php endfor; ?>
                <?php if ($page < $page_num) { ?>
                    <li class="page-item"><a class="page-link" href="?page=<?php echo $page + 1 . $url; ?>">التالي</a></li>
                    <li class="page-item"><a class="page-link" href="?page=<?php echo $page_num . $url; ?>">الاخيرة</a></li>
                <?php } ?>
            </ul>
        </nav>

        <div>
            <table class="table table-condensed table-striped">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>الخصم</th>
                        <th>التوصيل</th>
                        <th>المجموع</th>
                        <th>ملاحضات</th>
                        <th>يوم</th>
                        <th>
                            <form action="oadd.php">
                                <button>طلب جديد</button>
                            </form>
                        </th>
                        <form action="bulkdo.php">
                            <th>
                                <button class="b1">حذف بالتحديد</button>
                            </th>
                        </form>
                        <form action="bulkpo.php">
                            <th>
                                <button class="b6">طباعة بالتحديد</button>
                            </th>
                        </form>
                        <th>المنتجات</th>
                        <th>العميل</th>
                    </tr>
                </thead>

                <?php
                if (array_key_exists('pid', $_GET)) {
                    $or = orderByPId($_GET['pid'], $slimit, $limit);
                } elseif (array_key_exists('cid', $_GET)) {
                    $or = orderByCId($_GET['cid'], $slimit, $limit);
                } else {
                    $or = fetchOrdersL($slimit, $limit);
                }

                // show orders table
                $i = 0;
                foreach ($or as $key => $value) {
                    $i++;
                    $ibo = itemsByOId($or[$key]['id']);
                    $totalp = 0;
                    $dda = array();
                    $h = 0;
                    foreach ($ibo as $k => $v) {
                        $dda[$h] = pDByIId($ibo[$k]['id']);
                        $h++;
                        $totalp += pPriceByIId($ibo[$k]['id']) * $ibo[$k]['quantity'];
                    }
                    $totalp += min($dda);
                    $totalp -= $or[$key]['discount']; ?>
                    <tr>
                        <td><?php echo $or[$key]['id']; ?></td>
                        <td><?php echo $or[$key]['discount']; ?></td>
                        <td><?php echo min($dda); ?></td>
                        <td><?php echo $totalp; ?></td>
                        <td><?php echo $or[$key]['notes']; ?></td>
                        <td><?php echo round((time() - strtotime($or[$key]['odate'])) / (60 * 60 * 24)); ?> days ago</td>
                        <td align="center">
                            <form action="oupd.php">
                                <button name="id" value="<?php echo $or[$key]['id']; ?>">تعديل</button>
                            </form>
                        </td>
                        <td align="center">
                            <form action="odel.php">
                                <button class="b1" name="id" value="<?php echo $or[$key]['id']; ?>">حذف</button>
                            </form>
                        </td>
                        <td align="center">
                            <form action="print.php">
                                <button name="p6" value="<?php echo $or[$key]['id']; ?>">طباعة</button>
                            </form>
                        </td>
                        <td>
                            <?php $items = itemsByOId($or[$key]['id']);
                            $i = 0;
                            foreach ($items as $k => $v) {
                                $i++;
                                productById($items[$k]['product_id']);
                                echo productById($items[$k]['product_id'])['product_name'] . ' :' . $items[$k]['quantity'] . '<br>';
                            } ?>
                        </td>
                        <td class="clinks">
                            <?php $customer = customerById($or[$key]['customer_id'])['0']; ?>
                            <a href="<?php echo 'cupd.php?id=' . $customer['id']; ?>"><?php echo $customer['id'] . '- ' . $customer['phone'] . '\\' . $customer['phone2'] . '\\' . $customer['caddress']; ?></a>
                        </td>
                    </tr>
                <?php
                } ?>
            </table>
        </div>
    </div>
</body>

</html>