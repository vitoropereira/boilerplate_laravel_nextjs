import Image, { ImageProps } from 'next/image';

import logo from '../../../../public/images/logo.png';

const ApplicationLogo = ({ ...props }: ImageProps) => <Image src={logo} {...props} />;

export default ApplicationLogo;
