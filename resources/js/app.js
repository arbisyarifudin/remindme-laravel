// import './bootstrap';

import { createApp } from 'vue'

import App from './App.vue'
import router from './router'
import axios from '@/plugins/axios'
import VueAxios from 'vue-axios'
// import VueSweetalert2 from 'vue-sweetalert2';
// import 'sweetalert2/dist/sweetalert2.min.css';

// createApp(App).use(router).mount('#app')
const app = createApp(App)
app.use(VueAxios, axios)
// app.use(VueSweetalert2);
app.use(router)

app.provide('axios', app.config.globalProperties.axios)

app.mount('#app')

