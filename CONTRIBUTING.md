# 🤝 Contributing to Citus Database Benchmark

Thank you for your interest in contributing to the Citus Database Benchmark project! This guide will help you get started with contributing.

## 📋 Table of Contents
- [Code of Conduct](#code-of-conduct)
- [Getting Started](#getting-started)
- [Development Setup](#development-setup)
- [Contributing Guidelines](#contributing-guidelines)
- [Pull Request Process](#pull-request-process)
- [Issue Reporting](#issue-reporting)
- [Development Workflow](#development-workflow)

## Code of Conduct

This project adheres to a code of conduct. By participating, you are expected to uphold this code:

- **Be Respectful**: Treat everyone with respect and kindness
- **Be Collaborative**: Work together constructively
- **Be Inclusive**: Welcome contributors from all backgrounds
- **Be Professional**: Maintain a professional and friendly environment

## Getting Started

### Prerequisites
Before contributing, ensure you have:
- Git installed and configured
- Docker and Docker Compose
- Node.js 16+ and npm
- PHP 8.0+ and Composer
- Basic understanding of PostgreSQL and distributed databases

### Fork and Clone
1. Fork the repository on GitHub
2. Clone your fork locally:
```bash
git clone https://github.com/YOUR_USERNAME/Citus-Test.git
cd Citus-Test
```

3. Add the original repository as upstream:
```bash
git remote add upstream https://github.com/sahil28032005/Citus-Test.git
```

## Development Setup

### 1. Environment Setup
```bash
# Copy environment template
cp .env.example .env

# Install Node.js dependencies
npm install

# Install PHP dependencies
composer install
```

### 2. Start Development Environment
```bash
# Start Citus cluster
docker-compose up -d

# Verify setup
php src/php/benchmark.php --test
npm run dev
```

### 3. Verify Everything Works
```bash
# Run quick tests
php src/php/benchmark.php --count=1000
npx ts-node src/benchmark.ts
```

## Contributing Guidelines

### Areas for Contribution

#### 🚀 High Impact Contributions
- **New Language Implementations**: Python, Go, Rust, Java
- **Performance Optimizations**: Better algorithms, connection pooling
- **Monitoring Integration**: Prometheus, Grafana dashboards
- **Advanced Benchmarks**: SELECT, UPDATE, DELETE operations

#### 📚 Documentation Contributions
- **Tutorial Videos**: Setup and usage guides
- **Blog Posts**: Performance analysis, best practices
- **Code Examples**: Real-world use cases
- **API Documentation**: Detailed function documentation

#### 🐛 Bug Fixes and Improvements
- **Performance Issues**: Memory leaks, slow operations
- **Cross-Platform Compatibility**: Windows, macOS, Linux
- **Error Handling**: Better error messages and recovery
- **Test Coverage**: Unit tests, integration tests

### Coding Standards

#### TypeScript/Node.js
```typescript
// Use meaningful variable names
const connectionPool = new Pool();

// Add type annotations
async function insertUsers(count: number): Promise<void> {
    // Function implementation
}

// Use async/await instead of callbacks
try {
    const result = await client.query(sql, params);
} catch (error) {
    console.error('Database error:', error);
}
```

#### PHP
```php
// Follow PSR-12 coding standards
class CitusBenchmark
{
    private PDO $connection;
    
    public function insertUsers(int $count = 100000): void
    {
        // Method implementation
    }
    
    // Use type hints and return types
    private function executeBatch(array $batch): void
    {
        // Implementation
    }
}
```

#### Documentation
```markdown
# Use clear headings and structure
## Section Title
### Subsection

# Include code examples with language hints
```bash
docker-compose up -d
\```

# Use consistent formatting
- **Bold** for emphasis
- `code` for inline code
- > Quotes for important notes
```

## Pull Request Process

### 1. Branch Naming
```bash
# Feature branches
git checkout -b feature/add-python-implementation
git checkout -b feature/prometheus-monitoring

# Bug fix branches
git checkout -b fix/memory-leak-php
git checkout -b fix/connection-timeout

# Documentation branches
git checkout -b docs/setup-guide-improvements
```

### 2. Commit Messages
Follow conventional commit format:
```bash
# Features
git commit -m "feat: add Python benchmark implementation"
git commit -m "feat(php): add batch size optimization"

# Bug fixes
git commit -m "fix: resolve connection pool exhaustion"
git commit -m "fix(docker): correct worker node configuration"

# Documentation
git commit -m "docs: update setup guide for Windows"
git commit -m "docs(readme): add performance comparison table"

# Refactoring
git commit -m "refactor: improve error handling in PHP benchmark"
```

### 3. Pull Request Template
When creating a PR, include:

```markdown
## Description
Brief description of changes made.

## Type of Change
- [ ] Bug fix (non-breaking change which fixes an issue)
- [ ] New feature (non-breaking change which adds functionality)
- [ ] Breaking change (fix or feature that would cause existing functionality to not work as expected)
- [ ] Documentation update

## Testing
- [ ] Tests pass locally
- [ ] New tests added for new functionality
- [ ] Manual testing completed

## Checklist
- [ ] Code follows project coding standards
- [ ] Self-review completed
- [ ] Documentation updated if needed
- [ ] Changes are backwards compatible
```

### 4. Review Process
1. **Automated Checks**: Ensure all CI checks pass
2. **Code Review**: At least one maintainer review required
3. **Testing**: Manual testing for significant changes
4. **Documentation**: Updates must include documentation changes

## Issue Reporting

### Bug Reports
Use the bug report template:
```markdown
**Describe the bug**
A clear description of what the bug is.

**To Reproduce**
Steps to reproduce the behavior:
1. Run command '...'
2. See error

**Expected behavior**
What you expected to happen.

**Environment:**
- OS: [e.g. Windows 10, Ubuntu 20.04]
- PHP Version: [e.g. 8.1]
- Node.js Version: [e.g. 18.16]
- Docker Version: [e.g. 20.10]

**Additional context**
Any other context about the problem.
```

### Feature Requests
```markdown
**Is your feature request related to a problem?**
A clear description of what the problem is.

**Describe the solution you'd like**
A clear description of what you want to happen.

**Describe alternatives you've considered**
Alternative solutions or features you've considered.

**Additional context**
Any other context or screenshots about the feature request.
```

## Development Workflow

### 1. Before Starting Work
```bash
# Sync with upstream
git fetch upstream
git checkout main
git merge upstream/main

# Create feature branch
git checkout -b feature/your-feature-name
```

### 2. During Development
```bash
# Make small, focused commits
git add .
git commit -m "feat: implement initial structure"

# Test your changes
php src/php/benchmark.php --test
npm test

# Push regularly
git push origin feature/your-feature-name
```

### 3. Before Submitting PR
```bash
# Sync with latest main
git fetch upstream
git rebase upstream/main

# Run comprehensive tests
docker-compose up -d
php src/php/benchmark.php --batch --count=10000
npx ts-node src/benchmark.ts

# Clean up commit history if needed
git rebase -i HEAD~3
```

## Testing Guidelines

### Unit Testing
```bash
# PHP tests (if PHPUnit is set up)
composer test

# Node.js tests
npm test
```

### Integration Testing
```bash
# Full stack testing
docker-compose up -d
sleep 10  # Wait for services
php src/php/benchmark.php --test
```

### Performance Testing
```bash
# Benchmark comparison
time php src/php/benchmark.php --batch --count=50000
time npx ts-node src/benchmark.ts
```

## Community and Support

### Getting Help
- **GitHub Discussions**: For questions and general discussion
- **GitHub Issues**: For bug reports and feature requests
- **Documentation**: Check docs/ directory first

### Recognition
Contributors will be:
- Listed in the project contributors
- Mentioned in release notes for significant contributions
- Invited to become maintainers for sustained contributions

## Release Process

### Versioning
We follow [Semantic Versioning](https://semver.org/):
- **MAJOR**: Breaking changes
- **MINOR**: New features (backwards compatible)
- **PATCH**: Bug fixes (backwards compatible)

### Release Checklist
- [ ] All tests passing
- [ ] Documentation updated
- [ ] CHANGELOG.md updated
- [ ] Version bumped in package.json and composer.json
- [ ] Git tag created
- [ ] Release notes published

Thank you for contributing to the Citus Database Benchmark project! Your contributions help make distributed database testing better for everyone.
