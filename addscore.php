<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Guitar Wars - Add Your High Score</title>
  <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
  <h2>Guitar Wars - Add Your High Score</h2>

<?php

  require_once('appvars.php');
  require_once('connectvars.php');

  if (isset($_POST['submit'])) {

    // Grab the score data from the POST
    $name = mysql_real_escape_string(trim($_POST['name']));
    $score = mysql_real_escape_string(trim($_POST['score']));
    $screenshot = mysql_real_escape_string(trim($_FILES['screenshot']['name']));
    $screenshot_type = $_FILES['screenshot']['type'];
    $screenshot_size = $_FILES['screenshot']['size'];

    if (!empty($name) && !empty($score) && !empty($screenshot) && is_numeric($score) && is_string($name) && is_string($screenshot)) {
      if ((($screenshot_type == 'image/gif') || ($screenshot_type == 'image/jpeg') || ($screenshot_type == 'image/pjpeg') || ($screenshot_type == 'image/png'))
        && ($screenshot_size > 0) && ($screenshot_size <= GW_MAXFILESIZE)) {
        if ($_FILES['screenshot']['error'] == 0) {
          $target = GW_UPLOADPATH . $screenshot;
          if (move_uploaded_file($_FILES['screenshot']['tmp_name'], $target)) {

            // Write the data to the database
            $query = "INSERT INTO guitarwars ( date, name, score, screenshot ) VALUES (NOW(), '$name', '$score', '$screenshot')";
            $result = mysql_query($query)
                or die('Error inserting into db');
            // Confirm success with the user
            echo '<p>Thanks for adding your new high score!</p>';
            echo '<p><strong>Name:</strong> ' . $name . '<br />';
            echo '<strong>Score:</strong> ' . $score . '</p>';
            echo '<img src="' . GW_UPLOADPATH . $screenshot . '" /><br />';
            echo '<p><a href="index.php">&lt;&lt; Back to high scores</a></p>';

            // Clear the score data to clear the form
            $name = "";
            $score = "";
            $screenshot = "";

            mysql_close($dbc);
          } else {
            echo '<p class="error">Sorry, there was a problem uploading your screen shot image.</p>';
          }
        }
      } else {
        echo '<p class="error">The screen shot must be a GIF, JPEG, or PNG image file no greater than ' . (GW_MAXFILESIZE / 1024) . ' KB in size.</p>';
      }
      // Try to delete the temporary screen shot image file
      @unlink($_FILES['screenshot']['tmp_name']);
    } else {
      echo '<p class="error">Please enter all of the information to add your high score.</p>';
    }
  }
?>

  <hr />
  <form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <input type="hidden" name="MAX_FILE_SIZE" value="32768" />
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" value="<?php if (!empty($name)) echo $name; ?>" /><br />
    <label for="score">Score:</label>
    <input type="text" id="score" name="score" value="<?php if (!empty($score)) echo $score; ?>" />
    <label for="screenshot">Screen shot:</label>
    <input type="file" id="screenshot" name="screenshot" />
    <hr />
    <input type="submit" value="Add" name="submit" />
  </form>
</body> 
</html>
