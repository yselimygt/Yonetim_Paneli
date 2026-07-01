import React, { createContext, useContext, useState, useEffect } from 'react';
import { getSiteData } from '../services/siteApi';

const SiteDataContext = createContext();

export const useSiteData = () => useContext(SiteDataContext);

export const SiteDataProvider = ({ children }) => {
  const [siteData, setSiteData] = useState({
    settings: {},
    projects: [],
    gallery: [],
    achievements: [],
    awards: [],
    team: {}
  });
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchSiteData = async () => {
      try {
        const json = await getSiteData();
        if (json.success) {
          setSiteData(json.data);
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
