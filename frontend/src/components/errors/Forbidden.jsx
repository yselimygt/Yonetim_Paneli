import React from 'react';
import ErrorPage from './ErrorPage';

const Forbidden = () => {
  return (
    <ErrorPage 
      errorCode="403"
      title="Erişim Reddedildi!"
      description="Buraya girmeye yetkinizin olmadığı anlaşılıyor. Yönetim paneli veya özel bir alana girmeye çalışıyor olabilirsiniz."
    />
  );
};

export default Forbidden;
