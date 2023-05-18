<script>
export default {
    layout: AppLayout,
};
</script>
<script setup>
import axios from "axios";
import { notify } from "notiwind";
import { object, string } from "vue-types";
import { Head, useForm } from "@inertiajs/inertia-vue3";
import { ref, onMounted, reactive } from "vue";
import AppLayout from "@/layouts/apps.vue";
import debounce from "@/composables/debounce";
import VDropdownEditMenu from "@/components/VDropdownEditMenu/index.vue";
import VDataTable from "@/components/VDataTable/index.vue";
import VPagination from "@/components/VPagination/index.vue";
import VBreadcrumb from "@/components/VBreadcrumb/index.vue";
import VInput from "@/components/Vinput/index.vue";
import VSelect from "@/components/VSelect/index.vue";

import VLoading from "@/components/VLoading/index.vue";
import VEmpty from "@/components/src/icons/VEmpty.vue";
import VButton from "@/components/VButton/index.vue";
import VAlert from "@/components/VAlert/index.vue";
import VEdit from "@/components/src/icons/VEdit.vue";
import VTrash from "@/components/src/icons/VTrash.vue";
import VFilter from "./Filter.vue";
import VModalForm from "./ModalForm.vue";

const query = ref({});
const customerData = ref({
    id: "",
    customer_name: "",
});
const updateAction = ref(false);
const itemSelected = ref({});
const openModalForm = ref(false);

const form = useForm({
    customer: "",
    product: [""],
    payment_status: "",
    // paid_date: "",
    // total_price: "",
    marketplace: "",
});

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

const props = defineProps({
    title: string(),
    customers: object(),
});

onMounted(() => {
    props.customers.map((item) => {
        console.log(item.name);
        customerData.value = {
            id: item.id,
            customer_name: item.name,
        };

        return {
            label: item.name,
            value: item.name,
        };
    });
});
</script>

<template>
    <Head :title="props.title" />
    <VBreadcrumb :routes="breadcrumb" />
    <div class="mb-4 sm:mb-6 flex justify-between items-center">
        <h1 class="text-2xl md:text-3xl text-slate-800 font-bold">
            Create Order
        </h1>
    </div>

    <div class="bg-white shadow-lg rounded-sm border border-slate-200">
        <header class="block justify-between items-center sm:flex py-6 px-4">
            <h2 class="font-semibold text-slate-800">Create</h2>
            <div
                class="mt-3 sm:mt-0 flex space-x-2 sm:justify-between justify-end"
            >
                <VButton
                    label="Add Product"
                    type="primary"
                    @click="handleAddModalForm"
                    class="mt-auto"
                />
            </div>
        </header>
        <div class="flex flex-col gap-4 p-4">
            <VSelect
                placeholder="Customer Name"
                :required="true"
                v-model="form.customer"
                :options="customerData.customer_name"
                label="Customer Name"
            />
            <VInput
                placeholder="Product"
                label="Product Name"
                :required="true"
                v-model="form.product"
                @update:modelValue="formError.name = ''"
            />
            <VInput
                placeholder="Payment Status"
                label="Payment Status"
                :required="true"
                v-model="form.payment_status"
                @update:modelValue="formError.name = ''"
            />
            <VInput
                placeholder="Marketplace"
                label="Store Name"
                :required="true"
                v-model="form.marketplace"
                @update:modelValue="formError.name = ''"
            />
        </div>
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
