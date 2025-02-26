<script setup>
import {VueGoodTable} from 'vue-good-table-next';
import Pagination from "@/Components/Pagination.vue";
import {useForm} from "@inertiajs/vue3";
import {onBeforeMount, ref} from "vue";
import Select from "@/Components/Select.vue";
import InputLabel from "@/Components/InputLabel.vue";
import EncodeConfig from "@/Configuration/encode.config.js";
import DecodeConfig from "@/Configuration/decode.config.js";
const props = defineProps({
    model: {
        request: true,
    },
    column: {
        request: true,
    },
    compact: {
        type: Boolean,
        default: false
    },
    searchDefault:{
        type: String,
        default: 'name'
    },
    searchActive:{
        type: Object,
        default: {
            selectActive:true,
            enabled:true
        }
    },
    dataTable: {
        type: Object,
        request: true,
    },
    selectOptions: {
        type: Object,
        default: {
            enabled: true
        }
    },
    lineNumbers: {
        type: Boolean,
        default: false
    },
    maxHeight: {
        type: String,
        default: '700px'
    },
    fixedHeader: {
        type: Boolean,
        default: false
    },
    rowStyleClassFn: {
        type: String || Function,
    },
    rtl: {
        type: Boolean,
        default: false
    },
    sortOptions: {
        type: Object,
        default: {
            enabled: false
        }
    },
    onRowClick: {
        type: Function
    },
    onRowDoubleClick: {
        type: Function
    },
    onCellClick: {
        type: Function
    },
    onRowMouseover: {
        type: Function
    },
    onRowMouseleave: {
        type: Function
    },
    onPageChange: {
        type: Function
    },
    onPerPageChange: {
        type: Function
    },
    onSelectAll: {
        type: Function
    },
    selectionChanged: {
        type: Function,
    }
})

const isLoading = ref(false)

// data
const tableData = ref(props.dataTable)
const rowPerPages = ref([
    {id: '10', name: '10'},
    {id: '20', name: '20'},
    {id: '50', name: '50'},
    {id: '100', name: '100'},
])
const params= useForm({
    perPage: '10',
    search: '',
    column: props.searchDefault,
})

// method
const start = () => {
    let checkLocalStorage = localStorage.getItem('activePage') ?? null
    let pageLocalStorage = localStorage.getItem('page') ?? null
    let perPageLocalStorage = localStorage.getItem('perPage') ?? null
    let searchLocalStorage = localStorage.getItem('search') ?? null
    let columnLocalStorage = localStorage.getItem('column') ?? null
    if(checkLocalStorage){
        params.perPage = perPageLocalStorage
        params.search = searchLocalStorage
        params.column = columnLocalStorage
        changePage(`page?=${pageLocalStorage}`)
    }
}

const changePage = (valueLink) => {
    isLoading.value = true
    setTimeout(async() => {
        let nextPage = valueLink !== null ? valueLink.split('page?=')[1] : localStorage.getItem('page')
        localStorage.setItem('activePage', '1')
        localStorage.setItem('page', nextPage)
        localStorage.setItem('perPage', params.perPage)
        localStorage.setItem('search', params.search)
        localStorage.setItem('column', params.column)
        let encode = EncodeConfig.base64(`{"page":"${nextPage}","search":"${params.search}","perPage":${params.perPage},"column":"${params.column}"}`)
        let parameter = `data=${encode}`
        let cek = await props.model.getPage(parameter)
        tableData.value = DecodeConfig.base64toJSON(cek.data.content)
        isLoading.value = false
    })
}

onBeforeMount(async () => {
    // start()
})
/** Use of Functions in Table Events
 *
 *  onRowClick(params) {
 *     // params.row - row object
 *     // params.pageIndex - index of this row on the current page.
 *     // params.selected - if selection is enabled this argument
 *     // indicates selected or not
 *     // params.event - click event
 *   }
 *
 *   onRowDoubleClick(params) {
 *     // params.row - row object
 *     // params.pageIndex - index of this row on the current page.
 *     // params.selected - if selection is enabled this argument
 *     // indicates selected or not
 *     // params.event - click event
 *   }
 *
 *   onCellClick(params) {
 *     // params.row - row object
 *     // params.column - column object
 *     // params.rowIndex - index of this row on the current page.
 *     // params.event - click event
 *   }
 *
 *   onRowMouseover(params) {
 *     // params.row - row object
 *     // params.pageIndex - index of this row on the current page.
 *   }
 *
 *   onRowMouseleave(row, pageIndex) {
 *     // row - row object
 *     // pageIndex - index of this row on the current page.
 *   }
 *
 *   onPageChange(params) {
 *     // params.currentPage - current page that pagination is at
 *     // params.prevPage - previous page
 *     // params.currentPerPage - number of items per page
 *     // params.total - total number of items in the table
 *   }
 *
 *   onPerPageChange(params) {
 *     // params.currentPage - current page that pagination is at
 *     // params.currentPerPage - number of items per page
 *     // params.total - total number of items in the table
 *   }
 *
 *   selectionChanged(params) {
 *     // params.selectedRows - all rows that are selected (this page)
 *   }
 */
