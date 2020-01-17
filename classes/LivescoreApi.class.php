<?php

class LivescoreApi {

	public $connection = null;
	protected $_baseUrl = "https://livescore-api.com/api-client/";

	public function __construct() {
		$this->connection = mysqli_connect('localhost', 'root', 'root');
		$this->connection->select_db('ls_videos');
	}

	public function buildUrl($endpoint) {
		return $this->_baseUrl . $endpoint . "key=".KEY."&secret=" . SECRET;
	}

	public function getLivescores() {
		$url = $this->buildUrl('scores/live.json?');
		return $this->makeRequest($url);
	}

	public function makeRequest($url) {
		$cached = $this->useCache($url);
		if ($cached) {
			return json_decode($cache, true);
		}

		$json = file_get_contents($url);
		$this->saveCache($url, $json);

		return json_decode($json, true);
	}

	public function useCache($url) {
		$url = mysqli_escape_string($this->connection, crc32($url));
		$query = "SELECT json FROM cache WHERE url = '$url' AND time > DATE(NOW()-INTERVAL 60 SECOND)";
		$result = mysqli_query($this->connection, $query);

		if (!mysqli_num_rows($result)) {
			return false;
		}

		$row = mysqli_fetch_assoc($result);
		return $row['json'];
	}

	public function saveCache($url, $json) {
		$url = mysqli_escape_string($this->connection, crc32($url));
		$json = mysqli_escape_string($this->connection, $json);

		$query = "INSERT INTO cache (url, json, time) VALUES ('$url', '$json', NOW())
				ON DUPLICATE KEY UPDATE json = '$json', `time` = NOW()";
		mysqli_query($this->connection, $query);
	}
}

