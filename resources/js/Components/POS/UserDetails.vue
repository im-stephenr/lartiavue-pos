<script setup></script>
<template>
    <div class="grid grid-cols-[20%_80%]">
        <div>
            <img
                class="h-auto w-15 float-right mt-3 rounded-lg shadow-sm dark:shadow-gray-800"
                :src="`https://randomuser.me/api/portraits/men/${Math.floor(
                    Math.random() * 100
                )}.jpg`"
                alt="image description"
            />
        </div>
        <div class="flex flex-col items-baseline pt-5 pl-5">
            <!-- USER NAME -->
            <p class="font-medium text-gray-500 text-sm">
                <!-- This data comes from inertia globally initialized props (initialPage) which was defined under Middleware/HandleInertiaRequest share method -->
                {{ $page.props.auth.user.name }}
            </p>
            <!-- USER TYPE -->
            <span class="font-bold text-sm text-[#99BC85]">Cashier</span>
        </div>
    </div>
</template>
