function toggleSideBar() {
    const sidebar = document.querySelector('.sideBar');
    const button = document.querySelector('.toggleButton');

    sidebar.classList.toggle('collapsed');
    button.classList.toggle('collapsed');
}

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

function displayReceipt(reminder) {
    const receiptDiv = document.getElementById('reminderReceipt');
    const createdAtDate = new Date(reminder.created_at);
    const formattedCreatedAt = isNaN(createdAtDate.getTime()) ? 'N/A' : createdAtDate.toLocaleDateString();

    receiptDiv.innerHTML = `
        <div style="padding: 20px; text-align: left; font-size: 15px; color: #eee; border-radius: 8px; background-color: #3A3F50;">
            <h4 style="margin-top: 0; margin-bottom: 15px; color: white; text-align: center;">Reminder Details</h4>
            <hr style="border-top: 1px solid #555; margin-bottom: 15px;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                <span style="color: #bbb;">Name:</span>
                <span style="font-weight: bold; color: #00FF7F;">${reminder.reminder_name}</span>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                <span style="color: #bbb;">Amount:</span>
                <span>₹${parseFloat(reminder.reminder_amount).toLocaleString()}</span>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                <span style="color: #bbb;">Date:</span>
                <span>${reminder.reminder_date}</span>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                <span style="color: #bbb;">Category:</span>
                <span>${reminder.reminder_category}</span>
            </div>
            <hr style="border-top: 1px solid #555; margin-bottom: 10px;">
            <div style="text-align: center; font-size: 12px; color: #bbb; margin-bottom: 5px;">
                Go Broke - Financial Management
            </div>
            <div style="text-align: center; font-size: 12px; color: #bbb; margin-bottom: 5px;">
                Reminder Created On: ${formattedCreatedAt}
            </div>
            <div style="text-align: center; font-size: 12px; color: #bbb;">
                User ID: ${reminder.user_id}
            </div>
        </div>
    `;
}