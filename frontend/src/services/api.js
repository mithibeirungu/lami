import axios from 'axios';

const API_BASE = import.meta.env.VITE_API_URL || 'http://localhost:8000/api';

const api = axios.create({
  baseURL: API_BASE,
  headers: {
    'Content-Type': 'application/json',
  },
});

// Attach token from localStorage on every request if present (Sanctum personal token or legacy api_token)
api.interceptors.request.use((config) => {
  const token = localStorage.getItem('sanctum_token') || localStorage.getItem('api_token')
  if (token) {
    config.headers = config.headers || {}
    config.headers['Authorization'] = `Bearer ${token}`
  }
  return config
})

export default {
  // Auth
  register(payload) { return api.post('/register', payload); },
  registerAdmin(payload) { return api.post('/register-admin', payload); },
  login(payload) { return api.post('/login', payload); },
  // Admin
  getAdminDashboard() { return api.get('/admin/dashboard'); },

  // Cars
  getCars() { return api.get('/cars'); },
  getCar(id) { return api.get(`/cars/${id}`); },
  createCar(payload) { return api.post('/cars', payload); },
  updateCar(id, payload) { return api.put(`/cars/${id}`, payload); },
  deleteCar(id) { return api.delete(`/cars/${id}`); },

  // Lookup endpoints for forms
  getBrands() { return api.get('/brands'); },
  getBodyTypes() { return api.get('/body-types'); },

  // Comments
  postComment(carId, payload) { return api.post(`/cars/${carId}/comments`, payload); },

  // Favorites
  toggleFavorite(carId, payload) { return api.post(`/cars/${carId}/favorite`, payload); },
  getFavorites(userId) { return api.get('/favorites', { params: { user_id: userId } }); },
};
