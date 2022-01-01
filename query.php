<?php

define('DSN', 'mysql:host=localhost;dbname=orders');
define('DBUSER', 'root');
define('DBPASS', '');
define('DBOPTIONS', array(
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
));

try {
    $pdo = new PDO(DSN, DBUSER, DBPASS, DBOPTIONS);
} catch (PDOException $e) {
    echo "connection failed " .  $e->getMessage();
}

/*
************************************************
**********          customers         **********
************************************************
*/

function maxcid()
{
    try {
        global $pdo;
        $sql = "SELECT max(id)FROM customers";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetch()['max(id)'];
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function insertCustomers($cname, $caddress, $providence, $phone1, $phone2)
{
    try {
        $id = maxcid() + 1;
        global $pdo;
        $sql = "INSERT INTO customers(id, cname, caddress, providence, phone, phone2) 
        VALUES (:id, :cname, :caddress, :providence, :phone, :phone2)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id, 'cname' => $cname, 'caddress' => $caddress, 'providence' => $providence, 'phone' => $phone1, 'phone2' => $phone2]);
    } catch (PDOException $e) {
        echo 'c inserting failed: ' . $e->getMessage();
    }
}

function customerById($id)
{
    try {
        global $pdo;
        $sql = "SELECT * FROM customers WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        echo 'fetch failed ' . $e->getMessage();
    }
}

function deleteCustomers($id)
{
    try {
        global $pdo;
        $sql = "DELETE FROM customers WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
    } catch (PDOException $e) {
        echo 'deleting failed ' . $e->getMessage();
    }
}

function updateCustomers($id, $cname, $caddress, $providence, $phone1, $phone2)
{
    try {
        global $pdo;
        $sql = "UPDATE customers SET cname = :cname, caddress = :caddress, providence = :providence, phone = :phone, phone2 = :phone2 WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['cname' => $cname, 'caddress' => $caddress, 'providence' => $providence, 'phone' => $phone1, 'phone2' => $phone2, 'id' => $id]);
    } catch (PDOException $e) {
        echo 'updating failed ' . $e->getMessage();
    }
}

function customerByPhone($phone)
{
    try {
        global $pdo;
        $sql = 'SELECT * FROM customers WHERE phone = :phone OR phone2 = :phone2';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue('phone', $phone);
        $stmt->bindValue('phone2', $phone);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        echo 'searching failed ' . $e->getMessage();
    }
}

function customerCount()
{
    try {
        global $pdo;
        $sql = 'SELECT id FROM customers';
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->rowCount();
    } catch (PDOException $e) {
        echo 'searching failed ' . $e->getMessage();
    }
}

function fetchCustomersL($slimit = 0, $limit = 100)
{
    try {
        global $pdo;
        $sql = 'SELECT * FROM customers ORDER BY id DESC LIMIT ' . $slimit . "," . $limit;
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        echo 'fetching failed ' . $e->getMessage();
    }
}

function fetchCustomers()
{
    try {
        global $pdo;
        $sql = 'SELECT * FROM customers ORDER BY cdate DESC';
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        echo 'fetching failed ' . $e->getMessage();
    }
}

function customerOrderCount($id)
{
    try {
        global $pdo;
        $sql = 'SELECT id FROM orders WHERE customer_id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount();
    } catch (PDOException $e) {
        echo 'count failed ' . $e->getMessage();
    }
}

/*
*************************************************
**********          products          ***********
*************************************************
*/

function maxpid()
{
    try {
        global $pdo;
        $sql = "SELECT max(id) FROM products";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetch()['max(id)'];
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function insertProduct($product_name, $price, $delivery_cost, $quantity)
{
    try {
        $pdisable = 1;
        $id = maxpid() + 1;
        global $pdo;
        $sql = "INSERT INTO products(id, product_name, price, delivery_cost, quantity, pdisable) 
        VALUES (:id, :product_name, :price, :delivery_cost, :quantity, :pdisable)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id, 'product_name' => $product_name, 'price' => $price, 'delivery_cost' => $delivery_cost, 'quantity' => $quantity, 'pdisable' => $pdisable]);
    } catch (PDOException $e) {
        echo 'inserting failed ' . $e->getMessage();
    }
}

function productById($id)
{
    try {
        global $pdo;
        $sql = "SELECT * FROM products WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        echo 'fetch failed ' . $e->getMessage();
    }
}

function productByName($product_name)
{
    try {
        global $pdo;
        $sql = "SELECT * FROM products WHERE product_name = :product_name";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['product_name' => $product_name]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        echo 'fetch failed ' . $e->getMessage();
    }
}

function deleteProducts($id)
{
    try {
        global $pdo;
        $sql = "DELETE FROM products WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
    } catch (PDOException $e) {
        echo 'deleting failed ' . $e->getMessage();
    }
}

