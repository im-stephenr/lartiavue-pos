<script setup>
import { computed, ref, watch } from "vue";
import axios from "axios";
import Modal from "../Modal.vue";
import { route } from "../../../../vendor/tightenco/ziggy/src/js";
import { useForm } from "@inertiajs/vue3";
import { useToast } from "vue-toast-notification";
import { usePage } from "@inertiajs/vue3";

const props = defineProps({
    customers: Object,
    totalPayment: Object,
    customerActiveTabId: String,
    handleAddCustomer: Function,
    handleActiveCustomerTab: Function,
    handleCancelOrder: Function,
});

const sale = useForm({
    cashReceive: null,
    change: null,
    total: null,
    orderDetails: null,
    cashier: null,
});

const modalStatus = ref(false);
const cashReceive = ref(0);
const toast = useToast();
const page = usePage(); // adding this since we can't access the Inertia's initialProps using $page
// Handle Toggle Modal Close/OPen
const handleToggleModal = (status) => {
    // If total is zero, do nothing.
    if (props.totalPayment.total === 0) {
        return false;
    }
    if (status === "open") {
        modalStatus.value = true;
    } else {
        modalStatus.value = false;
    }

    // Clear sale data
    handleClearCashReceive();
};

// Handle Input number in Cash Receive
const handleInputNumber = (number) => {
    if (typeof number === "object") {
        number = number.target.value.slice(-1);
    }

    cashReceive.value = String(cashReceive.value);

    if (number === 10) number = 0; // convert the 10 to 0
    // if cashReceive value is just 0 then replace it with the inputted number
    if (cashReceive.value === 0) {
        cashReceive.value = number;
    } else {
        // If inputted number is a decimal
        if (number === ".") {
            console.log("Adding Decimal");
            // Checks if the cashReceive value already has decimal to ensure only 1 decimal character
            if (!cashReceive.value.includes(".")) {
                cashReceive.value += number;
            }
        } else {
            cashReceive.value = cashReceive.value += number; // combing the inputted number by Converting the cashRecieve to String and insert the inputted number
        }
    }
};

// Computed variable, so it will automatically convert the String to formatted number
const cashReceiveComputed = computed(() => {
    // The original Variable cashReceive.value still updates since we are creating a reference variable and the original variable is reactive (ref)
    const num = parseFloat(cashReceive.value); // create a new variable for the cashReceive and convert it into float
    return isNaN(num) ? "" : num.toLocaleString("en"); // if NaN return blank else return the formatted number (with comma and decimal)
});

// Computed variable, it will auto triggers if cashReceive.value or totalpayment.total has been modified and returns the result
const changeComputed = computed(() => {
    const rawReceive = cashReceive.value;
    const rawTotal = props.totalPayment.total;
    const finalChange = rawReceive === 0 ? 0 : rawReceive - rawTotal;
    return isNaN(finalChange) ? "" : finalChange.toLocaleString("en"); // return 0 first if the cash receive is zero so it wont show negative result
});

const handleDeleteInputtedNumber = () => {
    const rawString = String(cashReceive.value); // create new variable which is converted to string
    const newRaw = rawString.slice(0, -1); // remove last digit
    cashReceive.value = newRaw === "" ? 0 : Number(newRaw); // if cashReceive is empty after removing numbers then show 0 else show the remaining numbers
};

const handleClearCashReceive = () => {
    cashReceive.value = 0;
};

const handleProceedPayment = () => {
    // Dont Proceed if Cash receive is < total
    if (cashReceive.value < props.totalPayment.total) {
        console.log("Please get the cash first.");
        return false;
    }
    // Save/Send the data to backend
    const currentCustomer = props.customers.filter(
        (customer) => customer.id === props.customerActiveTabId
    );

    // Combine Cash Receive plus Change plus Order details and send/save to backend
    // Should add the logged in Cashier in Sale
    sale.cashReceive = cashReceive.value;
    sale.change = changeComputed.value;
    sale.orderDetails = currentCustomer[0].orderDetails;
    sale.total = props.totalPayment.total;
    sale.cashier = page.props.auth.user.name;

    try {
        sale.post(route("sale.add"), {
            preserveScroll: true,
            onSuccess: () => {
                toast.success("Sale Addedd Successfully!");
                // close the modal
                modalStatus.value = false;

                // Remove current customer
                props.handleCancelOrder(props.customerActiveTabId);
                // Clear sub total
                props.totalPayment.subTotal = 0;
                props.totalPayment.total = 0;
                // Create new customer active tab if customer tab is empty
                if (props.customers.length === 1) {
                    console.log("Creating new customer tab");
                    // create a new customer tab
                    props.handleAddCustomer();
                    setTimeout(() => {
                        props.handleActiveCustomerTab(props.customers[0].id);
                    }, 300);
                }
            },
        });
    } catch (error) {
        console.log("Error", error);
    }
};

