<script setup>
const props = defineProps({
    progress: {
        type: Number,
        default: 0
    },
    title: {
        type: String,
        default: 'Progress'
    }
});
</script>

<template>

    <div class="flex justify-between mb-1">
        <span class="text-base font-medium">{{ title }}</span>
        <span class="text-sm font-medium">{{progress}}%</span>
    </div>
    <div class="w-full bg-gray-200 rounded-full h-6">
        <div class="bg-gradient-to-r from-teal-400 via-teal-500 to-teal-600 h-6 rounded-full" :style='`width:${progress}%`'></div>
    </div>

</template>
