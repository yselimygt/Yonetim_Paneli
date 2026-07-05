<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InnoPlace - Topluluğun Dev Tuvali</title>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;700&family=Poppins:wght@600;800&display=swap" rel="stylesheet">
    <style>
        :root { --bg-color: #050505; --primary-gradient: linear-gradient(135deg, #FF8C00 0%, #FF0080 100%); --text-color: #ffffff; --glass-bg: rgba(255, 255, 255, 0.05); --glass-border: rgba(255, 255, 255, 0.1); }
        body { margin: 0; padding: 0; background-color: var(--bg-color); color: var(--text-color); font-family: 'IBM Plex Sans', sans-serif; overflow-x: hidden; display: flex; flex-direction: column; min-height: 100vh; }
        .background-grid { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-image: linear-gradient(var(--glass-border) 1px, transparent 1px), linear-gradient(90deg, var(--glass-border) 1px, transparent 1px); background-size: 50px 50px; opacity: 0.2; z-index: -2; perspective: 1000px; transform: scale(1.1); animation: moveGrid 20s linear infinite; }
        @keyframes moveGrid { 0% { transform: translateY(0) scale(1.1); } 100% { transform: translateY(50px) scale(1.1); } }
        .pixel-particle { position: absolute; width: 50px; height: 50px; background: rgba(255, 140, 0, 0.1); z-index: -1; animation: blink 3s infinite; }
        @keyframes blink { 0%, 100% { opacity: 0; } 50% { opacity: 1; } }
        .hero-section { flex: 1; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; padding: 20px; position: relative; }
        .brand-badge { background: rgba(255, 255, 255, 0.1); padding: 8px 20px; border-radius: 50px; font-size: 14px; font-weight: 600; margin-bottom: 20px; border: 1px solid var(--glass-border); text-transform: uppercase; letter-spacing: 2px; color: #FF8C00; animation: slideDown 0.8s ease-out; }
        h1 { font-family: 'Poppins', sans-serif; font-size: clamp(3rem, 8vw, 6rem); font-weight: 800; margin: 0; line-height: 1; background: var(--primary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent; text-shadow: 0 10px 30px rgba(255, 0, 128, 0.3); animation: zoomIn 1s ease-out; }
        p.subtitle { font-size: clamp(1rem, 2vw, 1.5rem); color: #94a3b8; max-width: 600px; margin: 20px auto 40px; line-height: 1.6; animation: fadeIn 1.2s ease-out; }
        .stats-container { display: flex; gap: 20px; margin-bottom: 40px; animation: fadeIn 1.5s ease-out; }
        .stat-box { background: var(--glass-bg); border: 1px solid var(--glass-border); padding: 15px 30px; border-radius: 16px; backdrop-filter: blur(10px); text-align: center; min-width: 120px; }
        .stat-number { font-size: 1.8rem; font-weight: bold; color: white; display: block; }
        .stat-label { font-size: 0.8rem; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; }
        .cta-btn { background: var(--primary-gradient); color: white; text-decoration: none; padding: 20px 60px; font-size: 1.2rem; font-weight: bold; border-radius: 16px; transition: transform 0.2s, box-shadow 0.2s; box-shadow: 0 10px 40px rgba(255, 0, 128, 0.4); display: inline-flex; align-items: center; gap: 15px; animation: bounceIn 1.8s ease-out; border: 1px solid rgba(255, 255, 255, 0.2); }
        .cta-btn:hover { transform: translateY(-5px) scale(1.05); box-shadow: 0 20px 60px rgba(255, 0, 128, 0.6); }
        footer { text-align: center; padding: 30px 20px; color: #64748b; font-size: 0.9rem; border-top: 1px solid var(--glass-border); background: rgba(0,0,0,0.5); line-height: 1.6; }
        .dev-link { color: #FF8C00; text-decoration: none; font-weight: 600; transition: color 0.3s; margin-left: 5px; } .dev-link:hover { color: #FF0080; }
        @keyframes slideDown { from { transform: translateY(-50px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        @keyframes zoomIn { from { transform: scale(0.8); opacity: 0; } to { transform: scale(1); opacity: 1; } }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes bounceIn { 0% { transform: scale(0); opacity: 0; } 60% { transform: scale(1.1); opacity: 1; } 100% { transform: scale(1); } }
    </style>
</head>
<body>
    <div class="background-grid" id="bgGrid"></div>
    <div class="hero-section">
        <div class="brand-badge">InnoMIS Topluluğu Sunar</div>
        <h1>INNOPLACE</h1>
        <p class="subtitle">Üniversitenin en büyük dijital savaş alanı. İzini bırak, bölümünün bayrağını dik ve sanatını konuştur.</p>
        <div class="stats-container">
            <div class="stat-box"><span class="stat-number" id="pixelCount">0</span><span class="stat-label">Boyanan Piksel</span></div>
            <div class="stat-box"><span class="stat-number">100x100</span><span class="stat-label">Savaş Alanı</span></div>
        </div>
        <a href="innoplace.php" class="cta-btn">
            <span>TUVALE GİR</span>
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>
    </div>
    <footer>&copy; 2025 InnoMIS | İnovasyon ve Yönetim Bilişim Sistemleri Topluluğu<br>Geliştirici: <a href="https://oguzkaanekin.site" target="_blank" class="dev-link">oguzkaanekin</a></footer>
    <script>
        const grid = document.getElementById('bgGrid');
        for(let i=0; i<20; i++) { let div = document.createElement('div'); div.className = 'pixel-particle'; div.style.left = Math.random()*100+'%'; div.style.top = Math.random()*100+'%'; div.style.animationDelay = Math.random()*3+'s'; div.style.background = Math.random()>0.5?'rgba(255,140,0,0.2)':'rgba(255,0,128,0.2)'; grid.appendChild(div); }
        let lastCount = 0;
        async function fetchStats() {
            try { const res = await fetch('api/place_get.php'); const data = await res.json();
            if(data.length !== lastCount) { animateValue("pixelCount", lastCount, data.length, 1000); lastCount = data.length; } } catch(e){}
        }
        function animateValue(id, start, end, duration) {
            if(start === end) return;
            var obj = document.getElementById(id); if(obj.timer) clearInterval(obj.timer);
            var range = end - start; var current = start; var increment = end > start ? 1 : -1; var stepTime = Math.abs(Math.floor(duration / range));
            obj.timer = setInterval(function() { current += increment; obj.innerHTML = current; if(current == end) clearInterval(obj.timer); }, Math.max(stepTime, 10));
        }
        fetchStats(); setInterval(fetchStats, 3000);
    </script>
</body>
</html>