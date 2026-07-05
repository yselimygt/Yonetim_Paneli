import React, { useState } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import { addItem, getStorage } from '../../utils/storage';
import Input from '../../components/ui/Input';
import Button from '../../components/ui/Button';
import { Check, AlertCircle } from 'lucide-react';

const CATEGORIES = [
  "Yılın En İlgi Çekici Topluluğu",
  "Yılın En Eğlenceli Etkinliklerini Düzenleyen Topluluk",
  "Yılın En Aktif Topluluğu",
  "Yılın En Tanınır ve En İyi Çıkış Yapan Topluluğu",
  "Yılın Sosyal Medyayı En İyi Yöneten Topluluğu",
  "Yılın En İyi Proje Fikrine Sahip Topluluğu",
  "Yılın En İyi Sosyal Sorumluluk Ve Sürdürülebilirlik Projesini Yapan Topluluğu",
  "Yılın Ulusal Düzeyde En Başarılı Topluluğu",
  "Yılın Tematik Etkinliklerde En Başarılı Topluluğu",
  "Yılın En Çok Ve Nitelikli İş Birliği Yapan Topluluğu",
  "Yılın Topluluk İçi İletişimi ve Dayanışması En İyi Olan Topluluk",
  "Yılın En Yüksek Katılımlı Etkinliğini Düzenleyen Topluluğu"
];

