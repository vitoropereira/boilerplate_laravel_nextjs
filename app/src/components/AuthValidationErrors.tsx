const AuthValidationErrors = ({ errors = [], ...props }) => {
    return (
        <>
            {errors.length > 0 && (
                <div {...props}>
                    <div className="font-medium text-red-600">Alguma coisa esta errada.</div>

                    <ul className="mt-3 list-disc list-inside text-sm text-red-600">
                        {errors.map((error) => (
                            <li key={error}>{error}</li>
                        ))}
                    </ul>
                </div>
            )}
        </>
    );
};

export default AuthValidationErrors;
