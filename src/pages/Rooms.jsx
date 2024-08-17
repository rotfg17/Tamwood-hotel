import { useEffect, useState } from 'react';

const Rooms = () => {
    const [rooms, setRooms] = useState([]);

    const fetchRooms = async () => {
        try {
            const response = await fetch('http://localhost:3006/api/rooms');
            if (!response.ok) {
                throw new Error('Error al obtener los datos del servidor');
            }
            const data = await response.json();
            setRooms(data);
        } catch (error) {
            console.error('Error al obtener las rooms:', error);
        }
    };

    useEffect(() => {
        fetchRooms();
    }, []);

    return (
        <div className="Rooms">
            <h1>Rooms</h1>
            <select className="custom-select">
                <option value="" disabled>Select room</option>
                {rooms.map((room, index) => (
                    <option key={index} value={room.room_type}>{room.room_type}</option>
                ))}
            </select>
        </div>
    );
};

export default Rooms;
