export const createSlug = (name) => {
  const turkishMap = {
    'ı': 'i', 'İ': 'i', 'ğ': 'g', 'Ğ': 'g', 
    'ü': 'u', 'Ü': 'u', 'ş': 's', 'Ş': 's', 
    'ö': 'o', 'Ö': 'o', 'ç': 'c', 'Ç': 'c'
  };
  
  return name
    .split('')
    .map(char => turkishMap[char] || char)
    .join('')
    .toLowerCase()
    .trim()
    .replace(/[^a-z0-9\s-]/g, '')
    .replace(/\s+/g, '-');
};
