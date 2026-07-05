document.addEventListener('DOMContentLoaded', () => {
    const tableBody = document.getElementById('admin-table-body');
    const resetBtn = document.getElementById('btn-reset-all');
    let categories = [];
    let isFetching = false;

    // 1. Kategorileri Çek
    fetch('data.json')
        .then(response => response.json())
        .then(data => {
            categories = data.categories;
            fetchAdminData();
            setInterval(fetchAdminData, 3000); // 3 saniyede bir güncelle
        });

    function fetchAdminData() {
        if (isFetching) return;
        isFetching = true;

        fetch('api.php')
            .then(response => response.json())
            .then(voteData => {
                renderTable(voteData);
            })
            .catch(err => console.error('Admin veri çekme hatası:', err))
            .finally(() => isFetching = false);
    }

    function renderTable(voteData) {
        tableBody.innerHTML = '';
        categories.forEach(cat => {
            const count = voteData[cat.id] || 0;
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${cat.name}</td>
                <td class="count-cell" style="text-align: center;">${count}</td>
            `;
            tableBody.appendChild(row);
        });
    }

    resetBtn.addEventListener('click', () => {
        if (!confirm('TÜM OYLAR SIFIRLANACAK! Emin misiniz?')) return;

        fetch('api.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'reset' })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('Tüm oylar başarıyla sıfırlandı.');
                fetchAdminData();
            }
        })
        .catch(err => alert('Sıfırlama sırasında hata oluştu: ' + err));
    });
});
