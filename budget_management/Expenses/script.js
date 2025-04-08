function toggleSideBar() {
    const sidebar = document.querySelector('.sideBar');
    const button = document.querySelector('.toggleButton');

    sidebar.classList.toggle('collapsed');
    button.classList.toggle('collapsed');
}


function openForm() {
    document.getElementById("addForm").style.display = "block";
    document.getElementById("overlay").style.display = "block";
}

function closeForm() {
    document.getElementById("addForm").style.display = "none";
    document.getElementById("overlay").style.display = "none";
}


function openEditForm() {
    document.getElementById("editForm").style.display = "block";
    document.getElementById("editOverlay").style.display = "block";
}

function closeEditForm() {
    document.getElementById("editForm").style.display = "none";
    document.getElementById("editOverlay").style.display = "none";
}

