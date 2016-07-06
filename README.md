# Nummerplade API

This is a simple library to demonstrate how to use our API.

Find more information on http://www.nummerpladeapi.dk

### Example
````php
$client = new Kameli\NummerpladeApi\Client('API_TOKEN');

$vehicle = $client->vehicleByRegistration('REGISTRATION');
// $vehicle = $client->vehicleByVin('VIN');

$dmr = $client->dmr($vehicle->vehicle_id);
$debt = $client->debt($vehicle->vehicle_id);
$inspections = $client->inspections($vehicle->vehicle_id);
````
