import sambangService from "@/Services/Sambang.service.js";

class PantauanModel {
    getPage(param) {
        return sambangService.servicePantauanPage(param);
    }
}

export default new PantauanModel();
