#!/bin/sh

# Aguarda o MySQL subir
echo "⏳ Aguardando o MySQL subir..."
# Espera o MySQL ficar disponível usando PHP
until php -r 'exit(@fsockopen("db", 3306) ? 0 : 1);'; do
  echo "Aguardando MySQL..."
  sleep 2
done

echo "MySQL está pronto!"

# Copia o .env se não existir
if [ ! -f .env ]; then
  echo "Copiando .env.example para .env"
  cp .env.example .env
fi

# Instala dependências se não existirem
if [ ! -d vendor ]; then
  echo "Instalando dependências"
  composer install
fi

echo "Verificando se a chave da aplicação existe..."
# Gera chave da app
php artisan key:generate

# Gera JWT_SECRET (se ainda não existir)
if ! grep -q "JWT_SECRET=" .env; then
  echo "Gerando JWT_SECRET"
  php artisan jwt:secret --force
fi

# Roda migrations + seeders
echo "Rodando migrations e seeders"
php artisan migrate --seed

echo "Iniciando servidor Laravel..."
# Inicia o servidor Laravel
php artisan serve --host=0.0.0.0 --port=8000
