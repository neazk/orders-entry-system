<?php include 'inc/header.inc'; ?>

<title>orders</title>

<SCRIPT language="javascript">
    $(function() {
        // add multiple select / deselect functionality
        $("#selectall").click(function() {
            $('.name').attr('checked', this.checked);
        });
        // if all checkbox are selected, then check the select all checkbox
        // and viceversa
        $(".name").click(function() {
            if ($(".name").length == $(".name:checked").length) {
                $("#selectall").attr("checked", "checked");
            } else {
                $("#selectall").removeAttr("checked");
            }
        });
    });
</SCRIPT>
</head>
<?php require_once('query.php'); ?>

<body>
    <?php include 'inc/navbar.inc'; ?>
    <div>

        <!-- pagination -->
        <?php $pp = isset($_GET['page']) ? '&page=' . $_GET['page'] : ''; ?>
        <a href="?limit=35<?php echo $pp; ?>">35</a>
        <a href="?limit=50<?php echo $pp; ?>">50</a>
        <a href="?limit=100<?php echo $pp; ?>">100</a>

        <?php
        $totalrecords = orderCount(true);
        if (isset($_GET['print'])) {
            $totalrecords = orderCount();
        }
        $limit = 50;
        if (isset($_GET['limit'])) {
            $limit = $_GET['limit'];
        }
        $page_num = ceil($totalrecords / $limit);
        $page = 1;
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
        }
        $slimit = ($page - 1) * $limit; ?>

        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-end">
                <?php for ($page = 1; $page <= $page_num; $page++) : ?>
                    <li class="page-item"><a class="page-link" href="<?php echo "?page=" . $page . '&limit=' . $limit; ?>"><?php echo $page; ?></a></li>
                <?php endfor; ?>
            </ul>
        </nav>

        <table class="table table-condensed table-striped">
            <form action="prints.php" method="POST">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectall"></th>
                        <th>id</th>
                        <th>الخصم</th>
                        <th>التوصيل</th>
                        <th>المجموع</th>
                        <th>ملاحضات</th>
                        <th>يوم</th>
                        <th>مطبوع؟</th>

                        <th>
                            <button class="b6">اطبع المحدد</button>
                        </th>
                    </tr>
                </thead>

                <!-- delivery cost and total price -->
                <?php
                if (!isset($_GET['print']) || $_GET['print'] == '0') {
                    $or = fetchOrdersLNoPrint($slimit, $limit);
                }
                // } else {
                //     $or = fetchOrdersL($slimit, $limit);
                // }
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
                        <td><input type="checkbox" class="name" name="ck[]" value="<?php echo $or[$key]['id']; ?>"></td>
                        <td><?php echo $or[$key]['id']; ?></td>
                        <td><?php echo $or[$key]['discount']; ?></td>
                        <td><?php echo min($dda); ?></td>
                        <td><?php echo $totalp; ?></td>
                        <td><?php echo $or[$key]['notes']; ?></td>
                        <td><?php echo round((time() - strtotime($or[$key]['odate'])) / (60 * 60 * 24)); ?> days ago</td>
                        <td><?php echo $or[$key]['oprint']; ?></td>
                        <td>
                            <?php
                            $items = itemsByOId($or[$key]['id']);
                            $i = 0;
                            foreach ($items as $k => $v) {
                                $i++;
                                productById($items[$k]['product_id']);
                                echo productById($items[$k]['product_id'])['product_name'];
                                echo ' :' . $items[$k]['quantity'] . '<br>';
                            } ?>
                        </td>
                        <td>
                            <?php
                            $customer = customerById($or[$key]['customer_id'])['0'];
                            echo $customer['id'] . '- ' . $customer['phone'] . '\\' . $customer['phone2'] . '\\' . $customer['caddress']; ?>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </form>
        </table>
    </div>
</body>

</html>