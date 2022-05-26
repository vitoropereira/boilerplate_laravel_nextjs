import { FaBullhorn, FaWindowClose } from 'react-icons/fa';
const AuthValidationSuccess = ({ successMessage, titleMessage, handleCloseAlert, ...props }) => {
    return (
        <div className="bg-indigo-600">
            <div className="max-w-7xl mx-auto py-3 px-3 sm:px-6 lg:px-8">
                <div className="flex items-center justify-between flex-wrap">
                    <div className="w-0 flex-1 flex items-center">
                        <span className="flex p-2 rounded-lg bg-indigo-800">
                            <FaBullhorn className="h-6 w-6 text-white" aria-hidden="true" />
                        </span>
                        <p className="ml-3 font-medium text-white truncate">
                            <span className="md:hidden">{titleMessage}</span>
                            <span className="hidden md:inline">{successMessage}</span>
                        </p>
                    </div>

                    <div className="order-2 flex-shrink-0 sm:order-3 sm:ml-3">
                        <button
                            type="button"
                            onClick={handleCloseAlert}
                            className="-mr-1 flex p-2 rounded-md hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-white sm:-mr-2">
                            <span className="sr-only">Dismiss</span>
                            <FaWindowClose className="h-3 w-3 text-white" aria-hidden="true" />
                        </button>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default AuthValidationSuccess;
