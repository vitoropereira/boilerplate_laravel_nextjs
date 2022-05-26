import Toast from '../components/toast';
import './styles/tailwind.css';

const App = ({ Component, pageProps }) => {
    return (
        <>
            <Toast />
            <Component {...pageProps} />
        </>
    );
};
export default App;
