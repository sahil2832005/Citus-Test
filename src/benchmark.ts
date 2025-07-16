import { Pool } from 'pg';

import dotenv from 'dotenv';
dotenv.config();

const pool = new Pool(); // automatically uses env vars

async function insertUsers(): Promise<void> {
    const client = await pool.connect();
    console.log("pool connection successful...");
    console.time('InsertTime');
    await client.query('BEGIN');

    try {
        const insertQuery = 'INSERT INTO users (id, name, email) VALUES ($1, $2, $3)';
        for (let i = 1; i <= 100_000; i++) {
            const values = [i, `User${i}`, `user${i}@test.com`];
            await client.query(insertQuery, values);

            if (i % 1000 === 0) {
                console.log(`Inserted: ${i}`);
            }
        }
        await client.query('COMMIT');
        console.timeEnd('InsertTime');
    }
    catch (err) {
        await client.query('ROLLBACK');
        console.error('Error during insert:', err);
    }
    finally {
        client.release();
        await pool.end();
        console.log("pool connection released...");
    }

}

//test call
insertUsers();