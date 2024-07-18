<template>
    <navbar-component></navbar-component>
    <div class="container text-center pt-5 pb-5">
        <h1>Ocorreu um erro</h1>
        <p>Desculpe, algo deu errado ao processar seu pagamento.</p>
        <a class="btn btn-primary" href="/payments">
            <i class="fa-solid fa-home"></i> Voltar para a Página Inicial
        </a>
    </div>
</template>

<script>
import NavbarComponent from '../template/NavbarComponent.vue';

export default {
    name: 'PaymentErrorComponent',
    components: {
        NavbarComponent,
    },
};
</script>

<style scoped>
.container {
    max-width: 600px;
    margin: 0 auto;
}

h1 {
    color: #ff0000; /* Red color for the error message */
}

p {
    margin-bottom: 20px;
}

.btn {
    margin-top: 20px;
}
</style>
