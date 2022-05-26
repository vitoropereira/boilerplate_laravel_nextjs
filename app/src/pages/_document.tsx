import Document, { Html, Head, Main, NextScript, DocumentContext } from 'next/document';
import Toast from '../components/toast';

class MyDocument extends Document {
    render() {
        return (
            <Html>
                <Head>
                    <link
                        href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap"
                        rel="stylesheet"
                    />
                </Head>
                <body className="antialiased">
                    <Main />

                    <NextScript />
                </body>
            </Html>
        );
    }
}

export default MyDocument;
