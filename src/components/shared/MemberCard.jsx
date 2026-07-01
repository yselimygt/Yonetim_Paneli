import React from 'react';
import { Linkedin } from 'lucide-react';

const MemberCard = ({ member, isBoard = false, isDirector = false, onClick }) => (
  <div 
    onClick={() => onClick && onClick(member)}
    className={`p-4 group border border-white/5 hover:border-white/10 hover:bg-white/[0.02] rounded-2xl transition-all duration-300 h-full flex items-center gap-4 sm:gap-5 relative ${onClick ? 'cursor-pointer' : ''}`}
  >
    {/* Left: Avatar */}
    <div className={`relative shrink-0 ${isBoard ? 'w-16 h-16 sm:w-20 sm:h-20' : 'w-14 h-14 sm:w-16 sm:h-16'}`}>
      <div className={`w-full h-full rounded-full flex items-center justify-center border transition-all duration-300 ${isDirector || isBoard ? 'bg-primary/5 border-primary/20 group-hover:border-primary/40' : 'bg-white/5 border-white/10 group-hover:border-white/20'}`}>
        <span className={`${isBoard ? 'text-xl sm:text-2xl' : 'text-lg sm:text-xl'} font-display font-bold text-white/80 group-hover:text-white transition-colors`}>{member.initials}</span>
      </div>
      <div className="absolute inset-0 rounded-full bg-primary/10 blur-md -z-10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
    </div>
    
    {/* Right: Info */}
    <div className="flex flex-col items-start justify-center flex-1 text-left min-w-0">
      <h4 className="text-white font-display font-medium text-sm sm:text-base mb-0.5 group-hover:text-primary transition-colors truncate w-full">{member.name}</h4>
      <p className={`${isBoard || isDirector ? 'text-primary' : 'text-text-meta'} text-[10px] sm:text-xs font-medium ${member.currentJob ? 'mb-0.5' : 'mb-2'} leading-tight opacity-80 truncate w-full`}>{member.role}</p>
      
      {member.currentJob && (
        <p className="text-[10px] sm:text-[11px] text-white/70 mb-1.5 truncate w-full">{member.currentJob}</p>
      )}

      {/* Socials */}
      {member.linkedin && (
        <a 
          href={member.linkedin} 
          target="_blank" 
          rel="noreferrer" 
          onClick={(e) => e.stopPropagation()} 
          className="text-text-meta hover:text-[#0077b5] transition-all duration-300 hover:-translate-y-0.5"
        >
          <Linkedin className="w-4 h-4" />
        </a>
      )}
    </div>
  </div>
);

export default MemberCard;
