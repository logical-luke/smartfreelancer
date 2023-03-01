# Choose the base image
FROM node:18 as build-stage

# Set the APP_ENV argument with a default value of 'prod'
ARG APP_ENV=prod

# Set the working directory
WORKDIR /app

# Copy the package.json and package-lock.json files
COPY package*.json ./

# Install the dependencies
RUN npm install

# Copy the app source code
COPY . .

# Build the app
RUN npm run build

# Choose the base image
FROM nginx:1.21-alpine

# Copy the Nginx configuration file
COPY docker/app/nginx.conf /etc/nginx/conf.d/default.conf

# Copy the built app files from the previous stage
COPY --from=build-stage /app/dist /usr/share/nginx/html

# Set the container to listen on port 80
EXPOSE 80

# Start the Nginx server
CMD ["nginx", "-g", "daemon off;"]
