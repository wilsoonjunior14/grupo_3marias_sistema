
class Location():
    country = ""
    country_code = ""
    region = ""
    region_code = ""
    city = ""

    # The constructor of the class
    def __init__(self, location):
        self.country = location["country"]
        self.country_code = location["country_code"]
        self.region = location["region"]
        self.region_code = location["region_code"]
        self.city = location["county"]

        self.applyInputValidation()

    
    # The input validation
    def applyInputValidation(self):
        if (self.country == None or self.country.__len__ == 0):
            raise Exception('Country information not provided.')
        if (self.country_code == None or self.country_code.__len__ == 0):
            raise Exception('Country Code information not provided.')
        if (self.region == None or self.region.__len__ == 0):
            raise Exception('Region information not provided.')
        if (self.region_code == None or self.region_code.__len__ == 0):
            raise Exception('Region Code information not provided.')
        if (self.city == None or self.city.__len__ == 0):
            raise Exception('City information not provided.')
        