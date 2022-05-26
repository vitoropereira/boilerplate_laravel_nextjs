import { ButtonHTMLAttributes } from 'react';

const Button = ({ type = 'submit', className, ...props }: ButtonHTMLAttributes<HTMLButtonElement>) => (
    <button
        type={type}
        className={`${className} group relative flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500`}
        {...props}
    />
);

export default Button;