</script>
<template>
    <div v-if="searchActive.enabled" class="flex justify-end w-auto mb-2 mt-4">
        <select v-if="searchActive.selectActive" id="countries" v-model="params.column"
                class="flex-shrink-0 truncate line-clamp-1 z-10 rounded-l-lg w-24 md:w-36 text-sm font-medium text-gray-900 bg-gray-100 border border-gray-300 hover:bg-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 ">
            <option v-for="(item, index) in column" v-show="item.search" :key="index"
                    :value="item.field">{{
                    item.label
                }}
            </option>
        </select>
        <div class="relative w-full">
            <input v-model="params.search" v-on:keyup.enter="changePage('page?=1')"
                   type="search" id="default-search"
                   class="block w-full p-3 text-sm text-gray-900s border border-gray-300 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 "
                   :class="searchActive.selectActive ? 'rounded-r-lg ':'rounded-lg'"
                   placeholder="Search ..." required>
            <button @click="changePage('page?=1')"
                    type="submit"
                    class="text-white absolute right-2.5 top-1.5 bg-gray-800 hover:bg-gray-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-1.5">
                <font-awesome-icon icon="fa-solid fa-search" class="mx-1"/>
            </button>
        </div>
    </div>
    <VueGoodTable
        :columns="column"
        :rows="tableData.data"
        :select-options="selectOptions"
        :line-numbers="lineNumbers"
        :max-height="maxHeight"
        :fixed-header="fixedHeader"
        :row-style-class="rowStyleClassFn"
        :rtl="rtl"
        :sort-options="sortOptions"
        :is-loading="isLoading"
        ref="datatab"
        :compactMode="compact"
        v-on:selected-rows-change="selectionChanged"
        v-on:row-click="onRowClick"
        v-on:row-dblclick="onRowDoubleClick"
        v-on:cell-click="onCellClick"
        v-on:row-mouseenter="onRowMouseover"
        v-on:row-mouseleave="onRowMouseleave"
        v-on:page-change="onPageChange"
        v-on:per-page-change="onPerPageChange"
        v-on:select-all="onSelectAll"
    >
        <template #table-row="props">
            <slot name="table-row" v-bind="props"></slot>
        </template>
        <template #table-column="props">
            <slot name="table-column" v-bind="props"></slot>
        </template>
        <template #column-filter="props">
            <slot name="column-filter" v-bind="props"></slot>
        </template>
    </VueGoodTable>
    <section>
        <div class="flex justify-end flex-wrap my-3">
            <InputLabel value="rows per page" class="px-3 py-2 mb-1 mr-1 text-xs leading-4 text-gray-400  font-normal"/>
            <Select @change="changePage('page?=1')"
                    class="min-w-[48px] max-w-[100px] px-3 py-2 mb-1 mr-7 text-xs leading-4 text-gray-400  font-normal"
                    :data="rowPerPages" :model-value="params.perPage" v-model="params.perPage"/>
            <template v-for="(link, key) in tableData.links">
                <div
                    v-if="link.url === null"
                    :key="key"
                    class="
                        px-3
                        py-2
                        mb-2
                        mr-1
                        text-xs
                        leading-4
                        text-gray-400
                        border
                        rounded
                    "
                    v-html="link.label"
                />
                <div v-else class="mb-1 mr-1">
                    <div
                        v-if="link.active"
                        :key="key"
                        class="
                        px-3
                        py-2
                        text-xs
                        leading-4
                        border
                        rounded
                        bg-blue-50
                        text-gray-400
                        border-blue-100
                    "
                        v-html="link.label"
                    />
                    <div
                        v-if="!link.active"
                        class="
                        flex items-center
                        px-3
                        py-2
                        text-xs
                        leading-4
                        border
                        rounded
                        cursor-pointer
                        hover:bg-white
                        focus:border-indigo-500 focus:text-indigo-500
                    "
                        :class="{ 'bg-white text-black': link.active ? '' : '' }"
                        @click="changePage(link.url)"
                        v-html="link.label"
                    />
                </div>
            </template>
        </div>
    </section>
</template>

<style scoped>

</style>
