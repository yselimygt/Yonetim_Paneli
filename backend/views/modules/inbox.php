<?php
$formTypes = defined('FORM_TYPES') ? FORM_TYPES : [];
$totalMessages = count($messages);
$unreadCount = 0;
$readCount = 0;

foreach ($messages as $messageItem) {
    if (($messageItem['status'] ?? '') === 'unread') {
        $unreadCount++;
    } else {
        $readCount++;
    }
}
?>

<style>
.inbox-v2 {
    display: flex;
    flex-direction: column;
    gap: 12px;
    min-height: 0;
}

.inbox-v2-head {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 12px;
}

.inbox-v2-title {
    margin: 0;
    color: var(--text);
    font-size: 18px;
    font-weight: 800;
    line-height: 1.2;
}

.inbox-v2-subtitle {
    margin: 4px 0 0;
    color: var(--muted);
    font-size: 12px;
}

.inbox-v2-count {
    flex: 0 0 auto;
    padding: 7px 10px;
    border: 1px solid var(--border);
    border-radius: 999px;
    background: var(--card);
    color: var(--text);
    font-size: 11px;
    font-weight: 700;
}

.inbox-v2-layout {
    display: grid;
    grid-template-columns: minmax(280px, 370px) minmax(0, 1fr);
    gap: 12px;
    height: calc(100vh - 184px);
    min-height: 520px;
}

.inbox-v2-list,
.inbox-v2-detail {
    min-width: 0;
    min-height: 0;
    border: 1px solid var(--border);
    border-radius: 14px;
    background: var(--card);
    overflow: hidden;
}

.inbox-v2-list {
    display: flex;
    flex-direction: column;
}

.inbox-v2-toolbar {
    display: flex;
    flex-direction: column;
    gap: 10px;
    padding: 12px;
    border-bottom: 1px solid var(--border);
    background: var(--card-alt);
}

.inbox-v2-toolbar-title {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    color: var(--text);
    font-size: 13px;
    font-weight: 800;
}

.inbox-v2-filters {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 6px;
}

.inbox-v2-filter {
    min-height: 32px;
    padding: 6px 7px;
    border: 1px solid var(--border);
    border-radius: 10px;
    background: var(--input-bg);
    color: var(--text);
    cursor: pointer;
    font-size: 10px;
    font-weight: 700;
    line-height: 1.1;
}

.inbox-v2-filter.is-active {
    border-color: var(--primary);
    background: var(--primary);
    color: #061019;
}

.inbox-v2-cards {
    display: flex;
    flex: 1 1 auto;
    min-height: 0;
    flex-direction: column;
    gap: 8px;
    overflow-y: auto;
    padding: 10px;
}

.inbox-v2-card {
    position: relative;
    display: grid;
    grid-template-columns: minmax(0, 1fr) auto;
    gap: 5px 9px;
    width: 100%;
    min-height: 62px;
    padding: 10px;
    border: 1px solid var(--border);
    border-radius: 12px;
    background: var(--input-bg);
    color: inherit;
    cursor: pointer;
    text-align: left;
}

.inbox-v2-card.is-unread::before {
    content: '';
    position: absolute;
    top: 13px;
    left: 8px;
    width: 7px;
    height: 7px;
    border-radius: 999px;
    background: var(--accent, var(--primary));
}

.inbox-v2-card:hover,
.inbox-v2-card.is-active {
    border-color: var(--primary);
    background: rgba(245, 158, 11, .08);
}

.inbox-v2-card.is-hidden {
    display: none !important;
}

