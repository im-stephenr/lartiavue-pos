<script setup>
import ContentContainer from "../components/ContentContainer.vue";
import Modal from "../components/Modal.vue";
import { onMounted, reactive, ref } from "vue";
import axios from "axios";
import Vue3EasyDataTable from "vue3-easy-data-table";
import DefaultLayout from "../Layouts/DefaultLayout.vue";
import { useForm } from "@inertiajs/vue3";
import { route } from "../../../vendor/tightenco/ziggy/src/js";
import { useToast } from "vue-toast-notification";

defineProps({
    products: Array,
    categories: Array,
});

const toast = useToast();
const modalStatus = ref(false);

const form = useForm({
    id: "",
    image: "",
    title: "",
    category: "",
    price: "",
    status: "In stock",
    created_at: "2025-05-10",
    updated_at: "2025-05-10",
});

// Handle Toggle Modal Close/OPen
const handleToggleModal = (status) => {
    if (status === "open") {
        modalStatus.value = true;
    } else {
        modalStatus.value = false;
    }

    form.errors = {}; // reset errors on modal toggle
};

const headers = [
    {
        text: "Image",
        value: "image",
    },
    {
        text: "Title",
        value: "title",
    },
    {
        text: "Category",
        value: "category",
    },
    {
        text: "Price",
        value: "price",
    },
    {
        text: "Status",
        value: "status",
    },
    {
        text: "Created At",
        value: "created_at_formatted",
    },
    {
        text: "Updated At",
        value: "updated_at_formatted",
    },
    {
        text: "Action",
        value: "actions",
        sortable: false,
    },
];

const handleAdd = () => {
    handleToggleModal("open");

    // Reset Form
    form.id = "";
    form.image = "";
    form.title = "";
    form.category = "";
    form.price = "";

    // Hide the image when add product is clicked
    setTimeout(() => {
        document.getElementById("uploadPhotoContainer").classList.add("hidden");
    }, 100);
};

const handleEdit = (data) => {
    handleToggleModal("open");
    form.id = data.id; // set the form ID of the selected product
    form.title = data.title;
    form.category = data.category;
    form.price = data.price;
    form.status = data.status;
    form.created_at = data.created_at;
    form.updated_at = data.updated_at;
    form.image = data.image;
    // Set the product photo on modal pop up
    if (form.image !== null) {
        setTimeout(() => {
            document
                .getElementById("uploadPhotoContainer")
                .classList.remove("hidden");
            document.getElementById("productPhoto").src =
                "storage/" + form.image;
        }, 100); // adding 500ms delay since productPhoto does not exist yet or returns null when opening modal
    } else {
        setTimeout(() => {
            document
                .getElementById("uploadPhotoContainer")
                .classList.add("hidden");
        }, 100);
    }
    console.log("Editing", data);
};

const handleDelete = (data) => {
    if (confirm("Are you sure you want to delete this Product?")) {
        try {
            form.delete(route("products.delete", data.id), {
                preserveScroll: true,
                onSuccess: () => {
                    toast.success("Product Deleted Successfully");
                },
            });
        } catch (error) {
            console.log("Error", error);
        }
    }
};

const handleSubmit = async () => {
    const isEditing = form.id === "" ? false : true;
    try {
        if (!isEditing) {
            form.post(route("products.add"), {
                preserveScroll: true,
                onSuccess: () => {
                    toast.success("Product added successfully!");
                    modalStatus.value = false;
                },
            });
        } else {
            form.post(route("products.update", form.id), {
                preserveScroll: true,
                forceFormData: true,
                onSuccess: () => {
                    toast.success("Product updated successfully!");
                    modalStatus.value = false;
                },
            });
        }
    } catch (error) {
        console.log("ERror", error);
    }
};

// Upload photo
const uploadPhoto = (e) => {
    let productPhoto = document.getElementById("productPhoto");
    let uploadPhotoContainer = document.getElementById("uploadPhotoContainer");
    uploadPhotoContainer.classList.remove("hidden");
    form.image = null; // Reset when choosing photo as it causes error

    if (form.image === null) {
        // Display the Photo Uploaded
        productPhoto.src = window.URL.createObjectURL(e.target.files[0]);
        form.image = e.target.files[0]; // set the image to the uploaded photo
    } else {
        form.image = e.target.files[0]; // set the image to the uploaded photo
        productPhoto.src = `storage/${form.image}`;
    }
};

