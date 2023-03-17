<?php 

namespace App;

use App\classes\Database;

require '../vendor/autoload.php';
require_once './functions/config.php';
require './functions/functions.php';

$csvFilePath = 'paris.csv';
$datas = readcsv($csvFilePath);

$db = new Database();

$conn = $db->connexion($host, $dbname, $user, $password);

$db->createTable('meteo', $conn);

$donnees = $db->selectAll('meteo', $conn);

if(count($donnees) == 0){
    $db->insertDatasInMeteo('meteo', $conn, $datas);    
}


$dates = $db->select('meteo', $conn, 'date');

if(isset($_GET) && (count($_GET) > 0))
{
    $date = $_GET['date'];
    $dateSelected = $db->selectWhere('meteo', $conn, $date);
}else{
    $date = '';
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meteo - Paris</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <form action="#" method="get" class="w-25">
            <select class="form-select" name="date" id="date">
                <option value="">Selectionner votre date...</option>
                <?php foreach($dates as $date): ?>
                <option value="<?= $date->date; ?>"><?= $date->date; ?></option>
                <?php endforeach; ?>
            </select>
            <button class="form-control btn btn-primary" name="select" type="submit" value="submit">Sélectionner</button>
        </form>
        <div class="card" style="width: 45rem;">
            <img src="./public/images/paris.jpg" width="150" height="450" class="card-img-top" alt="Paris">
            
            <?php foreach($dateSelected as $date): ?>
            <div class="row">
                <div class="card-body col-md-3">
                    <h5 class="card-title"><?= $date->city; ?></h5>
                    <h6 class="card-title text-muted"><?= $date->date; ?></h6>
                    <p class="card-text">
                    <?= $date->resume; ?>
                    </p>
                </div>
                <div class="col-md-8">
                    <?php if($date->period === 'matin'): ?>
                        <img src="./public/images/<?= $date->period ?>.jpg" alt="<?= $date->period ?>">
                    <?php elseif($date->period === 'après-midi'): ?>
                        <img src="./public/images/midi.jpg" alt="Midi">
                    <?php else: ?>
                        <img src="./public/images/<?= $date->period ?>.jpg" alt="<?= $date->period ?>">
                    <?php endif; ?>
                </div>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><?= $date->period ?></li>
                <li class="list-group-item"><?= $date->resume ?></li>
                <li class="list-group-item"><?= $date->tempMin ?></li>
                <li class="list-group-item"><?= $date->tempMax ?></li>
                <li class="list-group-item"><?= $date->comment ?></li>
            </ul>
            
            <?php endforeach; ?>
        </div>
        
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>
</body>
</html>