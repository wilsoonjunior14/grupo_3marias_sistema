const userKey = "3marias_user_data_key";

export function storeUserData(userdata) {
    localStorage.setItem(userKey, JSON.stringify(userdata));
}

export function removeUserData() {
    localStorage.removeItem(userKey);
}

export function isLogged() {
    const data = retrieveUserData();
    if (!data) {
        return false;
    }
    return true;
}

export function retrieveUserData() {
    const data = localStorage.getItem(userKey);
    if (!data) {
        return null;
    }
    return JSON.parse(data);
}

export function hasPermission(permission) {
    const userdata = retrieveUserData();
    if (userdata) {
        if (userdata.user.group.description.toString().toUpperCase() === permission.toString().toUpperCase()) {
            return true;
        }
    }
    return false;
}