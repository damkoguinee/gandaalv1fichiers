<?php 
require 'headerv3.php';?>
<!DOCTYPE html>
<html>
<head>
  <!-- Add Bootstrap CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    /* Custom CSS for sticky column */
    .table-sticky {
      overflow-x: auto;
    }

    .table-sticky td:first-child,
    .table-sticky th:first-child {
      position: sticky;
      left: 0;
      z-index: 1;
      background-color: #fff;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="table-sticky" style="height:100vh;">
      <table class="table table-bordered table-striped">
        <thead class="sticky-top bg-light">
          <tr>
            <th>Sticky Header</th>
            <th>Header 2</th>
            <th>Header 3</th>
          </tr>
        </thead>
        <tbody>
          <?php
          // Replace this with your server-side code to fetch data from the database
          // For demonstration purposes, I'm using a simple array
			$prodm=$DB->query('SELECT  *from enseignant left join contact on enseignant.matricule=contact.matricule order by(prenomen)');

          $data = array(
            array('Data 1', 'Data 2', 'Data 3'),
            array('Data 4', 'Data 5', 'Data 6'),
            // Add more data here
          );

          foreach ($prodm as $row) {
            echo '<tr>';
            echo '<td>' . $row->matricule . '</td>';
            echo '<td>' . $row->matricule . '</td>';
            echo '<td>' . $row->matricule . '</td>';
            echo '</tr>';
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Add Bootstrap JS and jQuery -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script>
    // JavaScript to adjust the position of sticky column
    
  </script>
</body>
</html>
