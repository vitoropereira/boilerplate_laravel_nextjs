import Cookies from 'js-cookie';

export const onRequest = (config) => {
    if (
        config.method == 'post' ||
        config.method == 'put' ||
        (config.method == 'delete' && !Cookies.get('XSRF-TOKEN'))
    ) {
        return setCSRFToken().then((response) => config);
    }

    return config;
};

const setCSRFToken = () => {
    return fetch('/sanctum/csrf-cookie', {
        method: 'GET',
    });
};
