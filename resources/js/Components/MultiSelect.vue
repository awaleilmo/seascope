<script setup>
import {onMounted, ref} from "vue";

const props = defineProps({
    labelText: {
        type: String,
        required: true,
    },
    data: {
        type: Array,
        required: true,
    },
    select: {
        type: Array,
        required: true,
    },
    model: {
        type: Array,
        required: true,
    },
})

const initialSelect= ref('')
const selectAccess= ref([])

const selectMulti = () => {
    if (initialSelect.value === 'all') {
        const data = props.data
        for (let i = 0; i < data.length; i++) {
            props.model.push(data[i].id)
            selectAccess.value.push(data[i].name)
        }
    } else {
        let findData = props.data.find((a) => a.id === initialSelect.value)
        props.model.push(findData.id)
        selectAccess.value.push(findData.name)
    }
}
const removeMulti = (index) => {
    selectAccess.value.splice(index, 1)
    props.model.splice(index, 1)
}

onMounted(() => {
    selectAccess.value = props.select
})
</script>
<template>
    <label class="block my-2 text-sm font-medium text-gray-900 ">{{ labelText }}</label>
    <select @change="selectMulti()"
            v-model="initialSelect"
            @select="$emit('update:modelValue', $event.target.value)"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
        <option value="all">
            All
        </option>
        <option v-for="item in data" :key="item" v-show="!selectAccess.find((a)=> a === item.name)" :value="item.id">
            {{ item.name }}
        </option>
    </select>
    <div class="my-2 flex flex-wrap">
        <div v-for="(item,index) in selectAccess" :key="index"
             class="bg-gray-500 mb-2 text-gray-100 text-sm font-medium mr-2 px-2.5 py-0.5 rounded">
            {{ item }}
            <button @click="removeMulti(index)" type="button"
                    class=" text-gray-400 bg-transparent hover:text-gray-200 rounded-lg text-sm ml-2 items-center">
                <font-awesome-icon icon="fa-solid fa-close"/>
                <span class="sr-only">Close modal</span>
            </button>
        </div>
    </div>
</template>
