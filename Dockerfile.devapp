# Choose the base image
FROM node:20

# Set the APP_ENV argument with a default value of 'dev'
ARG APP_ENV=dev

# Set the working directory
WORKDIR /app

# Copy the application files
COPY client .

# Install the dependencies
RUN npm install

EXPOSE 5173

# Start the development server
CMD ["npm", "run", "dev"]
