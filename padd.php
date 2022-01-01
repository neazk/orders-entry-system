<?php include 'inc/header.inc'; ?>
<?php require_once('query.php'); ?>
<title>add product</title>
</head>

<body>
    <?php include 'inc/navbar.inc'; ?>
    <?php if (@$_GET['ppp'] == 'ap1') {
        echo '<P style="color:red"><b>add failed: name must be unique!!</b></P>';
    } ?>
    <form>
        <div>الاسم<input type="text" name="product_name" placeholder="product_name" value="" required></div>
        <div>السعر<input type="text" name="price" placeholder="price" value="" required></div>
        <div>التوصيل<input type="text" name="delivery_cost" placeholder="delivery_cost" value="" required></div>
        <div>الكمية<input type="text" name="quantity" placeholder="quantity" value=""></div>
        <div>
            <button name="b0" id="b0"><b> ادخال </b></button>
        </div>
    </form>
    <?php if (array_key_exists('b0', $_GET)) {
        if (productByName($_GET['product_name'])) {
            header('Location: padd.php?ppp=ap1');
        } else {
            insertProduct($_GET['product_name'], $_GET['price'], $_GET['delivery_cost'], $_GET['quantity']);
            header('Location: products.php');
        }
    } ?>
</body>

</html>