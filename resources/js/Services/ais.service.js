import axios from 'axios';

let API_URL = '/api/ais/';

class AisService {
    service(method, url, data) {
        return axios({
            url: url,
            method: method,
            data: data
        })
            .then(response => {
                return { success: true, data: response.data };
            })
            .catch(error => {
                return { success: false, data: error.response.data.message };
            });
    }

    serviceGet() {
        return this.service('get', API_URL);
    }
    serviceGetByMMSI(mmsi) {
        return this.service('get', API_URL +'/'+ mmsi);
    }
    servicePost(data) {
        return this.service('post', API_URL, data);
    }
}

export default new AisService();
