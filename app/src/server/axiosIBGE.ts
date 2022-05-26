import Axios from 'axios';

const axiosIBGE = Axios.create({
    baseURL: 'https://servicodados.ibge.gov.br/api/v1/',
    headers: {
        'Content-Type': 'application/json',
    },
});

export default axiosIBGE;
