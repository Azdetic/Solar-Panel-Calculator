# SolarSmart — Brighter Indonesia

SolarSmart is a high-fidelity web application designed to help Indonesian building owners transition to solar energy. By bridging the gap between NASA satellite data and everyday users, it provides accurate, location-specific estimates for solar panel installation feasibility, costs, and long-term benefits.

---

## 🌟 Key Features

- **Interactive Solar Simulator**: Pin-drop your exact location on a map to get accurate solar irradiance data.
- **NASA POWER Integration**: Fetches real-time multi-year average solar radiation data from NASA satellites for your specific coordinates.
- **Economic Analysis**: Calculates total investment costs, monthly savings, and your estimated payback period (ROI).
- **Environmental Impact**: See exactly how much CO2 emissions your building can avoid by switching to solar.
- **Premium UI/UX**: Built with a "hand-crafted" minimalist aesthetic using modern design tokens and utility-first styling.

---

## 🛠️ Tech Stack

- **Framework**: [Laravel 12](https://laravel.com/)
- **Frontend Stack**: [Livewire 4](https://livewire.laravel.com/) + [Alpine.js](https://alpinejs.dev/)
- **Styling**: [Tailwind CSS v4](https://tailwindcss.com/)
- **Mapping**: [Leaflet.js](https://leafletjs.com/) + OpenStreetMap
- **Data Source**: [NASA POWER API](https://power.larc.nasa.gov/)
- **Icons**: [Google Material Symbols](https://fonts.google.com/icons)

---

## 🚀 Getting Started

### Prerequisites

- PHP 8.2+
- Composer
- Node.js & NPM
- MySQL/MariaDB

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/Azdetic/Solar-Panel-Calculator.git
   cd Solar-Panel-Calculator
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Configuration**
   Update your `.env` file with your database credentials, then run:
   ```bash
   php artisan migrate --seed
   ```

5. **Start Development Server**
   ```bash
   # Terminal 1
   php artisan serve

   # Terminal 2
   npm run dev
   ```

---

## 👥 The Team

SolarSmart was built as a final project for the **Proyek Perangkat Lunak** course at **Telkom University** by:

- **Wira**
- **Khai**
- **Gia**

---

## 📄 License

This project was built for educational purposes as part of the PPL Github project.
