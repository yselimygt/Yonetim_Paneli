import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { addItem } from '../../utils/storage';
import Input from '../../components/ui/Input';
import Button from '../../components/ui/Button';

const Apply = () => {
  const navigate = useNavigate();
  const [formData, setFormData] = useState({
    communityName: '',
    university: '',
    managerName: '',
    email: '',
    phone: '',
    category: '',
    currentVotes: '',
    targetVotes: '',
    competitors: '',
    description: '',
  });

  const handleSubmit = (e) => {
    e.preventDefault();
    addItem('applications', {
      ...formData,
      status: 'pending',
      createdAt: new Date().toISOString()
    });
    navigate('/projects/oy-takip/success');
  };

  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  return (
    <div className="min-h-screen p-8 flex justify-center items-start">
      <div className="w-full max-w-2xl bg-white/[0.02] border border-white/10 rounded-2xl p-8 backdrop-blur-md">
        <h2 className="text-3xl font-bold mb-6 bg-gradient-to-r from-[#f49f20] to-[#da2c2e] bg-clip-text text-transparent">Başvuru Formu</h2>
        <form onSubmit={handleSubmit} className="space-y-4">
          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            <Input label="Topluluk Adı" name="communityName" value={formData.communityName} onChange={handleChange} required />
            <Input label="Üniversite" name="university" value={formData.university} onChange={handleChange} required />
            <Input label="Yetkili Adı Soyadı" name="managerName" value={formData.managerName} onChange={handleChange} required />
            <Input label="E-posta" type="email" name="email" value={formData.email} onChange={handleChange} required />
            <Input label="Telefon" name="phone" value={formData.phone} onChange={handleChange} required />
            <Input label="Yarışılan Kategori" name="category" value={formData.category} onChange={handleChange} required />
            <Input label="Mevcut Bilinen Oy Sayısı" type="number" name="currentVotes" value={formData.currentVotes} onChange={handleChange} required />
            <Input label="Hedef Oy Sayısı" type="number" name="targetVotes" value={formData.targetVotes} onChange={handleChange} required />
          </div>
          <Input label="Rakip Topluluklar" name="competitors" value={formData.competitors} onChange={handleChange} placeholder="Örn: X Topluluğu, Y Topluluğu" />
          
          <div>
            <label className="block text-sm font-medium text-gray-300 mb-1">Açıklama</label>
            <textarea 
              name="description" 
              value={formData.description} 
              onChange={handleChange}
              className="w-full bg-[#0d0a1a]/50 border border-white/10 rounded-lg px-4 py-2.5 text-white focus:border-[#f49f20] focus:outline-none"
              rows={4}
            />
          </div>
          
          <div className="pt-4 border-t border-white/10">
            <Button type="submit" className="w-full">Başvuruyu Gönder</Button>
          </div>
        </form>
      </div>
    </div>
  );
};

export default Apply;
