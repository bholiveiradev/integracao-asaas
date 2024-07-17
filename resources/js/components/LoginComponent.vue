<template>
    <div>
        <h1>Login Page Test</h1>
        <form @submit.prevent="handleSubmit">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input
                    type="email"
                    class="form-control"
                    id="email"
                    v-model="formData.email"
                    required
                />
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Senha</label>
                <input
                    type="password"
                    class="form-control"
                    id="password"
                    v-model="formData.password"
                    required
                />
            </div>
            <button type="submit" class="btn btn-primary" :disabled="loading">
                <span
                    v-if="loading"
                    class="spinner-border spinner-border-sm mr-1"
                    role="status"
                    aria-hidden="true"
                ></span>
                <span v-if="!loading">Entrar</span>
                <span v-if="loading">&nbsp;Entrando...</span>
            </button>
        </form>
    </div>
</template>

<script>
import { ref, reactive } from "vue";

export default {
    name: "LoginComponent",
    setup() {
        const formData = reactive({
            email: "",
            password: "",
        });
        const loading = ref(false);

        const handleSubmit = () => {
            loading.value = true;
            setTimeout(() => {
                alert(`Email: ${formData.email}, Senha: ${formData.password}`);
                loading.value = false;
            }, 2000);
        };

        return {
            formData,
            loading,
            handleSubmit,
        };
    },
};
</script>

<style scoped>
/* Estilos personalizados */
</style>
