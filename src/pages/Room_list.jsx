import { useEffect, useState } from 'react';

const RoomList = () => {
    const [rooms, setRooms] = useState([]);
    const [error, setError] = useState(null);
    const [showEditModal, setShowEditModal] = useState(false);
    const [showDeleteModal, setShowDeleteModal] = useState(false);
    const [selectedRoom, setSelectedRoom] = useState(null);
    const [editData, setEditData] = useState({
        room_number: '',
        room_type: '',
        price_per_night: '',
        description: '',
        status: ''
    });

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

            setRooms(data.data);
        } catch (error) {
            setError(error.message);
        }
    };

    const handleEditClick = (room) => {
        setSelectedRoom(room);
        setEditData({
            room_number: room.room_number,
            room_type: room.room_type,
            price_per_night: room.price_per_night,
            description: room.description,
            status: room.status,
        });
        setShowEditModal(true);
    };

    const handleDeleteClick = (room) => {
        setSelectedRoom(room);
        setShowDeleteModal(true);
    };

    const handleEditSave = async () => {
        try {
            const response = await fetch('http://localhost/Tamwood-hotel/api/update-room', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(editData)
            });

            if (!response.ok) {
                throw new Error('Error updating room');
            }

            const data = await response.json();
            if (data.error) {
                throw new Error(data.error);
            }

            setShowEditModal(false);
            fetchRooms(); // Refrescar la lista de habitaciones después de la edición
        } catch (error) {
            setError(error.message);
        }
    };

    const handleDeleteConfirm = async () => {
        try {
            const response = await fetch('http://localhost/Tamwood-hotel/api/delete-room', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ room_number: selectedRoom.room_number })
            });

            if (!response.ok) {
                throw new Error('Error deleting room');
            }

            const data = await response.json();
            if (data.error) {
                throw new Error(data.error);
            }

            setShowDeleteModal(false);
            fetchRooms(); // Refrescar la lista de habitaciones después de la eliminación
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
            <table className="table transaction-table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Room Number</th>
                        <th>Room Type</th>
                        <th>Price</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Action</th>
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
                                    <button onClick={() => handleEditClick(room)}>Edit</button>
                                    {' '}
                                    <button onClick={() => handleDeleteClick(room)}>Delete</button>
                                </td>
                            </tr>
                        ))
                    )}
                </tbody>
            </table>

            {/* Modal para editar */}
            {showEditModal && (
                <div className="modal">
                    <div className="modal-content">
                        <span className="close" onClick={() => setShowEditModal(false)}>&times;</span>
                        <h2>Edit Room</h2>
                        <form>
                            <div>
                                <label>Room Number:</label>
                                <input
                                    type="text"
                                    value={editData.room_number}
                                    onChange={(e) => setEditData({ ...editData, room_number: e.target.value })}
                                    disabled
                                />
                            </div>
                            <div>
                                <label>Room Type:</label>
                                <input
                                    type="text"
                                    value={editData.room_type}
                                    onChange={(e) => setEditData({ ...editData, room_type: e.target.value })}
                                />
                            </div>
                            <div>
                                <label>Price per Night:</label>
                                <input
                                    type="text"
                                    value={editData.price_per_night}
                                    onChange={(e) => setEditData({ ...editData, price_per_night: e.target.value })}
                                />
                            </div>
                            <div>
                                <label>Description:</label>
                                <textarea
                                    value={editData.description}
                                    onChange={(e) => setEditData({ ...editData, description: e.target.value })}
                                />
                            </div>
                            <div>
                                <label>Status:</label>
                                <select
                                    value={editData.status}
                                    onChange={(e) => setEditData({ ...editData, status: e.target.value })}
                                    required
                                >
                                    <option value="">Select Status</option>
                                    <option value="Available">Available</option>
                                    <option value="Occupied">Occupied</option>
                                    <option value="Maintenance">Maintenance</option>
                                </select>
                            </div>
                        </form>
                        <button onClick={handleEditSave}>Save Changes</button>
                        <button onClick={() => setShowEditModal(false)}>Cancel</button>
                    </div>
                </div>
            )}

            {/* Modal para eliminar */}
            {showDeleteModal && (
                <div className="modal">
                    <div className="modal-content">
                        <span className="close" onClick={() => setShowDeleteModal(false)}>&times;</span>
                        <h2>Delete Room</h2>
                        <p>¿Estás seguro que quieres eliminar la habitación {selectedRoom?.room_number}?</p>
                        <button onClick={handleDeleteConfirm}>Confirm</button>
                        <button onClick={() => setShowDeleteModal(false)}>Cancel</button>
                    </div>
                </div>
            )}
        </div>
    );
};

export default RoomList;
