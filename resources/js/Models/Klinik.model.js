import klinikService from "@/Services/Klinik.service.js";

class KlinikModel {
    constructor() {
        this.id = null;
        this.polda_name = '';
        this.polda_id = null;
        this.personil_jml = [];
        this.personil_sat = [];
        this.lokasi = '';
        this.jml_peserta = '';
        this.keluhan_peserta = '';
        this.obat = '';
        this.keterangan = '';
        this.foto = null;
        this.foto_original = null;
        this.tanggal = '';
        this.waktu = '';
    }
    createUpdate(data) {
        return klinikService.serviceCreateUpdate(data);
    }
    getAll() {
        return klinikService.serviceGetAll();
    }
    getById(id) {
        return klinikService.serviceGetById(id);
    }
    getPage(param) {
        return klinikService.servicePage(param);
    }
    destroy(id) {
        return klinikService.serviceDestroy(id);
    }
    getFoto(id) {
        return klinikService.serviceGetFoto(id);
    }
    sapuDownload(params) {
        return klinikService.serviceDownload(params);
    }
    postPhoto(data) {
        return klinikService.servicePostPhoto(data);
    }
    deletePhoto(id, urutan) {
        return klinikService.serviceDeletePhoto(id, urutan);
    }
}

export default new KlinikModel();
