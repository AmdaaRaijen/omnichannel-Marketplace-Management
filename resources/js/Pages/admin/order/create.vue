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
const stopDelete = ref(true);

const paymentOption = ["paid", "pending", "failed"];

const form = useForm({
    customer: "",
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

const productSelected = ref([
    {
        product_id: "",
        order_quantity: "",
        price: "0",
        priceAmount: "0",
    },
]);

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
    let total_price = 0;
    productSelected.value.map((product) => {
        total_price += product.priceAmount;
    });
    const data = {
        customer_id: +form.customer,
        product_selected: productSelected.value,
        payment_status: form.payment_status,
        sales_channel_id: +form.marketplace,
        total_price: total_price,
    };

    console.log(data);
    axios
        .post(route("order.store"), data)
        .then((res) => {
            form.reset();
            productSelected.value = [
                {
                    product_id: "",
                    order_quantity: "",
                    price: "0",
                    priceAmount: "0",
                },
            ];
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
            console.log(res.response.data);
            notify(
                {
                    type: "error",
                    group: "top",
                    text: res.response.data.meta,
                },
                2500
            );
        });
};

const handleAddAnotherProduct = () => {
    stopDelete.value = false;
    productSelected.value.push({
        product_id: "",
        order_quantity: "",
        price: "0",
        priceAmount: "0",
    });
};

const handleDeleteProduct = (index) => {
    productSelected.value.splice(index, 1);
    productSelected.value.length <= 1
        ? (stopDelete.value = true)
        : (stopDelete.value = false);
};

const handleProductPriceEstimation = (index) => {
    try {
        const productPrice = props.products.find((data) => {
            return data.id == +productSelected.value[index].product_id;
        }).price;
        const orderQuantity = productSelected.value[index].order_quantity;
        productSelected.value[index].price = productPrice;
        productSelected.value[index].priceAmount = productPrice * orderQuantity;
    } catch (e) {
        productSelected.value[index].price = 0;
        productSelected.value[index].priceAmount = 0;
    }
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
            <!-- SELECTED PRODUCT -->
            <div class="flex flex-col gap-4 justify-between">
                <div
                    v-for="(product, index) in productSelected"
                    class="flex w-full gap-4"
                >
                    <!-- PRODUCT NAME-->
                    <VSelect
                        placeholder="Product Name"
                        label="Select Product Name"
                        :required="true"
                        v-model="productSelected[index].product_id"
                        @change="handleProductPriceEstimation(index)"
                        :options="productData"
                        class="w-1/2"
                    />
                    <div class="flex flex-col">
                        <p
                            class="mb-3 whitespace-nowrap block text-sm font-medium text-slate-600"
                        >
                            Price
                        </p>
                        <span
                            class="block text-sm font-medium text-slate-600 mb-1 whitespace-nowrap"
                            >Rp. {{ product.price }}</span
                        >
                    </div>
                    <!-- ORDER QUANTITY -->
                    <VInput
                        placeholder="Order Quantity"
                        :required="true"
                        type="number"
                        v-model="productSelected[index].order_quantity"
                        @input="handleProductPriceEstimation(index)"
                        label="Order Quantity"
                        class="w-1/2"
                    />
                    <div class="flex flex-col">
                        <p
                            class="mb-3 whitespace-nowrap block text-sm font-medium text-slate-600"
                        >
                            Price Amount
                        </p>
                        <span
                            class="block text-sm font-medium text-slate-600 mb-1 whitespace-nowrap"
                            >Rp. {{ product.priceAmount }}</span
                        >
                    </div>
                    <VButton
                        label="delete"
                        type="danger"
                        v-if="stopDelete"
                        disabled
                        aria-disabled="stopDelete"
                        @click="handleDeleteProduct(index)"
                        class="mt-auto"
                    />
                    <VButton
                        label="delete"
                        type="danger"
                        v-if="!stopDelete"
                        aria-disabled="stopDelete"
                        @click="handleDeleteProduct(index)"
                        class="mt-auto"
                    />
                </div>
                <div class="flex justify-end">
                    <VButton
                        label="Add Another Product"
                        type="primary"
                        @click="handleAddAnotherProduct"
                        class="mt-auto"
                    />
                </div>
            </div>
            <!-- PAYMENT STATUS -->
            <VSelect
                placeholder="Payment Status"
                label="Payment Status"
                :required="true"
                v-model="form.payment_status"
                :options="paymentOption"
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
