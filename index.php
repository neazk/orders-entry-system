<?php include 'inc/header.inc'; ?>

<title>home</title>
</head>

<?php require_once('Bulkadd.php');
require_once('query.php'); ?>

<body>
    <?php include 'inc/navbar.inc'; ?>
    <form method="POST">
        <?php if (!array_key_exists('go', $_POST)) { ?>
            <div><select name="productid">
                    <option value="none">اختر منتجا</option>
                    <?php foreach (fetchActiveProducts() as $prod) { ?>
                        <option value="<?php echo $prod['id']; ?>"><?php echo $prod['product_name']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <br>
            <textarea name="txt" cols="100" rows="27"></textarea>
            <button name="go" value="go">تحضير الطلبات</button>
        <?php
            //print orders insert count
            if (array_key_exists('iii', $_GET)) {
                echo $_GET['iii'] . ' طلبا اضيف';
            }
        } ?>
    </form>

    <!-- prepare table -->
    <?php
    if (@$_POST['productid'] != 'none') { ?>
        <table class="table table-condensed table-striped">
            <form method="POST">
                <?php if (array_key_exists('go', $_POST)) { ?>
                    <tr>
                        <th></th>
                        <th>عنوان</th>
                        <th>اسم</th>
                        <th>رقم الهاتف</th>
                        <th></th>
                        <th>خصم</th>
                        <th>ملاحضات</th>
                        <th>المنتج</th>
                        <th>ادخال؟</th>
                    </tr>
                    <?php
                    bulkIn($_POST['txt']); ?>
                    <button name="adslct" value="adslct">اضف المحدد</button>
                <?php
                } ?>
            </form>
        </table>
    <?php
    } else { ?>
        <div class="alert">
            <?php echo 'اختر منتجا'; ?>
        </div>
    <?php
    }

    // insert orders
    if (array_key_exists('adslct', $_POST)) {
        $iii = 0;
        if (array_key_exists('chklst', $_POST)) {
            $cl = $_POST['chklst'];
            foreach ($cl as $k => $v) {
                insertCustomers($_POST['cname' . $cl[$k]], $_POST['adrs' . $cl[$k]], '', $_POST['nmbr1' . $cl[$k]], $_POST['nmbr2' . $cl[$k]]);
                insertOrder(maxcid(), $_POST['dsqunt' . $cl[$k]], $_POST['notes' . $cl[$k]]);
                if ($_POST['quatt1' . $cl[$k]] > 0) {
                    insertItems(maxoid(), $_POST['prdctid1' . $cl[$k]], $_POST['quatt1' . $cl[$k]]);
                }
                if ($_POST['quatt2' . $cl[$k]] > 0) {
                    insertItems(maxoid(), $_POST['prdctid2' . $cl[$k]], $_POST['quatt2' . $cl[$k]]);
                }
                $iii++;
            }
        }
        if (array_key_exists('chklstexist', $_POST)) {
            $cle = $_POST['chklstexist'];
            foreach ($cle as $k => $v) {
                $cu = customerByPhone($_POST['nmbr1' . $cle[$k]])['0'];
                updateCustomers($cu['id'], $cu['cname'], $_POST['adrs' . $cle[$k]], $cu['providence'], $_POST['nmbr1' . $cle[$k]], $_POST['nmbr2' . $cle[$k]],);
                insertOrder($cu['id'], $_POST['dsqunt' . $cle[$k]], $_POST['notes' . $cle[$k]]);
                if ($_POST['quatt1' . $cle[$k]] > 0) {
                    insertItems(maxoid(), $_POST['prdctid1' . $cle[$k]], $_POST['quatt1' . $cle[$k]]);
                }
                if ($_POST['quatt2' . $cle[$k]] > 0) {
                    insertItems(maxoid(), $_POST['prdctid2' . $cle[$k]], $_POST['quatt2' . $cle[$k]]);
                }
                $iii++;
            }
        }

        header('Location: index.php?iii=' . $iii);
    }
    ?>

    <?php include 'inc/footer.inc' ?>