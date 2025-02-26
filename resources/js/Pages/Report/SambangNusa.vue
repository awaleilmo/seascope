<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import {onBeforeMount, ref} from "vue";
import sysConfig from "@/Configuration/sys.config.js";
import Binaan from "@/Pages/Report/Sambang/Binaan.vue";
import Sentuhan from "@/Pages/Report/Sambang/Sentuhan.vue";
import Pantauan from "@/Pages/Report/Sambang/Pantauan.vue";

// variable
const menuTab = ref([
    {id: 1, name: 'Binaan', logo: 'building-wheat'},
    {id: 2, name: 'Sentuhan', logo: 'house-flag'},
    {id: 3, name: 'Pantauan', logo: 'house-signal'}
])
const activeTab = ref(1)

// method
const onClickTab = (id) => {
    localStorage.setItem('activePage', '1')
    localStorage.setItem('page', '1')
    localStorage.setItem('perPage', '10')
    localStorage.setItem('search', '')
    localStorage.setItem('column', 'name')
    activeTab.value = id
}
onBeforeMount(() => {

})
</script>

<template>
    <Head title="Sambang Nusa"/>

    <AuthenticatedLayout>
        <template #header>
            Sambang Nusa Presisi
        </template>

        <div class=" mx-auto sm:px-6 lg:px-8">
            <div class="bg-white sm:px-4 overflow-hidden shadow-sm rounded-lg">
                <ul class="flex flex-wrap text-sm gap-3 font-medium text-center text-gray-500 border-b border-gray-200 dark:border-gray-700 dark:text-gray-400">
                    <li v-for="tab in menuTab" :key="tab.id" @click="activeTab = tab.id">
                        <a href="#" aria-current="page"
                           :class="{'active text-blue-600 border-blue-600' : activeTab === tab.id, 'border-transparent hover:text-gray-600 hover:border-gray-300' : activeTab !== tab.id}"
                           class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg  group" @click="onClickTab(tab.id)">
                            <font-awesome-icon :icon="['fas', tab.logo ]"
                                               class="w-5 h-5 mr-2"
                                               :class="activeTab === tab.id ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500'"
                            />
                            {{tab.name}}</a>
                    </li>
                </ul>
            </div>

            <div class="bg-white mt-2 overflow-hidden shadow-sm rounded-lg">
                <div v-if="activeTab === 1 ">
                    <header class="bg-white shadow px-6 py-4 font-bold text-xl text-gray-700">Binaan </header>
                    <binaan />
                </div>
                <div v-if="activeTab === 2 ">
                    <header class="bg-white shadow px-6 py-4 font-bold text-xl text-gray-700">Sentuhan </header>
                    <sentuhan />
                </div>
                <div v-if="activeTab === 3 ">
                    <header class="bg-white shadow px-6 py-4 font-bold text-xl text-gray-700">Pantauan </header>
                    <pantauan />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
