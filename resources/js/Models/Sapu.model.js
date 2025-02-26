import sapuService from "@/Services/Sapu.service.js";

class SapuModel {
    constructor() {
        this.id = null;
        this.polda_name = '';
        this.polda_id = null;
        this.personil_ket = [];
        this.personil_jml = [];
        this.personil_sat = [];
        this.lokasi = '';
        this.sampah_organik_jml = 0;
        this.sampah_organik_ket = '';
        this.sampah_anorganik_jml = 0;
        this.sampah_anorganik_ket = '';
        this.keterangan = '';
        this.foto = null;
        this.foto_original = null;
        this.tanggal = '';
        this.waktu = '';
    }
    createUpdate(data) {
        return sapuService.serviceCreateUpdate(data);
    }
    getAll() {
        return sapuService.serviceGetAll();
    }
    getById(id) {
        return sapuService.serviceGetById(id);
    }
    getPage(param) {
        return sapuService.servicePage(param);
    }
    destroy(id) {
        return sapuService.serviceDestroy(id);
    }
    getFoto(id) {
        return sapuService.serviceGetFoto(id);
    }
    sapuDownload(params) {
        return sapuService.serviceDownload(params);
    }
    postPhoto(data) {
        return sapuService.servicePostPhoto(data);
    }
    deletePhoto(id, urutan) {
        return sapuService.serviceDeletePhoto(id, urutan);
    }

}

export default new SapuModel();
