<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import EncodeConfig from "@/Configuration/encode.config.js";
import {onBeforeMount, onMounted, reactive, ref} from "vue";
import DecodeConfig from "@/Configuration/decode.config.js";
import AkuTable from "@/Components/AkuTable.vue";
import SuccessButton from "@/Components/SuccessButton.vue";
import WarningButton from "@/Components/WarningButton.vue";
import DangerWarning from "@/Components/DangerWarning.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import Modal from "@/Components/Modal.vue";
import Notification from "@/Components/Notification.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import TextInputArea from "@/Components/TextInputArea.vue";
import sysConfig from "@/Configuration/sys.config.js";
import GlobalModel from "@/Models/Global.model.js";
import Select from '@/Components/Select.vue';


// Variables

// model
const Model = reactive((GlobalModel));

// boolean
const isLoading = ref(true)
const rendering = ref(false)
const confirmDeleteData = ref(false);
const renderSaksi = ref(true);
const renderBarbuk = ref(true);
const renderTersangka = ref(true);

// object
const userAuth = ref(sysConfig.userAuth());

const popModal = ref({
    status: false,
    title: '',
})
const bootModel = ref({})
const alerts = ref({
    status: false,
    message: '',
    type: 'info',
})

// array
const columns = [
    {label: 'Action', field: 'action', search: false, sortable: false, width: 'auto'},
    {label: 'No Laporan', field: 'id_no_lp', search: true, sortable: true, width: '250px'},
    {label: 'Hasil Tangkapan', field: 'hasil_tangkapan', search: true, sortable: true, width: '300px'},
    {label: 'Jenis Kasus', field: 'jenis_kasus', search: true, sortable: true, width: '200px'},
    {label: 'Kronologis', field: 'kronologi', search: true, sortable: true, width: '600px'},
    {label: 'Tersangka', field: 'tersangka', search: true, sortable: true, width: '500px'},
    {label: 'Korban', field: 'korban', search: true, sortable: true, width: 'auto'},
    {label: 'Saksi', field: 'saksi', search: true, sortable: true, width: '500px'},
    {label: 'Melanggar Pasal', field: 'melanggar_pasal', search: true, sortable: true, width: '350px'},
    {label: 'Barang Bukti', field: 'barang_bukti', search: true, sortable: true, width: '300px'},
    {label: 'Kerugian', field: 'kerugian', search: true, sortable: true, width: '250px'},
    {label: 'BA Serah Terima Perkara dan BB', field: 'ba_serah_terima', search: true, sortable: true, width: '300px'},
    {label: 'Keterangan', field: 'keterangan', search: true, sortable: true, width: '300px'},
]

const arrayAgama = ref([
    {value: 'Islam', label: 'Islam'},
    {value: 'Kristen', label: 'Kristen'},
    {value: 'Protestan', label: 'Protestan'},
    {value: 'Hindu', label: 'Hindu'},
    {value: 'Buddha', label: 'Buddha'},
    {value: 'Khonghucu', label: 'Khonghucu'},
])

const arrayJenisKelamin = ref([
    {value: 'Laki-Laki', label: 'Laki-Laki'},
    {value: 'Perempuan', label: 'Perempuan'},
])

const arrHari = ref([
    'MINGGU', 'SENIN', 'SELASA', 'RABU', 'KAMIS', 'JUMAT', 'SABTU'
])
const arrBulan = ref([
    {value: '0', label: 'JANUARI'},
    {value: '1', label: 'FEBRUARI'},
    {value: '2', label: 'MARET'},
    {value: '3', label: 'APRIL'},
    {value: '4', label: 'MEI'},
    {value: '5', label: 'JUNI'},
    {value: '6', label: 'JULI'},
    {value: '7', label: 'AGUSTUS'},
    {value: '8', label: 'SEPTEMBER'},
    {value: '9', label: 'OKTOBER'},
    {value: '10', label: 'NOVEMBER'},
    {value: '11', label: 'DESEMBER'},
])

const printModal = ref({
    open: false,
    title: '',
    periode: '1',
    namaBulan: '',
    namaHari: '',
    tglDari: '',
    tglSampai: '',
    tgl: '',
    bulan: '',
    tahun: '',
    formatExport: 'xlsx',
});

// Methods
const startup = async () => {
    let check = parseInt(localStorage.getItem('page') ?? 1)
    let encode = EncodeConfig.base64(`{"page":${check},"search":"","perPage":10}`)
    let rawDT = await Model.getPage(`data=${encode}`)
    bootModel.value = DecodeConfig.base64toJSON(rawDT.data.content)
    rendering.value = true
}

const resetModel = () => {
    let tgl = new Date();
    Model.id = null;
    Model.id_no_lp = '';
    Model.tanggal_lp = tgl.toISOString().slice(0, 10);
    Model.hasil_tangkapan = '';
    Model.jenis_kasus = '';
    Model.kronologi = '';
    Model.tersangka_nama = ['',];
    Model.tersangka_nik = ['',];
    Model.tersangka_warganegara = ['',];
    Model.tersangka_suku = ['',];
    Model.tersangka_jk = ['',];
    Model.tersangka_tempat_tgl_lahir = ['',];
    Model.tersangka_agama = ['',];
    Model.tersangka_umur = ['',];
    Model.tersangka_pekerjaan = ['',];
    Model.tersangka_alamat = ['',];
    Model.korban = '';
    Model.saksi_nama = ['',];
    Model.saksi_nik = ['',];
    Model.saksi_warganegara = ['',];
    Model.saksi_suku = ['',];
    Model.saksi_jk = ['',];
    Model.saksi_tempat_tgl_lahir = ['',];
    Model.saksi_umur = ['',];
    Model.saksi_agama = ['',];
    Model.saksi_pekerjaan = ['',];
    Model.saksi_alamat = ['',];
    Model.melanggar_pasal = '';
    Model.barang_bukti = ['',];
    Model.kerugian = '';
    Model.penyidik = '';
    Model.ba_serah_terima = '';
    Model.keterangan = '';
    Model.created_by = userAuth._rawValue.id;
    Model.updated_by = userAuth._rawValue.id;
}

