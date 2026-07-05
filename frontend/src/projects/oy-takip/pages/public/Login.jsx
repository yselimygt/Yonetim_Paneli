import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { getStorage } from '../../utils/storage';
import { login } from '../../utils/auth';
import Input from '../../components/ui/Input';
import Button from '../../components/ui/Button';

const Login = () => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const navigate = useNavigate();

  const handleLogin = (e) => {
    e.preventDefault();
    const users = getStorage('users');
    const user = users.find(u => u.email === email && u.password === password);
    
    if (user) {
      login(user);
      if (user.role === 'super_admin') navigate('/projects/oy-takip/admin');
      else if (user.role === 'community_admin') navigate('/projects/oy-takip/community');
      else navigate('/projects/oy-takip');
    } else {
      setError('E-posta veya şifre hatalı. (Örn admin@demo.com / admin veya topluluk@demo.com / demo)');
    }
  };

  return (
    <div className="min-h-screen flex items-center justify-center p-6">
      <div className="w-full max-w-md bg-white/[0.02] border border-white/10 rounded-2xl p-8 backdrop-blur-md">
        <h2 className="text-2xl font-bold mb-6 text-center">Giriş Yap</h2>
        {error && <div className="mb-4 p-3 bg-red-500/20 text-red-400 rounded-lg text-sm text-center">{error}</div>}
        <form onSubmit={handleLogin} className="space-y-4">
          <Input 
            label="E-posta" 
            type="email" 
            value={email} 
            onChange={(e) => setEmail(e.target.value)} 
            required 
            placeholder="admin@demo.com"
          />
          <Input 
            label="Şifre" 
            type="password" 
            value={password} 
            onChange={(e) => setPassword(e.target.value)} 
            required 
            placeholder="admin123"
          />
          <Button type="submit" className="w-full mt-6">Giriş Yap</Button>
        </form>
      </div>
    </div>
  );
};

export default Login;
