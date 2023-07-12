<!DOCTYPE html>
<html>
<head>
  <title>Invoice</title>
  <link rel="stylesheet" type="text/css" href="styles.css">
  <style>
    body {
  font-family: Arial, sans-serif;
  margin: 0;
  padding: 20px;
}

.invoice {
  max-width: 800px;
  margin: 0 auto;
  background-color: #f9f9f9;
  padding: 20px;
  border: 1px solid #ccc;
}

h1 {
  text-align: center;
  margin-top: 0;
}

.invoice-details {
  display: flex;
  justify-content: space-between;
}

.left,
.right {
  width: 48%;
}

table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}

th,
td {
  border: 1px solid #ccc;
  padding: 8px;
}

th {
  background-color: #f2f2f2;
}

.total {
  text-align: right;
  margin-top: 20px;
}

p {
  margin: 5px 0;
}

.close-button {
  display: block;
  padding: 10px 20px;
  background-color: #878484;
  border: none;
  color: #fff;
  cursor: pointer;
  border-radius: 4px;
}

</style>
</head>
<body> 
<!-- Container element to hold the snipping GIF -->
<div id="snipping-container"></div>
<button class="close-button" onclick="window.close()">Закрыть</button>

  <div class="invoice">
    <center><span><b>Счет-фактура</b></span><br></center>
   <center> <span><b>OP2023-10-1 от 11.05.2023</b></span><br></center>
    <center><span><b>к договору OP2023-10-1 от 11.05.2023 </b></span><br></center><br><br>
    <div class="invoice-details">
      <div class="left">
        <p><strong>Invoice Number:</strong> INV-001</p>
        <p><strong>Date:</strong> May 12, 2023</p>
      </div>
      <div class="right">
        <p><strong>Customer:</strong> John Doe</p>
        <p><strong>Email:</strong> john.doe@example.com</p>
      </div>
    </div>
    <table>
      <tr>
        <th>Item</th>
        <th>Description</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>Total</th>
      </tr>
      <tr>
        <td>Product 1</td>
        <td>Description 1</td>
        <td>2</td>
        <td>$10.00</td>
        <td>$20.00</td>
      </tr>
      <tr>
        <td>Product 2</td>
        <td>Description 2</td>
        <td>1</td>
        <td>$15.00</td>
        <td>$15.00</td>
      </tr>
    </table>
    <div class="total">
      <p><strong>Total:</strong> $35.00</p>
    </div>
  </div>
  <script src="js/snipping.js"></script>
</body>
</html>
