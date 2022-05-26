import useSWR from 'swr';
import { useEffect } from 'react';
import { useRouter } from 'next/router';
import axios from '../server/axios';

interface AuthProp {
    middleware?: string;
    redirectIfAuthenticated?: string;
}

interface RegisterProps {
    name: string;
    email: string;
    password: string;
    password_confirmation: string;
    setErrors(errors: any): void;
    setIsLoading(isLoading: boolean): void;
}

export interface UserProps {
    id: number;
    access_level: number;
    address1?: string;
    address2?: string;
    cell_phone?: string;
    city?: string;
    country?: string;
    cpf?: string;
    email: string;
    image_path?: string;
    name: string;
    neighborhood?: string;
    postcode?: string;
    state?: string;
}

export const useAuth = ({ middleware, redirectIfAuthenticated }: AuthProp) => {
    const router = useRouter();

    const {
        data: user,
        error,
        revalidate,
    } = useSWR<UserProps>('user', () =>
        axios
            .get('/api/user')
            .then((res) => res.data)
            .catch((error) => {
                if (error.response.status !== 409) throw error;
                router.push('/verify-email');
            })
    );

    const csrf = () => axios.get('/sanctum/csrf-cookie');

    const registerUser = async ({ setErrors, setIsLoading, ...props }: RegisterProps) => {
        await csrf();

        setErrors([]);
        setIsLoading(true);

        axios
            .post('/register', props)
            .then(() => revalidate())
            .catch((error) => {
                if (error.response.status !== 422) throw error;
                setIsLoading(false);
                setErrors(Object.values(error.response.data.errors).flat());
            });
    };

    const login = async ({ setErrors, setStatus, setIsLoading, ...props }) => {
        await csrf();

        setErrors([]);
        setStatus(null);
        axios
            .post('/login', props)
            .then(() => revalidate())
            .catch((error) => {
                if (error.response.status !== 422) throw error;
                setErrors(Object.values(error.response.data.errors).flat());
            })
            .finally(() => {
                setStatus(null);
                setIsLoading(false);
            });
    };

    const forgotPassword = async ({ setErrors, setStatus, email }) => {
        await csrf();

        setErrors([]);
        setStatus(null);

        axios
            .post('/forgot-password', { email })
            .then((response) => setStatus(response.data.status))
            .catch((error) => {
                if (error.response.status !== 422) throw error;

                setErrors(Object.values(error.response.data.errors).flat());
            });
    };

    const resetPassword = async ({ setErrors, setStatus, ...props }) => {
        await csrf();

        setErrors([]);
        setStatus(null);

        axios
            .post('/reset-password', { token: router.query.token, ...props })
            .then((response) => router.push('../login?reset=' + btoa(response.data.status)))
            .catch((error) => {
                if (error.response.status != 422) throw error;

                setErrors(Object.values(error.response.data.errors).flat());
            });
    };

    const resendEmailVerification = ({ setStatus }) => {
        axios.post('/email/verification-notification').then((response) => setStatus(response.data.status));
    };

    const logout = async () => {
        if (!error) {
            await axios.post('/logout');

            revalidate();
        }

        window.location.pathname = '/';
    };

    useEffect(() => {
        if (middleware === 'guest' && redirectIfAuthenticated && user) router.push(redirectIfAuthenticated);
        if (middleware === 'auth' && error) logout();
    }, [user, error]);

    return {
        user,
        registerUser,
        login,
        forgotPassword,
        resetPassword,
        resendEmailVerification,
        logout,
    };
};
