# Service B

## Local Development

### Requirements

- sudo - You might need root permissions depending on your OS configuration.
- Docker Engine and Docker Compose (see https://docs.docker.com/engine/install/ and https://docs.docker.com/compose/install/ for installation);
- Composer (see https://getcomposer.org/download/ for installation);

### Run

1. Move into the `ServiceB` dir if you're not already.
```sh
cd ServiceB/
```
2. Install dependencies with composer:
```sh
composer install
```

3. Bring up the Service B containers:
```sh
docker compose -f setup/docker-compose.yaml up  # you might need sudo permissions to run this command
```

4. Open http://localhost:8081/ in your browser, a welcome page should appear.

## Endpoints

#### POST `/process`

- **Endpoint:** `/process`
- **Method:** POST
- **Description:** This endpoint will validate the given payload, store it and return the generated id.
- **Request Body**:
```
{
    "name": "string"
    "data": "string"
}
Explanation:
    - "name" (required): The endpoint will only accept payloads with name "X" or "Y". Anything else will fail the validation.
    - "data" (required): A string containing the data to store.
```
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
