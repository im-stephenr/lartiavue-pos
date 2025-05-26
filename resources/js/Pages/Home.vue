<script setup>
import { Head } from "@inertiajs/vue3";
// defineOptions({ layout: Layout }); // adding this code to make the Layout component as Parent component
</script>
<!-- THIS IS JUST A DEMO PAGE -->
<template>
    <Head title=" | Home" />
    <div>
        <!-- A shared prop that can be found under HandleInertiaRequest.php -->
        <h1 class="text-3xl text-red-400">
            HELLO {{ $page.props.auth.user }}!
        </h1>
    </div>
</template>
