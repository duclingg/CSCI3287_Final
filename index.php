<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            background-color: #CFB87C;
        }
        h1, p {
            text-align: center;
            font-family: "Arial";
        }
        h1 {
            font-weight: bold;
            text-transform: uppercase;
        }
    </style>
    <title>APARTMENT COMPLEX DATABASE</title>
</head>
<body>
    <h1>APARTMENT COMPLEX DATABASE</h1>
    <p>Justin Hoang</p>
    <p>CSCI 3287</p>
    <p>Parcheta</p>

    <?php
    // Database connection code
    $servername = "csci3287.cse.ucdenver.edu";
    $username = "hoangdu";
    $password = "wr46ew2A43wb";
    $dbname = "hoangdu_DB";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Tenant Information
    $tenant = "SELECT TENANT.FName AS 'First Name', 
    TENANT.LName AS 'Last Name', 
    TENANT.TenantPhone AS 'Phone #', 
    TENANT.TenantEmail AS 'Email', 
    LEASE.ApartmentNo AS 'Apartment #' 
    FROM TENANT, LEASE 
    WHERE TENANT.LeaseID = LEASE.LeaseID 
    ORDER BY LEASE.ApartmentNo ASC";

    $tenantResult = $conn->query($tenant);

    // Display the results
    if ($tenantResult->num_rows > 0) {
        echo "<table border='1'>
                <h2>Tenant Information</h2>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Phone #</th>
                    <th>Email</th>
                    <th>Apartment #</th>
                </tr>";
        while ($row = $tenantResult->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row['First Name'] . "</td>
                    <td>" . $row['Last Name'] . "</td>
                    <td>" . $row['Phone #'] . "</td>
                    <td>" . $row['Email'] . "</td>
                    <td>" . $row['Apartment #'] . "</td>
                </tr>";
        }
        echo "</table>";
    } else {
        echo "0 results";
    }

    // Late Rent Payment List
    $late = "SELECT TENANT.FName AS 'First Name', 
    TENANT.LName AS 'Last Name', 
    TENANT.TenantEmail AS 'Email', 
    LEASE.LeaseID, 
    TRANSACTION.RentDueDate AS 'Due Date', 
    TRANSACTION.TDate AS 'Payment Date', TRANSACTION.TotalCost + 75 AS 'Rent Due with Late Fee $', 
    TIMESTAMPDIFF( MONTH, LEASE.StartDate, LEASE.EndDate ) AS 'Lease Term in Months' 
    FROM TENANT, LEASE, TRANSACTION 
    WHERE TENANT.LeaseID = LEASE.LeaseID 
    AND TENANT.TenantEmail = TRANSACTION.TenantEmail 
    AND (TRANSACTION.TDate IS NULL OR TRANSACTION.TDate > TRANSACTION.RentDueDate) 
    ORDER BY LEASE.LeaseID ASC";

    $lateResult = $conn->query($late);

    // Display the results
    if ($lateResult->num_rows > 0) {
        echo "<table border='1'>
                <h2>December Late Rent Payment List</h2>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>LeaseID</th>
                    <th>Due Date</th>
                    <th>Payment Date</th>
                    <th>Rent Due with Late Fee $</th>
                    <th>Lease Term in Months</th>
                </tr>";
        while ($row = $lateResult->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row['First Name'] . "</td>
                    <td>" . $row['Last Name'] . "</td>
                    <td>" . $row['Email'] . "</td>
                    <td>" . $row['LeaseID'] . "</td>
                    <td>" . $row['Due Date'] . "</td>
                    <td>" . $row['Payment Date'] . "</td>
                    <td>" . $row['Rent Due with Late Fee $'] . "</td>
                    <td>" . $row['Lease Term in Months'] . "</td>
                </tr>";
        }
        echo "</table>";
    } else {
        echo "0 results";
    }

    // Lease Expiration Status
    $expiry = "SELECT TENANT.FName AS 'First Name', 
    TENANT.LName AS 'Last Name', 
    TENANT.TenantEmail AS 'Email', 
    LEASE.LeaseID, 
    LEASE.StartDate AS 'Lease Start Date', 
    LEASE.EndDate AS 'Lease End Date', 
    IF(TIMESTAMPDIFF(MONTH, CURDATE(), LEASE.EndDate) <= 3, 'Expiring Soon', 'Not Expiring Soon') AS 'Expiration Status', 
    IF(TIMESTAMPDIFF(MONTH, CURDATE(), LEASE.EndDate) <= 3, 'To Be Determined', 'Not Applicable') AS 'Renewal Status', 
    AMENITY.StorageNo AS 'Storage #', 
    AMENITY.GarageNo AS 'Garage #' 
    FROM TENANT, LEASE, TRANSACTION, AMENITY 
    WHERE TENANT.LeaseID = LEASE.LeaseID 
    AND TRANSACTION.TenantEmail = TENANT.TenantEmail 
    AND TRANSACTION.AmenityID = AMENITY.AmenityID 
    AND (AMENITY.StorageNo IS NOT NULL OR AMENITY.GarageNo IS NOT NULL) 
    AND TIMESTAMPDIFF(MONTH, CURDATE(), LEASE.EndDate) BETWEEN 0 AND 3 
    ORDER BY LEASE.LeaseID ASC;";

    $expiryResult = $conn->query($expiry);

    // Display the results
    if ($expiryResult->num_rows > 0) {
        echo "<table border='1'>
                <h2>Lease Expiration Status</h2>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>LeaseID</th>
                    <th>Lease Start Date</th>
                    <th>Lease End Date</th>
                    <th>Renewal Status</th>
                    <th>Storage #</th>
                    <th>Garage #</th>
                </tr>";
        while ($row = $expiryResult->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row['First Name'] . "</td>
                    <td>" . $row['Last Name'] . "</td>
                    <td>" . $row['Email'] . "</td>
                    <td>" . $row['LeaseID'] . "</td>
                    <td>" . $row['Lease Start Date'] . "</td>
                    <td>" . $row['Lease End Date'] . "</td>
                    <td>" . $row['Renewal Status'] . "</td>
                    <td>" . $row['Storage #'] . "</td>
                    <td>" . $row['Garage #'] . "</td>
                </tr>";
        }
        echo "</table>";
    } else {
        echo "0 results";
    }

    // Amenity Yearly Sales Report
    $amenity = "SELECT EXTRACT(YEAR FROM TRANSACTION.TDate) AS Year, 
    EXTRACT(MONTH FROM TRANSACTION.TDate) AS Month, 
    SUM(CASE WHEN AMENITY.Item IS NOT NULL THEN AMENITY.UnitPrice * TRANSACTION.Quantity ELSE 0 END) AS 'Total Sales $', 
    SUM(CASE WHEN AMENITY.EquipmentType IS NOT NULL THEN AMENITY.Deposit + (AMENITY.DailyFee * (DATEDIFF(TRANSACTION.DateReturned, TRANSACTION.DateLoaned) + 1)) ELSE 0 END) AS 'Total Loans $' 
    FROM TENANT, AMENITY, TRANSACTION 
    WHERE AMENITY.AmenityID = TRANSACTION.AmenityID 
    AND TENANT.TenantEmail = TRANSACTION.TenantEmail 
    AND (AMENITY.StorageNo IS NULL AND AMENITY.GarageNo IS NULL) 
    AND EXTRACT(YEAR FROM TRANSACTION.TDate) = EXTRACT(YEAR FROM CURRENT_DATE) GROUP BY Year, Month ORDER BY Year, Month";

    $amenityResult = $conn->query($amenity);

    // Display the results
    if ($amenityResult->num_rows > 0) {
        echo "<table border='1'>
                <h2>Amenity Year-to-Date Sales Report</h2>
                <tr>
                    <th>Year</th>
                    <th>Month</th>
                    <th>Total Sales $</th>
                    <th>Total Loans $</th>
                </tr>";
        while ($row = $amenityResult->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row['Year'] . "</td>
                    <td>" . $row['Month'] . "</td>
                    <td>" . $row['Total Sales $'] . "</td>
                    <td>" . $row['Total Loans $'] . "</td>
                </tr>";
        }
        echo "</table>";
    } else {
        echo "0 results";
    }

    // Maintenance Logs
    $maint = "SELECT MAINTENANCE.ProjectType AS 'Project Type', 
    MAINTENANCE.DateRequested AS 'Date Requested', 
    MAINTENANCE.DateScheduled AS 'Date Scheduled', 
    MAINTENANCE.DateCompleted AS 'Date Completed' 
    FROM MAINTENANCE 
    WHERE MAINTENANCE.DateScheduled IS NOT NULL 
    ORDER BY MAINTENANCE.ProjectType";

    $maintResult = $conn->query($maint);

    // Display the results
    if ($maintResult->num_rows > 0) {
        echo "<table border='1'>
                <h2>Decemeber Maintenance Log</h2>
                <tr>
                    <th>Project Type</th>
                    <th>Date Requested</th>
                    <th>Date Scheduled</th>
                    <th>Date Completed</th>
                </tr>";
        while ($row = $maintResult->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row['Project Type'] . "</td>
                    <td>" . $row['Date Requested'] . "</td>
                    <td>" . $row['Date Scheduled'] . "</td>
                    <td>" . $row['Date Completed'] . "</td>
                </tr>";
        }
        echo "</table>";
    } else {
        echo "0 results";
    }
    
    // Quarterly Maintenance Report
    $quarterly = "SELECT MAINTENANCE.ProjectType AS 'Job Category', 
    COUNT(CASE WHEN MAINTENANCE.DateCompleted IS NOT NULL THEN MAINTENANCE.ProjectID END) AS '# of Projects Completed', 
    (SUM(CASE WHEN EXTRACT(QUARTER FROM MAINTENANCE.DateCompleted) = EXTRACT(QUARTER FROM CURRENT_DATE) THEN 1 ELSE 0 END) / COUNT(MAINTENANCE.ProjectID) * 100) AS 'Percentage Completed', 
    AVG(EXTRACT(HOUR FROM (MAINTENANCE.DateCompleted - MAINTENANCE.DateScheduled))) AS 'Avg Completion Time (Hours)' 
    FROM MAINTENANCE 
    GROUP BY MAINTENANCE.ProjectType";

    $quarterlyResult = $conn->query($quarterly);

    // Display the results
    if ($quarterlyResult->num_rows > 0) {
        echo "<table border='1'>
                <h2>Q4 Maintenance Report</h2>
                <tr>
                    <th>Job Category</th>
                    <th># of Projects Completed</th>
                    <th>Percentage Completed</th>
                    <th>Avg Completion Time (Hours)</th>
                </tr>";
        while ($row = $quarterlyResult->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row['Job Category'] . "</td>
                    <td>" . $row['# of Projects Completed'] . "</td>
                    <td>" . $row['Percentage Completed'] . "</td>
                    <td>" . $row['Avg Completion Time (Hours)'] . "</td>
                </tr>";
        }
        echo "</table>";
    } else {
        echo "0 results";
    }

    // Close the database connection
    $conn->close();
    ?>

</body>
</html>