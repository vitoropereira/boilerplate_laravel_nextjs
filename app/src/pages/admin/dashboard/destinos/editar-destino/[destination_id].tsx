import Head from 'next/head';
import { GetServerSideProps } from 'next';
import { useEffect, useState } from 'react';
import { FaCamera } from 'react-icons/fa';
import { SubmitHandler, useForm } from 'react-hook-form';
import { yupResolver } from '@hookform/resolvers/yup';
import { useRouter } from 'next/router';
import * as Yup from 'yup';
import slugify from 'slugify';

import axiosIBGE from '../../../../../server/axiosIBGE';
import { useAuth } from '../../../../../hooks/auth';

import AuthValidationErrors from '../../../../../components/AuthValidationErrors';
import Button from '../../../../../components/Button';
import Input from '../../../../../components/Input';
import AppLayout from '../../../../../components/Layouts/AppLayout';
import Label from '../../../../../components/Label';
import Select from '../../../../../components/Select';
import TextArea from '../../../../../components/TextArea';

import axios from '../../../../../server/axios';
import { TourDestinations } from '../../../../../utils/tourDestinations';
import { notifyInfo } from '../../../../../hooks/toast';

const schema = Yup.object().shape({
    name: Yup.string().required('Nome é obrigatório.').min(3, 'Mínimo de 3 caracteres.'),
    state: Yup.string().required('Estado é obrigatório.'),
    city: Yup.string().required('Cidade é obrigatório.'),
});

export interface Product {
    id: number;
    tour_destination_id: number;
    featured_image_id?: any;
    name: string;
    slug: string;
    description: string;
    available: boolean;
    meta_title: string;
    meta_description: string;
    created_at: Date;
    updated_at: Date;
    deleted_at?: any;
}

interface TourDestinationProps {
    id: number;
    featured_image_id: number;
    name: string;
    slug: string;
    country: string;
    country_region: string;
    state: string;
    city: string;
    description: string;
}
interface EditDestinationProps {
    data: TourDestinationProps;
}

