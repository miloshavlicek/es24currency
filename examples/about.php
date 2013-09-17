<?

include_once __DIR__.'/../source/class.ES24Currency.php';

$currencyValue = new ES24CurrencyValue;
$currencyValue->setValue(152.25);
$currencyValue->setCurrencyAbbrev('EUR');
$currencyValue->setSymbolShow(true);
$html = $currencyValue->getFormatted();

$currencyValue = new ES24CurrencyValue;
$currencyValue->setValue(15547.00);
$currencyValue->setCurrencyAbbrev('CZK');
$currencyValue->setSymbolShow(true);
$currencyValue->setStrikeDecimal(true);
$currencyValue->setThousandsSep(' ');
$html2 = $currencyValue->getFormatted();

?>
<!DOCTYPE html>
<html>
    <head>
        <title>ES24Datatable</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="description" content="PHP class for work with currencies">
        <meta name="keywords" content="php,currency,class">
        <meta name="author" content="Miloš Havlíèek">
        <style>
            body {
                background: #eee;
                margin-top: 0;
                font-family: Calibri;
            }
            #page {
                margin: 0 auto;
                width: 920px;
                padding: 10px 30px 50px 30px;
                margin: 0 auto;
                background: #fff;
            }
            .paragraph {
                padding-left: 20px;
                width: 500px;
                font-size: 25px;
                color: #444;
            }
            #github {
                position: fixed;
                right: 100px;
                top: 0;
                background-color: #666;
                color: #fff;
                padding: 10px 30px;
                font-size: 25px;
                cursor: pointer;
            }
            #github:hover {
                background-color: #222;
            }
        </style>
    </head>
    <body>
        <div id="github" onclick="window.location.href='https://github.com/miloshavlicek/ES24Currency';">github</div>
        <div id="page">
            <h1>ES24Currency</h1>
            <p class="paragraph">
                Work with currencies in php easily!<br />
                With ES24Currency class you can format, count or round currency values with precision you want.
            <h2>Format Examples</h2>
            <ul>
                <li><?=$html?></li>
                <li><?=$html2?></li>
            </ul>
        </div>
    </body>
</html>
