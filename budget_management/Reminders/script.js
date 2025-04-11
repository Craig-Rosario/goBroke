function toggleSideBar() {
    const sidebar = document.querySelector('.sideBar');
    const button = document.querySelector('.toggleButton');

    sidebar.classList.toggle('collapsed');
    button.classList.toggle('collapsed');
}

function openReminderForm() {
    document.querySelector('.addReminderForm').style.display = "block";
    document.querySelector('.reminderOverlay').style.display = "block";
}

function closeReminderForm() {
    document.querySelector('.addReminderForm').style.display = "none";
    document.querySelector('.reminderOverlay').style.display = "none";
}

function openEditReminderForm() {
    document.querySelector('.editReminderForm').style.display = "block";
    document.querySelector('.editReminderOverlay').style.display = "block";
}

function closeEditReminderForm() {
    document.querySelector('.editReminderForm').style.display = "none";
    document.querySelector('.editReminderOverlay').style.display = "none";
}
