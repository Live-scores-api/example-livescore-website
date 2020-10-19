# Setup

1. Rename `config.example.ini` to `config.ini`. This will allow `config.php` to
be able to read our configuration settings.
2. Use the MySQL table structure provided in `structure.sql` to create the MySQL
table where the LiveScoreApi class is going to cache the response from the
live-score-api.com endpoints

# Making your first call to get the livescores

```
$LiveScoreApi = new LiveScoreApi(KEY, SECRET, DB_HOST, DB_USER, DB_PASS, DB_NAME);
echo '<pre>';
var_dump($LiveScoreApi->getLivescores());
```
