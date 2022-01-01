<?php include 'inc/header.inc'; ?>
<?php require_once('query.php'); ?>
<title>edit product</title>
</head>

<body>
    <?php $carray = productById($_GET['id']); ?>
    <?php include 'inc/navbar.inc'; ?>
    <?php if (@$_GET['ppp'] == 'up1') {
        echo '<P style="color:red"><b>الاسم يجب ان لا يكون مكررا</b></P>';
    } ?>
    <form>
        <div><input type="text" name="id" value="<?php echo $carray['id']; ?>" hidden></div>
        <div>الاسم<input type="text" name="product_name" placeholder="product_name" value="<?php echo $carray['product_name']; ?>"></div>
        <div>السعر<input type="text" name="price" placeholder="price" value="<?php echo $carray['price']; ?>"></div>
        <div>التوصيل<input type="text" name="delivery_cost" placeholder="delivery_cost" value="<?php echo $carray['delivery_cost']; ?>"></div>
        <div>الكميه<input type="text" name="quantity" placeholder="quantity" value="<?php echo $carray['quantity']; ?>"></div>
        <div>
            <input type="radio" name="disa" id="disa1" value="1" <?php echo $carray['pdisable'] == 1 ? 'checked' : ''; ?>>
            <label for="disa1" style="color:green;">نشط</label><br>
            <input type="radio" name="disa" id="disa0" value="0" <?php echo $carray['pdisable'] == 1 ? '' : 'checked'; ?>>
            <label for="disa0" style="color:red;">غير نشط</label>
        </div>
        <div>
            <button name="b2" id="b2"><b> حفظ </b></button>
        </div>
    </form>
    <?php if (array_key_exists('b2', $_GET)) {
        if (productByName($_GET['product_name'])['id']) {
            if (productByName($_GET['product_name'])['id'] != $_GET['id']) {
                header('Location: pupd.php?ppp=up1&id=' . $_GET['id']);
            }
        }
        updateProducts($_GET['id'], $_GET['product_name'], $_GET['price'], $_GET['delivery_cost'], $_GET['quantity'], $_GET['disa']);
        header('Location: products.php');
    } ?>
</body>

</html>