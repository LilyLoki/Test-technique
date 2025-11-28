FROM node:20-alpine AS deps
WORKDIR /usr/src/app

COPY frontend-nextjs/package.json frontend-nextjs/package-lock.json ./

RUN npm ci

FROM node:20-alpine AS builder
WORKDIR /usr/src/app

COPY --from=deps /usr/src/app/node_modules ./node_modules
COPY frontend-nextjs/ ./

RUN npm run build

FROM node:20-alpine AS runner
WORKDIR /usr/src/app

ENV NODE_ENV=production

COPY --from=builder /usr/src/app/.next ./.next
COPY --from=builder /usr/src/app/public ./public
COPY --from=deps /usr/src/app/node_modules ./node_modules
COPY frontend-nextjs/package.json ./

EXPOSE 3000

CMD ["npm", "run", "start"]
