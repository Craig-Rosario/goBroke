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

function openForm() {
    document.getElementById("addForm").style.display = "block";
    document.getElementById("incomeOverlay").style.display = "block";
}

function closeForm() {
    document.getElementById("addForm").style.display = "none";
    document.getElementById("incomeOverlay").style.display = "none";
}

function openEditForm(link) {

    const incomeId = link.getAttribute('data-id');
    const incomeName = link.getAttribute('data-name');
    const incomeAmount = link.getAttribute('data-amount');
    const incomeDate = link.getAttribute('data-date');
    const incomeCategory = link.getAttribute('data-category');

    document.getElementById('editId').value = incomeId;  
    document.getElementById('editIncomeName').value = incomeName; 
    document.getElementById('editIncomeAmt').value = incomeAmount;  
    document.getElementById('editIncomeDate').value = incomeDate;  
    document.getElementById('editIncomeCat').value = incomeCategory; 

    document.getElementById('editForm').style.display = 'block';
    document.getElementById('editOverlay').style.display = 'block';
}

function closeEditForm() {
    document.getElementById('editForm').style.display = 'none';
    document.getElementById('editOverlay').style.display = 'none';
}
