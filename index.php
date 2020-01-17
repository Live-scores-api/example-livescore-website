<?php

include 'config.php';
include 'functions.php';
include 'classes/LivescoreApi.class.php';

$Api = new LivescoreApi();
$data = $Api->getLivescores();
$timezone = 'Europe/Istanbul';

?>

<html>
	<head>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	</head>
	<body>
		<div class="container">
      <div class="row">
        <div class="col-md-3">
        </div>
        <div class="col-md-6">
          <table class="table table-bordered">
            <tr class="table-info">
              <th>KO</th>
              <th>Time</th>
              <th>Home</th>
              <th>Score</th>
              <th>Away</th>
            </tr>
            <?php foreach ($data['data']['match'] as $_match) { ?>
              <tr>
                <td><?=convert($_match['scheduled'], $timezone)?></td>
                <td><?=$_match['time']?></td>
                <td style="text-align: right;"><?=$_match['home_name']?></td>
                <td style="text-align: center;"><?=$_match['score']?></td>
                <td><?=$_match['away_name']?></td>
              </tr>
            <?php } ?>
          </table>
        </div>
        <div class="col-md-3">
        </div>
      </div>
    </div>
	</body>
</html>
