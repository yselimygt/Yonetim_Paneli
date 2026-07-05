import applicationsData from '../data/applications.json';
import communitiesData from '../data/communities.json';
import votesData from '../data/votes.json';
import competitorsData from '../data/competitors.json';
import usersData from '../data/users.json';
import plansData from '../data/plans.json';

const PREFIX = 'oy_takip_';

export const initStorage = () => {
  if (!localStorage.getItem(`${PREFIX}applications`)) {
    localStorage.setItem(`${PREFIX}applications`, JSON.stringify(applicationsData));
  }
  if (!localStorage.getItem(`${PREFIX}communities`)) {
    localStorage.setItem(`${PREFIX}communities`, JSON.stringify(communitiesData));
  }
  if (!localStorage.getItem(`${PREFIX}votes`)) {
    localStorage.setItem(`${PREFIX}votes`, JSON.stringify(votesData));
  }
  if (!localStorage.getItem(`${PREFIX}competitors`)) {
    localStorage.setItem(`${PREFIX}competitors`, JSON.stringify(competitorsData));
  }
  if (!localStorage.getItem(`${PREFIX}users`)) {
    localStorage.setItem(`${PREFIX}users`, JSON.stringify(usersData));
  }
  if (!localStorage.getItem(`${PREFIX}plans`)) {
    localStorage.setItem(`${PREFIX}plans`, JSON.stringify(plansData));
  }
};

export const getStorage = (key) => {
  const data = localStorage.getItem(`${PREFIX}${key}`);
  return data ? JSON.parse(data) : [];
};

export const setStorage = (key, data) => {
  localStorage.setItem(`${PREFIX}${key}`, JSON.stringify(data));
};

export const addItem = (key, item) => {
  const data = getStorage(key);
  const newItem = { ...item, id: `${key}_${Date.now()}_${Math.floor(Math.random() * 1000)}` };
  data.push(newItem);
  setStorage(key, data);
  return newItem;
};

export const updateItem = (key, id, updates) => {
  const data = getStorage(key);
  const index = data.findIndex((item) => item.id === id);
  if (index !== -1) {
    data[index] = { ...data[index], ...updates };
    setStorage(key, data);
    return data[index];
  }
  return null;
};

export const deleteItem = (key, id) => {
  const data = getStorage(key);
  const filtered = data.filter((item) => item.id !== id);
  setStorage(key, filtered);
};
