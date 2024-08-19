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
app.post('/api/rooms', (req, res) => {
    const { room_number, room_type, price_per_night, description, image_url,status,create_at,updated_at } = req.body;
    const SQL_QUERY = `INSERT INTO rooms (room_number, room_type, price_per_night, description, image_url,status,create_at,updated_at) VALUES (?, ?, ?, ?, ?,?,?,?)`;

    DB.query(SQL_QUERY, [room_number, room_type, price_per_night, description, image_url,status,create_at,updated_at], (err, results) => {
        if (err) {
            console.error("Error adding room:", err);
            res.status(500).send('Error adding room');
            return;
        }
        res.status(201).send('Room added successfully!');
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
