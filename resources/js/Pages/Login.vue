<script setup>
// import { useToast } from "vue-toastification";
import { Link, useForm } from "@inertiajs/vue3";
import { route } from "../../../vendor/tightenco/ziggy/src/js";
import GuestLayout from "../Layouts/GuestLayout.vue";

// const toast = useToast();
// const route = useRouter();

const form = useForm({
    email: null,
    password: null,
    remember: null,
});

const handleSubmit = async () => {
    form.post(route("login"), {
        preserveScroll: true,
        onError: () => {
            form.reset("password");
        },
    });
};
</script>
<template>
    <GuestLayout>
        <div class="text-center">
            <h1 class="text-2xl mb-10">Login</h1>
        </div>
        <form @submit.prevent="handleSubmit">
            <div class="mb-5">
                <label
                    for="email"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                    >Your email</label
                >
                <input
                    v-model="form.email"
                    type="email"
                    id="email"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="email@email.com"
                    required
                />
                <!-- This is from inertiajs feature that automatically pass reactive props from backend -->
                <span class="text-xs text-red-600" v-if="form.errors">{{
                    form.errors.email
                }}</span>
            </div>
            <div class="mb-5">
                <label
                    for="password"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                    >Your password</label
                >
                <input
                    v-model="form.password"
                    type="password"
                    id="password"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    required
                />
            </div>
            <div class="flex items-start mb-5">
                <div class="flex items-center h-5">
                    <input
                        id="remember"
                        type="checkbox"
                        v-model="form.remember"
                        value=""
                        class="w-4 h-4 border border-gray-300 rounded-sm bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800"
                    />
                </div>
                <label
                    for="remember"
                    class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                    >Remember me</label
                >
            </div>
            <button
                type="submit"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
            >
                Submit
            </button>
            <Link
                class="float-right text-blue-500 hover:underline"
                :href="route('register')"
            >
                Register
            </Link>
        </form>
    </GuestLayout>
</template>
