import React, { createContext, useContext, useState, useEffect } from 'react';
import { getSiteData } from '../services/siteApi';
import {
  popupConfig as defaultPopupConfig,
  stats as defaultStats,
  sponsors as defaultSponsors,
  faqs as defaultFaqs
} from '../data/innomisData';

const SiteDataContext = createContext();

export const useSiteData = () => useContext(SiteDataContext);

const defaultHero = {
  titleLine1: 'Bilişimde Yeniliğe',
  titleLine2: 'İnovasyonda Zirveye!',
  description:
    'InnoMIS Community; teknoloji, yapay zeka, girişimcilik ve kariyer odaklı etkinliklerle öğrencileri üretmeye, öğrenmeye ve bağlantı kurmaya davet eden yenilikçi bir öğrenci topluluğudur.',
  primaryCta: {
    label: 'Topluluğa Katıl',
    href: '/kayit'
  },
  secondaryCta: {
    label: 'Etkinlikleri Keşfet',
    href: '/events'
  }
};

const defaultBrand = {
  navbarLogo: '/innomis/assets/logo/Yatay-Beyaz.png',
  footerLogo: '/innomis/assets/logo/Yatay-Beyaz.png',
  popupLogo: '/innomis/assets/logo/Ana-Logo-Arma.png',
  heroMascot: '/innomis/assets/innox/innox-kararli.png',
  faqMascot: '/innomis/assets/innox/merak-eden-innox.svg',
  brandName: 'InnoMIS Community',
  footerDescription:
    'Teknoloji, inovasyon ve kariyer odaklı öğrenci topluluğu. Öğrenen, üreten ve paylaşan öğrencileri aynı çatı altında buluşturur.'
};

const defaultContact = {
  email: 'info.innomis@gmail.com',
  phone: '0555 555 55 55',
  address: 'Dere Aşıklar, Köyceğiz Yerleşkesi Köyceğiz, Demeç Sk. No:42/3, 42005 Meram/Konya',
  addressDetail: 'Necmettin Erbakan Üniversitesi / Uygulamalı Bilimler Fakültesi',
  mapEmbedUrl:
    'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3151.727670977827!2d32.42436737521876!3d37.81989437197368!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14d08db8c0b2f56b%3A0x6b801a2f6fb39e80!2sNecmettin%20Erbakan%20%C3%9Cniversitesi%20Uygulamal%C4%B1%20Bilimler%20Fak%C3%BCltesi!5e0!3m2!1str!2str!4v1700000000000!5m2!1str!2str',
  socials: {
    instagram: 'https://www.instagram.com/neuinnomis/',
    x: 'https://x.com/neuinnomis',
    youtube: 'https://www.youtube.com/@neuinnomis',
    tiktok: 'https://www.tiktok.com/@neuinnomis',
    github: 'https://github.com/neuinnomis',
    linkedin: 'https://www.linkedin.com/company/innomis-community'
  }
};

const normalizeSiteData = (data = {}) => {
  const incomingSettings = data.settings || {};
  const popupConfig = incomingSettings.popupConfig || {};
  const hero = incomingSettings.hero || {};
  const brand = incomingSettings.brand || {};
  const contact = incomingSettings.contact || {};

  const defaultSiteSettings = {
    popupConfig: {
      ...defaultPopupConfig,
      ...popupConfig,
      cta: {
        ...defaultPopupConfig.cta,
        ...(popupConfig.cta || {})
      },
      secondaryCta: {
        ...defaultPopupConfig.secondaryCta,
        ...(popupConfig.secondaryCta || {})
      }
    },
    stats: defaultStats,
    sponsors: defaultSponsors,
    faqs: defaultFaqs,
    hero: {
      ...defaultHero,
      ...hero,
      primaryCta: {
        ...defaultHero.primaryCta,
        ...(hero.primaryCta || {})
      },
      secondaryCta: {
        ...defaultHero.secondaryCta,
        ...(hero.secondaryCta || {})
      }
    },
    brand: {
      ...defaultBrand,
      ...brand
    },
    contact: {
      ...defaultContact,
      ...contact,
      socials: {
        ...defaultContact.socials,
        ...(contact.socials || {})
      }
    }
  };

  return {
    settings: {
      ...defaultSiteSettings,
      ...incomingSettings,
      popupConfig: defaultSiteSettings.popupConfig,
      hero: defaultSiteSettings.hero,
      brand: defaultSiteSettings.brand,
      contact: defaultSiteSettings.contact
    },
    projects: data.projects || [],
    gallery: data.gallery || [],
    achievements: data.achievements || [],
    awards: data.awards || [],
    team: data.team || { board: [], departments: [], campusRepresentatives: [] }
  };
};

export const SiteDataProvider = ({ children }) => {
  const [siteData, setSiteData] = useState(() => normalizeSiteData());
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchSiteData = async () => {
      try {
        const json = await getSiteData();
        if (json.success) {
          setSiteData(normalizeSiteData(json.data));
        } else {
          console.error("API error:", json);
        }
      } catch (err) {
        console.error("Fetch site data failed:", err);
        setError(err);
      } finally {
        setLoading(false);
      }
    };

    fetchSiteData();
  }, []);

  return (
    <SiteDataContext.Provider value={{ siteData, loading, error }}>
      {children}
    </SiteDataContext.Provider>
  );
};
