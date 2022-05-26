import React from 'react';
import PropTypes from 'prop-types';

import TableDropdown from './DestinationTableDropdown';
import Button from '../Button';
import { useRouter } from 'next/router';
import { DestinyProps } from '../../pages/admin/dashboard/destinos';

export interface ContentProps {
    id: number;
    featured_image_id?: number;
    name: string;
    slug: string;
    country: string;
    country_region: string;
    state: string;
    city: string;
    description: string;
}

interface DestinationTableProps extends DestinyProps {
    color: string;
    setShowModal: (showModal: boolean) => void;
    setTourDestinationID: (tourDestinationID: string) => void;
}

export default function DestinationTable({
    color,
    data,
    meta,
    links,
    setShowModal,
    setTourDestinationID,
}: DestinationTableProps) {
    const route = useRouter();
    function handleAddDestination() {
        route.push('./destinos/novo-destino');
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
                                Destinos
                            </h3>
                        </div>
                        <Button onClick={handleAddDestination}>Adicionar Destino</Button>
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
                                    Cidade
                                </th>
                                <th
                                    className={
                                        'px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-center ' +
                                        (color === 'light'
                                            ? 'bg-blueGray-50 text-blueGray-500 border-blueGray-100'
                                            : 'bg-blueGray-600 text-blueGray-200 border-blueGray-500')
                                    }>
                                    Estado
                                </th>
                                <th
                                    className={
                                        'px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-center ' +
                                        (color === 'light'
                                            ? 'bg-blueGray-50 text-blueGray-500 border-blueGray-100'
                                            : 'bg-blueGray-600 text-blueGray-200 border-blueGray-500')
                                    }>
                                    Região
                                </th>
                                <th
                                    className={
                                        'px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-center ' +
                                        (color === 'light'
                                            ? 'bg-blueGray-50 text-blueGray-500 border-blueGray-100'
                                            : 'bg-blueGray-600 text-blueGray-200 border-blueGray-500')
                                    }>
                                    País
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
                            {data.map((item) => (
                                <tr key={item.id}>
                                    <th className="border-t-0 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-2 pl-4 text-center items-center">
                                        <img
                                            src="../../../images/logo.png"
                                            className="h-12 w-12 bg-white rounded-full border"
                                            alt="..."></img>
                                    </th>
                                    <th className="border-t-0 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-2 text-center">
                                        <span
                                            className={
                                                'ml-3 font-bold ' +
                                                +(color === 'light' ? 'text-blueGray-600' : 'text-white')
                                            }>
                                            {item.name}
                                        </span>
                                    </th>
                                    <td className="border-t-0 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap text-center p-4">
                                        {item.city}
                                    </td>
                                    <td className="border-t-0 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap text-center p-4">
                                        {item.state}
                                    </td>
                                    <td className="border-t-0 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap text-center p-4">
                                        {item.country_region}
                                    </td>
                                    <td className="border-t-0 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap text-center p-4">
                                        {item.country}
                                    </td>
                                    <td className="border-t-0 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-2 text-right">
                                        <TableDropdown
                                            destination_id={item.id.toString()}
                                            setShowModal={setShowModal}
                                            setTourDestinationID={setTourDestinationID}
                                        />
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>
        </>
    );
}

DestinationTable.defaultProps = {
    color: 'light',
};

DestinationTable.propTypes = {
    color: PropTypes.oneOf(['light', 'dark']),
};
