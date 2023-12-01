import axios from "axios";
import {retrieveUserData} from './Storage';
import config from '../config.json';

export const BASE_URL = config.url + "/api";

function performPost(url, payload, headers) {
    return axios.post(BASE_URL + url, payload, headers);
}

function performDelete(url, headers) {
    return axios.delete(BASE_URL + url, headers);
}

function performPut(url, payload, headers) {
    return axios.put(BASE_URL + url, payload, headers);
}

function performGet(url, headers) {
    return axios.get(BASE_URL + url, headers);
}

function getHeaders() {
    const data = retrieveUserData();
    if (!data) {
        return {};
    }
    return {
        headers: {
            "Accept" : "application/json",
            "Authorization" : data.type + " "+data.access_token
        }
    }
}

function getCustomHeaders() {
    const data = retrieveUserData();
    if (!data) {
        return {};
    }
    return {
        headers: {
            "Content-Type": undefined,
            "Authorization" : data.type + " "+data.access_token
        }
    }
}

export function performLogin(payload) {
    return performPost("/login", payload);
}

export function performRecoveryPassword(payload) {
    return performPost("/users/recovery", payload);
}

export function performGetUsers() {
    return axios.get(BASE_URL + "/v1/users", getHeaders());
}

export function performGetGroups() {
    return axios.get(BASE_URL + "/v1/groups", getHeaders());
}

function doRequest(url, type, payload, headers) {
    if (type === "GET") {
        return performGet(url, headers);
    }
    if (type === "POST") {
        return performPost(url, payload, headers);
    }
    if (type === "DELETE") {
        return performDelete(url, headers);
    }
    if (type === "PUT") {
        return performPut(url, payload, headers);
    }
}

export function performRequest(type, url, payload) {
    const headers = getHeaders();
    return doRequest(url, type, payload, headers);
}

export function performCustomRequest(type, url, payload) {
    const headers = getCustomHeaders();
    return doRequest(url, type, payload, headers);
}

export function performGetCEPInfo(cep) {
    return axios.get("https://viacep.com.br/ws/"+cep+"/json/", getHeaders());
}
