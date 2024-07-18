import { createApp } from 'vue';
import './bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';

import LoginComponent from './components/auth/LoginComponent.vue';
import RegisterComponent from './components/auth/RegisterComponent.vue';

import PaymentListComponent from './components/payment/ListComponent.vue';
import PaymentCreateComponent from './components/payment/CreateComponent.vue';
import PaymentShowComponent from './components/payment/ShowComponent.vue';
import PaymentErrorComponent from './components/payment/ErrorComponent.vue';

const app = createApp({});

// AUTH
app.component('login-component',  LoginComponent);
app.component('register-component',  RegisterComponent);

// PAYMENTS
app.component('payment-list-component', PaymentListComponent);
app.component('payment-create-component', PaymentCreateComponent);
app.component('payment-show-component', PaymentShowComponent);
app.component('payment-error-component', PaymentErrorComponent);

app.mount('#app');
