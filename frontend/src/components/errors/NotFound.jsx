import React from 'react';
import ErrorPage from './ErrorPage';

const NotFound = () => {
  return (
    <ErrorPage 
      errorCode="404"
      title="Sayfa Bulunamadı"
      description="Görünüşe göre aradığınız sayfayı bulamadık. Belki de henüz keşfedilmemiş bir teknolojiye adım atmışsınızdır!"
    />
  );
};

export default NotFound;
