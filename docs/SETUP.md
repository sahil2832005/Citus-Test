# 🚀 Complete Setup Guide

## Prerequisites Installation

### Windows Setup

#### 1. Install Docker Desktop
1. Download from [Docker Desktop for Windows](https://desktop.docker.com/win/main/amd64/Docker%20Desktop%20Installer.exe)
2. Run installer and follow setup wizard
3. Enable WSL 2 backend when prompted
4. Restart computer if required

#### 2. Install Node.js
1. Download from [Node.js Official Site](https://nodejs.org/)
2. Choose LTS version (18.x or later)
3. Run installer with default settings
4. Verify installation:
```powershell
node --version
npm --version
```

#### 3. Install PHP
1. Download from [PHP for Windows](https://windows.php.net/download/)
2. Choose "Thread Safe" version for PHP 8.0+
3. Extract to `C:\php`
4. Add `C:\php` to system PATH
5. Copy `php.ini-development` to `php.ini`
6. Enable PostgreSQL extension in `php.ini`:
```ini
extension=pdo_pgsql
extension=pgsql
```

#### 4. Install Composer
1. Download from [Composer Setup](https://getcomposer.org/Composer-Setup.exe)
2. Run installer and follow wizard
3. Verify installation:
```powershell
composer --version
```

### Linux/macOS Setup

#### Ubuntu/Debian
```bash
# Update package list
sudo apt update

# Install Docker
curl -fsSL https://get.docker.com -o get-docker.sh
sh get-docker.sh
sudo usermod -aG docker $USER

# Install Docker Compose
sudo apt install docker-compose

# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt-get install -y nodejs

# Install PHP
sudo apt install php8.1 php8.1-pgsql php8.1-mbstring php8.1-xml

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

#### macOS (using Homebrew)
```bash
# Install Homebrew if not already installed
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"

# Install required packages
brew install docker docker-compose node php composer

# Start Docker Desktop
open /Applications/Docker.app
```

## Project Setup

### 1. Clone Repository
```bash
git clone https://github.com/sahil28032005/Citus-Test.git
cd Citus-Test
```

### 2. Environment Configuration
```bash
# Copy environment template (if it doesn't exist)
cp .env.example .env

# Edit .env file with your preferences
# Default values should work for local development
```

### 3. Install Dependencies

#### Node.js Dependencies
```bash
npm install
```

#### PHP Dependencies
```bash
composer install
```

## Docker Setup

### 1. Start Citus Cluster
```bash
# Start all services in background
docker-compose up -d

# Check if all containers are running
docker-compose ps
```

Expected output:
```
NAME                STATUS              PORTS
citus_master        Up 2 minutes        0.0.0.0:5432->5432/tcp
citus_worker_1      Up 2 minutes        5432/tcp
citus_worker_2      Up 2 minutes        5432/tcp
```

### 2. Initialize Citus Cluster
```bash
# Connect to master and add workers
docker-compose exec citus_master psql -U postgres -c "
SELECT * FROM citus_add_node('citus_worker_1', 5432);
SELECT * FROM citus_add_node('citus_worker_2', 5432);
"

# Verify workers are added
docker-compose exec citus_master psql -U postgres -c "
SELECT * FROM citus_get_active_worker_nodes();
"
```

### 3. Create and Distribute Tables
```bash
# Create users table and distribute it
docker-compose exec citus_master psql -U postgres -c "
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE
);

SELECT create_distributed_table('users', 'id');
"
```

## Verification Steps

### 1. Test Database Connection
```bash
# Using PHP benchmark
php src/php/benchmark.php --test

# Using Node.js
npm run dev
```

### 2. Quick Benchmark Test
```bash
# Run small test with PHP
php src/php/benchmark.php --count=1000

# Run Node.js test
npx ts-node src/benchmark.ts
```

### 3. Verify Citus Distribution
```bash
# Check shard distribution
docker-compose exec citus_master psql -U postgres -c "
SELECT 
    shardid, 
    nodename, 
    nodeport 
FROM pg_dist_shard_placement 
WHERE logicalrelid = 'users'::regclass;
"
```

## Common Setup Issues

### Issue 1: Docker Permission Denied (Linux)
```bash
# Add user to docker group
sudo usermod -aG docker $USER
# Logout and login again, or run:
newgrp docker
```

### Issue 2: Port 5432 Already in Use
```bash
# Check what's using the port
sudo netstat -tulpn | grep 5432

# Stop local PostgreSQL if running
sudo systemctl stop postgresql

# Or change port in docker-compose.yml
ports:
  - "5433:5432"  # Changed from 5432:5432
```

### Issue 3: PHP Extensions Missing
```bash
# Ubuntu/Debian
sudo apt install php-pgsql php-mbstring

# CentOS/RHEL
sudo yum install php-pgsql php-mbstring

# Windows - Edit php.ini and uncomment:
extension=pdo_pgsql
extension=pgsql
```

### Issue 4: Node.js Version Issues
```bash
# Check Node.js version
node --version

# Update Node.js if below version 16
# Use Node Version Manager (nvm)
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.0/install.sh | bash
nvm install --lts
nvm use --lts
```

## Development Workflow

### 1. Daily Development
```bash
# Start services
docker-compose up -d

# Run quick test
php src/php/benchmark.php --test

# Develop and test changes
# ... your development work ...

# Stop services when done
docker-compose down
```

### 2. Performance Testing Session
```bash
# Start with clean state
docker-compose down -v  # Remove volumes
docker-compose up -d

# Initialize cluster
# ... run initialization commands ...

# Run comprehensive benchmarks
php src/php/benchmark.php --batch --count=100000
npx ts-node src/benchmark.ts

# Analyze results
# ... review performance metrics ...
```

### 3. Debugging Session
```bash
# View container logs
docker-compose logs citus_master
docker-compose logs citus_worker_1

# Access container shell
docker-compose exec citus_master bash

# Connect to database directly
docker-compose exec citus_master psql -U postgres

# Monitor resource usage
docker stats
```

## IDE Setup

### VS Code Extensions
Install these recommended extensions:
- **PHP Intelephense**: PHP language support
- **TypeScript Importer**: TypeScript auto-imports
- **Docker**: Docker container management
- **PostgreSQL**: Database query support
- **Git Graph**: Visual git history

### VS Code Settings
Add to `.vscode/settings.json`:
```json
{
    "typescript.preferences.includePackageJsonAutoImports": "auto",
    "php.validate.executablePath": "/path/to/php",
    "docker.attachShellCommand.linuxContainer": "/bin/bash",
    "files.associations": {
        "*.env": "dotenv"
    }
}
```

## Backup and Restore

### Create Backup
```bash
# Backup entire database
docker-compose exec citus_master pg_dump -U postgres postgres > backup.sql

# Backup only users table
docker-compose exec citus_master pg_dump -U postgres -t users postgres > users_backup.sql
```

### Restore Backup
```bash
# Restore database
docker-compose exec -T citus_master psql -U postgres postgres < backup.sql

# Restore specific table
docker-compose exec -T citus_master psql -U postgres postgres < users_backup.sql
```

## Security Considerations

### Development Environment
- Use default passwords only in development
- Bind database to localhost only
- Don't expose worker nodes externally

### Production Checklist
- [ ] Change default passwords
- [ ] Use environment variables for secrets
- [ ] Configure firewall rules
- [ ] Enable SSL/TLS connections
- [ ] Set up monitoring and alerting
- [ ] Configure backup strategy

This setup guide should get you up and running with the Citus benchmark environment. If you encounter any issues not covered here, check the troubleshooting section in the main README or create an issue on GitHub.
