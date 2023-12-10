import axios from 'axios';

const axiosInstance = axios.create();
const apiHostname = 'http://telemedi-zadanie.localhost';

axiosInstance.interceptors.request.use(
    config => {
        if (config.url) {
            config.url = apiHostname + config.url;
        }

        return config;
    },
    error => Promise.reject(error)
);

axiosInstance.interceptors.response.use(
    response => {
        return response.data;

    },
    error => {
        return error.response;
    }
)

export default axiosInstance;
