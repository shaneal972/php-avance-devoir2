<?php 

namespace App;

use App\classes\Database;

require '../vendor/autoload.php';
require './functions/readcsv.php';

$db = new Database();

$db->create();