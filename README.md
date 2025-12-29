# Sales Dashboard

A modern sales dashboard built with PHP, Tailwind CSS, and Chart.js.

## Features

- Real-time sales metrics
- Rolling 12 months trend visualization
- Month-to-date performance tracking
- Responsive design

## Setup

1. Clone the repository
2. Copy `.env.example` to `.env` and configure your database credentials
3. Install dependencies: `npm install`
4. Build CSS: `npm run build` or `npm run watch` for development
5. Configure your web server to point to the `public` directory

## Requirements

- PHP 7.4+ with SQL Server PDO driver (`php_pdo_sqlsrv`)
- MS SQL Server Express (or any SQL Server edition)
- Node.js and npm (for Tailwind CSS)

## Database Setup (SQL Server Express)

### SQL Server Express Connection Options

**Option 1: Named Instance (Default)**
```
DB_SERVER=localhost\SQLEXPRESS
DB_PORT=1433
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
DB_TRUSTED_CONNECTION=false
```

**Option 2: Windows Authentication**
```
DB_SERVER=localhost\SQLEXPRESS
DB_PORT=1433
DB_DATABASE=your_database_name
DB_TRUSTED_CONNECTION=true
```

**Option 3: Remote Server**
```
DB_SERVER=192.168.1.100\SQLEXPRESS
DB_PORT=1433
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
DB_TRUSTED_CONNECTION=false
```

### Enabling SQL Server Express for Remote Connections

1. Open **SQL Server Configuration Manager**
2. Enable **TCP/IP** protocol
3. Enable **SQL Server Browser** service
4. Configure Windows Firewall to allow port 1433
5. Restart SQL Server service

## Development

```bash
npm run watch  # Watch for CSS changes
```


