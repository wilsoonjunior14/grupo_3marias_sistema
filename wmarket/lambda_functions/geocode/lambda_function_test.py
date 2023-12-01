import unittest
import lambda_function

class GeocodeTest(unittest.TestCase):

    def test_empty_latitude_longitude(self):
        context = None
        event = dict()

        self.assertRaisesRegexp(Exception, "payload not provided.",lambda_function.lambda_handler, event, context)

    def test_empty_longitude(self):
        context = None
        event = dict()
        event["latitude"] = 1.0
        event["longitude"] = None

        self.assertRaisesRegexp(Exception, "longitude invalid.", lambda_function.lambda_handler, event, context)

    def test_empty_latitude(self):
        context = None
        event = dict()
        event["latitude"] = None
        event["longitude"] = 1.0

        self.assertRaisesRegexp(Exception, "latitude invalid.", lambda_function.lambda_handler, event, context)

    def test_invalid_data(self):
        context = None
        event = dict()
        event["latitude"] = 1.0
        event["longitude"] = 1.0

        self.assertRaisesRegexp(Exception, "Country information not provided.", lambda_function.lambda_handler, event, context)

if __name__ == '__main__':
    unittest.main()