const handleRemoveImage = () => {
    console.log("Removing Image");
    document.getElementById("uploadPhotoContainer").classList.add("hidden");
    form.image = null;
};
</script>
<template>
    <DefaultLayout>
        <Teleport to="body">
            <Modal
                :isOpen="modalStatus"
                @closeModal="handleToggleModal('close')"
            >
                <template #header>
                    <h3 class="text-xl text-[#99BC85]">
                        {{ form.id === "" ? "Add" : "Edit" }} Product
                    </h3>
                </template>
                <template #body>
                    <form
                        class="max-w-sm mx-auto"
                        @submit.prevent="handleSubmit"
                        id="addProductForm"
                    >
                        <input type="hidden" name="id" v-model="form.id" />
                        <div class="mb-5 text-center" id="uploadPhotoContainer">
                            <img
                                id="productPhoto"
                                width="200"
                                alt=""
                                class="p-1 border-1 border-gray-300 rounded-sm m-auto"
                            />
                            <button
                                @click.prevent="handleRemoveImage"
                                type="button"
                                title="Remove Image"
                                class="p-1 mt-1 bg-red-500 text-xs text-white rounded-sm font-bold"
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
                        </div>
                        <div class="mb-5">
                            <label
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                for="file_input"
                                >Upload Image</label
                            >
                            <input
                                type="file"
                                @input="uploadPhoto"
                                class="w-full text-slate-500 font-medium text-sm bg-gray-100 file:cursor-pointer cursor-pointer file:border-0 file:py-2 file:px-4 file:mr-4 file:bg-[#a59a8e] hover:file:bg-[#806446] file:text-white rounded"
                            />
                            <!-- This is from inertiajs feature that automatically pass reactive props from backend -->
                            <span
                                class="text-xs text-red-600"
                                v-if="form.errors"
                                >{{ form.errors.image }}</span
                            >
                        </div>
                        <div class="mb-5">
                            <label
                                for="product_name"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                >Name</label
                            >
                            <input
                                type="text"
                                id="product_name"
                                v-model="form.title"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder=""
                            />
                            <!-- This is from inertiajs feature that automatically pass reactive props from backend -->
                            <span
                                class="text-xs text-red-600"
                                v-if="form.errors"
                                >{{ form.errors.title }}</span
                            >
                        </div>
                        <div class="mb-5">
                            <label
                                for="category"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                >Category</label
                            >
                            <select
                                id="category"
                                v-model="form.category"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            >
                                <option
                                    v-for="category in categories"
                                    :value="`${category.title}`"
                                >
                                    {{ category.title }}
                                </option>
                            </select>
                            <!-- This is from inertiajs feature that automatically pass reactive props from backend -->
                            <span
                                class="text-xs text-red-600"
                                v-if="form.errors"
                                >{{ form.errors.category }}</span
                            >
                        </div>
                        <div class="mb-5" v-if="form.id !== ''">
                            <label
                                for="status"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                >Status</label
                            >
                            <select
                                id="status"
                                v-model="form.status"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            >
                                <option value="In stock">In stock</option>
                                <option value="Out of stock">
                                    Out of stock
                                </option>
                            </select>
                        </div>
                        <div class="mb-5">
                            <label
                                for="product_price"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                >Price</label
                            >
                            <input
                                type="number"
                                min="1"
                                id="product_price"
                                v-model="form.price"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder=""
                            />
                            <!-- This is from inertiajs feature that automatically pass reactive props from backend -->
                            <span
                                class="text-xs text-red-600"
                                v-if="form.errors"
                                >{{ form.errors.price }}</span
                            >
                        </div>
                    </form>
                </template>
                <template #footer>
                    <button
                        form="addProductForm"
                        type="submit"
                        class="text-white float-right bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                    >
                        Submit
                    </button>
                </template>
            </Modal>
        </Teleport>
        <ContentContainer>
            <div class="p-10">
                <h1 class="text-xl text-[#a59a8e] font-bold float-left">
                    Products List
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
                    >Add Product
                </button>
                <Vue3EasyDataTable
                    class="mt-10"
                    :theme-color="'#99BC85'"
                    :headers="headers"
                    :items="products"
                >
                    <!-- TEMPLATE FOR RENDERING IMAGE -->
                    <template #item-image="item">
                        <img
                            v-if="item.image !== null"
                            :src="`storage/${item.image}`"
                            width="100"
                            alt=""
                        />
                    </template>
                    <!-- TEMPLATE FOR RENDERING BUTTONS -->
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
