import Head from 'next/head';
import { useEffect, useState } from 'react';
import { FaCamera } from 'react-icons/fa';
import { SubmitHandler, useForm } from 'react-hook-form';
import { yupResolver } from '@hookform/resolvers/yup';
import * as Yup from 'yup';

import axios from '../../../../../server/axios';
import { useAuth } from '../../../../../hooks/auth';

import AuthValidationErrors from '../../../../../components/AuthValidationErrors';
import Button from '../../../../../components/Button';
import Input from '../../../../../components/Input';
import AppLayout from '../../../../../components/Layouts/AppLayout';
import Label from '../../../../../components/Label';
import Select, { OptionType } from '../../../../../components/Select';
import TextArea from '../../../../../components/TextArea';
import slugify from 'slugify';
import { GetServerSideProps, GetStaticProps } from 'next';
import { ProductProps, Products } from '../../../../../utils/products';
import { Toggle } from '../../../../../components/Toggle';

export interface ProductPrices {
    data: any[];
}

export interface TourDestination {
    images: any[];
    product_prices?: any;
    id: number;
    featured_image_id?: any;
    country: string;
    country_region: string;
    state: string;
    city: string;
    name: string;
    slug: string;
    description: string;
}

export interface Product {
    images: any[];
    product_prices: ProductPrices;
    tour_destination: TourDestination;
    id: number;
    featured_image_id?: any;
    name: string;
    slug: string;
    description: string;
    available: boolean;
    meta_title: string;
    meta_description: string;
}

interface EditProductProps {
    data: Product;
    tourDestinations: OptionType[];
}

const schema = Yup.object().shape({
    name: Yup.string().required('Nome é obrigatório').min(3, 'Mínimo de 3 caracteres'),
    tour_destination_id: Yup.string().required('Local do produto é obrigatório'),
});

