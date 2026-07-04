import React, { useState, useEffect } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { useSiteData } from '../context/SiteDataContext';
import MemberCard from './shared/MemberCard';
import MemberModal from './shared/MemberModal';

const Alumni = () => {
  const { year, slug } = useParams();
  const navigate = useNavigate();
  const [selectedMember, setSelectedMember] = useState(null);
  
  const { siteData } = useSiteData();
  const alumniData = siteData?.team?.alumni || [];

  // Parse URL on mount or URL change
  useEffect(() => {
    if (year && slug && alumniData.length > 0) {
      let foundMember = null;
      
      const yearGroup = alumniData.find(g => g.year === year);
      if (yearGroup) {
        const member = yearGroup.members.find(m => m.uniqueSlug === slug);
        if (member) {
          foundMember = {...member, universityLogo: member.universityLogo || '/assets/uni/neuLogo.png'};
        }
      }

      if (foundMember) {
        setSelectedMember(foundMember);
        setTimeout(() => {
           document.getElementById('alumni')?.scrollIntoView({ behavior: 'smooth' });
        }, 100);
      }
    } else {
      setSelectedMember(null);
    }
  }, [year, slug, alumniData]);

  const handleMemberClick = (member, yearId) => {
    if (member && member.uniqueSlug) {
      navigate(`/alumni/${yearId}/${member.uniqueSlug}`, { replace: true });
    }
  };

  const closeModal = () => {
    setSelectedMember(null);
    navigate('/alumni', { replace: true });
  };

  return (
    <section className="py-24 relative bg-surface/30 border-y border-white/5 min-h-screen" id="alumni">
      {/* Glow */}
      <div className="absolute top-0 right-1/4 w-[400px] h-[400px] bg-primary/5 blur-[120px] rounded-full pointer-events-none"></div>

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 pt-10">
        <div className="text-center mb-16">
          <h2 className="font-display text-4xl sm:text-5xl font-bold text-white mb-4">
            Mezunlarımızla <span className="text-gradient">Gurur Duyuyoruz</span>
          </h2>
          <p className="text-text-secondary max-w-2xl mx-auto">
            Topluluğumuza değer katan ve şimdi iş hayatında başarılara imza atan değerli mezunlarımız.
          </p>
        </div>

        {alumniData.length === 0 ? (
          <div className="text-center text-text-meta py-20">
            <p>Henüz mezun bilgisi eklenmemiş.</p>
          </div>
        ) : (
          <div className="space-y-16">
            {alumniData.map((yearGroup, idx) => (
              <div key={idx} className="bg-white/5 border border-white/5 rounded-3xl p-6 sm:p-8 hover:border-white/10 transition-colors animate-fade-in-up" style={{ animationDelay: `${idx * 150}ms` }}>
                <h3 className="text-xl sm:text-2xl font-display font-bold text-white mb-6 sm:mb-8 flex items-center justify-center gap-4 text-center">
                  <span className="w-8 h-1 bg-gradient-to-r from-primary to-secondary rounded-full"></span>
                  {yearGroup.name}
                  <span className="w-8 h-1 bg-gradient-to-r from-primary to-secondary rounded-full"></span>
                </h3>
                
                <div className="flex flex-wrap justify-center gap-4">
                  {yearGroup.members && yearGroup.members.map((member, mIdx) => (
                    <div key={mIdx} className="w-full sm:w-[calc(50%-1rem)] lg:w-[calc(33.333%-1rem)]">
                      <MemberCard 
                        member={{...member, universityLogo: member.universityLogo || '/assets/uni/neuLogo.png'}} 
                        onClick={() => handleMemberClick(member, yearGroup.year)} 
                      />
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

export default Alumni;
