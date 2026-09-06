import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import 'bootstrap/dist/css/bootstrap.min.css';
import axios from 'axios';

axios.defaults.baseURL = 'https://cource-project-uphw.onrender.com';

const app = createApp(App)

app.use(router)

app.mount('#app')
