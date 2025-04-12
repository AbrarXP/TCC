FROM node:18

WORKDIR /app

# HANYA salin file package*.json dulu
COPY package*.json ./

# Jalankan npm install DI DALAM CONTAINER
RUN npm install

# Baru salin semua file lain
COPY . .

# Port dan command
EXPOSE 5000
CMD ["npm", "start"]
