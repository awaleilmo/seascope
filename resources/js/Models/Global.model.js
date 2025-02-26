import GlobalService from "@/Services/Global.service.js";

class GlobalModel {
    constructor() {
        this.id = null;
        this.id_no_lp = '';
        this.tanggal_lp = null;
        this.hasil_tangkapan = '';
        this.jenis_kasus = '';
        this.kronologi = '';
        this.tersangka_nama = '';
        this.tersangka_nik = '';
        this.tersangka_warganegara = '';
        this.tersangka_suku = '';
        this.tersangka_jk = '';
        this.tersangka_tempat_tgl_lahir = '';
        this.tersangka_umur = '';
        this.tersangka_agama = '';
        this.tersangka_pekerjaan = '';
        this.tersangka_alamat = '';
        this.korban = '';
        this.saksi_nama = '';
        this.saksi_nik = '';
        this.saksi_warganegara = '';
        this.saksi_suku = '';
        this.saksi_jk = '';
        this.saksi_tempat_tgl_lahir = '';
        this.saksi_umur = '';
        this.saksi_agama = '';
        this.saksi_pekerjaan = '';
        this.saksi_alamat = '';
        this.melanggar_pasal = '';
        this.barang_bukti = '';
        this.kerugian = '';
        this.ba_serah_terima = null;
        this.created_by = '';
        this.updated_by = '';
    }
    createUpdate(data) {
        return GlobalService.serviceCreateUpdate(data);
    }
    getAll() {
        return GlobalService.serviceGetAll();
    }
    getById(id) {
        return GlobalService.serviceGetById(id);
    }
    getPage(param) {
        return GlobalService.servicePage(param);
    }
    destroy(id) {
        return GlobalService.serviceDestroy(id);
    }

}

export default new GlobalModel();
