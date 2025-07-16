/* eslint-disable import/order */
import '@/@iconify/icons-bundle'
import App from '@/App.vue'
import vuetify from '@/plugins/vuetify'
import { loadFonts } from '@/plugins/webfontloader'
import router from '@/router'
import '@core-scss/template/index.scss'
import '@layouts/styles/index.scss'
import '@styles/styles.scss'
import { createPinia } from 'pinia'
import Swal from 'sweetalert2'
import { createApp } from 'vue'
import VueApexCharts from "vue3-apexcharts"
import Vue3EasyDataTable from 'vue3-easy-data-table'
import 'vue3-easy-data-table/dist/style.css'

loadFonts()

const app = createApp(App)

app.use(vuetify)
app.component('EasyDataTable', Vue3EasyDataTable);
app.use(createPinia())
app.use(router)
app.use(VueApexCharts);

app.config.globalProperties.$handleRightClick =(event)=>{
  event.preventDefault();
};

app.directive('prevent-right-click', {
  beforeMount: (el, binding) => {
    el.addEventListener('contextmenu', (e) => {
      e.preventDefault();
    });
  },
  unmounted: (el,binding) => {
    el.removeEventListener('contextmenu', binding.value);
  },
});

app.mount('#app')

const showToast = (icon, title, text) => {
  const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.addEventListener('mouseenter', Swal.stopTimer)
      toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
  })

  Toast.fire({
    icon: icon,
    title: title,
    text: text
  })
}

app.config.globalProperties.$showToast = showToast

/** localhost */
app.config.globalProperties.$basephoto = 'http://localhost:8000/storage/';
/** development */
/** production */

