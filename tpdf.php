<?php
require 'pdfcrowd/pdfcrowd.php';

function generatePDF()
{

    try {
        // build the url and remove the pdf field from the query string
        $url = "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["PHP_SELF"];
        if (count($_GET) > 1) {
            unset($_GET["pdf"]);
            $url = $url . "?" . http_build_query($_GET, '', '&');
        }

        // call the API
        $client = new \Pdfcrowd\HtmlToPdfClient("username", "apikey");
        $pdf = $client->convertUrl($url);

        // send the generated pdf to the browser
        header("Content-Type: application/pdf");
        header("Cache-Control: no-cache");
        header("Accept-Ranges: none");
        header("Content-Disposition: attachment; filename=\"created.pdf\"");

        echo $pdf;
    }
    catch(\Pdfcrowd\Error $why) {
        fwrite(STDERR, "Pdfcrowd Error: {$why}\n");
    }

    return True;
}?>