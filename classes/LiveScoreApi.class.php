<?php
/**
 * Live Score API Client class for accessing the data
 */
class LiveScoreApi {

	protected $_key;
	protected $_secret;

	public $connection = null;
	protected $_baseUrl = "https://livescore-api.com/api-client/";

	/**
	 * Sets up the Live Score API client
	 *
	 * @param string $key your Live Score API key
	 * @param string $secret your Live Score API secret
	 * @param string $host the host address to where your MySQL database is running
	 * @param string $user the MySQL database username
	 * @param string $pass the MySQL database password
	 * @param string $db the name of the MySQL database where we are going to cache responses
	 * @throws InvalidArgumentException if there is a problem with the configuration
	 */
	public function __construct($key, $secret, $host, $user, $pass, $db) {
		if (strlen($key) != 16) {
			throw new InvalidArgumentException('Live Score API key must be 16 characters');
		}

		if (strlen($secret) != 32) {
			throw new InvalidArgumentException('Live Score API secret must be 32 characters');
		}

		if (!$host) {
			throw new InvalidArgumentException('MySQL database host cannot be empty');
		}

		if (!$user) {
			throw new InvalidArgumentException('MySQL database username cannot be empty');
		}

		if (!$db) {
			throw new InvalidArgumentException('MySQL database name cannot be empty');
		}

		$this->_key = $key;
		$this->_secret = $secret;
		$this->connection = mysqli_connect($host, $user, $pass);
		$this->connection->select_db($db);
	}

	/**
	 * Builds the URL with which we can access the Live Score API data
	 *
	 * @param string $endpoint the API endpoint to be accessed
	 * @param array $params the parameters to be provided to the endpoint
	 * @return string the full URL to be called
	 */
	protected function _buildUrl($endpoint, $params) {
		$params['key'] =  $this->_key;
		$params['secret'] = $this->_secret;
		return $this->_baseUrl . $endpoint . '?' . http_build_query($params);
	}

	/**
	 * Gets the live scores
	 *
	 * @param array $params filter para meters
	 * @return array with live scores data
	 */
	public function getLivescores($params = []) {
		$url = $this->_buildUrl('scores/live.json', $params);
		$data = $this->_makeRequest($url);
		return $data['match'];
	}

	/**
	 * Makes the actual HTTP request to the Live Score API services
	 * if possible it will get the data from the cache
	 *
	 * @param string $url the Live Score API endpoint to be called
	 * @return array with data
	 * @throws RuntimeException if there is something wrong with the request
	 */
	protected function _makeRequest($url) {
		$json = $this->_useCache($url);

		if ($json) {
			$data = json_decode($json, true);
		} else {
			$json = file_get_contents($url);
			$data = json_decode($json, true);

			if (!$data['success']) {
				throw new RuntimeException($data['error']);
			}

			$this->_saveCache($url, $json);
		}

		return $data['data'];
	}

	/**
	 * Loads a URLs cached response if it is still applicable
	 *
	 * @param string $url the Live Score API endpoint which response was cached
	 * @return boolean|string false if the cache has become invalid otherwise
	 * the JSON response that was cached
	 */
	protected function _useCache($url) {
		$url = mysqli_escape_string($this->connection, crc32($url));
		$query = "SELECT json FROM cache WHERE url = '$url' AND time > DATE(NOW()-INTERVAL 60 SECOND)";
		$result = mysqli_query($this->connection, $query);
		if (!$result) {
			return false;
		}

		if (!mysqli_num_rows($result)) {
			return false;
		}

		$row = mysqli_fetch_assoc($result);
		return $row['json'];
	}

	/**
	 * Saves a response from the Live Score API endpoints to the
	 * cache table so it can be reused and hourly quote can be
	 * spared
	 *
	 * @param string $url the Live Score API URL that was called
	 * @param strning $json the JSON that was returned by the endpoint
	 */
	protected function _saveCache($url, $json) {
		$url = mysqli_escape_string($this->connection, crc32($url));
		$json = mysqli_escape_string($this->connection, $json);

		$query = "INSERT INTO cache (url, json, time) VALUES ('$url', '$json', NOW())
				ON DUPLICATE KEY UPDATE json = '$json', `time` = NOW()";
		mysqli_query($this->connection, $query);
	}
}

