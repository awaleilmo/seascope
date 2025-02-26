import kiaService from "@/Services/Kia.service.js";

class KiaModel {
    constructor() {
        this.id = null
        this.no_lp = null
        this.tanggal_lp = null
        this.hasil_tangkapan = null
        this.jenis_kasus = null
        this.kronologis = null

        this.tersangka_nama = [""]
        this.tersangka_nik = [""]
        this.tersangka_warganegara = [""]
        this.tersangka_suku = [""]
        this.tersangka_jk = [""]
        this.tersangka_tempat_tgl_lahir = [""]
        this.tersangka_umur = [""]
        this.tersangka_agama = [""]
        this.tersangka_pekerjaan = [""]
        this.tersangka_alamat = [""]

        this.korban = null

        this.saksi_nama = null
        this.saksi_nik = null
        this.saksi_warganegara = null
        this.saksi_suku = null
        this.saksi_jk = null
        this.saksi_tempat_tgl_lahir = null
        this.saksi_umur = null
        this.saksi_agama = null
        this.saksi_pekerjaan = null
        this.saksi_alamat = null

        this.melanggar_pasal = null
        this.barang_bukti = null
        this.kerugian = null
        this.penyidik = null
        this.penanganan_perkara = null
        this.keterangan = null
    }
    createUpdate(data) {
        return kiaService.serviceCreateUpdate(data);
    }
    getAll() {
        return kiaService.serviceGetAll();
    }
    getById(id) {
        return kiaService.serviceGetById(id);
    }
    getPage(param) {
        return kiaService.servicePage(param);
    }
    destroy(id) {
        return kiaService.serviceDestroy(id);
    }

}

export default new KiaModel();
