import React from 'react';
import ErrorPage from './ErrorPage';

const Unauthorized = () => {
  return (
    <ErrorPage 
      errorCode="401"
      title="Giriş Yapmanız Gerekiyor"
      description="Bu sayfayı görüntüleyebilmek için InnoMIS hesabınıza giriş yapmış olmalısınız."
    />
  );
};

export default Unauthorized;
