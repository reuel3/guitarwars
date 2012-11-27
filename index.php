<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Guitar Wars - High Scores</title>
  <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
  <h2>Guitar Wars - High Scores</h2>
  <p>Welcome, Guitar Warrior, do you have what it takes to crack the high score list? If so, just <a href="addscore.php">add your own score</a>.</p>
  <hr />

<?php

  require_once('appvars.php');
  require_once('connectvars.php');

  // Connect to the database 
  $dbc = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD)
      or die("Could not connect to database");

  mysql_select_db(DB_NAME);

  // Retrieve the score data from MySQL
  $query = "SELECT * FROM guitarwars where approved = 1 order by score desc, date asc";
  $data = mysql_query($query)
      or die("Could not run query");

  // Loop through the array of score data, formatting it as HTML 
  echo '<table>';
  $i = 0;
  while ($row = mysql_fetch_array($data, MYSQL_ASSOC)) { 
    if ($i == 0) {
      echo '<tr><td colspan="2" class="topscoreheader">Top Score: ' . $row['score'] . '</td></tr>';
    }
    // Display the score data
    echo '<tr><td class="scoreinfo">';
    echo '<span class="score">' . $row['score'] . '</span><br />';
    echo '<strong>Name:</strong> ' . $row['name'] . '<br />';
    echo '<strong>Date:</strong> ' . $row['date'] . '</td>';
    if (is_file(GW_UPLOADPATH . $row['screenshot']) && filesize(GW_UPLOADPATH . $row['screenshot']) > 0) {
      echo '<td><img src="' . GW_UPLOADPATH . $row['screenshot'] . '" alt="Score image" /></td>';
    } else {
      echo '<td><img src="images/unverified.gif" alt="Unverified score" /></td>';
    }
    echo '</tr>';
    $i++;
  }
  echo '</table>';

  mysql_close($dbc);
?>

</body> 
</html>
