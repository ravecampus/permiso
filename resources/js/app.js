import './bootstrap';

import './bootstrap';
import {createApp } from 'vue'
import 'bootstrap-icons/font/bootstrap-icons.css'

import router from './router'

import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'

import axios from 'axios'
axios.defaults.withCredentials = true;
axios.defaults.baseURL = '/';

import Swal from 'sweetalert2'
window.Swal  = Swal

const toast = Swal.mixin({
    toast:true,
    position:'center',
    timer:2000,
    background: '#17a673',
    color: '#fff',
    showConfirmButton:false,
    timerProgressBar:true
})

window.toast = toast

import App from './components/App.vue'

const app = createApp(App);
app.component('VueDatePicker', VueDatePicker)
app.use(router).mount("#app")