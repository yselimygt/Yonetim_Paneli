import React from 'react';
import { Link } from 'react-router-dom';
import { CheckCircle } from 'lucide-react';
import Button from '../../components/ui/Button';

const ApplySuccess = () => {
  return (
    <div className="min-h-screen flex items-center justify-center p-6">
      <div className="max-w-md w-full bg-white/[0.02] border border-white/10 rounded-2xl p-8 text-center backdrop-blur-md">
        <CheckCircle className="w-20 h-20 text-green-500 mx-auto mb-6" />
        <h2 className="text-2xl font-bold text-white mb-2">Başvurunuz Alındı!</h2>
        <p className="text-gray-400 mb-8">
          Başvurunuz başarıyla sistemimize iletilmiştir. Yönetici onayının ardından paneliniz aktif edilecektir. Durum bilgisi e-posta adresinize gönderilecektir.
        </p>
        <Link to="/projects/oy-takip">
          <Button variant="secondary" className="w-full">Ana Sayfaya Dön</Button>
        </Link>
      </div>
    </div>
  );
};

export default ApplySuccess;
