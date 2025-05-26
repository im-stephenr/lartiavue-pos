<script setup>
import Card from "../Card.vue";
defineProps({
    productSoldHistory: Object,
});
</script>
<template>
    <Card cardTitle="Total Sold Per Product">
        <div class="relative overflow-auto h-[350px]">
            <table
                class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400"
            >
                <thead
                    class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
                >
                    <tr>
                        <th scope="col" class="px-6 py-3">Product</th>
                        <th scope="col" class="px-6 py-3">Total Sold</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(total, product) in productSoldHistory"
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200"
                    >
                        <th
                            scope="row"
                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                        >
                            {{ product }}
                        </th>
                        <td
                            :class="`px-6 py-4 font-bold ${
                                total === 0 ? 'text-red-500' : ''
                            }`"
                        >
                            {{ total }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </Card>
</template>
