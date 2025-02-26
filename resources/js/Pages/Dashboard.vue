<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import Map from "@/Pages/DashboardPart/Map.vue";
</script>

<template>
    <Head title="Dashboard"/>

    <AuthenticatedLayout>
        <template #header>
            SeaScope – Smarter Maritime Monitoring
        </template>

        <div class="grid h-[93vh]">
            <Map/>
        </div>
    </AuthenticatedLayout>
</template>
