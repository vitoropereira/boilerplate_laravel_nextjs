interface AlertModalProps {
    modalType: 'DELETE' | 'ALERT';
    modalAlertIsOpen: boolean;
    setShowModal: (showModal: boolean) => void;
    handleModal: (id: number) => void;
    handleModalID?: string;
}

const AlertModal = ({ modalAlertIsOpen, setShowModal, handleModal, modalType, handleModalID }: AlertModalProps) => {
    return (
        <>
            <div
                className={`${modalAlertIsOpen ? 'flex' : 'hidden'} bg-slate-800 bg-opacity-50 
                justify-center items-center absolute top-0 right-0 bottom-0 left-0`}>
                <div className="bg-white px-16 py-14 rounded-md text-center">
                    {modalType === 'DELETE' ? (
                        <svg
                            width="40"
                            height="40"
                            className="mt-4 w-12 h-12 m-auto text-gray-400 dark:text-gray-200"
                            fill="currentColor"
                            viewBox="0 0 1792 1792"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M704 1376v-704q0-14-9-23t-23-9h-64q-14 0-23 9t-9 23v704q0 14 9 23t23 9h64q14 0 23-9t9-23zm256 0v-704q0-14-9-23t-23-9h-64q-14 0-23 9t-9 23v704q0 14 9 23t23 9h64q14 0 23-9t9-23zm256 0v-704q0-14-9-23t-23-9h-64q-14 0-23 9t-9 23v704q0 14 9 23t23 9h64q14 0 23-9t9-23zm-544-992h448l-48-117q-7-9-17-11h-317q-10 2-17 11zm928 32v64q0 14-9 23t-23 9h-96v948q0 83-47 143.5t-113 60.5h-832q-66 0-113-58.5t-47-141.5v-952h-96q-14 0-23-9t-9-23v-64q0-14 9-23t23-9h309l70-167q15-37 54-63t79-26h320q40 0 79 26t54 63l70 167h309q14 0 23 9t9 23z"></path>
                        </svg>
                    ) : (
                        <svg
                            className="mx-auto mb-4 w-14 h-14 text-gray-400 dark:text-gray-200"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    )}

                    <h3 className="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">
                        Você tem certeza que deseja excluir?
                    </h3>

                    <button
                        data-modal-toggle="popup-modal"
                        type="button"
                        onClick={() => handleModal(parseInt(handleModalID))}
                        className="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                        Sim, tenho certeza!
                    </button>

                    <button
                        data-modal-toggle="popup-modal"
                        type="button"
                        onClick={() => setShowModal(false)}
                        className="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                        Não, quero cancelar.
                    </button>
                </div>
            </div>
        </>
    );
};

export default AlertModal;
