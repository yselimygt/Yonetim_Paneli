import React, { useEffect } from 'react';
import { Routes, Route, useLocation } from 'react-router-dom';
import { useSiteData } from './context/SiteDataContext';
import Navbar from './components/Navbar';
import Hero from './components/Hero';
import SponsorMarquee from './components/SponsorMarquee';
import About from './components/About';
import Stats from './components/Stats';
import Events from './components/Events';
import Achievements from './components/Achievements';
import Gallery from './components/Gallery';
import Team from './components/Team';
import Alumni from './components/Alumni';
import Corporate from './components/Corporate';
import FAQ from './components/FAQ';
import JoinCTA from './components/JoinCTA';
import Contact from './components/Contact';
import ContactPage from './components/ContactPage';
import LinksPage from './components/LinksPage';
import AnnouncementPopup from './components/AnnouncementPopup';
import NotFound from './components/errors/NotFound';
import ServiceUnavailable from './components/errors/ServiceUnavailable';
import Footer from './components/Footer';
import RegistrationForm from './components/RegistrationForm';
import ApplicationHub from './components/ApplicationHub';
import ManagementForm from './components/ManagementForm';
import RepresentativeForm from './components/RepresentativeForm';

// Projects
import OyTakipApp from './projects/oy-takip/OyTakipApp';

// Yasal Metin Sayfaları
import Privacy from './components/legal/Privacy';
import Terms from './components/legal/Terms';
import KVKK from './components/legal/KVKK';

// Ana Sayfa Bileşeni
const Home = () => {
  return (
    <>
      <AnnouncementPopup />
      <Hero />
      <SponsorMarquee />
      <About />
      <Stats />
      <Achievements />
      <Gallery previewMode={true} />
      <FAQ />
      <JoinCTA />
      <Contact />
    </>
  );
};

// Sayfa değişiminde en üste scroll yapmak için yardımcı bileşen
const ScrollToTop = () => {
  const { pathname, hash } = useLocation();

  useEffect(() => {
    // Eğer URL'de bir hash (#about vb) varsa o bölüme scroll yap
    if (hash) {
      setTimeout(() => {
        const element = document.querySelector(hash);
        if (element) {
          element.scrollIntoView({ behavior: 'smooth' });
        }
      }, 100);
    } else {
      // Sadece sayfa değiştiğinde en üste çık
      window.scrollTo(0, 0);
    }
  }, [pathname, hash]);

  return null;
};

function App() {
  const { siteData, loading } = useSiteData();
  const location = useLocation();
  const isLinksPage = location.pathname === '/links';
  const isOyTakipPage = location.pathname.startsWith('/projects/oy-takip');

  const maintenanceMode = siteData?.settings?.maintenanceMode || {};
  if (maintenanceMode.enabled) {
    return <ServiceUnavailable title={maintenanceMode.title} description={maintenanceMode.description} />;
  }

  if (isLinksPage) {
    return (
      <Routes>
        <Route path="/links" element={<LinksPage />} />
      </Routes>
    );
  }

  if (isOyTakipPage) {
    return (
      <Routes>
        <Route path="/projects/oy-takip/*" element={<OyTakipApp />} />
      </Routes>
    );
  }

  return (
    <div className="min-h-screen flex flex-col relative overflow-x-hidden">
      <ScrollToTop />
      <Navbar />
      <main className="flex-grow pt-20">
        <Routes>
          <Route path="/" element={<Home />} />
          <Route path="/team" element={<Team />} />
          <Route path="/team/:slug" element={<Team />} />
          <Route path="/alumni" element={<Alumni />} />
          <Route path="/alumni/:year/:slug" element={<Alumni />} />
          <Route path="/corporate" element={<Corporate />} />
          <Route path="/gallery" element={<Gallery />} />
          <Route path="/events" element={<Events />} />
          <Route path="/iletisim" element={<ContactPage />} />
          <Route path="/kayit" element={<RegistrationForm />} />
          <Route path="/basvuru" element={<ApplicationHub />} />
          <Route path="/yonetim-basvuru" element={<ManagementForm />} />
          <Route path="/temsilci-basvuru" element={<RepresentativeForm />} />
          
          {/* Yasal Metinler */}
          <Route path="/gizlilik-politikasi" element={<Privacy />} />
          <Route path="/kullanim-sartlari" element={<Terms />} />
          <Route path="/kvkk" element={<KVKK />} />

          {/* 404 Not Found Route */}
          <Route path="*" element={<NotFound />} />
        </Routes>
      </main>
      <Footer />
    </div>
  );
}

export default App;
