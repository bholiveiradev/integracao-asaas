<template>
    <navbar-component></navbar-component>
    <div class="container pt-5 pb-5">
        <h3 class="mb-3 text-center">Gerar Pagamento</h3>
        <a class="link-offset-2 link-underline link-underline-opacity-0"
            style="--bs-icon-link-transform: translate3d(0, -0.125rem, 0)" href="/payments">
            <i class="fa-solid fa-arrow-left"></i>
            Voltar
        </a>
        <div class="card">
            <div class="card-body">
                <form @submit.prevent="handleSubmit">
                    <div class="mb-3">
                        <label for="description" class="form-label">Descrição</label>
                        <input type="text" class="form-control" id="description" v-model="formData.description" />
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Valor</label>
                        <input
                            type="text"
                            class="form-control"
                            id="amount"
                            v-money="moneyOptions"
                            v-model="formData.amount"
                            required
                        />
                    </div>
                    <div class="mb-3">
                        <label for="billing_type" class="form-label">Tipo de Cobrança</label>
                        <select class="form-select" id="billing_type" v-model="formData.billing_type" required>
                            <option value="BOLETO">BOLETO</option>
                            <option value="PIX">PIX</option>
                            <option value="CREDIT_CARD">
                                CARTÃO DE CRÉDITO
                            </option>
                        </select>
                    </div>
                    <div class="mb-3" v-if="formData.billing_type === 'CREDIT_CARD'">
                        <label for="installment_count" class="form-label">Qtd Parcelas</label>
                        <select class="form-select" id="installment_count" v-model="formData.installment_count" required>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="6">6</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                        </select>
                    </div>
                    <div class="row" v-if="formData.billing_type === 'CREDIT_CARD'">
                        <div class="col-12 mb-2">
                            <hr />
                        </div>
                        <div class="col-md-6">
                            <h5>Dados do Titular do Cartão</h5>
                            <div class="mb-3">
                                <label for="name" class="form-label">Nome Completo</label>
                                <input type="text" class="form-control" id="name" v-model="formData.name" required />
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" class="form-control" id="email" v-model="formData.email" required />
                            </div>
                            <div class="mb-3">
                                <label for="cpf_cnpj" class="form-label">CPF / CNPJ</label>
                                <input type="text" class="form-control" id="cpf_cnpj" v-model="formData.cpf_cnpj"
                                    v-mask="'###.###.###-##'" required />
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="postal_code" class="form-label">CEP</label>
                                        <input type="text" class="form-control" id="postal_code" @blur="fetchAddress"
                                            v-model="formData.postal_code" v-mask="'#####-###'" required />
                                            <small v-if="address">{{ address.logradouro }}</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="address_number" class="form-label">Número do Endereço</label>
                                        <input type="text" class="form-control" id="address_number"
                                            v-model="formData.address_number" required />
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="address_complement" class="form-label">Compl. do Endereço <small>(Opctional)</small></label>
                                <input type="text" class="form-control" id="address_complement"
                                    v-model="formData.address_complement" />
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Telefone com DDD</label>
                                <input type="text" class="form-control" id="phone" v-model="formData.phone"
                                    v-mask="'(##) ####-####'" required />
                            </div>
                            <div class="mb-3">
                                <label for="mobile_phone" class="form-label">Celular com DDD</label>
                                <input type="text" class="form-control" id="mobile_phone"
                                    v-model="formData.mobile_phone" v-mask="'(##) #####-####'" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h5>Dados do Cartão</h5>
                            <div class="mb-3">
                                <label for="card_number" class="form-label">Número do Cartão</label>
                                <input type="text" class="form-control" id="card_number" v-model="formData.card_number"
                                    v-mask="'#### #### #### ####'" required />
                            </div>
                            <div class="mb-3">
                                <label for="card_holder_name" class="form-label">Nome do Titular (Impresso no
                                    Cartão)</label>
                                <input type="text" class="form-control" id="card_holder_name"
                                    v-model="formData.card_holder_name" required />
                            </div>
                            <div class="mb-3">
                                <label for="card_expiration" class="form-label">Validade do Cartão (MM/AA)</label>
                                <input type="text" class="form-control" id="card_expiration"
                                    v-model="formData.card_expiration" v-mask="'##/##'" required />
                            </div>
                            <div class="mb-3">
                                <label for="cvv" class="form-label">CVV</label>
                                <input type="text" class="form-control" id="cvv" v-model="formData.cvv" v-mask="'###'"
                                    required />
                            </div>
                        </div>
                    </div>
                    <div class="d-grid gap-2 text-center">
                        <button type="submit" class="btn btn-primary d-block fw-bold" :disabled="loading">
                            <span v-if="loading" class="spinner-border spinner-border-sm mr-1" role="status"
                                aria-hidden="true"></span>
                            <span v-if="!loading">Finalizar Pagamento</span>
                            <span v-else>&nbsp;Gerando...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import { ref } from "vue";
import { mask } from "vue-the-mask";
import { VMoney } from 'v-money';

import NavbarComponent from "../template/NavbarComponent.vue";

export default {
    name: "CreatePaymentComponent",
    directives: {
        mask,
        money: VMoney,
    },
    components: {
        NavbarComponent,
    },
    setup() {
        const formData = ref({
            description: '',
            amount: '0.00',
            billing_type: 'BOLETO',
            installment_count: '1',
            card_number: '',
            card_holder_name: '',
            card_expiration: '',
            cvv: '',
            name: '',
            email: '',
            cpf_cnpj: '',
            postal_code: '',
            address_number: '',
            address_complement: '',
            phone: '',
            mobile_phone: '',
        });
        const address = ref(null);
        const loading = ref(false);

        const handleSubmit = async () => {
            loading.value = true;
            try {
                const response = await axios.post("/api/payments", formData.value);
                if (response.data.success) {
                    resetForm();
                    window.location.href = `/payments/${response.data.data.id}`;
                }
            } catch (error) {
                if (error.response.status === 422) {
                    alert(`Erro ao gerar pagamento: ${error.response.data.message}`);
                    return;
                }
                alert("Erro ao gerar pagamento");
            } finally { loading.value = false; }
        };

        const fetchAddress = async () => {
            if (!formData.value.postal_code) return;

            const cep = formData.value.postal_code;
            const cleanedCep = cep.replace(/\D/g, '');

            try {
                const response = await axios.get(
                    `https://viacep.com.br/ws/${cleanedCep}/json`
                );
                console.log(response.data);
                if (response.data.erro) {
                    throw new Error("CEP não encontrado");
                }
                address.value = response.data;
            } catch (err) {
                address.value = null;
            }
        };

        const resetForm = () => {
            formData.value = {
                description: '',
                amount: '0.00',
                billing_type: 'BOLETO',
                installment_count: '1',
                card_number: '',
                card_holder_name: '',
                card_expiration: '',
                cvv: '',
                name: '',
                email: '',
                cpf_cnpj: '',
                postal_code: '',
                address_number: '',
                address_complement: '',
                phone: '',
                mobile_phone: '',
            };
        };

        const moneyOptions = {
            decimal: '.',
            thousands: '',
            prefix: '',
            precision: 2,
        };

        return {
            formData,
            loading,
            address,
            moneyOptions,
            handleSubmit,
            fetchAddress,
        };
    },
};
</script>