function updateProducts($id, $product_name, $price, $delivery_cost, $quantity, $pdisable)
{
    try {
        global $pdo;
        $sql = "UPDATE products SET product_name = :product_name, price = :price, delivery_cost = :delivery_cost, quantity = :quantity, pdisable = :pdisable WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['product_name' => $product_name, 'price' => $price, 'delivery_cost' => $delivery_cost, 'quantity' => $quantity, 'pdisable' => $pdisable, 'id' => $id]);
    } catch (PDOException $e) {
        echo 'updating failed ' . $e->getMessage();
    }
}

function searchProducts($product_name)
{
    try {
        global $pdo;
        $sql = 'SELECT * FROM products WHERE product_name LIKE :product_name';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['product_name' => '%' . $product_name . '%']);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        echo 'searching failed ' . $e->getMessage();
    }
}

function fetchProducts()
{
    try {
        global $pdo;
        $sql = 'SELECT * FROM products ORDER BY product_name ASC';
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        echo 'fetching failed ' . $e->getMessage();
    }
}

function fetchActiveProducts()
{
    try {
        global $pdo;
        $sql = 'SELECT * FROM products WHERE pdisable = "1" ORDER BY product_name ASC';
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        echo 'fetching failed ' . $e->getMessage();
    }
}

/*
************************************************
**********           orders           **********
************************************************
*/

function maxoid()
{
    try {
        global $pdo;
        $sql = "SELECT max(id) FROM orders";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetch()['max(id)'];
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function insertOrder($customer_id, $discount, $notes)
{
    try {
        $id = maxoid() + 1;
        global $pdo;
        $sql = "INSERT INTO orders(id, customer_id, discount, notes) 
        VALUES (:id, :customer_id, :discount, :notes)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id, 'customer_id' => $customer_id, 'discount' => $discount, 'notes' => $notes]);
    } catch (PDOException $e) {
        echo 'inserting failed ' . $e->getMessage();
    }
}

function orderById($id)
{
    try {
        global $pdo;
        $sql = "SELECT * FROM orders WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        echo 'fetch failed ' . $e->getMessage();
    }
}

function deleteOrders($id)
{
    try {
        global $pdo;
        $sql = "DELETE FROM orders WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
    } catch (PDOException $e) {
        echo 'deleting failed ' . $e->getMessage();
    }
}

function oprint1($id)
{
    try {
        global $pdo;
        $sql = "UPDATE orders SET oprint = '1' WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
    } catch (PDOException $e) {
        echo 'updating failed ' . $e->getMessage();
    }
}

function oprint0($id)
{
    try {
        global $pdo;
        $sql = "UPDATE orders SET oprint = '0' WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
    } catch (PDOException $e) {
        echo 'updating failed ' . $e->getMessage();
    }
}

function updateOrders($id, $customer_id, $discount, $notes)
{
    try {
        global $pdo;
        $sql = "UPDATE orders SET customer_id = :customer_id, discount = :discount, notes = :notes WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['customer_id' => $customer_id, 'discount' => $discount, 'notes' => $notes, 'id' => $id]);
    } catch (PDOException $e) {
        echo 'updating failed ' . $e->getMessage();
    }
}

function orderCountByCId($customer_id)
{
    try {
        global $pdo;
        $sql = 'SELECT id FROM `orders` WHERE customer_id = :customer_id';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['customer_id' => $customer_id]);
        return $stmt->rowCount();
    } catch (PDOException $e) {
        echo 'searching failed ' . $e->getMessage();
    }
}

function orderByCId($customer_id, $slimit = 0, $limit = 100)
{
    try {
        global $pdo;
        $sql = 'SELECT * FROM `orders` WHERE customer_id = :customer_id 
        ORDER BY odate DESC LIMIT ' . $slimit . "," . $limit;
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['customer_id' => $customer_id]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        echo 'searching failed ' . $e->getMessage();
    }
}

function orderCountByPId($product_id)
{
    try {
        global $pdo;
        $sql = 'SELECT id FROM `orders` WHERE `id` IN 
        (SELECT `order_id`  FROM `order_items` WHERE `product_id` = :product_id)';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['product_id' => $product_id]);
        return $stmt->rowCount();
    } catch (PDOException $e) {
        echo 'searching failed ' . $e->getMessage();
    }
}

function orderByPId($product_id, $slimit = 0, $limit = 100)
{
    try {
        global $pdo;
        $sql = 'SELECT * FROM `orders` WHERE `id` IN 
        (SELECT `order_id`  FROM `order_items` WHERE `product_id` = :product_id) 
        ORDER BY odate DESC LIMIT ' . $slimit . "," . $limit;
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['product_id' => $product_id]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        echo 'searching failed ' . $e->getMessage();
    }
}

