import { useRouter } from 'next/router';
import axios from '../server/axios';
import { notifyError, notifySuccess } from '../hooks/toast';

export interface ProductProps {
    id: number;
    featured_image_id: number;
    tour_destination_id: number;
    name: string;
    slug: string;
    description: string;
    available: boolean;
    meta_title: string;
    meta_description: string;
}

interface ProductsProps {
    product: ProductProps;
    setApiValidation(errors: any): void;
    setIsLoading(isLoading: boolean): void;
}

interface EditProductsProps {
    product: ProductProps;
    product_id: number;
    setApiValidation(errors: any): void;
    setIsLoading(isLoading: boolean): void;
}

export const Products = () => {
    const router = useRouter();

    const saveNewProduct = async ({ setApiValidation, setIsLoading, product }: ProductsProps) => {
        setIsLoading(true);
        setApiValidation([]);
        await axios
            .post(`/api/products`, product)
            .then((response) => {
                setIsLoading(false);
                notifySuccess('Produto salvo com sucesso!', 3000);
                router.push('/admin/dashboard/produtos');
            })
            .catch((error) => {
                setIsLoading(false);
                if (error.response.status !== 422) throw error;
                console.log(error);
                setApiValidation(Object.values(error.response.data.errors).flat());
            });
    };

    const editProduct = async ({ setApiValidation, setIsLoading, product, product_id }: EditProductsProps) => {
        setIsLoading(true);
        setApiValidation([]);
        try {
            await axios
                .put(`/api/products/${product_id}`, product)
                .then(() => {
                    setIsLoading(false);
                    notifySuccess('Edição salva com sucesso!', 3000);
                    router.push('/admin/dashboard/produtos');
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

    const deleteProduct = async (product_id: number) => {
        try {
            await axios.delete(`/api/products/${product_id}`);
            notifySuccess('Produto excluído com sucesso!', 3000);
            router.push('/admin/dashboard/produtos');
        } catch (error) {
            // if (error.response.status === 422) {
            //     notifyError('Não foi possível excluir este produto. Ele deve possuir um ou mais produto.', 4000);
            // }
            console.log(error);
        }
    };

    return {
        saveNewProduct,
        editProduct,
        deleteProduct,
    };
};
