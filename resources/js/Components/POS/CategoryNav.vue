<script setup>
defineProps({
    categories: Array,
    selectedCategory: String,
});

defineEmits(["handleSwitchCategory"]);
</script>
<template>
    <div class="mt-10 p-5">
        <h3 class="text-[#a59a8e] font-bold text-xl">Choose Category</h3>
        <div
            class="mt-5 flex gap-4 overflow-x-scroll [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none]"
        >
            <!-- List of categories in cards -->
            <!-- All Categories -->
            <button
                type="button"
                class="min-w-20 w-20 h-20 rounded-lg bg-white grid cursor-pointer hover:bg-[#f2f2f2]"
                @click.prevent="$emit('handleSwitchCategory', '')"
            >
                <div class="m-auto">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="size-6 text-[#a59a8e]"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M5.25 8.25h15m-16.5 7.5h15m-1.8-13.5-3.9 19.5m-2.1-19.5-3.9 19.5"
                        />
                    </svg>
                </div>
                <div class="text-[#a59a8e] font-medium text-xs text-center">
                    All
                </div>
            </button>
            <!-- Each Category -->
            <button
                v-for="category in categories"
                :key="category.id"
                type="button"
                :class="`min-w-20 w-20 h-20 rounded-lg  grid cursor-pointer hover:bg-[#f2f2f2] ${
                    selectedCategory === category.title
                        ? 'bg-[#f2f2f2]'
                        : 'bg-white'
                }`"
                @click.prevent="$emit('handleSwitchCategory', category.title)"
            >
                <div class="m-auto">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="size-6 text-[#a59a8e]"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M5.25 8.25h15m-16.5 7.5h15m-1.8-13.5-3.9 19.5m-2.1-19.5-3.9 19.5"
                        />
                    </svg>
                </div>
                <div class="text-[#a59a8e] font-medium text-xs text-center">
                    {{ category.title }}
                </div>
            </button>
        </div>
    </div>
</template>
