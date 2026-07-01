const API_BASE_URL = '/api';

async function requestJson(path) {
  const response = await fetch(`${API_BASE_URL}${path}`);

  if (!response.ok) {
    throw new Error(`API request failed: ${response.status}`);
  }

  return response.json();
}

export function getSiteData() {
  return requestJson('/site-data');
}

export function getEvents() {
  return requestJson('/events');
}
