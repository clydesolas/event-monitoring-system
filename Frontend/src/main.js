import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import store from './store';
import axios from 'axios';
import VueAxios from 'vue-axios';
import '@mdi/font/css/materialdesignicons.css'; 
import './assets/main.css';
import 'vuetify/styles'; // Make sure to import the Vuetify styles
import { createVuetify } from 'vuetify';
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import VCalendar from 'v-calendar';
import 'v-calendar/style.css';
import VueApexCharts from "vue3-apexcharts";



const myCustomLightTheme = {
  dark: false,
  colors: {
    surface: '#f6f1f4',
  },
  
}
const vuetify = createVuetify({
  components,
  directives,
  icons: {
    defaultSet: 'mdi',
  },
  theme: {
    defaultTheme: 'myCustomLightTheme',
    themes: {
      myCustomLightTheme,
    },
  },
});

const app = createApp(App);
app.use(VueApexCharts);

// Use plugin with optional defaults
app.use(VCalendar, {})

// Use Vuetify
app.use(vuetify);

// Use VueAxios and set the axios instance globally
app.use(VueAxios, axios);
app.config.globalProperties.$axios = axios;

// Use router and store
app.use(router);
app.use(store);

app.mount('#app');
