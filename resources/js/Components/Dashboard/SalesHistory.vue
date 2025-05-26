<script setup>
import Card from "../Card.vue";
import { ref } from "vue";

defineProps({
    salesHistory: Array,
});
</script>
<template>
    <Card cardTitle="Sales History">
        <div class="relative overflow-auto h-[350px]">
            <table
                class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400"
            >
                <thead
                    class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
                >
                    <tr>
                        <th scope="col" class="px-6 py-3">Order</th>
                        <th scope="col" class="px-6 py-3">Total</th>
                        <th scope="col" class="px-6 py-3">Cashier</th>
                        <th scope="col" class="px-6 py-3">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="sale in salesHistory"
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200"
                    >
                        <th
                            scope="row"
                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                        >
                            <ul
                                v-for="(order, index) in JSON.parse(
                                    sale.orderDetails
                                )"
                                :key="index"
                            >
                                <li>
                                    {{ index + 1 + ".) " + order.title }}
                                    <span class="text-xs text-red-500"
                                        >({{ order.qty }}) pc{{
                                            order.qty > 1 ? "s" : ""
                                        }}</span
                                    >
                                </li>
                            </ul>
                        </th>
                        <td class="px-6 py-4">₱{{ sale.total_formatted }}</td>
                        <td class="px-6 py-4">{{ sale.cashier }}</td>
                        <td class="px-6 py-4">
                            {{ sale.created_at_formatted }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </Card>
</template>
