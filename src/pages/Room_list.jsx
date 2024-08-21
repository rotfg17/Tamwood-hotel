import { useEffect, useState } from 'react';
// Importaciones nombradas desde 'date-fns'
import { format, isValid } from 'date-fns';

const RoomList = () => {
    const [rooms, setRooms] = useState([]);
    const [error, setError] = useState(null);

    // Declarar la sessionKey
    const sessionKey = 'tamwood-hotel:)';

    const fetchRooms = async () => {
        try {
            const response = await fetch('http://localhost/Tamwood-hotel/api/room-type', {
                method: 'GET',
                headers: {
                    'session-key': sessionKey,  // Agrega la session-key en los encabezados
                    'Content-Type': 'application/json',
                },
            });

            if (!response.ok) {
                if (response.status === 401) {
                    throw new Error('Session expired, please login again.');
                }
                throw new Error('Error al obtener los datos del servidor');
            }

            const data = await response.json();
            setRooms(data);
        } catch (error) {
            console.error('Error al obtener las habitaciones:', error);
            setError(error.message || 'Failed to load rooms');
        }
    };

    useEffect(() => {
        fetchRooms();
    }, []);

    return (
        <div className="transaction-container">
            <h2>Rooms List</h2>
            {error && <p style={{ color: 'red' }}>{error}</p>}
            <table className="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Room Number</th>
                        <th>Room Type</th>
                        <th>Price</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    {rooms.length === 0 ? (
                        <tr>
                            <td colSpan="6">No rooms available</td>
                        </tr>
                    ) : (
                        rooms.map((room, index) => (
                            <tr key={`${room.room_number}-${index}`}>
                                <td>{room.room_number}</td>
                                <td>{room.room_type}</td>
                                <td>${room.price_per_night}</td>
                                <td>{room.description}</td>
                                <td className={`status-${room.status.toLowerCase()}`}>
                                    {room.status}
                                </td>
                                <td>
                                    {isValid(new Date(room.created_at))
                                        ? format(new Date(room.created_at), "PPP")
                                        : "Invalid Date"}
                                </td>
                            </tr>
                        ))
                    )}
                </tbody>
            </table> 
        </div>
    );
};

export default RoomList;
