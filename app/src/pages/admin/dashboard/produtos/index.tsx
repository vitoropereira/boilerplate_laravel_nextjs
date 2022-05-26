import Head from 'next/head';
import AppLayout from '../../../../components/Layouts/AppLayout';
import { GetStaticProps } from 'next';
import axios from '../../../../server/axios';
import ProductTable from '../../../../components/ProductTable';
import { TourDestinationProps } from '../../../../utils/tourDestinations';
import AlertModal from '../../../../components/AlertModal';
import { useState } from 'react';
import { Products } from '../../../../utils/products';

export interface Link {
    url: string;
    label: string;
    active: boolean;
}

export interface MetaProps {
    current_page: number;
    from: number;
    last_page: number;
    links: Link[];
    path: string;
    per_page: number;
    to: number;
    total: number;
}

export interface DataProps {
    id: number;
    featured_image_id?: any;
    tour_destination: TourDestinationProps;
    images: any[];
    name: string;
    slug: string;
    description: string;
    available: boolean;
    meta_title: string;
    meta_description: string;
}

interface Props {
    data: DataProps[];
    meta: MetaProps;
}

const Product = ({ data, meta }: Props) => {
    const [showModal, setShowModal] = useState(false);
    const [productID, setProductID] = useState('');

    const { deleteProduct } = Products();

    async function handleDelete(id: number) {
        try {
            await deleteProduct(id);
            setShowModal(false);
        } catch (error) {
            console.log(error);
        }
    }

    return (
        <>
            <AppLayout header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Todos Produtos</h2>}>
                <Head>
                    <title>portal dev. - Produtos</title>
                </Head>

                <div className="py-12">
                    <ProductTable
                        color="light"
                        data={data}
                        meta={meta}
                        // links={links}
                        setShowModal={setShowModal}
                        setProductID={setProductID}
                    />
                </div>
            </AppLayout>
            <AlertModal
                modalAlertIsOpen={showModal}
                setShowModal={setShowModal}
                handleModal={handleDelete}
                handleModalID={productID}
                modalType="DELETE"
            />
        </>
    );
};

export const getStaticProps: GetStaticProps = async () => {
    const res = await axios.get('/api/products');
    const { data } = res;
    const meta = data.meta;
    const response = data.data;

    return {
        props: {
            data: response,
            meta,
        },
    };
};

export default Product;
