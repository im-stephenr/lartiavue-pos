<script setup>
import { ref } from "vue";

const props = defineProps({
    handleSearchSubmit: Function,
});
// Use reactive for multiple inputs
const searchKey = ref(""); // this automatically links to input with v-model='searchKey'

const handleOnSearch = () => {
    props.handleSearchSubmit(searchKey.value);
};
</script>
<template>
    <form
        class="flex items-center w-full mx-auto"
        method="POST"
        @submit.prevent="handleOnSearch"
    >
        <label for="voice-search" class="sr-only">Search</label>
        <div class="relative w-full">
            <div
                class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="size-6 w-4 h-4 text-gray-500 dark:text-gray-400"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"
                    />
                </svg>
            </div>
            <input
                v-model="searchKey"
                name="searchKey"
                type="text"
                class="bg-gray-50 border border-gray-100 text-gray-900 text-sm rounded-lg focus:outline-none block w-full ps-10 p-2.5"
                placeholder="Search Products or Categories..."
                required
            />
            <button
                type="submit"
                class="absolute inset-y-0 end-0 flex items-center pe-3 cursor-pointer"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="w-4 h-4 text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"
                    />
                </svg>
            </button>
        </div>
    </form>
</template>
