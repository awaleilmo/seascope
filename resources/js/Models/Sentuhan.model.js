import sambangService from "@/Services/Sambang.service.js";

class SentuhanModel {
    getPage(param) {
        return sambangService.serviceSentuhanPage(param);
    }
}

export default new SentuhanModel();
