import { useEffect, useState } from 'react';
// Importaciones nombradas desde 'date-fns'
import { format, isValid } from 'date-fns';

const RoomList = () => {
    const [rooms, setRooms] = useState([]);
    const [error, setError] = useState(null);

    const fetchRooms = async () => {
        try {
            const response = await fetch('http://localhost/Tamwood-hotel/api/room-type');
            
            if (!response.ok) {
                throw new Error('Error fetching rooms');
            }

            const data = await response.json();
            if (data.error) {
                throw new Error(data.error);
            }

            setRooms(data.data); // Asegurarse de que la estructura de la respuesta coincida
        } catch (error) {
            setError(error.message);
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
                            <td colSpan="7">No rooms available</td>
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
