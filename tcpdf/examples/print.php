<?php
require_once('tcpdf_include.php');
require_once('../../query.php');
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

$fontN =  TCPDF_FONTS::addTTFfont('C:\xampp\htdocs\app\tcpdf\fonts\BigVesta-Arabic-Regular.ttf', 'TrueTypeUnicode', '', 96);

$pdf = new TCPDF('p', 'mm', 'A5', true, 'UTF-8', false);

// set default header data
$pdf->SetHeaderData('', 0, 'شركة السلطان', '07700001736');

// set header and footer fonts
// $pdf->setHeaderFont(array('dejavusans', '', PDF_FONT_SIZE_MAIN));
$pdf->setHeaderFont(array($fontN, '', PDF_FONT_SIZE_MAIN));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
    require_once(dirname(__FILE__) . '/lang/eng.php');
    $pdf->setLanguageArray($l);
}

$lg = array();
$lg['a_meta_charset'] = 'UTF-8';
$lg['a_meta_dir'] = 'rtl';
$lg['a_meta_language'] = 'fa';
$lg['w_page'] = 'page';

// set some language-dependent strings (optional)
$pdf->setLanguageArray($lg);
$pdf->SetFont($fontN, '', 11);

$pdf->AddPage();

$html = '<div style="text-align:left">' . $order['id'] . '<br>' . $order['odate'] . '</div>
<div>
    <span>اسم الزبون: </span>
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
<table border="1" cellspacing="0" cellpadding="4">
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
    <span>' . $price . ' د.ع</span>
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
<br><div style="text-align:center">العنوان: بغداد-الدورة-الصحة</div>';

$pdf->writeHTML($html, true, false, true, false, '');

$pdf->lastPage();
$lpn = '5600';
$pdfnam = $order['id'] . '.pdf';
$pdf->Output($pdfnam, 'I');
