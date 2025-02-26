<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import {Link} from '@inertiajs/vue3';
import bg from '@/Assets/bg7.jpg'

const cssCustom = {
    backgroundImage: `url("${bg}")`,
    backgroundAttachment: 'fixed',
    backgroundRepeat: 'no-repeat',
}
</script>

<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-900">
        <div class="absolute min-h-screen bg-center bg-cover brightness-75 w-full" :style="cssCustom">
        </div>
        <div
            class="w-full sm:max-w-md mt-6 px-6 py-4 backdrop-blur-md drop-shadow-lg shadow-2xl overflow-hidden sm:rounded-lg border-2 border-white border-opacity-5"
        >
            <div class="flex justify-center w-full">
                <Link href="/">
                    <ApplicationLogo class="h-60 fill-current text-gray-500"/>
                </Link>
            </div>
            <slot/>
        </div>
    </div>
</template>
