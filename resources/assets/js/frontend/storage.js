/*
    sessionStorage: sẽ bị xóa khi đóng tab
*/

function setStorage(key, value) {
    if ( typeof(Storage) !== 'undefined') {
        // Khởi tạo sesionStorage
        sessionStorage.setItem(key, value);
    } else {
        console.error('Browser not support!');
    }
}

function getStorage(key) {
    if ( typeof(Storage) !== 'undefined') {
        // get sessionStorage
        return sessionStorage.getItem(key);
    } else {
        console.error('Browser not support!');
    }
}

function getStorageLength() {
    if ( typeof(Storage) !== 'undefined') {
        // lấy ra số lượng session đã lưu trữ
        return sessionStorage.length;
    } else {
        console.error('Browser not support!');
    }
}

function removeStorage(key) {
    if ( typeof(Storage) !== 'undefined') {
        // xóa 1 item localStorage
        sessionStorage.removeItem(key);
    } else {
        console.error('Browser not support!');
    }
}

function removeAllStorage() {
    if ( typeof(Storage) !== 'undefined') {
        // xóa tất cả item trong sessionStorage
        sessionStorage.clear();
    } else {
        console.error('Browser not support!');
    }
}

export default { 
    setStorage,
    getStorage,
    getStorageLength,
    removeStorage,
    removeAllStorage
}