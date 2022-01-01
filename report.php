<?php include 'inc/header.inc'; ?>
<?php include 'query.php'; ?>
<title>تقارير</title>
</head>

<body>
    <?php include 'inc/navbar.inc'; ?>
    <form>
        <button name="view" value="20">20</button>
        <button name="view" value="30">30</button>
        <button name="view" value="60">60</button>
        <button name="view" value="90">90</button>
    </form>
    <form>
        <input type="date" name="d1">
        <input type="date" name="d2">
        <input type="submit" name="btw" value="اضهر">
    </form>
    <div>
        <table>
            <?php
            if (!array_key_exists('btw', $_GET)) {
                echo '<tr>
                <th>التاريخ</th>
                <th>عدد الطلبات</th>
                </tr>';
                $i = 0;
                if (array_key_exists('view', $_GET)) {
                    $c = $_GET['view'];
                } else {
                    $c = 20;
                }
                $min = 0;
                $today = date('Y-m-d', time());
                $t1 = $today;
                $t2 = $today;
                while ($i < $c) {
                    $i++;
                    $t1 = date('Y-m-d', time() - $min);
                    echo '<tr><td>' . $t1 . '</td>
                    <td>' . orderCountByTime($t1, $t1) . '</td></tr>';
                    $min += 86400;
                }
            } else {
                $t1 = $_GET['d1'];
                $t2 = $_GET['d2'];
                echo '<tr><td>' . $t1 . ' to ' . $t2 . '</td>
                <td>' . orderCountByTime($t1, $t2) . '</td></tr>';
            } ?>
        </table>
    </div>
    <?php include 'inc/footer.inc' ?>