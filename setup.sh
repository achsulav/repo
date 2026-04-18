#!/bin/bash

# Blogify One-Command Setup Script

echo "🚀 Starting Blogify setup..."

# 1. Generate SSL Certificates if they don't exist
if [ ! -f app/SSL/_wildcard.blogify.dev+1.pem ]; then
    echo "🔐 Generating SSL certificates..."
    mkdir -p app/SSL
    mkcert -cert-file app/SSL/_wildcard.blogify.dev+1.pem -key-file app/SSL/_wildcard.blogify.dev+1-key.pem "*.blogify.dev" blogify.dev
else
    echo "✅ SSL certificates already exist."
fi

# 2. Start Docker Containers
echo "🐳 Starting Docker containers..."
docker-compose up -d

# 3. Wait for DB to be ready
echo "⏳ Waiting for database to be ready..."
sleep 10

# 4. Run Migrations and Seeds
echo "📂 Setting up database schema..."
docker exec -it blogify_php php migrate.php
docker exec -it blogify_php php seed_categories.php

echo "✨ Setup complete! Access your project at https://blogify.dev or http://localhost"
