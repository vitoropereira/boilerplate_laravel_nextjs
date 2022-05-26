import { useEffect, useState } from 'react';
import { SubmitHandler, useForm } from 'react-hook-form';

import { useAuth } from '../../../hooks/auth';
import ApplicationLogo from '../../../components/ApplicationLogo';
import AuthCard from '../../../components/AuthCard';
import AuthSessionStatus from '../../../components/AuthSessionStatus';
import AuthValidationErrors from '../../../components/AuthValidationErrors';
import Button from '../../../components/Button';
import GuestLayout from '../../../components/Layouts/GuestLayout';
import Input from '../../../components/Input';
import Label from '../../../components/Label';
import Link from 'next/link';

interface LoginFormDataProps {
    email: string;
    password: string;
}

const Login = () => {
    const [apiError, setApiError] = useState([]);
    const [status, setStatus] = useState('');
    const [isLoading, setIsLoading] = useState(false);

    const { register, handleSubmit } = useForm<LoginFormDataProps>();

    const onSubmit: SubmitHandler<LoginFormDataProps> = (data) => {
        try {
            setIsLoading(true);
            setStatus('');

            login({
                email: data.email,
                password: data.password,
                setErrors: setApiError,
                setStatus,
                setIsLoading,
            });
        } catch (error) {
            console.log(error);
            setIsLoading(false);
        }
    };

    const { login } = useAuth({
        middleware: 'guest',
        redirectIfAuthenticated: './dashboard',
    });

    function thereIsAnError() {
        if (apiError.length > 0) {
            setIsLoading(false);
        }
    }

    useEffect(() => {
        thereIsAnError();
    }, [apiError]);

    return (
        <GuestLayout>
            <AuthCard
                logo={
                    <Link href="/">
                        <a>{/* <ApplicationLogo className="w-20 h-20 fill-current text-gray-500" /> */}</a>
                    </Link>
                }>
                {/* Session Status */}
                <AuthSessionStatus className="mb-4" status={status} />

                {/* Validation Errors */}
                <AuthValidationErrors className="mb-4" errors={apiError} />

                <form onSubmit={handleSubmit(onSubmit)}>
                    <Label htmlFor="email">Email</Label>

                    <Input
                        name="email"
                        type="text"
                        className="block mt-1 w-full"
                        placeholder="Digite seu e-mail cadastrado."
                        autoFocus
                        register={register}
                    />
                    <Label className="mt-4" htmlFor="password">
                        Senha
                    </Label>

                    <Input
                        name="password"
                        type="password"
                        className="block mt-1 w-full"
                        placeholder="Digite sua senha."
                        register={register}
                    />

                    {/* Remember Me */}
                    <div className="block mt-4">
                        <label htmlFor="remember_me" className="inline-flex items-center">
                            <input
                                id="remember_me"
                                type="checkbox"
                                name="remember"
                                className="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            />

                            <span className="ml-2 text-sm text-gray-600">Lembrar de mim</span>
                        </label>
                    </div>
                    <Button className="ml-3 mt-1">Entrar</Button>
                </form>

                <div className="flex items-center justify-end mt-4">
                    <Link href="/admin/login/cadastro">
                        <a className="underline text-sm text-gray-600 hover:text-gray-900">Cadastrar</a>
                    </Link>
                    <div className="flex items-center mx-6 my-3 sm:my-0 ">
                        <Link href="/admin/login/forgot-password">
                            <a className="underline text-sm text-gray-600 hover:text-gray-900">Esqueceu sua senha?</a>
                        </Link>
                    </div>
                </div>

                {isLoading && (
                    <div>
                        <div className="w-8 h-8 border-4 border-blue-400 border-solid border-y-white rounded-full animate-spin"></div>
                    </div>
                )}
            </AuthCard>
        </GuestLayout>
    );
};

export default Login;
