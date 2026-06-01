// Create Product
document.getElementById('createForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    const formData = new FormData(e.target);
    formData.append('action', 'create');

    try {
        const response = await fetch('index.php', {
            method: 'POST',
            body: formData
        });

        if (response.ok) {
            window.location.reload();
        } else {
            alert('Error creating product');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred');
    }
});

// Edit Product
function editProduct(id) {
    const row = document.querySelector(`tr[data-id="${id}"]`);
    const name = row.querySelector('.product-name').textContent;
    const price = row.querySelector('.product-price').textContent.replace('$', '');
    const stock = row.querySelector('.product-stock').textContent;

    document.getElementById('editId').value = id;
    document.getElementById('editName').value = name;
    document.getElementById('editPrice').value = price;
    document.getElementById('editStock').value = stock;

    const modal = document.getElementById('editModal');
    modal.style.display = 'block';
}

// Update Product
document.getElementById('editForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    const formData = new FormData(e.target);
    formData.append('action', 'update');

    try {
        const response = await fetch('index.php', {
            method: 'POST',
            body: formData
        });

        if (response.ok) {
            window.location.reload();
        } else {
            alert('Error updating product');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred');
    }
});

// Delete Product
function deleteProduct(id) {
    if (confirm('Are you sure you want to delete this product?')) {
        window.location.href = `index.php?delete=${id}`;
    }
}

// Modal functionality
const modal = document.getElementById('editModal');
const closeBtn = document.getElementsByClassName('close')[0];

closeBtn.onclick = function () {
    modal.style.display = 'none';
}

window.onclick = function (event) {
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}

// Real-time search functionality (optional enhancement)
function searchProducts() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('productsTable');
    const rows = table.getElementsByTagName('tr');

    for (let i = 1; i < rows.length; i++) {
        const nameCell = rows[i].getElementsByTagName('td')[1];
        if (nameCell) {
            const nameValue = nameCell.textContent || nameCell.innerText;
            if (nameValue.toLowerCase().indexOf(filter) > -1) {
                rows[i].style.display = '';
            } else {
                rows[i].style.display = 'none';
            }
        }
    }
}
