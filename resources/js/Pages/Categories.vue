<script setup>
import ContentContainer from "../components/ContentContainer.vue";
import { onMounted, ref } from "vue";
import Modal from "../components/Modal.vue";
import axios from "axios";
import Vue3EasyDataTable from "vue3-easy-data-table";
import DefaultLayout from "../Layouts/DefaultLayout.vue";
import { route } from "../../../vendor/tightenco/ziggy/src/js";
import { useForm } from "@inertiajs/vue3";
import { useToast } from "vue-toast-notification";

defineProps({
    categories: Array,
});

const modalStatus = ref(false);
const form = useForm({
    title: null,
});
const categoryId = ref("");
const toast = useToast();

const handleToggleModal = (status) => {
    if (status === "open") {
        modalStatus.value = true;
    } else {
        modalStatus.value = false;
    }
    console.log("Form", form);
};

const handleSubmit = async () => {
    try {
        const isEditing = categoryId.value !== "" ? true : false;

        // if is editing then trigger axios PUT to update the existing data with the category id inside parameter **IMPORTANT
        if (isEditing) {
            // editing
            console.log("EDITING");
            form.put(route("categories.update", categoryId.value), {
                preserveScroll: true,
                onSuccess: () => {
                    toast.success("Category Updated Successfully!");
                    modalStatus.value = false;
                },
            });
        } else {
            // adding
            console.log("ADDING");
            form.post(route("categories.add"), {
                preserveScroll: true,
                onSuccess: () => {
                    toast.success("Category Added Successfully!");
                    modalStatus.value = false;
                },
            });
        }
    } catch (error) {
        console.log("Error", error);
    }
};

// Data table headers
const headers = [
    { text: "ID", value: "id" },
    { text: "Title", value: "title" },
    { text: "Created At", value: "created_at_formatted" },
    { text: "Updated At", value: "updated_at_formatted" },
    { text: "Actions", value: "actions", sortable: false },
];

const handleAdd = () => {
    handleToggleModal("open");
    form.title = null;
    categoryId.value = "";
};

const handleEdit = (data) => {
    handleToggleModal("open");
    form.title = data.title;
    categoryId.value = data.id;
};

const handleDelete = (data) => {
    if (confirm("Are you sure you want to delete this Category?")) {
        try {
            form.delete(route("categories.delete", data.id), {
                preserveScroll: true,
                onSuccess: () => {
                    toast.success("Category Deleted Successfully!");
                },
            });
        } catch (error) {
            console.log("ERror", error);
        }
    }
};
</script>
<template>
    <DefaultLayout>
        <Teleport to="body">
            <!-- ADD CATEGORY MODAL -->
            <Modal
                :isOpen="modalStatus"
                @closeModal="handleToggleModal('close')"
            >
                <template #header>
                    <h3 class="text-xl text-[#99BC85]">
                        {{ categoryId === "" ? "Add" : "Edit" }} Category
                    </h3>
                </template>
                <template #body>
                    <form
                        class="max-w-sm mx-auto"
                        id="addCategoryForm"
                        @submit.prevent="handleSubmit"
                    >
                        <input
                            type="hidden"
                            v-model="categoryId"
                            name="categoryId"
                        />
                        <div class="mb-5">
                            <label
                                for="product_name"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                >Name</label
                            >
                            <input
                                type="text"
                                id="categoryName"
                                name="category"
                                v-model="form.title"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder=""
                                required
                            />
                            <!-- This is from inertiajs feature that automatically pass reactive props from backend -->
                            <span
                                class="text-xs text-red-600"
                                v-if="form.errors"
                                >{{ form.errors.title }}</span
                            >
                        </div>
                    </form>
                </template>
                <template #footer>
                    <button
                        form="addCategoryForm"
                        type="submit"
                        class="text-white float-right bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                    >
                        Submit
                    </button>
                </template>
            </Modal>
        </Teleport>
        <!-- CATEGORY LIST -->
        <ContentContainer>
            <div class="p-10">
                <h1 class="text-xl text-[#a59a8e] font-bold float-left">
                    Category List
                </h1>
                <button
                    @click.prevent="handleAdd"
                    type="button"
                    class="bg-blue-500 cursor-pointer p-2 text-white text-sm rounded-sm ml-5 hover:bg-blue-600"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="size-5 float-left"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 4.5v15m7.5-7.5h-15"
                        /></svg
                    >Add Category
                </button>
                <!-- Object.values converts object to array -->
                <Vue3EasyDataTable
                    class="mt-10"
                    :theme-color="'#99BC85'"
                    :headers="headers"
                    :items="categories"
                >
                    <template #item-actions="item">
                        <button
                            @click="handleEdit(item)"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded cursor-pointer"
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
                                    d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"
                                />
                            </svg>
                        </button>
                        <button
                            @click="handleDelete(item)"
                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 ml-2 rounded cursor-pointer"
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
                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"
                                />
                            </svg>
                        </button>
                    </template>
                </Vue3EasyDataTable>
            </div>
        </ContentContainer>
    </DefaultLayout>
</template>
