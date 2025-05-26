<script setup>
import DemoLayout from "../Layouts/DemoLayout.vue";
import { Head } from "@inertiajs/vue3";
defineProps({
    name: String,
});
const testFunction = () => {
    alert("CLICKED!");
};

defineOptions({ layout: DemoLayout }); // adding this code to make the Layout component as Parent component
</script>
<!-- THIS IS JUST A DEMO PAGE -->
<template>
    <Head title=" | About" />
    <h1>ABOUT PAGE</h1>
    <p>Hello prop defined this file {{ name }}</p>
    <p>Hello Shared Prop {{ $page.props.auth.user }}</p>
    <button class="p-2 bg-blue-300 text-white" @click="testFunction">
        CLICK ME
    </button>
</template>
