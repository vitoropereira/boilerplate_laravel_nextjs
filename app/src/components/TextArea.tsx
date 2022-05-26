import { TextareaHTMLAttributes } from 'react';

type ErrorType = {
    error?: string;
};

interface TextAreaProps extends TextareaHTMLAttributes<HTMLTextAreaElement> {
    error?: ErrorType[];
    register: (name: string) => void;
}

const TextArea = ({ name, placeholder, register, className, children, error, ...props }: TextAreaProps) => {
    return (
        <>
            <textarea
                name={name}
                className={`${className} ${
                    error && 'border-red-500'
                }  rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50`}
                {...props}
                {...register(name)}>
                {children}
            </textarea>
            {error && <span className="text-red-500 text-sm">{error}</span>}
        </>
    );
};

export default TextArea;
