// Create Car
document.getElementById('createForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    const formData = new FormData(e.target);
    formData.append('action', 'create');

    try {
        const response = await fetch('admin.php', {
            method: 'POST',
            body: formData
        });

        if (response.ok) {
            window.location.reload();
        } else {
            alert('Error creating car');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred');
    }
});

// Edit Car
function editCar(id) {
    const row = document.querySelector(`tr[data-id="${id}"]`);
    
    document.getElementById('editId').value = id;
    document.getElementById('editMakeModel').value = row.querySelector('.car-make_model').textContent;
    document.getElementById('editPrice').value = row.querySelector('.car-price').textContent;
    document.getElementById('editColor').value = row.querySelector('.car-color').textContent;
    document.getElementById('editMileage').value = row.querySelector('.car-mileage').textContent;
    document.getElementById('editEngine').value = row.querySelector('.car-engine').textContent;
    document.getElementById('editTransmission').value = row.querySelector('.car-transmission').textContent;
    document.getElementById('editFuel').value = row.querySelector('.car-fuel').textContent;
    document.getElementById('editImageFile').value = row.querySelector('.car-image_file').textContent;
    
    document.getElementById('editFinancing').checked = row.querySelector('.car-financing').textContent === '1';
    document.getElementById('editLocallyUsed').checked = row.querySelector('.car-locally_used').textContent === '1';
    document.getElementById('editInspection').checked = row.querySelector('.car-inspection').textContent === '1';

    const modal = document.getElementById('editModal');
    modal.style.display = 'block';
}

// Update Car
document.getElementById('editForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    const formData = new FormData(e.target);
    formData.append('action', 'update');

    try {
        const response = await fetch('admin.php', {
            method: 'POST',
            body: formData
        });

        if (response.ok) {
            window.location.reload();
        } else {
            alert('Error updating car');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred');
    }
});

// Delete Car
function deleteCar(id) {
    if (confirm('Are you sure you want to delete this car?')) {
        window.location.href = `admin.php?delete=${id}`;
    }
}

// Modal functionality
const modal = document.getElementById('editModal');
const closeBtn = document.getElementsByClassName('close')[0];

if (closeBtn) {
    closeBtn.onclick = function () {
        modal.style.display = 'none';
    }
}

window.onclick = function (event) {
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}
