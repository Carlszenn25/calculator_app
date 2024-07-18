<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Calculator APP</title>
  <link rel="stylesheet" href="stylesss.css">
</head>

<body>
  <div class="container">
    <h1>CALCULATOR</h1>

    <?php
    //database settings
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "calculator_db";

    //database connection
    $con = new mysqli($servername, $username, $password, $dbname);

    //checking connection
    if ($con->connect_error) {
      die("Connection failed" . $con->connect_error);
    }

    $result = "";
    $error = "";


    if ($_SERVER["REQUEST_METHOD"] == "POST") {

      //retrieve the column in the table
      $operand1 = $_POST['operand1'];
      $operator = $_POST['operator'];
      $operand2 = $_POST['operand2'];

      //perform calculation
      if ($operator == '+') {
        $result = $operand1 + $operand2;
      } elseif ($operator == '-') {
        $result = $operand1 - $operand2;
      } elseif ($operator == '*') {
        $result = $operand1 * $operand2;
      } elseif ($operator == '/') {
        if ($operand2 != 0) {
          $result = $operand1 - $operand2;
        } else {
          $error = ": Invalid Calculation";
        }
      } else {
        $error = ": Select Operator";
      }

      if (empty($error)) {

        //insert calculation into the database
        $sql = "INSERT INTO calculations (operand1,operator,operand2,result) VALUES($operand1,'$operator',$operand2,$result)";

        if ($con->query($sql) === TRUE) {
          $success = "CALCULATION RECORDED";
        } else {
          $error = "ERROR" . $sql . "<br>";
          $con->error;
        }
      }
    }

    //close connection
    $con->close();

    ?>
    <form method="post">
      <input type="number" name="operand1" required>
      <select name="operator" id="operator" required>
        <option value="none">none</option>
        <option value="+">+</option>
        <option value="-">-</option>
        <option value="*">*</option>
        <option value="/">/</option>
      </select>
      <input type="number" name="operand2" required>
      <button type="submit">Calculate</button>
    </form>

    <?php

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      if (!empty($error)) {
        echo "<p style ='color: red;'>ERROR $error</p>";
      } else {
        echo "<p>RESULT: $operand1 $operator $operand2 = $result</p>";
      }
    }
    ?>
  </div>
</body>

</html>