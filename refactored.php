<?php

declare(strict_types=1);    // Enable strict mode
function getCurrencies(): array
{    // function to get currencies and their codes
    $urlCurrency = "https://api.freecurrencyapi.com/v1/currencies?apikey=t8QApcXRwuOLRhEd1bjFq5vPsYsi48Xze21SKroy&currencies=EUR%2CUSD%2CJPY%2CBGN%2CCZK%2CDKK%2CGBP%2CHUF%2CPLN%2CRON%2CSEK%2CCHF%2CISK%2CNOK%2CHRK%2CRUB%2CTRY%2CAUD%2CBRL%2CCAD%2CCNY%2CHKD%2CIDR%2CILS%2CINR%2CKRW%2CMXN%2CMYR%2CNZD%2CPHP%2CSGD%2CTHB%2CZAR&base_currency=EUR";    // API endpoint to get currencies
    $dataCurrency = file_get_contents($urlCurrency);    // get the response from API endpoint
    $dataCurrency = json_decode($dataCurrency);    // Decode json response
    $options = [];    // Initialize options array
    foreach ($dataCurrency->data as $code => $currency) {    // loop over each currency
        $name = $currency->name_plural . ' (' . $currency->symbol . ')';    // Concat name and symbol of currency
        $options[$code] = $name;    // append name and symbol to options array with code as key
    }
    return $options;    // return function options
}
function convertCurrency(): string
{    // function to convert currency
    if (empty($_POST)) {    // check if POST request is empty or not
        return '';    // return empty string
    }
    $from = $_POST['from'];    // get value of "from" index in POST request
    $to = $_POST['to'];    // get value of "to" index in POST request
    $amount = (float) $_POST['amount'];    // get value of "amount" index in POST request, the (float) transform the string answer from the json in floating integer.
    $urlExchange = "https://api.freecurrencyapi.com/v1/latest?apikey=t8QApcXRwuOLRhEd1bjFq5vPsYsi48Xze21SKroy&currencies=$to&base_currency=$from";    // API endpoint to get exchange rate
    $curl = curl_init($urlExchange);    // Initiate curl request
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);    // set the curl option to return response
    $resp = curl_exec($curl);    // execute curl request
    curl_close($curl);    // close curl request
    $dataExchange = json_decode($resp);    // decode json response
    $rate = $dataExchange->data->$to * $amount;    // calculate rate by multiply amount with exchange rate
    return "Taux de change de $amount $from vers $to : $rate $to";    // return the result
}
$currencies = getCurrencies();    // call getCurrencies() and store the response in currencies variable
$amount = isset($_POST['amount']) ? (float) $_POST['amount'] : '';    // get the amount from POST request
?>
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
    <form action="refactored.php" method="POST">
        <label for="amount">AMOUNT:</label>
        <input type="number" id="amount" name="amount" placeholder="Enter the Amount" value="<?= $amount ?>">
        <label for="from">From:</label>
        <select id="from" name="from">
            <?php foreach ($currencies as $code => $name) : ?>
                <option value="<?= $code ?>" <?= isset($_POST['from']) && $_POST['from'] === $code ? ' selected' : '' ?>><?= $name ?></option>
            <?php endforeach; ?>
        </select>

        <label for="to">To:</label>
        <select id="to" name="to"> 
            <?php foreach ($currencies as $code => $name) : ?>
                <option value="<?= $code ?>" <?= isset($_POST['to']) && $_POST['to'] === $code ? ' selected' : '' ?>><?= $name ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Convert</button>
    </form>
    <div class="result">
        <p>Result:
            <span id="result">
                <?= convertCurrency() ?>
            </span>
        </p>
    </div>
</body>
</html>