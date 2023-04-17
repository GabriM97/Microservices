# Service A

## Local Development

### Requirements

- sudo - You might need root permissions depending on your OS configuration.
- Docker Engine and Docker Compose (see https://docs.docker.com/engine/install/ and https://docs.docker.com/compose/install/ for installation);
- Composer (see https://getcomposer.org/download/ for installation);

### Run

1. Move into the `ServiceA` dir if you're not already.
```sh
cd ServiceA/
```
2. Install dependencies with composer:
```sh
composer install
```

3. Bring up the Service A containers:
```sh
docker compose -f setup/docker-compose.yaml up  # you might need sudo permissions to run this command
```

4. Open http://localhost:8080/ in your browser, a welcome page should appear.

## Endpoints

#### GET `service/run/{payload}`

- **Endpoint:** `/service/run/{payload}`
- **Method:** GET
- **Description:** This endpoint will send an HTTP request to the `ServiceB` using the given payload and returns a 200 response containing a `payload_id`.
- **Parameters:**
    - `{payload}`: string (required) - The payload to use. Accepts only 'X' or 'Y' which are the only two payloads available.
- **Success Response:**
```
HTTP Status Code: 200 OK
Headers:
    - Content-Type: application/json
Body: 
    {
        "data": {
            "payload_id": "string"
        }
    }
Explanation:
    - payload_id: Returns the generated id of the stored payload.
```
- **Error Response:**
```
HTTP Status Code: 400 Bad Request
Headers:
    - Content-Type: application/json
Body: 
    {
        "type": "string",
        "title": "string",
        "detail": "string",
        "status": 400,
        "all_errors": [array]
    }
Explanation:
    - type: A unique identifier for the error.
    - title: A short summary of the error.
    - detail: A detailed explanation of the error.
    - status: The HTTP code of the response.
    - all_errors: An array of error messages, useful when there are multiple errors that occurred during the request.
```

#### GET `/service/run`

- **Endpoint:** `/service/run`
- **Method:** GET
- **Description:** This endpoint will send two HTTP requests to the `ServiceB` using, respectively, the payload "X" and the payload "Y" and return a response containing the response messages of each request.
- **Response:**
```
HTTP Status Code: 
    - `200 OK` - When no errors occurred
    - `400 Bad Request` - When at least one of the requests returned 400.
Headers:
    - Content-Type: application/json
Body:
    {
        "data": {
            "{status_code}": [array]
        }
    }
Explanation:
    The "data" object might contain different `status_code` keys (i.e. "200" and "400"), one for each code returned, and as a value an array containing the response messages of the corresponding `status_code`.
```
