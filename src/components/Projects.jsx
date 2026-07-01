import React, { useState } from 'react';
import { ArrowUpRight, Code, Layout, Brain, Gamepad2, Settings } from 'lucide-react';
import { useSiteData } from '../context/SiteDataContext';

const getCategoryIcon = (category) => {
  switch(category) {
    case 'Web Geliştirme': return <Layout className="w-6 h-6" />;
    case 'Mobil Uygulama': return <Code className="w-6 h-6" />;
    case 'Yapay Zeka': return <Brain className="w-6 h-6" />;
    case 'Oyun Geliştirme': return <Gamepad2 className="w-6 h-6" />;
    default: return <Settings className="w-6 h-6" />;
  }
};

const getStatusLabel = (status) => {
  switch(status) {
    case 'active': return 'Aktif';
    case 'completed': return 'Tamamlandı';
    case 'planning': return 'Yakında';
    default: return status;
  }
};

const Projects = () => {
  const [activeFilter, setActiveFilter] = useState('Tümü');
  const { siteData } = useSiteData();
  const projects = siteData?.projects || [];

  const statuses = ['Tümü', ...new Set(projects.map(p => getStatusLabel(p.status)))];

  const filteredProjects = activeFilter === 'Tümü' 
    ? projects 
    : projects.filter(p => getStatusLabel(p.status) === activeFilter);

  return (
    <section className="py-16 lg:py-24 relative" id="projects">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div className="text-center max-w-3xl mx-auto mb-12 space-y-6">
          <h2 className="font-display text-4xl sm:text-5xl font-bold leading-tight text-white">
            Etkinlikten fazlası: <span className="text-gradient">üreten</span> bir teknoloji topluluğu
          </h2>
          <p className="text-text-secondary text-lg">
            InnoMIS ekipleri; dijital sistemler, topluluk araçları, tasarım hafızası ve deneysel teknoloji projeleriyle üretim kültürünü canlı tutar.
          </p>
        </div>

        {/* Filters */}
        <div className="flex flex-wrap justify-center gap-2 mb-12">
          {statuses.map((status, idx) => (
            <button
              key={idx}
              onClick={() => setActiveFilter(status)}
              className={`px-4 py-2 rounded-full text-sm font-medium transition-all duration-300 ${
                activeFilter === status 
                  ? 'bg-primary text-white shadow-[0_0_15px_rgba(244,159,32,0.4)]' 
                  : 'bg-white/5 text-text-meta hover:bg-white/10 hover:text-white border border-white/5'
              }`}
            >
              {status}
            </button>
          ))}
        </div>

        {/* Projects Grid */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {filteredProjects.map((project, idx) => (
            <div key={idx} className="glass-card p-6 sm:p-8 group hover:border-accent-purple/30 transition-all duration-300 relative overflow-hidden flex flex-col h-full">
              <div className="absolute -right-10 -top-10 w-32 h-32 bg-accent-purple/10 blur-2xl rounded-full group-hover:bg-accent-purple/20 transition-colors pointer-events-none"></div>
              
              <div className="flex justify-between items-start mb-6">
                <div className="w-12 h-12 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-white group-hover:text-accent-purple transition-colors shrink-0">
                  {getCategoryIcon(project.category)}
                </div>
                <div className="flex flex-wrap justify-end gap-2 text-right">
                  <span className="px-3 py-1 rounded-full bg-white/5 border border-white/10 text-text-main text-[10px] font-bold uppercase tracking-wider">
                    {project.category}
                  </span>
                  <span className={`px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border border-current/20 
                    ${project.status === 'active' ? 'bg-accent-green/10 text-accent-green' : 
                      project.status === 'completed' ? 'bg-primary/10 text-primary' : 
                      project.status === 'planning' ? 'bg-accent-cyan/10 text-accent-cyan' :
                      'bg-text-secondary/10 text-text-secondary'}`}>
                    {getStatusLabel(project.status)}
                  </span>
                </div>
              </div>
              
              <div className="flex-1">
                <h3 className="text-xl sm:text-2xl font-display font-bold text-white mb-3">{project.title}</h3>
                <p className="text-text-secondary leading-relaxed text-sm sm:text-base">{project.description}</p>
              </div>

              {/* Action Button */}
              {(project.demo_link || project.github_link) && (
                <div className="mt-6 pt-6 border-t border-white/10 flex gap-4">
                  {project.demo_link && (
                    <a 
                      href={project.demo_link} 
                      target="_blank" 
                      rel="noreferrer"
                      className="inline-flex items-center gap-2 text-sm font-semibold text-primary hover:text-white transition-colors group/link"
                    >
                      Projeyi İncele
                      <ArrowUpRight className="w-4 h-4 group-hover/link:translate-x-0.5 group-hover/link:-translate-y-0.5 transition-transform" />
                    </a>
                  )}
                  {project.github_link && (
                    <a 
                      href={project.github_link} 
                      target="_blank" 
                      rel="noreferrer"
                      className="inline-flex items-center gap-2 text-sm font-semibold text-text-secondary hover:text-white transition-colors group/link"
                    >
                      GitHub
                      <ArrowUpRight className="w-4 h-4 group-hover/link:translate-x-0.5 group-hover/link:-translate-y-0.5 transition-transform" />
                    </a>
                  )}
                </div>
              )}
            </div>
          ))}
        </div>
      </div>
    </section>
  );
};

export default Projects;
