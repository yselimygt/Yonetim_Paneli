import { 
    Calendar, Users, Lightbulb, TrendingUp, Code, 
    MonitorSmartphone, Palette, Bot, Sparkles, MapPin, Mail, Instagram, Linkedin, Github, Youtube, ChevronDown, MessageSquare
  } from 'lucide-react';
  
  export const navLinks = [
    { name: 'Hakkımızda', href: '/#about' },
    { name: 'Etkinlikler', href: '/events' },
    { name: 'Galeri', href: '/gallery' },
    { name: 'Ekip', href: '/team' },
    { name: 'İletişim', href: '/iletisim' },
  ];

  export const popupConfig = {
    enabled: true,
    storageKey: 'innomis_announcement_seen_v1',
    autoCloseSec: 5,
    badge: '📣 Duyuru',
    title: 'InnoMIS Community\'ye Hoş Geldin!',
    description:
      'Teknoloji, girişimcilik ve yapay zeka dünyasına adım atmaya hazır mısın? InnoMIS Community\'nin etkinliklerini, eğitimlerini ve projelerini takip et — topluluğun bir parçası ol!',
    cta: {
      label: 'Topluluğa Katıl',
      href: '/kayit',
    },
    secondaryCta: {
      label: 'Keşfet',
      href: '/#about',
    },
  };

  export const stats = [
    { label: 'Aktif Üye', value: '500+', desc: 'Topluluk ekosisteminde yer alan öğrenci ağı.' },
    { label: 'Etkinlik', value: '25+', desc: 'Workshop, panel, zirve ve buluşmalar.' },
    { label: 'Eğitim Saati', value: '40+', desc: 'Teknoloji ve kariyer odaklı eğitim içerikleri.' },
    { label: 'İş Birliği', value: '15+', desc: 'Marka, topluluk ve sektör paydaşları.' }
  ];
  
  export const sponsors = [
    '/innomis/assets/sponsor/kapsul.png',
    '/innomis/assets/sponsor/redbull.png',
    '/innomis/assets/sponsor/sorbil.webp',
    '/innomis/assets/sponsor/techcareernet.png',
    '/assets/uni/kgtuLogo.png',
    '/assets/uni/ktoLogo.png',
    '/assets/uni/ktunLogo.png',
    '/assets/uni/neuLogo.png',
    '/assets/uni/selcukLogo.png'
  ];
  
  export const values = [
    { icon: <Sparkles className="w-6 h-6 text-primary" />, title: 'İnovasyon', desc: 'Fikirleri gerçek projelere dönüştüren üretim kültürü.' },
    { icon: <MonitorSmartphone className="w-6 h-6 text-accent-cyan" />, title: 'Teknoloji', desc: 'Yapay zeka, yazılım, veri, tasarım ve dijital dönüşüm odaklı içerikler.' },
    { icon: <Users className="w-6 h-6 text-accent-purple" />, title: 'Liderlik', desc: 'Öğrencilerin ekip yönetimi, organizasyon ve sorumluluk alma becerilerini geliştirmesi.' },
    { icon: <TrendingUp className="w-6 h-6 text-accent-green" />, title: 'Kariyer', desc: 'Etkinlikler, paneller, iş birlikleri ve sektör bağlantılarıyla kariyer gelişimi.' }
  ];
  
  export const events = [
    {
      title: 'MIS CONNECT\'26',
      desc: 'Teknoloji, kariyer, girişimcilik ve inovasyon ekosistemini öğrencilerle buluşturan güçlü bir zirve deneyimi.',
      date: 'Nisan 2026',
      location: 'Konya',
      category: 'Zirve',
      tagColor: 'bg-primary/20 text-primary border-primary/20',
      status: 'upcoming',
      image: 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&q=80&w=800',
      targetDate: '2026-04-15T09:00:00', // for countdown
      registrationLink: '#',
      speakers: [
        { name: 'Dr. Ahmet Yılmaz', role: 'Yapay Zeka Araştırmacısı' },
        { name: 'Zeynep Kaya', role: 'Startup Kurucusu' }
      ],
      agenda: [
        { time: '09:00 - 10:00', activity: 'Kayıt ve Networking' },
        { time: '10:00 - 11:30', activity: 'Açılış Konuşmaları' },
        { time: '11:45 - 13:00', activity: 'Geleceğin Teknolojileri Paneli' }
      ]
    },
    {
      title: 'AI Workshop',
      desc: 'Yapay zeka araçları ve üretken teknoloji üzerine uygulamalı eğitim.',
      date: 'Yakında',
      location: 'Selçuk Üniversitesi',
      category: 'Eğitim',
      tagColor: 'bg-accent-purple/20 text-accent-purple border-accent-purple/20',
      status: 'upcoming',
      image: 'https://images.unsplash.com/photo-1485827404703-89b55fcc595e?auto=format&fit=crop&q=80&w=800',
      registrationLink: '#',
      speakers: [
        { name: 'Murat Can', role: 'AI Engineer' }
      ],
      agenda: [
        { time: '14:00 - 15:00', activity: 'Prompt Engineering Temelleri' },
        { time: '15:15 - 16:30', activity: 'Uygulamalı Proje Geliştirme' }
      ]
    },
    {
      title: 'Girişimcilik Buluşması',
      desc: 'Fikirden ürüne giden süreç ve startup ekosistemi üzerine söyleşi.',
      date: 'Kasım 2025',
      location: 'KTO Karatay Üni.',
      category: 'Panel',
      tagColor: 'bg-accent-cyan/20 text-accent-cyan border-accent-cyan/20',
      status: 'past',
      image: 'https://images.unsplash.com/photo-1556761175-5973dc0f32d7?auto=format&fit=crop&q=80&w=800',
      speakers: [
        { name: 'Elif Şahin', role: 'Melek Yatırımcı' }
      ],
      agenda: [
        { time: '13:00 - 15:00', activity: 'Girişimcilik Söyleşisi' }
      ]
    },
    {
      title: 'Kariyer Paneli',
      desc: 'Sektör profesyonellerinden kariyer yolculuğu tavsiyeleri.',
      date: 'Aralık 2025',
      location: 'KTÜN',
      category: 'Kariyer',
      tagColor: 'bg-accent-green/20 text-accent-green border-accent-green/20',
      status: 'past',
      image: 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&q=80&w=800',
      speakers: [
        { name: 'Burak Aydın', role: 'Kariyer Danışmanı' }
      ]
    }
  ];
  
  export const projects = [
    {
      title: 'Topluluk Yönetim Sistemi',
      desc: 'Üye, ekip ve etkinlik süreçlerini daha düzenli yönetmek için geliştirilen dijital altyapı.',
      status: 'Geliştiriliyor',
      category: 'Web',
      icon: <Code className="w-5 h-5" />,
      link: null
    },
    {
      title: 'InnoMIS Akademi',
      desc: 'Topluluk içi düzenlenen eğitimlerin (video, not, atölye materyalleri) depolanacağı dijital öğrenme portalı.',
      status: 'Yakında',
      category: 'E-Learning',
      icon: <MonitorSmartphone className="w-5 h-5" />,
      link: null
    },
    {
      title: 'Kariyer & Mezun Ağı Portalı',
      desc: 'Mezunların staj ve iş ilanlarını aktif öğrencilerle paylaşabildiği, mentorluk süreçlerinin yürütüldüğü iletişim aracı.',
      status: 'Planlanıyor',
      category: 'HR Tech',
      icon: <Users className="w-5 h-5" />,
      link: null
    },
    {
      title: 'Hackathon Yönetim Aracı',
      desc: 'Takım oluşturma, proje teslimi ve jüri puanlama süreçlerini dijitalleştiren altyapı.',
      status: 'Fikir Aşaması',
      category: 'Event Tech',
      icon: <Sparkles className="w-5 h-5" />,
      link: null
    },
    {
      title: 'Sosyal Medya Tasarım Hafızası',
      desc: 'Topluluğun kurumsal kimliğini koruyan görsel arşiv ve tasarım sistemi.',
      status: 'Aktif',
      category: 'Design',
      icon: <Palette className="w-5 h-5" />,
      link: '#'
    },
    {
      title: 'AI Deney Alanı',
      desc: 'Yapay zeka araçlarıyla içerik, otomasyon ve fikir geliştirme denemeleri.',
      status: 'Deneysel',
      category: 'AI',
      icon: <Bot className="w-5 h-5" />,
      link: '#'
    }
  ];
  
  export const achievements = [
    {
      year: '2026',
      title: 'MIS CONNECT’26 Hazırlıkları',
      desc: 'Teknoloji ve kariyer odaklı büyük zirve için ekipler, tasarımlar ve dijital altyapı hazırlıkları başlatıldı.',
      tag: 'Zirve',
      tagColor: 'text-primary border-primary/30'
    },
    {
      year: '2025',
      title: 'Yılın Sosyal Medyayı En İyi Yöneten Topluluğu',
      desc: 'Topluluğun dijital iletişim, içerik ve görsel kimlik başarısı ödüllendirildi.',
      tag: 'Ödül',
      tagColor: 'text-accent-green border-accent-green/30'
    },
    {
      year: '2025',
      title: 'Kurumsal Kimlik Yenilendi',
      desc: 'Renk paleti, görsel dil, maskot, sosyal medya tasarımları ve web kimliği yeniden tasarlandı.',
      tag: 'Marka',
      tagColor: 'text-accent-purple border-accent-purple/30'
    },
    {
      year: '2024',
      title: 'Yeni Ekip Yapılanması',
      desc: 'Etkinlik, sosyal medya, Ar-Ge, sponsorluk ve operasyon ekipleri daha sistemli hale getirildi.',
      tag: 'Ekip',
      tagColor: 'text-accent-cyan border-accent-cyan/30'
    }
  ];
  
  export const awards = [
    {
      year: '2025',
      title: 'Yılın En İyi Topluluk İletişimi',
      organization: 'Tech Community Awards',
      desc: 'Topluluğun dijital iletişim ve sosyal medya başarısı.',
    },
    {
      year: '2024',
      title: 'Yılın İnovatif Teknoloji Topluluğu',
      organization: 'Genç Bilişimciler Zirvesi',
      desc: 'Yıl boyunca üretilen projeler ve sağlanan ekosistem katkısı.',
    },
    {
      year: '2023',
      title: 'En Aktif Öğrenci Topluluğu',
      organization: 'Üniversite Etkinlikleri Zirvesi',
      desc: 'Yıl boyunca düzenlenen yüksek katılımlı teknik eğitimler.',
    }
  ];
  
  export const galleryItems = [
    { title: 'MIS CONNECT Sahnesi', category: 'Zirve', image: 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?q=80&w=1000&auto=format&fit=crop' },
    { title: 'AI Workshop', category: 'Eğitim', image: 'https://images.unsplash.com/photo-1531482615713-2afd69097998?q=80&w=1000&auto=format&fit=crop' },
    { title: 'Ekip Buluşması', category: 'Topluluk', image: 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?q=80&w=1000&auto=format&fit=crop' },
    { title: 'Kariyer Paneli', category: 'Etkinlik', image: 'https://images.unsplash.com/photo-1475721025505-44fbbf23d436?q=80&w=1000&auto=format&fit=crop' },
    { title: 'Topluluk Anı', category: 'Sosyal', image: 'https://images.unsplash.com/photo-1511632765486-a01980e01a18?q=80&w=1000&auto=format&fit=crop' },
    { title: 'Hazırlık Süreci', category: 'Ekip', image: 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?q=80&w=1000&auto=format&fit=crop' }
  ];
  
  export const teamStructure = {
    board: [
      { name: 'Ahmet Yılmaz', role: 'Yönetim Kurulu Başkanı', initials: 'AY', linkedin: '#',
        faculty: 'Mühendislik Mimarlık Fakültesi', department: 'Bilgisayar Mühendisliği', grade: '4. Sınıf',
        email: 'ahmet.yilmaz@innomis.tr', universityLogo: '/assets/uni/neuLogo.png',
        communityHistory: [
          { period: 'Oca 2025 – Devam ediyor · 6 ay', title: 'Yönetim Kurulu Başkanı', dept: 'Yönetim Kurulu' },
          { period: 'Mar 2024 – Oca 2025 · 10 ay', title: 'Genel Koordinatör', dept: 'Organizasyon' },
          { period: '2023', title: 'Genel Üye', dept: 'InnoMIS Community' },
        ]
      },
      { name: 'Ayşe Demir', role: 'Başkan Yardımcısı', initials: 'AD', linkedin: '#',
        faculty: 'Sosyal ve Beşeri Bilimler Fakültesi', department: 'İşletme', grade: '3. Sınıf',
        email: 'ayse.demir@innomis.tr', universityLogo: '/assets/uni/neuLogo.png',
        communityHistory: [
          { period: 'Şub 2025 – Devam ediyor · 5 ay', title: 'Başkan Yardımcısı', dept: 'Yönetim Kurulu' },
          { period: 'Eyl 2024 – Şub 2025 · 6 ay', title: 'İnsan Kaynakları Direktörü', dept: 'İnsan Kaynakları' },
          { period: '2023', title: 'Genel Üye', dept: 'InnoMIS Community' },
        ]
      }
    ],
    departments: [
      {
        id: 'arge',
        name: 'Ar-Ge ve Teknoloji',
        director: { 
          name: 'Yavuz Selim Yiğit', 
          role: 'Ar-Ge ve Teknoloji Direktörü', 
          initials: 'YSY', 
          linkedin: 'https://linkedin.com/in/yavuz-selim-yigit',
          faculty: 'Mühendislik Mimarlık Fakültesi',
          department: 'Elektrik-Elektronik Mühendisliği',
          grade: '3. Sınıf',
          email: 'mail@yavuzselimyigit.com',
          phone: '+90 535 596 1046',
          github: 'https://github.com/yselimygt',
          website: 'yavuzselimyigit.com',
          communityHistory: [
            { period: 'Kas 2025 – Devam ediyor · 8 ay', title: 'AR-GE ve Teknoloji Direktörü', dept: 'Ar-Ge ve Teknoloji' },
            { period: 'Ağu 2025 – Devam ediyor · 11 ay', title: 'Sosyal Medya İçerik Oluşturucu', dept: 'Sosyal Medya' },
            { period: '2024', title: 'Genel Üye', dept: 'InnoMIS Community' },
          ]
        },
        members: []
      },
      {
        id: 'ik',
        name: 'İnsan Kaynakları',
        director: { name: 'Merve Çelik', role: 'İnsan Kaynakları Direktörü', initials: 'MÇ', linkedin: '#',
          faculty: 'Hukuk Fakültesi', department: 'Hukuk', grade: '3. Sınıf',
          email: 'merve.celik@innomis.tr', universityLogo: '/assets/uni/neuLogo.png',
          communityHistory: [
            { period: 'Kas 2025 – Devam ediyor · 7 ay', title: 'İnsan Kaynakları Direktörü', dept: 'İnsan Kaynakları' },
            { period: 'Mar 2025 – Kas 2025 · 8 ay', title: 'Üye Koordinatörü', dept: 'İnsan Kaynakları' },
            { period: '2024', title: 'Genel Üye', dept: 'InnoMIS Community' },
          ]
        },
        members: [
          { name: 'Ceren Yıldız', role: 'Ekip Koordinatörü', initials: 'CY', linkedin: '#',
            faculty: 'Sosyal ve Beşeri Bilimler Fakültesi', department: 'Psikoloji', grade: '2. Sınıf',
            email: 'ceren.yildiz@innomis.tr', universityLogo: '/assets/uni/neuLogo.png',
            communityHistory: [
              { period: 'Oca 2025 – Devam ediyor · 6 ay', title: 'Ekip Koordinatörü', dept: 'İnsan Kaynakları' },
              { period: '2024', title: 'Genel Üye', dept: 'InnoMIS Community' },
            ]
          },
          { name: 'Fatma Gül', role: 'Üye İletişimi', initials: 'FG', linkedin: '#',
            faculty: 'Mühendislik Mimarlık Fakültesi', department: 'Endüstri Mühendisliği', grade: '2. Sınıf',
            email: 'fatma.gul@innomis.tr', universityLogo: '/assets/uni/neuLogo.png',
            communityHistory: [
              { period: 'Şub 2025 – Devam ediyor · 5 ay', title: 'Üye İletişim Sorumlusu', dept: 'İnsan Kaynakları' },
              { period: '2024', title: 'Genel Üye', dept: 'InnoMIS Community' },
            ]
          }
        ]
      },
      {
        id: 'organizasyon',
        name: 'Organizasyon',
        director: { name: 'Elif Şahin', role: 'Organizasyon Direktörü', initials: 'EŞ', linkedin: '#',
          faculty: 'Sosyal ve Beşeri Bilimler Fakültesi', department: 'Sosyoloji', grade: '3. Sınıf',
          email: 'elif.sahin@innomis.tr', universityLogo: '/assets/uni/neuLogo.png',
          communityHistory: [
            { period: 'Eyl 2025 – Devam ediyor · 9 ay', title: 'Organizasyon Direktörü', dept: 'Organizasyon' },
            { period: 'Oca 2025 – Eyl 2025 · 8 ay', title: 'Etkinlik Planlama Sorumlusu', dept: 'Organizasyon' },
            { period: '2024', title: 'Genel Üye', dept: 'InnoMIS Community' },
          ]
        },
        members: [
          { name: 'Burak Aydın', role: 'Sponsorluk Sorumlusu', initials: 'BA', linkedin: '#',
            faculty: 'Mühendislik Mimarlık Fakültesi', department: 'Makine Mühendisliği', grade: '3. Sınıf',
            email: 'burak.aydin@innomis.tr', universityLogo: '/assets/uni/neuLogo.png',
            communityHistory: [
              { period: 'Oca 2025 – Devam ediyor · 6 ay', title: 'Sponsorluk Sorumlusu', dept: 'Organizasyon' },
              { period: '2024', title: 'Genel Üye', dept: 'InnoMIS Community' },
            ]
          },
          { name: 'Kaan Kaya', role: 'Etkinlik Planlama', initials: 'KK', linkedin: '#',
            faculty: 'Mühendislik Mimarlık Fakültesi', department: 'Bilgisayar Mühendisliği', grade: '2. Sınıf',
            email: 'kaan.kaya@innomis.tr', universityLogo: '/assets/uni/neuLogo.png',
            communityHistory: [
              { period: 'Şub 2025 – Devam ediyor · 5 ay', title: 'Etkinlik Planlama', dept: 'Organizasyon' },
              { period: '2024', title: 'Genel Üye', dept: 'InnoMIS Community' },
            ]
          },
          { name: 'Deniz Şen', role: 'Saha ve Lojistik', initials: 'DŞ', linkedin: '#',
            faculty: 'Sosyal ve Beşeri Bilimler Fakültesi', department: 'İktisat', grade: '2. Sınıf',
            email: 'deniz.sen@innomis.tr', universityLogo: '/assets/uni/neuLogo.png',
            communityHistory: [
              { period: 'Mar 2025 – Devam ediyor · 4 ay', title: 'Saha ve Lojistik Sorumlusu', dept: 'Organizasyon' },
              { period: '2024', title: 'Genel Üye', dept: 'InnoMIS Community' },
            ]
          },
          { name: 'Örnek İsim 1', role: 'Ekip Üyesi', initials: 'Öİ', linkedin: '#',
            faculty: 'Mühendislik Mimarlık Fakültesi', department: 'Bilgisayar Mühendisliği', grade: '1. Sınıf',
            email: 'uye1@innomis.tr', universityLogo: '/assets/uni/neuLogo.png',
            communityHistory: [
              { period: 'Nis 2025 – Devam ediyor · 3 ay', title: 'Ekip Üyesi', dept: 'Organizasyon' },
            ]
          },
          { name: 'Örnek İsim 2', role: 'Ekip Üyesi', initials: 'Öİ', linkedin: '#',
            faculty: 'Mühendislik Mimarlık Fakültesi', department: 'Endüstri Mühendisliği', grade: '2. Sınıf',
            email: 'uye2@innomis.tr', universityLogo: '/assets/uni/neuLogo.png',
            communityHistory: [
              { period: 'Nis 2025 – Devam ediyor · 3 ay', title: 'Ekip Üyesi', dept: 'Organizasyon' },
            ]
          },
          { name: 'Örnek İsim 3', role: 'Ekip Üyesi', initials: 'Öİ', linkedin: '#',
            faculty: 'Sosyal ve Beşeri Bilimler Fakültesi', department: 'Uluslararası İlişkiler', grade: '1. Sınıf',
            email: 'uye3@innomis.tr', universityLogo: '/assets/uni/neuLogo.png',
            communityHistory: [
              { period: 'May 2025 – Devam ediyor · 2 ay', title: 'Ekip Üyesi', dept: 'Organizasyon' },
            ]
          },
          { name: 'Örnek İsim 4', role: 'Ekip Üyesi', initials: 'Öİ', linkedin: '#',
            faculty: 'Mühendislik Mimarlık Fakültesi', department: 'Bilgisayar Mühendisliği', grade: '1. Sınıf',
            email: 'uye4@innomis.tr', universityLogo: '/assets/uni/neuLogo.png',
            communityHistory: [
              { period: 'May 2025 – Devam ediyor · 2 ay', title: 'Ekip Üyesi', dept: 'Organizasyon' },
            ]
          }
        ]
      },
      {
        id: 'sosyalmedya',
        name: 'Sosyal Medya',
        director: { name: 'Can Demir', role: 'Sosyal Medya Direktörü', initials: 'CD', linkedin: '#',
          faculty: 'GüzeL Sanatlar Fakültesi', department: 'Grafik Tasarım', grade: '3. Sınıf',
          email: 'can.demir@innomis.tr', universityLogo: '/assets/uni/neuLogo.png',
          communityHistory: [
            { period: 'Eyl 2025 – Devam ediyor · 9 ay', title: 'Sosyal Medya Direktörü', dept: 'Sosyal Medya' },
            { period: 'Nis 2025 – Eyl 2025 · 5 ay', title: 'İçerik Editörü', dept: 'Sosyal Medya' },
            { period: '2024', title: 'Genel Üye', dept: 'InnoMIS Community' },
          ]
        },
        members: [
          { name: 'Ece Su', role: 'İçerik Editörü', initials: 'ES', linkedin: '#',
            faculty: 'Güzel Sanatlar Fakültesi', department: 'Görsel İletişim Tasarımı', grade: '2. Sınıf',
            email: 'ece.su@innomis.tr', universityLogo: '/assets/uni/neuLogo.png',
            communityHistory: [
              { period: 'Kas 2025 – Devam ediyor · 7 ay', title: 'İçerik Editörü', dept: 'Sosyal Medya' },
              { period: '2025', title: 'Genel Üye', dept: 'InnoMIS Community' },
            ]
          },
          { name: 'Kerem Işık', role: 'Kurgu ve Montaj', initials: 'KI', linkedin: '#',
            faculty: 'Güzel Sanatlar Fakültesi', department: 'Sinema ve Televizyon', grade: '2. Sınıf',
            email: 'kerem.isik@innomis.tr', universityLogo: '/assets/uni/neuLogo.png',
            communityHistory: [
              { period: 'Oca 2026 – Devam ediyor · 6 ay', title: 'Kurgu ve Montaj Sorumlusu', dept: 'Sosyal Medya' },
              { period: '2025', title: 'Genel Üye', dept: 'InnoMIS Community' },
            ]
          }
        ]
      },
      {
        id: 'kurumsal',
        name: 'Kurumsal İlişkiler',
        director: { name: 'Örnek İsim', role: 'Kurumsal İlişkiler Direktörü', initials: 'Öİ', linkedin: '#',
          faculty: 'Mühendislik Mimarlık Fakültesi', department: 'Yönetim Bilişim Sistemleri', grade: '3. Sınıf',
          email: 'kurumsal.direktor@innomis.tr', universityLogo: '/assets/uni/neuLogo.png',
          communityHistory: [
            { period: 'Kas 2025 – Devam ediyor · 7 ay', title: 'Kurumsal İlişkiler Direktörü', dept: 'Kurumsal İlişkiler' },
            { period: '2024', title: 'Genel Üye', dept: 'InnoMIS Community' },
          ]
        },
        members: []
      }
    ],
    campusRepresentatives: [
      {
        id: 'selcuk',
        university: 'Selçuk Üniversitesi',
        logo: '/assets/uni/selcukLogo.png',
        representatives: [
          { name: 'Örnek İsim', role: 'Kampüs Temsilcisi', initials: 'Öİ', linkedin: '#',
            communityHistory: [
              { period: 'Mar 2025 – Devam ediyor · 4 ay', title: 'Selçuk Ü. Kampüs Temsilcisi', dept: 'Kampüs Temsilcileri' },
              { period: '2024', title: 'Genel Üye', dept: 'InnoMIS Community' },
            ]
          }
        ]
      },
      {
        id: 'ktun',
        university: 'Konya Teknik Üniversitesi',
        logo: '/assets/uni/ktunLogo.png',
        representatives: [
          { name: 'Örnek İsim', role: 'Kampüs Temsilcisi', initials: 'Öİ', linkedin: '#',
            communityHistory: [
              { period: 'Mar 2025 – Devam ediyor · 4 ay', title: 'KTÜN Kampüs Temsilcisi', dept: 'Kampüs Temsilcileri' },
              { period: '2024', title: 'Genel Üye', dept: 'InnoMIS Community' },
            ]
          }
        ]
      },
      {
        id: 'kto',
        university: 'KTO Karatay Üniversitesi',
        logo: '/assets/uni/ktoLogo.png',
        representatives: [
          { name: 'Örnek İsim', role: 'Kampüs Temsilcisi', initials: 'Öİ', linkedin: '#',
            communityHistory: [
              { period: 'Nis 2025 – Devam ediyor · 3 ay', title: 'KTO Karatay Kampüs Temsilcisi', dept: 'Kampüs Temsilcileri' },
              { period: '2024', title: 'Genel Üye', dept: 'InnoMIS Community' },
            ]
          }
        ]
      },
      {
        id: 'kgtu',
        university: 'Konya Gıda ve Tarım Üniversitesi',
        logo: '/assets/uni/kgtuLogo.png',
        representatives: [
          { name: 'Örnek İsim', role: 'Kampüs Temsilcisi', initials: 'Öİ', linkedin: '#',
            communityHistory: [
              { period: 'Nis 2025 – Devam ediyor · 3 ay', title: 'KGTU Kampüs Temsilcisi', dept: 'Kampüs Temsilcileri' },
              { period: '2024', title: 'Genel Üye', dept: 'InnoMIS Community' },
            ]
          }
        ]
      }
    ]
  };

  export const alumniData = [
    {
      year: '2024',
      members: [
        {
          name: 'Örnek Mezun',
          role: 'Eski Kurucu Başkan',
          initials: 'ÖM',
          currentJob: 'Yazılım Mühendisi @ Aselsan',
          faculty: 'Mühendislik Mimarlık Fakültesi',
          department: 'Bilgisayar Mühendisliği',
          universityLogo: '/assets/uni/neuLogo.png',
          companyLogo: '',
          email: 'ornek@mezun.com',
          phone: '+90 555 000 0000',
          linkedin: '#',
          github: '#',
          website: 'ornekmezun.com',
          communityHistory: [
            { period: 'Oca 2024 – Haz 2024 · 6 ay', title: 'Kurucu Başkan', dept: 'Yönetim Kurulu' },
            { period: 'Eyl 2023 – Oca 2024 · 5 ay', title: 'Ar-Ge Ekip Lideri', dept: 'Ar-Ge ve Teknoloji' },
            { period: '2023', title: 'Kurucu Üye', dept: 'InnoMIS Community' },
          ]
        }
      ]
    }
  ];
  
  export const brandColors = [
    { hex: '#0C0A09', name: 'Background', usage: 'Karanlık mod arka planı (Sıcak Siyah)' },
    { hex: '#F49F20', name: 'Primary Orange', usage: 'Enerji, üretim, inovasyon, ana vurgu' },
    { hex: '#DA2C2E', name: 'Secondary Red', usage: 'Güç, ikincil vurgu' },
    { hex: '#8B5CF6', name: 'Accent Purple', usage: 'Yapay zeka, teknoloji' },
    { hex: '#F43F5E', name: 'Accent Rose', usage: 'Sıcak vurgu, uyarı' },
    { hex: '#EAB308', name: 'Accent Yellow', usage: 'Başarı, yıldız' },
  ];
  
  export const faqs = [
    {
      q: 'InnoMIS Community’ye kimler katılabilir?',
      a: 'Teknoloji, girişimcilik, kariyer ve topluluk çalışmalarına ilgi duyan üniversite öğrencileri katılabilir.'
    },
    {
      q: 'Etkinliklere katılım ücretli mi?',
      a: 'Etkinliğe göre değişebilir. Duyurularda katılım koşulları açıkça belirtilir.'
    },
    {
      q: 'Ekiplerde görev almak mümkün mü?',
      a: 'Evet. Sosyal medya, etkinlik, Ar-Ge, sponsorluk, tasarım, yazılım ve operasyon ekiplerinde görev alınabilir.'
    },
    {
      q: 'Sponsor veya partner olarak nasıl iletişime geçilir?',
      a: 'İletişim formu, e-posta veya sosyal medya hesapları üzerinden toplulukla iletişime geçilebilir.'
    },
    {
      q: 'Etkinlik duyurularını nereden takip edebilirim?',
      a: 'Instagram, LinkedIn ve web sitesi üzerinden güncel duyurular takip edilebilir.'
    }
  ];
  
  const XIcon = ({ className }) => (
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" className={className}>
      <path d="M4 4l11.733 16h4.267l-11.733 -16z"></path>
      <path d="M4 20l6.768 -6.768m2.46 -2.46l6.772 -6.772"></path>
    </svg>
  );
  
  const TikTokIcon = ({ className }) => (
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" className={className}>
      <path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5"></path>
    </svg>
  );


  export const socials = [
    { icon: <Instagram className="w-5 h-5" />, href: 'https://www.instagram.com/neuinnomis/', label: 'Instagram' },
    { icon: <XIcon className="w-5 h-5" />, href: 'https://x.com/neuinnomis', label: 'X (Twitter)' },
    { icon: <Youtube className="w-5 h-5" />, href: 'https://www.youtube.com/@neuinnomis', label: 'YouTube' },
    { icon: <TikTokIcon className="w-5 h-5" />, href: 'https://www.tiktok.com/@neuinnomis', label: 'TikTok' },
    { icon: <Github className="w-5 h-5" />, href: 'https://github.com/neuinnomis', label: 'GitHub' },
    { icon: <Linkedin className="w-5 h-5" />, href: '#', label: 'LinkedIn' }
  ];
  
  export const footerLinks = {
    corporate: [
      { name: 'Hakkımızda', href: '/#about' },
      { name: 'Ekip', href: '/team' },
      { name: 'Mezunlar', href: '/alumni' },
      { name: 'Kurumsal', href: '/corporate' }
    ],
    events: [
      { name: 'Etkinlikler', href: '/events' },
      { name: 'Galeri', href: '/gallery' },
      { name: 'Kayıt Ol', href: '/kayit' },
      { name: 'Yönetim Başvurusu', href: '/yonetim-basvuru' },
      { name: 'Temsilci Başvurusu', href: '/temsilci-basvuru' },
      { name: 'İletişim', href: '/#contact' }
    ],
    legal: [
      { name: 'Gizlilik Politikası', href: '/gizlilik-politikasi' },
      { name: 'Kullanım Şartları', href: '/kullanim-sartlari' },
      { name: 'KVKK Aydınlatma Metni', href: '/kvkk' }
    ]
  };
