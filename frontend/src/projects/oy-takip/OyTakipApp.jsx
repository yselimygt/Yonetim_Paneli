import React, { useEffect } from 'react';
import { Routes, Route, useNavigate, Navigate } from 'react-router-dom';
import { isAuthenticated, hasRole } from './utils/auth';
import { initStorage as initializeStorage } from './utils/storage';

// Pages
import Home from './pages/public/Home';
import Apply from './pages/public/Apply';
import ApplySuccess from './pages/public/ApplySuccess';
import Login from './pages/public/Login';

import AdminDashboard from './pages/admin/AdminDashboard';
import Applications from './pages/admin/Applications';
import Communities from './pages/admin/Communities';
import Rankings from './pages/admin/Rankings';

import CommunityDashboard from './pages/community/CommunityDashboard';
import VoteTracker from './pages/community/VoteTracker';
import Competitors from './pages/community/Competitors';
import VoteHistory from './pages/community/VoteHistory';

// Layouts
import AdminLayout from './components/layout/AdminLayout';
import CommunityLayout from './components/layout/CommunityLayout';

const ProtectedRoute = ({ children, role }) => {
  if (!isAuthenticated()) {
    return <Navigate to="/projects/oy-takip/login" replace />;
  }
  
  if (role && !hasRole(role)) {
    return <Navigate to="/projects/oy-takip" replace />;
  }
  
  return children;
};

const OyTakipApp = () => {
  useEffect(() => {
    initializeStorage();
  }, []);

  return (
    <div className="min-h-screen bg-[#1B1E27] text-white font-sans selection:bg-[#DA2F81]/30 selection:text-white">
      <Routes>
        {/* Public Routes */}
        <Route path="/" element={<Home />} />
        <Route path="/apply" element={<Apply />} />
        <Route path="/success" element={<ApplySuccess />} />
        <Route path="/login" element={<Login />} />

        {/* Admin Routes */}
        <Route path="/admin" element={
          <ProtectedRoute role="super_admin">
            <AdminLayout />
          </ProtectedRoute>
        }>
          <Route index element={<AdminDashboard />} />
          <Route path="applications" element={<Applications />} />
          <Route path="communities" element={<Communities />} />
          <Route path="rankings" element={<Rankings />} />
        </Route>

        {/* Community Routes */}
        <Route path="/community" element={
          <ProtectedRoute role="community_admin">
            <CommunityLayout />
          </ProtectedRoute>
        }>
          <Route index element={<CommunityDashboard />} />
          <Route path="tracker" element={<VoteTracker />} />
          <Route path="competitors" element={<Competitors />} />
          <Route path="history" element={<VoteHistory />} />
        </Route>
      </Routes>
    </div>
  );
};

export default OyTakipApp;
