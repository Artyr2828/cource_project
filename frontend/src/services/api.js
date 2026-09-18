import axios from "axios";

const api = axios.create({
  //baseURL: 'https://cource-project-uphw.onrender.com',
  baseURL: 'http://localhost:8000',
  withCredentials: true
});

export default api;