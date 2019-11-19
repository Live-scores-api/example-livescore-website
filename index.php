<?php

include 'config.php';

$url = "https://livescore-api.com/api-client/scores/live.json?key=".KEY."&secret=" . SECRET;
$json = file_get_contents($url);
$data = json_decode($json, true);
?>

<html>
	<head></head>
	<body>
		<table>
			<tr>
				<td>Time</td>
				<td>Home</td>
				<td>Score</td>
				<td>Away</td>
			</tr>
			<?php foreach ($data['data']['match'] as $_match) { ?>
				<tr>
					<td><?=$_match['time']?></td>
					<td><?=$_match['home_name']?></td>
					<td><?=$_match['score']?></td>
					<td><?=$_match['away_name']?></td>
				</tr>
			<?php } ?>
		</table>
	</body>
</html>