import React from 'react';
import PropTypes from 'prop-types';

import ProductDropdown from './ProductTableDropdown';
import Button from '../Button';
import { useRouter } from 'next/router';
import { DataProps, MetaProps } from '../../pages/admin/dashboard/produtos';

interface ProductTableProps {
    color: string;
    data?: DataProps[];
    meta?: MetaProps;
    setShowModal?: (showModal: boolean) => void;
    setProductID?: (productID: string) => void;
}

export interface Data {
    id: number;
    featured_image_id?: any;
    country: string;
    country_region: string;
    state: string;
    city: string;
    name: string;
    slug: string;
    description: string;
}

export default function ProductTable({ color, data, meta, setShowModal, setProductID }: ProductTableProps) {
    const route = useRouter();

    function handleAddDestination() {
        route.push('./produtos/novo-produto');
    }
    return (
        <>
            <div
                className={
                    'relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded ' +
                    (color === 'light' ? 'bg-white' : 'bg-blueGray-700 text-white')
                }>
                <div className="rounded-t mb-0 px-4 py-3 border-0">
                    <div className="flex flex-wrap items-center">
                        <div className="relative w-full px-4 max-w-full flex-grow flex-1">
                            <h3
                                className={
                                    'font-semibold text-lg ' + (color === 'light' ? 'text-blueGray-700' : 'text-white')
                                }>
                                Produtos
                            </h3>
                        </div>
                        <Button onClick={handleAddDestination}>Adicionar Produto</Button>
                    </div>
                </div>
                <div className="block w-full overflow-x-auto">
                    {/* Projects table */}
                    <table className="items-center w-full bg-transparent border-collapse">
                        <thead>
                            <tr>
                                <th
                                    className={
                                        'px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-center ' +
                                        (color === 'light'
                                            ? 'bg-blueGray-50 text-blueGray-500 border-blueGray-100'
                                            : 'bg-blueGray-600 text-blueGray-200 border-blueGray-500')
                                    }>
                                    Imagens
                                </th>
                                <th
                                    className={
                                        'px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-center ' +
                                        (color === 'light'
                                            ? 'bg-blueGray-50 text-blueGray-500 border-blueGray-100'
                                            : 'bg-blueGray-600 text-blueGray-200 border-blueGray-500')
                                    }>
                                    Nome
                                </th>
                                <th
                                    className={
                                        'px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-center ' +
                                        (color === 'light'
                                            ? 'bg-blueGray-50 text-blueGray-500 border-blueGray-100'
                                            : 'bg-blueGray-600 text-blueGray-200 border-blueGray-500')
                                    }>
                                    Local
                                </th>
                                <th
                                    className={
                                        'px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-center ' +
                                        (color === 'light'
                                            ? 'bg-blueGray-50 text-blueGray-500 border-blueGray-100'
                                            : 'bg-blueGray-600 text-blueGray-200 border-blueGray-500')
                                    }>
                                    Disponível
                                </th>
                                <th
                                    className={
                                        'px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-center ' +
                                        (color === 'light'
                                            ? 'bg-blueGray-50 text-blueGray-500 border-blueGray-100'
                                            : 'bg-blueGray-600 text-blueGray-200 border-blueGray-500')
                                    }>
                                    Descrição do Preço
                                </th>
                                <th
                                    className={
                                        'px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-center ' +
                                        (color === 'light'
                                            ? 'bg-blueGray-50 text-blueGray-500 border-blueGray-100'
                                            : 'bg-blueGray-600 text-blueGray-200 border-blueGray-500')
                                    }>
                                    Custo
                                </th>
                                <th
                                    className={
                                        'px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-center ' +
                                        (color === 'light'
                                            ? 'bg-blueGray-50 text-blueGray-500 border-blueGray-100'
                                            : 'bg-blueGray-600 text-blueGray-200 border-blueGray-500')
                                    }>
                                    Venda
                                </th>
                                <th
                                    className={
                                        'px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-right ' +
                                        (color === 'light'
                                            ? 'bg-blueGray-50 text-blueGray-500 border-blueGray-100'
                                            : 'bg-blueGray-600 text-blueGray-200 border-blueGray-500')
                                    }></th>
                            </tr>
                        </thead>
                        <tbody>
                            {data.map((item) => {
                                return (
                                    <tr key={item.id}>
                                        <td className="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-center flex items-center">
                                            <img
                                                src="../../../images/logo.png"
                                                className="h-12 w-12 bg-white rounded-full border"
                                                alt="..."
                                            />
                                        </td>
                                        <td className="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-center">
                                            <span
                                                className={
                                                    'ml-3 font-bold ' +
                                                    +(color === 'light' ? 'text-blueGray-600' : 'text-white')
                                                }>
                                                {item.name}
                                            </span>
                                        </td>
                                        <td className="border-t-0 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-center">
                                            {item.tour_destination &&
                                                `${item.tour_destination.name} - ${item.tour_destination.city}`}
                                        </td>
                                        <td className="border-t-0 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-center">
                                            {item.available ? 'Sim' : 'Não'}
                                        </td>
                                        <td className="border-t-0 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"></td>
                                        <td className="border-t-0 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"></td>
                                        <td className="border-t-0 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"></td>
                                        <td className="border-t-0 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-right">
                                            <ProductDropdown
                                                product_id={item.id.toString()}
                                                setShowModal={setShowModal}
                                                setProductID={setProductID}
                                            />
                                        </td>
                                    </tr>
                                );
                            })}
                        </tbody>
                    </table>
                </div>
            </div>
        </>
    );
}

ProductTable.defaultProps = {
    color: 'light',
};

ProductTable.propTypes = {
    color: PropTypes.oneOf(['light', 'dark']),
};