const addFunction = async () => {
    resetModel()
    isLoading.value = true
    popModal.value.status = true
    popModal.value.title = 'Tambah Data Perkara Penegakkan Hukum Kapal Patroli dan Subdit Gakkum'
    isLoading.value = false
}
const editFunction = async (val) => {
    isLoading.value = true
    Model.id = val.id
    Model.id_no_lp = val.id_no_lp
    Model.hasil_tangkapan = val.hasil_tangkapan
    Model.jenis_kasus = val.jenis_kasus
    Model.kronologi = val.kronologi
    Model.tersangka_nama = JSON.parse(val.tersangka_nama)
    Model.tersangka_nik = JSON.parse(val.tersangka_nik)
    Model.tersangka_warganegara = JSON.parse(val.tersangka_warganegara)
    Model.tersangka_suku = JSON.parse(val.tersangka_suku)
    Model.tersangka_jk = JSON.parse(val.tersangka_jk)
    Model.tersangka_tempat_tgl_lahir = JSON.parse(val.tersangka_tempat_tgl_lahir)
    Model.tersangka_agama = JSON.parse(val.tersangka_agama)
    Model.tersangka_umur = JSON.parse(val.tersangka_umur)
    Model.tersangka_pekerjaan = JSON.parse(val.tersangka_pekerjaan)
    Model.tersangka_alamat = JSON.parse(val.tersangka_alamat)
    Model.korban = val.korban
    Model.saksi_nama = JSON.parse(val.saksi_nama)
    Model.saksi_nik = JSON.parse(val.saksi_nik)
    Model.saksi_warganegara = JSON.parse(val.saksi_warganegara)
    Model.saksi_suku = JSON.parse(val.saksi_suku)
    Model.saksi_jk = JSON.parse(val.saksi_jk)
    Model.saksi_tempat_tgl_lahir = JSON.parse(val.saksi_tempat_tgl_lahir)
    Model.saksi_umur = JSON.parse(val.saksi_umur)
    Model.saksi_agama = JSON.parse(val.saksi_agama)
    Model.saksi_pekerjaan = JSON.parse(val.saksi_pekerjaan)
    Model.saksi_alamat = JSON.parse(val.saksi_alamat)
    Model.melanggar_pasal = val.melanggar_pasal
    Model.barang_bukti = JSON.parse(val.barang_bukti)
    Model.kerugian = val.kerugian
    Model.penyidik = val.penyidik
    Model.ba_serah_terima = val.ba_serah_terima
    Model.keterangan = val.keterangan
    Model.tanggal_lp = val.tanggal_lp
    Model.updated_by = userAuth._rawValue.id
    popModal.value.status = true
    popModal.value.title = 'Ubah Data Perkara Penegakkan Hukum Kapal Patroli dan Subdit Gakkum'
    isLoading.value = false
}
const confirmUserDeletion = (val) => {
    Model.id = val.id
    confirmDeleteData.value = true;
};
const DeleteFn = async () => {
    isLoading.value = true;
    rendering.value = false
    let encode = EncodeConfig.base64(JSON.stringify(Model.id))
    let deleteData = await Model.destroy(encode);
    if (deleteData.data.status) {
        alerts.value.status = true
        alerts.value.message = 'Data Berhasil Dihapus'
        alerts.value.type = 'success'
        closeModal()
        await startup()
        isLoading.value = false;
        rendering.value = true
    } else {
        isLoading.value = false
        rendering.value = true
        closeModal()
        alerts.value.status = true
        alerts.value.message = deleteData.data.message
        alerts.value.type = 'danger'
    }
}
const validation = () => {
    let modalHeader = document.getElementById('modal-header')
    if (Model.id_no_lp ==='' || Model.id_no_lp === null) {
        modalHeader.scrollIntoView({behavior: 'smooth'})
        alerts.value.status = true
        alerts.value.message = 'Nomor Tidak Boleh Kosong'
        alerts.value.type = 'danger'
        return false;
    }

    if (Model.tanggal_lp === '' || Model.tanggal_lp === null) {
        modalHeader.scrollIntoView({behavior: 'smooth'})
        alerts.value.status = true
        alerts.value.message = 'Tanggal Tidak Boleh Kosong'
        alerts.value.type = 'danger'
        return false;
    }

    if (Model.hasil_tangkapan === '' || Model.hasil_tangkapan === null) {
        modalHeader.scrollIntoView({behavior: 'smooth'})
        alerts.value.status = true
        alerts.value.message = 'Hasil Tangkapan Tidak Boleh Kosong'
        alerts.value.type = 'danger'
        return false;
    }

    if (Model.jenis_kasus === '' || Model.jenis_kasus === null) {
        modalHeader.scrollIntoView({behavior: 'smooth'})
        alerts.value.status = true
        alerts.value.message = 'Jenis Kasus Tidak Boleh Kosong'
        alerts.value.type = 'danger'
        return false;
    }

    if (Model.kronologi === '' || Model.kronologi === null) {
        modalHeader.scrollIntoView({behavior: 'smooth'})
        alerts.value.status = true
        alerts.value.message = 'Kronologi Tidak Boleh Kosong'
        alerts.value.type = 'danger'
        return false;
    }

    if (Model.tersangka_nama.length === 0) {
        modalHeader.scrollIntoView({behavior: 'smooth'})
        alerts.value.status = true
        alerts.value.message = 'Tersangka Tidak Boleh Kosong'
        alerts.value.type = 'danger'
        return false;
    }

    if (Model.tersangka_warganegara.length === 0) {
        modalHeader.scrollIntoView({behavior: 'smooth'})
        alerts.value.status = true
        alerts.value.message = 'Warganegara Tidak Boleh Kosong'
        alerts.value.type = 'danger'
        return false;
    }

    if (Model.korban === '' || Model.korban === null) {
        modalHeader.scrollIntoView({behavior: 'smooth'})
        alerts.value.status = true
        alerts.value.message = 'Korban Tidak Boleh Kosong'
        alerts.value.type = 'danger'
        return false;
    }

    if (Model.saksi_nama.length === 0) {
        modalHeader.scrollIntoView({behavior: 'smooth'})
        alerts.value.status = true
        alerts.value.message = 'Saksi Tidak Boleh Kosong'
        alerts.value.type = 'danger'
        return false;
    }
    if (Model.saksi_warganegara.length === 0) {
        modalHeader.scrollIntoView({behavior: 'smooth'})
        alerts.value.status = true
        alerts.value.message = 'Warganegara Tidak Boleh Kosong'
        alerts.value.type = 'danger'
        return false;
    }

    if (Model.melanggar_pasal === '' || Model.melanggar_pasal === null) {
        modalHeader.scrollIntoView({behavior: 'smooth'})
        alerts.value.status = true
        alerts.value.message = 'Melanggar Pasal Tidak Boleh Kosong'
        alerts.value.type = 'danger'
        return false;
    }

    if (Model.barang_bukti.length === 0 ) {
        modalHeader.scrollIntoView({behavior: 'smooth'})
        alerts.value.status = true
        alerts.value.message = 'Barang Bukti Tidak Boleh Kosong'
        alerts.value.type = 'danger'
        return false;
    }

    if (Model.ba_serah_terima === '' || Model.ba_serah_terima === null) {
        modalHeader.scrollIntoView({behavior: 'smooth'})
        alerts.value.status = true
        alerts.value.message = 'BA Serah Terima Tidak Boleh Kosong'
        alerts.value.type = 'danger'
        return false;
    }

    if (Model.keterangan === '' || Model.keterangan === null) {
        modalHeader.scrollIntoView({behavior: 'smooth'})
        alerts.value.status = true
        alerts.value.message = 'Keterangan Tidak Boleh Kosong'
        alerts.value.type = 'danger'
        return false;
    }

    save();
}


