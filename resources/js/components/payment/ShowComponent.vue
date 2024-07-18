<template>
    <navbar-component></navbar-component>
    <div v-if="loading" class="text-center pt-5">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Carregando...</span>
        </div>
        <p>Processando...</p>
    </div>

    <div v-else class="container pt-5 pb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Obrigado pelo pagamento!</h3>
        </div>

        <div v-if="billingType === 'BOLETO'">
            <p>Seu boleto está pronto para impressão.</p>
            <a class="btn btn-primary" :href="boletoUrl" target="_blank">
                <i class="fa-solid fa-print"></i> Imprimir Boleto
            </a>
            <div v-if="pixCode">
                <p class="mt-3">Ou pague utilizando o QR Code Pix abaixo.</p>
                <div class="mb-3">
                    <img :src="`data:image/png;base64,${pixQrCodeUrl}`" alt="QR Code Pix" class="img-fluid"/>
                </div>
                <div class="mb-3">
                    <input
                        type="text"
                        :value="pixCode"
                        class="form-control"
                        readonly
                    />
                    <button class="btn btn-secondary mt-2" @click="copyToClipboard">
                        Copiar para a área de transferência
                    </button>
                </div>
            </div>
        </div>

        <div v-if="billingType === 'PIX'">
            <div v-if="pixCode">
                <p>Seu QR Code Pix está disponível abaixo. Use-o para concluir o pagamento.</p>
                <div class="mb-3">
                    <img :src="`data:image/png;base64,${pixQrCodeUrl}`" alt="QR Code Pix" class="img-fluid"/>
                </div>
                <div class="mb-3">
                    <input
                        type="text"
                        :value="pixCode"
                        class="form-control"
                        readonly
                    />
                    <button class="btn btn-secondary mt-2" @click="copyToClipboard">
                        Copiar para a área de transferência
                    </button>
                </div>
            </div>
            <div v-else>
                <p>O QR Code não pode ser pago.</p>
            </div>
        </div>

        <div v-if="billingType === 'CREDIT_CARD'">
            <p>Seu pagamento foi processado com sucesso. Agradecemos!</p>
        </div>
    </div>
</template>

<script>
import { ref, onMounted, onUnmounted } from 'vue';
import NavbarComponent from '../template/NavbarComponent.vue';

export default {
    name: 'PaymentShowComponent',
    components: {
        NavbarComponent,
    },
    setup() {
        const loading           = ref(true);
        const billingType       = ref(null);
        const boletoUrl         = ref('');
        const pixQrCodeUrl      = ref('');
        const pixCode           = ref('');
        let   pollingInterval   = null;

        const fetchPaymentDetails = async () => {
            try {
                const uuid      = getUUIDFromPath();
                const response  = await axios.get(`/api/payments/${uuid}`);
                const { data }  = response;

                if (data.status === 'REQUEST_ERROR' || data.status === 'INTERNAL_ERROR') {
                    window.location.href = `/payments/${uuid}/error`;
                    return;
                }

                billingType.value   = data.billing_type;
                boletoUrl.value     = data.external_url;

                if ((data.billing_type === 'PIX' || data.billing_type === 'BOLETO') && data.status === 'PENDING') {
                    const responsePix   = await axios.get(`/api/payments/${uuid}/pixQrCode`);
                    const { pixQrCode } = responsePix.data;

                    pixQrCodeUrl.value  = pixQrCode.encodedImage;
                    pixCode.value       = pixQrCode.payload;
                }

                if (!data.processing) {
                    clearInterval(pollingInterval);
                    loading.value = false;
                }
            } catch (error) { loading.value = false; }
        };

        const getUUIDFromPath = () => {
            const path  = window.location.pathname;
            const parts = path.split('/');
            return parts[2];
        };

        const copyToClipboard = async () => {
            if (navigator.clipboard && navigator.clipboard.writeText) {
                try {
                    await navigator.clipboard.writeText(pixCode.value);
                    alert('Código Pix copiado para a área de transferência!');
                } catch (err) {
                    console.error('Erro ao copiar para a área de transferência:', err);
                }
            } else {
                alert('Clipboard API não suportada pelo navegador.');
            }
        };

        const startPolling = () => {
            pollingInterval = setInterval(fetchPaymentDetails, 3000);
        };

        onMounted(() => {
            fetchPaymentDetails();
            startPolling();
        });

        onUnmounted(() => {
            if (pollingInterval) {
                clearInterval(pollingInterval);
            }
        });

        return {
            loading,
            billingType,
            boletoUrl,
            pixQrCodeUrl,
            pixCode,
            copyToClipboard,
        };
    },
};
</script>

<style scoped>
.spinner-border {
    width: 3rem;
    height: 3rem;
}
</style>
