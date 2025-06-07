<?php
// Include database configuration
include('config.php');

// Form submission handling
if (isset($_POST['submit'])) {
    // Retrieve payable amount and reading difference
    $payableAmount = isset($_POST['payableAmount']) ? floatval($_POST['payableAmount']) : 0;
    $Readingdifference = isset($_POST['Readingdifference']) ? floatval($_POST['Readingdifference']) : 0;

    // Generate a unique cycle ID
    $cycle = time();

    // Loop through all member inputs
    for ($i = 1; $i <= 9; $i++) {
        $name = isset($_POST["name$i"]) ? $_POST["name$i"] : '';
        $lastReading = isset($_POST["lastReading$i"]) ? floatval($_POST["lastReading$i"]) : 0;
        $newReading = isset($_POST["newReading$i"]) ? floatval($_POST["newReading$i"]) : 0;
        $membersCount = isset($_POST["membersCount$i"]) ? intval($_POST["membersCount$i"]) : 0;

        // Calculate units and prices
        $unit = $newReading - $lastReading;
        $price_per_unit = ($Readingdifference != 0) ? ($payableAmount / $Readingdifference) : 0;
        $total_without_water = $unit * $price_per_unit;

        // Insert member data into the accounts table
        $query = "INSERT INTO accounts (name, lastReading, newReading, membersCount) 
                  VALUES ('$name', '$lastReading', '$newReading', '$membersCount')";
        mysqli_query($db_conn, $query);
        $account_id = mysqli_insert_id($db_conn);

        // Insert bill data
        $bill_query = "INSERT INTO bill (acc_id, unit, price_per_unit, total_without_water, cycle) 
                       VALUES ('$account_id', '$unit', '$price_per_unit', '$total_without_water', '$cycle')";
        mysqli_query($db_conn, $bill_query);
    }

    // Calculate totals
    $total_query = "
        SELECT SUM(total_without_water) AS total_sum, SUM(accounts.membersCount) AS total_members
        FROM bill
        JOIN accounts ON bill.acc_id = accounts.id
        WHERE bill.cycle = '$cycle'";
    $result = mysqli_query($db_conn, $total_query);
    $row = mysqli_fetch_assoc($result);
    $total_of_all_without_water = isset($row['total_sum']) ? floatval($row['total_sum']) : 0;
    $total_members = isset($row['total_members']) ? intval($row['total_members']) : 0;

    $amount_water = $payableAmount - $total_of_all_without_water;
    $water_per_person = ($total_members != 0) ? ($amount_water / $total_members) : 0;

    // Update bill table
    $update_query = "
        UPDATE bill 
        INNER JOIN accounts ON bill.acc_id = accounts.id
        SET 
            bill.amount_water = '$amount_water',
            bill.water_per_person = '$water_per_person',
            bill.water_per_family = accounts.membersCount * '$water_per_person',
            bill.total_bill_for_each_family = (accounts.membersCount * '$water_per_person') + bill.total_without_water
        WHERE bill.cycle = '$cycle'";
    mysqli_query($db_conn, $update_query);
}

// Fetch bill data
$query = "
    SELECT 
        accounts.name, 
        accounts.lastReading, 
        accounts.newReading, 
        bill.unit, 
        bill.price_per_unit AS per_unit_price, 
        accounts.membersCount, 
        bill.water_per_family, 
        bill.total_bill_for_each_family 
    FROM 
        accounts
    INNER JOIN bill ON accounts.id = bill.acc_id
    ";
$result = mysqli_query($db_conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Water Billing</title>
</head>
<body>
    <h1>Enter Water Billing Details</h1>
    <form method="POST">
        <label>Payable Amount:</label>
        <input type="number" name="payableAmount" step="0.01">
        <label>Reading Difference:</label>
        <input type="number" name="Readingdifference" step="0.01">
        <br>
        <?php for ($i = 1; $i <= 9; $i++): ?>
            <h4>Member <?php echo $i; ?></h4>
            <label>Name:</label>
            <input type="text" name="name<?php echo $i; ?>">
            <label>Last Reading:</label>
            <input type="number" name="lastReading<?php echo $i; ?>" step="0.01">
            <label>New Reading:</label>
            <input type="number" name="newReading<?php echo $i; ?>" step="0.01">
            <label>Members Count:</label>
            <input type="number" name="membersCount<?php echo $i; ?>" step="1">
        <?php endfor; ?>
        <button type="submit" name="submit">Submit</button>
    </form>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <h2>Bill Data</h2>
        <table border="1" cellspacing="0" cellpadding="5" width="100%" style="text-align:center">
            <tr>
                <th>Name</th>
                <th>Last Reading</th>
                <th>New Reading</th>
                <th>Unit</th>
                <th>Price per Unit</th>
                <th>Members Count</th>
                <th>Water per Family</th>
                <th>Total Bill</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['lastReading']; ?></td>
                    <td><?php echo $row['newReading']; ?></td>
                    <td><?php echo $row['unit']; ?></td>
                    <td><?php echo $row['per_unit_price']; ?></td>
                    <td><?php echo $row['membersCount']; ?></td>
                    <td><?php echo $row['water_per_family']; ?></td>
                    <td><?php echo $row['total_bill_for_each_family']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No data found.</p>
    <?php endif; ?>
</body>
</html>