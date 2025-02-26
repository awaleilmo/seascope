<script setup>
import {onBeforeMount, onBeforeUnmount} from "vue";
import {ref} from "vue";

const date = new Date();
const name = "Clock";
const dateTimer = ref({
    hours: date.getHours(),
    minutes: date.getMinutes(),
    seconds: date.getSeconds(),
})
const timer = ref(undefined);

// methods
const setDateTime = () => {
    const date = new Date();
    dateTimer.value.hours = zeroPadding(date.getHours(), 2)
    dateTimer.value.minutes = zeroPadding(date.getMinutes(), 2)
    dateTimer.value.seconds = zeroPadding(date.getSeconds(), 2)
}
const zeroPadding = (num, digit) => {
    let zero = "";
    for (let i = 0; i < digit; i++) {
        zero += "0";
    }
    return (zero + num).slice(-digit);
}

onBeforeMount(() => {
    timer.value = setInterval(setDateTime, 1000);
})
onBeforeUnmount(() => {
    clearInterval(timer.value);
})
</script>
<template>
    <div>
        {{ dateTimer.hours }}:{{ dateTimer.minutes }}:{{ dateTimer.seconds }}
    </div>
</template>
