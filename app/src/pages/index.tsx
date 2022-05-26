import { useAuth } from '../hooks/auth';
import Head from 'next/head';
import { useRouter } from 'next/router';
import { useEffect } from 'react';

export default function Home() {
    const { user } = useAuth({ middleware: 'guest' });

    const route = useRouter();

    useEffect(() => {
        route.push('./admin/login');
    }, []);

    return (
        <>
            <Head>
                <title>portal dev.</title>
            </Head>
        </>
    );
}
