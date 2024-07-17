<template>
    <div>
        <h1>Register Page Test</h1>
    </div>
</template>

<script>
import { ref, reactive } from "vue";

export default {
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
