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