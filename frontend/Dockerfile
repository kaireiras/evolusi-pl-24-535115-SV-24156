# STAGE 1: Build Vue Source Code
FROM node:20-alpine AS builder

WORKDIR /app

COPY package*.json ./
RUN npm ci

COPY . .
RUN npm run build

# STAGE 2: Production Nginx Server
FROM nginx:1.27-alpine

# Salin hasil build dist ke Nginx web root
COPY --from=builder /app/dist /usr/share/nginx/html

# Salin konfigurasi Nginx SPA
COPY nginx.conf /etc/nginx/conf.d/default.conf

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]