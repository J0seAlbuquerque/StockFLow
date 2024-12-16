# Escolha da imagem base, PHP com Apache
FROM php:8.1-apache

# Instalar dependências do PHP (exemplo: MySQLi)
RUN docker-php-ext-install mysqli

# Habilitar o mod_rewrite do Apache para URLs amigáveis
RUN a2enmod rewrite

# Copiar os arquivos do projeto para o contêiner
COPY . /var/www/html/StockFLow

# Definir as permissões corretas (se necessário)
RUN chown -R www-data:www-data /var/www/html/StockFLow

# Expor a porta 80 para o Apache
EXPOSE 80

# Definir o comando de inicialização do Apache
CMD ["apache2-foreground"]