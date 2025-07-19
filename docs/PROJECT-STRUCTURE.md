# 📁 Project Structure

This document describes the organization and structure of the Citus Database Benchmark project.

## Directory Tree

```
Citus-Test/
├── 📁 assects/                    # Assets and media files
│   └── citus.png                  # Citus logo/architecture diagrams
├── 📁 docs/                       # Documentation files
│   ├── ARCHITECTURE.md            # Citus architecture deep dive
│   ├── PERFORMANCE.md             # Performance benchmarking guide
│   └── SETUP.md                   # Complete setup instructions
├── 📁 src/                        # Source code
│   ├── benchmark.ts               # Node.js/TypeScript implementation
│   └── 📁 php/                    # PHP implementation
│       └── benchmark.php          # PHP benchmark class
├── 📄 .env                        # Environment variables (local config)
├── 📄 .gitignore                  # Git ignore patterns
├── 📄 composer.json               # PHP dependencies and autoloading
├── 📄 docker-compose.yml          # Citus cluster configuration
├── 📄 package.json                # Node.js dependencies and scripts
├── 📄 README.md                   # Main project documentation
├── 📄 README-PHP.md               # PHP-specific documentation
├── 📄 run.php                     # Cross-platform CLI runner
├── 📄 run.ps1                     # PowerShell runner script
└── 📄 tsconfig.json               # TypeScript configuration
```

## File Descriptions

### 🔧 Configuration Files

#### `docker-compose.yml`
- **Purpose**: Defines Citus distributed database cluster
- **Components**: Master coordinator + 2 worker nodes
- **Networks**: Internal Docker network for inter-node communication
- **Volumes**: Persistent storage for each database node

#### `.env`
- **Purpose**: Environment configuration for database connections
- **Variables**: Database host, port, credentials, connection settings
- **Security**: Contains sensitive information, excluded from version control

#### `package.json`
- **Purpose**: Node.js project configuration and dependencies
- **Dependencies**: TypeScript, node-postgres, dotenv
- **Scripts**: Development and build commands

#### `composer.json`
- **Purpose**: PHP project configuration and dependencies
- **Dependencies**: PHP 8.0+, PDO PostgreSQL, vlucas/phpdotenv
- **Autoloading**: PSR-4 autoloading for PHP classes

#### `tsconfig.json`
- **Purpose**: TypeScript compiler configuration
- **Target**: ES2020 with Node.js compatibility
- **Paths**: Source file locations and output settings

### 💻 Source Code

#### `src/benchmark.ts`
```typescript
// Node.js/TypeScript Implementation
Features:
- Connection pooling with node-postgres
- Transaction-based insertions
- Progress monitoring
- Error handling and rollback
- Environment variable configuration
```

#### `src/php/benchmark.php`
```php
// PHP Implementation
Features:
- PDO-based database connections
- Class-based architecture
- CLI interface with multiple options
- Batch processing capabilities
- Comprehensive error handling
```

### 📚 Documentation

#### `README.md`
- **Scope**: Complete project overview
- **Content**: Architecture diagrams, setup instructions, usage examples
- **Audience**: Developers, system administrators, contributors

#### `docs/ARCHITECTURE.md`
- **Scope**: Deep dive into Citus architecture
- **Content**: Component roles, data distribution, query flow
- **Audience**: Technical users, database administrators

#### `docs/PERFORMANCE.md`
- **Scope**: Comprehensive benchmarking guide
- **Content**: Testing methodology, optimization techniques, troubleshooting
- **Audience**: Performance engineers, DevOps teams

#### `docs/SETUP.md`
- **Scope**: Step-by-step installation guide
- **Content**: Prerequisites, platform-specific instructions, verification
- **Audience**: New users, deployment teams

### 🚀 Utility Scripts

#### `run.php`
```php
// Cross-platform CLI runner
Commands:
- install: composer install
- test: connection testing
- benchmark: standard benchmark
- batch: batch processing benchmark
- cleanup: database cleanup
- docker-up/down: container management
```

#### `run.ps1`
```powershell
# Windows PowerShell runner
Features:
- Color-coded output
- Error handling
- Help system
- Command validation
```

## Development Workflow

### 1. File Modification Guidelines

#### Adding New Features
1. **Source Code**: Add to appropriate `src/` subdirectory
2. **Documentation**: Update relevant docs files
3. **Tests**: Add test cases if applicable
4. **Configuration**: Update config files if needed

#### Directory Structure for New Components
```
src/
├── benchmark.ts              # Original Node.js implementation
├── php/
│   ├── benchmark.php         # Main PHP implementation
│   ├── classes/              # Additional PHP classes (if needed)
│   └── tests/                # PHP unit tests (if added)
├── python/                   # Future Python implementation
└── shared/                   # Shared utilities/configs
```

### 2. File Naming Conventions

#### Source Files
- **TypeScript**: `camelCase.ts` (e.g., `benchmark.ts`)
- **PHP**: `camelCase.php` (e.g., `benchmark.php`)
- **Classes**: `PascalCase.php` (e.g., `DatabaseConnection.php`)

#### Documentation
- **Main docs**: `UPPERCASE.md` (e.g., `README.md`)
- **Specific guides**: `descriptive-name.md` (e.g., `setup-guide.md`)

#### Configuration
- **Environment**: `.env`, `.env.example`
- **Package managers**: `package.json`, `composer.json`
- **Build tools**: `tsconfig.json`, `webpack.config.js`

### 3. Asset Management

#### Images and Diagrams
- **Location**: `assects/` directory
- **Formats**: PNG, SVG for diagrams; JPG for photos
- **Naming**: Descriptive names (e.g., `citus-architecture.png`)

#### Generated Files
- **Benchmark Results**: Store in `results/` (gitignored)
- **Logs**: Store in `logs/` (gitignored)
- **Temporary**: Store in `tmp/` (gitignored)

## Dependencies Overview

### Node.js Stack
```json
{
  "dependencies": {
    "pg": "PostgreSQL client library",
    "dotenv": "Environment variable loader",
    "@types/pg": "TypeScript definitions for pg"
  },
  "devDependencies": {
    "typescript": "TypeScript compiler",
    "ts-node": "TypeScript execution engine",
    "@types/node": "Node.js type definitions"
  }
}
```

### PHP Stack
```json
{
  "require": {
    "php": ">=8.0",
    "vlucas/phpdotenv": "Environment variable management"
  },
  "require-dev": {
    "phpunit/phpunit": "Testing framework (optional)"
  }
}
```

### Docker Stack
```yaml
services:
  citus_master:
    image: "citusdata/citus:12.1"
    role: "Coordinator node"
  
  citus_worker_1:
    image: "citusdata/citus:12.1"
    role: "Data node 1"
  
  citus_worker_2:
    image: "citusdata/citus:12.1"
    role: "Data node 2"
```

## Future Enhancements

### Planned Additions
1. **`src/python/`**: Python implementation using psycopg2
2. **`src/go/`**: Go implementation using lib/pq
3. **`tests/`**: Comprehensive test suite
4. **`monitoring/`**: Prometheus/Grafana configurations
5. **`scripts/`**: Automation and deployment scripts

### Potential Structure Expansion
```
Citus-Test/
├── benchmarks/               # Different benchmark scenarios
├── configs/                  # Environment-specific configurations
├── monitoring/               # Observability stack
├── scripts/                  # Automation scripts
└── tests/                    # Test suites
    ├── unit/                 # Unit tests
    ├── integration/          # Integration tests
    └── performance/          # Performance test definitions
```

This structure provides a solid foundation for the current implementation while allowing for future growth and additional language implementations.
