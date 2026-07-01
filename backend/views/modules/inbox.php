<?php $formTypes = defined('FORM_TYPES') ? FORM_TYPES : []; ?>

<style>
/* Split Pane Layout */
.inbox-container {
    display: flex;
    gap: 20px;
    height: calc(100vh - 200px); /* Adjust based on your header/footer */
    min-height: 500px;
}

/* Left Pane: Message List */
.inbox-list-pane {
    flex: 0 0 350px; /* Fixed width, or you could use percentage */
    display: flex;
    flex-direction: column;
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 12px;
    overflow: hidden;
}

.inbox-list-header {
    padding: 16px;
    border-bottom: 1px solid var(--border);
    background: var(--card-alt);
}

.inbox-list-content {
    flex: 1;
    overflow-y: auto;
    padding: 12px;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.msg-card {
    padding: 12px;
    border: 1px solid var(--border);
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
    background: var(--input-bg);
    position: relative;
}

.msg-card:hover {
    border-color: var(--primary);
    transform: translateY(-1px);
}

.msg-card.active {
    background: rgba(244,166,23, 0.05); /* accent color lightened */
    border-color: var(--accent);
}

.msg-card.unread {
    font-weight: 600;
}
.msg-card.unread::before {
    content: '';
    position: absolute;
    top: 16px;
    left: 8px;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: var(--accent);
}

.msg-card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 4px;
    padding-left: 12px; /* Space for unread dot */
}

.msg-sender {
    font-size: 14px;
    color: var(--text);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 150px;
}

.msg-date {
    font-size: 11px;
    color: var(--muted);
    flex-shrink: 0;
}

.msg-subject {
    font-size: 12px;
    color: var(--text-secondary);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-bottom: 8px;
    padding-left: 12px;
}

.msg-badge-row {
    padding-left: 12px;
}

/* Right Pane: Message Details */
.inbox-detail-pane {
    flex: 1;
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 12px;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.inbox-empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: var(--muted);
    text-align: center;
    padding: 40px;
}

.inbox-empty-state svg {
    margin-bottom: 16px;
    opacity: 0.5;
}

.inbox-detail-content {
    display: none; /* Hidden by default until selected */
    flex-direction: column;
    height: 100%;
}

.detail-header {
    padding: 20px;
    border-bottom: 1px solid var(--border);
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: var(--card-alt);
}

.detail-body {
    flex: 1;
    overflow-y: auto;
    padding: 24px;
}

