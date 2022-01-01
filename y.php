<!DOCTYPE html>
<?php require_once 'query.php'; ?>

<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>

<body>

    <?php
    if (customerByPhone("07814855954")) {
    }
    $time = time();
    for ($i = 0; $i < 30; $i++) {
        echo '<th>' . date('d', $time) . '</th>';
        $time -= 86400;
    }
    ?>

</body>

</html>