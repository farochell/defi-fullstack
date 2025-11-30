#!/bin/bash

# Créer le dossier certs s'il n'existe pas
mkdir -p ./certs

# Générer le certificat wildcard pour *.defifullstack.com
openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
  -keyout ./certs/wildcard.defifullstack.com.key \
  -out ./certs/wildcard.defifullstack.com.crt \
  -subj "/C=CA/ST=Quebec/L=Montreal/O=Dev/CN=*.defifullstack.com" \
  -addext "subjectAltName=DNS:*.defifullstack.com,DNS:defifullstack.com"

echo "✅ Certificat wildcard généré dans ./certs"
echo "📝 N'oubliez pas d'ajouter les domaines à votre /etc/hosts"