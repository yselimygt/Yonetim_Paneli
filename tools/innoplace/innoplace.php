<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>InnoPlace</title>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --bg-color: #000000; --ui-bg: #1a1a1b; --ui-border: #343536; --text-color: #ffffff; --accent-color: #ff4500; }
        body { margin: 0; overflow: hidden; background-color: var(--bg-color); font-family: 'IBM Plex Sans', sans-serif; color: var(--text-color); user-select: none; -webkit-user-select: none; touch-action: none; }
        
        #viewport { width: 100vw; height: 100vh; display: flex; justify-content: center; align-items: center; overflow: hidden; background: #050505; cursor: crosshair; }
        #canvas-container { position: relative; transform-origin: center center; box-shadow: 0 0 100px rgba(255, 255, 255, 0.05); }
        canvas { display: block; image-rendering: pixelated; }
        #reticle { position: absolute; width: 10px; height: 10px; border: 1px solid rgba(255, 255, 255, 0.9); box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.8); pointer-events: none; z-index: 5; display: none; }
        
        /* HEADER */
        #header { position: fixed; top: 0; left: 0; width: 100%; padding: 15px 10px; pointer-events: none; display: flex; flex-direction: column; align-items: center; gap: 8px; z-index: 100; box-sizing: border-box; }
        .header-pill { background-color: rgba(26, 26, 27, 0.9); backdrop-filter: blur(5px); border: 1px solid var(--ui-border); padding: 8px 20px; border-radius: 99px; pointer-events: auto; display: flex; align-items: center; gap: 12px; font-size: 14px; box-shadow: 0 4px 15px rgba(0,0,0,0.5); }
        .info-pill { background-color: rgba(26, 26, 27, 0.9); backdrop-filter: blur(5px); border: 1px solid var(--ui-border); border-radius: 99px; padding: 6px 16px; font-size: 13px; font-weight: 600; color: #ffffff; pointer-events: auto; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4); }
        .timer { font-weight: bold; color: #00A368; min-width: 60px; text-align: center; } .timer.waiting { color: #ff4500; }
        
        /* ALT BAR */
        #bottom-bar { position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%); width: 95%; max-width: 600px; background-color: rgba(26, 26, 27, 0.95); border: 1px solid var(--ui-border); border-radius: 16px; padding: 12px; display: flex; gap: 12px; align-items: center; overflow-x: auto; white-space: nowrap; box-shadow: 0 10px 30px rgba(0,0,0,0.6); z-index: 100; -webkit-overflow-scrolling: touch; touch-action: pan-x; }
        #bottom-bar::-webkit-scrollbar { display: none; }
        .color-btn { flex: 0 0 auto; width: 42px; height: 42px; border-radius: 50%; border: 2px solid #333; cursor: pointer; transition: transform 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275); position: relative; }
        .color-btn.active { border-color: white; transform: scale(1.2); box-shadow: 0 0 15px rgba(255,255,255,0.4); border-width: 3px; z-index: 2; }
        
        /* KONTROLLER (ZOOM & MUSIC) */
        #zoom-controls { position: fixed; bottom: 100px; right: 15px; display: flex; flex-direction: column; gap: 10px; z-index: 90; }
        .icon-btn { width: 44px; height: 44px; background: rgba(26, 26, 27, 0.8); border: 1px solid var(--ui-border); color: white; border-radius: 50%; font-size: 20px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(0,0,0,0.3); cursor: pointer; transition: all 0.2s; }
        .icon-btn:active { transform: scale(0.9); }
        
        /* Müzik çalıyorken butonun rengi değişsin */
        .music-playing { border-color: #00A368 !important; color: #00A368 !important; box-shadow: 0 0 10px rgba(0, 163, 104, 0.5); }

        #toast { position: fixed; top: 100px; left: 50%; transform: translateX(-50%); background: rgba(30, 30, 30, 0.95); color: white; padding: 10px 20px; border-radius: 30px; font-weight: 600; font-size: 14px; opacity: 0; transition: opacity 0.3s; pointer-events: none; z-index: 200; border: 1px solid rgba(255,255,255,0.1); white-space: nowrap; }
    </style>
</head>
<body>
    
    <audio id="bg-music" loop>
        <source src="https://cdn.pixabay.com/download/audio/2022/05/27/audio_1808fbf07a.mp3?filename=lofi-study-112191.mp3" type="audio/mpeg">
    </audio>

    <div id="header">
        <div class="header-pill">
            <span style="font-weight:700; color:#FF8C00">InnoPlace</span>
            <span style="color:#555">|</span>
            <span id="coordinates" style="font-family:monospace">(0, 0)</span>
            <span style="color:#555">|</span>
            <span id="status-text" class="timer">Hazır!</span>
        </div>
        <div class="info-pill">
            Tuval Yenilenmiştir Sanatınızı Konuşturun🎉 
        </div>
    </div>

    <div id="toast">Bildirim</div>
    <div id="viewport"><div id="canvas-container"><canvas id="placeCanvas"></canvas><div id="reticle"></div></div></div>
    
    <div id="zoom-controls">
        <div class="icon-btn" id="music-btn" onclick="toggleMusic()">🔇</div>
        <div class="icon-btn" onclick="adjustZoom(1.2)">+</div>
        <div class="icon-btn" onclick="adjustZoom(0.8)">-</div>
    </div>
    
    <div id="bottom-bar">
        <div class="color-btn active" style="background:#FF4500" data-color="#FF4500"></div><div class="color-btn" style="background:#FFA800" data-color="#FFA800"></div><div class="color-btn" style="background:#FFD635" data-color="#FFD635"></div><div class="color-btn" style="background:#00A368" data-color="#00A368"></div><div class="color-btn" style="background:#7EED56" data-color="#7EED56"></div><div class="color-btn" style="background:#2450A4" data-color="#2450A4"></div><div class="color-btn" style="background:#3690EA" data-color="#3690EA"></div><div class="color-btn" style="background:#51E9F4" data-color="#51E9F4"></div><div class="color-btn" style="background:#811E9F" data-color="#811E9F"></div><div class="color-btn" style="background:#FF99AA" data-color="#FF99AA"></div><div class="color-btn" style="background:#FFFFFF" data-color="#FFFFFF"></div><div class="color-btn" style="background:#9C9C9C" data-color="#9C9C9C"></div><div class="color-btn" style="background:#000000" data-color="#000000"></div><div class="color-btn" style="background:#FF0080" data-color="#FF0080"></div>
    </div>

    <script>
        const GRID_SIZE = 100, PIXEL_SIZE = 10;
        const canvas = document.getElementById('placeCanvas'), ctx = canvas.getContext('2d');
        const container = document.getElementById('canvas-container'), viewport = document.getElementById('viewport');
        const reticle = document.getElementById('reticle'), coordsDisplay = document.getElementById('coordinates');
        const statusText = document.getElementById('status-text'), toast = document.getElementById('toast'), bottomBar = document.getElementById('bottom-bar');
        let scale = 1, panX = 0, panY = 0, selectedColor = '#FF4500', pixels = {}, cooldownInterval = null;
        let isDragging = false, lastTouchX = 0, lastTouchY = 0, initialPinchDistance = 0, initialScale = 1, isPinching = false, hasMoved = false; 
        
        // MÜZİK DEĞİŞKENLERİ
        const bgMusic = document.getElementById('bg-music');
        const musicBtn = document.getElementById('music-btn');
        bgMusic.volume = 0.5; // Ses seviyesi %50

        canvas.width = GRID_SIZE * PIXEL_SIZE; canvas.height = GRID_SIZE * PIXEL_SIZE; reticle.style.width = PIXEL_SIZE+'px'; reticle.style.height = PIXEL_SIZE+'px';
        ctx.fillStyle = "#FFFFFF"; ctx.fillRect(0, 0, canvas.width, canvas.height);
        updateTransform(); fetchPixels(); setInterval(fetchPixels, 2000);
        bottomBar.addEventListener('touchmove', (e)=>{e.stopPropagation()}, {passive:false}); bottomBar.addEventListener('touchstart', (e)=>{e.stopPropagation()}, {passive:false});
        bottomBar.addEventListener('wheel', (e)=>{if(e.deltaY!==0){e.preventDefault(); bottomBar.scrollLeft+=e.deltaY;}});
        async function fetchPixels(){ try{ const res=await fetch('api/place_get.php'); if(!res.ok)return; const data=await res.json(); data.forEach(p=>{ const k=`${p.x}_${p.y}`; if(pixels[k]!==p.color){ ctx.fillStyle=p.color; ctx.fillRect(p.x*PIXEL_SIZE,p.y*PIXEL_SIZE,PIXEL_SIZE,PIXEL_SIZE); pixels[k]=p.color;} }); }catch(e){} }
        viewport.addEventListener('mousedown', (e)=>{if(e.target.closest('.color-btn')||e.target.closest('.icon-btn'))return; isDragging=true; hasMoved=false; lastTouchX=e.clientX; lastTouchY=e.clientY; viewport.style.cursor='grabbing';});
        viewport.addEventListener('mousemove', (e)=>{if(isDragging){ const dx=e.clientX-lastTouchX, dy=e.clientY-lastTouchY; if(Math.abs(dx)>2||Math.abs(dy)>2)hasMoved=true; panX+=dx; panY+=dy; lastTouchX=e.clientX; lastTouchY=e.clientY; updateTransform(); } else { updateReticle(e.clientX, e.clientY); }});
        viewport.addEventListener('mouseup', (e)=>{isDragging=false; viewport.style.cursor='crosshair'; if(!hasMoved && e.button===0){ handleTap(e.clientX, e.clientY); }});
        viewport.addEventListener('wheel', (e)=>{e.preventDefault(); adjustZoom(e.deltaY>0?0.9:1.1);}, {passive:false});
        viewport.addEventListener('touchstart', (e)=>{if(e.target.closest('.color-btn')||e.target.closest('.icon-btn'))return; hasMoved=false; if(e.touches.length===1){isDragging=true; lastTouchX=e.touches[0].clientX; lastTouchY=e.touches[0].clientY;} else if(e.touches.length===2){isDragging=false; isPinching=true; initialPinchDistance=getDistance(e.touches); initialScale=scale;}}, {passive:false});
        viewport.addEventListener('touchmove', (e)=>{e.preventDefault(); if(isPinching && e.touches.length===2){ const dist=getDistance(e.touches); const zoom=dist/initialPinchDistance; scale=Math.min(Math.max(0.5, initialScale*zoom), 8); updateTransform(); hasMoved=true; } else if(isDragging && e.touches.length===1){ const dx=e.touches[0].clientX-lastTouchX, dy=e.touches[0].clientY-lastTouchY; if(Math.abs(dx)>2||Math.abs(dy)>2)hasMoved=true; panX+=dx; panY+=dy; lastTouchX=e.touches[0].clientX; lastTouchY=e.touches[0].clientY; updateTransform(); }}, {passive:false});
        viewport.addEventListener('touchend', (e)=>{isDragging=false; isPinching=false; if(!hasMoved && e.changedTouches.length>0){ const t=e.changedTouches[0]; handleTap(t.clientX, t.clientY); }});
        function getDistance(touches){ return Math.hypot(touches[0].clientX-touches[1].clientX, touches[0].clientY-touches[1].clientY); }
        function updateTransform(){ container.style.transform=`translate(${panX}px,${panY}px) scale(${scale})`; }
        function adjustZoom(factor){ scale*=factor; scale=Math.min(Math.max(0.5,scale),8); updateTransform(); }
        function updateReticle(cx, cy){ const r=container.getBoundingClientRect(); const x=(cx-r.left)/scale, y=(cy-r.top)/scale; const gx=Math.floor(x/PIXEL_SIZE), gy=Math.floor(y/PIXEL_SIZE); if(gx>=0 && gx<GRID_SIZE && gy>=0 && gy<GRID_SIZE){ reticle.style.display='block'; reticle.style.left=(gx*PIXEL_SIZE)+'px'; reticle.style.top=(gy*PIXEL_SIZE)+'px'; coordsDisplay.innerText=`(${gx}, ${gy})`; return {gx, gy}; } else { reticle.style.display='none'; return {gx:-1, gy:-1}; } }
        function handleTap(cx, cy){ const {gx, gy} = updateReticle(cx, cy); if(gx>=0){ if(statusText.classList.contains('waiting')){ showToast("Enerjin yok, bekle!", true); return; } placePixel(gx, gy); } }
        async function placePixel(x, y){ try{ const res=await fetch('api/place_update.php', {method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify({x, y, color:selectedColor})}); const text=await res.text(); try{ const r=JSON.parse(text); if(r.status==='success'){ ctx.fillStyle=selectedColor; ctx.fillRect(x*PIXEL_SIZE,y*PIXEL_SIZE,PIXEL_SIZE,PIXEL_SIZE); showToast("Piksel Yerleştirildi!"); } else if(r.code==='COOLDOWN'){ showToast("Enerjin Bitti!", true); startCooldownTimer(r.wait_seconds); } else { showToast(r.message, true); } }catch(e){ showToast("Sunucu Hatası", true); } }catch(e){ showToast("Bağlantı Hatası", true); } }
        document.querySelectorAll('.color-btn').forEach(btn=>{ btn.addEventListener('click', (e)=>{ e.stopPropagation(); document.querySelectorAll('.color-btn').forEach(b=>b.classList.remove('active')); btn.classList.add('active'); selectedColor=btn.dataset.color; }); });
        function startCooldownTimer(s){ if(cooldownInterval)clearInterval(cooldownInterval); let r=Math.ceil(s); statusText.classList.add('waiting'); statusText.innerText=`${r}s`; cooldownInterval=setInterval(()=>{ r--; if(r<=0){ clearInterval(cooldownInterval); statusText.classList.remove('waiting'); statusText.innerText="Hazır!"; } else { statusText.innerText=`${r}s`; } }, 1000); }
        function showToast(m,e=false){ toast.innerText=m; toast.style.borderColor=e?"#ff4500":"#00A368"; toast.style.color=e?"#ff4500":"#00A368"; toast.style.opacity=1; setTimeout(()=>toast.style.opacity=0, 3000); }

        // MÜZİK KONTROL FONKSİYONU
        function toggleMusic() {
            if (bgMusic.paused) {
                bgMusic.play();
                musicBtn.innerText = "🔊"; // Ses açık ikonu
                musicBtn.classList.add("music-playing");
                showToast("Müzik Açıldı 🎵");
            } else {
                bgMusic.pause();
                musicBtn.innerText = "🔇"; // Ses kapalı ikonu
                musicBtn.classList.remove("music-playing");
                showToast("Müzik Kapatıldı");
            }
        }
    </script>
</body>
</html>