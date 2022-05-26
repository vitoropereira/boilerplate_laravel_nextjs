import { InputHTMLAttributes, useCallback } from 'react';
import { FieldError } from 'react-hook-form';
import { cep, cpf, currency, phone } from './mask';

type ErrorType = {
    error?: string;
};

interface InputProps extends InputHTMLAttributes<HTMLInputElement> {
    mask?: 'cep' | 'currency' | 'cpf' | 'phone';
    error?: FieldError;
    register: (name: string) => void;
}

const Input = ({ name, type, placeholder, mask, className, error, register, ...props }: InputProps) => {
    const handleKeyUp = useCallback(
        (e: React.FormEvent<HTMLInputElement>) => {
            if (mask === 'cep') {
                cep(e);
            }
            if (mask === 'currency') {
                currency(e);
            }
            if (mask === 'cpf') {
                cpf(e);
            }
            if (mask === 'phone') {
                phone(e);
            }
        },
        [mask]
    );
    return (
        <>
            <input
                name={name}
                type={type}
                className={`${className} ${
                    error ? 'focus:border-red-500' : 'focus:border-indigo-300'
                } rounded-md shadow-sm border-gray-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50`}
                onKeyUp={handleKeyUp}
                {...register(name)}
                {...props}
            />
            {error && <span className="text-red-500 text-sm">{error?.message}</span>}
        </>
    );
};

export default Input;
