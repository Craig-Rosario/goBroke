<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Go Broke</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>

<?php
session_start();
$user_id = $_SESSION['user_id'] ?? 1;

include("../Registration/database.php");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_reminder'])) {
    $name = $_POST['reminder_name'];
    $amount = $_POST['reminder_amount'];
    $date = $_POST['reminder_date'];
    $category = $_POST['reminder_category'];

    $stmt = $conn->prepare("INSERT INTO reminders (user_id, reminder_name, reminder_amount, reminder_date, reminder_category) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isdss", $user_id, $name, $amount, $date, $category);
    $stmt->execute();
    $stmt->close();
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_reminder'])) {
    $id = $_POST['reminder_id'];
    $name = $_POST['reminder_name'];
    $amount = $_POST['reminder_amount'];
    $date = $_POST['reminder_date'];
    $category = $_POST['reminder_category'];

    $stmt = $conn->prepare("UPDATE reminders SET reminder_name=?, reminder_amount=?, reminder_date=?, reminder_category=? WHERE id=? AND user_id=?");
    $stmt->bind_param("sdssii", $name, $amount, $date, $category, $id, $user_id);
    $stmt->execute();
    $stmt->close();
    header("Location: index.php");
    exit();
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM reminders WHERE id = ? AND user_id=?");
    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();
    $stmt->close();
    header("Location: index.php");
    exit();
}

$result = $conn->query("SELECT * FROM reminders WHERE user_id = $user_id ORDER BY reminder_date ASC");
?>

<div class="sideBar">
    <div class="sideBarTitle">
        <h2><span class="fullText"><span class="red">Go</span><span class="green">Broke</span></span></h2>
        <h3><span class="shortText"><span class="red">g</span><span class="green">B</span></span></h3>
        <button class="toggleButton" onclick="toggleSideBar()"><i class="fa-solid fa-bars"></i></button>
    </div>
    <nav>
        <ul>
            <li><a href="../Dashboard/index.php"><i class="fas fa-home"></i> <span>Home</span></a></li>
            <li><a href="../Income/index.php"><i class="fas fa-wallet"></i> <span>Income Tracker</span></a></li>
            <li><a href="../Expenses/index.php"><i class="fas fa-chart-line"></i> <span>Expense Tracker</span></a></li>
            <li><a href="../Reminders/index.php"><i class="fas fa-bell"></i> <span>Reminders</span></a></li>
        </ul>
    </nav>
    <ul class="logout">
        <li><a href="../Login/login.php"><i class="fa-solid fa-right-from-bracket"></i> <span>Logout</span></a></li>
    </ul>
</div>

