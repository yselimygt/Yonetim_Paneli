import React, { useState, useEffect } from 'react';
import { ArrowRight, MapPin, Calendar as CalendarIcon, Clock, X, ExternalLink, Loader2 } from 'lucide-react';
import { getEvents } from '../services/siteApi';
const CountdownTimer = ({ targetDate }) => {
  const [timeLeft, setTimeLeft] = useState({ days: 0, hours: 0, minutes: 0, seconds: 0 });

  useEffect(() => {
    const timer = setInterval(() => {
      const difference = new Date(targetDate) - new Date();
      if (difference > 0) {
        setTimeLeft({
          days: Math.floor(difference / (1000 * 60 * 60 * 24)),
          hours: Math.floor((difference / (1000 * 60 * 60)) % 24),
          minutes: Math.floor((difference / 1000 / 60) % 60),
          seconds: Math.floor((difference / 1000) % 60)
        });
      } else {
        clearInterval(timer);
      }
    }, 1000);
    return () => clearInterval(timer);
  }, [targetDate]);

  return (
    <div className="flex gap-2 sm:gap-4 items-center justify-center md:justify-start mt-4 sm:mt-6">
      {Object.entries(timeLeft).map(([unit, value]) => (
        <div key={unit} className="flex flex-col items-center">
          <div className="w-12 h-12 sm:w-20 sm:h-20 bg-white/10 backdrop-blur-md border border-white/20 rounded-xl sm:rounded-2xl flex items-center justify-center text-xl sm:text-3xl font-display font-bold text-white mb-1 sm:mb-2 shadow-lg">
            {value.toString().padStart(2, '0')}
          </div>
          <span className="text-[9px] sm:text-xs uppercase tracking-wider text-text-secondary font-medium">
            {unit === 'days' ? 'Gün' : unit === 'hours' ? 'Saat' : unit === 'minutes' ? 'Dk' : 'Sn'}
          </span>
        </div>
      ))}
    </div>
  );
};

