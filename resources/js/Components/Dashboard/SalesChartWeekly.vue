<script setup>
import { ref } from "vue";
import VueApexCharts from "vue3-apexcharts";
import Card from "../Card.vue";

defineOptions({
    name: "Sales",
});

const props = defineProps({
    weeks: Array,
    salesWeekly: Object,
});
const chartOptions = ref({
    chart: {
        id: "weekly-bar",
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
        categories: props.weeks, // change to week
    },
    colors: ["#fbba64"],
});
const series = ref([
    {
        name: "Sales",
        data: [
            props.salesWeekly.Sunday.total,
            props.salesWeekly.Monday.total,
            props.salesWeekly.Tuesday.total,
            props.salesWeekly.Wednesday.total,
            props.salesWeekly.Thursday.total,
            props.salesWeekly.Friday.total,
            props.salesWeekly.Saturday.total,
        ], // change to weekly sales
    },
]);
</script>
<template>
    <Card cardTitle="Sales (Week)">
        <VueApexCharts
            class="w-full"
            height="350"
            type="bar"
            :options="chartOptions"
            :series="series"
        ></VueApexCharts>
    </Card>
</template>
