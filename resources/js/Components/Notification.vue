<script setup>
import {computed, ref, watch, watchEffect} from 'vue';
import {usePage} from "@inertiajs/vue3";

const props = defineProps({
    alerts: {
        type: Object,
        default: {
            status: false
        },
        required: true
    }
})

const timeOut = ref(setTimeout(() => {
    usePage().props.flash = []
    props.alerts.status = false
}, 100))

watchEffect(() => {
    if (props.alerts.status === true) {
        clearTimeout(timeOut.value)
        timeOut.value = setTimeout(() => {
            usePage().props.flash = []
            props.alerts.status = false
        }, 5000)
    }
})

const close = () => {
    usePage().props.flash = []
    props.alerts.status = false
}
const typeClass = computed(() => {
    return {
        primary: "bg-blue-50 text-blue-500",
        secondary: "bg-gray-200 text-gray-500",
        danger: "bg-red-50 text-red-500",
        success: "bg-green-100 text-green-500",
        warning: "bg-orange-100 text-yellow-500",
        info: "bg-blue-100 text-gray-500",
    }[props.alerts.type];
});
</script>
<template>
    <transition
        enter-active-class="ease-out duration-500"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="ease-in duration-500"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div v-if="alerts.status"
             class="flex items-center p-4 gap-4 mb-4 font-semibold border border-none drop-shadow-lg text-sm absolute top-16 w-[90%] md:w-1/2 md:top-20 left-1/2 transform -translate-x-1/2 rounded-lg "
             :class="typeClass"
        >
            <div class="ml-3 text-sm font-medium">
                {{ alerts.message }}
            </div>
            <button @click="close" type="button"
                    class=" text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                <font-awesome-icon icon="fa-solid fa-close"/>
                <span class="sr-only">Close modal</span>
            </button>
        </div>
    </transition>
</template>

<style scoped>

</style>
