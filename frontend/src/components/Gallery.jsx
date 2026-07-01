import React, { useMemo, useState } from 'react';
import { Link } from 'react-router-dom';
import { ArrowRight } from 'lucide-react';
import { useSiteData } from '../context/SiteDataContext';

const INITIAL_GALLERY_LIMIT = 8;
const GALLERY_INCREMENT = 8;
const HEIGHTS = [
  'h-[160px] sm:h-[250px]',
  'h-[220px] sm:h-[320px]',
  'h-[180px] sm:h-[280px]',
  'h-[240px] sm:h-[350px]',
  'h-[150px] sm:h-[220px]',
  'h-[200px] sm:h-[300px]'
];

const Gallery = ({ previewMode = false }) => {
  const { siteData } = useSiteData();
  const galleryItems = siteData?.gallery || [];
  const [visibleCount, setVisibleCount] = useState(INITIAL_GALLERY_LIMIT);
  const shuffledItems = useMemo(() => shuffleGalleryItems(galleryItems), [galleryItems]);

  const displayItems = useMemo(
    () => previewMode ? shuffledItems.slice(0, 3) : shuffledItems.slice(0, visibleCount),
    [shuffledItems, previewMode, visibleCount]
  );
  const hasMoreItems = !previewMode && visibleCount < shuffledItems.length;

  return (
    <section className="py-16 lg:py-24 relative" id="gallery">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex flex-col md:flex-row justify-between items-end mb-16 gap-6">
          <div>
            <h2 className="font-display text-4xl sm:text-5xl font-bold text-white">
              Enerjimizi <span className="text-gradient">karelere</span> sigdirdik
            </h2>
          </div>
        </div>

        <div className="columns-2 md:columns-3 lg:columns-4 gap-2 sm:gap-4 space-y-2 sm:space-y-4">
          {displayItems.map((item, idx) => (
            <GalleryCard key={item.id || item.image_url || idx} item={item} index={idx} />
          ))}

          {previewMode && (
            <Link
              to="/gallery"
              className="relative flex flex-col items-center justify-center rounded-xl sm:rounded-2xl overflow-hidden group bg-surface border border-white/5 hover:border-primary/50 break-inside-avoid w-full h-[150px] sm:h-[220px] transition-all"
            >
              <div className="absolute inset-0 bg-gradient-to-br from-surface to-background/50 z-0"></div>
              <div className="relative z-10 flex flex-col items-center gap-2 sm:gap-3">
                <span className="font-display font-semibold text-sm sm:text-lg text-text-secondary group-hover:text-white transition-colors">Tum Albumu Gor</span>
                <div className="w-10 h-10 sm:w-12 sm:h-12 rounded-full border border-white/10 flex items-center justify-center group-hover:border-primary/50 group-hover:bg-primary/10 transition-colors">
                  <ArrowRight className="w-5 h-5 sm:w-6 sm:h-6 text-text-secondary group-hover:text-primary group-hover:translate-x-1 transition-all" />
                </div>
              </div>
            </Link>
          )}
        </div>

        {hasMoreItems && (
          <div className="flex justify-center mt-10">
            <button
              type="button"
              onClick={() => setVisibleCount((count) => count + GALLERY_INCREMENT)}
              className="px-6 py-3 rounded-full border border-white/10 text-text-secondary hover:text-white hover:border-primary/50 hover:bg-primary/10 transition-colors"
            >
              Daha fazla goster
            </button>
          </div>
        )}
      </div>
    </section>
  );
};

const shuffleGalleryItems = (items) => {
  const shuffled = [...items];
  for (let i = shuffled.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [shuffled[i], shuffled[j]] = [shuffled[j], shuffled[i]];
  }
  return shuffled;
};

const GalleryCard = ({ item, index }) => {
  const itemHeight = HEIGHTS[index % HEIGHTS.length];
  const imageSrc = item.thumb_url || item.image_url;

  return (
    <div
      className={`relative rounded-xl sm:rounded-2xl overflow-hidden group bg-surface border border-white/5 break-inside-avoid w-full [content-visibility:auto] [contain-intrinsic-size:260px] ${itemHeight}`}
    >
      <div className="absolute inset-0 bg-gradient-to-br from-surface to-background/50 z-0"></div>
      {imageSrc ? (
        <img
          src={imageSrc}
          alt={item.title || item.category || 'Galeri gorseli'}
          loading={index < 4 ? 'eager' : 'lazy'}
          decoding="async"
          fetchPriority={index < 2 ? 'high' : 'low'}
          className="absolute inset-0 w-full h-full object-cover z-0 group-hover:scale-110 transition-transform duration-700 opacity-80 group-hover:opacity-100"
        />
      ) : (
        <div className="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnN2ZyI+PGNpcmNsZSBjeD0iMiIgYz0iMiIgcj0iMSIgZmlsbD0icmdiYSgyNTUsMjU1LDI1NSwwLjAzKSIvPjwvc3ZnPg==')] mix-blend-overlay z-0 group-hover:scale-110 transition-transform duration-700"></div>
      )}

      <div className="absolute inset-0 bg-gradient-to-t from-background via-background/40 to-transparent z-10"></div>

      <div className="absolute bottom-0 left-0 w-full p-3 sm:p-6 z-20 translate-y-2 sm:translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
        <span className="inline-block px-1.5 sm:px-2 py-0.5 sm:py-1 rounded bg-primary/20 text-primary text-[8px] sm:text-[10px] font-bold uppercase tracking-wider border border-primary/20 mb-1 sm:mb-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 delay-100">
          {item.category}
        </span>
        <h4 className="text-white font-display font-semibold text-sm sm:text-lg md:text-xl leading-tight">{item.title || item.category}</h4>
      </div>
    </div>
  );
};

export default Gallery;
