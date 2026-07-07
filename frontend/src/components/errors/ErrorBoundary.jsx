import React from 'react';
import ErrorPage from './ErrorPage';

class ErrorBoundary extends React.Component {
  constructor(props) {
    super(props);
    this.state = { hasError: false };
  }

  static getDerivedStateFromError(error) {
    // Update state so the next render will show the fallback UI.
    return { hasError: true };
  }

  componentDidCatch(error, errorInfo) {
    // You can also log the error to an error reporting service here
    console.error("ErrorBoundary caught an error:", error, errorInfo);
  }

  render() {
    if (this.state.hasError) {
      // You can render any custom fallback UI
      return (
        <ErrorPage 
          errorCode="500"
          title="Bir Şeyler Ters Gitti"
          description="Sistemde beklenmeyen bir arıza oluştu. Innox ve ekibimiz bunu çözmek için çalışıyor!"
          mascotUrl="/innomis/assets/innox/merak-eden-innox.svg"
          actionButton={
            <button 
              onClick={() => window.location.reload()}
              className="px-8 py-3 bg-gradient-primary text-white rounded-xl font-bold text-base shadow-[0_0_20px_rgba(244,159,32,0.3)] hover:shadow-[0_0_30px_rgba(218,44,46,0.5)] hover:-translate-y-1 transition-all inline-flex items-center gap-2"
            >
              Sayfayı Yenile
            </button>
          }
        />
      );
    }

    return this.props.children; 
  }
}

export default ErrorBoundary;
