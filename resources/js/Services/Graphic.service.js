import axios from 'axios';

let API_URL = '/api/graphic/';

class RoleService {
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

    serviceGiat(param) {
        return this.service('get', API_URL+'giat?data='+param);
    }
    serviceSampah(param) {
        return this.service('get', API_URL+'sampah?data='+param);
    }
    serviceKlinik(param) {
        return this.service('get', API_URL+'klinik?data='+param);
    }
    servicePerpustakaan(param) {
        return this.service('get', API_URL+'perpustakaan?data='+param);
    }
    serviceProgramPolda(param) {
        return this.service('get', API_URL+'program/' +
            'polda?data='+param);
    }
}

export default new RoleService();
