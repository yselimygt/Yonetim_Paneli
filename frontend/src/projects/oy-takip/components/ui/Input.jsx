import React, { forwardRef } from 'react';

const Input = forwardRef(({ label, error, className = '', ...props }, ref) => {
  return (
    <div className={`w-full ${className}`}>
      {label && <label className="block text-sm font-medium text-gray-300 mb-1">{label}</label>}
      <input
        ref={ref}
        className={`w-full bg-[#0d0a1a]/50 border ${error ? 'border-red-500' : 'border-white/10'} rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:outline-none focus:border-[#f49f20] focus:ring-1 focus:ring-[#f49f20] transition-colors`}
        {...props}
      />
      {error && <span className="text-red-400 text-xs mt-1 block">{error}</span>}
    </div>
  );
});

Input.displayName = 'Input';
export default Input;
