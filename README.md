# Influencer Collaboration Platform MVP

## Project Description

This project is a Minimum Viable Product (MVP) for an application that allows influencers to advertise their accounts for collaboration opportunities with businesses, aiming to finding influencers and establishing partnerships faster. The backend is built using Symfony 6.4 and PHP 8.1, and the frontend application will be developed using React. The backend handles all functionalities, including user authentication, profile management, and search capabilities through a REST API for frontend communication.

## Requirements

- PHP 8.1
- Composer
- Symfony 6.4
- Docker

## Configuration
Before running application, ensure that you have properly configured your environment variables. Specifically, you need to set up the DATABASE_URL and MAILER_DSN.

## Running the Application
To start the development server, install dependencies, and execute fixtures, use the following command in the main project directory:

make dev

## Running Tests
To execute the tests, run:

vendor/bin/phpunit

## Application Scope
This is a backend-only application. All endpoints are defined in controllers. The frontend, developed in React, will interact with these endpoints through the REST API.

## Testing the Endpoints
For testing the endpoints, the best way is to use Postman. Postman allows you to easily create requests to test and validate the functionality of each endpoint.

## Additional Notes
Ensure Docker is installed and running on your system to use the provided make dev command.
The application uses a REST API for communication between the backend and frontend.
