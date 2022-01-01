<?php include 'inc/header.inc'; ?>

<title>customers</title>
</head>
<?php require_once('query.php'); ?>

<body>
    <?php include 'inc/navbar.inc'; ?>
    <div>
        <form action="customers.php">
            <input type="text" name="sphone" placeholder="phone">
            <input type="submit" value="search">
        </form>
    </div>

    <!-- pagination -->
    <?php
    $totalrecords = customerCount();
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
                <li class="page-item"><a class="page-link" href="?page=<?php echo $page - 1; ?>">السابق</a></li>
            <?php } ?>
            <?php for ($i = -5; $i <= 5; $i++) : ?>
                <?php if ($page + $i >= 1 && $page + $i <= $page_num) { ?>
                    <li class="page-item"><a class="page-link" href="<?php echo "?page=" . $page + $i; ?>"><?php echo $page + $i; ?></a></li>
                <?php } ?>
            <?php endfor; ?>
            <?php if ($page < $page_num) { ?>
                <li class="page-item"><a class="page-link" href="?page=<?php echo $page + 1; ?>">التالي</a></li>
                <li class="page-item"><a class="page-link" href="?page=<?php echo $page_num; ?>">الاخيرة</a></li>
            <?php } ?>
        </ul>
    </nav>

    <div>
        <table class="table table-condensed table-striped">
            <thead>
                <tr>
                    <th>id</th>
                    <th>الاسم</th>
                    <th>العنوان</th>
                    <th>المحافضة</th>
                    <th>الرقم</th>
                    <th>رقم2</th>
                    <th>التاريخ</th>
                    <th colspan="2">
                        <form action="cadd.php">
                            <button>اضافة زبون</button>
                        </form>
                    </th>
                </tr>
            </thead>
            <?php
            if (array_key_exists('sphone', $_GET)) {
                $snum = strtr($_GET['sphone'], array('۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4', '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9', '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4', '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9'));
                $fc = customerByPhone($snum);
            } else {
                $fc = fetchCustomersL($slimit, $limit);
            }

            // table content
            foreach ($fc as $key => $value) { ?>
                <tr>
                    <td><?php echo $fc[$key]['id']; ?></td>
                    <td><?php echo $fc[$key]['cname']; ?></td>
                    <td><?php echo $fc[$key]['caddress']; ?></td>
                    <td><?php echo $fc[$key]['providence']; ?></td>
                    <td><?php echo $fc[$key]['phone']; ?></td>
                    <td><?php echo $fc[$key]['phone2']; ?></td>
                    <td><?php echo $fc[$key]['cdate']; ?></td>
                    <td>
                        <form action="cupd.php">
                            <button name="id" value="<?php echo $fc[$key]['id']; ?>">تعديل</button>
                        </form>
                    </td>
                    <td>
                        <form action="cdel.php">
                            <button class="b1" name="id" value="<?php echo $fc[$key]['id']; ?>">حذف</button>
                        </form>
                    </td>
                    <td>
                        <form action="orders.php">
                            <button name="cid" value="<?php echo $fc[$key]['id']; ?>">عرض الطلبات <?php echo customerOrderCount($fc[$key]['id']); ?></button>
                        </form>
                    </td>
                </tr>
            <?php }
            ?>
        </table>
    </div>
</body>

</html>