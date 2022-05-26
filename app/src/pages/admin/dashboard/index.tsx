import Head from 'next/head';
import { useState } from 'react';
import AppLayout from '../../../components/Layouts/AppLayout';
import { useAuth, UserProps } from '../../../hooks/auth';

const Dashboard = () => {
    const { user } = useAuth({ middleware: 'auth' });
    const [isLoading, setIsLoading] = useState<boolean>(false);

    return (
        <AppLayout
            header={
                <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                    {!user ? 'Carregando...' : 'Dashboard'}
                </h2>
            }>
            <Head>
                <title>portal dev. - Usuários</title>
            </Head>
            {!user ? (
                <div className="flex justify-center items-center h-96 w-full">
                    <div className="w-16 h-16 border-4 border-blue-400 border-solid border-y-white rounded-full animate-spin"></div>
                </div>
            ) : (
                <>
                    <div className="py-12">
                        <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                            <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div className="p-6 bg-white border-b border-gray-200">
                                    {user.access_level > 0 ? 'Você esta logado.' : 'Aguarde seu cadastro ser aprovado.'}
                                </div>
                            </div>
                        </div>
                    </div>
                </>
            )}
        </AppLayout>
    );
};

export default Dashboard;
