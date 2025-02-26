<script setup>
import {ref} from 'vue';

defineProps({
    modelValue: {
        type: String,
        required: true,
    },
    data: {
        type: Array,
    }
});

defineEmits(['update:modelValue']);

const input = ref(null);

defineExpose({focus: () => input.value.focus()});
</script>

<template>
    <select
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        class="bg-gray-50 capitalize border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full mt-1 p-2.5" data="" model-value="">
        <option v-for="item in data" :key="item" :value="item.id">
            <div class="capitalize">
            {{ item.name }}
            </div>
        </option>
    </select>
</template>
