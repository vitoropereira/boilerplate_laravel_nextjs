import { useRef, useState } from 'react';
import { FaEllipsisV, FaTrashAlt, FaEdit } from 'react-icons/fa';
import { createPopper } from '@popperjs/core';
import Link from 'next/link';

interface DestinationTableDropdownProps {
    destination_id: string;
    setShowModal: (showModal: boolean) => void;
    setTourDestinationID: (tourDestinationID: string) => void;
}

const DestinationTableDropdown = ({
    destination_id,
    setShowModal,
    setTourDestinationID,
}: DestinationTableDropdownProps) => {
    const [dropdownPopoverShow, setDropdownPopoverShow] = useState(false);

    const btnDropdownRef = useRef(null);
    const popoverDropdownRef = useRef(null);

    const openDropdownPopover = () => {
        createPopper(btnDropdownRef.current, popoverDropdownRef.current, {
            placement: 'left-start',
        });
        setDropdownPopoverShow(true);
    };

    const closeDropdownPopover = () => {
        setDropdownPopoverShow(false);
    };

    return (
        <>
            <a
                className="text-blueGray-500 py-1 px-3 cursor-pointer"
                ref={btnDropdownRef}
                onClick={(e) => {
                    e.preventDefault();
                    dropdownPopoverShow ? closeDropdownPopover() : openDropdownPopover();
                }}>
                <FaEllipsisV />
            </a>
            <div
                ref={popoverDropdownRef}
                className={
                    (dropdownPopoverShow ? 'block ' : 'hidden ') +
                    'bg-white text-base z-50 float-left py-2 list-none text-left rounded shadow-lg min-w-48 w-1/12'
                }>
                <Link href={`./destinos/editar-destino/${destination_id}`}>
                    <a
                        href={`./destinos/editar-destino/${destination_id}`}
                        className={
                            'text-sm py-2 px-4 font-normal block w-full whitespace-nowrap bg-transparent text-blueGray-700'
                        }>
                        <span className="flex justify-around">
                            Editar
                            <FaEdit />
                        </span>
                    </a>
                </Link>
                <button
                    type="button"
                    className={
                        'text-sm py-2 px-4 font-normal block w-full whitespace-nowrap bg-transparent text-blueGray-700 text-rose-700'
                    }
                    onClick={() => {
                        setShowModal(true);
                        setTourDestinationID(destination_id);
                    }}>
                    <span className="flex justify-around">
                        {' '}
                        Excluir <FaTrashAlt />
                    </span>
                </button>
            </div>
        </>
    );
};

export default DestinationTableDropdown;
