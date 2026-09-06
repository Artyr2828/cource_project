import axios from "axios";

const api = axios.create({
  baseURL: 'https://cource-project-uphw.onrender.com/',
  withCredentials: true
});

export default api;