const addTersangka = () => {
    renderTersangka.value = false
    Model.tersangka_nama.push('')
    Model.tersangka_nik.push('')
    Model.tersangka_warganegara.push('')
    Model.tersangka_suku.push('')
    Model.tersangka_jk.push('')
    Model.tersangka_tempat_tgl_lahir.push('')
    Model.tersangka_agama.push('')
    Model.tersangka_umur.push('')
    Model.tersangka_pekerjaan.push('')
    Model.tersangka_alamat.push('')
    renderTersangka.value = true
}

const removeTersangka = (index) => {
    renderTersangka.value = false
    Model.tersangka_nama.splice(index, 1)
    Model.tersangka_nik.splice(index, 1)
    Model.tersangka_warganegara.splice(index, 1)
    Model.tersangka_suku.splice(index, 1)
    Model.tersangka_jk.splice(index, 1)
    Model.tersangka_tempat_tgl_lahir.splice(index, 1)
    Model.tersangka_agama.splice(index, 1)
    Model.tersangka_umur.splice(index, 1)
    Model.tersangka_pekerjaan.splice(index, 1)
    Model.tersangka_alamat.splice(index, 1)
    renderTersangka.value = true
}

const addSaksi = () => {
    renderSaksi.value = false
    Model.saksi_nama.push('')
    Model.saksi_nik.push('')
    Model.saksi_warganegara.push('')
    Model.saksi_suku.push('')
    Model.saksi_jk.push('')
    Model.saksi_tempat_tgl_lahir.push('')
    Model.saksi_umur.push('')
    Model.saksi_agama.push('')
    Model.saksi_pekerjaan.push('')
    Model.saksi_alamat.push('')
    renderSaksi.value = true
}

const removeSaksi = (index) => {
    renderSaksi.value = false
    Model.saksi_nama.splice(index, 1)
    Model.saksi_nik.splice(index, 1)
    Model.saksi_warganegara.splice(index, 1)
    Model.saksi_suku.splice(index, 1)
    Model.saksi_jk.splice(index, 1)
    Model.saksi_tempat_tgl_lahir.splice(index, 1)
    Model.saksi_umur.splice(index, 1)
    Model.saksi_agama.splice(index, 1)
    Model.saksi_pekerjaan.splice(index, 1)
    Model.saksi_alamat.splice(index, 1)
    renderSaksi.value = true
}


const addBarangBukti = () => {
    renderBarbuk.value = false
    Model.barang_bukti.push('')
    renderBarbuk.value = true
}

const removeBarangBukti = (index) => {
    renderBarbuk.value = false
    Model.barang_bukti.splice(index, 1)
    renderBarbuk.value = true
}


