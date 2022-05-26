import ApplicationLogo from '../../../components/ApplicationLogo';
import AuthCard from '../../../components/AuthCard';
import AuthSessionStatus from '../../../components/AuthSessionStatus';
import AuthValidationErrors from '../../../components/AuthValidationErrors';
import Button from '../../../components/Button';
import GuestLayout from '../../../components/Layouts/GuestLayout';
import Input from '../../../components/Input';
import Label from '../../../components/Label';
import Link from 'next/link';
import { useAuth } from '../../../hooks/auth';
import { useEffect, useState } from 'react';
import { useRouter } from 'next/router';

const ForgotPassword = () => {
    const { forgotPassword } = useAuth({ middleware: 'guest' });

    const route = useRouter();

    const [email, setEmail] = useState('');
    const [errors, setErrors] = useState([]);
    const [status, setStatus] = useState(null);
    const [isLoading, setIsLoading] = useState(false);

    function thereIsAnError() {
        if (errors.length > 0) {
            setIsLoading(false);
        }
    }

    function thereIsAnStatus() {
        if (!!status) {
            setIsLoading(false);
        }
    }

    useEffect(() => {
        thereIsAnError();
    }, [errors]);

    useEffect(() => {
        thereIsAnStatus();
    }, [status]);

    const submitForm = async (event) => {
        event.preventDefault();
        setIsLoading(true);

        const response = await forgotPassword({ email, setErrors, setStatus });
        // setTimeout("route.push('/admin/login')", 3000)
    };

    return (
        <GuestLayout>
            <AuthCard
                logo={
                    <Link href="/">
                        <a>{/* <ApplicationLogo className="w-20 h-20 fill-current text-gray-500" /> */}</a>
                    </Link>
                }>
                <div className="mb-4 text-sm text-gray-600">
                    Esqueceu sua senha? Se problemas. Apenas insira seu e-mail cadastrado e nós iremos enviar um e-mail
                    com um link para reiniciar sua Senha.
                </div>

                {/* Session Status */}
                <AuthSessionStatus className="mb-4" status={status} />

                {/* Validation Errors */}
                <AuthValidationErrors className="mb-4" errors={errors} />

                <form onSubmit={submitForm}>
                    {/* Email Address */}
                    <div>
                        <Label htmlFor="email">Email</Label>
                        <Input
                            id="email"
                            type="email"
                            name="email"
                            value={email}
                            className="block mt-1 w-full"
                            onChange={(event) => setEmail(event.target.value)}
                            required
                            autoFocus
                        />
                    </div>

                    <div className="flex items-center justify-end mt-4">
                        <Button>E-mail para reiniciar a senha</Button>
                    </div>
                </form>
                {isLoading && (
                    <div>
                        <div className="w-8 h-8 border-4 border-blue-400 border-solid border-y-white rounded-full animate-spin"></div>
                    </div>
                )}
            </AuthCard>
        </GuestLayout>
    );
};

export default ForgotPassword;