function orderByPhone($phone, $phone2)
{
    try {
        global $pdo;
        $sql = 'SELECT * FROM `orders` WHERE customer_id = (SELECT id FROM customers WHERE customers.phone = :phone OR customers.phone2 = :phone2)';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['phone' => $phone, 'phone' => $phone2]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        echo 'searching failed ' . $e->getMessage();
    }
}

function orderCountByTime($date1, $date2)
{
    try {
        global $pdo;
        $sql = 'SELECT id FROM orders WHERE odate >= :date1 AND odate <= :date2';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['date1' => $date1, 'date2' => $date2]);
        return $stmt->rowCount();
    } catch (PDOException $e) {
        echo 'searching failed ' . $e->getMessage();
    }
}

function orderCount($oprint = false)
{
    $op = '';
    if ($oprint === true) {
        $op = ' WHERE oprint = 0';
    }
    try {
        global $pdo;
        $sql = 'SELECT id FROM orders' . $op;
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->rowCount();
    } catch (PDOException $e) {
        echo 'searching failed ' . $e->getMessage();
    }
}

function fetchOrdersL($slimit = 0, $limit = 100)
{
    try {
        global $pdo;
        $sql = "SELECT * FROM orders ORDER BY id DESC LIMIT " . $slimit . "," . $limit;
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        echo 'fetching failed ' . $e->getMessage();
    }
}

function fetchOrdersLNoPrint($slimit = 0, $limit = 100, $order = 'ASC')
{
    try {
        global $pdo;
        $sql = 'SELECT * FROM orders WHERE oprint = "0" ORDER BY id ' . $order . ' LIMIT ' . $slimit . "," . $limit;
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        echo 'fetching failed ' . $e->getMessage();
    }
}

function fetchOrders()
{
    try {
        global $pdo;
        $sql = 'SELECT * FROM orders ORDER BY id DESC';
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        echo 'fetching failed ' . $e->getMessage();
    }
}

/*
************************************************
**********           items            **********
************************************************
*/

function maxiid()
{
    try {
        global $pdo;
        $sql = "SELECT max(id) FROM order_items";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetch()['max(id)'];
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function insertItems($order_id, $product_id, $quantity)
{
    try {
        $id = maxiid() + 1;
        global $pdo;
        $sql = "INSERT INTO order_items (id, order_id, product_id, quantity) VALUES (:id, :order_id, :product_id, :quantity)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id, 'order_id' => $order_id, 'product_id' => $product_id, 'quantity' => $quantity]);
    } catch (PDOException $e) {
        echo 'fetching failed ' . $e->getMessage();
    }
}

function itemsByOId($order_id)
{
    try {
        global $pdo;
        $sql = 'SELECT * FROM order_items WHERE order_id = :order_id ';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['order_id' => $order_id]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        echo 'searching failed ' . $e->getMessage();
    }
}

function itemsCountByPId($product_id)
{
    try {
        global $pdo;
        $sql = 'SELECT id FROM order_items WHERE product_id = :product_id ';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['product_id' => $product_id]);
        return $stmt->rowCount();
    } catch (PDOException $e) {
        echo 'searching failed ' . $e->getMessage();
    }
}

function itemsCountByPIdDate($product_id, $date)
{
    try {
        global $pdo;
        if (!isset($date)) {
            $date = date('Y-m-d');
        }
        $sql = 'SELECT id FROM order_items WHERE product_id = :product_id AND id IN (SELECT id FROM orders WHERE odate = :odate)';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['product_id' => $product_id, 'odate' => $date]);
        return $stmt->rowCount();
    } catch (PDOException $e) {
        echo 'searching failed ' . $e->getMessage();
    }
}

function pPriceByIId($id)
{
    try {
        global $pdo;
        $sql = "SELECT products.price FROM products WHERE products.id = (SELECT order_items.product_id FROM order_items WHERE order_items.id = :id)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch()['price'];
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function pDByIId($id)
{
    try {
        global $pdo;
        $sql = "SELECT products.delivery_cost FROM products WHERE products.id = (SELECT order_items.product_id FROM order_items WHERE order_items.id = :id)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch()['delivery_cost'];
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function updateItem($id, $product_id, $quantity)
{
    try {
        global $pdo;
        $sql = "UPDATE order_items SET product_id = :product_id, quantity = :quantity WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['product_id' => $product_id, 'quantity' => $quantity, 'id' => $id]);
    } catch (PDOException $e) {
        echo 'updating failed ' . $e->getMessage();
    }
}

function deleteItem($id)
{
    try {
        global $pdo;
        $sql = "DELETE FROM order_items WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
    } catch (PDOException $e) {
        echo 'deleting failed ' . $e->getMessage();
    }
}
