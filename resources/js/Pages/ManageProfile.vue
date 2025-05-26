<script setup>
import { onMounted } from "vue";
import ContentContainer from "../components/ContentContainer.vue";
import DefaultLayout from "../Layouts/DefaultLayout.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import { useToast } from "vue-toast-notification";

defineProps({
    salesHistory: Object,
});

const page = usePage();
const toast = useToast();

const userForm = useForm({
    image: page.props.auth.user.image,
    name: page.props.auth.user.name,
    email: page.props.auth.user.email,
});

const userPasswordForm = useForm({
    password: null,
    password_confirmation: null,
});

const handleUserPasswordUpdateSubmit = () => {
    userPasswordForm.put(
        route("users.password.update", page.props.auth.user.id),
        {
            preserveScroll: true,
            onSuccess: () => {
                toast.success("Password Updated Successfully!");
            },
        }
    );
};

const handleUserFormSubmit = () => {
    userForm.post(route("users.profile.update", page.props.auth.user.id), {
        preserveScroll: true,
        onSuccess: () => {
            toast.success("Profile Updated Successfully!");
        },
    });
};

// Upload photo
const uploadPhoto = (e) => {
    let userPhoto = document.getElementById("userPhoto");
    userPhoto.src = window.URL.createObjectURL(e.target.files[0]); // set the avatar to the chosen uploaded photo
};

onMounted(() => {
    if (userForm.image === null) {
        let userPhoto = document.getElementById("userPhoto");
        userPhoto.src = `storage/photos/users/Anonymous.png`;
    } else {
        userPhoto.src = `storage/${userForm.image}`;
    }
});
</script>
<template>
    <DefaultLayout>
        <ContentContainer>
            <div class="w-full p-10 grid gap-4 grid-cols-1">
                <div class="bg-white text-center h-fit pb-20 shadow-sm">
                    <img
                        id="userPhoto"
                        class="w-40 mx-auto rounded-full border-1 border-gray-50 p-1 mt-10 shadow-sm dark:shadow-gray-800"
                        alt="image description"
                    />
                    <h3 class="text-3xl font-bold mt-5">
                        {{ $page.props.auth.user.name }}
                    </h3>
                    <p class="text-gray-400 text-md mt-1">Cashier</p>
                    <p class="text-gray-600 text-md mt-1 font-medium">
                        Date Started:
                        {{ $page.props.auth.user.created_at_formatted }}
                    </p>
                </div>
                <!-- UPDATE PROFILE -->
                <div>
                    <form
                        class="w-full shadow-sm p-10 bg-white"
                        @submit.prevent="handleUserFormSubmit"
                    >
                        <div>
                            <h3 class="text-3xl mb-10">Profile</h3>
                        </div>
                        <div class="mb-5">
                            <label
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                for="file_input"
                                >Upload Photo</label
                            >
                            <input
                                type="file"
                                @input="uploadPhoto"
                                class="w-full text-slate-500 font-medium text-sm bg-gray-100 file:cursor-pointer cursor-pointer file:border-0 file:py-2 file:px-4 file:mr-4 file:bg-[#a59a8e] hover:file:bg-[#806446] file:text-white rounded"
                            />
                            <span
                                class="text-xs text-red-500"
                                v-if="userForm.errors"
                                >{{ userForm.errors.image }}</span
                            >
                        </div>
                        <div class="mb-5">
                            <label
                                for="name"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                >Name</label
                            >
                            <input
                                type="text"
                                id="name"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder=""
                                required
                                v-model="userForm.name"
                            />
                            <span
                                class="text-xs text-red-500"
                                v-if="userForm.errors"
                                >{{ userForm.errors.name }}</span
                            >
                        </div>
                        <div class="mb-5">
                            <label
                                for="email"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                >Email</label
                            >
                            <input
                                type="email"
                                id="email"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder=""
                                required
                                v-model="userForm.email"
                            />
                            <span
                                class="text-xs text-red-500"
                                v-if="userForm.errors"
                                >{{ userForm.errors.email }}</span
                            >
                        </div>

                        <button
                            type="submit"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                        >
                            Update Profile
                        </button>
                    </form>
                </div>
                <!-- Change Password -->
                <div>
                    <form
                        class="w-full shadow-sm p-10 bg-white"
                        @submit.prevent="handleUserPasswordUpdateSubmit"
                    >
                        <div>
                            <h3 class="text-3xl mb-10">Change Password</h3>
                        </div>

                        <div class="mb-5">
                            <label
                                for="password"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                >Password</label
                            >
                            <input
                                type="password"
                                id="password"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                required
                                v-model="userPasswordForm.password"
                            />
                            <span
                                class="text-xs text-red-500"
                                v-if="userPasswordForm.errors"
                                >{{ userPasswordForm.errors.password }}</span
                            >
                        </div>

                        <div class="mb-5">
                            <label
                                for="password_confirmation"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                                >Password Confirmation</label
                            >
                            <input
                                type="password"
                                id="password_confirmation"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                required
                                v-model="userPasswordForm.password_confirmation"
                            />
                            <span
                                class="text-xs text-red-500"
                                v-if="userPasswordForm.errors"
                                >{{
                                    userPasswordForm.errors
                                        .password_confirmation
                                }}</span
                            >
                        </div>

                        <button
                            type="submit"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                        >
                            Change Password
                        </button>
                    </form>
                </div>
                <!-- empty space -->
                <div></div>
                <div class="shadow-sm bg-white p-10">
                    <h3 class="text-3xl mb-10">Transaction History</h3>
                    <div class="relative overflow-y-scroll max-h-[500px]">
                        <table
                            class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400"
                        >
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
                            >
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Product Sold
                                    </th>
                                    <th scope="col" class="px-6 py-3">Total</th>
                                    <th scope="col" class="px-6 py-3">
                                        Date Added
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200"
                                    v-for="sales in salesHistory"
                                >
                                    <td class="px-6 py-4">
                                        <ul
                                            v-for="(order, index) in JSON.parse(
                                                sales.orderDetails
                                            )"
                                            :key="index"
                                        >
                                            <li>
                                                {{
                                                    index +
                                                    1 +
                                                    ".) " +
                                                    order.title
                                                }}
                                                <span
                                                    class="text-xs text-red-500"
                                                    >({{ order.qty }}) pc{{
                                                        order.qty > 1 ? "s" : ""
                                                    }}</span
                                                >
                                            </li>
                                        </ul>
                                    </td>
                                    <td class="px-6 py-4">
                                        ₱{{ sales.total_formatted }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ sales.created_at_formatted }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </ContentContainer>
    </DefaultLayout>
</template>
