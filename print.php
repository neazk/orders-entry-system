<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once('query.php');


$id = $_GET['p6'];
$order = orderById($id);
$customer = customerById($order['customer_id']);
$items = itemsByOId($id);
$i = 0;
$price = 0;
foreach ($items as $k => $v) {
    $i++;
    $products[$i] = productById($items[$k]['product_id'])['product_name'];
    $price = $price + pPriceByIId($items[$k]['id']) * $items[$k]['quantity'];
    $quants[$i] = $items[$k]['quantity'];
    $dcosts[$i] = pDByIId($items[$k]['id']);
}

$price = $price + min($dcosts);

$html = '<body dir="rtl" style="font-family: XBRiyaz"><div style="text-align:left">' . $order['id'] . '<br>' . $order['odate'] . '</div>
    <div>
        <span style="font-family: frutiger">اسم الزبون: </span>
        <span>' . $customer['0']['cname'] . '</span>
        <br><br>';
$html .= '
        <span>عنوان الزبون: </span>
        <span>' . $customer['0']['caddress'] . '</span><br><br>';
if ($customer['0']['providence'] != '') {
    $html .= '
        <span>المحافضة: </span>
        <span>' . $customer['0']['providence'] . '</span><br><br>';
}
$html .= '
        <span>رقم الزبون: </span>
        <span>' . $customer['0']['phone'] . '</span><br><br>';
if ($customer['0']['phone2'] != '') {
    $html .= '
        <span>الرقم2: </span>
        <span>' . $customer['0']['phone2'] . '</span><br><br>';
}
$html .= '
    <span>الملاحضات: </span>
    <span>' . $order['notes'] . '</span><br>';
$html .= '</div><hr>';
$html .= '
    <table border="1" cellspacing="0" cellpadding="4" style="width: 100%">
    <tr style="background-color: #7F8C8D">
        <th></th>
        <th>اسم المنتج</th>
        <th>العدد</th>
    </tr>';

$ii = 0;
foreach ($items as $k => $v) {
    $ii++;
    $html .= '
    <tr>
    <td>' . $ii . '</td>
    <td>' . $products[$ii] . '</td>
    <td>' . $quants[$ii] . '</td>
    </tr>';
}
$html .= '
    </table>
    <hr>';

$html .= '
    <div>
        <span>المجموع: </span>
        <span>' . number_format($price) . ' د.ع</span>
    </div>
    <hr>';

$html .= '
    <div>
    توضيح:
    <br>
    1-غير مسؤلين عن سؤء استخدام المنتج
    <br>
    2-يعتبر هذا الوصل ضمان حق يرجى الاحتفاظ به
    <br>
    3-ضمان المنتج شهر كامل
    </div>
    <br>
    <br>
    <br>
    <br>
    <br><div style="text-align:center">العنوان: بغداد-الدورة-الصحة</div></body>';


$header = array(
    'odd' => array(
        'L' => array(
            'content' => 'السلطان',
            'font-size' => 10,
            'font-style' => 'B',
            'font-family' => 'serif',
            'color' => '#000000'
        ),
        'C' => array(
            'content' => '07700001736',
            'font-size' => 10,
            'font-style' => 'r',
            'font-family' => 'serif',
            'color' => '#000000'
        ),
        'R' => array(
            'content' => '',
            'font-size' => 10,
            'font-style' => 'B',
            'font-family' => 'serif',
            'color' => '#000000'
        ),
        'line' => 1,
    )
);

$defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
$fontDirs = $defaultConfig['fontDir'];

$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];

$mpdf = new \Mpdf\Mpdf([
    'mode' => 'utf-8',
    'format' => 'A5',
    'orientation' => 'P',
    'autoArabic' => true,
    'default_font_size' => 11,
    'fontDir' => array_merge($fontDirs, [
        __DIR__ . '/fonts',
    ]),
    'fontdata' => $fontData + [
        'bar' => [
            'R' => 'BigVesta-Arabic-Regular.ttf',
        ]
    ],
    'useOTL' => 0xFF,
    'useKashida' => 75,
    'default_font' => 'XBRiyaz',
]);
$mpdf->SetHeader($header, 'odd');
$mpdf->AddFontDirectory('fonts');

$mpdf->autoScriptToLang = true;
$mpdf->autoLangToFont = true;
$mpdf->SetDirectionality('rtl');
$mpdf->WriteHTML($html);
$pdfnam = $order['id'] . '.pdf';
$mpdf->Output($pdfnam, 'D');
