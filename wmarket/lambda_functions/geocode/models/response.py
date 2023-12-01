from .location import Location

class Response(): 
    data = []
    location = None

    # Constructor of the class.
    def __init__(self, data):
        self.data = data
        self.location = Location(data[0])
        self.applyInputValidation()



    # Validates the data location found.
    def applyInputValidation(self):
        if (self.data == None):
            raise Exception('No data found. Its invalid.')
        if (self.data.__len__ == 0):
            raise Exception('No data found.')
        if (self.location == None):
            raise Exception('No location found')
        
