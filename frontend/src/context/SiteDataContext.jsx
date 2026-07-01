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

const normalizeSiteData = (data = {}) => {
  const incomingSettings = data.settings || {};
  const popupConfig = incomingSettings.popupConfig || {};
  const hero = incomingSettings.hero || {};

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
    }
  };

  return {
    settings: {
      ...defaultSiteSettings,
      ...incomingSettings,
      popupConfig: defaultSiteSettings.popupConfig,
      hero: defaultSiteSettings.hero
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
