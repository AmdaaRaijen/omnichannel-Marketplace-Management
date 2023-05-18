<script>
export default {
    layout: AppLayout,
    components: { Link },
};
</script>
<script setup>
import axios from "axios";
import { notify } from "notiwind";
import { object, string } from "vue-types";
import { Head, Link } from "@inertiajs/inertia-vue3";
import { ref, onMounted, reactive } from "vue";
import AppLayout from "@/layouts/apps.vue";
import debounce from "@/composables/debounce";
import VDropdownEditMenu from "@/components/VDropdownEditMenu/index.vue";
import VDataTable from "@/components/VDataTable/index.vue";
import VPagination from "@/components/VPagination/index.vue";
import VBreadcrumb from "@/components/VBreadcrumb/index.vue";
import VLoading from "@/components/VLoading/index.vue";
import VEmpty from "@/components/src/icons/VEmpty.vue";
import VButton from "@/components/VButton/index.vue";
import VAlert from "@/components/VAlert/index.vue";
import VEdit from "@/components/src/icons/VEdit.vue";
import VTrash from "@/components/src/icons/VTrash.vue";
import VFilter from "./Filter.vue";
import VModalForm from "./ModalForm.vue";
import { Inertia } from "@inertiajs/inertia";

const query = ref({});
const updateAction = ref(false);
const itemSelected = ref({});
const openModalForm = ref(false);

const breadcrumb = [
    {
        name: "Dashboard",
        active: false,
        to: route("dashboard.index"),
    },
    {
        name: "Order",
        active: true,
        to: route("order.index"),
    },
];

const heads = [
    "Customer Name",
    "Product Name",
    "Payment Status",
    "Paid Date",
    "Total Price",
    "Marketplace",
    "",
];
const isLoading = ref(true);

const props = defineProps({
    title: string(),
    additional: object(),
    orders: object(),
});

const handleAddModalForm = () => {
    updateAction.value = false;
    openModalForm.value = true;
};
const handleEditModal = (data) => {
    updateAction.value = true;
    itemSelected.value = data;
    openModalForm.value = true;
};

const closeModalForm = () => {
    itemSelected.value = ref({});
    openModalForm.value = false;
};

const getData = debounce(async () => {
    axios
        .get(route("order.getdata"))
        .then((res) => {
            query.value = res.data;
        })
        .finally(() => (isLoading.value = false));
}, 500);

const handleCreatePage = () => {
    Inertia.get(route("order.createPage"));
};

onMounted(() => {
    getData();
});
</script>

<template>
    <Head :title="props.title" />
    <VBreadcrumb :routes="breadcrumb" />
    <div class="mb-4 sm:mb-6 flex justify-between items-center">
        <h1 class="text-2xl md:text-3xl text-slate-800 font-bold">Order</h1>
    </div>

    <div
        class="bg-white shadow-lg rounded-sm border border-slate-200"
        :class="isLoading && 'min-h-[40vh] sm:min-h-[50vh]'"
    >
        <header class="block justify-between items-center sm:flex py-6 px-4">
            <h2 class="font-semibold text-slate-800">All Products</h2>
            <div
                class="mt-3 sm:mt-0 flex space-x-2 sm:justify-between justify-end"
            >
                <!-- Filter -->
                <VFilter
                    @search="searchHandle"
                    @apply="applyFilter"
                    @clear="clearFilter"
                    :additional="additional"
                />
                <VButton
                    label="Create Order"
                    type="primary"
                    @click="handleCreatePage"
                    class="mt-auto"
                />
            </div>
        </header>
        <VDataTable :heads="heads" :isLoading="isLoading">
            <tr v-if="isLoading">
                <td
                    class="h-[100%] overflow-hidden my-2"
                    :colspan="heads.length"
                >
                    <VLoading />
                </td>
            </tr>
            <tr
                v-else-if="!isLoading"
                v-for="(order, index) in query"
                :key="index"
            >
                <td class="px-4 whitespace-nowrap h-16">
                    {{ order.customer_name }}
                </td>
                <td class="px-4 whitespace-nowrap h-16">
                    {{ order.product_name }}
                </td>
                <td class="px-4 whitespace-nowrap h-16">
                    {{ order.payment_status }}
                </td>
                <td class="px-4 whitespace-nowrap h-16">{{ order.paid_at }}</td>
                <td class="px-4 whitespace-nowrap h-16">
                    Rp. {{ order.total_price }}
                </td>
                <td class="px-4 whitespace-nowrap h-16">
                    {{ order.platform_name }}
                </td>
                <td class="px-4 whitespace-nowrap h-16 text-right">
                    <VDropdownEditMenu
                        class="relative inline-flex r-0"
                        :align="'right'"
                        :last="index === query.length - 1 ? true : false"
                    >
                        <li
                            class="cursor-pointer hover:bg-slate-100"
                            @click="handleEditModal(order)"
                        >
                            <div class="flex items-center space-x-2 p-3">
                                <span>
                                    <VEdit color="primary" />
                                </span>
                                <span>Edit</span>
                            </div>
                        </li>
                        <li class="cursor-pointer hover:bg-slate-100">
                            <div
                                class="flex justify-between items-center space-x-2 p-3"
                                @click="alertDelete(order)"
                            >
                                <span>
                                    <VTrash color="danger" />
                                </span>
                                <span>Delete</span>
                            </div>
                        </li>
                    </VDropdownEditMenu>
                </td>
            </tr>
        </VDataTable>
    </div>
    <VModalForm
        :data="itemSelected"
        :update-action="updateAction"
        :open-dialog="openModalForm"
        @close="closeModalForm"
        @successSubmit="successSubmit"
        :additional="additional"
    />
</template>