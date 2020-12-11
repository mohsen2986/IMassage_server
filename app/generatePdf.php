<?php
    require_once base_path('vendor/tcpdf-master/tcpdf.php');


    class MYPDF extends TCPDF {

    //Page header
    public function Header() {
        // Logo
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}
//
//    $result = null;
//    if ($items = ($product->getBasketItemReceipt($token))) {
//        while ($row = $items->fetch_assoc()) {
//            $result["datas"] [] = $row;
//            $receipt += $row['price'];
//            $receipt_offer += $row['offer_price'];
//        }
//    }
//    generate($result["datas"] ,"test" , "mohsen" , "09360332986" , "birjand");
//    echo ($product->getAddressById(1)->fetch_assoc()["address"]);
//    echo ($product->getReciverInfoById(1)->fetch_assoc()["name"]);

function generate($datas)
{
// create new PDF document
    $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // set some language dependent data:
    $lg = Array();
    $lg['a_meta_charset'] = 'UTF-8';
    $lg['a_meta_dir'] = 'rtl';
    $lg['a_meta_language'] = 'fa';
    $lg['w_page'] = 'page';

    // set some language-dependent strings (optional)
    $pdf->setLanguageArray($lg);

    // ---------------------------------------------------------

    // set font
    $pdf->SetFont('dejavusans', '', 12);
    // add a page
    $pdf->AddPage();
    $pdf->Ln(2);
    $pdf->Cell(180, 0, 'صورت حساب فروش مهرکالا', 0, 1, 'C', 0, 'C', 1);
    $pdf->Ln(4);


    $pdf->Ln(12);

    $pdf->SetFillColor(255, 255, 200);
    // set color for text
    $pdf->SetTextColor(0, 0, 0);
    // write the first column
    $pdf->MultiCell(11, 0, "ردیف", 1, 'C', 1, 0, '', '', true, 0, false, true, 0);
    $pdf->MultiCell(45, 0, "نام کاربر", 1, 'C', 1, 0, '', '', true, 0, false, true, 0);
    $pdf->MultiCell(45, 0, "نام خانوادگی", 1, 'C', 1, 0, '', '', true, 0, false, true, 0);
    $pdf->MultiCell(45, 0, "شماره تماس", 1, 'C', 1, 0, '', '', true, 0, false, true, 0);
    $pdf->MultiCell(35, 0, "تاریخ تولد", 1, 'C', 1, 0, '', '', true, 0, false, true, 0);
    $pdf->Ln(7);

    $row_counter = 1;
    $total = 0;

    foreach ($datas as $data) {
        $name = $data["name"];
        $family = $data["family"];
        $phone = $data["phone"];
        $birthday = $data["date"];

        $pdf->Cell(11, 15, "$row_counter", 1);
        $pdf->Cell(45, 15, "$name", 1);
        $pdf->Cell(45, 15, "$family", 1);
        $pdf->Cell(45, 15, "$phone", 1);
        $pdf->Cell(35, 15, "$birthday", 1);

        $pdf->Ln();
        $row_counter++;
    }
    // set LTR direction for english translation
    $pdf->SetFontSize(10);

    // print newline
    $pdf->Ln();

    // Restore RTL direction
    $pdf->setRTL(true);

    // set font
    $pdf->SetFont('aefurat', '', 18);

    // print newline
    $pdf->Ln();


    //Close and output PDF document
    $pdf->Output("users".'.pdf', 'D');


//============================================================+
// END OF FILE
//============================================================+
}
