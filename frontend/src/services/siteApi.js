const API_BASE_URL = import.meta.env.VITE_API_URL || '/api';

async function requestJson(path) {
  const response = await fetch(`${API_BASE_URL}${path}`);

  if (!response.ok) {
    throw new Error(`API request failed: ${response.status}`);
  }

  return response.json();
}

async function postJson(path, data) {
  const response = await fetch(`${API_BASE_URL}${path}`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(data)
  });

  if (!response.ok) {
    let message = `API request failed: ${response.status}`;
    try {
        const errorData = await response.json();
        if (errorData.message) message = errorData.message;
    } catch (e) {
        // ignore JSON parse error
    }
    throw new Error(message);
  }

  return response.json();
}

export function getSiteData() {
  return requestJson('/site-data');
}

export function getEvents() {
  return requestJson('/events');
}

export function submitMessage(data) {
  return postJson('/submit-message', data);
}

export function submitApplication(data) {
  return postJson('/submit-application', data);
}

export function submitMembership(data) {
  return postJson('/submit-membership', data);
}
