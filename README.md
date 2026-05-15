# Sistema de Gestión y Seguimiento "Mundo Inclusivo" - Cáritas Coroico ♿🌐

Este proyecto es una solución integral (Web y Móvil) diseñada para digitalizar y optimizar la gestión de beneficiarios con discapacidad. Desarrollado como Proyecto de Grado para la **Universidad Mayor de San Andrés (UMSA)**, el sistema permite centralizar la información, automatizar reportes y realizar seguimientos en zonas rurales sin conexión a internet.

## 🚀 Características Principales

* **Gestión Centralizada (CRUD):** Registro completo de beneficiarios, tutores e instituciones.
* **Sincronización Offline-First:** Aplicación móvil que permite el registro de datos en campo sin internet y sincronización automática posterior.
* **Generación de Reportes:** Creación instantánea de reportes en PDF y Excel, reduciendo el tiempo administrativo de 30 días a solo segundos.
* **Arquitectura Robusta:** Implementación del patrón Modelo-Vista-Controlador (MVC).
* **Seguridad:** Sistema de autenticación con roles de usuario y encriptación de datos sensibles.

## 🛠️ Stack Tecnológico

### Backend & Web
* **Framework:** Laravel 10
* **Lenguaje:** PHP 8.2 / 8.3
* **Motor de Plantillas:** Blade
* **Base de Datos:** MySQL / MariaDB

### Mobile
* **Lenguaje:** Java
* **Entorno:** Android Studio
* **Base de Datos Local:** SQLite (para soporte offline)

### Metodologías y Estándares
* **Gestión de Proyecto:** SCRUM (Metodología Ágil)
* **Ingeniería Web:** UWE (UML-based Web Engineering)
* **Calidad de Software:** ISO/IEC 9126

## 📋 Requisitos de Instalación

1.  Clonar el repositorio: `git clone https://github.com/hansel-bustamante/mundo-inclusivo.git`
2.  Instalar dependencias de PHP: `composer install`
3.  Configurar el archivo `.env` con las credenciales de base de datos.
4.  Ejecutar las migraciones: `php artisan migrate`
5.  Iniciar el servidor local: `php artisan serve`

---
**Autor:** Hansel Alain Bustamante Callisaya  
*Ingeniero de Sistemas Informáticos - UMSA*
