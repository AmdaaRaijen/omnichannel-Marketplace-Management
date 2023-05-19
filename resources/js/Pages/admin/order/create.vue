<script>
export default {
    layout: AppLayout,
};
</script>
<script setup>
import axios from "axios";
import { notify } from "notiwind";
import { integer, object, string } from "vue-types";
import { Head, useForm } from "@inertiajs/inertia-vue3";
import { ref, onMounted, reactive } from "vue";
import AppLayout from "@/layouts/apps.vue";
import VBreadcrumb from "@/components/VBreadcrumb/index.vue";
import VInput from "@/components/Vinput/index.vue";
import VSelect from "@/components/VSelect/index.vue";

import VButton from "@/components/VButton/index.vue";

const customerData = ref({ id: "" });
const productData = ref({ id: "" });
const marketplaceData = ref({ id: "" });

const paymentOption = ["paid", "pending", "failed"];

const form = useForm({
    customer: "",
    product: [""],
    payment_status: "",
    marketplace: "",
    order_quantity: "",
});

const breadcrumb = [
    {
        name: "Dashboard",
        active: false,
        to: route("dashboard.index"),
    },
    {
        name: "Order",
        active: false,
        to: route("order.index"),
    },
    {
        name: "Create Order",
        active: true,
        to: route("order.createPage"),
    },
];

const props = defineProps({
    title: string(),
    customers: object(),
    products: object(),
    platforms: object(),
});

onMounted(() => {
    props.customers.map((customer) => {
        customerData.value[customer.id] = customer.name;
    });
    props.products.map((product) => {
        productData.value[product.id] = product.name;
    });
    props.platforms.map((marketplace) => {
        marketplaceData.value[
            marketplace.id
        ] = `${marketplace.name}, ${marketplace.platform_name}`;
    });
});

const handleCreate = () => {
    const totalPrice =
        props.products.find((product) => product.id == +form.product).price *
        form.order_quantity;

    const data = {
        customer_id: +form.customer,
        product_id: +form.product,
        payment_status: form.payment_status,
        sales_channel_id: +form.marketplace,
        order_quantity: +form.order_quantity,
        total_price: totalPrice,
        price: props.products.find((product) => product.id == +form.product)
            .price,
    };
    // Inertia.post(route("order.store"), data);
    axios
        .post(route("order.store"), data)
        .then((res) => {
            form.reset();
            notify(
                {
                    type: "success",
                    group: "top",
                    text: res.data.message,
                },
                2500
            );
        })
        .catch((res) => {
            notify(
                {
                    type: "error",
                    group: "top",
                    text: res.response.data.message,
                },
                2500
            );
        });
};
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
            ></div>
        </header>
        <div class="flex flex-col gap-4 p-4">
            <!-- CUSTOMER NAME -->
            <VSelect
                placeholder="Customer Name"
                label="Select Customer Name"
                :required="true"
                v-model="form.customer"
                :options="customerData"
            />

            <!-- PRODUCT NAME -->
            <VSelect
                placeholder="Product Name"
                label="Select Product Name"
                :required="true"
                v-model="form.product"
                :options="productData"
            />

            <!-- PAYMENT STATUS -->
            <VSelect
                placeholder="Payment Status"
                label="Payment Status"
                :required="true"
                v-model="form.payment_status"
                :options="paymentOption"
            />
            <!-- ORDER QUANTITY -->
            <VInput
                placeholder="Order Quantity"
                :required="true"
                type="number"
                v-model="form.order_quantity"
                label="Order Quantity"
            />
            <!-- MARKETPLACE NAME -->
            <VSelect
                placeholder="Marketplace Name"
                label="Select Store Name"
                :required="true"
                v-model="form.marketplace"
                :options="marketplaceData"
            />
            <VButton
                label="Add Product"
                type="primary"
                @click="handleCreate"
                class="mt-auto"
            />
        </div>
    </div>
</template>
