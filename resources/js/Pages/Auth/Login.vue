<script setup>
import { ref } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import Checkbox from "@/Components/Checkbox.vue";

const props = defineProps({
    status: {
        type: String,
        default: "",
    },
});

const form = ref({
    email: "",
    password: "",
    remember: false,
    errors: '',
    processing: false,
});

const login = async () => {
    form.value.processing = true;
    try {
        await axios.get("/sanctum/csrf-cookie");
        const response = await axios.post("/api/login", form.value);
        localStorage.setItem("token", response.data.access_token);
        router.visit("/dashboard");
    } catch (error) {
        if (error.response && error.response.data.message) {
            form.value.errors = error.response.data.message;
        }
    } finally {
        form.value.processing = false;
    }
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div
            v-if="props.status"
            class="mb-4 font-medium text-sm text-green-600"
        >
            {{ props.status }}
        </div>
        <form @submit.prevent="login">
            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                />

            </div>

            <div class="block mt-4">
                <label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ms-2 text-sm text-gray-600">Remember me</span>
                </label>
            </div>
            <InputError class="mt-2" :message="form.errors" />
        
            <div class="flex items-center justify-end mt-4">
                <PrimaryButton
                    class="ms-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Log in
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
