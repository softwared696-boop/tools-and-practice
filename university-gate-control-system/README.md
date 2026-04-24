# University Gate Control System

A comprehensive, production-ready university gate security management platform built with HTML, CSS, JavaScript, PHP, and MySQL.

## Features

### Core Modules
- **People Access Control**: Student, staff, and visitor entry/exit management
- **Material Movement Tracking**: Track laptops, equipment, and university assets
- **Visitor Management**: Temporary passes and QR code generation
- **Security Inspection**: Bag checks and prohibited item detection
- **Incident Management**: Report, track, and resolve security incidents
- **Reports & Analytics**: Comprehensive reporting system

### User Roles
1. Gate Officers / Security Guards
2. Admin
3. Main Admin
4. Students
5. Staff
6. Visitor Registration Officers

### Key Features
- Role-based access control
- Real-time monitoring
- Dark/Light theme support
- Fully responsive design
- QR code scanning
- Material permission workflow
- Incident escalation system
- Comprehensive audit trails

## Technology Stack

- **Frontend**: HTML5, CSS3, Vanilla JavaScript
- **Backend**: Core PHP (No frameworks)
- **Database**: MySQL 5.7+

## Installation

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server

### Setup Steps

1. Clone or copy the project to your web server:
```bash
cp -r university-gate-control-system /var/www/html/
```

2. Create the database:
```bash
mysql -u root -p < database/schema.sql
```

3. Configure database connection in `config/db.php`:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'university_gate_control');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
```

4. Set proper permissions:
```bash
chmod -R 755 /var/www/html/university-gate-control-system
chmod -R 777 /var/www/html/university-gate-control-system/assets/uploads
chmod -R 777 /var/www/html/university-gate-control-system/logs
```

5. Access the application:
```
http://localhost/university-gate-control-system
```

### Default Login Credentials
- **Username**: admin
- **Password**: admin123
- **Role**: Main Admin

⚠️ **Important**: Change the default password immediately after first login!

## Project Structure

```
university-gate-control-system/
├── config/                 # Configuration files
├── includes/               # Reusable components
├── assets/
│   ├── css/               # Stylesheets
│   ├── js/                # JavaScript files
│   └── images/            # Images and icons
├── modules/               # Feature modules
├── roles/                 # Role-specific dashboards
├── actions/               # Form processors
├── ajax/                  # AJAX endpoints
├── database/              # Database schema and migrations
└── logs/                  # Application logs
```

## Security Features

- Password hashing with bcrypt
- CSRF token protection
- SQL injection prevention (PDO prepared statements)
- XSS protection
- Session security
- Input validation
- Role-based access control
- Audit logging

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)

## License

Proprietary - University Use Only

## Support

For technical support, contact: security@university.edu

---
Version: 1.0.0
Last Updated: 2024