.detail-footer {
    padding: 16px 20px;
    border-top: 1px solid var(--border);
    background: var(--card-alt);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

/* Responsive adjustments */
@media (max-width: 900px) {
    .inbox-container {
        flex-direction: column;
        height: auto;
    }
    .inbox-list-pane {
        flex: none;
        max-height: 400px; /* Limit height on mobile */
    }
    .inbox-detail-pane {
        min-height: 500px;
    }
}
</style>

<div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h3 style="font-size: 16px; font-weight:600; color:var(--text);"><?= View::escape($app['name']) ?></h3>
        <p style="font-size: 12px; color: var(--muted);"><?= View::escape($app['description'] ?? '') ?></p>
    </div>
</div>

<div class="inbox-container fade-in-up stagger-1">
    
    <!-- LEFT PANE: MESSAGE LIST -->
    <div class="inbox-list-pane">
        <div class="inbox-list-header">
            <h4 style="font-size: 14px; margin: 0; color: var(--text);">Gelen Kutusu (<?= count($messages) ?>)</h4>
        </div>
        <div class="inbox-list-content">
            <?php if (empty($messages)): ?>
                <div style="text-align: center; padding: 30px 10px; color: var(--muted); font-size: 13px;">
                    Henüz mesaj bulunmuyor.
                </div>
            <?php else: ?>
                <?php foreach ($messages as $msg): ?>
                    <?php
                        // Badge colors logic
                        $typeKey   = $msg['type'] ?? '';
                        $typeLabel = $formTypes[$typeKey] ?? $typeKey;
                        $badgeColors = [
                            'sponsorship'        => '#f4a61720',
                            'contact'            => '#00bcd420',
                            'membership'         => '#7c3aed20',
                            'volunteer'          => '#10b98120',
                            'partnership'        => '#3b82f620',
                            'event_registration' => '#f9731620',
                            'job_application'    => '#ec489920',
                            'feedback'           => '#84cc1620',
                            'press'              => '#64748b20',
                        ];
                        $badgeBorders = [
                            'sponsorship'        => '#f4a61740',
                            'contact'            => '#00bcd440',
                            'membership'         => '#7c3aed40',
                            'volunteer'          => '#10b98140',
                            'partnership'        => '#3b82f640',
                            'event_registration' => '#f9731640',
                            'job_application'    => '#ec489940',
                            'feedback'           => '#84cc1640',
                            'press'              => '#64748b40',
                        ];
                        $bg     = $badgeColors[$typeKey]  ?? 'rgba(255,255,255,0.05)';
                        $border = $badgeBorders[$typeKey] ?? 'var(--border)';
                    ?>
                    <div class="msg-card <?= $msg['status'] === 'unread' ? 'unread' : '' ?>" 
                         onclick="viewMessage(this, <?= htmlspecialchars(json_encode($msg), ENT_QUOTES, 'UTF-8') ?>)">
                        
                        <div class="msg-card-header">
                            <strong class="msg-sender"><?= View::escape($msg['sender_name'] ?: 'İsimsiz') ?></strong>
                            <span class="msg-date"><?= date('d.m.Y', strtotime($msg['created_at'])) ?></span>
                        </div>
                        
                        <div class="msg-subject">
                            <?= View::escape($msg['company_name'] ?: '-') ?> &bull; <?= View::escape($msg['category'] ?: 'Genel') ?>
                        </div>
                        
                        <div class="msg-badge-row">
                            <span class="badge" style="background:<?= $bg ?>; border:1px solid <?= $border ?>; color:var(--text); font-size:10px; padding: 2px 6px;">
                                <?= View::escape($typeLabel) ?>
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- RIGHT PANE: MESSAGE DETAILS -->
    <div class="inbox-detail-pane">
        
        <!-- Empty State -->
        <div id="emptyStatePane" class="inbox-empty-state">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <rect x="2" y="4" width="20" height="16" rx="2" ry="2"></rect>
                <path d="M22 7l-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
            </svg>
            <p>Okumak için listeden bir mesaj seçin</p>
        </div>

        <!-- Detail Content -->
        <div id="detailContentPane" class="inbox-detail-content">
            
            <div class="detail-header">
                <h3 style="margin:0; font-size:18px; display:flex; align-items:center; gap:10px;">
                    Mesaj Detayı
                    <span id="msgTypeBadge" style="display:none; font-size:11px; font-weight:500; padding:2px 10px; border-radius:20px; background:var(--accent-bg,rgba(244,166,23,0.15)); border:1px solid var(--accent,#f4a617); color:var(--accent,#f4a617);"></span>
                </h3>
                <div style="font-size: 13px; color: var(--muted);" id="msgDateFull"></div>
            </div>

            <div class="detail-body">
                <div style="display:flex; flex-direction:column; gap:20px;">
                    
                    <!-- Meta Info Grid -->
                    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:16px; background:var(--input-bg); padding:20px; border-radius:8px; border:1px solid var(--border);">
                        <div>
                            <div style="font-size:11px; color:var(--muted); margin-bottom:4px; text-transform:uppercase; letter-spacing:0.5px;">Gönderen</div>
                            <strong id="msgSender" style="font-size:15px;"></strong>
                        </div>
                        <div>
                            <div style="font-size:11px; color:var(--muted); margin-bottom:4px; text-transform:uppercase; letter-spacing:0.5px;">Kurum</div>
                            <strong id="msgCompany" style="font-size:15px;"></strong>
                        </div>
                        <div>
                            <div style="font-size:11px; color:var(--muted); margin-bottom:4px; text-transform:uppercase; letter-spacing:0.5px;">Kategori/Konu</div>
                            <strong id="msgCategory" style="font-size:15px;"></strong>
                        </div>
                    </div>

                    <!-- Contact Info -->
                    <div style="display:flex; gap:24px; padding:0 4px; flex-wrap:wrap;">
                        <div id="emailContainer">
                            <div style="font-size:12px; color:var(--muted); margin-bottom:4px;">E-posta</div>
                            <a id="msgEmail" href="#" style="color:var(--primary); text-decoration:none; display:flex; align-items:center; gap:6px; font-weight:500;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                                <span id="msgEmailText"></span>
                            </a>
                        </div>
                        <div id="phoneContainer">
                            <div style="font-size:12px; color:var(--muted); margin-bottom:4px;">Telefon</div>
                            <a id="msgPhone" href="#" style="color:var(--primary); text-decoration:none; display:flex; align-items:center; gap:6px; font-weight:500;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                <span id="msgPhoneText"></span>
                            </a>
                        </div>
                    </div>

                    <hr style="border:0; border-top:1px solid var(--border); margin:10px 0;">

                    <!-- Message Body -->
                    <div>
                        <div style="font-size:12px; color:var(--muted); margin-bottom:12px; font-weight:500;">Mesaj İçeriği:</div>
                        <div id="msgText" style="background:var(--input-bg); padding:24px; border-radius:8px; font-size:14px; line-height:1.7; white-space:pre-wrap; color:var(--text); border:1px solid var(--border);"></div>
                    </div>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="detail-footer">
                <form method="POST" id="deleteForm" onsubmit="return confirm('Bu mesajı tamamen silmek istediğinize emin misiniz?');">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="message_id" id="deleteMsgId">
                    <button type="submit" class="btn btn-outline" style="color:var(--danger); border-color:rgba(218,44,46, 0.3);">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:4px; vertical-align:middle;"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                        Mesajı Sil
                    </button>
                </form>

                <div>
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
const INBOX_FORM_TYPES = <?= json_encode($formTypes) ?>;

function getFormTypeLabel(key) {
    return INBOX_FORM_TYPES[key] || key;
}

function viewMessage(cardEl, msg) {
    // Handle UI active states
    document.querySelectorAll('.msg-card').forEach(el => el.classList.remove('active'));
    cardEl.classList.add('active');

    // Switch panes
    document.getElementById('emptyStatePane').style.display = 'none';
    document.getElementById('detailContentPane').style.display = 'flex';

    // Populate data
    document.getElementById('msgSender').innerText = msg.sender_name || '-';
    document.getElementById('msgCompany').innerText = msg.company_name || '-';
    
    let dateObj = new Date(msg.created_at);
    document.getElementById('msgDateFull').innerText = dateObj.toLocaleDateString('tr-TR', { 
        day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute:'2-digit' 
    });
    
    let emailEl = document.getElementById('msgEmail');
    let emailTextEl = document.getElementById('msgEmailText');
    let emailContainer = document.getElementById('emailContainer');
    if(msg.email) {
        emailTextEl.innerText = msg.email;
        emailEl.href = 'mailto:' + msg.email;
        emailContainer.style.display = 'block';
    } else {
        emailContainer.style.display = 'none';
    }
    
    let phoneEl = document.getElementById('msgPhone');
    let phoneTextEl = document.getElementById('msgPhoneText');
    let phoneContainer = document.getElementById('phoneContainer');
    if(msg.phone) {
        phoneTextEl.innerText = msg.phone;
        phoneEl.href = 'tel:' + msg.phone;
        phoneContainer.style.display = 'block';
    } else {
        phoneContainer.style.display = 'none';
    }

    document.getElementById('msgCategory').innerText = msg.category || '-';
    document.getElementById('msgText').innerText = msg.message || '-';

    // Form type badge
    const typeLabel = getFormTypeLabel(msg.type || '');
    const typeBadgeEl = document.getElementById('msgTypeBadge');
    if (typeBadgeEl) {
        typeBadgeEl.innerText = typeLabel;
        typeBadgeEl.style.display = typeLabel ? 'inline-block' : 'none';
    }

    // Set form ids
    document.getElementById('deleteMsgId').value = msg.id;
    document.getElementById('statusMsgId').value = msg.id;

    if (msg.status === 'unread') {
        document.getElementById('statusAction').value = 'mark_read';
        document.getElementById('statusBtnText').innerText = 'Okundu İşaretle';
    } else {
        document.getElementById('statusAction').value = 'mark_unread';
        document.getElementById('statusBtnText').innerText = 'Okunmadı İşaretle';
    }
    
    // On mobile, auto-scroll to detail pane
    if (window.innerWidth <= 900) {
        document.getElementById('detailContentPane').scrollIntoView({ behavior: 'smooth' });
    }
}
</script>