const EditProduct = ({ data, tourDestinations }: EditProductProps) => {
    const { user } = useAuth({
        middleware: 'auth',
    });
    console.log(data.id);
    const {
        register,
        handleSubmit,
        formState: { errors },
    } = useForm<ProductProps>({
        resolver: yupResolver(schema),
    });

    const { editProduct } = Products();

    const [isProductAvailable, setIsProductAvailable] = useState(data.available);
    const [destinations, setDestinations] = useState(tourDestinations);
    const [description, setDescription] = useState(data.description);
    const [image_path, setImage_path] = useState('');
    const [isLoading, setIsLoading] = useState(false);
    const [apiValidation, setApiValidation] = useState([]);

    const onSubmit: SubmitHandler<ProductProps> = async (formData) => {
        setIsLoading(true);

        formData['available'] = isProductAvailable;

        formData['meta_title'] = formData.name;
        if (formData.description === '') {
            formData['meta_description'] = formData.name;
        } else {
            formData['meta_description'] = formData.description;
        }
        formData['slug'] = slugify(formData.name, {
            lower: true,
        });

        Object.keys(formData).map((key) =>
            formData[key] === '' || formData[key] === null ? delete formData[key] : ''
        );
        console.log(formData.id);
        try {
            await schema.validate(formData, {
                abortEarly: false,
            });

            await editProduct({
                setApiValidation,
                setIsLoading,
                product: formData,
                product_id: data.id,
            });
        } catch (err) {
            if (err instanceof Yup.ValidationError) {
                console.log(err);
                const errorMessages = {};

                err.inner.forEach((error) => {
                    errorMessages[error.path] = error.message;
                });

                return setApiValidation([errorMessages]);
            }
            setApiValidation(['Erro ao salvar os dados, tente novamente mais tarde.']);

            setIsLoading(false);
        }
    };

    function thereIsAnError() {
        if (apiValidation.length > 0) {
            setIsLoading(false);
        }
    }

    useEffect(() => {
        thereIsAnError();
    }, [apiValidation]);

    return (
        <AppLayout
            header={
                <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                    {!user ? 'Carregando...' : 'Editar Produtos'}
                </h2>
            }>
            <Head>
                <title>portal dev. - Cadastro de produtos</title>
            </Head>
            {!user ? (
                <div className="flex justify-center items-center h-96 w-full">
                    <div className="w-16 h-16 border-4 border-blue-400 border-solid border-y-white rounded-full animate-spin"></div>
                </div>
            ) : (
                <>
                    <div className="py-12">
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div className="w-full mx-2 sm:px-4 lg:px-6">
                                <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                    <div className="p-6 bg-white border-b border-gray-200">
                                        <div className="card card-small mb-4 pt-3">
                                            <div className="card-header border-bottom text-center">
                                                <div className="mb-3 mx-auto h-16 w-16">
                                                    {/* <Image
                                                        className="shadow-xl rounded-full h-auto align-middle border-none absolute -m-16 -ml-20 lg:-ml-16 max-w-150-px"
                                                        src={profile}
                                                        alt="User Avatar"
                                                    /> */}
                                                </div>
                                                <span className="border-bottom"></span>
                                                <button
                                                    type="submit"
                                                    className="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                    <span className="absolute left-0 inset-y-0 flex items-center pl-3">
                                                        <FaCamera
                                                            className="h-5 w-5 text-indigo-500 group-hover:text-indigo-400"
                                                            aria-hidden="true"
                                                        />
                                                    </span>
                                                    {image_path == '' ? 'Enviar Imagem' : 'Trocar Imagem'}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div className="col-span-2 w-full mx-2 sm:px-4 lg:px-6">
                                <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                    <div className="p-6 bg-white border-b border-gray-200">
                                        <AuthValidationErrors className="mb-4" errors={apiValidation} />
                                        <form onSubmit={handleSubmit(onSubmit)}>
                                            <h6 className="text-blueGray-400 text-sm mt-3 mb-6 font-bold uppercase">
                                                Informação do Destino no Brasil
                                            </h6>
                                            <div className="flex flex-wrap">
                                                <div className="w-full lg:w-6/12 px-4">
                                                    <div className="relative w-full mb-3">
                                                        <Label
                                                            className="block uppercase text-blueGray-600 text-xs font-bold mb-2"
                                                            htmlFor="grid-password">
                                                            Selecione uma localidade
                                                        </Label>
                                                        <Select
                                                            name="tour_destination_id"
                                                            className="focus:ring-indigo-500 focus:border-indigo-500  block mt-1 w-full"
                                                            options={destinations}
                                                            register={register}
                                                            error={errors.tour_destination_id}
                                                            realValue={data.tour_destination.id.toString()}
                                                        />
                                                    </div>
                                                </div>
                                            </div>
                                            <hr className="mt-6 border-b-1 border-blueGray-300" />

                                            <h6 className="text-blueGray-400 text-sm mt-3 mb-6 font-bold uppercase">
                                                Informação do Produto
                                            </h6>
                                            <div className="flex flex-wrap">
                                                <div className="w-full lg:w-12/12 px-4">
                                                    <div className="relative w-full mb-3">
                                                        <Label
                                                            className="block uppercase text-blueGray-600 text-xs font-bold mb-2"
                                                            htmlFor="grid-password">
                                                            Nome do Produto
                                                        </Label>
                                                        <Input
                                                            name="name"
                                                            type="text"
                                                            className="focus:ring-indigo-500 focus:border-indigo-500 block mt-1 w-full"
                                                            placeholder="Digite seu Endereço"
                                                            register={register}
                                                            error={errors.name}
                                                            defaultValue={data.name}
                                                        />
                                                    </div>
                                                </div>
                                                <div className="w-full lg:w-12/12 px-4">
                                                    <div className="relative w-full mb-3">
                                                        <Label
                                                            className="block uppercase text-blueGray-600 text-xs font-bold mb-2"
                                                            htmlFor="grid-password">
                                                            Descrição
                                                        </Label>
                                                        <TextArea
                                                            name="description"
                                                            className="focus:ring-indigo-500 focus:border-indigo-500  block mt-1 w-full"
                                                            placeholder="Digite um complemento, caso tenha."
                                                            register={register}
                                                            onChange={(e) => setDescription(e.target.value)}
                                                            defaultValue={description}></TextArea>
                                                        <p>{errors.description?.message}</p>
                                                    </div>
                                                </div>
                                                <div className="w-full lg:w-12/12 px-4">
                                                    <div className="relative w-full mb-3">
                                                        <Label
                                                            className="block uppercase text-blueGray-600 text-xs font-bold mb-2"
                                                            htmlFor="grid-password">
                                                            O produto esta disponível?
                                                        </Label>
                                                        <Toggle
                                                            setIsProductAvailable={setIsProductAvailable}
                                                            available={isProductAvailable}
                                                        />
                                                        <p>{errors.description?.message}</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div className="flex items-center justify-end mt-4">
                                                <Button className="text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                    Salvar
                                                </Button>
                                            </div>
                                        </form>
                                        {isLoading && (
                                            <div>
                                                <div className="w-8 h-8 border-4 border-blue-400 border-solid border-y-white rounded-full animate-spin"></div>
                                            </div>
                                        )}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </>
            )}
        </AppLayout>
    );
};

export const getServerSideProps: GetServerSideProps = async ({ req, params }) => {
    const { product_id } = params;
    const res = await axios.get('/api/tour_destinations');
    const { data } = res;
    const response = data.data;

    const tourDestinations = response.map((destination: any) => {
        return {
            label: destination.name,
            value: destination.id,
        };
    });

    try {
        const res = await axios.get('/api/product/' + product_id);
        const { data } = res.data;
        console.log(data);
        return {
            props: {
                data,
                tourDestinations,
            },
        };
    } catch (err) {
        console.log(err);
        return {
            props: {},
        };
    }
};

export default EditProduct;
