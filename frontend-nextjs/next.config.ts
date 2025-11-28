import type { NextConfig } from 'next'

const nextConfig: NextConfig = {
  reactStrictMode: true,
  env: {
    NEXT_PUBLIC_BASE_URL:
      process.env.NEXT_PUBLIC_BASE_URL || 'http://localhost:8000',
    NEXT_SERVER_BASE_URL:
      process.env.NEXT_SERVER_BASE_URL || 'http://localhost:8000',
  },
}

export default nextConfig
