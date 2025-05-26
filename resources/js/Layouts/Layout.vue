<script setup>
import { Link } from "@inertiajs/vue3";
import { route } from "../../../vendor/tightenco/ziggy/src/js";
</script>
<template>
    <!-- THIS IS JUST A TEST LAYOUT -->
    <div>
        <header>
            <nav>
                <div>
                    <ul class="flex flex-row gap-2 text-blue-400">
                        <!-- USE LINK TO CALL CLIENT SIDE RENDERING INSTEAD OF <a> WHICH CALLS SERVER SIDE AND REFRESHES THE PAGE-->
                        <!-- route('') is from ZiggyVue plugin -->
                        <li><Link :href="route('home')">Home</Link></li>
                        <li><Link :href="route('about')">About</Link></li>
                        <li><Link :href="route('register')">Register</Link></li>
                    </ul>
                </div>
            </nav>
        </header>
        <main>
            <slot />
        </main>
    </div>
</template>
