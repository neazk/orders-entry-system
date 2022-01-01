<?php include 'inc/header.inc'; ?>
<?php require_once('query.php'); ?>
<title>add customer</title>
</head>

<body>
    <?php include 'inc/navbar.inc'; ?>
    <?php if (@$_GET['ppp'] == 'ac1') {
        echo '<P style="color:red"><b>خطأ الرفم موجود مسبقا</b></P>';
    } ?>
    <form>
        <div>name<input type="text" name="name" placeholder="الاسم" value=""></div>
        <div>address<input type="text" name="address" placeholder="العنوان" value=""></div>
        <div>providence<input type="text" name="providence" placeholder="المحافضة" value=""></div>
        <div>phone<input type="number" name="phone1" placeholder="الرقم" value="" min="0" required></div>
        <div>phone2<input type="number" name="phone2" placeholder="رقم2" value="" min="0"></div>
        <div>
            <button name="b0" class="b0" id="b0"><b> ادخال </b></button>
        </div>
    </form>
    <?php if (array_key_exists('b0', $_GET)) {
        if (customerByPhone($_GET['phone1'])) {
            header('Location: cadd.php?ppp=ac1');
        } else {
            insertCustomers($_GET['name'], $_GET['address'], $_GET['providence'], $_GET['phone1'], $_GET['phone2']);
            header('Location: customers.php');
        }
    } ?>
</body>

</html>