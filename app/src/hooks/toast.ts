import { toast } from 'react-toastify';

export const notifySuccess = (text: string, time: number) => {
    toast.success(text, {
        position: toast.POSITION.TOP_RIGHT,
        autoClose: time,
    });
};

export const notifyError = (text: string, time: number) => {
    toast.success(text, {
        position: toast.POSITION.TOP_RIGHT,
        autoClose: time,
        type: 'error',
    });
};

export const notifyInfo = (text: string, time: number) => {
    toast.success(text, {
        position: toast.POSITION.TOP_RIGHT,
        autoClose: time,
        type: 'info',
    });
};
