interface LabelProps {
    className?: String;
    children: string;
    htmlFor: string;
}

const Label = ({ className, children, htmlFor, ...props }: LabelProps) => (
    <label className={`${className} block font-medium text-sm text-gray-700`} {...props}>
        {children}
    </label>
);

export default Label;
