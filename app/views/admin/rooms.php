<?php
$pageTitle = 'Manage Rooms - Room Rental';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' rx='20' fill='%23f0f7fa'/><path d='M50 25L20 50v25a5 5 0 005 5h15v-15a5 5 0 015-5h10a5 5 0 015 5v15h15a5 5 0 005-5V50L50 25z' fill='%235a7858'/></svg>">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        sage: { 50: '#f4f7f4', 100: '#e4ebe4', 200: '#c9d9c9', 300: '#a3bfa3', 400: '#7a9f7a', 500: '#4a7c59', 600: '#3d6549', 700: '#33523d', 800: '#2b4333', 900: '#24382b' },
                        pale: { 50: '#f0f7ff', 100: '#e0efff', 200: '#baddff', 300: '#7cc2ff', 400: '#36a3ff', 500: '#0c87eb', 600: '#006bc9', 700: '#0055a3', 800: '#054986', 900: '#0a3d6f' }
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gradient-to-br from-sage-600 via-sage-700 to-pale-600 min-h-screen">
    <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

    <div class="ml-64 p-8">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-white">Room Management</h1>
                <p class="text-sage-200">Add, edit, and manage rooms</p>
            </div>
            <button onclick="openModal('addRoomModal')" class="flex items-center px-6 py-3 bg-gradient-to-r from-sage-500 to-sage-600 text-white rounded-xl hover:from-sage-600 hover:to-sage-700 transition shadow-lg">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add Room
            </button>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <!-- Rooms Table -->
        <div class="bg-gradient-to-br from-sage-50 to-pale-50 rounded-xl shadow-lg overflow-hidden">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-sage-100 to-pale-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Room</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Type</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Price/Night</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Capacity</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (empty($rooms)): ?>
                        <tr><td colspan="6" class="px-6 py-12 text-center text-gray-500">No rooms found. Click "Add Room" to create one.</td></tr>
                    <?php else: ?>
                        <?php foreach ($rooms as $room): ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <img src="<?php echo !empty($room['photo_url']) ? htmlspecialchars($room['photo_url']) : 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=100'; ?>" 
                                             class="w-14 h-14 rounded-lg object-cover shadow-md">
                                        <div class="ml-4">
                                            <p class="font-semibold text-gray-800"><?php echo htmlspecialchars($room['name'] ?? 'Room ' . $room['room_number']); ?></p>
                                            <p class="text-sm text-gray-500">Room #<?php echo htmlspecialchars($room['room_number']); ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 bg-sage-100 text-sage-700 text-sm font-medium rounded-full"><?php echo htmlspecialchars($room['room_type'] ?? $room['type'] ?? 'Standard'); ?></span>
                                </td>
                                <td class="px-6 py-4 text-lg font-semibold text-gray-800">₱<?php echo number_format($room['price'] ?? $room['price_per_night'] ?? 0, 2); ?></td>
                                <td class="px-6 py-4 text-gray-600"><?php echo $room['capacity'] ?? 2; ?> guests</td>
                                <td class="px-6 py-4">
                                    <?php $status = strtolower($room['status'] ?? 'available'); ?>
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full <?php echo $status === 'available' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                        <?php echo ucfirst($status); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-2">
                                        <button onclick='editRoom(<?php echo json_encode($room); ?>)' class="p-2 bg-pale-100 text-pale-600 rounded-lg hover:bg-pale-200 transition" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                        <form method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this room?')">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?php echo $room['room_id'] ?? $room['id']; ?>">
                                            <button type="submit" class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition" title="Delete">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Room Modal -->
    <div id="addRoomModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gradient-to-r from-sage-500 to-pale-500 rounded-t-2xl">
                <h2 class="text-xl font-bold text-white">Add New Room</h2>
                <button onclick="closeModal('addRoomModal')" class="text-white/80 hover:text-white"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <form method="POST" enctype="multipart/form-data" class="p-6">
                <input type="hidden" name="action" value="create">
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Room Number *</label>
                            <input type="text" name="room_number" required class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-sage-500 focus:ring-0 transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Room Name</label>
                            <input type="text" name="name" class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-sage-500 focus:ring-0 transition">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Type *</label>
                            <select name="type" required class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-sage-500 focus:ring-0 transition">
                                <option value="Standard">Standard</option>
                                <option value="Deluxe">Deluxe</option>
                                <option value="Suite">Suite</option>
                                <option value="Family">Family</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Capacity *</label>
                            <input type="number" name="capacity" min="1" value="2" required class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-sage-500 focus:ring-0 transition">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Price per Night (₱) *</label>
                        <input type="number" name="price_per_night" step="0.01" required class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-sage-500 focus:ring-0 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" rows="3" class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-sage-500 focus:ring-0 transition"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Amenities</label>
                        <input type="text" name="amenities" placeholder="WiFi, AC, TV, etc." class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-sage-500 focus:ring-0 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Room Image</label>
                        <input type="file" name="image" accept="image/*" class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-sage-500 focus:ring-0 transition">
                    </div>
                </div>
                <div class="flex justify-end space-x-4 mt-6 pt-4 border-t border-gray-100">
                    <button type="button" onclick="closeModal('addRoomModal')" class="px-6 py-2 border-2 border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 transition">Cancel</button>
                    <button type="submit" class="px-6 py-2 bg-gradient-to-r from-sage-500 to-sage-600 text-white rounded-lg hover:from-sage-600 hover:to-sage-700 transition shadow-md">Add Room</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Room Modal -->
    <div id="editRoomModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gradient-to-r from-pale-500 to-sage-500 rounded-t-2xl">
                <h2 class="text-xl font-bold text-white">Edit Room</h2>
                <button onclick="closeModal('editRoomModal')" class="text-white/80 hover:text-white"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
            </div>
            <form method="POST" enctype="multipart/form-data" class="p-6">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" id="edit_id">
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Room Number *</label>
                            <input type="text" name="room_number" id="edit_room_number" required class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-pale-500 focus:ring-0 transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Room Name</label>
                            <input type="text" name="name" id="edit_name" class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-pale-500 focus:ring-0 transition">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Type *</label>
                            <select name="type" id="edit_type" required class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-pale-500 focus:ring-0 transition">
                                <option value="Standard">Standard</option>
                                <option value="Deluxe">Deluxe</option>
                                <option value="Suite">Suite</option>
                                <option value="Family">Family</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Capacity *</label>
                            <input type="number" name="capacity" id="edit_capacity" min="1" required class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-pale-500 focus:ring-0 transition">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Price per Night (₱) *</label>
                            <input type="number" name="price_per_night" id="edit_price" step="0.01" required class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-pale-500 focus:ring-0 transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                            <select name="status" id="edit_status" required class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-pale-500 focus:ring-0 transition">
                                <option value="available">Available</option>
                                <option value="occupied">Occupied</option>
                                <option value="maintenance">Maintenance</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" id="edit_description" rows="3" class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-pale-500 focus:ring-0 transition"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Amenities</label>
                        <input type="text" name="amenities" id="edit_amenities" class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-pale-500 focus:ring-0 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Update Image (optional)</label>
                        <input type="file" name="image" accept="image/*" class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:border-pale-500 focus:ring-0 transition">
                    </div>
                </div>
                <div class="flex justify-end space-x-4 mt-6 pt-4 border-t border-gray-100">
                    <button type="button" onclick="closeModal('editRoomModal')" class="px-6 py-2 border-2 border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 transition">Cancel</button>
                    <button type="submit" class="px-6 py-2 bg-gradient-to-r from-pale-500 to-pale-600 text-white rounded-lg hover:from-pale-600 hover:to-pale-700 transition shadow-md">Update Room</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
            document.getElementById(id).classList.add('flex');
        }
        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
            document.getElementById(id).classList.remove('flex');
        }
        function editRoom(room) {
            document.getElementById('edit_id').value = room.room_id || room.id;
            document.getElementById('edit_room_number').value = room.room_number;
            document.getElementById('edit_name').value = room.name || '';
            document.getElementById('edit_type').value = room.room_type || room.type || 'Standard';
            document.getElementById('edit_capacity').value = room.capacity || 2;
            document.getElementById('edit_price').value = room.price || room.price_per_night || 0;
            document.getElementById('edit_status').value = room.status || 'available';
            document.getElementById('edit_description').value = room.description || '';
            document.getElementById('edit_amenities').value = room.amenities || '';
            openModal('editRoomModal');
        }
        // Close modal on outside click
        document.querySelectorAll('[id$="Modal"]').forEach(modal => {
            modal.addEventListener('click', e => { if (e.target === modal) closeModal(modal.id); });
        });
    </script>
</body>
</html>
