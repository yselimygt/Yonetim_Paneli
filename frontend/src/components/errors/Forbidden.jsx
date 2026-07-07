import React from 'react';
import ErrorPage from './ErrorPage';

const Forbidden = () => {
  return (
    <ErrorPage 
      errorCode="403"
      title="Erişim Reddedildi!"
      description="Innox buraya girmeye yetkinizin olmadığını söylüyor. Yönetim paneli veya özel bir alana girmeye çalışıyor olabilirsiniz."
      mascotUrl="/innomis/assets/innox/innox-kararli.png"
    />
  );
};

export default Forbidden;
