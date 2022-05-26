import { useRouter } from 'next/router';
import axios from '../server/axios';
import { notifyError, notifySuccess } from '../hooks/toast';

export interface UF {
    nome: string;
}

export interface Mesorregiao {
    UF: string;
}

export interface MicroProps {
    mesorregiao: Mesorregiao;
}

export interface RegionProps {
    microrregiao: MicroProps;
    sigla: string;
    nome: string;
    id: number;
}

export interface TourDestinationProps {
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

export interface UseDestinationProps {
    tourDestination: TourDestinationProps;
}

interface TourDestinationsProps {
    tourDestination: TourDestinationProps;
    setApiValidation(errors: any): void;
    setIsLoading(isLoading: boolean): void;
}

interface EditTourDestinationsProps {
    tourDestination: TourDestinationProps;
    tourDestination_id: number;
    setApiValidation(errors: any): void;
    setIsLoading(isLoading: boolean): void;
}

export const TourDestinations = () => {
    const router = useRouter();

    const saveNewTourDestination = async ({
        setApiValidation,
        setIsLoading,
        tourDestination,
    }: TourDestinationsProps) => {
        setIsLoading(true);
        setApiValidation([]);
        await axios
            .post('/api/tour_destinations', tourDestination)
            .then(() => {
                setIsLoading(false);
                notifySuccess('Destino salvo com sucesso!', 3000);
                router.push('/admin/dashboard/destinos');
            })
            .catch((error) => {
                setIsLoading(false);
                if (error.response.status !== 422) throw error;
                console.log(error);
                setApiValidation(Object.values(error.response.data.errors).flat());
            });
    };

    const getTourDestinationBySlug = async (slug: string) => {
        const response = await axios.get(`/api/tour_destinations?slug=${slug}`);
        return response.data;
    };

    const deleteTourDestination = async (tourDestination_id: number) => {
        try {
            await axios.delete(`/api/tour_destinations/${tourDestination_id}`);
            notifySuccess('Destino excluído com sucesso!', 3000);
            router.push('/admin/dashboard/destinos');
        } catch (error) {
            if (error.response.status === 422) {
                notifyError('Não foi possível excluir este destino. Ele deve possuir um ou mais produto.', 4000);
            }
            console.log(error);
        }
    };

    const editTourDestination = async ({
        setApiValidation,
        setIsLoading,
        tourDestination,
        tourDestination_id,
    }: EditTourDestinationsProps) => {
        setIsLoading(true);
        setApiValidation([]);
        try {
            await axios
                .put(`/api/tour_destinations/${tourDestination_id}`, tourDestination)
                .then(() => {
                    setIsLoading(false);
                    notifySuccess('Edição salva com sucesso!', 3000);
                    router.push('/admin/dashboard/destinos');
                })
                .catch((error) => {
                    setIsLoading(false);
                    if (error.response.status !== 422) throw error;
                    console.log(error);
                    setApiValidation(Object.values(error.response.data.errors).flat());
                    notifyError('Erro ao salvar edição do destino!', 3000);
                });
        } catch (error) {
            notifyError('Erro ao salvar edição do destino!', 3000);
            console.log(error);
        }
    };

    return {
        saveNewTourDestination,
        getTourDestinationBySlug,
        editTourDestination,
        deleteTourDestination,
    };
};
