import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import 'bootstrap/dist/css/bootstrap.min.css';
import axios from 'axios';
import 'bootstrap-icons/font/bootstrap-icons.css';
import {createPinia} from 'pinia'
const app = createApp(App)
app.use(createPinia())
app.use(router)

app.mount('#app')
