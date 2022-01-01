<?php include 'inc/header.inc'; ?>

<title>delete product</title>
</head>
<?php require_once('query.php'); ?>

<body>
    <?php $carray = productById($_GET['id']); ?>
    <?php include 'inc/navbar.inc'; ?>
    <form>
        <div>الاسم <?php echo $carray['product_name']; ?></div>
        <div>السعر <?php echo $carray['price']; ?></div>
        <div>التوصيل <?php echo $carray['delivery_cost']; ?></div>
        <div>الكمية <?php echo $carray['quantity']; ?></div>
        <?php echo $carray['pdisable'] == 1 ? '<div style="color:green;">active</div>' : '<div style="color:red;">disabled</div>'; ?>
        <div>
            <button name="b1" id="b1" value="<?php echo $carray['id']; ?>" onclick="return confirm('هل انت متأكد انك تريد حذف المنتج؟');"><b> حذف </b></button>
        </div>
    </form>
    <?php if (array_key_exists('b1', $_GET)) {
        deleteProducts($_GET['b1']);
        header('Location: products.php');
    } ?>
</body>

</html>