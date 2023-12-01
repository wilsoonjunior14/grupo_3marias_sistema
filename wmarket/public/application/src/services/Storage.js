const userKey = "busquei_user_data_key";

export function storeUserData(userdata) {
    localStorage.setItem(userKey, JSON.stringify(userdata));
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