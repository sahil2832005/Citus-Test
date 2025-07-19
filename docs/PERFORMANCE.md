# 📊 Performance Benchmarking Guide

## Benchmark Overview

This guide provides detailed information about performance testing with our Citus benchmark suite.

## Testing Methodology

### Benchmark Types

#### 1. Throughput Testing
- **Objective**: Measure maximum records per second
- **Method**: Time large batch insertions
- **Metrics**: Records/second, total execution time

#### 2. Latency Testing
- **Objective**: Measure individual operation response time
- **Method**: Single insert operations with timing
- **Metrics**: Average, median, 95th percentile response times

#### 3. Scalability Testing
- **Objective**: Performance across different data volumes
- **Method**: Tests from 1K to 1M+ records
- **Metrics**: Performance degradation curves

## Performance Comparison Matrix

### Implementation Comparison

| Metric | Node.js (Single) | PHP (Single) | PHP (Batch) |
|--------|------------------|--------------|-------------|
| **Setup Complexity** | Low | Low | Low |
| **Memory Usage** | 50-100MB | 30-80MB | 40-120MB |
| **CPU Usage** | Medium | Low-Medium | Medium-High |
| **Throughput (10K)** | 1K-3K rps | 2K-5K rps | 8K-20K rps |
| **Throughput (100K)** | 1K-3K rps | 2K-5K rps | 10K-50K rps |
| **Best Use Case** | Real-time | Web apps | Bulk loading |

### Hardware Impact

#### Minimum Specifications
- **CPU**: 2 cores, 2.0 GHz
- **RAM**: 4GB
- **Storage**: SSD recommended
- **Network**: 100 Mbps

#### Recommended Specifications
- **CPU**: 4+ cores, 3.0 GHz
- **RAM**: 8GB+
- **Storage**: NVMe SSD
- **Network**: 1 Gbps

#### High-Performance Specifications
- **CPU**: 8+ cores, 3.5 GHz+
- **RAM**: 16GB+
- **Storage**: High-speed NVMe
- **Network**: 10 Gbps

## Benchmark Scenarios

### Scenario 1: Development Testing
```bash
# Quick validation - 1K records
php src/php/benchmark.php --count=1000
```
**Expected Results**:
- Execution time: < 5 seconds
- Throughput: > 200 records/second

### Scenario 2: Integration Testing
```bash
# Medium load - 10K records
php src/php/benchmark.php --batch --count=10000
```
**Expected Results**:
- Execution time: < 10 seconds
- Throughput: > 1,000 records/second

### Scenario 3: Load Testing
```bash
# High load - 100K records
php src/php/benchmark.php --batch --count=100000 --batch-size=2000
```
**Expected Results**:
- Execution time: < 30 seconds
- Throughput: > 3,000 records/second

### Scenario 4: Stress Testing
```bash
# Very high load - 1M records
php src/php/benchmark.php --batch --count=1000000 --batch-size=5000
```
**Expected Results**:
- Execution time: < 300 seconds (5 minutes)
- Throughput: > 3,000 records/second

## Optimization Techniques

### Database Optimizations
```sql
-- Increase checkpoint segments for better write performance
ALTER SYSTEM SET checkpoint_segments = 64;

-- Optimize write-ahead logging
ALTER SYSTEM SET wal_buffers = '16MB';
ALTER SYSTEM SET max_wal_size = '2GB';

-- Tune shared buffers
ALTER SYSTEM SET shared_buffers = '256MB';
```

### Application Optimizations

#### PHP Optimizations
- **Batch Size Tuning**: Optimal range 1000-5000 records
- **Memory Limit**: Set `memory_limit = 512M` for large batches
- **OPcache**: Enable for repeated script execution

#### Node.js Optimizations
- **Connection Pooling**: Increase pool size for concurrent operations
- **V8 Heap**: Set `--max-old-space-size=4096` for large datasets

### Docker Optimizations
```yaml
# docker-compose.yml optimizations
services:
  citus_master:
    deploy:
      resources:
        limits:
          cpus: '2.0'
          memory: 2G
        reservations:
          cpus: '1.0'
          memory: 1G
```

## Monitoring During Benchmarks

### System Metrics
```bash
# Monitor Docker container resources
docker stats --format "table {{.Name}}\t{{.CPUPerc}}\t{{.MemUsage}}"

# Monitor disk I/O
iostat -x 1

# Monitor network usage
iftop
```

### Database Metrics
```sql
-- Monitor active connections
SELECT count(*) FROM pg_stat_activity;

-- Check lock contention
SELECT * FROM pg_locks WHERE NOT granted;

-- Monitor checkpoint activity
SELECT * FROM pg_stat_bgwriter;
```

## Performance Troubleshooting

### Common Performance Issues

#### Slow Insertion Speed
**Symptoms**: < 1,000 records/second
**Solutions**:
- Use batch processing
- Increase batch size
- Check Docker resource allocation
- Verify SSD storage

#### High Memory Usage
**Symptoms**: > 1GB memory consumption
**Solutions**:
- Reduce batch size
- Implement batch chunking
- Increase Docker memory limits

#### Connection Timeouts
**Symptoms**: "Connection refused" errors
**Solutions**:
- Increase connection pool size
- Add connection retry logic
- Check Docker network health

### Performance Regression Testing
```bash
# Automated performance test
#!/bin/bash
echo "Running performance regression tests..."

# Baseline test
time php src/php/benchmark.php --batch --count=10000 > baseline.log 2>&1

# Extract timing
grep "Total time" baseline.log

# Compare with previous results
# (implement comparison logic)
```

## Benchmark Results Interpretation

### Good Performance Indicators
- **Consistent Throughput**: Performance doesn't degrade with data size
- **Low Memory Growth**: Memory usage remains stable
- **Fast Transaction Times**: < 100ms for batch operations
- **Even Distribution**: Data spreads evenly across workers

### Performance Red Flags
- **Decreasing Throughput**: Performance drops significantly with scale
- **Memory Leaks**: Continuously growing memory usage
- **Lock Contention**: High wait times for database locks
- **Uneven Distribution**: Hot spots on specific workers

## Advanced Benchmarking

### Custom Metrics Collection
```php
// Add to benchmark.php for detailed metrics
$memoryBefore = memory_get_usage();
$startTime = microtime(true);

// ... benchmark code ...

$memoryAfter = memory_get_usage();
$endTime = microtime(true);

echo "Memory used: " . ($memoryAfter - $memoryBefore) . " bytes\n";
echo "Peak memory: " . memory_get_peak_usage() . " bytes\n";
echo "Execution time: " . ($endTime - $startTime) . " seconds\n";
```

### Concurrent Testing
```bash
# Run multiple benchmark instances
php src/php/benchmark.php --batch --count=50000 &
php src/php/benchmark.php --batch --count=50000 &
wait
```

This comprehensive approach ensures you get accurate, actionable performance data from your Citus benchmark tests.