const EventModal = ({ event, onClose }) => {
  if (!event) return null;

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6">
      <div className="absolute inset-0 bg-background/80 backdrop-blur-sm" onClick={onClose}></div>
      <div className="relative bg-surface border border-white/10 rounded-2xl sm:rounded-3xl w-full max-w-4xl max-h-[90vh] overflow-y-auto shadow-2xl animate-fade-in-up scrollbar-hide">
        {/* Close Button */}
        <button 
          onClick={onClose}
          className="absolute top-4 right-4 z-30 w-10 h-10 bg-background/50 backdrop-blur-md rounded-full border border-white/10 flex items-center justify-center text-white hover:bg-white/10 transition-colors"
        >
          <X className="w-5 h-5" />
        </button>

        {/* Hero Section */}
        <div className="h-48 sm:h-80 relative overflow-hidden rounded-t-xl sm:rounded-t-3xl">
          <div className="absolute inset-0 bg-gradient-to-t from-surface via-surface/50 to-transparent z-10"></div>
          {event.image ? (
            <img src={event.image} alt={event.title} className="w-full h-full object-cover" />
          ) : (
             <div className="w-full h-full bg-gradient-to-br from-primary/20 to-accent-purple/20"></div>
          )}
          <div className="absolute bottom-0 left-0 p-4 sm:p-10 z-20 w-full">
            <span className={`inline-block px-2 sm:px-3 py-1 rounded-full text-[10px] sm:text-xs font-bold uppercase tracking-wider border backdrop-blur-md mb-2 sm:mb-4 ${event.tagColor}`}>
              {event.category}
            </span>
            <h2 className="text-2xl sm:text-5xl font-display font-bold text-white mb-1 sm:mb-2 leading-tight">{event.title}</h2>
          </div>
        </div>

        {/* Content Section */}
        <div className="p-4 sm:p-10 grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-10">
          <div className="lg:col-span-2">
            <h3 className="text-lg sm:text-xl font-bold text-white mb-2 sm:mb-4">Etkinlik Hakkında</h3>
            <p className="text-sm sm:text-base text-text-secondary leading-relaxed mb-6 sm:mb-8">{event.desc}</p>
            
            {event.agenda && event.agenda.length > 0 && (
              <>
                <h3 className="text-xl font-bold text-white mb-4">Program</h3>
                <div className="space-y-4 mb-8">
                  {event.agenda.map((item, i) => (
                    <div key={i} className="flex items-start gap-4 p-4 rounded-xl bg-white/5 border border-white/5">
                      <div className="flex items-center gap-2 text-primary font-medium shrink-0">
                        <Clock className="w-4 h-4" />
                        {item.time}
                      </div>
                      <div className="text-white">{item.activity}</div>
                    </div>
                  ))}
                </div>
              </>
            )}

            {event.speakers && event.speakers.length > 0 && (
              <>
                <h3 className="text-xl font-bold text-white mb-4">Konuşmacılar</h3>
                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  {event.speakers.map((speaker, i) => (
                    <div key={i} className="flex items-center gap-4 p-4 rounded-xl bg-white/5 border border-white/5">
                      <div className="w-12 h-12 rounded-full bg-gradient-to-br from-primary to-secondary flex items-center justify-center text-white font-bold text-lg shrink-0">
                        {speaker.name.charAt(0)}
                      </div>
                      <div>
                        <div className="font-bold text-white">{speaker.name}</div>
                        <div className="text-sm text-text-secondary">{speaker.role}</div>
                      </div>
                    </div>
                  ))}
                </div>
              </>
            )}
          </div>

          <div className="lg:col-span-1">
            <div className="glass-card p-6 rounded-2xl lg:sticky lg:top-6">
              <h3 className="text-lg font-bold text-white mb-6">Etkinlik Detayları</h3>
              <div className="space-y-4 mb-8">
                <div className="flex items-start gap-3">
                  <CalendarIcon className="w-5 h-5 text-primary shrink-0" />
                  <div>
                    <div className="text-sm text-text-secondary mb-1">Tarih</div>
                    <div className="font-medium text-white">{event.date}</div>
                  </div>
                </div>
                {event.location && (
                  <div className="flex items-start gap-3">
                    <MapPin className="w-5 h-5 text-primary shrink-0" />
                    <div>
                      <div className="text-sm text-text-secondary mb-1">Konum</div>
                      {event.locationLink ? (
                        <a href={event.locationLink} target="_blank" rel="noopener noreferrer" className="font-medium text-white hover:text-primary transition-colors hover:underline">
                          {event.location}
                        </a>
                      ) : (
                        <div className="font-medium text-white">{event.location}</div>
                      )}
                    </div>
                  </div>
                )}
              </div>
              
              {event.status === 'upcoming' && (
                <a 
                  href={event.registrationLink || '#'} 
                  className="w-full flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-primary to-secondary text-white rounded-xl font-bold hover:shadow-lg hover:shadow-primary/25 transition-all"
                >
                  Kayıt Ol <ExternalLink className="w-4 h-4" />
                </a>
              )}
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

const EventCard = ({ event, onOpenModal }) => (
  <div 
    onClick={() => onOpenModal(event)}
    className="glass-card rounded-2xl overflow-hidden group hover:-translate-y-2 transition-all duration-300 hover:border-primary/30 flex flex-col h-full cursor-pointer"
  >
    {/* Image Container */}
    <div className="h-40 sm:h-56 w-full relative overflow-hidden">
      <div className="absolute inset-0 bg-gradient-to-t from-background/90 to-transparent z-10"></div>
      {event.image ? (
        <img 
          src={event.image} 
          alt={event.title} 
          className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
        />
      ) : (
        <div className="w-full h-full bg-gradient-to-br from-surface to-background/50 flex items-center justify-center">
          <CalendarIcon className="w-8 sm:w-12 h-8 sm:h-12 text-white/20" />
        </div>
      )}
      
      {/* Category Tag */}
      <div className="absolute top-3 sm:top-4 left-3 sm:left-4 z-20">
        <span className={`px-2 sm:px-3 py-1 rounded-full text-[10px] sm:text-xs font-bold uppercase tracking-wider border backdrop-blur-md ${event.tagColor}`}>
          {event.category}
        </span>
      </div>
    </div>

    {/* Content */}
    <div className="p-4 sm:p-6 flex flex-col flex-grow relative z-20">
      <div className="flex flex-wrap items-center gap-3 sm:gap-4 mb-3 sm:mb-4 text-[13px] sm:text-sm text-text-main">
        <div className="flex items-center gap-1.5">
          <CalendarIcon className="w-4 h-4 text-primary" />
          <span className="font-medium">{event.date}</span>
        </div>
        {event.location && (
          <div className="flex items-center gap-1.5">
            <MapPin className="w-4 h-4 text-primary" />
            {event.locationLink ? (
              <a 
                href={event.locationLink} 
                target="_blank" 
                rel="noopener noreferrer" 
                className="font-medium hover:text-primary transition-colors hover:underline"
                onClick={(e) => e.stopPropagation()}
              >
                {event.location}
              </a>
            ) : (
              <span className="font-medium">{event.location}</span>
            )}
          </div>
        )}
      </div>
      
      <h3 className="text-xl sm:text-2xl font-display font-bold text-white mb-2 sm:mb-3 group-hover:text-primary transition-colors">
        {event.title}
      </h3>
      
      <p className="text-text-secondary text-[13px] sm:text-sm leading-relaxed mb-4 sm:mb-6 flex-grow line-clamp-2">
        {event.desc}
      </p>
      
      {/* Action Button */}
      <button className="flex items-center gap-2 text-white font-semibold text-sm hover:text-primary transition-colors group/btn mt-auto">
        Detayları Gör
        <ArrowRight className="w-4 h-4 group-hover/btn:translate-x-1 transition-transform" />
      </button>
    </div>
  </div>
);

const Events = () => {
  const [activeTab, setActiveTab] = useState('upcoming');
  const [selectedCategory, setSelectedCategory] = useState('Tümü');
  const [selectedEvent, setSelectedEvent] = useState(null);
  const [events, setEvents] = useState([]);
  const [loading, setLoading] = useState(true);

  // Fetch events from API
  useEffect(() => {
    const fetchEvents = async () => {
      try {
        const result = await getEvents();
        if (result.success) {
          setEvents(result.data);
        }
      } catch (error) {
        console.error('Failed to fetch events:', error);
      } finally {
        setLoading(false);
      }
    };

    fetchEvents();
  }, []);

  // Prevent scrolling when modal is open
  useEffect(() => {
    if (selectedEvent) {
      document.body.style.overflow = 'hidden';
    } else {
      document.body.style.overflow = 'unset';
    }
  }, [selectedEvent]);

  // Extract unique categories
  const allCategories = ['Tümü', ...new Set(events.map(e => e.category))];

  const filteredEvents = events.filter(event => 
    event.status === activeTab && 
    (selectedCategory === 'Tümü' || event.category === selectedCategory)
  );

  // Find next big upcoming event with a targetDate
  const nextBigEvent = events.find(e => e.status === 'upcoming' && e.targetDate);

  if (loading) {
    return (
      <section className="py-16 lg:py-24 relative overflow-hidden" id="events">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 flex flex-col items-center justify-center min-h-[400px]">
          <Loader2 className="w-12 h-12 text-primary animate-spin mb-4" />
          <p className="text-text-secondary">Etkinlikler yükleniyor...</p>
        </div>
      </section>
    );
  }

  return (
    <section className="py-16 lg:py-24 relative overflow-hidden" id="events">
      {/* Modal Portal */}
      <EventModal event={selectedEvent} onClose={() => setSelectedEvent(null)} />

      {/* Background elements */}
      <div className="absolute right-0 top-1/4 w-96 h-96 bg-accent-cyan/10 blur-[120px] rounded-full pointer-events-none"></div>

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div className="text-center mb-8 sm:mb-12">
          <h2 className="font-display text-3xl sm:text-5xl font-bold leading-tight text-white mb-3 sm:mb-6">
            Ekosistemi <span className="text-gradient">bir araya getiren</span> buluşmalar
          </h2>
          <p className="text-text-secondary text-base sm:text-lg max-w-2xl mx-auto px-2">
            Teknoloji, kariyer, girişimcilik ve inovasyon ekosistemini öğrencilerle buluşturan etkinlik deneyimleri.
          </p>
        </div>

        {/* Tab Switcher */}
        <div className="flex justify-center mb-8 sm:mb-12">
          <div className="inline-flex bg-white/5 p-1 rounded-full border border-white/10 w-full sm:w-auto overflow-hidden">
            <button 
              onClick={() => {
                setActiveTab('upcoming');
                setSelectedCategory('Tümü');
              }}
              className={`flex-1 sm:flex-none px-4 sm:px-10 py-2.5 sm:py-3 text-[13px] sm:text-base font-semibold rounded-full transition-all duration-300 whitespace-nowrap ${activeTab === 'upcoming' ? 'bg-gradient-to-r from-primary to-secondary text-white shadow-lg' : 'text-text-secondary hover:text-white hover:bg-white/5'}`}
            >
              Yaklaşan Etkinlikler
            </button>
            <button 
              onClick={() => {
                setActiveTab('past');
                setSelectedCategory('Tümü');
              }}
              className={`flex-1 sm:flex-none px-4 sm:px-10 py-2.5 sm:py-3 text-[13px] sm:text-base font-semibold rounded-full transition-all duration-300 whitespace-nowrap ${activeTab === 'past' ? 'bg-gradient-to-r from-primary to-secondary text-white shadow-lg' : 'text-text-secondary hover:text-white hover:bg-white/5'}`}
            >
              Geçmiş Etkinlikler
            </button>
          </div>
        </div>

        {/* Category Filters */}
        <div className="flex flex-wrap justify-center gap-2 sm:gap-3 mb-8 sm:mb-12 animate-fade-in-up px-2" style={{ animationDelay: '0.1s' }}>
          {allCategories.map(cat => (
            <button 
              key={cat}
              onClick={() => setSelectedCategory(cat)}
              className={`px-4 sm:px-5 py-1.5 sm:py-2 rounded-full text-[13px] sm:text-sm font-medium transition-all ${selectedCategory === cat ? 'bg-white text-background shadow-lg shadow-white/10' : 'glass-card text-text-secondary hover:text-white hover:border-white/20'}`}
            >
              {cat}
            </button>
          ))}
        </div>

        {/* Countdown Timer for Next Big Event (Only shown on 'upcoming' and 'Tümü' tab) */}
        {activeTab === 'upcoming' && selectedCategory === 'Tümü' && nextBigEvent && (
          <div className="mb-10 sm:mb-16 p-6 sm:p-12 glass-card rounded-2xl sm:rounded-3xl border border-primary/20 relative overflow-hidden group animate-fade-in-up" style={{ animationDelay: '0.2s' }}>
            <div className="absolute inset-0 bg-gradient-to-br from-primary/10 via-transparent to-accent-purple/10 z-0"></div>
            <div className="relative z-10 flex flex-col md:flex-row items-center gap-6 sm:gap-8 justify-between">
              <div className="text-center md:text-left w-full md:w-auto">
                <span className="inline-block px-2 sm:px-3 py-1 rounded-full bg-primary/20 text-primary text-[10px] sm:text-xs font-bold uppercase tracking-wider mb-3 sm:mb-4 border border-primary/20">
                  Sıradaki Büyük Zirve
                </span>
                <h3 className="text-2xl sm:text-5xl font-display font-bold text-white mb-2">{nextBigEvent.title}</h3>
                <p className="text-text-secondary text-sm sm:text-lg mb-2">{nextBigEvent.location} • {nextBigEvent.date}</p>
                <CountdownTimer targetDate={nextBigEvent.targetDate} />
              </div>
              <div className="shrink-0 w-full md:w-auto mt-4 sm:mt-6 md:mt-0">
                <button 
                  onClick={() => setSelectedEvent(nextBigEvent)} 
                  className="w-full md:w-auto px-6 sm:px-8 py-3 sm:py-4 bg-white text-background rounded-xl font-bold text-sm sm:text-base hover:bg-primary hover:text-white transition-colors shadow-xl"
                >
                  Etkinliği İncele
                </button>
              </div>
            </div>
          </div>
        )}

        {/* Events Grid */}
        <div className="animate-fade-in-up" style={{ animationDelay: '0.3s' }}>
          {filteredEvents.length > 0 ? (
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
              {filteredEvents.map((event, idx) => (
                <EventCard key={idx} event={event} onOpenModal={setSelectedEvent} />
              ))}
            </div>
          ) : (
            <div className="text-center py-16 glass-card rounded-2xl">
              <CalendarIcon className="w-12 h-12 text-white/20 mx-auto mb-4" />
              <h3 className="text-xl font-display font-bold text-white mb-2">Bu kategoriye ait etkinlik bulunmuyor.</h3>
              <p className="text-text-secondary">Farklı bir kategori seçebilir veya daha sonra tekrar kontrol edebilirsiniz.</p>
            </div>
          )}
        </div>

      </div>
    </section>
  );
};

export default Events;
