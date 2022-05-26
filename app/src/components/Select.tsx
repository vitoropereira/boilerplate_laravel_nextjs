import React, { SelectHTMLAttributes, useState } from 'react';
import { FieldError } from 'react-hook-form';
import StateManagedSelect from 'react-select';
import ReactSelect from 'react-select';

export type OptionType = {
    label: string;
    value: string;
};

interface SelectProps extends SelectHTMLAttributes<HTMLSelectElement> {
    error?: FieldError;
    register: (name: string) => void;
    className?: string;
    options: OptionType[];
    name: string;
    realValue?: string;
}

export default function Select({ register, options, name, error, className, realValue, ...props }: SelectProps) {
    return (
        <>
            <select
                name={name}
                className={`${className} ${
                    error ? 'focus:border-red-500' : 'focus:border-indigo-300'
                }  rounded-md shadow-sm border-gray-300  focus:ring focus:ring-indigo-200 focus:ring-opacity-50`}
                {...register(name)}
                defaultValue={realValue}
                {...props}>
                <option value="">Selecione...</option>
                {options.map((option) => {
                    return (
                        <option key={option.value} value={option.value}>
                            {option.label}
                        </option>
                    );
                })}
            </select>
            {error && <span className="text-red-500 text-xs italic">{error?.message}</span>}
        </>
    );
}
