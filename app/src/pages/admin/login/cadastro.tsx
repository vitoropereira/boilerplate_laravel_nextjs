import Link from 'next/link';
import { useState } from 'react';

import ApplicationLogo from '../../../components/ApplicationLogo';
import AuthCard from '../../../components/AuthCard';
import AuthValidationErrors from '../../../components/AuthValidationErrors';
import Button from '../../../components/Button';
import GuestLayout from '../../../components/Layouts/GuestLayout';
import Input from '../../../components/Input';
import Label from '../../../components/Label';

import { useAuth } from '../../../hooks/auth';
import { SubmitHandler, useForm } from 'react-hook-form';
import { notifyError, notifySuccess } from '../../../hooks/toast';

interface RegisterFormDataProps {
    name: string;
    email: string;
    password: string;
    password_confirmation: string;
}

const Register = () => {
    const { registerUser } = useAuth({
        middleware: 'guest',
        redirectIfAuthenticated: '/admin/dashboard',
    });

    const { register, handleSubmit } = useForm<RegisterFormDataProps>();

    const [errors, setErrors] = useState([]);
    const [isLoading, setIsLoading] = useState(false);

    const onSubmit: SubmitHandler<RegisterFormDataProps> = (data) => {
        try {
            setIsLoading(true);

            registerUser({
                name: data.name,
                email: data.email,
                password: data.password,
                password_confirmation: data.password_confirmation,
                setErrors,
                setIsLoading,
            });

            notifySuccess('Cadastro realizado com sucesso!', 4000);
        } catch (error) {
            notifyError('Erro ao tentar realizar o cadastro. Favor tentar mais tarde.', 4000);
            console.log(error);
            setIsLoading(false);
        }
    };

    const submitForm = (event) => {
        event.preventDefault();
        try {
            setIsLoading(true);
        } catch (error) {}
    };

    return (
        <GuestLayout>
            <AuthCard
                logo={
                    <Link href="/">
                        <a>
                            {/* <ApplicationLogo
                                className="fill-current text-gray-500"
                                width={120}
                                height={120}
                            /> */}
                        </a>
                    </Link>
                }>
                {/* Validation Errors */}
                <AuthValidationErrors className="mb-4" errors={errors} />

                <form onSubmit={handleSubmit(onSubmit)}>
                    {/* Name */}
                    <div>
                        <Label htmlFor="name">Name</Label>

                        <Input
                            name="name"
                            type="text"
                            className="block mt-1 w-full"
                            required
                            autoFocus
                            register={register}
                        />
                    </div>

                    {/* Email Address */}
                    <div className="mt-4">
                        <Label htmlFor="email">Email</Label>

                        <Input name="email" type="email" register={register} className="block mt-1 w-full" required />
                    </div>

                    {/* Password */}
                    <div className="mt-4">
                        <Label htmlFor="password">Password</Label>

                        <Input
                            name="password"
                            type="password"
                            register={register}
                            className="block mt-1 w-full"
                            required
                            autoComplete="new-password"
                        />
                    </div>

                    {/* Confirm Password */}
                    <div className="mt-4">
                        <Label htmlFor="password_confirmation">Confirm Password</Label>

                        <Input
                            name="password_confirmation"
                            type="password"
                            register={register}
                            className="block mt-1 w-full"
                            required
                        />
                    </div>

                    <div className="flex items-center justify-end mt-4">
                        <Link href="/login">
                            <a className="underline text-sm text-gray-600 hover:text-gray-900">Already registered?</a>
                        </Link>

                        <Button className="ml-4">Register</Button>
                    </div>
                </form>
                {isLoading && (
                    <div>
                        <div className="w-8 h-8 border-4 border-blue-400 border-solid border-y-white rounded-full animate-spin" />
                    </div>
                )}
            </AuthCard>
        </GuestLayout>
    );
};

export default Register;
