import React, { useState, useEffect } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { useSiteData } from '../context/SiteDataContext';
import { createSlug } from '../utils/helpers';
import MemberCard from './shared/MemberCard';
import MemberModal from './shared/MemberModal';

const Team = () => {
  const { slug } = useParams();
  const navigate = useNavigate();
  const [activeTab, setActiveTab] = useState('management');
  const [selectedMember, setSelectedMember] = useState(null);
  
  const { siteData } = useSiteData();
  const teamStructure = siteData?.team?.board ? siteData.team : { board: [], departments: [], campusRepresentatives: [] };

  // Parse URL on mount or URL change
  useEffect(() => {
    if (slug && teamStructure.board) {
      let foundMember = null;
      let foundTab = 'management';
      
      // 1. Check Board
      foundMember = teamStructure.board.find(m => m.uniqueSlug === slug);
      if (foundMember) {
        foundMember = {...foundMember, universityLogo: foundMember.universityLogo || '/assets/uni/neuLogo.png'};
      }

      // 2. Check Departments
      if (!foundMember && teamStructure.departments) {
        for (const department of teamStructure.departments) {
          if (department.director && department.director.uniqueSlug === slug) {
            foundMember = {...department.director, universityLogo: department.director.universityLogo || '/assets/uni/neuLogo.png'};
            break;
          }
          if (department.members) {
            const member = department.members.find(m => m.uniqueSlug === slug);
            if (member) {
              foundMember = {...member, universityLogo: member.universityLogo || '/assets/uni/neuLogo.png'};
              break;
            }
          }
        }
      }

      // 3. Check Campus Representatives
      if (!foundMember && teamStructure.campusRepresentatives) {
        for (const campus of teamStructure.campusRepresentatives) {
          if (campus.representatives) {
            const member = campus.representatives.find(m => m.uniqueSlug === slug);
            if (member) {
              foundTab = 'campus';
              foundMember = {...member, universityLogo: member.universityLogo || campus.logo};
              break;
            }
          }
        }
      }

      if (foundMember) {
        setSelectedMember(foundMember);
        setActiveTab(foundTab);
        
        setTimeout(() => {
           document.getElementById('team')?.scrollIntoView({ behavior: 'smooth' });
        }, 100);
      } else {
        setSelectedMember(null);
      }
    } else {
      setSelectedMember(null);
    }
  }, [slug, teamStructure]);

  const handleMemberClick = (member) => {
    if (member && member.uniqueSlug) {
      navigate(`/team/${member.uniqueSlug}`, { replace: true });
    }
  };

  const closeModal = () => {
    setSelectedMember(null);
    navigate('/team', { replace: true });
  };

  return (
    <section className="py-16 lg:py-24 relative bg-surface/30 border-y border-white/5" id="team">
      {/* Glow */}
      <div className="absolute top-0 right-1/4 w-[400px] h-[400px] bg-primary/5 blur-[120px] rounded-full pointer-events-none"></div>

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div className="text-center mb-12">
          <h2 className="font-display text-4xl sm:text-5xl font-bold text-white">
            Bu enerjinin arkasında <span className="text-gradient">üreten</span> bir ekip var
          </h2>
        </div>

        {/* Tab Switcher */}
        <div className="flex justify-center mb-16">
          <div className="inline-flex bg-white/5 p-1.5 rounded-full border border-white/10">
            <button 
              onClick={() => setActiveTab('management')}
              className={`px-6 sm:px-10 py-3 text-sm sm:text-base font-semibold rounded-full transition-all duration-300 ${activeTab === 'management' ? 'bg-gradient-to-r from-primary to-secondary text-white shadow-lg' : 'text-text-secondary hover:text-white hover:bg-white/5'}`}
            >
              Ana Ekip
            </button>
            <button 
              onClick={() => setActiveTab('campus')}
              className={`px-6 sm:px-10 py-3 text-sm sm:text-base font-semibold rounded-full transition-all duration-300 ${activeTab === 'campus' ? 'bg-gradient-to-r from-primary to-secondary text-white shadow-lg' : 'text-text-secondary hover:text-white hover:bg-white/5'}`}
            >
              Kampüs Temsilcileri
            </button>
          </div>
        </div>

        {/* Management Team Content */}
        {activeTab === 'management' && (
          <div className="animate-fade-in-up">
            {/* Board Section */}
            <div className="mb-24">
          <div className="text-center mb-10">
            <h3 className="inline-block text-2xl sm:text-3xl font-display font-bold text-white border-b-2 border-primary/50 pb-2">Yönetim Kurulu</h3>
          </div>
          <div className="flex flex-wrap justify-center gap-4 max-w-4xl mx-auto">
            {teamStructure.board.map((member, idx) => (
              <div key={idx} className="w-full sm:w-[calc(50%-1rem)]">
                <MemberCard member={{...member, universityLogo: member.universityLogo || '/assets/uni/neuLogo.png'}} isBoard={true} onClick={() => handleMemberClick(member)} />
              </div>
            ))}
          </div>
        </div>

        {/* Departments Section */}
        <div className="space-y-16">
          {[...teamStructure.departments].sort((a, b) => a.name.localeCompare(b.name, 'tr')).map((dept, idx) => (
            <div key={idx} className="bg-white/5 border border-white/5 rounded-3xl p-6 sm:p-8 hover:border-white/10 transition-colors">
              <h3 className="text-xl sm:text-2xl font-display font-bold text-white mb-6 sm:mb-8 flex items-center justify-center gap-4 text-center">
                <span className="w-8 h-1 bg-gradient-to-r from-primary to-secondary rounded-full"></span>
                {dept.name}
              </h3>
              
              {(!dept.members || dept.members.length === 0) ? (
                /* Tek kişilik (Sadece Direktör) departman tasarımı (Ortalanmış) */
                <div className="flex justify-center">
                  <div className="relative group w-full max-w-[280px]">
                    <div className="absolute -inset-0.5 bg-gradient-to-tr from-primary to-secondary opacity-20 rounded-2xl blur group-hover:opacity-40 transition-opacity"></div>
                    <MemberCard member={{...dept.director, universityLogo: dept.director.universityLogo || '/assets/uni/neuLogo.png'}} isDirector={true} onClick={() => handleMemberClick(dept.director)} />
                  </div>
                </div>
              ) : (
                /* Direktör + Üyelerin olduğu normal grid tasarımı */
                <div className="space-y-6">
                  {/* Director */}
                  <div className="flex justify-center">
                    <div className="relative group w-full max-w-sm">
                      <div className="absolute -inset-0.5 bg-gradient-to-tr from-primary to-secondary opacity-20 rounded-2xl blur group-hover:opacity-40 transition-opacity"></div>
                      <MemberCard member={{...dept.director, universityLogo: dept.director.universityLogo || '/assets/uni/neuLogo.png'}} isDirector={true} onClick={() => handleMemberClick(dept.director)} />
                    </div>
                  </div>
                  
                  {/* Team Members */}
                  <div className="flex flex-wrap justify-center gap-4">
                    {dept.members.map((member, mIdx) => (
                      <div key={mIdx} className="w-full sm:w-[calc(50%-1rem)] lg:w-[calc(33.333%-1rem)]">
                        <MemberCard member={{...member, universityLogo: member.universityLogo || '/assets/uni/neuLogo.png'}} onClick={() => handleMemberClick(member)} />
                      </div>
                    ))}
                  </div>
                </div>
              )}
            </div>
          ))}
        </div>
          </div>
        )}

        {/* Campus Representatives Content */}
        {activeTab === 'campus' && (
          <div className="animate-fade-in-up space-y-16">
            {teamStructure.campusRepresentatives.map((campus, idx) => (
              <div key={idx} className="bg-white/5 border border-white/5 rounded-3xl p-6 sm:p-8 hover:border-white/10 transition-colors">
                <h3 className="text-xl sm:text-2xl font-display font-bold text-white mb-6 sm:mb-8 flex items-center justify-center gap-4 text-center">
                  {campus.logo ? (
                    <div className="bg-white p-1.5 rounded-lg flex items-center justify-center shrink-0">
                      <img src={campus.logo} alt={`${campus.university} Logo`} className="h-8 sm:h-10 w-auto object-contain" />
                    </div>
                  ) : (
                    <span className="w-8 h-1 bg-gradient-to-r from-primary to-secondary rounded-full shrink-0"></span>
                  )}
                  <span className="leading-tight">{campus.university}</span>
                </h3>
                
                <div className="flex flex-wrap justify-center gap-4">
                  {campus.representatives.map((member, mIdx) => (
                    <div key={mIdx} className="w-full sm:w-[calc(50%-1rem)] lg:w-[calc(33.333%-1rem)]">
                      <MemberCard member={{...member, universityLogo: member.universityLogo || campus.logo}} onClick={() => handleMemberClick(member)} />
                    </div>
                  ))}
                </div>
              </div>
            ))}
          </div>
        )}

      </div>

      {/* Member Detail Modal */}
      {selectedMember && (
        <MemberModal member={selectedMember} onClose={closeModal} />
      )}
    </section>
  );
};

export default Team;
