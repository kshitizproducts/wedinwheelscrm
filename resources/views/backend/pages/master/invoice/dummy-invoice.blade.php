<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Invoice INV-001</title>
<style>

    @media print {
  body {
    background-color: #1e1e1e !important;
    -webkit-print-color-adjust: exact; /* Chrome & Safari */
    print-color-adjust: exact; /* Firefox */
  }

  .invoice-container {
    background-color: #1e1e1e !important;
    color: #fff !important;
    width: 100%;
    padding: 20px;
    box-shadow: none; /* remove screen shadows */
  }

  table {
    color: #fff !important;
    border-color: #444 !important;
  }

  th, td {
    border-color: #444 !important;
  }

  .print-btn {
    display: none; /* hide buttons on print */
  }
}



  @page { size: A4; margin: 0; }
  body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background: #121212;
    display: flex;
    justify-content: center; /* center horizontally */
    align-items: flex-start; /* top of the page */
    min-height: 100vh;
    color: #fff;
  }

  .invoice-container {
    width: 210mm;
    min-height: 297mm;
    padding: 20mm;
    box-sizing: border-box;
    background-color: #2b2b2b;
    border-radius: 10px;
    margin: 20px 0;
  }

  .header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 2px solid #ffc107;
    padding-bottom: 15px;
    margin-bottom: 20px;
  }

  .header img { height: 80px; }
  .header h2 { color: #ffc107; }

  .bill-invoice { display: flex; justify-content: space-between; margin-bottom: 20px; }
  .bill-to, .invoice-details { width: 48%; }
  .bill-to p, .invoice-details p { margin: 4px 0; font-size: 14px; }

  table { width: 100%; border-collapse: collapse; margin-top: 10px; }
  table, th, td { border: 1px solid #444; }
  th { background-color: #333; color: #ffc107; padding: 10px; font-size: 14px; }
  td { padding: 8px; font-size: 13px; }
  .text-end { text-align: right; }

  .total { margin-top: 20px; display: flex; justify-content: flex-end; }
  .total table { width: 300px; border: none; }
  .total table th, .total table td { border: none; padding: 6px 10px; font-size: 14px; }
  .total table th { color: #ffc107; text-align: left; }
  .total table td { text-align: right; }

  .footer { margin-top: 40px; text-align: center; font-size: 12px; color: #bbb; }

  .btn-print {
    margin-bottom: 20px;
    padding: 8px 15px;
    background-color: #ffc107;
    color: #1b1b1b;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
  }

  @media print {
    body { background: #121212; color: #fff; }
    .invoice-container { background-color: #2b2b2b; color: #fff; box-shadow: none; margin: 0; border-radius: 0; }
    .btn-print { display: none; }
    table, th, td { border-color: #555; }
  }
</style>
</head>
<body>

<div class="invoice-container">
  <button class="btn-print no-print" onclick="window.print()">🖨️ Print / Save PDF</button>

  <div class="header">
    <img src="https://wedinwheels.com/wp-content/uploads/2024/01/wedinwheels-logo.png" alt="Logo">
    <h2>Invoice</h2>
  </div>

  <div class="bill-invoice">
    <div class="bill-to">
      <h4 style="color:#ffc107;">Bill To:</h4>
      <p>Rohit Sharma</p>
      <p>+91 9876543210</p>
      <p>rohit@email.com</p>
    </div>
    <div class="invoice-details">
      <p><strong>Invoice No:</strong> INV-001</p>
      <p><strong>Date:</strong> 05-10-2025</p>
      <p><strong>Status:</strong> Paid</p>
    </div>
  </div>

  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>Item Description</th>
        <th>Quantity</th>
        <th>Unit Price</th>
        <th>Total</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>1</td>
        <td>Wedding Car Rental - Honda City</td>
        <td>1</td>
        <td>₹5,000</td>
        <td>₹5,000</td>
      </tr>
      <tr>
        <td>2</td>
        <td>Wedding Car Rental - Toyota Innova</td>
        <td>1</td>
        <td>₹6,500</td>
        <td>₹6,500</td>
      </tr>
      <tr>
        <td>3</td>
        <td>Decoration & Accessories</td>
        <td>1</td>
        <td>₹2,000</td>
        <td>₹2,000</td>
      </tr>
</tbody>
  </table>

  <div class="total">
    <table>
      <tr>
        <th>Subtotal:</th>
        <td>₹13,500</td>
      </tr>
      <tr>
        <th>Tax (18%):</th>
        <td>₹2,430</td>
      </tr>
      <tr>
        <th>Total:</th>
        <td>₹15,930</td>
      </tr>
    </table>
  </div>

  <div class="footer">
    Thank you for your business! | Wed In Wheels | www.wedinwheels.com
  </div>
</div>

</body>
</html>
