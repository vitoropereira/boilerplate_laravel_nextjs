import Head from 'next/head';
import AppLayout from '../../../../components/Layouts/AppLayout';
import DestinationTable from '../../../../components/DestinationTable';
import { GetStaticProps } from 'next';
import axios from '../../../../server/axios';
import { useAuth } from '../../../../hooks/auth';
import { useState } from 'react';
import AlertModal from '../../../../components/AlertModal';
import { TourDestinations } from '../../../../utils/tourDestinations';

export interface DestinyProps {
    data: DataProps[];
    links: Links;
    meta: Meta;
}

export interface DataProps {
    images: any[];
    products: any;
    id: number;
    featured_image_id: any;
    country: string;
    country_region: string;
    state: string;
    city: string;
    name: string;
    slug: string;
    description: string;
    product: any[];
}

export interface Links {
    first: string;
    last: string;
    prev: any;
    next: any;
}

export interface Meta {
    current_page: number;
    from: number;
    last_page: number;
    per_page: number;
    to: number;
    total: number;
}

const Destiny = ({ data, meta, links }: DestinyProps) => {
    const { user } = useAuth({
        middleware: 'auth',
    });
    const [showModal, setShowModal] = useState(false);
    const [tourDestinationID, setTourDestinationID] = useState('');

    const { deleteTourDestination } = TourDestinations();

    async function handleDelete(id: number) {
        try {
            await deleteTourDestination(id);
            setShowModal(false);
        } catch (error) {
            console.log(error);
        }
    }

    return (
        <>
            <AppLayout
                header={
                    <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                        {!user ? 'Carregando...' : 'Meus dados'}
                    </h2>
                }>
                <Head>
                    <title>portal dev. - Destinos</title>
                </Head>

                <div className="py-12">
                    <DestinationTable
                        color="light"
                        data={data}
                        meta={meta}
                        links={links}
                        setShowModal={setShowModal}
                        setTourDestinationID={setTourDestinationID}
                    />
                </div>
            </AppLayout>
            <AlertModal
                modalAlertIsOpen={showModal}
                setShowModal={setShowModal}
                handleModal={handleDelete}
                handleModalID={tourDestinationID}
                modalType="DELETE"
            />
        </>
    );
};

export const getStaticProps: GetStaticProps = async () => {
    const res = await axios.get('/api/tour_destinations');
    const { data } = res;

    const meta = data.meta;
    const links = data.meta;
    const response = data.data;

    return {
        props: {
            data: response,
            meta,
            links,
        },
    };
};

export default Destiny;