export default function EditDestination({ data }: EditDestinationProps) {
    const { user } = useAuth({
        middleware: 'auth',
    });

    const router = useRouter();

    const [tour_destination, setTourDestination] = useState<EditDestinationProps>({ data });
    const [stateId, setStateId] = useState<string>();
    const [cityId, setCityId] = useState<string>();
    const { editTourDestination } = TourDestinations();

    const {
        register,
        handleSubmit,
        formState: { errors },
    } = useForm<TourDestinationProps>({
        resolver: yupResolver(schema),
    });

    const [cities, setCities] = useState([]);
    const [states, setStates] = useState([]);

    const [image_path, setImage_path] = useState('');
    const [isLoading, setIsLoading] = useState(false);
    const [apiValidation, setApiValidation] = useState([]);

    const onSubmit: SubmitHandler<TourDestinationProps> = async (data) => {
        setIsLoading(true);

        await axiosIBGE
            .get(`https://servicodados.ibge.gov.br/api/v1/localidades/municipios/${data.city}`)
            .then((response) => {
                data['country_region'] = response.data.microrregiao.mesorregiao.UF.regiao.nome;
                data['state'] = response.data.microrregiao.mesorregiao.UF.nome;
                data['city'] = response.data.nome;
            })
            .catch((error) => {
                console.log(error);
            });

        data['country'] = 'Brasil';
        data['slug'] = slugify(data.name, {
            lower: true,
        });

        Object.keys(data).map((key) => (data[key] === '' || data[key] === null ? delete data[key] : ''));

        try {
            await schema.validate(data, {
                abortEarly: false,
            });

            await editTourDestination({
                setApiValidation,
                setIsLoading,
                tourDestination: data,
                tourDestination_id: tour_destination.data.id,
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
            setApiValidation(['Errors ao salvar os dados, tente novamente mais tarde.']);
            setIsLoading(false);
        }
    };

    async function handleGetCities(event: React.FormEvent<HTMLSelectElement>) {
        event.preventDefault();
        const state_id = event.currentTarget.value;
        getCity(state_id);
    }

    async function getCity(stateId: string) {
        await axiosIBGE
            .get(`https://servicodados.ibge.gov.br/api/v1/localidades/estados/${stateId}/municipios?orderBy=nome`)
            .then((response) => {
                const result = response.data.map((city) => {
                    if (city.nome === tour_destination.data.city) {
                        setCityId(city.id);
                    }
                    return {
                        value: city.id,
                        label: city.nome,
                    };
                });
                setCities(result);
            });
    }

    async function getState() {
        await axiosIBGE
            .get('https://servicodados.ibge.gov.br/api/v1/localidades/estados?orderBy=nome')
            .then((response) => {
                const result = response.data.map((state) => {
                    if (state.nome === tour_destination.data.state) {
                        setStateId(state.id);
                    }
                    return {
                        value: state.id,
                        label: state.nome + ' - ' + state.sigla,
                    };
                });
                setStates(result);
            });
    }

    function thereIsAnError() {
        if (apiValidation.length > 0) {
            setIsLoading(false);
        }
    }

    useEffect(() => {
        thereIsAnError();
    }, [errors]);

    useEffect(() => {
        getCity(stateId);
    }, [stateId]);

    useEffect(() => {
        getState();
        if (!tour_destination.data) {
            router.push('../../../dashboard/destinos');
            notifyInfo('Não foi possível carregar os dados, tente novamente mais tarde.', 3000);
        }
        if (stateId) {
            getCity(stateId);
        }
    }, []);

    return (
        <AppLayout
            header={
                <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                    {!user ? 'Carregando...' : 'Editar destinos'}
                </h2>
            }>
            <Head>
                <title>portal dev. - Cadastro de destinos</title>
            </Head>
            {!user || !tour_destination.data || !stateId ? (
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
                                                        {states.length != 0 && (
                                                            <>
                                                                <Label
                                                                    className="block uppercase text-blueGray-600 text-xs font-bold mb-2"
                                                                    htmlFor="grid-password">
                                                                    Selecione um Estado
                                                                </Label>
                                                                <Select
                                                                    name="state"
                                                                    className="focus:ring-indigo-500 focus:border-indigo-500 block mt-1 w-full"
                                                                    options={states}
                                                                    onChange={(event) => handleGetCities(event)}
                                                                    register={register}
                                                                    error={errors.state}
                                                                    realValue={stateId}
                                                                />
                                                            </>
                                                        )}
                                                    </div>
                                                </div>
                                                <div className="w-full lg:w-6/12 px-4">
                                                    <div className="relative w-full mb-3">
                                                        {cities.length != 0 && (
                                                            <>
                                                                <Label
                                                                    className="block uppercase text-blueGray-600 text-xs font-bold mb-2"
                                                                    htmlFor="grid-password">
                                                                    Selecione um Cidade
                                                                </Label>

                                                                <Select
                                                                    name="city"
                                                                    className="focus:ring-indigo-500 focus:border-indigo-500   block mt-1 w-full"
                                                                    options={cities}
                                                                    register={register}
                                                                    error={errors.city}
                                                                    realValue={cityId}
                                                                />
                                                            </>
                                                        )}
                                                    </div>
                                                </div>
                                            </div>
                                            <hr className="mt-6 border-b-1 border-blueGray-300" />

                                            <h6 className="text-blueGray-400 text-sm mt-3 mb-6 font-bold uppercase">
                                                Informação da Localidade
                                            </h6>
                                            <div className="flex flex-wrap">
                                                <div className="w-full lg:w-12/12 px-4">
                                                    <div className="relative w-full mb-3">
                                                        <Label
                                                            className="block uppercase text-blueGray-600 text-xs font-bold mb-2"
                                                            htmlFor="grid-password">
                                                            Nome do Local
                                                        </Label>
                                                        <Input
                                                            name="name"
                                                            type="text"
                                                            className="focus:ring-indigo-500 focus:border-indigo-500 block mt-1 w-full"
                                                            placeholder="Digite seu Endereço"
                                                            register={register}
                                                            error={errors.name}
                                                            defaultValue={tour_destination.data.name}
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
                                                            defaultValue={tour_destination.data.description}></TextArea>
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
}

export const getServerSideProps: GetServerSideProps = async ({ req, params }) => {
    const { destination_id } = params;

    try {
        const res = await axios.get('/api/tour_destinations/' + destination_id);
        const { data } = res.data;

        return {
            props: {
                data,
            },
        };
    } catch (err) {
        console.log(err);
        return {
            props: {},
        };
    }
};
