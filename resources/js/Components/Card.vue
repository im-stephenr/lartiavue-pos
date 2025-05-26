<script setup>
defineProps({
    cardTitle: String,
});
</script>
<template>
    <div
        class="mx-auto block p-6 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 w-full"
    >
        <h1 class="text-center font-bold mb-2 text-[#99BC85]">
            {{ cardTitle }}
        </h1>
        <slot></slot>
    </div>
</template>
