<template>
     <div v-if="isLoading" class="overlay">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Carregando...</span>
        </div>
        <p>Processando...</p>
    </div>
</template>

<script>
export default {
    props: {
        isLoading: {
            type: Boolean,
            required: false,
            default: false,
        }
    }
}
</script>

<style scoped>
.overlay {
    position: absolute;
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: rgba(255, 255, 255, .6);
    color: black;
    font-size: 1.4rem;
    flex-direction: column;
    width: 100%;
    z-index: 999;
}
</style>