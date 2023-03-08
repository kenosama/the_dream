<?php    // PHP open tag
declare(strict_types=1);
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
    $urlCurrency = "https://api.freecurrencyapi.com/v1/currencies?apikey=t8QApcXRwuOLRhEd1bjFq5vPsYsi48Xze21SKroy&currencies=EUR%2CUSD%2CJPY%2CBGN%2CCZK%2CDKK%2CGBP%2CHUF%2CPLN%2CRON%2CSEK%2CCHF%2CISK%2CNOK%2CHRK%2CRUB%2CTRY%2CAUD%2CBRL%2CCAD%2CCNY%2CHKD%2CIDR%2CILS%2CINR%2CKRW%2CMXN%2CMYR%2CNZD%2CPHP%2CSGD%2CTHB%2CZAR&base_currency=EUR";    // URL for exchange rate
    $dataCurrency = file_get_contents($urlCurrency);    // fetch data from the URL and store in the dataCurrency variable
    $dataCurrency = json_decode($dataCurrency);    // Convert data into json

    $options = '';    // Initialize options variable
    foreach ($dataCurrency->data as $code => $currency) {    // Loop over dataCurrency
    $name = $currency->name_plural . ' (' . $currency->symbol . ')';    // name of currency
    $options .= '<option value="' . $code . '" ';    // concatenate to options variable
    if (isset($_POST['from']) && $_POST['from'] === $code) {    // check if post request has been sent
        $options .= 'selected';    // concatenate selected to options variable
    }
    $options .= '>' . $name . '</option>';    // concatenate to options variable
    }

    $amount = '';    // Initialize amount variable
    if (isset($_POST['amount'])) {    // Check if amount has been sent or not
        $amount = (float) $_POST['amount'];    // Cast amount to float and assign it to amount variable
    }

    echo '
    <form action="index.php" method="POST">    
    <label for="amount">AMOUNT:</label>   
    <input type="number" id="amount" name="amount" placeholder="Enter the Amount" value="' . $amount . '">    

    <label for="from">From:</label>    
    <select id="from" name="from">' . $options . '</select>    

    <label for="to">To:</label>   
    <select id="to" name="to">';
    foreach ($dataCurrency->data as $code => $currency) {    // Loop over dataCurrency
        $name = $currency->name_plural . ' (' . $currency->symbol . ')';    // name of currency
        $options .= '<option value="' . $code . '" ';    // concatenate to options variable
        if (isset($_POST['to']) && $_POST['to'] === $code) {    // check if post request has been sent
            $options .= 'selected';    // concatenate selected to options variable
        }
        $options .= '>' . $name . '</option>';    // concatenate to options variable
    }
    echo $options . '</select>   

    <button type="submit">Convert</button>  
    </form>';    // form tag closing
?>
    <div class="result">    
        <p>Result: 
            <span id="result">
                <?php 
                if (!empty($_POST)) {    // check if post request has been sent or not
    // Récupérer les données de l'API
    $from = $_POST['from'];    // Assign from variable
    $to = $_POST['to'];    // Assign to variable
    $amount = (float) $_POST['amount'];    // cast amount to float and assign it to amount variable
    $urlExchange = "https://api.freecurrencyapi.com/v1/latest?apikey=t8QApcXRwuOLRhEd1bjFq5vPsYsi48Xze21SKroy&currencies=$to&base_currency=$from";    // URL for exchange rate
    $curl = curl_init($urlExchange);    // initialize curl to make request
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);    // set curl option
    $resp = curl_exec($curl);    // execute curl request
    curl_close($curl);    // close curl

    // Convertir le JSON en objet PHP
    $dataExchange = json_decode($resp);    // Convert response to json

    // Accéder aux taux de change
    $taux = $dataExchange->data->$to * $amount;    // Calculate exchange rate

    // Afficher les taux de change
    echo "Taux de change de $amount $from vers $to : $taux $to";    // Print message on the console
}
                ?>
            </span>
        </p>
    </div>

</body>

</html>
