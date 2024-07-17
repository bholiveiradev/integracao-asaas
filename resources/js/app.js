import { createApp, defineAsyncComponent } from 'vue';
import './bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';

const app = createApp({});

app.component('login-component',  defineAsyncComponent(() => import('./components/LoginComponent.vue')));
app.component('register-component',  defineAsyncComponent(() => import('./components/RegisterComponent.vue')));

app.mount('#app');