const Apply = () => {
  const navigate = useNavigate();
  const [formData, setFormData] = useState({
    communityName: '',
    university: '',
    managerName: '',
    email: '',
    phone: '',
    competitors: '',
    description: '',
    image: '', // To store selected logo
  });
  const [selectedCategories, setSelectedCategories] = useState([]);
  const [categoryVotes, setCategoryVotes] = useState({});
  const [error, setError] = useState('');
  
  // Olası Üniversite ve Topluluk listesini storage üzerinden çekiyoruz
  // Gerçek senaryoda bu bir API üzerinden rehberden çekilebilir.
  const allCommunities = getStorage('communities') || [];
  const uniqueUniversities = [...new Set(allCommunities.map(c => c.university).filter(Boolean))];
  
  // Seçili üniversiteye göre filtrelenmiş topluluklar
  const filteredCommunities = formData.university 
    ? allCommunities.filter(c => c.university === formData.university)
    : [];

  const handleCategoryToggle = (cat) => {
    if (selectedCategories.includes(cat)) {
      setSelectedCategories(selectedCategories.filter(c => c !== cat));
      setError('');
    } else {
      if (selectedCategories.length >= 3) {
        setError('En fazla 3 kategori seçebilirsiniz.');
        return;
      }
      setSelectedCategories([...selectedCategories, cat]);
      setError('');
    }
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    if (selectedCategories.length === 0) {
      setError('Lütfen en az 1 kategori seçiniz.');
      window.scrollTo({ top: 0, behavior: 'smooth' });
      return;
    }

    const applicationData = {
      ...formData,
      category: selectedCategories.join(', '),
      categoryVotes: categoryVotes,
      status: 'pending',
      createdAt: new Date().toISOString()
    };

    addItem('applications', applicationData);
    navigate('/projects/oy-takip/success');
  };

  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  return (
    <div className="min-h-screen bg-[#1B1E27] pb-12 md:pb-12 pt-0 md:pt-12 px-0 md:px-4 flex justify-center items-start relative">
      <div className="w-full max-w-4xl bg-[#1B1E27] md:bg-black/40 border-0 md:border border-white/10 rounded-none md:rounded-3xl p-5 md:p-10 shadow-none md:shadow-2xl relative overflow-hidden backdrop-blur-xl">
        {/* Glow Effects */}
        <div className="absolute top-0 right-0 w-[300px] h-[300px] bg-[#DA2F81]/15 blur-[100px] rounded-full pointer-events-none" />
        <div className="absolute bottom-0 left-0 w-[300px] h-[300px] bg-[#ffb74d]/10 blur-[100px] rounded-full pointer-events-none" />

        <div className="relative z-10">
          <div className="mb-6 flex flex-col items-center sm:items-start text-center sm:text-left">
            <div className="flex items-center justify-center gap-4 mb-6 sm:hidden">
              <img 
                src="https://www.gmtoplulukodulleri.com/gmalogov1.png" 
                alt="GM Topluluk Ödülleri Logo" 
                className="w-20 drop-shadow-md"
              />
              <div className="h-10 w-px bg-white/20"></div>
              <img 
                src="/assets/logo/Ana-Logo-Arma.png" 
                alt="Innomis Logo" 
                className="w-20 drop-shadow-md"
              />
            </div>
            <Link to="/projects/oy-takip" className="text-[#ffb74d] hover:text-[#DA2F81] transition-colors text-sm mb-4 inline-block font-medium">
              &larr; Ana Sayfaya Dön
            </Link>
            <h2 className="text-3xl md:text-4xl font-extrabold mb-2 text-white">Başvuru Formu</h2>
            <p className="text-gray-400 text-sm md:text-base">Topluluğunuzun oy sayacını manuel takip etmek için formu doldurun.</p>
          </div>
          
          {error && (
            <div className="mb-6 p-4 bg-red-500/10 border border-red-500/20 rounded-xl flex items-center gap-3 text-red-400 text-sm md:text-base">
              <AlertCircle size={20} className="flex-shrink-0" />
              <span>{error}</span>
            </div>
          )}

          <form onSubmit={handleSubmit} className="space-y-6 md:space-y-8">
            <div className="bg-white/[0.03] border border-white/5 p-5 md:p-6 rounded-2xl">
              <h3 className="text-lg md:text-xl font-bold mb-4 border-b border-white/5 pb-2">İletişim Bilgileri</h3>
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-6">
                <div className="flex flex-col gap-1 relative">
                  <label className="text-sm font-medium text-gray-300">Üniversite Seçiniz</label>
                  <select 
                    name="university"
                    value={formData.university}
                    onChange={(e) => {
                      setFormData(prev => ({ ...prev, university: e.target.value, communityName: '', image: '' }));
                    }}
                    required
                    className="w-full bg-[#0d0a1a]/50 border border-white/10 hover:border-white/20 focus:border-[#DA2F81] rounded-lg px-4 py-3 text-white appearance-none outline-none transition-colors"
                  >
                    <option value="" disabled>Üniversite Seçin...</option>
                    {uniqueUniversities.map((uni, idx) => (
                      <option key={idx} value={uni}>{uni}</option>
                    ))}
                  </select>
                </div>

                <div className="flex flex-col gap-1 relative">
                  <label className="text-sm font-medium text-gray-300">Topluluk Seçiniz</label>
                  <div className="relative">
                    <select 
                      name="communityName"
                      value={formData.communityName}
                      onChange={(e) => {
                        const selected = filteredCommunities.find(c => c.name === e.target.value);
                        setFormData(prev => ({ 
                          ...prev, 
                          communityName: e.target.value,
                          image: selected ? selected.image : ''
                        }));
                      }}
                      required
                      disabled={!formData.university}
                      className="w-full bg-[#0d0a1a]/50 border border-white/10 hover:border-white/20 focus:border-[#DA2F81] rounded-lg pl-12 pr-4 py-3 text-white appearance-none outline-none transition-colors disabled:opacity-50"
                    >
                      <option value="" disabled>
                        {formData.university ? "Topluluk Seçin..." : "Önce Üniversite Seçin"}
                      </option>
                      {filteredCommunities.map((com, idx) => (
                        <option key={idx} value={com.name}>{com.name}</option>
                      ))}
                    </select>
                    
                    {/* Selected Logo Preview or Placeholder inside the select box */}
                    <div className="absolute left-3 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full overflow-hidden bg-white/10 pointer-events-none flex items-center justify-center">
                      {formData.image ? (
                        <img src={formData.image} alt="logo" className="w-full h-full object-cover" />
                      ) : (
                        <span className="text-[10px] text-gray-400">?</span>
                      )}
                    </div>
                  </div>
                </div>

                <Input label="Yetkili Adı Soyadı" name="managerName" value={formData.managerName} onChange={handleChange} required />
                <Input label="E-posta" type="email" name="email" value={formData.email} onChange={handleChange} required />
                <Input label="Telefon" name="phone" value={formData.phone} onChange={handleChange} required />
              </div>
            </div>

            <div className="bg-white/[0.03] border border-white/5 p-5 md:p-6 rounded-2xl">
              <div className="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-4 border-b border-white/5 pb-2 gap-2">
                <h3 className="text-lg md:text-xl font-bold">Kategoriler</h3>
                <span className="text-xs md:text-sm px-3 py-1 bg-[#DA2F81]/20 text-[#ffb74d] rounded-full border border-[#DA2F81]/30">
                  {selectedCategories.length} / 3 Seçildi
                </span>
              </div>
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-4">
                {CATEGORIES.map((cat, idx) => {
                  const isSelected = selectedCategories.includes(cat);
                  const isDisabled = !isSelected && selectedCategories.length >= 3;
                  
                  return (
                    <button
                      key={idx}
                      type="button"
                      disabled={isDisabled}
                      onClick={() => handleCategoryToggle(cat)}
                      className={`text-left p-3 md:p-4 rounded-xl border transition-all duration-300 flex items-start gap-3 w-full
                        ${isSelected 
                          ? 'bg-[#DA2F81]/15 border-[#DA2F81]/50 text-white shadow-[0_0_15px_rgba(218,47,129,0.2)]' 
                          : isDisabled 
                            ? 'bg-transparent border-white/5 text-gray-600 cursor-not-allowed'
                            : 'bg-white/[0.02] border-white/10 text-gray-300 hover:bg-white/[0.05] hover:border-white/20 active:scale-[0.98]'
                        }`}
                    >
                      <div className={`w-5 h-5 mt-0.5 rounded flex-shrink-0 flex items-center justify-center border transition-colors
                        ${isSelected ? 'bg-[#DA2F81] border-[#DA2F81]' : 'border-gray-500'}
                      `}>
                        {isSelected && <Check size={14} className="text-white" />}
                      </div>
                      <span className="text-xs md:text-sm font-medium leading-snug">{cat}</span>
                    </button>
                  );
                })}
              </div>
            </div>

            <div className="bg-white/[0.03] border border-white/5 p-5 md:p-6 rounded-2xl">
               <h3 className="text-lg md:text-xl font-bold mb-4 border-b border-white/5 pb-2">Kategori Bazlı Oy Durumu</h3>
              
              {selectedCategories.length === 0 ? (
                <div className="text-gray-500 text-sm italic py-4">Lütfen yukarıdan en az 1 kategori seçiniz.</div>
              ) : (
                <div className="space-y-6">
                  {selectedCategories.map((cat, idx) => (
                    <div key={idx} className="bg-white/[0.02] p-4 rounded-xl border border-white/5">
                      <h4 className="text-[#DA2F81] font-bold text-sm mb-3">{cat}</h4>
                      <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <Input 
                          label="Mevcut Bilinen Oy Sayısı" 
                          type="number" 
                          value={categoryVotes[cat]?.current || ''} 
                          onChange={(e) => setCategoryVotes({...categoryVotes, [cat]: {...categoryVotes[cat], current: Number(e.target.value)}})} 
                          required 
                        />
                        <Input 
                          label="Hedef Oy Sayısı" 
                          type="number" 
                          value={categoryVotes[cat]?.target || ''} 
                          onChange={(e) => setCategoryVotes({...categoryVotes, [cat]: {...categoryVotes[cat], target: Number(e.target.value)}})} 
                          required 
                        />
                      </div>
                    </div>
                  ))}
                </div>
              )}
            </div>
            
            {/* Unified Submit Button */}
            <div className="pt-6 px-4 md:px-0">
              <Button type="submit" className="w-full py-4 text-base md:text-lg shadow-[0_5px_20px_rgba(218,47,129,0.3)] bg-gradient-to-r from-[#DA2F81] to-[#ffb74d] text-white">Başvuruyu Tamamla ve Gönder</Button>
            </div>
          </form>
        </div>
      </div>
    </div>
  );
};

export default Apply;
