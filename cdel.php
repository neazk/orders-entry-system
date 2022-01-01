<?php include 'inc/header.inc'; ?>

<title>delete customer</title>
</head>
<?php require_once('query.php'); ?>

<body>
    <?php $carray = customerById($_GET['id'])['0']; ?>
    <?php include 'inc/navbar.inc'; ?>
    <form>
        <div>الاسم <?php echo $carray['cname'] ?></div>
        <div>العنوان <?php echo $carray['caddress'] ?></div>
        <div>المحافضة <?php echo $carray['providence'] ?></div>
        <div>الرفم <?php echo $carray['phone'] ?></div>
        <div>رقم2 <?php echo $carray['phone2'] ?></div>
        <div>التاريح <?php echo date('Y-m-d', strtotime($carray['cdate'])) ?></div>
        <div>
            <button name="b1" id="b1" value="<?php echo $carray['id'] ?>" onclick="return confirm('هل انت متأكد انك تريد حذف العميل؟');"><b> حذف </b></button>
        </div>
    </form>
    <?php if (array_key_exists('b1', $_GET)) {
        deleteCustomers($_GET['b1']);
        header('Location: customers.php');
    } ?>
</body>

</html>