import './bootstrap';
import '../css/app.css';
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import 'vue-good-table-next/dist/vue-good-table-next.css'
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import VueApexCharts from 'vue3-apexcharts'
import  {
    faCircleUser,
    faBook,
    faPencil,
    faSearch,
    faAdd,
    faClose,
    faReceipt,
    faChartPie,
    faUserGroup,
    faPhone,
    faEnvelope,
    faCartShopping,
    faPaperPlane,
    faBan,
    faClipboardCheck,
    faFileDownload,
    faArrowRight,
    faTrash,
    faFileExport,
    faPrint,
    faWineBottle,
    faBoxOpen,
    faCaretUp,
    faCaretDown,
    faCircle,
    faGear,
    faHouseFloodWaterCircleArrowRight,
    faBroom,
    faSchool,
    faHouseMedicalFlag,
    faBuildingShield,
    faBuildingWheat,
    faHouseFlag,
    faHouseSignal,
    faEye,
    faRecycle,
    faTrashCan,
    faBridgeWater,
    faHouseChimney,
    faSpinner,
    faSort,
    faSortUp,
    faSortDown,
    faFileImage,
    faImage,
    faClock,
    faCircleCheck,
    faCircleExclamation,
    faExpand,
    faCompress
} from '@fortawesome/free-solid-svg-icons'
library.add(
    faCircleUser,
    faBook,
    faPencil,
    faSearch,
    faAdd,
    faClose,
    faReceipt,
    faChartPie,
    faUserGroup,
    faPhone,
    faEnvelope,
    faCartShopping,
    faPaperPlane,
    faBan,
    faClipboardCheck,
    faFileDownload,
    faArrowRight,
    faTrash,
    faFileExport,
    faPrint,
    faWineBottle,
    faBoxOpen,
    faCaretUp,
    faCaretDown,
    faCircle,
    faGear,
    faHouseFloodWaterCircleArrowRight,
    faBroom,
    faSchool,
    faHouseMedicalFlag,
    faBuildingShield,
    faEye,
    faBuildingShield,
    faBuildingWheat,
    faHouseFlag,
    faHouseSignal,
    faRecycle,
    faTrashCan,
    faBridgeWater,
    faHouseChimney,
    faSpinner,
    faSort,
    faSortUp,
    faSortDown,
    faFileImage,
    faImage,
    faClock,
    faCircleCheck,
    faCircleExclamation,
    faExpand,
    faCompress
)
const appName = 'SeaScope – Smarter Maritime Monitoring';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({el, App, props, plugin}) {
        return createApp({render: () => h(App, props)})
            .use(plugin)
            .use(ZiggyVue)
            .component('font-awesome-icon', FontAwesomeIcon)
            .component('VueDatePicker', VueDatePicker)
            .component('apexchart', VueApexCharts)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
}).then(r =>{
});
