# 🏗️ Citus Architecture Deep Dive

## Overview
Citus transforms PostgreSQL into a distributed database by extending it with new features. This document explains the architecture and how our benchmark utilizes it.

## Citus Architecture Components

### 1. Coordinator Node (Master)
- **Role**: Query planning, routing, and result aggregation
- **Responsibilities**:
  - Receives all SQL queries from applications
  - Plans distributed query execution
  - Routes queries to appropriate worker nodes
  - Aggregates results from workers
  - Maintains metadata about distributed tables

### 2. Worker Nodes
- **Role**: Data storage and query execution
- **Responsibilities**:
  - Store sharded table data
  - Execute distributed queries locally
  - Return partial results to coordinator
  - Handle local transactions

## Data Distribution Strategies

### Hash Distribution
```sql
-- Our benchmark uses hash distribution on user ID
SELECT create_distributed_table('users', 'id');
```

### Benefits
- **Even Distribution**: Hash function ensures balanced data across workers
- **Parallel Processing**: Queries can run simultaneously on multiple workers
- **Horizontal Scaling**: Add more workers to increase capacity

## Query Execution Flow

1. **Application** sends SQL query to **Coordinator**
2. **Coordinator** parses and plans the distributed query
3. **Coordinator** routes sub-queries to relevant **Workers**
4. **Workers** execute queries on their local shards
5. **Workers** return partial results to **Coordinator**
6. **Coordinator** aggregates and returns final result to **Application**

## Performance Implications

### Insertion Performance
- **Single Inserts**: Each insert requires coordinator routing
- **Batch Inserts**: Multiple rows processed in single transaction
- **Parallel Execution**: Workers process different shards simultaneously

### Network Overhead
- **Inter-node Communication**: Minimal for hash-distributed data
- **Result Aggregation**: Coordinator combines worker results
- **Connection Pooling**: Reduces connection establishment overhead

## Monitoring and Observability

### Key Metrics
- **Query Distribution**: Balanced load across workers
- **Shard Count**: Number of shards per worker
- **Connection Usage**: Active connections per node
- **Query Performance**: Execution time across nodes

### Diagnostic Queries
```sql
-- View active worker nodes
SELECT * FROM citus_get_active_worker_nodes();

-- Check shard distribution
SELECT * FROM pg_dist_shard WHERE logicalrelid = 'users'::regclass;

-- Monitor query statistics
SELECT * FROM citus_stat_statements;
```

## Benchmark Architecture Mapping

Our benchmark specifically tests:
- **Insertion Throughput**: How fast can we insert data
- **Distribution Efficiency**: How well data spreads across workers
- **Transaction Performance**: ACID compliance in distributed environment
- **Connection Scaling**: How connection pooling affects performance

This architecture enables our benchmark to test real-world distributed database scenarios while maintaining ACID properties and providing horizontal scalability.
