<?php include 'inc/header.inc'; ?>

<title>orders</title>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>

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
<?php require_once 'query.php'; ?>

<body>
    <?php include 'inc/navbar.inc'; ?>
    <div class="row">
        <table>
            <form action="bulk-upd.php">
                <tr>
                    <th><input type="checkbox" id="selectall"></th>
                    <th>id</th>
                    <th>discount</th>
                    <th>delivery cost</th>
                    <th>final amount</th>
                    <th>notes</th>
                    <th>days</th>
                    <th>
                        <button class="b0">edit selected</button>
                    </th>
                    <th>

                        <select name="productid">
                            <option value="none">اختر منتجا</option>
                            <?php foreach (fetchActiveProducts() as $prod) {
                                echo '<option value="' . $prod['id'] . '">' . $prod['product_name'] . '</option>';
                            } ?>
                        </select>
                    </th>
                </tr>

                <!-- delivery cost and total price -->
                <?php
                $or = fetchOrdersL();
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
                        <td><input type="checkbox" class="name" name="ck[]" value="' . $or[$key]['id'] . '"></td>
                        <td><?php echo $or[$key]['id']; ?></td>
                        <td><?php echo $or[$key]['discount']; ?></td>
                        <td><?php echo min($dda); ?></td>
                        <td><?php echo $totalp; ?></td>
                        <td><?php echo $or[$key]['notes']; ?></td>
                        <td><?php echo round((time() - strtotime($or[$key]['odate'])) / (60 * 60 * 24)); ?> days ago</td>
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
                } ?>
            </form>
        </table>
    </div>
</body>

</html>