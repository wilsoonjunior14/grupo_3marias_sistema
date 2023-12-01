# -------------------------------------
# | BusqueiAppGeocodeLocationFunction |
# -------------------------------------
# 
# Overview: this lambda function aims to detect the latitude and longitude provided in order to 
# discover from the positionstack API the exact location which the user provided.
#
# How Setup
# pip3 install -r /path/requirements.txt -t /path/dependencies
# e.g. pip3 install -r /Users/wilson/Documents/projects/wmarket/wmarket/lambda_functions/geocode/requirements.txt 
# -t /Users/wilson/Documents/projects/wmarket/wmarket/lambda_functions/geocode/dependencies
#
# How check the size in bytes on pipeline
# e.g. stat -f %z  geocode.zip
# How zip the file
# e.g. zip -r geocode.zip geocode
#
# References
# 1. How deploy the lambda function: https://awstip.com/how-to-add-external-python-libraries-to-aws-lambda-baa8cb8dd7e1

from geocode import Geocode
import json

def input_validation(event):
    if (not event):
        raise Exception('payload not provided.')
    if (event["latitude"] == None or event["latitude"] == 0):
        raise Exception('latitude invalid.')
    if (event["longitude"] == None or event["longitude"] == 0):
        raise Exception('longitude invalid.')


def lambda_handler(event, context):
    input_validation(event)

    geocodeInstance = Geocode(event["latitude"], event["longitude"])
    geocodeInstance.performRequest()

    location = geocodeInstance.response.location
    print ("country = {}, country_code = {}, region = {}, region_code = {}, city = {}".format(location.country, location.country_code, location.region, location.region_code, location.city))

    return {
        'statusCode': 200,
        'location': json.dumps(location.__dict__)
    }
