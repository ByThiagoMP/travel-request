# Etapa 1: build
FROM node:20-alpine as builder

WORKDIR /app
COPY . .
# Copia o .env local para dentro do container
COPY .env.example .env

COPY package*.json ./
RUN npm install

COPY . .

RUN npm run build


# Etapa 2: servir com nginx
FROM nginx:stable-alpine

# Copia os arquivos buildados da etapa anterior
COPY --from=builder /app/dist /usr/share/nginx/html

# Remove a configuração default do nginx e usa a nossa (opcional)
COPY nginx.conf /etc/nginx/conf.d/default.conf

# Expõe a porta
EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]