const save = async () => {
    Model.tersangka_nama = JSON.stringify(Model.tersangka_nama)
    Model.tersangka_nik = JSON.stringify(Model.tersangka_nik)
    Model.tersangka_warganegara = JSON.stringify(Model.tersangka_warganegara)
    Model.tersangka_suku = JSON.stringify(Model.tersangka_suku)
    Model.tersangka_jk = JSON.stringify(Model.tersangka_jk)
    Model.tersangka_tempat_tgl_lahir = JSON.stringify(Model.tersangka_tempat_tgl_lahir)
    Model.tersangka_umur = JSON.stringify(Model.tersangka_umur)
    Model.tersangka_agama = JSON.stringify(Model.tersangka_agama)
    Model.tersangka_pekerjaan = JSON.stringify(Model.tersangka_pekerjaan)
    Model.tersangka_alamat = JSON.stringify(Model.tersangka_alamat)

    Model.saksi_nama = JSON.stringify(Model.saksi_nama)
    Model.saksi_nik = JSON.stringify(Model.saksi_nik)
    Model.saksi_warganegara = JSON.stringify(Model.saksi_warganegara)
    Model.saksi_suku = JSON.stringify(Model.saksi_suku)
    Model.saksi_jk = JSON.stringify(Model.saksi_jk)
    Model.saksi_tempat_tgl_lahir = JSON.stringify(Model.saksi_tempat_tgl_lahir)
    Model.saksi_umur = JSON.stringify(Model.saksi_umur)
    Model.saksi_agama = JSON.stringify(Model.saksi_agama)
    Model.saksi_pekerjaan = JSON.stringify(Model.saksi_pekerjaan)
    Model.saksi_alamat = JSON.stringify(Model.saksi_alamat)

    Model.barang_bukti = JSON.stringify(Model.barang_bukti)
    isLoading.value = true
    rendering.value = false
    let encode = EncodeConfig.base64(JSON.stringify(Model))
    let saveData = await Model.createUpdate(`data=${encode}`)
    if (saveData.data.status) {
        alerts.value.status = true;
        alerts.value.message = 'Data Berhasil Disimpan';
        alerts.value.type = 'success';
        closeModal()
        await startup()
        isLoading.value = false;
    } else {
        let modalHeader = document.getElementById('modal-header')
        modalHeader.scrollIntoView({behavior: 'smooth'})

        Model.tersangka_nama = JSON.parse(Model.tersangka_nama)
        Model.tersangka_nik = JSON.parse(Model.tersangka_nik)
        Model.tersangka_warganegara = JSON.parse(Model.tersangka_warganegara)
        Model.tersangka_suku = JSON.parse(Model.tersangka_suku)
        Model.tersangka_jk = JSON.parse(Model.tersangka_jk)
        Model.tersangka_tempat_tgl_lahir = JSON.parse(Model.tersangka_tempat_tgl_lahir)
        Model.tersangka_umur = JSON.parse(Model.tersangka_umur)
        Model.tersangka_agama = JSON.parse(Model.tersangka_agama)
        Model.tersangka_pekerjaan = JSON.parse(Model.tersangka_pekerjaan)
        Model.tersangka_alamat = JSON.parse(Model.tersangka_alamat)

        Model.saksi_nama = JSON.parse(Model.saksi_nama)
        Model.saksi_nik = JSON.parse(Model.saksi_nik)
        Model.saksi_warganegara = JSON.parse(Model.saksi_warganegara)
        Model.saksi_suku = JSON.parse(Model.saksi_suku)
        Model.saksi_jk = JSON.parse(Model.saksi_jk)
        Model.saksi_tempat_tgl_lahir = JSON.parse(Model.saksi_tempat_tgl_lahir)
        Model.saksi_umur = JSON.parse(Model.saksi_umur)
        Model.saksi_agama = JSON.parse(Model.saksi_agama)
        Model.saksi_pekerjaan = JSON.parse(Model.saksi_pekerjaan)
        Model.saksi_alamat = JSON.parse(Model.saksi_alamat)

        Model.barang_bukti = JSON.parse(Model.barang_bukti)
        alerts.value.status = true
        alerts.value.message = saveData.data.message
        alerts.value.type = 'danger'
        rendering.value = true
        isLoading.value = false
    }
}
const closeModal = () => {
    popModal.value.status = false
    confirmDeleteData.value = false
    resetModel()
}

const formatBarangBukti = (barbuk) => {
    let jsonBarbuk = JSON.parse(barbuk)
    let barangBukti = ''
    let nm = 1;
    jsonBarbuk.map((item, index) => {
        barangBukti += `${nm}. ${item}</br>`
        nm++
    })
    return barangBukti
}

const formatTersangka = (nama,nik,wn,suku,jk,ttl,umur,agama,pekerjaan,alamat) => {
    let jsonNama = JSON.parse(nama)
    let jsonNik = JSON.parse(nik)
    let jsonWn = JSON.parse(wn)
    let jsonSuku = JSON.parse(suku)
    let jsonJk = JSON.parse(jk)
    let jsonTtl = JSON.parse(ttl)
    let jsonUmur = JSON.parse(umur)
    let jsonAgama = JSON.parse(agama)
    let jsonPekerjaan = JSON.parse(pekerjaan)
    let jsonAlamat = JSON.parse(alamat)
    let tersangka = ''
    let nm = 1;
    jsonNama.map((item, index) => {
        tersangka += `
            ${nm}. ${item} </br>  &nbsp
            ${jsonNik[index]}</br>  &nbsp
            ${jsonWn[index]}</br>  &nbsp
            ${jsonSuku[index]}</br>  &nbsp
            ${jsonJk[index]}</br>  &nbsp
            ${jsonTtl[index]}</br>  &nbsp
            ${jsonUmur[index]} Thn </br>  &nbsp
            ${jsonAgama[index]}</br>  &nbsp
            ${jsonPekerjaan[index]}</br>  &nbsp
            ${jsonAlamat[index]}</br>
        </br>`
        nm++
    })
    return tersangka
}

const formatSaksi = (nama,nik,wn,suku,jk,ttl,umur,agama,pekerjaan,alamat) => {
    let jsonNama = JSON.parse(nama)
    let jsonNik = JSON.parse(nik)
    let jsonWn = JSON.parse(wn)
    let jsonSuku = JSON.parse(suku)
    let jsonJk = JSON.parse(jk)
    let jsonTtl = JSON.parse(ttl)
    let jsonUmur = JSON.parse(umur)
    let jsonAgama = JSON.parse(agama)
    let jsonPekerjaan = JSON.parse(pekerjaan)
    let jsonAlamat = JSON.parse(alamat)
    let saksi = ''
    let nm = 1;
    jsonNama.map((item, index) => {
        saksi += `
            ${nm}. ${item} </br>  &nbsp
            ${jsonNik[index]}</br>  &nbsp
            ${jsonWn[index]}</br>  &nbsp
            ${jsonSuku[index]}</br>  &nbsp
            ${jsonJk[index]}</br>  &nbsp
            ${jsonTtl[index]}</br>  &nbsp
            ${jsonUmur[index]} Thn </br>  &nbsp
            ${jsonAgama[index]}</br>  &nbsp
            ${jsonPekerjaan[index]}</br>  &nbsp
            ${jsonAlamat[index]}</br>  &nbsp
        </br>`
        nm++
    })
    return saksi
}


