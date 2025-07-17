# Citus Database Benchmark - PHP Version

This is a PHP implementation of the Citus database benchmark, equivalent to the Node.js TypeScript version.

## Prerequisites

- PHP 8.0 or higher
- Composer
- PostgreSQL with Citus extension
- Docker (optional, for running Citus cluster)

## Installation

1. Install PHP dependencies using Composer:
```bash
composer install
```

2. Copy the environment file and configure your database settings:
```bash
cp .env.example .env
```

3. Update the `.env` file with your database credentials:
```
PGHOST=localhost
PGPORT=5432
PGUSER=postgres
PGPASSWORD=postgres
PGDATABASE=postgres
```

## Usage

### Basic Insert Benchmark
Run the standard benchmark (inserts 100,000 records one by one):
```bash
php src/php/benchmark.php
```

### Batch Insert Benchmark
Run the batch insert benchmark for better performance:
```bash
php src/php/benchmark.php --batch
```

### Custom Record Count
Specify a custom number of records to insert:
```bash
php src/php/benchmark.php --count=50000
```

### Batch Insert with Custom Settings
```bash
php src/php/benchmark.php --batch --count=100000 --batch-size=5000
```

### Test Database Connection
Test if the database connection and Citus extension are working:
```bash
php src/php/benchmark.php --test
```

### Cleanup Database
Truncate the users table:
```bash
php src/php/benchmark.php --cleanup
```

## Docker Setup

If you want to run the Citus cluster using Docker:

1. Start the Citus cluster:
```bash
docker-compose up -d
```

2. Wait for the services to be ready, then run the benchmark:
```bash
php src/php/benchmark.php --test
php src/php/benchmark.php --batch
```

3. Stop the cluster when done:
```bash
docker-compose down
```

## Performance Comparison

The PHP version includes two insertion methods:

1. **Single Insert**: Inserts records one by one (similar to the Node.js version)
2. **Batch Insert**: Groups multiple records in a single query for better performance

### Features

- **Environment Configuration**: Uses `.env` file for database settings
- **Transaction Support**: All inserts are wrapped in transactions
- **Progress Logging**: Shows progress every 1000 records
- **Error Handling**: Proper error handling with rollback on failures
- **Performance Metrics**: Displays total time and records per second
- **Connection Testing**: Verify database and Citus extension availability
- **Cleanup Utility**: Easy table truncation for testing

### Code Structure

- `src/php/benchmark.php`: Main benchmark class and CLI interface
- `composer.json`: PHP dependencies and autoloading configuration
- `.env`: Database configuration (already exists)

### Key Differences from Node.js Version

1. **Language**: PHP instead of TypeScript/Node.js
2. **Database Driver**: PDO (PostgreSQL) instead of node-postgres
3. **Batch Processing**: Added batch insert capability for better performance
4. **CLI Interface**: Command-line options for different operations
5. **Class-based**: Object-oriented approach for better organization

## Benchmarking Results

Run both versions to compare performance between Node.js and PHP implementations.
