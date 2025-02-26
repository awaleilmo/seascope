<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
    processing: {
        type: Boolean,
    }
});
</script>
<template>
    <button
        :type="type"
        :disabled="processing"
        :class="{ 'opacity-25': processing }"
        class=" text-white bg-gradient-to-r from-teal-400 via-teal-500 to-teal-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-teal-300 shadow-lg shadow-teal-500/50  font-medium rounded-lg text-sm px-5 py-2.5 text-center transition ease-in-out duration-150"
    >
        <slot />
    </button>
</template>