const formatDecimal = (angka) =>{
    let val = (angka/1).toFixed(2).replace('.', ',')
        return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
}

const printM = () => {
    isLoading.value = true;
    printModal.value.title = 'EXPORT DATA '
    printModal.value.button = 'Print'
    printModal.value.open = true;
    printModal.value.formatExport = 'pdf'
    const tanggal = new Date()
    printModal.value.tgl = tanggal.getDate()
    printModal.value.bulan = tanggal.getMonth()
    printModal.value.tahun = tanggal.getFullYear()
    isLoading.value = false;
}

const printFn = () => {
    isLoading.value = true
    if (printModal.value.periode === '0') {
        printModal.value.tglDari = printModal.value.tahun + '-' + (printModal.value.bulan + 1) + '-' + printModal.value.tgl
        printModal.value.tglSampai = printModal.value.tahun + '-' + (printModal.value.bulan + 1) + '-' + printModal.value.tgl
    } else if (printModal.value.periode === '1') {
        printModal.value.tglDari = printModal.value.tahun + '-' + (printModal.value.bulan + 1 < 10 ? '0' + (printModal.value.bulan + 1) : (printModal.value.bulan + 1)) + '-01'
        printModal.value.tglSampai = printModal.value.tahun + '-' + (printModal.value.bulan + 1 < 10 ? '0' + (printModal.value.bulan + 1) : (printModal.value.bulan + 1)) + '-31'
    } else if (printModal.value.periode === '2') {
        printModal.value.tglDari = printModal.value.tahun + '-01-01'
        printModal.value.tglSampai = printModal.value.tahun + '-12-31'
    }
    let tg = new Date(printModal.value.tglDari)
    printModal.value.namaHari = arrHari.value[tg.getDay()]
    let encode = EncodeConfig.base64(JSON.stringify(printModal.value))
    window.location.href = window.location.protocol + '//' + window.location.host + '/api/global/download?data=' + encode
    isLoading.value = false;
}

const numberFormat = (value) => {
    return value ? Number(value).toLocaleString('id-ID') : '-'
}


// Lifecycle
onBeforeMount(async () => {
    await startup()
})

onMounted(() => {
    isLoading.value = false
})
</script>

