import React, { createContext, useContext, useState, useEffect, ReactNode } from 'react';
import { getStoredToken, logout as apiLogout } from '../services/auth';

interface AuthContextType {
  isAuthenticated: boolean;
  loading: boolean;
  onLoginSuccess: () => void;
  onLogout: () => Promise<void>;
}

const AuthContext = createContext<AuthContextType>(null!);

export function AuthProvider({ children }: { children: ReactNode }) {
  const [isAuthenticated, setIsAuthenticated] = useState(false);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    (async () => {
      const token = await getStoredToken();
      setIsAuthenticated(!!token);
      setLoading(false);
    })();
  }, []);

  const onLoginSuccess = () => setIsAuthenticated(true);

  const onLogout = async () => {
    try {
      await apiLogout();
    } catch {
      //
    }
    setIsAuthenticated(false);
  };

  return (
    <AuthContext.Provider value={{ isAuthenticated, loading, onLoginSuccess, onLogout }}>
      {children}
    </AuthContext.Provider>
  );
}

export function useAuth() {
  return useContext(AuthContext);
}
