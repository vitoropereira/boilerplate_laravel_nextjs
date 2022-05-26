import { useState } from 'react';

interface ToggleProps {
    available: boolean;
    setIsProductAvailable: (isAvailable: boolean) => void;
}

export const Toggle = ({ available, setIsProductAvailable }: ToggleProps) => {
    const [isAvailable, setIsaAvailable] = useState(available);

    const handleClick = () => {
        setIsaAvailable(!isAvailable);
        setIsProductAvailable(!isAvailable);
    };

    return (
        <>
            <div>
                <div className="relative inline-block w-10 mr-2 align-middle select-none">
                    <input
                        type="checkbox"
                        name="toggle"
                        id="Blue"
                        className="checked:bg-blue-500 outline-none focus:outline-none right-4 checked:right-0 duration-200 ease-in absolute block w-6 h-6 rounded-full bg-red-700 border-4 appearance-none cursor-pointer"
                        onChange={handleClick}
                        checked={isAvailable}
                    />
                    <label
                        htmlFor="Blue"
                        className="block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                </div>
                <span className="text-gray-400 font-medium">{isAvailable ? 'Disponível' : 'Indisponível'}</span>
            </div>
        </>
    );
};
