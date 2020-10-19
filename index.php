<?php

ini_set('display_errors', 'Off');
error_reporting(E_ALL);

require_once 'config.php';
require_once 'classes/LiveScoreApi.class.php';

$LiveScoreApi = new LiveScoreApi(KEY, SECRET, DB_HOST, DB_USER, DB_PASS, DB_NAME);
$scores = $LiveScoreApi->getLivescores();

include 'inc/header.php';
include 'inc/left.php';

foreach ($scores as $_score) { ?>

<div class="match-line">
	<div class="row">
		<div class="col-md-2 time-box">
			<?=$_score['time']?>
		</div>
		<div class="col-md-4 team-name">
			<?=$_score['home_name']?>
		</div>
		<div class="col-md-2 score-box">
			<?=$_score['score']?>
		</div>
		<div class="col-md-4 team-name rigth">
			<?=$_score['away_name']?>
		</div>
	</div>
</div>

<script>
	setInterval(function() {
		window.location.href = window.location.href;
	}, 60000);
</script>

<?php
}

include 'inc/rigth.php';
include 'inc/footer.php';
