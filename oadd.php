<?php include 'inc/header.inc'; ?>

<title>add order</title>
</head>
<?php require_once 'query.php'; ?>

<body>
    <?php include 'inc/navbar.inc'; ?>
    <br>
    <!-- reset -->
    <form>
        <input type="submit" value="reset">
    </form>

    <!-- search -->
    <form>
        <input type="text" name="kw" placeholder="ابحث بالرقم">
        <input type="submit">
    </form>
    <br><br>

    <!-- new customer -->
    <?php
    if (@$_GET['ppp'] == 'ac1') {
        echo '<P style="color:red"><b>خطأ الرقم موجود مسبقا</b></P>';
    }
    if (array_key_exists("addc", $_GET)) {
        echo '<form>';
        echo '<div>الاسم<input type="text" name="name" placeholder="name" value=""></div>';
        echo '<div>العنوان<input type="text" name="address" placeholder="address" value=""></div>';
        echo '<div>المحافظة<input type="text" name="providence" placeholder="providence" value=""></div>';
        echo '<div>الرقم<input type="number" name="phone1" placeholder="phone1" value=""  min="0" requireds></div>';
        echo '<div>رقم2<input type="number" name="phone2" placeholder="phone2" value=""  min="0"></div>';
        echo '<div><button name="save" id="b0"><b> حفظ </b></button></div>';
        echo '</form>';
    }
    if (array_key_exists('save', $_GET)) {
        if (customerByPhone($_GET['phone1'])) {
            header('Location: oadd.php?ppp=ac1');
        } else {
            insertCustomers($_GET['name'], $_GET['address'], $_GET['providence'], $_GET['phone1'], $_GET['phone2']);
            header('Location: oadd.php');
        }
    } ?>

    <!-- customers list of #5 -->
    <table>
        <tr>
            <th>
                <form><button name="addc" id="addc" value="1">عميل جديد</button></form>
            </th>
            <th>الرقم</th>
            <th>رقم2</th>
            <th>العنوان</th>
        </tr>
        <?php
        $i = 0;
        if (array_key_exists("kw", $_GET)) {
            $snum = strtr($_GET['kw'], array('۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4', '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9', '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4', '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9'));
            $cp = customerByPhone($snum);
            foreach ($cp as $k => $v) {
                $i++; ?>
                <tr>
                    <td>
                        <form><button name="idbc" value="<?php echo $cp[$k]['id']; ?>">اختر</button></form>
                    </td>
                    <td><?php echo $cp[$k]['phone']; ?></td>
                    <td><?php echo $cp[$k]['phone2']; ?></td>
                    <td><?php echo $cp[$k]['caddress']; ?></td>
                </tr>
            <?php
            }
        } else {
            $x = 0;
            $cp = fetchCustomersL(0, 5);
            foreach ($cp as $k => $v) { ?>
                <tr>
                    <td>
                        <form><button name="idbc" value="<?php echo $cp[$k]['id']; ?>">اختر</button></form>
                    </td>
                    <td><?php echo $cp[$k]['phone']; ?></td>
                    <td><?php echo $cp[$k]['phone2']; ?></td>
                    <td><?php echo $cp[$k]['caddress']; ?></td>
                </tr>
        <?php
            }
            $x++;
        } ?>
    </table>

    <!-- order and items -->
    <?php if (array_key_exists("idbc", $_GET)) { ?>

        //items
        <form>
            <table>
                <tr>
                    <th>المنتج</th>
                    <th>العدد</th>
                </tr>
                <?php
                $px = 0;
                $pr = fetchActiveProducts();
                while ($px < 5) {
                    $px++; ?>
                    <tr>
                        <td><select name="product<?php echo $px; ?>" id="product<?php echo $px; ?>">
                                <?php foreach ($pr as $k => $v) { ?>
                                    <option value="<?php echo $pr[$k]['product_name']; ?>"><?php echo $pr[$k]['product_name']; ?></option>
                                <?php } ?>
                            </select></td>
                        <td><input type="number" name="quant<?php echo $px; ?>" min="0" value=""></td>
                    </tr>
                <?php
                } ?>
            </table>
            <br><br>

            //order
            <?php echo $_GET['idbc']; ?>
            <td> <?php echo customerById($_GET['idbc'])[0]['phone']; ?></td>
            <td> <?php echo customerById($_GET['idbc'])[0]['phone2']; ?></td>
            <td> <?php echo customerById($_GET['idbc'])[0]['caddress']; ?></td>
            </tr>
            </div>
            <input type="hidden" name="idbc" value="<?php echo $_GET["idbc"]; ?>">
            <div>الخصم<input type="text" name="discount" placeholder="الخصم" value=""></div>
            <div><?php echo date("Y-m-d", time()) ?></div>
            <div>المجموع javascript</div>
            <div>notes<input type="text" name="notes" placeholder="ملاحضات" value=""></div>
            <div>
                <button name="b0" id="b0"><b> حفظ </b></button>
            </div>
        </form>
    <?php
    } ?>
    <?php if (array_key_exists('b0', $_GET)) {
        if ($_GET['quant1'] != '') {
            insertOrder($_GET['idbc'], $_GET['discount'], $_GET['notes']);
            $id = maxoid();
            insertItems($id, productByName($_GET['product1'])['id'], $_GET['quant1']);
            if ($_GET['quant2'] != '') {
                insertItems($id, productByName($_GET['product2'])['id'], $_GET['quant2']);
            };
            if ($_GET['quant3'] != '') {
                insertItems($id, productByName($_GET['product3'])['id'], $_GET['quant3']);
            };
            if ($_GET['quant4'] != '') {
                insertItems($id, productByName($_GET['product4'])['id'], $_GET['quant4']);
            };
            if ($_GET['quant5'] != '') {
                insertItems($id, productByName($_GET['product5'])['id'], $_GET['quant5']);
            };
        };
        header('Location: orders.php');
    } ?>
</body>

</html>