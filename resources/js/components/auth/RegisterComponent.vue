<template>
    <div class="container p-5 pb-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h3 class="mb-3 text-center">Formulário de Cadastro</h3>
                <div class="card">
                    <div class="card-body">
                        <form @submit.prevent="handleSubmit">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="name" v-model="formData.name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" class="form-control" id="email" v-model="formData.email" required>
                            </div>
                            <div class="mb-3">
                                <label for="cpf_cnpj" class="form-label">CPF / CNPJ</label>
                                <input type="text" class="form-control" id="cpf_cnpj" v-model="formData.cpf_cnpj" v-mask="['###.###.###-##', '##.###.###/####-##']" required>
                            </div>
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label for="phone" class="form-label">Telefone: <small>(opcional)</small></label>
                                    <input type="tel" class="form-control" id="phone" v-model="formData.phone" v-mask="['(##) ####-####']">
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="mobile_phone" class="form-label">Celular</label>
                                    <input type="tel" class="form-control" id="mobile_phone" v-model="formData.mobile_phone" v-mask="['(##) #####-####']" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label for="password" class="form-label">Senha</label>
                                    <input type="password" class="form-control" id="password" v-model="formData.password" required>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="password_confirmation" class="form-label">Confirmar Senha</label>
                                    <input type="password" class="form-control" id="password_confirmation" v-model="formData.password_confirmation" required>
                                </div>
                            </div>
                            <div class="d-grid gap-2 text-center">
                                <button type="submit" class="btn btn-primary d-block fw-bold" :disabled="loading">
                                    <span
                                        v-if="loading"
                                        class="spinner-border spinner-border-sm mr-1"
                                        role="status"
                                        aria-hidden="true"
                                    ></span>
                                    <span v-if="!loading">Cadastrar</span>
                                    <span v-else>&nbsp;Cadastrando...</span>
                                </button>
                                <a
                                    href="/login"
                                    class="link-offset-2 link-underline link-underline-opacity-0"
                                >Já tem um cadastro? Fazer login</a>
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
import { mask } from 'vue-the-mask';

export default {
    name: 'RegisterComponent',
    directives: { mask },
    setup() {
        const formData = reactive({
            name: '',
            email: '',
            cpf_cnpj: '',
            phone: '',
            mobile_phone: '',
            password: '',
            password_confirmation: '',
        });
        const loading = ref(false);

        const handleSubmit = async () => {
            loading.value = true;
            try {
                const response = await axios.post('/api/register', formData);

                localStorage.setItem('token', response.data.access_token);

                alert('Cadatrado com sucesso! Você será redirecionado para a tela de login!');

                window.location.href = '/login';
            } catch (error) {
                alert(`Erro ao cadastrar usuário: ${error.response.data.message}`);
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
