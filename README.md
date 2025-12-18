# 📚 Documentation du Projet Laravel 11

## 📋 Description
Projet Laravel avec un service de calcul de commandes et des tests unitaires et fonctionnels complets.

---

<p align="center">
  <img src="assets/images/logo.png" width="150" alt="Logo du projet">
</p>

## 🚀 Installation Rapide

### 1️⃣ Prérequis
- PHP 8.2 ou plus
- Composer
- Git

### 2️⃣ Installation du projet
```bash
git clone https://github.com/ayoubaguezar/laravel-tests-unitaires.git
cd laravel-tests-unitaires
composer install
cp .env.example .env
php artisan key:generate
```

---

## 🧪 Tests

```bash
# Exécuter tous les tests
php artisan test

# Exécuter uniquement les tests unitaires
php artisan test --testsuite=Unit

# Exécuter uniquement les tests API
php artisan test --testsuite=Feature
```

---

## 📡 API Endpoint

### POST `/api/order/total`
Calcule le total d'une commande avec taxe.

### Exemple de requête
```json
{
  "items": [
    { "price": 100, "quantity": 2 },
    { "price": 50, "quantity": 1 }
  ],
  "tax_rate": 20
}
```

### Réponse réussie
```json
{
  "success": true,
  "data": {
    "total": 240,
    "tax_rate": 20,
    "items_count": 2,
    "currency": "EUR"
  }
}
```

---

## 📁 Structure des Fichiers

```text
app/
├── Services/
│   └── OrderService.php
└── Http/
    └── Controllers/
        └── OrderController.php

tests/
├── Unit/
│   └── OrderServiceTest.php
└── Feature/
    └── OrderApiTest.php
```

---

## 🔧 Fonctionnalités

### 🧮 Service de Calcul
- calculateTotal()
- calculateTotalWithTax()
- Validation des données
- Gestion des erreurs

### ✅ Tests
- Tests de calcul
- Tests de validation
- Tests de gestion des erreurs
- Tests de performance

---

## 🛠 Commandes Utiles

```bash
php artisan serve
php artisan route:list
php artisan test --coverage
```

---

## 📞 Contact
**Ayoub Aguezar**  
