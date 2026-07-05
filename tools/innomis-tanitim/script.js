document.addEventListener('DOMContentLoaded', () => {
    // Elegant Slideshow Logic
    const slides = document.querySelectorAll('.slide');
    let currentSlide = 0;
    const slideInterval = 5000; // 5 seconds per slide for a slow elegant feel

    if (slides.length > 1) {
        setInterval(() => {
            // Remove active class
            slides[currentSlide].classList.remove('active');
            
            // Next slide
            currentSlide = (currentSlide + 1) % slides.length;
            
            // Add active class
            slides[currentSlide].classList.add('active');
        }, slideInterval);
    }

    // Add 3D subtle mouse tracking to the glass frame if on desktop
    const frame = document.querySelector('.glass-frame');
    const container = document.querySelector('.perspective-container');

    if (container && frame && window.innerWidth > 900) {
        container.addEventListener('mousemove', (e) => {
            const rect = container.getBoundingClientRect();
            const x = e.clientX - rect.left - rect.width / 2;
            const y = e.clientY - rect.top - rect.height / 2;
            
            const rotateX = (y / (rect.height / 2)) * -5; // Max 5 deg
            const rotateY = (x / (rect.width / 2)) * 10; // Max 10 deg
            
            frame.style.transform = `rotateY(${rotateY}deg) rotateX(${rotateX}deg) scale(1)`;
        });

        container.addEventListener('mouseleave', () => {
            // Return to default diagonal pose smoothly
            frame.style.transform = `rotateY(-10deg) rotateX(5deg) scale(0.95)`;
        });
    }

    // QR Modal Logic
    const qrModal = document.getElementById('qr-modal');
    const qrTriggerBtn = document.getElementById('qr-enlarge-btn');
    const qrContainerTrigger = document.getElementById('qr-trigger');
    const closeModalBtn = document.getElementById('close-modal');
    const modalBackdrop = document.getElementById('modal-backdrop');

    function openModal() {
        if(qrModal) qrModal.classList.add('active');
    }

    function closeModal() {
        if(qrModal) qrModal.classList.remove('active');
    }

    if (qrTriggerBtn) qrTriggerBtn.addEventListener('click', openModal);
    if (qrContainerTrigger) qrContainerTrigger.addEventListener('click', openModal);
    if (closeModalBtn) closeModalBtn.addEventListener('click', closeModal);
    if (modalBackdrop) modalBackdrop.addEventListener('click', closeModal);

    // Fullscreen Prompt Logic
    const fsModal = document.getElementById('fullscreen-modal');
    const fsAcceptBtn = document.getElementById('fs-accept-btn');
    const fsDeclineBtn = document.getElementById('fs-decline-btn');
    const fsBackdrop = document.getElementById('fs-modal-backdrop');

    function closeFsModal() {
        if(fsModal) fsModal.classList.remove('active');
    }

    if (fsAcceptBtn) {
        fsAcceptBtn.addEventListener('click', () => {
            if (document.documentElement.requestFullscreen) {
                document.documentElement.requestFullscreen().catch(err => {
                    console.log(`Tam ekran moduna geçilemedi: ${err.message}`);
                });
            } else if (document.documentElement.webkitRequestFullscreen) { /* Safari */
                document.documentElement.webkitRequestFullscreen();
            } else if (document.documentElement.msRequestFullscreen) { /* IE11 */
                document.documentElement.msRequestFullscreen();
            }
            closeFsModal();
        });
    }

    if (fsDeclineBtn) fsDeclineBtn.addEventListener('click', closeFsModal);
    if (fsBackdrop) fsBackdrop.addEventListener('click', closeFsModal);

});
