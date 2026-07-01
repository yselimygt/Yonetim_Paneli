import React from 'react';

const LegalLayout = ({ title, lastUpdated, children }) => {
  return (
    <div className="min-h-screen bg-background relative overflow-hidden pt-12 pb-24">
      {/* Decorative Glows */}
      <div className="absolute top-0 right-0 w-1/3 h-96 bg-primary/10 blur-[120px] rounded-full pointer-events-none"></div>
      <div className="absolute bottom-0 left-0 w-1/3 h-96 bg-accent-cyan/10 blur-[120px] rounded-full pointer-events-none"></div>

      <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div className="mb-12">
          <h1 className="font-display text-4xl md:text-5xl font-bold text-white mb-4">
            {title}
          </h1>
          {lastUpdated && (
            <p className="text-text-meta text-sm">
              Son Güncelleme Tarihi: <span className="text-white">{lastUpdated}</span>
            </p>
          )}
        </div>

        <div className="glass-card p-6 md:p-10 rounded-2xl sm:rounded-3xl border-white/5">
          <div className="prose prose-invert prose-p:text-text-secondary prose-headings:text-white prose-a:text-primary hover:prose-a:text-white prose-strong:text-white max-w-none">
            {children}
          </div>
        </div>
      </div>
    </div>
  );
};

export default LegalLayout;
