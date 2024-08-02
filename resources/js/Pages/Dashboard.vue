<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputSuccess from "@/Components/InputSuccess.vue";
import { Head } from "@inertiajs/vue3";
import axios from "axios";
import { ref, onMounted } from "vue";

const file = ref(null);
const products = ref([]);
const errorMessage = ref("");
const successMessage = ref("");


const handleFileUpload = (event) => {
    file.value = event.target.files[0];
};

const uploadCSV = async () => {
    const formData = new FormData();
    formData.append("file", file.value);

    try {
        errorMessage.value = '';
        await axios.get("/sanctum/csrf-cookie");
        const response = await axios.post("/api/import", formData);
        successMessage.value = response.data.message;
        fetchProducts();
    } catch (error) {
        successMessage.value = '';
        if (
            error.response &&
            error.response.data &&
            error.response.data.errors &&
            error.response.data.errors.file
        ) {
            errorMessage.value =
                "Error uploading file: " +
                error.response.data.errors.file.join(", ");
        } else {
            errorMessage.value = "Error uploading file:" + error.message;
        }
    }
};

const fetchProducts = async () => {
    try {
        errorMessage.value ='';
        const response = await axios.get("/api/products");
        products.value = response.data;
    } catch (error) {
        successMessage.value = '';
        errorMessage.value = "Error fetching products:" + error.message;
    }
};

onMounted(() => {
    fetchProducts();
});
</script>

<template>
    <Head title="Home" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Import Products
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-4 bg-gray-100">
                        <form @submit.prevent="uploadCSV" class="space-y-4">
                            <input
                                type="file"
                                @change="handleFileUpload"
                                class="block w-full p-2 text-gray-700 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                            <InputError class="mt-2" :message="errorMessage" />
                            <button
                                type="submit"
                                class="px-4 py-2 text-white bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                                Import
                            </button>
                        </form>
                    </div>
                    <InputSuccess class="mt-2" :message="successMessage" />
                    <h2 class="mt-4 mb-4 text-2xl font-bold">Products</h2>
                    <div v-if="!products.length">No Imported Products</div>
                    <div v-else>
                        <table class="min-w-full bg-white border border-gray-300">
                        <thead>
                            <tr class="bg-gray-100 border-b">
                                <th
                                    class="px-4 py-2 font-medium text-center text-gray-600"
                                >
                                    Name
                                </th>
                                <th
                                    class="px-4 py-2 font-medium text-center text-gray-600"
                                >
                                    Price
                                </th>
                                <th
                                    class="px-4 py-2 font-medium text-center text-gray-600"
                                >
                                    SKU
                                </th>
                                <th
                                    class="px-4 py-2 font-medium text-center text-gray-600"
                                >
                                    Description
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="product in products"
                                :key="product.id"
                                class="border-b hover:bg-gray-50"
                            >
                                <td class="px-4 py-2 text-center">
                                    {{ product.name }}
                                </td>
                                <td class="px-4 py-2 text-center">
                                    {{ product.price }}
                                </td>
                                <td class="px-4 py-2 text-center">
                                    {{ product.sku }}
                                </td>
                                <td class="px-4 py-2 text-center">
                                    {{ product.description }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                    
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
