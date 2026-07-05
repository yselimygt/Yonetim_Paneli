document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('category-container');
    
    // Uygulama verilerini tutacağımız nesne
    let appData = {};
    let isProcessing = false; // Çifte tıklamaları önlemek için
    let selectedCategoryId = null;

    // Modal Elementleri
    const modal = document.getElementById('batch-modal');
    const batchInput = document.getElementById('batch-input');
    const confirmBtn = document.getElementById('btn-modal-confirm');
    const cancelBtn = document.getElementById('btn-modal-cancel');

    // 1. Kategorileri Yükle
    fetch('data.json')
        .then(response => response.json())
        .then(data => {
            document.getElementById('loading').remove();
            initApp(data.categories);
            
            // Periyodik olarak oyları sunucudan çek (Her 2.5 saniyede bir eşitle)
            setInterval(fetchVotes, 2500);
        })
        .catch(error => {
            console.error('Kategoriler yüklenirken hata oluştu:', error);
            container.innerHTML = '<div style="color:var(--danger); text-align:center; padding: 20px; font-weight: bold;">Kategoriler yüklenemedi.</div>';
        });

    function initApp(categories) {
        categories.forEach((category, index) => {
            const id = category.id;
            appData[id] = 0;
            
            // İlk ittifak kategorisinden önce çizgi ekle
            if (category.isAlliance && !categories[index - 1]?.isAlliance) {
                const hr = document.createElement('div');
                hr.className = 'alliance-divider';
                container.appendChild(hr);
            }
            
            createCategoryCard(id, category.name, 0, category.logo, category.logoText);
        });

        // Başlangıçta sunucudan o anki güncel oyları al
        fetchVotes();
    }

    function createCategoryCard(id, name, initialCount, logoUrl, logoText) {
        const card = document.createElement('div');
        card.className = 'category-card';
        
        let logoHtml = '';
        if (logoUrl) {
            logoHtml = `<div class="category-logo-container">
                            <img src="${logoUrl}" alt="${name}" class="category-logo">
                            ${logoText ? `<span class="category-logo-text">${logoText}</span>` : ''}
                        </div>`;
        }

        card.innerHTML = `
            ${logoHtml}
            <div class="category-title">${name}</div>
            <div class="counter-display">
                <button class="btn btn-minus" data-id="${id}" aria-label="Azalt">
                    <i class="fa-solid fa-minus"></i>
                </button>
                <div class="count" id="count-${id}">${initialCount}</div>
                <div class="plus-group">
                    <button class="btn btn-plus" data-id="${id}" aria-label="Arttır">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                    <button class="btn-batch" data-id="${id}" title="Toplu Ekle">
                        <i class="fa-solid fa-list-numeric"></i>
                    </button>
                </div>
            </div>
        `;

        container.appendChild(card);

        // Event Listeners
        const minusBtn = card.querySelector('.btn-minus');
        const plusBtn = card.querySelector('.btn-plus');
        const batchBtn = card.querySelector('.btn-batch');

        minusBtn.addEventListener('click', () => sendVote(id, -1));
        plusBtn.addEventListener('click', () => sendVote(id, 1));
        batchBtn.addEventListener('click', () => openBatchModal(id, name));
    }

    function openBatchModal(id, name) {
        selectedCategoryId = id;
        document.getElementById('modal-title').textContent = `${name}`;
        batchInput.value = '';
        modal.style.display = 'flex';
        batchInput.focus();
    }

    function closeBatchModal() {
        modal.style.display = 'none';
        selectedCategoryId = null;
    }

    cancelBtn.addEventListener('click', closeBatchModal);
    
    confirmBtn.addEventListener('click', () => {
        const val = parseInt(batchInput.value);
        if (val > 0 && selectedCategoryId) {
            sendVote(selectedCategoryId, val);
            closeBatchModal();
        }
    });

    // Enter tuşu ile onaylama
    batchInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') confirmBtn.click();
    });

    // Dışarı tıklayınca kapatma
    modal.addEventListener('click', (e) => {
        if (e.target === modal) closeBatchModal();
    });

    function sendVote(id, change) {
        if (isProcessing) return;
        
        // Kullanıcıya hemen cevap verelim (Hissiyatı hızlandırmak için)
        const countElement = document.getElementById(`count-${id}`);
        let currentVal = parseInt(countElement.textContent);
        if(currentVal + change < 0) return; // Eksi olmasın
        
        countElement.textContent = currentVal + change;
        animateCount(countElement, change);

        isProcessing = true;
        
        // Arka planda sunucuya (Senkronize merkeze) iletiyoruz
        fetch('api.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'vote', id: id, change: change })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                updateUIWithData(data.data);
            }
        })
        .catch(err => {
            console.error("Oy gönderilirken hata:", err);
            // Hata olursa oyları tekrar sunucudan eşitle
            fetchVotes(); 
        })
        .finally(() => {
            isProcessing = false;
        });
    }

    function fetchVotes() {
        if(isProcessing) return; 
        
        fetch('api.php')
            .then(response => response.json())
            .then(data => {
                updateUIWithData(data);
            })
            .catch(error => console.error('Veriler çekilemedi:', error));
    }

    function updateUIWithData(data) {
        Object.keys(data).forEach(id => {
            appData[id] = data[id];
            const el = document.getElementById(`count-${id}`);
            if(el && parseInt(el.textContent) !== data[id]) {
                el.textContent = data[id];
                // Başka birinin eklediği bir oy sisteme düşünce cihazda mavi yanar
                animasyonluGuncelleme(el);
            }
        });
    }

    function animateCount(element, change) {
        element.style.transform = change > 0 ? 'scale(1.2)' : 'scale(0.8)';
        element.style.color = change > 0 ? '#34d399' : '#f87171'; // Arttırırken yeşil, azaltırken kırmızı
        setTimeout(() => {
            element.style.transform = 'scale(1)';
            element.style.color = 'var(--secondary)';
        }, 150);
    }
    
    function animasyonluGuncelleme(element) {
        element.style.transform = 'scale(1.1)';
        element.style.color = '#60a5fa'; // Diğer cihazdan gelen senkronizasyonda mavi parlar
        setTimeout(() => {
            element.style.transform = 'scale(1)';
            element.style.color = 'var(--secondary)';
        }, 300);
    }
});