defineEmits(["removeOrder", "manageQuantity"]);
</script>

<template>
    <!-- Payment Modal -->
    <Teleport to="body">
        <Modal :isOpen="modalStatus" @closeModal="handleToggleModal('close')">
            <template #header>
                <h3 class="font-bold text-center">PAYMENT</h3>
            </template>
            <template #body>
                <!-- Total -->
                <div class="grid grid-cols-[30%_70%]">
                    <div class="text-center mt-5">Total</div>
                    <div class="border-b-1 border-gray-200 p-1 text-center">
                        <p class="text-3xl font-bold mb-5">
                            ₱{{ totalPayment.total.toFixed(2) || 0 }}
                        </p>
                    </div>
                </div>
                <!-- Received Cash -->
                <div class="grid grid-cols-[30%_70%]">
                    <div class="text-center mt-5">Cash Received</div>
                    <div class="border-b-1 border-gray-200 p-1 text-center">
                        <p class="text-3xl float-left mt-5">₱</p>
                        <input
                            @keydown.backspace="handleClearCashReceive"
                            @input="handleInputNumber($event)"
                            ref="focusCashRecieve"
                            type="text"
                            class="text-3xl font-bold mt-5 mb-2"
                            style="width: 70%"
                            :value="`${cashReceiveComputed}`"
                        />
                    </div>
                </div>
                <!-- Cash Change -->
                <div class="grid grid-cols-[30%_70%]">
                    <div class="text-center mt-5">Change</div>
                    <div class="border-b-1 border-gray-200 p-1 text-center">
                        <p class="text-3xl font-bold mt-5 mb-2">
                            ₱
                            {{ changeComputed }}
                        </p>
                    </div>
                </div>
                <!-- Keypad # -->
                <div class="grid grid-cols-3 mt-5">
                    <button
                        class="border-1 border-gray-300 cursor-pointer hover:bg-gray-100 p-5 text-gray-600 bg-gray-200 font-bold text-2xl"
                        type="button"
                        :key="n"
                        v-for="n in 10"
                        @click.prevent="handleInputNumber(n)"
                    >
                        {{ n == 10 ? 0 : n }}
                    </button>
                    <!-- Decimal Button -->
                    <button
                        @click.prevent="handleInputNumber('.')"
                        class="border-1 border-gray-300 cursor-pointer hover:bg-gray-100 p-5 text-center bg-gray-200 font-bold"
                    >
                        .
                    </button>
                    <!-- Delete Button -->
                    <button
                        @click.prevent="handleDeleteInputtedNumber"
                        class="border-1 border-gray-300 cursor-pointer hover:bg-gray-100 p-5 text-center bg-gray-200"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="size-6 mx-auto"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 9.75 14.25 12m0 0 2.25 2.25M14.25 12l2.25-2.25M14.25 12 12 14.25m-2.58 4.92-6.374-6.375a1.125 1.125 0 0 1 0-1.59L9.42 4.83c.21-.211.497-.33.795-.33H19.5a2.25 2.25 0 0 1 2.25 2.25v10.5a2.25 2.25 0 0 1-2.25 2.25h-9.284c-.298 0-.585-.119-.795-.33Z"
                            />
                        </svg>
                    </button>
                </div>
            </template>
            <template #footer>
                <button
                    @click.prevent="handleProceedPayment"
                    type="button"
                    class="bg-blue-500 hover:bg-blue-600 p-5 w-full text-white font-bold cursor-pointer"
                >
                    PROCEED
                </button>
            </template>
        </Modal>
    </Teleport>
    <div class="bg-white p-5">
        <h3 class="text-[#a59a8e] font-bold text-xl mb-2">Bills</h3>
        <!-- Customer tab Row -->
        <div class="flex flex-row">
            <ul
                class="flex flex-wrap text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:border-gray-700 dark:text-gray-400"
            >
                <li v-for="(customer, index) in customers" class="me-2">
                    <a
                        @click.prevent="handleActiveCustomerTab(customer.id)"
                        href="#"
                        aria-current="page"
                        :class="[
                            customerActiveTabId === customer?.id
                                ? 'bg-[#99bc85] text-white'
                                : 'bg-gray-100 text-gray-600',
                            'inline-block',
                            'p-3',
                            'rounded-t-lg',
                        ]"
                        >Customer {{ index + 1 }}</a
                    >
                </li>
            </ul>
            <!-- Add Customer Button -->
            <button
                @click.prevent="handleAddCustomer"
                type="button"
                class="bg-[#E4EFE7] p-2 cursor-pointer ml-3"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="size-6"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 4.5v15m7.5-7.5h-15"
                    />
                </svg>
            </button>
        </div>
        <!-- Orders -->
        <div class="max-h-[60vh] overflow-y-scroll">
            <ul class="mt-6">
                <!-- We can actually use .find method inside v-for to get the current active customer based on customerActiveTabId -->
                <li
                    v-for="(order, index) in customers?.find(
                        (c) => c.id === customerActiveTabId
                    )?.orderDetails || []"
                    :key="order.id"
                    class="hover:bg-gray-100 mb-2"
                >
                    <div class="grid grid-cols-[70%_30%]">
                        <!-- Image -->
                        <div>
                            <img
                                :src="`storage/${
                                    order?.image !== null
                                        ? order.image
                                        : 'photos/emptyplate.png'
                                }`"
                                class="w-20 h-20 float-left mr-2 rounded-md"
                                alt=""
                            />
                            <!-- Product name -->
                            <h1
                                class="font-bold text-xl text-gray-600 float-left"
                            >
                                {{ order.title }}
                            </h1>
                            <!-- Remove Order -->
                            <button
                                @click.prevent="$emit('removeOrder', order.id)"
                                type="button"
                                class="p-1 rounded-sm mt-1 ml-2 cursor-pointer bg-red-400 hover:bg-red-600 text-white"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                    class="size-3"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"
                                    />
                                </svg>
                            </button>
                            <!-- Category Name -->
                            <p class="font-medium text-xs text-gray-400 mt-2">
                                #{{ order.category }}
                            </p>
                        </div>

                        <div class="text-right pt-3 pr-10">
                            <!-- Price -->
                            <h1 class="font-bold text-xl font-gray-600 mb-3">
                                ₱{{ order.price }}
                            </h1>
                            <!-- Quantity -->
                            <div class="flex justify-end">
                                <!-- DECREASE QUANTITY BUTTON -->
                                <button
                                    @click.prevent="
                                        $emit(
                                            'manageQuantity',
                                            order.id,
                                            'deduct'
                                        )
                                    "
                                    type="button"
                                    class="bg-red-100 text-red-600 hover:bg-red-200 cursor-pointer rounded-sm p-1"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor"
                                        class="size-4"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M5 12h14"
                                        />
                                    </svg>
                                </button>
                                <!-- QUANTITY NUMBER -->
                                <input
                                    type="number"
                                    min="1"
                                    class="border-none outline-hidden w-8 bg-white text-center"
                                    :value="`${order.qty}`"
                                />
                                <!-- ADD QUANTITY BUTTON -->
                                <button
                                    @click.prevent="
                                        $emit('manageQuantity', order.id, 'add')
                                    "
                                    type="button"
                                    class="bg-green-100 hover:bg-green-200 cursor-pointer rounded-sm p-1"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor"
                                        class="size-4 text-green-600"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M12 4.5v15m7.5-7.5h-15"
                                        />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <div class="pt-10">
            <table class="w-full">
                <tbody>
                    <tr class="text-gray-500">
                        <td class="p-2">Subtotal</td>
                        <td class="p-2">
                            ₱{{ totalPayment?.subTotal?.toFixed(2) || 0 }}
                        </td>
                    </tr>
                    <tr class="text-gray-500 hidden">
                        <td class="p-2">Discount</td>
                        <td class="p-2">₱3</td>
                    </tr>
                    <tr class="border-t-1 border-dashed border-gray-300">
                        <td class="p-2 font-bold text-xl">Total</td>
                        <td class="p-2 font-bold text-xl">
                            ₱{{ totalPayment?.total?.toFixed(2) || 0 }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="mt-10">
                <button
                    @click.prevent="handleCancelOrder(customerActiveTabId)"
                    type="button"
                    class="p-3 px-10 cursor-pointer rounded-sm hover:bg-red-400 text-red-600 font-bold bg-red-300"
                >
                    Cancel
                </button>
                <button
                    @click.prevent="handleToggleModal('open')"
                    type="button"
                    class="p-3 px-10 cursor-pointer rounded-sm hover:bg-green-400 text-white font-bold bg-green-300 float-right"
                >
                    Payment
                </button>
            </div>
        </div>
    </div>
</template>
