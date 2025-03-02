<template>
    <loading-component :isLoading="isLoading" />
    <navbar-component />
    <div class="container pt-5 pb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Lista de Pagamentos</h3>
            <a class="btn btn-primary" href="/payments/new">
                <i class="fa-solid fa-circle-plus"></i> Gerar Pagamento
            </a>
        </div>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Nome do Gateway</th>
                    <th>Descrição</th>
                    <th>Valor</th>
                    <th>Vencimento</th>
                    <th>Tipo Cobrança</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="payment in payments" :key="payment.id" v-if="payments.length">
                    <td>{{ payment.gateway_name }}</td>
                    <td>{{ payment.description }}</td>
                    <td>{{ payment.amount }}</td>
                    <td>{{ payment.due_date }}</td>
                    <td>{{ payment.billing_type }}</td>
                    <td>{{ payment.status }}</td>
                    <td class="d-flex gap-1 justify-content-center">
                        <a
                            :href="`/payments/${payment.id}`"
                            class="btn btn-info btn-sm"
                        >
                            <i class="fa-solid fa-eye"></i>
                        </a>
                        <a
                            class="btn btn-warning btn-sm"
                            v-if="payment.external_url"
                            :href="payment.external_url"
                            target="_blank"
                        >
                            <i class="fa-solid fa-link"></i>
                        </a>
                    </td>
                </tr>
                <tr v-else>
                    <td colspan="6">Nenhum pagamento encontrado.</td>
                </tr>
            </tbody>
        </table>

        <!-- Paginação -->
        <div v-if="pagination.last_page > 1" class="d-flex justify-content-between align-items-center">
            <span>Página {{ pagination.current_page }} de {{ pagination.last_page }}</span>
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <li class="page-item" :class="{ disabled: !pagination.prev_page_url }" v-if="pagination.prev_page_url">
                        <a class="page-link" :href="`/payments?page=${pagination.prev_page_url}`" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <li
                        v-for="page in pagination.pages"
                        :key="page"
                        :class="{ active: page === pagination.current_page }"
                        class="page-item"
                    >
                        <a class="page-link" :href="`/payments?page=${page}`">{{ page }}</a>
                    </li>
                    <li class="page-item" :class="{ disabled: !pagination.next_page_url }" v-if="pagination.next_page_url">
                        <a class="page-link" :href="`/payments?page=${pagination.next_page_url}`" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import NavbarComponent from '../template/NavbarComponent.vue';
import LoadingComponent from '../template/LoadingComponent.vue';

export default {
    name: 'PaymentListComponent',
    components: {
        NavbarComponent,
        LoadingComponent
    },
    setup() {
        const isLoading = ref(true);
        const payments = ref([]);
        const pagination = ref({
            current_page: 1,
            last_page: 1,
            next_page_url: null,
            prev_page_url: null,
            pages: [],
        });
        const getQueryParam = (param) => {
            const queryString = window.location.search;
            const urlParams = new URLSearchParams(queryString);
            return urlParams.get(param) ?? 1;
        };

        const fetchPayments = async (page = 1) => {
            try {
                const response = await axios.get(`/api/payments?page=${page}`);
                payments.value = response.data.data;

                const meta = response.data.meta;
                pagination.value = {
                    current_page: meta.current_page,
                    last_page: meta.last_page,
                    next_page_url: meta.current_page < meta.last_page ? meta.current_page + 1 : null,
                    prev_page_url: meta.current_page > 1 ? meta.current_page - 1 : null,
                    pages: Array.from({ length: meta.last_page }, (_, i) => i + 1),
                };
            } catch (error) {
                console.error('Erro ao buscar pagamentos:', error);
            } finally {
                isLoading.value = false;
            }
        };

        onMounted(() => {
            fetchPayments(getQueryParam('page'));
        });

        return {
            payments,
            pagination,
            fetchPayments,
            isLoading,
        };
    },
};
</script>

<style scoped>
.pagination .page-item.active .page-link {
    background-color: #007bff;
    border-color: #007bff;
}
</style>