<div class="mainContainer">
    <header>
        <h1>Reminders</h1>
    </header>
    <div class="income">
        <div class="totalIncome">
            <h3>Upcoming Bills</h3>
            <div class="incomeCard">
                <ul style="list-style-type: none; padding-left: 0; font-size: 16px;">
                    <?php
                    // Show top 5 upcoming reminders with images
                    $upcoming = $conn->query("SELECT reminder_name, reminder_date, reminder_category FROM reminders WHERE user_id = $user_id AND reminder_date >= CURDATE() ORDER BY reminder_date ASC LIMIT 5");
                    if ($upcoming->num_rows > 0):
                        while ($row = $upcoming->fetch_assoc()):
                            // Determine the image based on the category
                            $image = '';
                            switch (strtolower($row['reminder_category'])) {
                                case 'electricity':
                                    $image = 'electricity.png'; 
                                    break;
                                case 'internet':
                                    $image = 'internet.png'; 
                                    break;
                                case 'credit card':
                                    $image = 'credit_card.png'; 
                                    break;
                                case 'stationary':
                                    $image = 'stationary.png'; 
                                    break;
                                case 'books':
                                    $image = 'books.png'; 
                                    break;
                                default:
                                    $image = 'default.png'; 
                                    break;
                            }
                    ?>
                            <li>
                                <img src="<?= $image ?>" alt="<?= htmlspecialchars($row['reminder_category']) ?>">
                                <span><?= htmlspecialchars($row['reminder_name']) ?> - <?= $row['reminder_date'] ?></span>
                            </li>
                    <?php endwhile; else: ?>
                        <li>No upcoming bills</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

        <div class="incomeChart">
            <h3>Reminder Receipt</h3>
            <div class="incomeCard">
                <canvas id="goalChart2"></canvas>
                <p class="income-amount">₹5,432,000</p>
            </div>
        </div>
    </div>

    <div class="analytics">
        <h3>All Reminders</h3>
        <button class="addBtn" onclick="openReminderForm()"><i class="fa-solid fa-plus"></i>Add</button>

        <div class="incomeTable">
            <div class="tableCard">
                <table>
                    <tr>
                        <th>Name</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Category</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['reminder_name']) ?></td>
                        <td style="color:#00FF7F">₹<?= number_format($row['reminder_amount']) ?></td>
                        <td><?= $row['reminder_date'] ?></td>
                        <td><?= htmlspecialchars($row['reminder_category']) ?></td>
                        <td class="btns">
                            <i class="fa-solid fa-file-pen" onclick='openEditReminderForm(<?= json_encode($row) ?>)'></i>
                        </td>
                        <td class="btns" style="color: red;">
                            <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>
    </div>

    <div class="reminderOverlay" id="reminderOverlay"></div>
    <div class="addReminderForm" id="addReminderForm">
        <div class="addIncomeFormCard">
            <h1>Add New Reminder</h1>
            <button class="closeBtn" onclick="closeReminderForm()"><i class="fa-solid fa-xmark"></i></button>
            <form method="POST">
                <input type="hidden" name="add_reminder" value="1">
                <label>Reminder Name
                    <input type="text" name="reminder_name" required>
                </label>
                <label>Amount
                    <input type="number" name="reminder_amount" required>
                </label>
                <label>Date
                    <input type="date" name="reminder_date" required>
                </label>
                <label>Category
                    <input type="text" name="reminder_category" required>
                </label>
                <button type="submit">Submit</button>
            </form>
        </div>
    </div>

    <div class="editReminderOverlay" id="editReminderOverlay"></div>
    <div class="editReminderForm" id="editReminderForm">
        <div class="addIncomeFormCard">
            <h1>Edit Reminder</h1>
            <button class="closeBtn" onclick="closeEditReminderForm()"><i class="fa-solid fa-xmark"></i></button>
            <form method="POST">
                <input type="hidden" name="edit_reminder" value="1">
                <input type="hidden" id="editReminderId" name="reminder_id">
                <label>Reminder Name
                    <input type="text" id="editReminderName" name="reminder_name" required>
                </label>
                <label>Amount
                    <input type="number" id="editReminderAmt" name="reminder_amount" required>
                </label>
                <label>Date
                    <input type="date" id="editReminderDate" name="reminder_date" required>
                </label>
                <label>Category
                    <input type="text" id="editReminderCat" name="reminder_category" required>
                </label>
                <button type="submit">Save</button>
            </form>
        </div>
    </div>
</div>

<script>
function openReminderForm() {
    document.getElementById('reminderOverlay').style.display = 'block';
    document.getElementById('addReminderForm').style.display = 'block';
}

function closeReminderForm() {
    document.getElementById('reminderOverlay').style.display = 'none';
    document.getElementById('addReminderForm').style.display = 'none';
}

function openEditReminderForm(data) {
    document.getElementById('editReminderOverlay').style.display = 'block';
    document.getElementById('editReminderForm').style.display = 'block';
    document.getElementById('editReminderId').value = data.id;
    document.getElementById('editReminderName').value = data.reminder_name;
    document.getElementById('editReminderAmt').value = data.reminder_amount;
    document.getElementById('editReminderDate').value = data.reminder_date;
    document.getElementById('editReminderCat').value = data.reminder_category;
}

function closeEditReminderForm() {
    document.getElementById('editReminderOverlay').style.display = 'none';
    document.getElementById('editReminderForm').style.display = 'none';
}
</script>

</body>
</html>