import polisiRwService from "@/Services/PolisiRw.service.js";

class PolisiRwModel {
    constructor() {
        this.id = null;
        this.polda_name = '';
        this.polda_id = null;
        this.personil_jml = [];
        this.personil_sat = [];
        this.sambang = '';
        this.pemecahaan = '';
        this.informasi = '';
        this.penanganan = '';
        this.keterangan = '';
        this.foto = null;
        this.foto_original = null;
        this.tanggal = '';
        this.waktu = '';
    }
    createUpdate(data) {
        return polisiRwService.serviceCreateUpdate(data);
    }
    getAll() {
        return polisiRwService.serviceGetAll();
    }
    getById(id) {
        return polisiRwService.serviceGetById(id);
    }
    getPage(param) {
        return polisiRwService.servicePage(param);
    }
    destroy(id) {
        return polisiRwService.serviceDestroy(id);
    }
    getFoto(id) {
        return polisiRwService.serviceGetFoto(id);
    }
    sapuDownload(params) {
        return polisiRwService.serviceDownload(params);
    }
    postPhoto(data) {
        return polisiRwService.servicePostPhoto(data);
    }
    deletePhoto(id, urutan) {
        return polisiRwService.serviceDeletePhoto(id, urutan);
    }

}

export default new PolisiRwModel();
