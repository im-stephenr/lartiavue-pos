<script setup>
import ContentContainer from "../components/ContentContainer.vue";
import Billing from "../Components/POS/Billing.vue";
import CategoryNav from "../Components/POS/CategoryNav.vue";
import ProductMenu from "../Components/POS/ProductMenu.vue";
import SearchBar from "../Components/POS/SearchBar.vue";
import UserDetails from "../Components/POS/UserDetails.vue";
import DefaultLayout from "../Layouts/DefaultLayout.vue";
import { ref, onMounted } from "vue";
import { route } from "../../../vendor/tightenco/ziggy/src/js";
import { router } from "@inertiajs/vue3";

defineProps({
    products: Array,
    categories: Array,
});

// Initialized data
const customers = ref([
    {
        id: Math.random().toString(36).substring(2, 7),
        orderDetails: [],
    },
]);

const totalPayment = ref({
    subTotal: 0,
    total: 0,
});
// Data of Current Active/Single Customer to be displayed in Billing
const customerActiveTabId = ref("");
// Selected Category
const selectedCategory = ref("");

// Handle Adding Customer Tab
const handleAddCustomer = () => {
    const newCustomer = {
        id: Math.random().toString(36).substring(2, 7),
        orderDetails: [],
    };
    customers.value.push(newCustomer);
};

// Handle active customer tab / Switching of Customer Tabs
const handleActiveCustomerTab = (customerId) => {
    // Set Customer Active Tab ID to be Passed in Billing Component
    customerActiveTabId.value = customerId;

    // Trigger Calculate Total when switching Customer Tabs
    // Get the Active Customer current order list
    const currentActiveCustomerData = customers.value.filter(
        (customer) => customer.id === customerActiveTabId.value
    );

    // Calculate subtotal
    totalPayment.value.subTotal = handleCalculateTotal(
        currentActiveCustomerData[0].orderDetails
    );
};

// Handle cancel order / removing customer tab
const handleCancelOrder = (customerId) => {
    const removedCustomer = customers.value.filter(
        (customer) => customer["id"] !== customerId
    );
    customers.value = removedCustomer;
};

// Handle Adding Order to Specific Customer (EMIT from ProductMenu.vue)
const handleAddOrder = (product) => {
    // Filter the current active customer data
    const currentActiveCustomer = customers.value.filter(
        (customer) => customer["id"] === customerActiveTabId.value
    );
    // Filter the order if already exist then update only the quantity
    // Add the order to active customer
    const orderExist = currentActiveCustomer[0].orderDetails.filter(
        (order) => order.id === product.id
    );
    // If product already exist in customer order, then just add +1 to quantity
    if (orderExist.length > 0) {
        // Add quantity to specific/filtered product
        // This will also automatically updates the main customer.orderDetails since we are using .filter above which is a clone from currentActiveCustomer[0].orderDetails and it is reactive (ref)
        orderExist[0].qty++;
    } else {
        product.qty = 1; // Set new property to product called qty with value 1
        currentActiveCustomer[0].orderDetails.push(product);
    }

    // Calculate subtotal
    totalPayment.value.subTotal = handleCalculateTotal(
        currentActiveCustomer[0].orderDetails
    );
};

// Handle Add/Deduct Quantity
const handleManageQuantity = (productId, category) => {
    // Get the Active Customer current order list
    const currentActiveCustomerData = customers.value.filter(
        (customer) => customer.id === customerActiveTabId.value
    );

    // If customer active data exist then delete the specific order
    if (currentActiveCustomerData) {
        const orderDetails = currentActiveCustomerData[0].orderDetails.filter(
            (order) => order.id === productId
        );
        // This will automatically updates the custers.value data since it is reactive
        if (category === "add") orderDetails[0].qty++;
        if (category === "deduct") {
            // Only deduct if qty is greater than 1
            if (orderDetails[0].qty > 1) {
                orderDetails[0].qty--;
            }
        }

        // Calculate subtotal
        totalPayment.value.subTotal = handleCalculateTotal(
            currentActiveCustomerData[0].orderDetails
        );
    }
};

// Handle Removing Order from Billing list
const handleRemoveOrder = (productId) => {
    // Get the Active Customer current order list
    const currentActiveCustomerData = customers.value.filter(
        (customer) => customer.id === customerActiveTabId.value
    );

    // If customer active data exist then delete the specific order
    if (currentActiveCustomerData) {
        currentActiveCustomerData[0].orderDetails =
            currentActiveCustomerData[0].orderDetails.filter(
                (order) => order.id !== productId
            );

        totalPayment.value.subTotal = handleCalculateTotal(
            currentActiveCustomerData[0].orderDetails
        );
    }
};

// This will calculate the list of orders depending on each quantity
const handleCalculateTotal = (orders) => {
    let result = 0;
    // Formula is price * quantity + total (accumulator)
    result = orders.reduce(
        (total, order) => order.price * order.qty + total,
        0
    );

    // Temporarily make the Total the same as the Sub Total since we don't have functions for Discounts yet.
    totalPayment.value.total = result;
    console.log("Total", result);

    return result;
};

const handleSearchSubmit = (searchKey) => {
    router.get(
        route("pos"),
        {
            search: searchKey,
        },
        {
            preserveScroll: true,
            preserveState: true,
        }
    );
};

const handleSwitchCategory = (category) => {
    selectedCategory.value = category;
    router.get(
        route("pos"),
        {
            category: category,
        },
        {
            preserveScroll: true,
            preserveState: true,
        }
    );
};

onMounted(async () => {
    customerActiveTabId.value = customers.value[0].id; // on initial load, set the first generated customer as active tab
});
</script>
<template>
    <DefaultLayout>
        <ContentContainer>
            <div
                class="grid sm:grid-cols-1 lg:grid-cols-[70%_30%] md:grid-cols-[70%_30%]"
            >
                <div>
                    <div class="grid grid-cols-[70%_30%] p-2">
                        <!-- Search -->
                        <div class="p-5">
                            <SearchBar
                                :handleSearchSubmit="handleSearchSubmit"
                            />
                        </div>
                        <!-- User Details -->
                        <UserDetails />
                    </div>
                    <!-- Categories -->
                    <CategoryNav
                        :categories="categories"
                        :selectedCategory="selectedCategory"
                        @handleSwitchCategory="handleSwitchCategory"
                    />
                    <!-- Product Menu -->
                    <ProductMenu
                        :products="products"
                        @handleAddOrder="handleAddOrder"
                    />
                </div>
                <!-- Billing Container -->
                <Billing
                    :customers="customers"
                    :customerActiveTabId="customerActiveTabId"
                    :handleAddCustomer="handleAddCustomer"
                    :handleActiveCustomerTab="handleActiveCustomerTab"
                    :handleCancelOrder="handleCancelOrder"
                    @removeOrder="handleRemoveOrder"
                    @manageQuantity="handleManageQuantity"
                    :totalPayment="totalPayment"
                />
            </div>
        </ContentContainer>
    </DefaultLayout>
</template>
