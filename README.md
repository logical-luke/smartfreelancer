# Smartfreelancer

## Description

Smartfreelancer is a project management tool designed for freelancers to manage their tasks, clients, and projects efficiently. It includes features like task tracking, client management, project organization, and revenue estimation.

## Technologies Used

- PHP
- Composer
- TypeScript
- JavaScript
- NPM
- Vue.js
- Docker
- Docker Compose

## Installation

### Prerequisites

- Docker
- Docker Compose

### Steps

1. Clone the repository:
    ```sh
    git clone https://github.com/your-username/Smartfreelancer.git
    cd Smartfreelancer
    ```

2. Start the Docker containers:
    ```sh
    docker-compose up -d
    ```

## Usage

### Running the Project

The containers will automatically run the necessary commands to start the development server.

## Services and Exposed Ports (Host:Container)

- **db**: MySQL database
  - `3306:3306`
- **rabbitmq**: RabbitMQ message broker
  - `5672:5672`
  - `15672:15672`
- **api**: API server
  - `8080:80`
- **app**: Frontend application
  - `5173:5173`

## Contributing

1. Fork the repository.
2. Create a new branch (`git checkout -b feature-branch`).
3. Commit your changes (`git commit -m 'Add some feature'`).
4. Push to the branch (`git push origin feature-branch`).
5. Open a pull request.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.