.inbox-v2-sender {
    min-width: 0;
    overflow: hidden;
    padding-left: 12px;
    color: var(--text);
    font-size: 13px;
    font-weight: 800;
    line-height: 1.2;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.inbox-v2-date {
    color: var(--muted);
    font-size: 10px;
    line-height: 1.2;
    white-space: nowrap;
}

.inbox-v2-subject {
    min-width: 0;
    overflow: hidden;
    padding-left: 12px;
    color: var(--text-secondary);
    font-size: 11px;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.inbox-v2-type {
    align-self: end;
    justify-self: end;
    padding: 3px 7px;
    border: 1px solid rgba(59, 130, 246, .32);
    border-radius: 999px;
    background: rgba(59, 130, 246, .15);
    color: #3b82f6;
    font-size: 9px;
    font-weight: 800;
    letter-spacing: .3px;
    line-height: 1;
    text-transform: uppercase;
    white-space: nowrap;
}

.inbox-v2-empty-list,
.inbox-v2-empty-detail {
    display: flex;
    flex: 1 1 auto;
    min-height: 220px;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    gap: 8px;
    padding: 20px;
    color: var(--muted);
    text-align: center;
}

.inbox-v2-empty-detail.is-hidden {
    display: none !important;
}

.inbox-v2-empty-list i,
.inbox-v2-empty-detail i {
    font-size: 34px;
    opacity: .3;
}

.inbox-v2-detail {
    display: flex;
    flex-direction: column;
}

.inbox-v2-detail-card {
    display: flex;
    flex: 1 1 auto;
    min-height: 0;
    flex-direction: column;
    background: var(--bg);
}

.inbox-v2-detail-card.is-hidden {
    display: none !important;
}

.inbox-v2-detail-head {
    flex: 0 0 auto;
    display: grid;
    grid-template-columns: minmax(0, 1fr) auto;
    gap: 9px;
    padding: 12px;
    border-bottom: 1px solid var(--border);
    background: var(--card);
}

.inbox-v2-back {
    display: none;
}

.inbox-v2-detail-title {
    margin: 0;
    overflow: hidden;
    color: var(--text);
    font-size: 17px;
    font-weight: 900;
    line-height: 1.2;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.inbox-v2-detail-meta {
    margin-top: 3px;
    color: #60a5fa;
    font-size: 11px;
    font-weight: 700;
}

.inbox-v2-actions {
    grid-column: 1 / -1;
    display: grid;
    grid-template-columns: minmax(0, 1fr) auto;
    gap: 8px;
}

.inbox-v2-status-form,
.inbox-v2-delete-form {
    margin: 0;
}

.inbox-v2-action {
    width: 100%;
    min-height: 34px;
    padding: 6px 10px;
    border-radius: 10px !important;
    font-size: 11px !important;
    font-weight: 800 !important;
    white-space: nowrap;
}

.inbox-v2-delete {
    border-color: rgba(218, 44, 46, .35) !important;
    color: var(--danger) !important;
}

.inbox-v2-detail-body {
    flex: 1 1 auto;
    min-height: 0;
    overflow-y: auto;
    background: var(--bg);
}

.inbox-v2-meta-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 9px;
    padding: 12px;
}

.inbox-v2-info {
    min-width: 0;
    padding: 10px;
    border: 1px solid var(--border);
    border-radius: 12px;
    background: var(--card);
}

.inbox-v2-info-label {
    margin-bottom: 4px;
    color: #3b82f6;
    font-size: 9px;
    font-weight: 900;
    letter-spacing: .55px;
    line-height: 1.2;
    text-transform: uppercase;
}

.inbox-v2-info-value {
    overflow: hidden;
    color: var(--text);
    font-size: 12px;
    font-weight: 800;
    line-height: 1.35;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.inbox-v2-contact {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 9px;
    padding: 0 12px 12px;
}

.inbox-v2-contact-item {
    min-width: 0;
    padding: 10px;
    border: 1px solid var(--border);
    border-radius: 12px;
    background: var(--card);
}

.inbox-v2-contact-item.is-hidden {
    display: none !important;
}

.inbox-v2-contact-link {
    overflow: hidden;
    color: var(--primary);
    font-size: 12px;
    font-weight: 800;
    text-decoration: none;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.inbox-v2-section-title {
    margin: 0;
    padding: 4px 12px 0;
    color: var(--text);
    font-size: 13px;
    font-weight: 900;
}

.inbox-v2-message {
    margin: 10px 12px 18px;
    padding: 14px;
    border: 1px solid var(--border);
    border-radius: 12px;
    background: var(--card);
    color: var(--text);
    font-size: 13px;
    line-height: 1.65;
    white-space: pre-wrap;
}

@media (max-width: 900px) {
    .inbox-v2 {
        gap: 10px;
    }

    .inbox-v2-head {
        align-items: center;
    }

    .inbox-v2-title {
        font-size: 18px;
    }

    .inbox-v2-subtitle {
        display: none;
    }

    .inbox-v2-layout {
        display: block;
        height: auto;
        min-height: 0;
        transform: none !important;
        animation: none !important;
    }

    .inbox-v2-list {
        border-radius: 14px;
    }

    .inbox-v2-card {
        min-height: 58px;
        padding: 9px 10px;
    }

    .inbox-v2-detail {
        position: fixed;
        inset: 0;
        z-index: 5000;
        display: none;
        border: 0;
        border-radius: 0;
        background: var(--bg);
    }

    .inbox-v2-detail.is-open {
        display: flex;
    }

    .inbox-v2-empty-detail {
        display: none !important;
    }

    .inbox-v2-detail-card {
        width: 100%;
        height: 100dvh;
        background: var(--bg);
    }

    .inbox-v2-detail-head {
        grid-template-columns: auto minmax(0, 1fr);
        gap: 7px 9px;
        padding: max(8px, env(safe-area-inset-top)) 10px 8px;
    }

    .inbox-v2-back {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        grid-column: 1;
        grid-row: 1;
        width: 34px;
        min-width: 34px;
        min-height: 34px;
        border: 1px solid var(--border);
        border-radius: 10px;
        background: var(--input-bg);
        color: var(--text);
        cursor: pointer;
        font-size: 24px;
        line-height: 1;
    }

    .inbox-v2-heading {
        grid-column: 2;
        grid-row: 1;
        min-width: 0;
    }

    .inbox-v2-detail-title {
        font-size: 14px;
    }

    .inbox-v2-actions {
        grid-column: 1 / -1;
        grid-template-columns: 1fr 1fr;
        gap: 7px;
    }

    .inbox-v2-action {
        min-height: 32px;
        padding: 5px 9px;
        font-size: 10px !important;
    }

    .inbox-v2-meta-grid {
        grid-template-columns: 1fr;
        gap: 7px;
        padding: 8px 10px;
    }

    .inbox-v2-info {
        padding: 8px;
        border-radius: 10px;
    }

    .inbox-v2-contact {
        grid-template-columns: 1fr;
        gap: 7px;
        padding: 0 10px 8px;
    }

    .inbox-v2-contact-item {
        padding: 8px;
        border-radius: 10px;
    }

    .inbox-v2-section-title {
        padding: 4px 10px 0;
        font-size: 12px;
    }

    .inbox-v2-message {
        margin: 8px 10px calc(18px + env(safe-area-inset-bottom));
        padding: 12px;
        border-radius: 10px;
        font-size: 12px;
        line-height: 1.55;
    }

    body.inbox-v2-detail-open {
        overflow: hidden !important;
    }
}
</style>

<div class="inbox-v2">
    <header class="inbox-v2-head">
        <div>
            <h3 class="inbox-v2-title"><?= View::escape($app['name']) ?></h3>
            <p class="inbox-v2-subtitle"><?= View::escape($app['description'] ?? '') ?></p>
        </div>
        <div class="inbox-v2-count"><?= $totalMessages ?> mesaj</div>
    </header>

    <div class="inbox-v2-layout">
        <section class="inbox-v2-list" aria-label="Mesaj listesi">
            <div class="inbox-v2-toolbar">
                <div class="inbox-v2-toolbar-title">
                    <span>Gelen Kutusu</span>
                    <span><?= $totalMessages ?></span>
                </div>
                <div class="inbox-v2-filters" role="tablist" aria-label="Mesaj filtresi">
                    <button type="button" class="inbox-v2-filter is-active" data-filter="all">Tümü</button>
                    <button type="button" class="inbox-v2-filter" data-filter="unread">Okunmamış <?= $unreadCount ?></button>
                    <button type="button" class="inbox-v2-filter" data-filter="read">Okunmuş <?= $readCount ?></button>
                </div>
            </div>

            <div class="inbox-v2-cards" id="inboxList">
                <?php if (empty($messages)): ?>
                    <div class="inbox-v2-empty-list">
                        <i class="fas fa-inbox"></i>
                        <span>Henüz mesaj bulunmuyor.</span>
                    </div>
                <?php else: ?>
                    <?php foreach ($messages as $message): ?>
                        <?php
                            $typeKey = $message['type'] ?? '';
                            $typeLabel = $formTypes[$typeKey] ?? ($typeKey ?: 'Genel');
                            $statusKey = ($message['status'] ?? '') === 'unread' ? 'unread' : 'read';
                            $messageJson = htmlspecialchars(
                                json_encode($message, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP),
                                ENT_QUOTES,
                                'UTF-8'
                            );
                        ?>
                        <button
                            type="button"
                            class="inbox-v2-card <?= $statusKey === 'unread' ? 'is-unread' : '' ?>"
                            data-message-card
                            data-status="<?= $statusKey ?>"
                            data-message="<?= $messageJson ?>"
                        >
                            <span class="inbox-v2-sender"><?= View::escape($message['sender_name'] ?: 'İsimsiz') ?></span>
                            <span class="inbox-v2-date"><?= !empty($message['created_at']) ? date('d.m.Y', strtotime($message['created_at'])) : '-' ?></span>
                            <span class="inbox-v2-subject"><?= View::escape($message['company_name'] ?: '-') ?> &bull; <?= View::escape($message['category'] ?: 'Genel') ?></span>
                            <span class="inbox-v2-type"><?= View::escape($typeLabel) ?></span>
                        </button>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

        <aside class="inbox-v2-detail" id="inboxDetailPanel" aria-label="Mesaj detayı">
            <div class="inbox-v2-empty-detail" id="inboxEmptyDetail">
                <i class="fas fa-envelope-open-text"></i>
                <strong>Mesaj seçin</strong>
                <span>Okumak için listeden bir mesaj seçin.</span>
            </div>

            <div class="inbox-v2-detail-card is-hidden" id="inboxDetailCard">
                <div class="inbox-v2-detail-head">
                    <button type="button" class="inbox-v2-back" onclick="closeInboxDetail()" aria-label="Mesaj listesine dön">‹</button>
                    <div class="inbox-v2-heading">
                        <h3 class="inbox-v2-detail-title" id="inboxDetailTitle">Mesaj Detayı</h3>
                        <div class="inbox-v2-detail-meta" id="inboxDetailMeta">-</div>
                    </div>

                    <div class="inbox-v2-actions">
                        <form method="POST" class="inbox-v2-delete-form" onsubmit="return confirm('Bu mesajı tamamen silmek istediğinize emin misiniz?');">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="message_id" id="deleteMessageId">
                            <button type="submit" class="btn btn-outline inbox-v2-action inbox-v2-delete">Mesajı Sil</button>
                        </form>

                        <form method="POST" class="inbox-v2-status-form">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                            <input type="hidden" name="action" id="messageStatusAction" value="mark_read">
                            <input type="hidden" name="message_id" id="statusMessageId">
                            <button type="submit" class="btn btn-primary inbox-v2-action" id="messageStatusButton">Okundu İşaretle</button>
                        </form>
                    </div>
                </div>

                <div class="inbox-v2-detail-body" id="inboxDetailBody">
                    <div class="inbox-v2-meta-grid">
                        <div class="inbox-v2-info">
                            <div class="inbox-v2-info-label">Gönderen</div>
                            <div class="inbox-v2-info-value" id="detailSender">-</div>
                        </div>
                        <div class="inbox-v2-info">
                            <div class="inbox-v2-info-label">Kurum</div>
                            <div class="inbox-v2-info-value" id="detailCompany">-</div>
                        </div>
                        <div class="inbox-v2-info">
                            <div class="inbox-v2-info-label">Kategori / Konu</div>
                            <div class="inbox-v2-info-value" id="detailCategory">-</div>
                        </div>
                    </div>

                    <div class="inbox-v2-contact">
                        <div class="inbox-v2-contact-item" id="detailEmailBox">
                            <div class="inbox-v2-info-label">E-posta</div>
                            <a class="inbox-v2-contact-link" id="detailEmail" href="#">-</a>
                        </div>
                        <div class="inbox-v2-contact-item" id="detailPhoneBox">
                            <div class="inbox-v2-info-label">Telefon</div>
                            <a class="inbox-v2-contact-link" id="detailPhone" href="#">-</a>
                        </div>
                    </div>

                    <h4 class="inbox-v2-section-title">Mesaj İçeriği</h4>
                    <div class="inbox-v2-message" id="detailMessage">-</div>
                </div>
            </div>
        </aside>
    </div>
</div>

<script>
const INBOX_FORM_TYPES = <?= json_encode($formTypes) ?>;

document.addEventListener('DOMContentLoaded', () => {
    document.body.classList.remove('inbox-v2-detail-open');

    const cards = Array.from(document.querySelectorAll('[data-message-card]'));
    const filters = Array.from(document.querySelectorAll('.inbox-v2-filter'));

    filters.forEach((filterButton) => {
        filterButton.addEventListener('click', () => {
            const filter = filterButton.dataset.filter || 'all';
            filters.forEach((button) => button.classList.toggle('is-active', button === filterButton));

            cards.forEach((card) => {
                const isVisible = filter === 'all' || card.dataset.status === filter;
                card.classList.toggle('is-hidden', !isVisible);
                if (!isVisible) {
                    card.classList.remove('is-active');
                }
            });

            if (!document.querySelector('[data-message-card].is-active')) {
                resetInboxDetail();
            }
        });
    });

    cards.forEach((card) => {
        card.addEventListener('click', () => {
            try {
                const message = JSON.parse(card.dataset.message || '{}');
                openInboxDetail(message, card);
            } catch (error) {
                console.error('Mesaj verisi okunamadı:', error);
            }
        });
    });
});

function setInboxText(id, value) {
    const element = document.getElementById(id);
    if (element) {
        element.textContent = value || '-';
        element.title = value || '-';
    }
}

function getInboxTypeLabel(key) {
    return INBOX_FORM_TYPES[key] || key || 'Genel';
}

function formatInboxDate(value) {
    if (!value) return '-';
    const date = new Date(value);
    if (Number.isNaN(date.getTime())) return value;
    return date.toLocaleString('tr-TR', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

function setInboxContact(boxId, linkId, value, prefix) {
    const box = document.getElementById(boxId);
    const link = document.getElementById(linkId);
    if (!box || !link) return;

    if (value) {
        box.classList.remove('is-hidden');
        link.textContent = value;
        link.title = value;
        link.href = prefix + value;
    } else {
        box.classList.add('is-hidden');
        link.textContent = '-';
        link.removeAttribute('href');
    }
}

function openInboxDetail(message, card) {
    document.querySelectorAll('[data-message-card]').forEach((item) => item.classList.remove('is-active'));
    card?.classList.add('is-active');

    document.getElementById('inboxEmptyDetail')?.classList.add('is-hidden');
    document.getElementById('inboxDetailCard')?.classList.remove('is-hidden');
    document.getElementById('inboxDetailPanel')?.classList.add('is-open');
    document.body.classList.add('inbox-v2-detail-open');

    const typeLabel = getInboxTypeLabel(message.type || '');

    setInboxText('inboxDetailTitle', message.sender_name || 'İsimsiz');
    setInboxText('inboxDetailMeta', `${typeLabel} • ${formatInboxDate(message.created_at)}`);
    setInboxText('detailSender', message.sender_name || 'İsimsiz');
    setInboxText('detailCompany', message.company_name || '-');
    setInboxText('detailCategory', message.category || 'Genel');
    setInboxText('detailMessage', message.message || '-');

    setInboxContact('detailEmailBox', 'detailEmail', message.email || '', 'mailto:');
    setInboxContact('detailPhoneBox', 'detailPhone', message.phone || '', 'tel:');

    const deleteId = document.getElementById('deleteMessageId');
    const statusId = document.getElementById('statusMessageId');
    const statusAction = document.getElementById('messageStatusAction');
    const statusButton = document.getElementById('messageStatusButton');

    if (deleteId) deleteId.value = message.id || '';
    if (statusId) statusId.value = message.id || '';

    if (message.status === 'unread') {
        if (statusAction) statusAction.value = 'mark_read';
        if (statusButton) statusButton.textContent = 'Okundu İşaretle';
    } else {
        if (statusAction) statusAction.value = 'mark_unread';
        if (statusButton) statusButton.textContent = 'Okunmadı İşaretle';
    }

    document.getElementById('inboxDetailBody')?.scrollTo({ top: 0, behavior: 'auto' });
}

function resetInboxDetail() {
    document.getElementById('inboxEmptyDetail')?.classList.remove('is-hidden');
    document.getElementById('inboxDetailCard')?.classList.add('is-hidden');
    closeInboxDetail();
}

function closeInboxDetail() {
    document.getElementById('inboxDetailPanel')?.classList.remove('is-open');
    document.body.classList.remove('inbox-v2-detail-open');
}

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
        closeInboxDetail();
    }
});
</script>
