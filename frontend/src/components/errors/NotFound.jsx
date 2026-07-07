import React from 'react';
import ErrorPage from './ErrorPage';

const NotFound = () => {
  return (
    <ErrorPage 
      errorCode="404"
      title="Sayfa Bulunamadı"
      description="Görünüşe göre Innox bile aradığınız sayfayı bulamadı. Belki de henüz keşfedilmemiş bir teknolojiye adım atmışsınızdır!"
      mascotUrl="/innomis/assets/innox/merak-eden-innox.svg"
    />
  );
};

export default NotFound;
