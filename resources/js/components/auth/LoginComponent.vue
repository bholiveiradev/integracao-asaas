<template>
    <div class="container pt-5 pb-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h3 class="mb-3 text-center">Entrar na plataforma</h3>
                <div class="card">
                    <div class="card-body">
                        <form @submit.prevent="handleSubmit">
                            <div class="mb-3">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" class="form-control" id="email" v-model="formData.email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Senha</label>
                                <input type="password" class="form-control" id="password" v-model="formData.password" required>
                            </div>
                            <div class="d-grid gap-2 text-center">
                                <button type="submit" class="btn btn-primary d-block fw-bold" :disabled="loading">
                                    <span v-if="loading" class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span>
                                    <span v-if="!loading">Entrar</span>
                                    <span v-else>&nbsp;Entrando...</span>
                                </button>
                                <a href="/register" class="link-offset-2 link-underline link-underline-opacity-0">Não possui cadastro? Cadastre-se aqui</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, reactive } from 'vue';

export default {
    name: 'LoginComponent',
    setup() {
        const formData = reactive({
            email: '',
            password: '',
        });
        const loading = ref(false);

        const handleSubmit = async () => {
            loading.value = true;
            try {
                const response = await axios.post('/api/login', {
                    email: formData.email,
                    password: formData.password,
                });

                localStorage.setItem('access_token', response.data.access_token);

                window.location.href = '/payments';
            } catch (error) {
                alert('Erro ao fazer login. Por favor, verifique suas credenciais e tente novamente.');
            } finally {
                loading.value = false;
            }
        };

        return {
            formData,
            loading,
            handleSubmit,
        };
    },
};
</script>
