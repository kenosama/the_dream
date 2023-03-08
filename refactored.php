<?php   
declare(strict_types=1);

function getCurrencies(): array {
    $urlCurrency = "https://api.freecurrencyapi.com/v1/currencies?apikey=t8QApcXRwuOLRhEd1bjFq5vPsYsi48Xze21SKroy&currencies=EUR%2CUSD%2CJPY%2CBGN%2CCZK%2CDKK%2CGBP%2CHUF%2CPLN%2CRON%2CSEK%2CCHF%2CISK%2CNOK%2CHRK%2CRUB%2CTRY%2CAUD%2CBRL%2CCAD%2CCNY%2CHKD%2CIDR%2CILS%2CINR%2CKRW%2CMXN%2CMYR%2CNZD%2CPHP%2CSGD%2CTHB%2CZAR&base_currency=EUR";
    $dataCurrency = file_get_contents($urlCurrency);
    $dataCurrency = json_decode($dataCurrency);
    $options = [];
    foreach ($dataCurrency->data as $code => $currency) {
        $name = $currency->name_plural . ' (' . $currency->symbol . ')';
        $options[$code] = $name;
    }
    return $options;
}

function convertCurrency(): string {
    if (empty($_POST)) {
        return '';
    }

    $from = $_POST['from'];
    $to = $_POST['to'];
    $amount = (float) $_POST['amount'];

    $urlExchange = "https://api.freecurrencyapi.com/v1/latest?apikey=t8QApcXRwuOLRhEd1bjFq5vPsYsi48Xze21SKroy&currencies=$to&base_currency=$from";
    $curl = curl_init($urlExchange);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $resp = curl_exec($curl);
    curl_close($curl);

    $dataExchange = json_decode($resp);
    $taux = $dataExchange->data->$to * $amount;

    return "Taux de change de $amount $from vers $to : $taux $to";
}

$currencies = getCurrencies();
$amount = isset($_POST['amount']) ? (float) $_POST['amount'] : '';

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
    <form action="index.php" method="POST">
        <label for="amount">AMOUNT:</label>
        <input type="number" id="amount" name="amount" placeholder="Enter the Amount" value="<?= $amount ?>">
            <label for="from">From:</label>
    <select id="from" name="from">
        <?php foreach ($currencies as $code => $name): ?>
            <option value="<?= $code ?>"<?= isset($_POST['from']) && $_POST['from'] === $code ? ' selected' : '' ?>><?= $name ?></option>
        <?php endforeach; ?>
    </select>

    <label for="to">To:</label>
    <select id="to" name="to">
        <?php foreach ($currencies as $code => $name): ?>
            <option value="<?= $code ?>"<?= isset($_POST['to']) && $_POST['to'] === $code ? ' selected' : '' ?>><?= $name ?></option>
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
