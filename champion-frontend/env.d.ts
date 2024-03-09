/// <reference types="vite/client" />

interface ImportMeta {
  env: {
    VITE_INTERNAL_URL: string
    VITE_EXTERNAL_URL: string
    VITE_ACCESS_TOKEN_COOKIE: string
    VITE_REFRESH_TOKEN_COOKIE: string
  };
}