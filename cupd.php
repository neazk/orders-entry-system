<?php include 'inc/header.inc'; ?>

<title>edit customer</title>
</head>
<?php require_once('query.php'); ?>

<body>
    <?php $carray = customerById($_GET['id'])['0']; ?>
    <?php include 'inc/navbar.inc'; ?>
    <?php if (@$_GET['ppp'] == 'uc1') {
        echo '<P style="color:red"><b>edit failed: phone must be unique!!</b></P>';
    } ?>
    <form>
        <div><input type="text" name="id" value="<?php echo $carray['id'] ?>" hidden></div>
        <div>id <?php echo $carray['id'] ?></div>
        <div>الاسم<input type="text" name="name" placeholder="name" value="<?php echo $carray['cname'] ?>"></div>
        <div>العنوان<input type="text" name="address" placeholder="address" value="<?php echo $carray['caddress'] ?>"></div>
        <div>المحافضة<input type="text" name="providence" placeholder="providence" value="<?php echo $carray['providence'] ?>"></div>
        <div>الرقم<input type="text" name="phone1" placeholder="phone1" value="<?php echo $carray['phone'] ?>"></div>
        <div>رقم2<input type="text" name="phone2" placeholder="phone2" value="<?php echo $carray['phone2'] ?>"></div>
        <div>التاريح <?php echo $carray['cdate'] ?></div>
        <div>
            <button name="b2" id="b2"><b> تعديل </b></button>
        </div>
    </form>
    <?php if (array_key_exists('b2', $_GET)) {
        if (customerByPhone($_GET['phone1'])['0']['id'] != $_GET['id']) {
            header('Location: cupd.php?ppp=uc1&id=' . $_GET['id']);
        } else {
            updateCustomers($_GET['id'], $_GET['name'], $_GET['address'], $_GET['providence'], $_GET['phone1'], $_GET['phone2']);
            header('Location: customers.php');
        }
    } ?>
</body>

</html>