# Development Setup

Proje iki ayri calisma alanina ayrildi:

- `frontend/`: React + Vite arayuzu
- `backend/`: PHP backend ve admin panel

## React

```bash
cd frontend
npm run dev
```

React uygulamasi Vite ile calisir:

```text
http://localhost:5173
```

React icinden `/api/...` istekleri Vite proxy ile PHP backend'e gider.

## PHP

XAMPP Apache acikken backend su adresten calisir:

```text
http://localhost/innomis.tr/backend
```

Public API ornekleri:

```text
http://localhost/innomis.tr/backend/api/site-data
http://localhost/innomis.tr/backend/api/events
```

## Build

```bash
cd frontend
npm run build
```

Build sonrasi admin React bundle'i otomatik olarak `backend/public/assets/admin/`
altina kopyalanir.
