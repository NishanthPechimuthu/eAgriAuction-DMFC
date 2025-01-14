<?php
ob_start(); // Start output buffering
session_start();

include("header.php");
isAuthenticated();
include('../tcpdf/tcpdf.php'); // Include the TCPDF library
// Get data from database or helper functions
$auction_id = $_GET['auction_id'];
$highest_bid = getHighestBid($auction_id); // Get the highest bid
$sUserId = getHighestBidderId($auction_id);
$auction = getAuctionById($auction_id);
$sUser = getUserById($sUserId);
$rUser = getUserById($auction["auctionCreatedBy"]);
// Check if the user has the right to view the invoice
if ($sUserId != $_SESSION["userId"] && $auction["auctionCreatedBy"] != $_SESSION["userId"]) {
    echo '
        <p class="alert alert-warning alert-dismissible fade show d-flex align-items-center"
           role="alert"  data-bs-dismiss="alert"
                  aria-label="Close"
           style="white-space:nowrap; max-width: 100%; overflow-y: auto;">
            The Invoice is not for you.
           r: '.$rUser["userId"].' s:'.$sUserId.' u:'.$_SESSION["userId"].'
        </p>
        <a href="./auctions.php" class="btn btn-primary rounded-pill d-block">Back to home</a>
    ';
    exit();
}


$trans = getInvoiceDetails($sUserId, $auction_id, $highest_bid);

// Prepare the HTML content for the invoice
$html = '
    <style>
    table, tr, td {
        padding: 15px;
    }
    </style>
    <table style="background-color: #222222; color: #fff">
    <tbody>
    <tr>
    <td>
        <h1>INVOICE: #'.htmlspecialchars(explode('.', $trans["transTrackingId"])[1]).'</h1>
    </td>
    <td align="right">
       <img src="./logos/logo.png" height="60px"/><br>
        1/283, Somvarapatti, Udumalpet, Tiruppur, Tamil Nadu - 642205
        <br/>
        <strong>+91-8015864344</strong> | <strong>22ct19nishanth@gmail.com</strong>
    </td>
    </tr>
    </tbody>
    </table>
';
$html .= '
<table>
    <tbody>
        <tr style="padding: 0px;">
            <td>Invoice to<br/>
            <strong>&nbsp;&nbsp;&nbsp;&nbsp;'.htmlspecialchars($sUser["userFirstName"]." ".$sUser["userLastName"]).',</strong><br/>
            The payment has been successfully sent to user @'.$rUser["userName"].' for auction ID '.$auction["auctionId"].'
            </td>
            <td align="right">
            <strong>Total Due: &#8377;'.$highest_bid.'</strong><br/>
            GST NO: 27AAAPA1234A1Z5<br/>
            Invoice Date: '.$formattedDate = date("d-m-Y", strtotime($trans["createdAt"])).'
            </td>
        </tr>
        <tr>
          <td><strong>Transaction:</strong></td>
        </tr>
        <tr style="padding:5px;">
            <td>
               <br>
               <b>From: </b><br>
               <b>Name: </b>'.$sUser["userName"].'
                <br>&nbsp;&nbsp;<b>Card No: </b>'.$trans["transCardNo"].'<br>&nbsp;&nbsp;<strong>Transaction Id: </strong>'.htmlspecialchars(explode('_',explode('.', $trans["transTrackingId"])[0])[1]).'
            </td>
            <td>
                <br>
               <b>To: </b><br>
               <b>Name: </b>'.$rUser["userName"].'
                <br>&nbsp;&nbsp;<b>Account No: </b>'.$trans["transAccountNo"].'<br>&nbsp;&nbsp;<strong>Invoice Id: </strong>'.htmlspecialchars(explode('.', $trans["transTrackingId"])[1]) .'
            </td>
        </tr>
    </tbody>
</table>
';
$html .= '
<table>
    <thead>
        <tr style="font-weight:bold;">
            <th>Item name</th>
            <th>Category</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="border-bottom: 1px solid #222">'.$auction['auctionTitle'].'</td>
            <td style="border-bottom: 1px solid #222">'.htmlspecialchars(getCategoryById($auction["auctionCategoryId"])).'</td>
            <td style="border-bottom: 1px solid #222">&#8377;'.$highest_bid.'</td>
            <td style="border-bottom: 1px solid #222">1</td>
            <td style="border-bottom: 1px solid #222">&#8377;'.$highest_bid.'</td>
        </tr>
    </tbody>
</table>
';
$html .= '
      <p style="text-align: right;"><strong>Grand total: &#8377;'.$highest_bid.'</strong></p>
      <p style="text-align: center;">
        <h2>Thank you for your business.</h2>
      </p>
     <hr>
     <span>&nbsp;&nbsp;This is a digital invoice and does not require any physical signature.</span>
     <hr>
';

// Create new PDF document with default orientation (portrait) and default A4 size
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetMargins(-1, 0, -1);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('eAgri (blk)');
$pdf->SetTitle('Invoice ' . htmlspecialchars(explode('.', $trans["transTrackingId"])[1]));
$pdf->SetSubject('Auction Invoice for ' . htmlspecialchars($auction["auctionTitle"]));
$pdf->SetKeywords(htmlspecialchars($auction["auctionTitle"]) . ', auction, invoice');

// Set font to DejaVu Sans (for rupee symbol support)
$pdf->SetFont('dejavusans', '', 10);

// Add a watermark image
$pdf->AddPage();
$pdf->SetAlpha(0.1); // Set opacity to 10%
$pdf->Image('./logos/logo1.png', 30, 50, 150, 0, '', '', '', false, 300, '', false, false, 0);
$pdf->SetAlpha(1); // Reset opacity back to 100%

// Add HTML content
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 0, 0, true, '', true);

// Output PDF directly for download
$pdf_name = 'invoice_auction_'.$auction_id.'.pdf';
$pdf->Output($pdf_name, 'D'); // Download the PDF
ob_end_flush();