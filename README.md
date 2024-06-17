# Influencer Collaboration Platform MVP

## Project Description

This project is a Minimum Viable Product (MVP) for an application that allows influencers to advertise their accounts for collaboration opportunities with businesses, aiming to finding influencers and establishing partnerships faster. The backend is built using Symfony 6.4 and PHP 8.1, and the frontend application will be developed using React. The backend handles all functionalities, including user authentication, profile management, and search capabilities through a REST API for frontend communication.

## Requirements

- PHP 8.1
- Composer
- Symfony 6.4

## Configuration
Before running application, ensure that you have properly configured your environment variables. Specifically, you need to set up the DATABASE_URL and MAILER_DSN.

## Running the Application
To start the development server, install dependencies, and execute fixtures, use the following command in the main project directory:

make dev

## Running Tests
To execute the tests, run:

vendor/bin/phpunit
