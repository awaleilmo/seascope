import axios from 'axios';

const Api = '/api/map/'
class MapService {
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

    serviceInflow(baseRun, Dtime, depth) {
        return this.service('get', Api+'inflow/'+baseRun+'/'+Dtime+'/'+depth);
    }
    serviceInaWaves(baseRun, Dtime) {
        return this.service('get', Api+'inaWaves/'+baseRun+'/'+Dtime);
    }
    serviceGempa() {
        return this.service('get', Api+'gempa');
    }
}

export default new MapService();
