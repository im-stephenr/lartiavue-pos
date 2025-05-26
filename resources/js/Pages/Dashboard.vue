<script setup>
import ContentContainer from "../components/ContentContainer.vue";
import SalesChartMonthly from "../components/dashboard/SalesChartMonthly.vue";
import SalesChartWeekly from "../components/dashboard/SalesChartWeekly.vue";
import SalesHistory from "../components/dashboard/SalesHistory.vue";
import SoldProducts from "../components/dashboard/SoldProducts.vue";
import DefaultLayout from "../Layouts/DefaultLayout.vue";
defineProps({
    months: Array,
    salesMonthly: Object,
    weeks: Array,
    salesWeekly: Object,
    salesHistory: Array,
    productSoldHistory: Object,
});
</script>
<template>
    <DefaultLayout>
        <ContentContainer>
            <div
                class="grid lg:grid-cols-2 md:grid-cols-1 sm-grid-cols-1 gap-4 p-5"
            >
                <SalesChartMonthly
                    :salesMonthly="salesMonthly"
                    :months="months"
                />
                <SalesChartWeekly :weeks="weeks" :salesWeekly="salesWeekly" />
                <SalesHistory :salesHistory="salesHistory" />
                <SoldProducts :productSoldHistory="productSoldHistory" />
            </div>
        </ContentContainer>
    </DefaultLayout>
</template>
