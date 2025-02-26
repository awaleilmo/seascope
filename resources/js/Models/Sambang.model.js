import sambangService from "@/Services/Sambang.service.js";

class SambangModel {
    constructor() {
        this.id = null;
        this.polda_name = '';
        this.polda_id = null;
        this.personil_jml = [];
        this.personil_sat = [];
        this.lokasi = '';
        this.jarak = 0;
        this.uraian = '';
        this.jml_peserta = 0;
        this.keterangan = '';
        this.tipe = 0;
        this.foto = null;
        this.foto_original = null;
        this.tanggal = '';
        this.waktu = '';
    }
    createUpdate(data) {
        return sambangService.serviceCreateUpdate(data);
    }
    getAll() {
        return sambangService.serviceGetAll();
    }
    getById(id) {
        return sambangService.serviceGetById(id);
    }
    getBinaanPage(param) {
        return sambangService.serviceBinaanPage(param);
    }
    getSentuhanPage(param){
        return sambangService.serviceSentuhanPage(param);
    }
    getPantauanPage(param){
        return sambangService.servicePantauanPage(param);
    }
    destroy(id) {
        return sambangService.serviceDestroy(id);
    }
    getFoto(id) {
        return sambangService.serviceGetFoto(id);
    }
    sapuDownload(params) {
        return sambangService.serviceDownload(params);
    }
    postPhoto(data) {
        return sambangService.servicePostPhoto(data);
    }
    deletePhoto(id, urutan) {
        return sambangService.serviceDeletePhoto(id, urutan);
    }

}

export default new SambangModel();
