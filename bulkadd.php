<?php
require_once('query.php');

// convert arabic and farsi numbers to english && replace +964 with 0
function faTOen($string)
{
    $string = strtr($string, array('۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4', '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9', '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4', '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9'));
    return strtr($string, array("+964 " => "0", "+964" => "0"));
}
// remove spaces
function removeSpaces($string)
{
    return strtr($string, array(" " => ""));
};

if (array_key_exists('txt', $_POST)) {
    $s = $_POST['txt'];
}

function bulkIn($s)
{
    if (array_key_exists('txt', $_POST)) {
        $prodectid = $_POST['productid'];
    }
    $i = 0;
    // split
    $patt = '/(\[\d{1,2}:\d{1,2}\s[PAM]{2}\,\s\d{1,2}\/\d{1,2}\/\d{4}\]\s\+\d{3}\s\d{3}\s\d{3}\s\d{4}:\s)|(\[\d{1,2}:\d{1,2}\s[PAM]{2}\,\s\d{1,2}\/\d{1,2}\/\d{4}\]\s\w+:\s)/';
    $orders = preg_split($patt, $s);
    foreach ($orders as $key => $value) {
        $orders[$key] = faTOen($value);
    }
    $phonesarr = array();
    $message = '<td></td>';
    $chkt = 'checked';
    $lst = 'chklst[]';

    foreach ($orders as $key => $value) {
        $i++;
        $pat = "/0?7[98756]\d{8}/";
        preg_match_all($pat, removeSpaces($orders[$key]), $array);
        $phone[0] = @$array[0][0];
        $phone[1] = $array[0][1] ?? '';
        $address[0] = preg_replace($pat, "", $orders[$key]);
        $caddress = '';


        if (isset($phone[0])) {
            $message = '<td></td>';
            $chkt = 'checked';
            $lst = 'chklst[]';
            if (customerByPhone($phone[0])) {
                $caddress = customerByPhone($phone[0])['0']['caddress'];
                $lastorder = orderByCId(customerByPhone($phone[0])['0']['id'])['0'];
                $days = round((time() - strtotime($lastorder['odate'])) / (60 * 60 * 24));
                $items = itemsByOId($lastorder['id']);
                $itemsstr = '';
                foreach ($items as $k => $v) {
                    $itemsstr .= productById($items[$k]['product_id'])['product_name'] . ',';
                }
                $message = '<td style="color:red;">الزبون موجود ' . $days . ' يوما مضى: ' . $itemsstr . '</td>';
                $lst = 'chklstexist[]';
                $chkt = '';

                if (in_array($phone[0], $phonesarr)) {
                    $message = '<td style="color:orange;">مكرر الزبون موجود ' . $days . ' يوما مضى: ' . $itemsstr . '</td>';
                    $lst = 'chklstexist[]';
                    $chkt = '';
                }
            } elseif (in_array($phone[0], $phonesarr) && !customerByPhone($phone[0])) {
                $message = '<td style="color:orange;">مكرر </td>';
                $chkt = '';
            }
        } else {
            $message = '<td style="color:blue;">لا يوجد رقم</td>';
            $lst = 'chklst[]';
            $chkt = '';
        } ?>


        <tr>
            <td><?php echo $i; ?></td>
            <td><input type="text" size="60" name="adrs<?php echo $i; ?>" value="<?php echo $address[0]; ?>"><br><?php echo $caddress; ?></td>
            <td><input type="text" size="20" name="cname<?php echo $i; ?>"></td>
            <td>
                <input type="number" style="width: 120px;" name="nmbr1<?php echo $i; ?>" value="<?php echo $phone[0]; ?>">
                <input type="number" style="width: 120px;" name="nmbr2<?php echo $i; ?>" value="<?php echo $phone[1]; ?>">
            </td>
            <?php echo $message; ?>
            <td><input type="number" style="width: 100px;" name="dsqunt<?php echo $i; ?>"></td>
            <td><input type="text" name="notes<?php echo $i; ?>"></td>
            <td><select name="prdctid1<?php echo $i; ?>">
                    <?php
                    foreach (fetchActiveProducts() as $prod) {
                        if ($prod['id'] == $prodectid) {
                            echo '<option value="' . $prod['id'] . '" selected>' . $prod['product_name'] . ' ' . $prod['price'] / 1000 . '</option>';
                        } else {
                            echo '<option value="' . $prod['id'] . '">' . $prod['product_name'] . ' ' . $prod['price'] / 1000 . '</option>';
                        }
                    } ?>
                </select><input type="number" name="quatt1<?php echo $i; ?>" value="1" style="width: 50px;">
                <br>
                <select name="prdctid2<?php echo $i; ?>">
                    <option>اختر منتجا</option>
                    <?php
                    foreach (fetchActiveProducts() as $prod) {
                        echo '<option value="' . $prod['id'] . '">' . $prod['product_name'] . ' ' . $prod['price'] / 1000 . '</option>';
                    } ?>
                </select><input type="number" name="quatt2<?php echo $i; ?>" style="width: 50px;">
            </td>
            <td><input type="checkbox" name="<?php echo $lst; ?>" value="<?php echo $i; ?>" <?php echo $chkt; ?>></td>
        </tr>
<?php
        $phonesarr[$key] = @$array[0][0];
    }
}
