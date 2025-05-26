<script setup>
import { ref } from "vue";
import VueApexCharts from "vue3-apexcharts";
import Card from "../Card.vue";

defineOptions({
    name: "Sales",
});

const props = defineProps({
    months: Array,
    salesMonthly: Object,
});

const chartOptions = ref({
    chart: {
        id: "monthly-bar",
    },
    dataLabels: {
        formatter: (val) => val.toLocaleString(), // adds commas like 1,234.56
    },
    tooltip: {
        y: {
            formatter: (val) =>
                val.toLocaleString(undefined, { minimumFractionDigits: 2 }),
        },
    },
    xaxis: {
        categories: props.months, // Change to months
    },
    colors: ["#99BC85"],
});
const series = ref([
    {
        name: "Sales",
        data: [
            props.salesMonthly.January.total,
            props.salesMonthly.February.total,
            props.salesMonthly.March.total,
            props.salesMonthly.April.total,
            props.salesMonthly.May.total,
            props.salesMonthly.June.total,
            props.salesMonthly.July.total,
            props.salesMonthly.August.total,
            props.salesMonthly.September.total,
            props.salesMonthly.October.total,
            props.salesMonthly.November.total,
            props.salesMonthly.December.total,
        ], // change to monthly sales
    },
]);
</script>
<template>
    <Card cardTitle="Sales (Month)">
        <VueApexCharts
            class="w-full"
            height="350"
            type="bar"
            :options="chartOptions"
            :series="series"
        ></VueApexCharts>
    </Card>
</template>
