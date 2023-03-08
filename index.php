<?php
// Récupérer les données de l'API
$urlExchange = "https://api.freecurrencyapi.com/v1/latest?apikey=t8QApcXRwuOLRhEd1bjFq5vPsYsi48Xze21SKroy&currencies=EUR%2CUSD%2CJPY%2CBGN%2CCZK%2CDKK%2CGBP%2CHUF%2CPLN%2CRON%2CSEK%2CCHF%2CISK%2CNOK%2CHRK%2CRUB%2CTRY%2CAUD%2CBRL%2CCAD%2CCNY%2CHKD%2CIDR%2CILS%2CINR%2CKRW%2CMXN%2CMYR%2CNZD%2CPHP%2CSGD%2CTHB%2CZAR&base_currency=EUR";
$curl = curl_init($urlExchange);
$resp = curl_exec($curl);
// var_dump($resp);

// Convertir le JSON en objet PHP
$dataExchange = json_decode($resp);
var_dump($dataExchange)

// // Accéder aux taux de change
// $taux_EUR_USD = $dataExchange->dataExchange->USD; // taux de change EUR-USD

// // Afficher les taux de change
// echo "Taux de change EUR-USD : " . $taux_EUR_USD;
?>

<!DOCTYPE html>
<html>

<head>
    <title>Currency App</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style.css">
</head>

<body>
    <header>
        <h1>Currency App</h1>
    </header>
<?php
    $urlCurrency = "https://api.freecurrencyapi.com/v1/currencies?apikey=t8QApcXRwuOLRhEd1bjFq5vPsYsi48Xze21SKroy&currencies=EUR%2CUSD%2CJPY%2CBGN%2CCZK%2CDKK%2CGBP%2CHUF%2CPLN%2CRON%2CSEK%2CCHF%2CISK%2CNOK%2CHRK%2CRUB%2CTRY%2CAUD%2CBRL%2CCAD%2CCNY%2CHKD%2CIDR%2CILS%2CINR%2CKRW%2CMXN%2CMYR%2CNZD%2CPHP%2CSGD%2CTHB%2CZAR&base_currency=EUR";
    $dataCurrency = file_get_contents($urlCurrency);
    $dataCurrency = json_decode($dataCurrency);

    $options = '';
    foreach ($dataCurrency->data as $code => $currency) {
    $name = $currency->name_plural . ' (' . $currency->symbol . ')';
    $options .= '<option value="' . $code . '">' . $name . '</option>';
    }

    echo '
    <form action="index.php" method="POST">
    <label for="amount">AMOUNT:</label>
    <input type="number" id="amount" name="amount" placeholder="Enter the Amount">

    <label for="from">From:</label>
    <select id="from" name="from">' . $options . '</select>

    <label for="to">To:</label>
    <select id="to" name="to">' . $options . '</select>

    <button type="submit">Convert</button>
    </form>';
?>
    <div class="result">
        <p>Result: <span id="result"></span></p>
    </div>

</body>

</html>