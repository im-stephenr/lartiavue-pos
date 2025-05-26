<script setup>
const props = defineProps({
    products: Object,
});
// EMITS
defineEmits(["handleAddOrder"]);
</script>
<template>
    <div class="p-5">
        <h3 class="text-[#a59a8e] font-bold text-xl pb-5">Menu</h3>
        <div
            class="grid lg:grid-cols-5 md:grid-cols-2 sm-grid-cols-2 xs-grid-cols-1 gap-5 overflow-y-scroll max-h-200"
        >
            <button
                v-for="product in products"
                @click.prevent="$emit('handleAddOrder', product)"
                type="button"
                class="w-50 shadow-xs text-center bg-white hover:bg-[#E4EFE7] hover:shadow-lg rounded-3xl cursor-pointer"
            >
                <!-- Product Image -->
                <img
                    class="w-full h-50 m-auto rounded-1xl"
                    :src="`storage/${
                        product?.image !== null
                            ? product.image
                            : 'photos/emptyplate.png'
                    }`"
                />
                <div class="mt-4">
                    <!-- Product Name -->
                    <p class="text-gray-600 font-medium">{{ product.title }}</p>
                    <!-- Product Category -->
                    <div class="flex gap-2 ml-5">
                        <p class="text-lg font-medium text-gray-700 mb-5">
                            <b>₱{{ product.price }}</b>
                        </p>
                        <small class="text-xs text-gray-400 font-medium mt-2"
                            >#{{ product.category }}</small
                        >
                    </div>
                </div>
            </button>
        </div>
    </div>
</template>
