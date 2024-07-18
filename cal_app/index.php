<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Simple Calculator</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <div class="container">
    <h1>Simple Calculator</h1>

    <?php
    // Database connection settings
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "calculator_db";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    $result = "";
    $error = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // Retrieve form data
      $operand1 = $_POST['operand1'];
      $operand2 = $_POST['operand2'];
      $operator = $_POST['operator'];

      // Perform calculation
      switch ($operator) {
        case '+':
          $result = $operand1 + $operand2;
          break;
        case '-':
          $result = $operand1 - $operand2;
          break;
        case '*':
          $result = $operand1 * $operand2;
          break;
        case '/':
          if ($operand2 != 0) {
            $result = $operand1 / $operand2;
          } else {
            $error = "Division by zero is not allowed.";
          }
          break;
        default:
          $error = "Invalid operator.";
      }

      if (empty($error)) {
        // Insert calculation into the database
        $sql = "INSERT INTO calculations (operand1, operand2, operator, result) VALUES ($operand1, $operand2, '$operator', $result)";
        if ($conn->query($sql) === TRUE) {
          $success = "New calculation record created successfully.";
        } else {
          $error = "Error: " . $sql . "<br>" . $conn->error;
        }
      }
    }

    // Close the connection
    $conn->close();
    ?>

    <form action="" method="post">
      <input type="number" name="operand1" step="any" required>
      <select name="operator" required>
        <option value="none">none</option>
        <option value="+">+</option>
        <option value="-">-</option>
        <option value="*">*</option>
        <option value="/">/</option>
      </select>
      <input type="number" name="operand2" step="any" required>
      <button type="submit">Calculate</button>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      if (!empty($error)) {
        echo "<p style='color: red;'>Error: $error</p>";
      } else {
        echo "<p>Result: $operand1 $operator $operand2 = $result</p>";
      }
    }
    ?>
  </div>
</body>

</html>