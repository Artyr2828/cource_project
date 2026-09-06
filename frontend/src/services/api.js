import axios from "axios";

const api = axios.create({
  baseURL: 'https://cource-project-1-wwmt.onrender.com',
  withCredentials: true
});

export default api;