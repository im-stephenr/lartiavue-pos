<script setup>
import { Head } from "@inertiajs/vue3";
import { reactive } from "vue";
import { router, useForm } from "@inertiajs/vue3";
import { route } from "../../../../vendor/tightenco/ziggy/src/js";

// Use this if we use router from vue
// const form = reactive({
//     name: null,
//     email: null,
// });
// use this if we use inertia form post url
const form = useForm({
    name: null,
    email: null,
});

const submit = () => {
    // console.log("Submit", form);

    // router.post("/register", form); another sample for url post using vue3

    // Use this if we use inertia form post url
    form.post(route("register"));
};
</script>
<template>
    <Head title=" | Register" />
    <h1>REGISTER</h1>
    <div>
        <form action="" @submit.prevent="submit" method="POST">
            <label for="name"
                >Name:
                <input
                    v-model="form.name"
                    type="text"
                    class="border-1"
                    name="name"
            /></label>
            <label for="email"
                >Email:
                <input
                    v-model="form.email"
                    type="email"
                    class="border-1"
                    name="email"
            /></label>
            <input
                type="submit"
                class="p-2 bg-green-400 ml-2 text-white rounded"
                value="Submit"
                :disabled="form.processing"
            />
        </form>
    </div>
</template>
