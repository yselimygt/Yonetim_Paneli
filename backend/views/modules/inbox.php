<div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h3 style="font-size: 16px; font-weight:600; color:var(--text);"><?= View::escape($app['name']) ?></h3>
        <p style="font-size: 12px; color: var(--muted);"><?= View::escape($app['description'] ?? '') ?></p>
    </div>
</div>

<div class="card fade-in-up stagger-1">
    <div style="overflow-x: auto;">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 40px;">Durum</th>
                    <th>Form Türü</th>
                    <th>Gönderen</th>
                    <th>Kurum</th>
                    <th>Konu/Kategori</th>
                    <th>Tarih</th>
                    <th style="text-align: right;">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($messages)): ?>
                <tr>
                    <td colspan="7" style="text-align: center; padding: 30px; color: var(--muted);">
                        Gelen kutusu boş. Henüz yeni mesaj yok.
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach ($messages as $msg): ?>
                    <tr style="<?= $msg['status'] === 'unread' ? 'background: rgba(0,188,212,0.05); font-weight: 500;' : '' ?>">
                        <td>
                            <?php if ($msg['status'] === 'unread'): ?>
                                <span style="display:inline-block; width:10px; height:10px; border-radius:50%; background:var(--accent);"></span>
                            <?php else: ?>
                                <span style="display:inline-block; width:10px; height:10px; border-radius:50%; background:var(--muted);"></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge" style="background:var(--card-alt); border:1px solid var(--border); color:var(--text);">
                                <?= View::escape($msg['type']) ?>
                            </span>
                        </td>
                        <td><?= View::escape($msg['sender_name'] ?: '-') ?></td>
                        <td><?= View::escape($msg['company_name'] ?: '-') ?></td>
                        <td><?= View::escape($msg['category'] ?: '-') ?></td>
                        <td><?= date('d.m.Y H:i', strtotime($msg['created_at'])) ?></td>
                        <td style="text-align: right;">
                            <button class="btn btn-sm btn-outline" style="padding:4px 8px; font-size:12px;" onclick="viewMessage(<?= htmlspecialchars(json_encode($msg), ENT_QUOTES, 'UTF-8') ?>)">Oku</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Mesaj Okuma Modalı -->
<div id="messageModal" class="modal">
    <div class="modal-content" style="max-width: 600px;">
        <div class="modal-header">
            <h3 id="modalTitle">Mesaj Detayı</h3>
            <button type="button" class="close-btn" onclick="closeMessageModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div style="display:flex; flex-direction:column; gap:16px;">
                <div style="display:flex; justify-content:space-between; flex-wrap:wrap; gap:16px; background:var(--input-bg); padding:16px; border-radius:8px;">
                    <div>
                        <div style="font-size:11px; color:var(--muted);">Gönderen</div>
                        <strong id="msgSender"></strong>
                    </div>
                    <div>
                        <div style="font-size:11px; color:var(--muted);">Kurum</div>
                        <strong id="msgCompany"></strong>
                    </div>
                    <div>
                        <div style="font-size:11px; color:var(--muted);">Tarih</div>
                        <strong id="msgDate"></strong>
                    </div>
                </div>

                <div style="display:flex; gap:16px; flex-wrap:wrap;">
                    <div style="flex:1;">
                        <div style="font-size:11px; color:var(--muted);">E-posta</div>
                        <a id="msgEmail" href="#" style="color:var(--accent); text-decoration:none;"></a>
                    </div>
                    <div style="flex:1;">
                        <div style="font-size:11px; color:var(--muted);">Telefon</div>
                        <a id="msgPhone" href="#" style="color:var(--accent); text-decoration:none;"></a>
                    </div>
                </div>

                <div>
                    <div style="font-size:11px; color:var(--muted);">Kategori/Konu</div>
                    <strong id="msgCategory"></strong>
                </div>

                <div>
                    <div style="font-size:11px; color:var(--muted); margin-bottom:4px;">Mesaj</div>
                    <div id="msgText" style="background:var(--input-bg); padding:16px; border-radius:8px; font-size:13px; line-height:1.6; white-space:pre-wrap; color:var(--text);"></div>
                </div>
            </div>
            
            <div style="margin-top:24px; border-top:1px solid var(--border); padding-top:16px; display:flex; justify-content:space-between; gap:12px;">
                <form method="POST" id="deleteForm" onsubmit="return confirm('Bu mesajı tamamen silmek istediğinize emin misiniz?');">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="message_id" id="deleteMsgId">
                    <button type="submit" class="btn btn-outline" style="color:var(--danger); border-color:var(--danger);">Sil</button>
                </form>

                <div style="display:flex; gap:8px;">
                    <form method="POST" id="statusForm">
                        <input type="hidden" name="action" id="statusAction" value="mark_read">
                        <input type="hidden" name="message_id" id="statusMsgId">
                        <button type="submit" class="btn btn-primary" id="statusBtnText">Okundu İşaretle</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function viewMessage(msg) {
    document.getElementById('msgSender').innerText = msg.sender_name || '-';
    document.getElementById('msgCompany').innerText = msg.company_name || '-';
    
    let dateObj = new Date(msg.created_at);
    document.getElementById('msgDate').innerText = dateObj.toLocaleDateString('tr-TR', { hour: '2-digit', minute:'2-digit' });
    
    let emailEl = document.getElementById('msgEmail');
    if(msg.email) {
        emailEl.innerText = msg.email;
        emailEl.href = 'mailto:' + msg.email;
    } else {
        emailEl.innerText = '-';
        emailEl.removeAttribute('href');
    }
    
    let phoneEl = document.getElementById('msgPhone');
    if(msg.phone) {
        phoneEl.innerText = msg.phone;
        phoneEl.href = 'tel:' + msg.phone;
    } else {
        phoneEl.innerText = '-';
        phoneEl.removeAttribute('href');
    }

    document.getElementById('msgCategory').innerText = msg.category || msg.type || '-';
    document.getElementById('msgText').innerText = msg.message || '-';

    document.getElementById('deleteMsgId').value = msg.id;
    document.getElementById('statusMsgId').value = msg.id;

    if (msg.status === 'unread') {
        document.getElementById('statusAction').value = 'mark_read';
        document.getElementById('statusBtnText').innerText = 'Okundu İşaretle';
    } else {
        document.getElementById('statusAction').value = 'mark_unread';
        document.getElementById('statusBtnText').innerText = 'Okunmadı İşaretle';
    }

    document.getElementById('messageModal').classList.add('active');
}

function closeMessageModal() {
    document.getElementById('messageModal').classList.remove('active');
}
</script>
