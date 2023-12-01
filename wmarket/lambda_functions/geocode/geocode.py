from dependencies import requests
import json
from models.response import Response

class Geocode() :
    apiKey = "56a559e53f35389a5334fb77bf529906"
    latitude = 0.0
    longitude = 0.0
    response = None

    def __init__(self, latitude, longitude):
        self.latitude = latitude
        self.longitude = longitude

    # Performs the GET request to retrieve information
    def performRequest(self):
        url = "http://api.positionstack.com/v1/reverse?access_key={}&query={},{}".format(self.apiKey, self.latitude, self.longitude)
        results = requests.get(url)
        results = json.loads(results.text)

        self.response = Response(**results)