<template>
    <Head title="Sapu"/>

    <AuthenticatedLayout :props-loading="isLoading">
        <template #header>
            DATA PERKARA PENEGAKKAN HUKUM KAPAL PATROLI DAN SUBDIT GAKKUM
        </template>

        <div class=" mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <!-- Modal notification -->
                <Notification :alerts="alerts"/>
                <div class="md:px-3 md:py-4 flex flex-col md:flex-row gap-1.5 md:gap-1 md:justify-end">
                    <success-button class="pl-2 pr-3" @click="addFunction">
                        <font-awesome-icon icon="fa-solid fa-plus" class="mx-1"/>
                        Add
                    </success-button>
                    <primary-button class="pl-2 pr-3" @click="printM">
                        <font-awesome-icon icon="fa-solid fa-print" class="mx-1"/>
                        Export
                    </primary-button>
                </div>
                <AkuTable
                    v-if="rendering"
                    :column="columns"
                    :data-table="bootModel"
                    :select-options="{
                        enabled:false
                    }"
                    :model="Model"
                    :search-active="{
                        selectActive:false,
                        enabled:true
                    }"
                    checkbox-options
                    ref="datatable"
                >
                    <template #table-row="props">
                        <span v-if="props.column.field === 'barang_bukti'">
                            <span class="capitalize" v-html=" formatBarangBukti(props.row.barang_bukti)"/>
                        </span>

                        <span v-if="props.column.field === 'tersangka'">
                            <span class="capitalize"
                                v-html=" formatTersangka(props.row.tersangka_nama,
                                props.row.tersangka_nik,
                                props.row.tersangka_warganegara,
                                props.row.tersangka_suku,
                                props.row.tersangka_jk,
                                props.row.tersangka_tempat_tgl_lahir,
                                props.row.tersangka_umur,
                                props.row.tersangka_agama,
                                props.row.tersangka_pekerjaan,
                                props.row.tersangka_alamat,
                                )"/>
                        </span>

                        <span v-if="props.column.field === 'saksi'">
                            <span class="capitalize"
                                v-html=" formatSaksi(props.row.saksi_nama,
                                props.row.saksi_nik,
                                props.row.saksi_warganegara,
                                props.row.saksi_suku,
                                props.row.saksi_jk,
                                props.row.saksi_tempat_tgl_lahir,
                                props.row.saksi_umur,
                                props.row.saksi_agama,
                                props.row.saksi_pekerjaan,
                                props.row.saksi_alamat,
                                )"/>
                        </span>

                        <span v-if="props.column.field === 'kerugian'">
                            <span class="capitalize">
                                {{ formatDecimal(props.row.kerugian) }}
                            </span>
                        </span>

                         <span v-if="props.column.field === 'id_no_lp'">
                            {{ props.row.id_no_lp }} <br>
                            {{ props.row.tanggal_lp }}
                        </span>

                        <span v-if="props.column.field === 'action'">
                            <span class="flex gap-2">
                                <warning-button class="pl-2 pr-3" @click="editFunction(props.row)">
                                    <font-awesome-icon icon="fa-solid fa-pencil" class="mx-1"/>
                                    Edit
                                </warning-button>
                                <danger-warning class="pl-2 pr-3" @click="confirmUserDeletion(props.row)">
                                    <font-awesome-icon icon="fa-solid fa-trash" class="mx-1"/>
                                    Delete
                                </danger-warning>
                            </span>
                        </span>
                    </template>
                </AkuTable>
            </div>
        </div>

        <Modal :show="popModal.status" @close="closeModal" max-width="full">
            <div class="relative">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow">
                    <button @click="closeModal" type="button"
                            class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-red-300 hover:text-red-900 rounded-lg text-sm px-2.5 py-2 ml-auto inline-flex items-center"
                    >
                        <font-awesome-icon icon="fa-solid fa-close"/>
                        <span class="sr-only">Close modal</span>
                    </button>
                    <!-- Modal header -->
                    <div id="modal-header" class="px-6 py-4 border-b rounded-t bg-gray-100">
                        <h3 class="text-base font-semibold text-gray-900 lg:text-xl ">
                            {{ popModal.title }}
                        </h3>
                    </div>

                    <!-- Modal notification -->
                    <Notification :alerts="alerts"/>

                    <!-- Modal body -->
                    <div class="p-6">
                        <div class="mt-4">
                            <InputLabel value="No LP"/>
                                <TextInput
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="Model.id_no_lp"
                                    placeholder="Nomor LP"
                                />
                        </div>

                        <div class="mt-4">
                            <InputLabel value="Tanggal"/>
                            <div class="grid grid-cols-6 gap-3">
                                <TextInput
                                    type="date"
                                    class="mt-1 block w-full"
                                    v-model="Model.tanggal_lp"
                                    placeholder="Tanggal"
                                />
                            </div>
                        </div>

                        <div class="mt-4">
                            <InputLabel value="Hasil Tangkapa"/>
                                <TextInput
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="Model.hasil_tangkapan"
                                    placeholder="Hasil Tangkapan"
                                />
                        </div>

                        <div class="mt-4">
                            <InputLabel value="Jenis Kasus"/>
                                <TextInput
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="Model.jenis_kasus"
                                    placeholder="Jenis Kasus"
                                />
                        </div>

                        <div class="mt-4">
                            <InputLabel value="Kronologis"/>
                                <TextInputArea
                                    type="text"
                                    rows="10"
                                    class="mt-1 block w-full"
                                    v-model="Model.kronologi"
                                    placeholder="Kronologis"
                                />
                        </div>

                        <div class="mt-4">
                            <!-- <InputLabel value="Tersangka"/> -->
                            <button @click="addTersangka()" class="w-full bg-blue-200 rounded hover:bg-blue-300">
                                <font-awesome-icon icon="fa-solid fa-plus" class="mx-1"/> Tambah Tersangka
                            </button>
                            <br/> <br/>
                            <div class="rounded border px-2 py-3">
                                <div class="grid grid-cols-9 gap-2">
                                    <InputLabel class="px-2 font-bold" value="Nama Tersangka"/>
                                    <InputLabel class="px-2 font-bold" value="NIK"/>
                                    <InputLabel class="px-2 font-bold" value="Warganegara"/>
                                    <InputLabel class="px-2 font-bold" value="Suku"/>
                                    <InputLabel class="px-2 font-bold" value="Jenis Kelamin"/>
                                    <InputLabel class="px-2 font-bold" value="Tempat Tanggal Lahir"/>
                                    <InputLabel class="px-2 font-bold" value="Umur"/>
                                    <InputLabel class="px-2 font-bold" value="Agama"/>
                                    <InputLabel class="px-2 font-bold" value="Pekerjaan"/>
                                    <!-- <InputLabel class="px-2 font-bold" value="Alamat"/> -->
                                </div>

                                <div v-if="renderTersangka" v-for="(tersangka, index) in Model.tersangka_nama"
                                    class="grid grid-cols-1 gap-2"
                                    :class="Model.tersangka_nama.length > 1 ? 'mr-10' : ''"
                                >

                                    <div class="grid grid-cols-9 gap-2">
                                        <TextInput
                                            type="text"
                                            class="mt-1 block w-ful"
                                            v-model="Model.tersangka_nama[index]"
                                            placeholder="Nama Tersangka"
                                        />

                                        <TextInput
                                            type="number"
                                            class="mt-1 block w-full"
                                            v-model="Model.tersangka_nik[index]"
                                            placeholder="NIK"
                                        />

                                        <TextInput
                                            type="text"
                                            class="mt-1 block w-full"
                                            v-model="Model.tersangka_warganegara[index]"
                                            placeholder="Warganegara"
                                        />

                                        <TextInput
                                            type="text"
                                            class="mt-1 block w-full"
                                            v-model="Model.tersangka_suku[index]"
                                            placeholder="Suku"
                                        />


                                        <select
                                            class="mt-1 block w-full text-sm rounded-lg"
                                            v-model="Model.tersangka_jk[index]"
                                            @change="Model.tersangka_jk[index] = arrayJenisKelamin.find((item) => item.value === $event.target.value)['label']"
                                        >
                                            <option v-for="jenisKelamin in arrayJenisKelamin" :key="jenisKelamin.value" :value="jenisKelamin.value">
                                                {{ jenisKelamin.label }}
                                            </option>
                                        </select>

                                        <TextInput
                                            type="text"
                                            class="mt-1 block w-full"
                                            v-model="Model.tersangka_tempat_tgl_lahir[index]"
                                            placeholder="Tempat Tanggal Lahir"
                                        />

                                        <TextInput
                                            type="number"
                                            class="mt-1 block w-full"
                                            v-model="Model.tersangka_umur[index]"
                                            placeholder="Umur"
                                        />

                                        <select
                                            class="mt-1 block w-full text-sm rounded-lg"
                                            v-model="Model.tersangka_agama[index]"
                                            @change="Model.tersangka_agama[index] = arrayAgama.find((item) => item.value === $event.target.value)['label']"
                                        >
                                            <option v-for="agama in arrayAgama" :key="agama.value" :value="agama.value">
                                                {{ agama.label }}
                                            </option>
                                        </select>

                                        <TextInput
                                            type="text"
                                            class="mt-1 block w-full"
                                            v-model="Model.tersangka_pekerjaan[index]"
                                            placeholder="Pekerjaan"
                                        />

                                    </div>
                                    <TextInputArea
                                            type="text"
                                            class="mt-1 block w-full"
                                            v-model="Model.tersangka_alamat[index]"
                                            placeholder="Alamat"
                                        />

                                    <danger-warning v-if="Model.tersangka_nama.length > 1" class="absolute right-4 mt-1"
                                                    @click="removeTersangka(index)">
                                        <font-awesome-icon icon="fa-solid fa-trash" class="h-3"/>
                                    </danger-warning>
                                    <hr/> <br/>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <InputLabel value="Korban"/>
                                <TextInput
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="Model.korban"
                                    placeholder="Korban"
                                />
                        </div>

                        <div class="mt-4">
                            <!-- <InputLabel value="Saksi"/> -->
                            <button @click="addSaksi()" class="w-full bg-blue-200 rounded hover:bg-blue-300">
                                <font-awesome-icon icon="fa-solid fa-plus" class="mx-1"/> Tambah Saksi
                            </button>
                            <br/> <br/>
                            <div class="rounded border px-2 py-3">
                                <div class="grid grid-cols-9 gap-2">
                                    <InputLabel class="px-2 font-bold" value="Nama Saksi"/>
                                    <InputLabel class="px-2 font-bold" value="NIK"/>
                                    <InputLabel class="px-2 font-bold" value="Warganegara"/>
                                    <InputLabel class="px-2 font-bold" value="Suku"/>
                                    <InputLabel class="px-2 font-bold" value="Jenis Kelamin"/>
                                    <InputLabel class="px-2 font-bold" value="Tempat Tanggal Lahir"/>
                                    <InputLabel class="px-2 font-bold" value="Umur"/>
                                    <InputLabel class="px-2 font-bold" value="Agama"/>
                                    <InputLabel class="px-2 font-bold" value="Pekerjaan"/>
                                </div>
                                    <!-- <InputLabel class="px-2 font-bold" value="Alamat"/> -->

                                <div v-if="renderSaksi" v-for="(saksi, index) in Model.saksi_nama"
                                    class="grid grid-cols-1 gap-2"
                                    :class="Model.saksi_nama.length > 1 ? 'mr-10' : ''"
                                >
                                    <div class="grid grid-cols-9 gap-2">
                                        <TextInput
                                            type="text"
                                            class="mt-1 block w-full"
                                            v-model="Model.saksi_nama[index]"
                                            placeholder="Nama Saksi"
                                        />

                                        <TextInput
                                            type="number"
                                            class="mt-1 block w-full"
                                            v-model="Model.saksi_nik[index]"
                                            placeholder="NIK"
                                        />

                                        <TextInput
                                            type="text"
                                            class="mt-1 block w-full"
                                            v-model="Model.saksi_warganegara[index]"
                                            placeholder="Warganegara"
                                        />

                                        <TextInput
                                            type="text"
                                            class="mt-1 block w-full"
                                            v-model="Model.saksi_suku[index]"
                                            placeholder="Suku"
                                        />
                                        <select
                                            class="mt-1 block w-full text-sm rounded-lg"
                                            v-model="Model.saksi_jk[index]"
                                            @change="Model.saksi_jk[index] = arrayJenisKelamin.find((item) => item.value === $event.target.value)['label']"
                                        >
                                            <option v-for="jenisKelamin in arrayJenisKelamin" :key="jenisKelamin.value" :value="jenisKelamin.value">
                                                {{ jenisKelamin.label }}
                                            </option>
                                        </select>

                                        <TextInput
                                            type="text"
                                            class="mt-1 block w-full"
                                            v-model="Model.saksi_tempat_tgl_lahir[index]"
                                            placeholder="Tempat Tanggal Lahir"
                                        />

                                        <TextInput
                                            type="number"
                                            min="0"
                                            class="mt-1 block w-full"
                                            v-model="Model.saksi_umur[index]"
                                            placeholder="Umur"
                                        />

                                        <select
                                            class="mt-1 block w-full text-sm rounded-lg"
                                            v-model="Model.saksi_agama[index]"
                                            @change="Model.saksi_agama[index] = arrayAgama.find((item) => item.value === $event.target.value)['label']"
                                        >
                                            <option v-for="agama in arrayAgama" :key="agama.value" :value="agama.value">
                                                {{ agama.label }}
                                            </option>
                                        </select>

                                        <TextInput
                                            type="text"
                                            class="mt-1 block w-full"
                                            v-model="Model.saksi_pekerjaan[index]"
                                            placeholder="Pekerjaan"
                                        />
                                    </div>
                                        <TextInputArea
                                            type="text"
                                            class="mt-1 block w-full"
                                            v-model="Model.saksi_alamat[index]"
                                            placeholder="Alamat"
                                        />
                                    <danger-warning v-if="Model.saksi_nama.length > 1" class="absolute right-4 mt-1"
                                                    @click="removeSaksi(index)">
                                        <font-awesome-icon icon="fa-solid fa-trash" class="h-3"/>
                                    </danger-warning>

                                    <hr/> <br/>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <InputLabel value="Melanggar Pasal"/>
                                <TextInputArea
                                    type="text"
                                    rows="5"
                                    class="mt-1 block w-full"
                                    v-model="Model.melanggar_pasal"
                                    placeholder="Melanggar Pasal"
                                />
                        </div>


                        <div class="mt-4">
                            <!-- <InputLabel value="Barang Bukti"/> -->
                            <button @click="addBarangBukti()" class="w-full bg-blue-200 rounded hover:bg-gray-300">
                                <font-awesome-icon icon="fa-solid fa-plus" class="mx-1"/> Tambah Barang Bukti
                            </button>
                            <br/> <br/>
                            <div class="rounded border px-2 py-3">
                                <div class="grid grid-cols-1 gap-2">
                                    <InputLabel class="px-2 font-bold" value="Barang Bukti"/>
                                </div>
                                <div v-if="renderBarbuk" v-for="(barbuk, index) in Model.barang_bukti"
                                    class="grid grid-cols-1 gap-2"
                                    :class="Model.barang_bukti.length > 1 ? 'mr-10' : ''"
                                >
                                    <TextInputArea
                                        rows:="10"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="Model.barang_bukti[index]"
                                        placeholder="Barang Bukti"
                                    />

                                    <danger-warning v-if="Model.barang_bukti.length > 1" class="absolute right-4 mt-3"
                                                    @click="removeBarangBukti(index)">
                                        <font-awesome-icon icon="fa-solid fa-trash" class="h-4"/>
                                    </danger-warning>
                                    <hr/> <br/>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <InputLabel :value="`Kerugian: ${Model.kerugian ? numberFormat(Model.kerugian) : '-'}`"/>
                                <TextInput
                                    type="number"
                                    class="mt-1 block w-full"
                                    v-model="Model.kerugian"
                                    placeholder="Kerugian"
                                />
                        </div>

                        <div class="mt-4">
                            <InputLabel value="BA Serah Terima Perkara"/>
                                <TextInput
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="Model.ba_serah_terima"
                                    placeholder="BA Serah Terima Perkara"
                                />
                        </div>

                        <div class="mt-4">
                            <InputLabel value="Keterangan"/>
                            <TextInputArea
                                type="text"
                                rows="3"
                                class="mt-1 block w-full"
                                v-model="Model.keterangan"
                                placeholder="Keterangan"
                            />
                        </div>

                        <div class="mt-4">
                            <TextInput
                                type="hidden"
                                class="mt-1 block w-full"
                                v-model="Model.created_by"
                                placeholder="Created By"
                            />

                            <TextInput
                                type="hidden"
                                class="mt-1 block w-full"
                                v-model="Model.updated_by"
                                placeholder="Updated By"
                            />
                        </div>

                    </div>

                    <!-- Modal footer -->
                    <div class="px-4 py-2 flex justify-end border-t rounded-b bg-gray-100 ">
                        <primary-button @click="validation()">
                            Save
                        </primary-button>
                    </div>
                </div>
            </div>
        </Modal>

        <Modal :show="confirmDeleteData" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Apakah Anda yakin ingin menghapus data ini?
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Data yang sudah dihapus tidak dapat dikembalikan!
                </p>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal"> Cancel</SecondaryButton>

                    <DangerButton
                        class="ml-3"
                        :class="{ 'opacity-25': isLoading }"
                        :disabled="isLoading"
                        @click="DeleteFn"
                    >
                        Delete Data
                    </DangerButton>
                </div>
            </div>
        </Modal>

        <Modal :show="printModal.open" @close="printModal.open = false">
            <div class="relative">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow">
                    <button @click="printModal.open = false" type="button"
                            class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                    >
                        <font-awesome-icon icon="fa-solid fa-close"/>
                        <span class="sr-only">Close modal</span>
                    </button>
                    <!-- Modal header -->
                    <div class="px-6 py-4 border-b rounded-t bg-gray-100">
                        <h3 class="text-base font-semibold text-gray-900 lg:text-xl ">
                            {{ printModal.title }}
                        </h3>
                    </div>
                    <!-- Modal notification -->
                    <Notification :alerts="alerts"/>
                    <!-- Modal body -->
                    <div class="p-6">
                        <div class="mt-4">
                            <InputLabel value="Periode"/>
                            <select
                                class="bg-gray-50 capitalize border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                v-model="printModal.periode"
                            >
                                <option value="0">Per Hari</option>
                                <option value="1">Per Bulan</option>
                                <option value="2">Per Tahun</option>
                            </select>
                            <div class="w-full mt-4 grid gap-2"
                                 :class=" printModal.periode === '0' ? 'grid-cols-3' : (printModal.periode === '1' ? 'grid-cols-2' : 'grid-cols-1')">
                                <select
                                    v-if="printModal.periode === '0'"
                                    class="bg-gray-50 capitalize border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                    v-model="printModal.tgl"
                                >
                                    <option v-for="tng in 31" :key="tng" :value="tng">{{ tng }}</option>
                                </select>
                                <select
                                    v-if="printModal.periode !== '2'"
                                    class="bg-gray-50 capitalize border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                    v-model="printModal.bulan"
                                    @change="printModal.namaBulan = arrBulan.find((item) => item.value === $event.target.value)['label']"
                                >
                                    <option v-for="month in arrBulan" :key="month.value" :value="month.value">
                                        {{ month.label }}
                                    </option>
                                </select>
                                <text-input v-model="printModal.tahun" type="number"/>
                            </div>
                        </div>
                        <div class="mt-4">
                            <InputLabel value="Format"/>
                            <div class="relative w-full">
                                <select
                                    class="bg-gray-50 capitalize border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                    v-model="printModal.formatExport"
                                >
                                    <option value="xlsx">XLSX</option>
                                    <option value="xls">XLS</option>
                                    <option value="pdf">PDF</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div class="px-4 py-2 flex justify-end border-t rounded-b bg-gray-100 ">
                        <primary-button @click="printFn()">
                            Export
                        </primary-button>
                    </div>
                </div>
            </div>
        </Modal>

    </AuthenticatedLayout>
</template>
