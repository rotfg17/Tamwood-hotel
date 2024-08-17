const express = require('express');
const mysql = require('mysql');
const cors = require('cors');

const app = express();
const PORT = 3006;

app.use(cors()); // Habilita CORS

const DB = mysql.createConnection({
    host: 'localhost',
    user: 'hotelres',
    password: '',
    database: 'hotel_reservation',
});

DB.connect((err) => {
    if (err) {
        throw err;
    }
    console.log('Connected to database');
});

// Ruta para obtener habitaciones
app.get('/api/rooms', (req, res) => {
    const SQL_QUERY = "SELECT * FROM rooms";
    DB.query(SQL_QUERY, (err, results) => {
        if (err) {
            throw err;
        }
        res.json(results);
    });
});

// Ruta para obtener servicios
app.get('/api/services', (req, res) => {
    const SQL_QUERY = "SELECT * FROM services";
    DB.query(SQL_QUERY, (err, results) => {
        if (err) {
            throw err;
        }
        res.json(results);
    });
});

// Ruta para obtener reservas
app.get('/api/bookings', (req, res) => {
    const SQL_QUERY = "SELECT * FROM bookings";
    DB.query(SQL_QUERY, (err, results) => {
        if (err) {
            throw err;
        }
        res.json(results);
    });
});

// Ruta para obtener clientes
app.get('/api/customers', (req, res) => {
    const SQL_QUERY = "SELECT * FROM customers";
    DB.query(SQL_QUERY, (err, results) => {
        if (err) {
            throw err;
        }
        res.json(results);
    });
});

// Inicializar el servidor
app.listen(PORT, () => {
    console.log(`Servidor iniciado en el puerto http://localhost:${PORT}`);
});
